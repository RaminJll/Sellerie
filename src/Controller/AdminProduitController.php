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
use App\Enum\ProduitEtat;


class AdminProduitController extends AbstractController
{
    // Route pour afficher la liste des produits disponibles
    #[Route('/gestionProduit', name: 'app_admin_produit')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(): Response
    {
        return $this->render('admin/gestions_produits/allProduits.html.twig');
    }

    // Route pour ajouter un nouveau produit avec validation des données et gestion des erreurs
    #[Route('/ajoutProduit', name: 'ajout_produit')]
    #[IsGranted('ROLE_ADMIN')]
    public function ajoutProduit(Request $request, EntityManagerInterface $entityManager): Response
    {
        $produit = new Produit();
    
        $form = $this->createForm(ProduitType::class, $produit);
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Valider que le planning est une date future
            $planning = $produit->getPlanning();
            if ($planning && $planning < new \DateTime()) {
                $this->addFlash('error', 'La date de planning doit être dans le futur.');
                return $this->render('admin/gestions_produits/ajoutProduit_form.html.twig', [
                    'form' => $form->createView(),
                ]);
            }
    
            $entityManager->persist($produit);
            $entityManager->flush();
    
            $this->addFlash('success', 'Produit ajouté avec succès.');
    
            return $this->redirectToRoute('ajout_produit'); 
        }
    
        return $this->render('admin/gestions_produits/ajoutProduit_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }



// Route pour afficher et paginer les produits pouvant être modifiés
    #[Route('/allUpdateProduit', name: 'allUpdate_produit')]
    #[IsGranted('ROLE_ADMIN')]
    public function allUpdateProduit(Request $request, EntityManagerInterface $entityManager, PaginatorInterface $paginator, ProduitRepository $produitRepository): Response
    {
        $query = $produitRepository->createQueryBuilder('p')->getQuery();
    
        $produits = $paginator->paginate($query, $request->query->getInt('page', 1), 20);
    
        return $this->render('admin/gestions_produits/allUpdateProduit.html.twig', [
            'produits' => $produits,
        ]);
    }



// Route pour mettre à jour un produit spécifique
    #[Route('/updateProduit/{id}', name: 'update_produit')]
    #[IsGranted('ROLE_ADMIN')]
    public function updateProduit(int $id, Request $request, EntityManagerInterface $entityManager, ProduitRepository $produitRepository): Response
    {
        $produit = $produitRepository->find($id);
        if (!$produit) {
            throw $this->createNotFoundException('Produit non trouvé.');
        }
    
        $form = $this->createForm(UpdateProduitType::class, $produit);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
    
            $this->addFlash('success', 'Le produit a été modifié avec succès !');
    
            return $this->redirectToRoute('update_produit', ['id' => $id]);
        }
    
        return $this->render('admin/gestions_produits/updateProduit_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
// Route pour afficher les produits pouvant être supprimés avec exclusion de certains états
    #[Route('/allDeleteProduit', name: 'allDelete_produit')]
    #[IsGranted('ROLE_ADMIN')]
    public function allDeleteProduit(Request $request, PaginatorInterface $paginator, ProduitRepository $produitRepository): Response
    {
        $query = $produitRepository->findProduitsExclureEtat([
            ProduitEtat::HorsService->value,
            ProduitEtat::Reparation->value,
        ]);
    
        $produits = $paginator->paginate($query, $request->query->getInt('page', 1), 20);
    
        return $this->render('admin/gestions_produits/allDeleteProduit.html.twig', [
            'produits' => $produits,
        ]);
    }
    

// Route pour supprimer un produit spécifique
    #[Route('/deleteProduit/{id}', name: 'delete_produit')]
    #[IsGranted('ROLE_ADMIN')]
    public function deleteProduit(int $id, EntityManagerInterface $entityManager): Response
    {
        // Récupérer l'utilisateur
        $produit = $entityManager->getRepository(Produit::class)->find($id);

        if (!$produit) {
            throw $this->createNotFoundException('Utilisateur non trouvé');
        }

        $entityManager->remove($produit);
        $entityManager->flush();

        $this->addFlash('success', 'Le produit a été supprimé avec succès !');

        return $this->redirectToRoute('allDelete_produit');
    }
}
