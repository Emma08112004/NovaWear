<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\FavoritesRepository;
use App\Service\FavoritesService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/favoris')]
class FavoritesController extends AbstractController
{
    #[Route('/', name: 'favoris')]
    #[IsGranted('ROLE_USER')]
    public function index(FavoritesRepository $favoritesRepository): Response
    {
        return $this->render('main/favorites.html.twig', [
            'favorites' => $favoritesRepository->findFavoritesByUser($this->getUser()),
        ]);
    }

    #[Route('/add/{id}', name: 'add_favorite', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function add(int $id, FavoritesService $favoritesService): JsonResponse
    {
        return $favoritesService->addToFavorites($id, $this->getUser());
    }

    #[Route('/remove/{id}', name: 'remove_favori', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function remove(int $id, FavoritesService $favoritesService): JsonResponse
    {
        return $favoritesService->removeFromFavorites($id, $this->getUser());
    }
}
