<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ProduitRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Attribute\IsGranted;

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
    public function produitList(string $categorie, string $type, ProduitRepository $ProduitRepository, PaginatorInterface $paginator, Request $request ): Response
    {
        // Appelle une méthode personnalisée du repository
        $requete  = $ProduitRepository->findProduitsByTypeAndCategory($categorie, $type);
    
        $produits = $paginator->paginate($requete, $request->query->getInt('page', 1), 20);

        /** @var \App\Entity\User|null $user */
        $user = $this->getUser();

        return $this->render('Produit/list.html.twig', [
            'produits' => $produits,
            'categorie' => $categorie,
            'type' => $type,
        ]);
    }
    
}