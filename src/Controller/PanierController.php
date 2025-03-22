<?php

namespace App\Controller;

use App\Entity\Basket;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PanierController extends AbstractController
{
    #[Route('/ajout-panier/{id}', name: 'ajout_panier', methods: ['POST'])]
public function ajoutPanier(int $id, Request $request, ProductRepository $productRepository, EntityManagerInterface $em): JsonResponse
{
    $product = $productRepository->find($id);
    if (!$product) {
        return $this->json(['message' => 'Produit introuvable'], 404);
    }

    $taille = $request->request->get('taille');
    if (!$taille) {
        return $this->json(['message' => 'Veuillez sélectionner une taille avant d\'ajouter au panier'], 400);
    }

    $userId = 1; // à adapter si connexion plus tard

    // Cherche s'il existe déjà un article identique dans le panier
    $existingItem = $em->getRepository(Basket::class)->findOneBy([
        'productId' => $id,
        'userId' => $userId,
        'taille' => $taille,
    ]);

    if ($existingItem) {
        $existingItem->setQuantity($existingItem->getQuantity() + 1);
    } else {
        $panier = new Basket();
        $panier->setProductId($id);
        $panier->setUserId($userId);
        $panier->setQuantity(1);
        $panier->setTaille($taille);
        $em->persist($panier);
    }

    $em->flush();

    return $this->json(['message' => 'Produit ajouté au panier']);
}

#[Route('/supprimer-panier/{id}', name: 'supprimer_panier', methods: ['POST'])]
public function supprimerDuPanier(int $id, EntityManagerInterface $em): JsonResponse
{
    $item = $em->getRepository(Basket::class)->find($id);

    if (!$item) {
        return $this->json(['message' => 'Article non trouvé dans le panier'], 404);
    }

    $em->remove($item);
    $em->flush();

    return $this->json(['message' => 'Article supprimé du panier']);
}
}