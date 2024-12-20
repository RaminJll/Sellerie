<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ProduitRepository;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Maintenances;
use App\Form\MaintenancesType;
use Doctrine\ORM\EntityManagerInterface;
use App\Enum\ProduitEtat;
use App\Repository\MaintenancesRepository;


class MaintenanceController extends AbstractController
{
    #[Route(path: '/maintenance', name: 'app_maintenance')]
    #[IsGranted('ROLE_ADMIN')]
    public function maintenance(ProduitRepository $produitRepository, MaintenancesRepository $maintenancesRepository): Response
    {
        // Récupère uniquement les produits dont la colonne planning est aujourd'hui ou dans le passé
        $allProduits = $produitRepository->findAllMaintenance();
        $maintenancesData = [];
    
        foreach ($allProduits as $produit) {
            $maintenance = $maintenancesRepository->findOneBy(['produit' => $produit]);
    
            $maintenancesData[] = [
                'produit' => $produit,
                'categorie' => $produit->getCategorie(), // Suppose que la catégorie est une propriété du produit
                'typeProduit' => $produit->getTypeProduit(), // Suppose que le type est une propriété du produit
                'description' => $maintenance ? $maintenance->getDescription() : null,
                'coutMaintenance' => $maintenance ? $maintenance->getCoutMaintenance() : null,
                'dateFinMaintenance' => $maintenance ? $maintenance->getDateFinMaintenance() : null,
            ];
        }
    
        return $this->render('admin/gestions_produits/maintenance.html.twig', [
            'maintenancesData' => $maintenancesData,
        ]);
    }
    
    

    #[Route('/maintenances_form/{produitId}', name: 'formulaire_maintenance')]
    #[IsGranted('ROLE_ADMIN')]
    public function maintenanceForm(int $produitId, Request $request, EntityManagerInterface $entityManager, ProduitRepository $produitRepository): Response
    {
        // Récupération du produit
        $produit = $produitRepository->find($produitId);
        if (!$produit) {
            throw $this->createNotFoundException("Produit non trouvé !");
        }

        $maintenance = new Maintenances();
        $maintenance->setIdProduit($produit);

        $form = $this->createForm(MaintenancesType::class, $maintenance);
        $form->handleRequest($request);

        if ($request->isMethod('POST') && $form->isSubmitted() && $form->isValid()) {
            if ($maintenance->getCoutMaintenance() <= 0) {
                throw $this->createAccessDeniedException('Le coût de maintenance doit être supérieur à zéro.');
            }


            $maintenance->setEtatInit($produit->getEtat());

            $produit->setEtat(ProduitEtat::HorsService);

            // Enregistrement en base
            $entityManager->persist($produit);
            $entityManager->persist($maintenance);
            $entityManager->flush();

            // Redirection après succès
            return $this->redirectToRoute('app_maintenance');
        }

        // Retourner la vue
        return $this->render('admin/gestions_produits/maintenance_form.html.twig', [
            'form' => $form->createView(),
            'produit' => $produit,
        ]);
    }
}
