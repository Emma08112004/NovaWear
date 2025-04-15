<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Basket;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BasketService extends AbstractController
{
    public function __construct(
        private ProductRepository $productRepository,
        private EntityManagerInterface $em
    ) {
    }

    public function handleAddToBasket(int $productId, ?string $taille, $user): JsonResponse
    {
        $product = $this->productRepository->find($productId);

        if (!$product) {
            return new JsonResponse(['message' => 'Produit introuvable'], 404);
        }

        if (!$taille) {
            return new JsonResponse(['message' => 'Veuillez sélectionner une taille'], 400);
        }

        if (!$user) {
            return new JsonResponse(['message' => 'Connexion requise'], 403);
        }

        $userId = $user->getId();

        $existingItem = $this->em->getRepository(Basket::class)->findOneBy([
            'productId' => $productId,
            'userId' => $userId,
            'taille' => $taille,
        ]);

        if ($existingItem) {
            $existingItem->setQuantity($existingItem->getQuantity() + 1);
        } else {
            $basket = new Basket();
            $basket->setProductId($productId);
            $basket->setUserId($userId);
            $basket->setQuantity(1);
            $basket->setTaille($taille);
            $this->em->persist($basket);
        }

        $this->em->flush();

        return new JsonResponse(['message' => 'Produit ajouté au panier']);
    }

    public function removeFromBasket(int $id): JsonResponse
    {
        $item = $this->em->getRepository(Basket::class)->find($id);

        if (!$item) {
            return new JsonResponse(['message' => 'Article non trouvé'], 404);
        }

        $this->em->remove($item);
        $this->em->flush();

        return new JsonResponse(['message' => 'Article supprimé du panier']);
    }

    public function renderBasket($user): Response
    {
        $panier = $this->em->getRepository(Basket::class)->findBy([
            'userId' => $user->getId()
        ]);

        $panierData = [];
        $total = 0;

        foreach ($panier as $item) {
            $product = $this->productRepository->find($item->getProductId());
            if (!$product) {
                continue;
            }

            $total += $product->getPrixProduct() * $item->getQuantity();

            $panierData[] = [
                'id' => $item->getId(),
                'product' => $product,
                'quantity' => $item->getQuantity(),
                'taille' => $item->getTaille(),
            ];
        }

        return $this->render('main/basket.html.twig', [
            'panier' => $panierData,
            'total' => $total,
        ]);
    }
}
