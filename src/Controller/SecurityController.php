<?php

namespace App\Controller;

use App\Form\LoginFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Récupérer la dernière adresse email saisie par l'utilisateur
        $lastEmail = $authenticationUtils->getLastUsername();
        // Récupérer les éventuelles erreurs de connexion
        $error = $authenticationUtils->getLastAuthenticationError();

        // Créer le formulaire
        $form = $this->createForm(LoginFormType::class, [
            'email' => $lastEmail,
        ]);

        return $this->render('security/login.html.twig', [
            'loginForm' => $form->createView(),
            'error' => $error,
        ]);
    }
}
