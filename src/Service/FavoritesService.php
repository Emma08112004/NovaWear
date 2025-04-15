<?php

namespace App\Service;

use App\Entity\Favorites;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\User\UserInterface;

class FavoritesService
{
    public function __construct(
        private EntityManagerInterface $em,
        private ProductRepository $productRepo
    ) {}

    public function addToFavorites(int $id, ?UserInterface $user): JsonResponse
    {
        if (!$user) {
            return new JsonResponse(['message' => 'Connexion requise'], 403);
        }

        $product = $this->productRepo->find($id);
        if (!$product) {
            return new JsonResponse(['message' => 'Produit introuvable'], 404);
        }

        $existing = $this->em->getRepository(Favorites::class)->findOneBy([
            'product' => $product,
            'user' => $user,
        ]);

        if ($existing) {
            return new JsonResponse(['message' => 'Déjà dans les favoris'], 200);
        }

        $favori = new Favorites();
        $favori->setUser($user);
        $favori->setProduct($product);
        $this->em->persist($favori);
        $this->em->flush();

        return new JsonResponse(['message' => 'Ajouté aux favoris']);
    }

    public function removeFromFavorites(int $id, ?UserInterface $user): JsonResponse
    {
        if (!$user) {
            return new JsonResponse(['success' => false, 'message' => 'Utilisateur non connecté.'], 403);
        }

        $product = $this->productRepo->find($id);
        if (!$product) {
            return new JsonResponse(['success' => false, 'message' => 'Produit introuvable.'], 404);
        }

        $favori = $this->em->getRepository(Favorites::class)->findOneBy([
            'user' => $user,
            'product' => $product,
        ]);

        if (!$favori) {
            return new JsonResponse(['success' => false, 'message' => 'Favori non trouvé.'], 404);
        }

        $this->em->remove($favori);
        $this->em->flush();

        return new JsonResponse(['success' => true, 'message' => 'Favori supprimé.']);
    }
}