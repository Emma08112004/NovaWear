<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class FavoritesController extends AbstractController
{
    #[Route('/favorites', name: 'app_favorites')]
    public function index(): Response
    {
<<<<<<< Updated upstream
        return $this->render('favorites/index.html.twig', [
            'controller_name' => 'FavoritesController',
        ]);
    }
=======
        $user = $this->getUser(); //recup l'user connecté 
        $favorites = $favoritesRepository->findFavoritesByUser($user); // recup les favoris liés à l'user 
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
            return new JsonResponse(['success' => false, 'message' => 'Utilisateur non connecté.'], 403); //verif si user co sinon erreur 
        }

        $product = $productRepo->find($id);
        if (!$product) {
            return new JsonResponse(['success' => false, 'message' => 'Produit introuvable.'], 404); // cherche produit correspondant à l'ID, si existe pas : erreur 
        }

        $favori = $em->getRepository(Favorites::class)->findOneBy([
            'user' => $user,
            'product' => $product,//on cherche si user/produit est bien ds les fav 
        ]);

        if (!$favori) {
            return new JsonResponse(['success' => false, 'message' => 'Favori non trouvé.'], 404); // si il existe pas, on retourne une erreur 
        }

        $em->remove($favori); // supp le favoris de la bdd 
        $em->flush(); //valide la suppression

        return new JsonResponse(['success' => true, 'message' => 'Favori supprimé.']); 
    } catch (\Throwable $e) {
        return new JsonResponse([
            'success' => false,
            'message' => 'Erreur serveur : ' . $e->getMessage(),
        ], 500); // si erruer inattendu arrive on la bloque pour pas planter le site 
    }
}



    #[Route('/add/{id}', name: 'add_favorite', methods: ['POST'])]// ajouter en favoris 
    #[IsGranted('ROLE_USER')]
    public function addFavorite(int $id, ProductRepository $productRepository, EntityManagerInterface $em): JsonResponse
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->json(['message' => 'Connexion requise'], 403); // verif que l'user connecté 
        }

        $product = $productRepository->find($id);
        if (!$product) {
            return $this->json(['message' => 'Produit introuvable'], 404); // verif que le produit existe 
        }

        $existing = $em->getRepository(Favorites::class)->findOneBy([
            'product' => $product,
            'user' => $user,
        ]);

        if ($existing) {
            return $this->json(['message' => 'Déjà dans les favoris'], 200); // si produit est deja ds les fav, on n'ajoute pas 2 fois 
        }

        $favori = new Favorites(); 
        $favori->setUser($user);
        $favori->setProduct($product);
        $em->persist($favori);
        $em->flush(); // on cree le fav et on l'enregistre ds la bdd 

        return $this->json(['message' => 'Ajouté aux favoris']); 
    }
>>>>>>> Stashed changes
}
