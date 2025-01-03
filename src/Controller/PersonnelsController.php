<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\UpdateUserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class PersonnelsController extends AbstractController
{
// Action qui affiche la liste de tous les personnels (utilisateurs).
// Accessible uniquement par les administrateurs (ROLE_ADMIN).
    #[Route('/personnels', name: 'app_personnels')]
    #[IsGranted('ROLE_ADMIN')]
    public function personnels(): Response
    {
        return $this->render('admin/gestions_personnels/allPersonnels.html.twig');
    }

// Action qui permet d'ajouter un nouvel utilisateur.
// Elle affiche un formulaire et enregistre l'utilisateur dans la base de données après validation.
    #[Route('/ajoutUser', name: 'ajout_personnels')]
    #[IsGranted('ROLE_ADMIN')]
    public function ajoutUser(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
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

            $this->addFlash('success', 'L\'utilisateur a été ajouté avec succès !');

            return $this->redirectToRoute('ajout_personnels');
        }

        return $this->render('admin/gestions_personnels/personnels_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

// Action qui affiche la liste de tous les utilisateurs afin qu'ils puissent être mis à jour.
// Accessible uniquement par les administrateurs (ROLE_ADMIN).
    #[Route('/allUserUpdate', name: 'all_updatePersonnels')]
    #[IsGranted('ROLE_ADMIN')]
    public function allUserUpdate(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $users = $entityManager->getRepository(User::class)->findAll();

        return $this->render('admin/gestions_personnels/allUpdatePersonnels.html.twig', [
            'users' => $users,
        ]);
    }


// Action qui permet de modifier les informations d'un utilisateur spécifique.
// Elle vérifie si l'utilisateur existe et affiche un formulaire de mise à jour.
    #[Route('/updateUser/{id}', name: 'update_personnels')]
    #[IsGranted('ROLE_ADMIN')]
    public function modifierUser(int $id, Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response {
        $user = $entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            throw $this->createNotFoundException('Utilisateur non trouvé');
        }

        $form = $this->createForm(UpdateUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('nom')->getData() !== null && $form->get('nom')->getData() !== '') {
                $user->setNom($form->get('nom')->getData());
            }

            if ($form->get('email')->getData() !== null && $form->get('email')->getData() !== '') {
                $user->setEmail($form->get('email')->getData());
            }

            $password = $form->get('password')->getData();
            if ($password !== null && $password !== '') {
                $hashedPassword = $passwordHasher->hashPassword($user, $password);
                $user->setPassword($hashedPassword);
            }

            if ($form->get('role')->getData() !== null) {
                $user->setRole($form->get('role')->getData());
            }

            $entityManager->flush();

            $this->addFlash('success', 'L\'utilisateur a été modifié avec succès !');

            return $this->redirectToRoute('update_personnels', ['id' => $id]);
        }

        return $this->render('admin/gestions_personnels/updatePersonnels_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }


// Action qui affiche la liste de tous les utilisateurs afin qu'ils puissent être supprimés.
// Accessible uniquement par les administrateurs (ROLE_ADMIN).
    #[Route('/allUserDelete', name: 'all_deletePersonnels')]
    #[IsGranted('ROLE_ADMIN')]
    public function allUserDelete(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $users = $entityManager->getRepository(User::class)->findAll();

        return $this->render('admin/gestions_personnels/allDeletePersonnels.html.twig', [
            'users' => $users,
        ]);
    }

// Action qui permet de supprimer un utilisateur spécifique de la base de données.
// Elle vérifie si l'utilisateur existe avant de le supprimer.
    #[Route('/deleteUser/{id}', name: 'delete_personnels')]
    #[IsGranted('ROLE_ADMIN')]
    public function deleteUser(int $id, EntityManagerInterface $entityManager): Response
    {
        $user = $entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            throw $this->createNotFoundException('Utilisateur non trouvé');
        }

        $entityManager->remove($user);
        $entityManager->flush();

        $this->addFlash('success', 'L\'utilisateur a été supprimé avec succès !');

        return $this->redirectToRoute('all_deletePersonnels');
    }

}
