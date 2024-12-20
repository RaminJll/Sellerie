<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class PersonnelsController extends AbstractController
{
    #[Route('/personnels', name: 'app_personnels')]
    public function addUser(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Hashing the password
            $hashedPassword = $passwordHasher->hashPassword($user, $user->getPassword());
            $user->setPassword($hashedPassword);

            // Saving the user
            $entityManager->persist($user);
            $entityManager->flush();

            // Add flash message
            $this->addFlash('success', 'L\'utilisateur a été ajouté avec succès !');

            return $this->redirectToRoute('app_personnels');
        }

        return $this->render('admin/gestions_personnels/personnels_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
