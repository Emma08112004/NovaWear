<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\StripeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StripeController extends AbstractController
{
    #[Route('/create-checkout-session', name: 'stripe_checkout', methods: ['POST'])]
    public function checkout(StripeService $stripeService): Response
    {
        return $stripeService->createCheckoutSession($this->getUser());
    }

    #[Route('/payment-success', name: 'stripe_success')]
    public function success(StripeService $stripeService): Response
    {
        return $stripeService->handleSuccess($this->getUser());
    }

    #[Route('/payment-cancel', name: 'stripe_cancel')]
    public function cancel(): Response
    {
        return $this->render('stripe/cancel.html.twig');
    }
}
