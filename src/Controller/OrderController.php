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
    #[Route('/paiement', name: 'paiement', methods: ['GET', 'POST'])]
    public function paiement(Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('connexion');
        }

        if ($request->isMethod('POST')) {
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
                $order->setStatut('en attente');
                $order->setDateOrder(new \DateTime());

                $em->persist($order);
                $orders[] = $order;

                // Crée un résumé (summary) pour cette ligne de commande
                $summary = new Summary();
                $summary->setOrder($order);
                $summary->setProduct($product);
                $summary->setQuantite($item->getQuantity());
                $summary->setPrixProduct($product->getPrixProduct());
                $summary->setDateOrder(new \DateTime());

                $em->persist($summary);

                // Supprime du panier
                $em->remove($item);
                
                $totalGlobal += $order->getTotal();
            }

            // Créer un paiement pour la dernière commande seulement
            if (!empty($orders)) {
                $payment = new Payment();
                $payment->setOrder(end($orders));
                $payment->setMontant($totalGlobal);
                $payment->setStatut('effectué');
                $payment->setDatePayment(new \DateTime());

                $em->persist($payment);
            }

            $em->flush();

            $this->addFlash('success', 'Paiement effectué avec succès !');
            return $this->redirectToRoute('recapitulatif');
        }

        return $this->render('main/paiement.html.twig');
    }
}
