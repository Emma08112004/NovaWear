<?php

namespace App\Controller;

use App\Entity\Basket;
use App\Entity\Product;
use App\Entity\Order;
use App\Entity\Summary;
use App\Entity\Payment;
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

        Stripe::setApiKey($_SERVER['STRIPE_SECRET_KEY']);

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

            if (!$product) {
                continue; // skip si produit introuvable
            }

            $lineItems[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => $product->getNomProduct(),
                    ],
                    'unit_amount' => $product->getPrixProduct() * 100,
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

        $order = new Order();
        $order->setUser($user);
        $order->setDateOrder(new \DateTime());
        $order->setStatut('payé');
        $em->persist($order);

        foreach ($panier as $item) {
            $product = $em->getRepository(Product::class)->find($item->getProductId());

            if (!$product) {
                continue; // si produit introuvable on skip
            }

            $summary = new Summary();
            $summary->setOrder($order);
            $summary->setProduct($product);
            $summary->setUser($user);
            $summary->setQuantite($item->getQuantity());
            $summary->setPrixProduct($product->getPrixProduct());
            $summary->setDateOrder(new \DateTime());

            $order->addSummary($summary); // important si tu veux accéder à order->summaries
            $em->persist($summary);
            $em->remove($item);

            $totalGlobal += $product->getPrixProduct() * $item->getQuantity();
        }

        $payment = new Payment();
        $payment->setOrder($order);
        $payment->setMontant($totalGlobal);
        $payment->setStatut('effectué');
        $payment->setDatePayment(new \DateTime());
        $em->persist($payment);

        $order->setTotal($totalGlobal);

        $em->flush();

        $summaries = $em->getRepository(Summary::class)->findBy(['order' => $order]);

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