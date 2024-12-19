<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ProduitRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Enum\ProduitEtat;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReparationsRepository;

class ProduitController extends AbstractController
{
    #[Route('/produitType/{categorie}', name: 'produit_type')]
    #[IsGranted('ROLE_USER')]
    public function produitType(string $categorie, ProduitRepository $ProduitRepository): Response
    {
        $types = $ProduitRepository->findTypeProduitByCategory($categorie);

        return $this->render('Produit/type.html.twig', [
            'types' => $types,
            'categorie' => $categorie
        ]);
    }

    #[Route('/produitList/{categorie}/{type}', name: 'produit_list')]
    #[IsGranted('ROLE_USER')]
    public function produitList(string $categorie, string $type, ProduitRepository $produitRepository, PaginatorInterface $paginator, Request $request, ReparationsRepository $reparationsRepository, EntityManagerInterface $entityManager): Response
    {
        // Appelle une méthode personnalisée du repository pour obtenir les produits
        $requete = $produitRepository->findProduitsByTypeAndCategory($categorie, $type);
    
        // Pagination des produits
        $produits = $paginator->paginate($requete, $request->query->getInt('page', 1), 20);
    
        // Vérification et mise à jour de l'état des produits
        $aujourdhui = new \DateTime();
    
        foreach ($produits as $produit) {
            // Vérifier si le produit est en réparation
            if ($produit->getEtat() === ProduitEtat::Reparation) {
                // Vérifier les réparations liées à ce produit
                $reparations = $reparationsRepository->findBy(['produit' => $produit]);
    
                foreach ($reparations as $reparation) {
                    // Si la date de fin de la réparation est antérieure ou égale à aujourd'hui, on met à jour l'état du produit
                    if ($reparation->getDateFinReparation() <= $aujourdhui) {
                        // Mettre à jour l'état du produit en fonction de l'état initial de la réparation
                        $produit->setEtat($reparation->getEtatInit());
    
                        // Persist pour enregistrer les modifications
                        $entityManager->persist($produit);
                    }
                }
            }
        }
    
        // Appliquer les changements à la base de données
        $entityManager->flush();
    
        /** @var \App\Entity\User|null $user */
        $user = $this->getUser();
    
        return $this->render('Produit/list.html.twig', [
            'produits' => $produits,
            'categorie' => $categorie,
            'type' => $type,
        ]);
    }
    
    
}