<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\BasketService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BasketController extends AbstractController
{
    #[Route('/basket', name: 'basket')]
    public function index(BasketService $basketService): Response
    {
        return $basketService->renderBasket($this->getUser());
    }

    #[Route('/ajout-panier/{id}', name: 'ajout_panier', methods: ['POST'])]
    public function add(int $id, Request $request, BasketService $basketService): JsonResponse
    {
        $taille = $request->request->get('taille');

        return $basketService->handleAddToBasket($id, $taille, $this->getUser());
    }

    #[Route('/supprimer-panier/{id}', name: 'supprimer_panier', methods: ['POST'])]
    public function remove(int $id, BasketService $basketService): JsonResponse
    {
        return $basketService->removeFromBasket($id);
    }
}
