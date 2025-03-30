<?php

namespace App\Controller;

use App\Entity\Basket;
use App\Entity\Product;
use App\Entity\Order;
use App\Entity\Summary;
use App\Entity\Payment;
use App\Repository\SummaryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;

class StripeController extends AbstractController
{
    #[Route('/create-checkout-session', name: 'stripe_checkout', methods: ['POST'])]
    public function checkout(RequestStack $requestStack, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('connexion');
        }

        Stripe::setApiKey($_SERVER['STRIPE_SECRET_KEY']); // ou $_ENV selon ton setup

        $request = $requestStack->getCurrentRequest();
        $domain = $request->getSchemeAndHttpHost();

        $panier = $em->getRepository(Basket::class)->findBy(['userId' => $user->getId()]);
        if (count($panier) === 0) {
            $this->addFlash('error', 'Votre panier est vide.');
            return $this->redirectToRoute('panier');
        }

        $lineItems = [];

        foreach ($panier as $item) {
            $product = $em->getRepository(Product::class)->find($item->getProductId());

            $lineItems[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => $product->getNomProduct(),
                    ],
                    'unit_amount' => $product->getPrixProduct() * 100, // Stripe attend les centimes
                ],
                'quantity' => $item->getQuantity(),
            ];
        }

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => $domain . $this->generateUrl('stripe_success'),
            'cancel_url' => $domain . $this->generateUrl('stripe_cancel'),
        ]);

        return $this->redirect($session->url, 303);
    }

    #[Route('/paiement-success', name: 'stripe_success')]
public function success(EntityManagerInterface $em): Response
{
    $user = $this->getUser();

    $panier = $em->getRepository(Basket::class)->findBy(['userId' => $user->getId()]);
    if (count($panier) === 0) {
        $this->addFlash('error', 'Votre panier est vide.');
        return $this->redirectToRoute('home');
    }

    $totalGlobal = 0;
    $orders = [];

    foreach ($panier as $item) {
        $product = $em->getRepository(Product::class)->find($item->getProductId());

        $order = new Order();
        $order->setUser($user);
        $order->setProduct($product);
        $order->setTotal($product->getPrixProduct() * $item->getQuantity());
        $order->setStatut('payé');
        $order->setDateOrder(new \DateTime());

        $em->persist($order);
        $orders[] = $order;

        $summary = new Summary();
        $summary->setOrder($order);
        $summary->setProduct($product);
        $summary->setQuantite($item->getQuantity());
        $summary->setPrixProduct($product->getPrixProduct());
        $summary->setDateOrder(new \DateTime());

        $em->persist($summary);
        $em->remove($item);

        $totalGlobal += $order->getTotal();
    }

    if (!empty($orders)) {
        $payment = new Payment();
        $payment->setOrder(end($orders));
        $payment->setMontant($totalGlobal);
        $payment->setStatut('effectué');
        $payment->setDatePayment(new \DateTime());

        $em->persist($payment);
    }

    $em->flush();

    // Récupérer les Summary de CETTE commande uniquement
    $summaries = $em->getRepository(Summary::class)->findBy([
        'order' => $orders,
    ]);

    return $this->render('stripe/success.html.twig', [
        'summaries' => $summaries,
    ]);
}

    #[Route('/paiement-cancel', name: 'stripe_cancel')]
    public function cancel(): Response
    {
        return $this->render('stripe/cancel.html.twig');
    }
}