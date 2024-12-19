<?php

namespace App\Repository;

use App\Entity\Produit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Produit>
 */
class ProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produit::class);
    }

    public function findTypeProduitByCategory(string $category): array
    {
        return $this->createQueryBuilder('p')
            ->select('DISTINCT p.type_produit')
            ->where('p.categorie = :categorie')
            ->setParameter('categorie', $category)
            ->getQuery()
            ->getResult(); // Retourne un tableau avec les valeurs uniques
    }

    public function findProduitsByTypeAndCategory(string $category, string $type)
    {
        return $this->createQueryBuilder('p')
            ->where('p.type_produit = :type')
            ->setParameter('type', $type)
            ->andWhere('p.categorie = :categorie')
            ->setParameter('categorie', $category)
            ->getQuery()
            ->getResult(); // Retourne une liste d'entités Produit
    }

    public function findAllMaintenance()
    {
        $now = new \DateTime(); // Récupère la date actuelle

        return $this->createQueryBuilder('p')
            ->where('p.planning <= :now') // Condition pour vérifier que la date de planning est dans le passé ou aujourd'hui
            ->setParameter('now', $now)
            ->getQuery()
            ->getResult(); // Récupère les résultats sous forme d'objets
    }
}
