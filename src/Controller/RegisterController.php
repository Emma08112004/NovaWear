<?php
namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController
{
    #[Route('/inscription', name: 'inscription')] //route de l'inscription, url : /inscription
    public function register(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User(); // creation d'une nouvelle instance de l'entité user 
        $form = $this->createForm(RegistrationFormType::class, $user); //creer un formulaire grace a registrationFormType (classe de formulaire personnalisé en symphony) lié au user 

        $form->handleRequest($request); //verif si le formulaire est soumis 
        if ($form->isSubmitted() && $form->isValid()) { //si il est soumis et valide 
            
            $hashedPassword = $passwordHasher->hashPassword($user, $user->getMotDePasse());//hashage du mdp (pour la bdd)
            $user->setMotDePasse($hashedPassword); //on met a jour user avec le mdp hashé 

            $em->persist($user); //preparation pour enregistrer a la bdd ( methode de doctrine qui est un ORM(outil qui fait le lien entre le code et la bdd) de symphony)
            $em->flush(); // infos enregistré dans la bdd ( methode de symphony)

            return $this->redirectToRoute('connexion'); //redirection vers la page de connexion 
        }

        return $this->render('main/inscription.html.twig', [
            'form' => $form->createView(),
        ]);
    } 
}
