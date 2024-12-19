<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\HistoriquesRepository;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class StatistiquesController extends AbstractController
{
    #[Route('/statistiques', name: 'app_statistiques')]
    #[IsGranted('ROLE_ADMIN')]
    public function allhistorique(HistoriquesRepository $historiquesRepository): Response
    {
        $statistiques = $historiquesRepository->findAll();

        // Vous pouvez maintenant accéder aux données du produit et de l'utilisateur
        foreach ($statistiques as $historique) {
            // Accéder aux données du produit
            $produit = $historique->getIdProduit();

            // Accéder aux données de l'utilisateur
            $user = $historique->getUser();
        }

        return $this->render('admin/statistiques/statistiques.html.twig', [
            'statistiques' => $statistiques,
        ]);
    }
}
