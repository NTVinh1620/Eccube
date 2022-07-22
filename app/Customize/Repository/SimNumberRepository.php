<?php

namespace Customize\Repository;

use Customize\Entity\SimNumber;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method SimNumber|null find($id, $lockMode = null, $lockVersion = null)
 * @method SimNumber|null findOneBy(array $criteria, array $orderBy = null)
 * @method SimNumber[]    findAll()
 * @method SimNumber[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SimNumberRepository extends \Eccube\Repository\AbstractRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, SimNumber::class);
    }

    // /**
    //  * @return SimNumber[] Returns an array of SimNumber objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SimNumber
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
