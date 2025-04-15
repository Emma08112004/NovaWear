<?php

namespace App\Controller;

use App\Service\MyOrdersService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MyOrdersController extends AbstractController
{
    #[Route('/my-orders', name: 'my_orders')]
    public function index(MyOrdersService $ordersService): Response
    {
        $orders = $ordersService->getOrdersGroupedByCommand($this->getUser());

        return $this->render('main/my_orders.html.twig', [
            'orders' => $orders,
        ]);
    }
}