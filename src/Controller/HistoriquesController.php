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
        //$reservation->setDateRendu((new \DateTime())->setTime(0, 0, 0));
        $reservation->setSignalement(null);
        $reservation->setEtatInit($produit->getEtat());

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
    public function rendre(Request $request, ProduitRepository $produitRepository, EntityManagerInterface $entityManager, HistoriquesRepository $historiquesRepository): Response
    {
        $user = $this->getUser();

        // Validation CSRF
        $token = $request->request->get('_csrf_token');
        if (!$this->isCsrfTokenValid('produit_rendre', $token)) {
            throw $this->createAccessDeniedException('Requête non valide.');
        }

        // Récupérer l'ID du produit
        $produitId = (int) $request->request->get('id');
        $produit = $produitRepository->find($produitId);
        if (!$produit) {
            throw $this->createNotFoundException('Produit introuvable.');
        }

        // Récupérer l'état initial depuis la requête
        $etatInit = $request->request->get('etat_init');
        if (!in_array($etatInit, array_column(ProduitEtat::cases(), 'value'))) {
            throw $this->createNotFoundException('État initial invalide.');
        }

        // Vérification de l'historique pour cet utilisateur et produit
        $historique = $historiquesRepository->findOneBy(['user' => $user, 'produit' => $produit,]);

        if (!$historique || $historique->getEtatInit()->value !== $etatInit) {
            throw $this->createAccessDeniedException('Historique invalide ou non autorisé.');
        }

        $signalementInput = (string) $request->request->get('signalement');

        if ($signalementInput == null || $signalementInput == '') {
            $signalementInput = 'Rien à signaler';
        }

        // Optionnel : Validation stricte (texte uniquement, sans caractères spéciaux)
        if (!preg_match('/^[\p{L}\p{N}\p{P}\p{Z}]+$/u', $signalementInput)) {
            throw new \InvalidArgumentException('Le signalement contient des caractères non autorisés.');
        }

        // Enregistrer le signalement
        $historique->setSignalement($signalementInput);


        // Mettre à jour l'état du produit
        $produit->setEtat(ProduitEtat::from($etatInit));

        // Mettre à jour la date de retour dans l'historique
        $historique->setDateRendu((new \DateTime())->setTime(0, 0, 0));

        $entityManager->persist($produit);
        $entityManager->persist($historique);
        $entityManager->flush();

        $this->addFlash('success', 'Produit rendu avec succès.');
        return $this->redirectToRoute('app_historiques');
    }
}
