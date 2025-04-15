<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ManController extends AbstractController
{
    #[Route('/man', name: 'man')]
    public function index(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findByCategorie('man');
        return $this->render('main/man.html.twig', [
            'products' => $products,
        ]);
    }
}