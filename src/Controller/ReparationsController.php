<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Repository\HistoriquesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ProduitRepository;
use App\Repository\ReparationsRepository;
use App\Entity\Reparations;
use App\Enum\ProduitEtat;
use App\Form\ReparationsType;

class ReparationsController extends AbstractController
{

// Action qui récupère et affiche les informations relatives aux réparations,
// incluant les détails des produits, utilisateurs et réparations en cours ou passées.
// Accessible uniquement par les utilisateurs ayant le rôle ROLE_ADMIN.
    #[Route('/reparations', name: 'app_reparations')]
    #[IsGranted('ROLE_ADMIN')]
    public function reparation(HistoriquesRepository $historiquesRepository, ReparationsRepository $reparationsRepository): Response
    {
        $allReparations = $historiquesRepository->findAllReparations();
        $reparationsData = [];

        foreach ($allReparations as $historique) {
            $produit = $historique->getProduit();

            $reparation = $reparationsRepository->findOneBy(['produit' => $produit]);

            $reparationsData[] = [
                'produit' => $produit,
                'user' => $historique->getUser(),
                'dateEmpreinte' => $historique->getDateEmpreinte(),
                'dateRendu' => $historique->getDateRendu(),
                'retard' => $historique->getRetard(),
                'descriptionProbleme' => $reparation ? $reparation->getDescriptionProbleme() : null,
                'coutReparation' => $reparation ? $reparation->getCoutReparation() : null,
                'dateFinReparation' => $reparation ? $reparation->getDateFinReparation() : null,
            ];
        }

        return $this->render('admin/reparations/reparation.html.twig', [
            'reparationsData' => $reparationsData,
        ]);
    }


// Action qui permet d'ajouter ou de mettre à jour les réparations pour un produit spécifique.
// Elle affiche un formulaire de réparation, vérifie la validité des données soumises,
// et met à jour l'état du produit en "Réparation".
// Accessible uniquement par les utilisateurs ayant le rôle ROLE_ADMIN.
    #[Route('/reparations_form/{produitId}', name: 'formulaire_reparation')]
    #[IsGranted('ROLE_ADMIN')]
    public function reparationForm(int $produitId, Request $request, EntityManagerInterface $entityManager, ProduitRepository $produitRepository): Response
    {
        $produit = $produitRepository->find($produitId);
        if (!$produit) {
            throw $this->createNotFoundException("Produit non trouvé !");
        }

        $reparation = new Reparations();
        $reparation->setIdProduit($produit);


        $form = $this->createForm(ReparationsType::class, $reparation);
        $form->handleRequest($request);

        if ($request->isMethod('POST') && $form->isSubmitted() && $form->isValid()) {
            if ($reparation->getCoutReparation() <= 0) {
                throw $this->createAccessDeniedException('Le coût de réparation doit être supérieur à zéro.');
            }

            $reparation->setEtatInit($produit->getEtat());
            
            $produit->setEtat(ProduitEtat::Reparation);

            // Enregistrement en base
            $entityManager->persist($produit);
            $entityManager->persist($reparation);
            $entityManager->flush();

            $this->addFlash('success', 'La réparation a été ajoutée avec succès et l\'état du produit a été mis à jour !');
            return $this->redirectToRoute('app_reparations');
        }

        return $this->render('admin/reparations/reparation_form.html.twig', [
            'form' => $form->createView(),
            'produit' => $produit,
        ]);
    }
}
