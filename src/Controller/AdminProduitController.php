<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Form\UpdateProduitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ProduitRepository;
use Knp\Component\Pager\PaginatorInterface;


class AdminProduitController extends AbstractController
{
    #[Route('/gestionProduit', name: 'app_admin_produit')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(): Response
    {
        return $this->render('admin/gestions_produits/allProduits.html.twig');
    }


    #[Route('/ajoutProduit', name: 'ajout_produit')]
    #[IsGranted('ROLE_ADMIN')]
    public function ajoutProduit(Request $request, EntityManagerInterface $entityManager): Response
    {
        $produit = new Produit();
    
        // Créer le formulaire avec une catégorie optionnelle (non utilisée ici mais maintenue pour cohérence)
        $form = $this->createForm(ProduitType::class, $produit);
    
        // Gérer la soumission du formulaire
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Valider que le planning est une date future (dans le cas où la contrainte symfony échoue)
            $planning = $produit->getPlanning();
            if ($planning && $planning < new \DateTime()) {
                $this->addFlash('error', 'La date de planning doit être dans le futur.');
                return $this->render('admin/gestions_produits/ajoutProduit_form.html.twig', [
                    'form' => $form->createView(),
                ]);
            }
    
            // Persister les données
            $entityManager->persist($produit);
            $entityManager->flush();
    
            // Ajouter un message de succès
            $this->addFlash('success', 'Produit ajouté avec succès.');
    
            // Rediriger vers une autre page après le succès (par exemple, la liste des produits)
            return $this->redirectToRoute('ajout_produit'); // Remplacez 'liste_produits' par votre route de destination
        }
    
        return $this->render('admin/gestions_produits/ajoutProduit_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    

    #[Route('/allUpdateProduit', name: 'allUpdate_produit')]
    #[IsGranted('ROLE_ADMIN')]
    public function allUpdateProduit(Request $request, EntityManagerInterface $entityManager, PaginatorInterface $paginator, ProduitRepository $produitRepository): Response
    {
        // Récupérer tous les produits
        $query = $produitRepository->createQueryBuilder('p')->getQuery();
    
        // Pagination : 20 produits par page
        $produits = $paginator->paginate($query, $request->query->getInt('page', 1), 20);
    
        // Rendu de la vue avec les produits paginés
        return $this->render('admin/gestions_produits/allUpdateProduit.html.twig', [
            'produits' => $produits,
        ]);
    }


    #[Route('/updateProduit/{id}', name: 'update_produit')]
    #[IsGranted('ROLE_ADMIN')]
    public function updateProduit(int $id, Request $request, EntityManagerInterface $entityManager, ProduitRepository $produitRepository): Response
    {
        // Récupérer le produit dans la base de données
        $produit = $produitRepository->find($id);
        if (!$produit) {
            throw $this->createNotFoundException('Produit non trouvé.');
        }
    
        // Créer le formulaire et préremplir avec les données existantes
        $form = $this->createForm(UpdateProduitType::class, $produit);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Sauvegarder les modifications
            $entityManager->flush();
    
            // Ajouter un message flash
            $this->addFlash('success', 'Le produit a été modifié avec succès !');
    
            // Rediriger après la modification
            return $this->redirectToRoute('update_produit', ['id' => $id]);
        }
    
        return $this->render('admin/gestions_produits/updateProduit_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    

    #[Route('/allDeleteProduit', name: 'allDelete_produit')]
    #[IsGranted('ROLE_ADMIN')]
    public function allDeleteProduit(Request $request, EntityManagerInterface $entityManager, PaginatorInterface $paginator, ProduitRepository $produitRepository): Response
    {
        // Récupérer tous les produits
        $query = $produitRepository->createQueryBuilder('p')->getQuery();
    
        // Pagination : 20 produits par page
        $produits = $paginator->paginate($query, $request->query->getInt('page', 1), 20);
    
        // Rendu de la vue avec les produits paginés
        return $this->render('admin/gestions_produits/allDeleteProduit.html.twig', [
            'produits' => $produits,
        ]);
    }

    #[Route('/deleteProduit/{id}', name: 'delete_produit')]
    #[IsGranted('ROLE_ADMIN')]
    public function deleteProduit(int $id, EntityManagerInterface $entityManager): Response
    {
        // Récupérer l'utilisateur
        $produit = $entityManager->getRepository(Produit::class)->find($id);

        if (!$produit) {
            throw $this->createNotFoundException('Utilisateur non trouvé');
        }

        // Supprimer l'utilisateur
        $entityManager->remove($produit);
        $entityManager->flush();

        // Ajouter un message flash
        $this->addFlash('success', 'Le produit a été supprimé avec succès !');

        // Rediriger vers la liste des utilisateurs
        return $this->redirectToRoute('allDelete_produit');
    }
}
