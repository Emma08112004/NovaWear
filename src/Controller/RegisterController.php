<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\RegisterService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;

class RegisterController extends AbstractController
{
    #[Route('/register', name: 'register')]
    public function register(Request $request, RegisterService $registerService): Response
    {
        $result = $registerService->handleRegistration($request);

        if ($result instanceof User) {
            return $this->redirectToRoute('login');
        }

        return $this->render('main/register.html.twig', [
            'form' => $result->createView(),
        ]);
    }
}
