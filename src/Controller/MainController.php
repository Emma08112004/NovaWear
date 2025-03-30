<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Basket;
use App\Entity\Product;

class MainController extends AbstractController
{
    #[Route('/', name: 'home')] // Route pour la page d'accueil
    public function home(): Response
    {                                 
        return $this->render('main/home.html.twig');
    }


    #[Route('/connexion', name: 'connexion')] // Route pour la page de connexion
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
    $error = $authenticationUtils->getLastAuthenticationError();
    $lastUsername = $authenticationUtils->getLastUsername();

    return $this->render('main/connexion.html.twig', [
        'last_username' => $lastUsername,
        'error' => $error,
    ]);
}

    #[Route('/panier', name: 'panier')] // Route pour la page du panier
public function afficherPanier(EntityManagerInterface $em): Response
{
    $user = $this->getUser();
    $panier = $em->getRepository(Basket::class)->findBy([
    'userId' => $user->getId()
]);
    $panierData = [];
    $total = 0;

    foreach ($panier as $item) {
        $produit = $em->getRepository(Product::class)->find($item->getProductId());
        $total += $produit->getPrixProduct() * $item->getQuantity();

        $panierData[] = [
            'id' => $item->getId(), 
            'product' => $produit,
            'quantity' => $item->getQuantity(),
            'taille' => $item->getTaille()
        ];
    }

    return $this->render('main/panier.html.twig', [
        'panier' => $panierData,
        'total' => $total
    ]);
}

    
    #[Route('/femme', name: 'femme')] // Route pour la page femme
public function femme(ProductRepository $productRepository): Response
{
    $produits = $productRepository->findByCategorie('femme');
    return $this->render('main/femme.html.twig', [
        'produits' => $produits
    ]);
}

#[Route('/homme', name: 'homme')] // Route pour la page homme 
public function homme(ProductRepository $productRepository): Response
{
    $produits = $productRepository->findByCategorie('homme');
    return $this->render('main/homme.html.twig', [
        'produits' => $produits
    ]);
}

}
