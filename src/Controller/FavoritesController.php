<?php
namespace App\Controller;

use App\Repository\FavoritesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

    #[Route('/remove/{id}', name: 'remove_favorite')]
    //#[IsGranted('ROLE_USER')]
    public function removeFavorite(int $id, FavoritesRepository $favoritesRepository, EntityManagerInterface $entityManager): Response
    {
        $favorite = $favoritesRepository->findOneBy(['user' => $this->getUser(), 'product' => $id]);

        if ($favorite) {
            $entityManager->remove($favorite);
            $entityManager->flush();
        }

        return $this->redirectToRoute('favoris');
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