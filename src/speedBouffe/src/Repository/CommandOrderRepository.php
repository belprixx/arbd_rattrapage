<?php

namespace App\Repository;

use App\Entity\CommandOrder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CommandOrder|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommandOrder|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommandOrder[]    findAll()
 * @method CommandOrder[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommandOrderRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CommandOrder::class);
    }

    /**
     * @return Order[] Returns an array of Order objects
     */

    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }



    public function findOneBySomeField($value): ?CommandOrder
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function findByMaxResults($max)
    {
        return $this->createQueryBuilder('o')
            ->orderBy('o.id', 'DESC')
            ->setMaxResults($max)
            ->getQuery()
            ->getArrayResult()
            ;
    }

}
