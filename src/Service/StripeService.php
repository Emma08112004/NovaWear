<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Basket;
use App\Entity\Order;
use App\Entity\Payment;
use App\Entity\Product;
use App\Entity\Summary;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StripeService extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private RequestStack $requestStack,
        private RouterInterface $router
    ) {}

    public function createCheckoutSession($user): Response
    {
        if (!$user) {
            return $this->redirectToRoute('login');
        }

        Stripe::setApiKey($_SERVER['STRIPE_SECRET_KEY']);
        $request = $this->requestStack->getCurrentRequest();
        $domain = $request->getSchemeAndHttpHost();

        $panier = $this->em->getRepository(Basket::class)->findBy(['userId' => $user->getId()]);
        if (count($panier) === 0) {
            $this->addFlash('error', 'Votre panier est vide.');
            return $this->redirectToRoute('basket');
        }

        $lineItems = [];
        foreach ($panier as $item) {
            $product = $this->em->getRepository(Product::class)->find($item->getProductId());
            if (!$product) continue;

            $lineItems[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => ['name' => $product->getNomProduct()],
                    'unit_amount' => (int)($product->getPrixProduct() * 100),
                ],
                'quantity' => $item->getQuantity(),
            ];
        }

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => $domain . $this->router->generate('stripe_success'),
            'cancel_url' => $domain . $this->router->generate('stripe_cancel'),
        ]);

        return new Response('', 303, ['Location' => $session->url]);
    }

    public function handleSuccess($user): Response
    {
        if (!$user) {
            return $this->redirectToRoute('login');
        }

        $panier = $this->em->getRepository(Basket::class)->findBy(['userId' => $user->getId()]);
        if (count($panier) === 0) {
            $this->addFlash('error', 'Votre panier est vide.');
            return $this->redirectToRoute('home');
        }

        $order = (new Order())
            ->setUser($user)
            ->setDateOrder(new \DateTime())
            ->setStatut('payé');

        $this->em->persist($order);
        $totalGlobal = 0;

        foreach ($panier as $item) {
            $product = $this->em->getRepository(Product::class)->find($item->getProductId());
            if (!$product) continue;

            $summary = (new Summary())
                ->setOrder($order)
                ->setProduct($product)
                ->setUser($user)
                ->setQuantite($item->getQuantity())
                ->setPrixProduct((float)$product->getPrixProduct())
                ->setDateOrder(new \DateTime());

            $order->addSummary($summary);
            $this->em->persist($summary);
            $this->em->remove($item);

            $totalGlobal += $product->getPrixProduct() * $item->getQuantity();
        }

        $payment = (new Payment())
            ->setOrder($order)
            ->setMontant((float)$totalGlobal)
            ->setStatut('effectué')
            ->setDatePayment(new \DateTime());

        $this->em->persist($payment);
        $order->setTotal((float)$totalGlobal);
        $this->em->flush();

        $summaries = $this->em->getRepository(Summary::class)->findBy(['order' => $order]);

        return $this->render('main/Summary.html.twig', [
            'summaries' => $summaries,
            'user' => $user,
        ]);
    }
}