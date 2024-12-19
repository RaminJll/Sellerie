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
        $requete = $produitRepository->findProduitsByTypeAndCategory($categorie, $type);
    
        $produits = $paginator->paginate($requete, $request->query->getInt('page', 1), 20);
    
        $aujourdhui = new \DateTime();
    
        foreach ($produits as $produit) {
            if ($produit->getEtat() === ProduitEtat::Reparation) {
                $reparations = $reparationsRepository->findBy(['produit' => $produit]);
    
                foreach ($reparations as $reparation) {
                    if ($reparation->getDateFinReparation() <= $aujourdhui) {
                        $produit->setEtat($reparation->getEtatInit());
                        $produit->setPlanning($reparation->getEtatInit());
                        $entityManager->persist($produit);
                    }
                }
            }
    
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