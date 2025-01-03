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
use App\Repository\NotificationsRepository;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Repository\ProduitRepository;
use App\Enum\ProduitEtat;
use App\Entity\Historiques;

class NotificationController extends AbstractController
{
// Action qui gère les notifications liées à la gestion du stock. Elle vérifie si des notifications de réapprovisionnement ou de retard doivent être créées
// en fonction des produits présents dans la base de données et de leur état
// Si une notification doit être créée, elle est ajoutée à la base de données
    #[Route('/notification', name: 'app_notifications')]
    public function gestionStock(EntityManagerInterface $entityManager, ProduitRepository $produitRepository, NotificationsRepository $notificationsRepository): Response {
        $validEtats = [ProduitEtat::Neuf, ProduitEtat::BonEtat, ProduitEtat::USE];
    
        $typesAndCategories = $produitRepository->findDistinctTypesAndCategories();
    
        foreach ($typesAndCategories as $record) {
            $typeProduit = $record['type_produit'];
            $categorie = $record['categorie'];
    
            $validProductCount = $produitRepository->countByTypeAndCategorieAndEtats(
                $typeProduit,
                $categorie,
                $validEtats
            );
    
            $existingNotification = $notificationsRepository->findOneBy([
                'type_produit_manquant' => $typeProduit,
                'type' => NotificationType::Reapprovisionnement,
                'categorie' => $categorie
            ]);
    
            if ($validProductCount < 20 && !$existingNotification) {
                $notification = new Notifications();
                $notification->setType(NotificationType::Reapprovisionnement);
                $notification->setDateNotification(new \DateTime());
                $notification->setTypeProduitManquant($typeProduit);
                $notification->setCategorie($categorie);
    
                $entityManager->persist($notification);
            }
        }
    
        $entityManager->flush();
    
        $retardNotifications = $entityManager->getRepository(Historiques::class)->findRetardNotification();
    
        foreach ($retardNotifications as $retard) {
            $existingRetardNotification = $notificationsRepository->findOneBy([
                'produit' => $retard->getProduit(),
                'type' => NotificationType::Retard
            ]);
    
            if (!$existingRetardNotification) {
                $notification = new Notifications();
                $notification->setType(NotificationType::Retard);
                $notification->setDateNotification(new \DateTime());
                $notification->setProduit($retard->getProduit());
                $notification->setCategorie($retard->getProduit()->getCategorie());
    
                $entityManager->persist($notification);
            }
        }
    
        $entityManager->flush();
    
        $notifications = $notificationsRepository->findAll();
    
        return $this->render('admin/notifications/notifications.html.twig', [
            'notifications' => $notifications,
        ]);
    }
    
}
