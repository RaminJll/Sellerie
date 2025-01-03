<?php

namespace App\Repository;

use App\Entity\Produit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Enum\ProduitEtat;
use Doctrine\ORM\Query;

/**
 * @extends ServiceEntityRepository<Produit>
 */
class ProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produit::class);
    }


// Recherche les types de produits distincts pour une catégorie donnée.
// Cette méthode renvoie une liste de types de produits pour une catégorie spécifiée.
    public function findTypeProduitByCategory(string $category): array
    {
        return $this->createQueryBuilder('p')
            ->select('DISTINCT p.type_produit')
            ->where('p.categorie = :categorie')
            ->setParameter('categorie', $category)
            ->getQuery()
            ->getResult(); 
    }


// Recherche les produits d'un type spécifique dans une catégorie donnée.
// Cette méthode renvoie les produits correspondant à un type et une catégorie donnés.
    public function findProduitsByTypeAndCategory(string $category, string $type)
    {
        return $this->createQueryBuilder('p')
            ->where('p.type_produit = :type')
            ->setParameter('type', $type)
            ->andWhere('p.categorie = :categorie')
            ->setParameter('categorie', $category)
            ->getQuery()
            ->getResult();
    }


// Recherche tous les produits dont la date de planification est antérieure ou égale à la date actuelle.
// Cette méthode renvoie les produits qui sont prêts pour la maintenance.
    public function findAllMaintenance()
    {
        $now = new \DateTime();

        return $this->createQueryBuilder('p')
            ->where('p.planning <= :now')
            ->setParameter('now', $now)
            ->getQuery()
            ->getResult();
    }


// Recherche les produits dont l'état ne correspond à aucun des états exclus.
// Cette méthode renvoie des produits en excluant certains états.
    public function findProduitsExclureEtat(array $etatsExclus): Query
    {
        return $this->createQueryBuilder('p')
            ->where('p.etat NOT IN (:etatsExclus)')
            ->setParameter('etatsExclus', $etatsExclus)
            ->getQuery();
    }



// Recherche les produits dont l'état ne correspond à aucun des états exclus.
// Cette méthode renvoie des produits en excluant certains états.
    public function findDistinctTypesAndCategories(): array
    {
        return $this->createQueryBuilder('p')
            ->select('DISTINCT p.type_produit, p.categorie')
            ->getQuery()
            ->getResult();
    }


// Compte le nombre de produits par type, catégorie et états spécifiques.
// Cette méthode renvoie le nombre de produits qui correspondent à un type, une catégorie et des états donnés.
    public function countByTypeAndCategorieAndEtats(string $typeProduit, string $categorie, array $etats): int
    {
        return $this->createQueryBuilder('p')
            ->select('COUNT(p.id)')
            ->where('p.type_produit = :type_produit')
            ->andWhere('p.categorie = :categorie')
            ->andWhere('p.etat IN (:etats)')
            ->setParameter('type_produit', $typeProduit)
            ->setParameter('categorie', $categorie)
            ->setParameter('etats', $etats)
            ->getQuery()
            ->getSingleScalarResult();
    }
    
}
