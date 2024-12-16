<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Bundle\SecurityBundle\Security;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils, Security $security): Response
    {
        // Vérifier si un utilisateur est connecté
        $user = $security->getUser();

        if ($user) {
            // Map des rôles vers les routes
            $roleRoutes = [
                'ROLE_ADMIN' => 'app_admin_home',
                'ROLE_USER' => 'app_home',
            ];

            foreach ($user->getRoles() as $role) {
                if (isset($roleRoutes[$role])) {
                    return $this->redirectToRoute($roleRoutes[$role]);
                }
            }
        }


        // Récupérer l'erreur de connexion s'il y en a une
        $error = $authenticationUtils->getLastAuthenticationError();

        // Dernier nom d'utilisateur saisi par l'utilisateur
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }



    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        // Le code ici ne sera jamais exécuté !
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
