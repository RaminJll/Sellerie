<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DefaultPageController extends AbstractController
{
    // Route pour rediriger vers la page de connexion en tant que page par défaut
    #[Route('/', name: 'app_default_page')]
    public function index(): Response
    {
        return $this->redirectToRoute('app_login');
    }
}
