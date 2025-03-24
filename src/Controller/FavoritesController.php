<?php

namespace App\Controller;

use App\Repository\FavoritesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\ProductRepository;
use App\Entity\Favorites;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/favoris')]
class FavoritesController extends AbstractController
{
    #[Route('/', name: 'favoris')]
    #[IsGranted('ROLE_USER')]
    public function index(FavoritesRepository $favoritesRepository): Response
    {
        $user = $this->getUser();
        $favorites = $favoritesRepository->findFavoritesByUser($user);
        return $this->render('main/favorites.html.twig', [
            'favorites' => $favorites,
        ]);
    }

    #[Route('/remove/{id}', name: 'remove_favori', methods: ['POST'])]
public function removeFavori($id, EntityManagerInterface $em, ProductRepository $productRepo): JsonResponse
{
    try {
        $user = $this->getUser();
        if (!$user) {
            return new JsonResponse(['success' => false, 'message' => 'Utilisateur non connecté.'], 403);
        }

        $product = $productRepo->find($id);
        if (!$product) {
            return new JsonResponse(['success' => false, 'message' => 'Produit introuvable.'], 404);
        }

        $favori = $em->getRepository(Favorites::class)->findOneBy([
            'user' => $user,
            'product' => $product,
        ]);

        if (!$favori) {
            return new JsonResponse(['success' => false, 'message' => 'Favori non trouvé.'], 404);
        }

        $em->remove($favori);
        $em->flush();

        return new JsonResponse(['success' => true, 'message' => 'Favori supprimé.']);
    } catch (\Throwable $e) {
        return new JsonResponse([
            'success' => false,
            'message' => 'Erreur serveur : ' . $e->getMessage(),
        ], 500);
    }
}



    #[Route('/add/{id}', name: 'add_favorite', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function addFavorite(int $id, ProductRepository $productRepository, EntityManagerInterface $em): JsonResponse
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->json(['message' => 'Connexion requise'], 403);
        }

        $product = $productRepository->find($id);
        if (!$product) {
            return $this->json(['message' => 'Produit introuvable'], 404);
        }

        $existing = $em->getRepository(Favorites::class)->findOneBy([
            'product' => $product,
            'user' => $user,
        ]);

        if ($existing) {
            return $this->json(['message' => 'Déjà dans les favoris'], 200);
        }

        $favori = new Favorites();
        $favori->setUser($user);
        $favori->setProduct($product);
        $em->persist($favori);
        $em->flush();

        return $this->json(['message' => 'Ajouté aux favoris']);
    }
}


