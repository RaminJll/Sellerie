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

    #[Route('/produit/reserver', name: 'produit_reserver', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function reserver(Request $request, ProduitRepository $produitRepository, EntityManagerInterface $entityManager): Response
    {
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
    public function rendre(Request $request, ProduitRepository $produitRepository, EntityManagerInterface $entityManager, HistoriquesRepository $historiquesRepository): Response
    {
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
