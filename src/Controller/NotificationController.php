<?php

// src/Controller/NotificationController.php

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\Notifications;
use App\Enum\NotificationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NotificationController extends AbstractController
{
    #[Route('/notification', name: 'app_notification')]
    public function gestionStock(EntityManagerInterface $entityManager): Response
    {
        // Récupérer l'EntityManager pour interagir avec la base de données
        $produitRepository = $entityManager->getRepository(Produit::class);
        $notificationsRepository = $entityManager->getRepository(Notifications::class);

        // Récupérer tous les types de produits dans la table Produit
        $typesProduits = $produitRepository->findDistinctTypes(); // Méthode personnalisée pour obtenir les types distincts

        // Parcours de chaque type de produit
        foreach ($typesProduits as $typeProduit) {
            // Compter le nombre de produits valides (état : 'neuf', 'bon etat', 'usé') pour chaque type
            $validProductCount = $produitRepository->stock($typeProduit);

            // Si moins de 20 produits valides, ajouter une notification
            if ($validProductCount < 20) {
                $notification = new Notifications();
                $notification->setType(NotificationType::Reapprovisionnement);
                $notification->setDateNotification(new \DateTime());
                $notification->setTypeProduitManquant($typeProduit);

                // Enregistrer la notification dans la base de données
                $entityManager->persist($notification);
            }
        }

        // Enregistrer toutes les notifications créées
        $entityManager->flush();

        return $this->render('notification/index.html.twig', [
            'controller_name' => 'NotificationController',
        ]);
    }
}

