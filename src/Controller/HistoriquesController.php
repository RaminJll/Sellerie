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
use App\Repository\HistoriquesRepository;

class HistoriquesController extends AbstractController
{

// Route pour réserver un produit. Accessible uniquement aux utilisateurs ayant le rôle ROLE_USER
    #[Route('/produit/reserver', name: 'produit_reserver', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function reserver(Request $request, ProduitRepository $produitRepository, EntityManagerInterface $entityManager): Response
    {
        // Vérifie et sécurise la soumission du formulaire avec un token CSRF.
        // Récupère l'utilisateur actuellement connecté.
        // Réserve un produit en changeant son état et en créant un enregistrement dans l'historique.

        /** @var \App\Entity\User|null $user */
        $user = $this->getUser();

        $token = $request->request->get('_csrf_token');
        if (!$this->isCsrfTokenValid('produit_reserver', $token)) {
            throw $this->createAccessDeniedException('Requête non valide.');
        }

        $produitId = (int) $request->request->get('id');
        $produit = $produitRepository->find($produitId);
        if (!$produit) {
            throw $this->createNotFoundException('Produit introuvable.');
        }

        $reservation = new Historiques();
        $reservation->setIdUser($user);
        $reservation->setIdProduit($produit);
        $reservation->setDateEmpreinte((new \DateTime())->setTime(0, 0, 0));
        $reservation->setSignalement(null);
        $reservation->setEtatInit($produit->getEtat());
        $reservation->setRetard(0);

        $produit->setEtat(ProduitEtat::HorsService);
        $entityManager->persist($reservation);
        $entityManager->persist($produit);
        $entityManager->flush();

        return $this->redirectToRoute('app_historiques');
    }


    // Route pour afficher l'historique des réservations de l'utilisateur connecté.
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



    // Route pour rendre un produit réservé. Accessible uniquement aux utilisateurs ayant le rôle 'ROLE_USER'.
    #[Route('/produit/rendre', name: 'produit_rendre', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function rendre(Request $request, ProduitRepository $produitRepository, EntityManagerInterface $entityManager, HistoriquesRepository $historiquesRepository): Response
    {
        // Vérifie et sécurise la soumission du formulaire avec un token CSRF.
        // Gère le retour d'un produit en mettant à jour son état initial et en enregistrant toute anomalie signalée.
        // Calcule le retard éventuel dans la restitution et met à jour l'historique.

        $user = $this->getUser();

        $token = $request->request->get('_csrf_token');
        if (!$this->isCsrfTokenValid('produit_rendre', $token)) {
            throw $this->createAccessDeniedException('Requête non valide.');
        }

        $produitId = (int) $request->request->get('id');
        $produit = $produitRepository->find($produitId);
        if (!$produit) {
            throw $this->createNotFoundException('Produit introuvable.');
        }

        $etatInit = $request->request->get('etat_init');
        if (!in_array($etatInit, array_column(ProduitEtat::cases(), 'value'))) {
            throw $this->createNotFoundException('État initial invalide.');
        }

        $historique = $historiquesRepository->findOneBy(['user' => $user, 'produit' => $produit]);


        $signalementInput = (string) $request->request->get('signalement');

        if ($signalementInput === 'Rien a signaler') {
            $signalementInput = 'Rien a signaler';
        }
        elseif ($signalementInput === 'Probleme detecte') {
            $signalementInput = 'Probleme detecte';
        }
        else{
            throw $this->createNotFoundException('Mauvais signalement.');
        }


        $historique->setSignalement($signalementInput);

        $dateEmpreinte = $historique->getDateEmpreinte();
        $historique->setDateRendu((new \DateTime())->setTime(0, 0, 0));

        $dateRendu = new \DateTime();
        $diff = $dateEmpreinte->diff($dateRendu);
    
        if ($diff->days > 10) {
            $retard = $diff->days - 10;
            $historique->setRetard($retard);
        } else {
            $historique->setRetard(0);
        }

        $produit->setEtat(ProduitEtat::from($etatInit));

        $entityManager->persist($produit);
        $entityManager->persist($historique);
        $entityManager->flush();

        return $this->redirectToRoute('app_historiques');
    }
}
