<?php

namespace App\Repository;

use App\Entity\Historiques;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\User;

/**
 * @extends ServiceEntityRepository<Historiques>
 */
class HistoriquesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Historiques::class);
    }

    public function findHistoriqueByUser(User $user): array
    {
        return $this->createQueryBuilder('h')
            ->select('h.id, h.date_empreinte, h.date_rendu, h.signalement, h.etat_init, p.id AS id ,p.nom AS nom, p.categorie AS categorie, p.type_produit AS type_produit, p.etat AS etat')
            ->leftJoin('h.produit', 'p')
            ->where('h.user = :user')
            ->setParameter('user', $user)
            ->orderBy('h.date_empreinte', 'DESC')
            ->getQuery()
            ->getResult();
    }
    


//    /**
//     * @return Historiques[] Returns an array of Historiques objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('h.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Historiques
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
