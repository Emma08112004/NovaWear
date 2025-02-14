<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ManController extends AbstractController
{
    #[Route('/man', name: 'app_man')]
    public function index(): Response
    {
        return $this->render('man/index.html.twig', [
            'controller_name' => 'ManController',
        ]);
    }
}
