<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class AdminHomeController extends AbstractController
{
    #[Route('/admin/home', name: 'app_admin_home')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        return $this->render('admin/home/home.html.twig', [
            'controller_name' => 'AdminHomeController',
        ]);
    }
}
