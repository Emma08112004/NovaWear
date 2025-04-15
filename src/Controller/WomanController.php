<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WomanController extends AbstractController
{
    #[Route('/woman', name: 'woman')]
    public function index(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findByCategorie('woman');
        return $this->render('main/woman.html.twig', [
            'products' => $products,
        ]);
    }
}