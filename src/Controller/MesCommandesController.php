<?php

namespace App\Controller;

use App\Repository\SummaryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MesCommandesController extends AbstractController
{
    #[Route('/mes-commandes', name: 'mes_commandes')]
public function index(SummaryRepository $summaryRepository): Response
{
    $user = $this->getUser();
    $summaries = $summaryRepository->findByUser($user);

    $commandes = [];

    foreach ($summaries as $summary) {
        $order = $summary->getOrder();
        $orderId = $order->getId();

        if (!isset($commandes[$orderId])) {
            $commandes[$orderId] = [
                'date' => $order->getDateOrder(),
                'produits' => []
            ];
        }

        $commandes[$orderId]['produits'][] = $summary;
    }

    return $this->render('main/mes_commandes.html.twig', [
        'commandes' => $commandes,
    ]);
}
}