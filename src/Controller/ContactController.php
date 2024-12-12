<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;


class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    #[IsGranted('ROLE_USER')]
    public function index(): Response
    {
        return $this->render('contact/contact.html.twig');
    }
}
