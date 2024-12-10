<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {
        /** @var \App\Entity\User|null $user */
        $user = $this->getUser();
    
        return $this->render('home/home.html.twig', [
            'user' => $user->getUserIdentifier(),
            'id' => $user->getId()
        ]);
    }
    
}