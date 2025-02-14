<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class WomanController extends AbstractController
{
    #[Route('/woman', name: 'app_woman')]
    public function index(): Response
    {
        return $this->render('woman/index.html.twig', [
            'controller_name' => 'WomanController',
        ]);
    }
}
