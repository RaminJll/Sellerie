<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class HomeController extends AbstractController
{
// Contrôleur pour la gestion de la page d'accueil sécurisée
// Accessible uniquement aux utilisateurs ayant le rôle 'ROLE_USER'
    #[Route('/home', name: 'app_home')]
    #[IsGranted('ROLE_USER')]
    public function index(): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        /** @var \App\Entity\User|null $user */
        $user = $this->getUser();
    
        return $this->render('home/home.html.twig');
    }
}