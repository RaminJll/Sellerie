<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\HistoriquesRepository;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class StatistiquesController extends AbstractController
{

// Action qui récupère toutes les données des historiques pour générer des statistiques. 
// Seuls les utilisateurs ayant le rôle "ROLE_ADMIN" peuvent accéder à cette page.
// Les données sont récupérées via le repository HistoriquesRepository. 
// Le code parcourt ensuite ces historiques pour accéder aux informations du produit et de l'utilisateur associé.
    #[Route('/statistiques', name: 'app_statistiques')]
    #[IsGranted('ROLE_ADMIN')]
    public function allhistorique(HistoriquesRepository $historiquesRepository): Response
    {
        $statistiques = $historiquesRepository->findAll();

        foreach ($statistiques as $historique) {
            $produit = $historique->getIdProduit();

            $user = $historique->getUser();
        }

        return $this->render('admin/statistiques/statistiques.html.twig', [
            'statistiques' => $statistiques,
        ]);
    }
}
