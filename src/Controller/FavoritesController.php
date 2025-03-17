<?php
namespace App\Controller;

use App\Repository\FavoritesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
        $user = $this->getUser();
        $favorites = $favoritesRepository->findFavoritesByUser($user);

        return $this->render('favorites.html.twig', [
            'favorites' => $favorites,
        ]);
    }

    #[Route('/remove/{id}', name: 'remove_favorite')]
    #[IsGranted('ROLE_USER')]
    public function removeFavorite(int $id, FavoritesRepository $favoritesRepository, EntityManagerInterface $entityManager): Response
    {
        $favorite = $favoritesRepository->findOneBy(['user' => $this->getUser(), 'product' => $id]);

        if ($favorite) {
            $entityManager->remove($favorite);
            $entityManager->flush();
        }

        return $this->redirectToRoute('favoris');
    }
}