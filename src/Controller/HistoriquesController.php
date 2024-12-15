<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Historiques;
use App\Enum\ProduitEtat;
use App\Repository\ProduitRepository;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use App\Repository\HistoriquesRepository;

class HistoriquesController extends AbstractController
{
    
    #[Route('/produit/reserver', name: 'produit_reserver', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function reserver(Request $request, ProduitRepository $produitRepository, EntityManagerInterface $entityManager, CsrfTokenManagerInterface $csrfTokenManager): Response {
        $user = $this->getUser();
    
        // Validation CSRF
        $token = $request->request->get('_csrf_token');
        if (!$this->isCsrfTokenValid('produit_reserver', $token)) {
            throw $this->createAccessDeniedException('Requête non valide.');
        }
    
        // Récupérer l'ID du produit
        $produitId = (int) $request->request->get('id');
        $produit = $produitRepository->find($produitId);
        if (!$produit) {
            throw $this->createNotFoundException('Produit introuvable.');
        }
    
        // Créer l'historique
        $reservation = new Historiques();
        $reservation->setIdUser($user);
        $reservation->setIdProduit($produit);
        $reservation->setDateEmpreinte((new \DateTime())->setTime(0, 0, 0));
        $reservation->setDateRendu((new \DateTime())->setTime(0, 0, 0));
        $reservation->setSignalement(null);
    
        // Mettre à jour l'état du produit
        $produit->setEtat(ProduitEtat::HorsService);
        $entityManager->persist($reservation);
        $entityManager->persist($produit);
        $entityManager->flush();
    
        $this->addFlash('success', 'Réservation effectuée avec succès, le produit est maintenant hors service.');
        return $this->redirectToRoute('app_historiques');
    }

    #[Route('/historique', name: 'app_historiques', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function historique(HistoriquesRepository $historiquesRepository): Response
    {
        $user = $this->getUser();
        $historiques = $historiquesRepository->findHistoriqueByUser($user);
    
        return $this->render('Compte/historiques.html.twig', [
            'historiques' => $historiques,
        ]);
    }

    #[Route('/produit/rendre', name: 'produit_rendre', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function rendre(Request $request, ProduitRepository $produitRepository, EntityManagerInterface $entityManager, CsrfTokenManagerInterface $csrfTokenManager): Response {
        
        $user = $this->getUser();
    
        // Validation CSRF
        $token = $request->request->get('_csrf_token');
        if (!$this->isCsrfTokenValid('produit_rendre', $token)) {
            throw $this->createAccessDeniedException('Requête non valide.');
        }

        return $this->render('Compte/historiques.html.twig');
    }
}
