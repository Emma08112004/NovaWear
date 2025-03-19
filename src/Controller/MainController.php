<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function home(): Response
    {
        return $this->render('main/home.html.twig');
    }

    #[Route('/inscription', name: 'inscription')]
    public function inscription(): Response
    {
        return $this->render('main/inscription.html.twig');
    }

    #[Route('/connexion', name: 'connexion')]
    public function connexion(): Response
    {
        return $this->render('main/connexion.html.twig');
    }

    #[Route('/panier', name: 'panier')]
    public function panier(): Response
    {
        return $this->render('main/panier.html.twig');
    }

    #[Route('/paiement', name: 'paiement')]
    public function paiement(): Response
    {
        return $this->render('main/paiement.html.twig');
    }
    
    #[Route('/femme', name: 'femme')]
    public function femme(): Response
    {
        return $this->render('main/femme.html.twig');
    }

    #[Route('/homme', name: 'homme')]
    public function homme(): Response
    {
        return $this->render('main/homme.html.twig');
    }

    #[Route('/recapitulatif', name: 'recapitulatif')]
    public function recapitulatif(): Response
    {
        return $this->render('main/recapitulatif.html.twig');
    }
}
