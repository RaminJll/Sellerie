<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Bundle\SecurityBundle\Security;

class SecurityController extends AbstractController
{

// Action de connexion qui vérifie si un utilisateur est déjà connecté. Si l'utilisateur est connecté,
// il est redirigé vers la page d'accueil correspondant à son rôle (ROLE_ADMIN ou ROLE_USER).
// Si l'utilisateur n'est pas connecté, un formulaire de connexion est affiché.
// Accessible par tous les utilisateurs.
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils, Security $security): Response
    {
        // Vérifier si un utilisateur est connecté
        $user = $security->getUser();

        if ($user) {
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


        $error = $authenticationUtils->getLastAuthenticationError();

        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }


// Action de déconnexion. Cette méthode est interceptée par la clé logout dans la configuration du firewall,
// ce qui permet de gérer la déconnexion de manière transparente.
// Elle ne contient pas de logique, car Symfony gère automatiquement la déconnexion.
    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
