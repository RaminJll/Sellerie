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

// Recherche des historiques associés à un utilisateur spécifique.
// Cette fonction renvoie une liste d'historiques filtrée par l'utilisateur donné, 
// triée par la date d'empreinte (la plus récente en premier).
// Elle inclut des informations sur les produits associés à chaque historique.
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
    


// Recherche tous les historiques où le produit a été signalé comme ayant un problème (signalement = 'Probleme detecte').
// Cette fonction renvoie les historiques associés aux produits concernés, ainsi que les utilisateurs correspondants.
    public function findAllReparations()
    {
        return $this->createQueryBuilder('h')
            ->leftJoin('h.produit', 'p')
            ->addSelect('p')
            ->leftJoin('h.user', 'u')
            ->addSelect('u')
            ->where('h.signalement = :signalement')
            ->setParameter('signalement', 'Probleme detecte')
            ->getQuery()
            ->getResult();
    }



// Recherche des historiques dont la valeur de retard est supérieure à zéro.
// Cette fonction renvoie une liste d'historiques ayant un retard, pour potentiellement gérer des notifications de retard.
    public function findRetardNotification(): array
    {
        return $this->createQueryBuilder('h')
            ->where('h.retard > :value')
            ->setParameter('value', 0)
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
