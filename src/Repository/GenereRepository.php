<?php

namespace App\Repository;

use App\Entity\Genere;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Genere|null find($id, $lockMode = null, $lockVersion = null)
 * @method Genere|null findOneBy(array $criteria, array $orderBy = null)
 * @method Genere[]    findAll()
 * @method Genere[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GenereRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Genere::class);
    }

    // /**
    //  * @return Genere[] Returns an array of Genere objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Genere
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
