<?php

namespace App\Controller;

use App\Entity\Basket;
use App\Entity\Order;
use App\Entity\Payment;
use App\Entity\Product;
use App\Entity\Summary;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    #[Route('/paiement', name: 'paiement', methods: ['GET'])]
    public function paiement(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('connexion');
        }

        // Affiche juste la page avec le bouton Stripe
        return $this->render('main/paiement.html.twig');
    }

    #[Route('/stripe-success', name: 'stripe_success', methods: ['GET'])]
    public function stripeSuccess(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('connexion');
        }

        $panier = $em->getRepository(Basket::class)->findBy(['userId' => $user->getId()]);
        if (count($panier) === 0) {
            $this->addFlash('error', 'Votre panier est vide.');
            return $this->redirectToRoute('panier');
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
            $payment->setOrder(end($orders)); // optionnel : ou associer à chaque Order
            $payment->setMontant($totalGlobal);
            $payment->setStatut('effectué');
            $payment->setDatePayment(new \DateTime());

            $em->persist($payment);
        }

        $em->flush();

        $this->addFlash('success', 'Paiement effectué avec succès !');
        return $this->redirectToRoute('recapitulatif');
    }

    #[Route('/stripe-cancel', name: 'stripe_cancel')]
    public function stripeCancel(): Response
    {
        $this->addFlash('error', 'Le paiement a été annulé.');
        return $this->redirectToRoute('paiement');
    }
}