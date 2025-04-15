<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegisterService
{
    public function __construct(
        private EntityManagerInterface $em,
        private UserPasswordHasherInterface $passwordHasher,
        private FormFactoryInterface $formFactory
    ) {
    }

    public function handleRegistration(Request $request): FormInterface|User
    {
        $user = new User();
        $form = $this->formFactory->create(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hashedPassword = $this->passwordHasher->hashPassword(
                $user,
                $user->getMotDePasse()
            );

            $user->setMotDePasse($hashedPassword);

            $this->em->persist($user);
            $this->em->flush();

            return $user;
        }

        return $form;
    }
}
