<?php

namespace App\Repository;

use App\Entity\Buyers;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Buyers|null find($id, $lockMode = null, $lockVersion = null)
 * @method Buyers|null findOneBy(array $criteria, array $orderBy = null)
 * @method Buyers[]    findAll()
 * @method Buyers[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BuyersRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Buyers::class);
    }

//    /**
//     * @return Buyers[] Returns an array of Buyers objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */


    public function findOneByMail($value): ?Buyers
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.mail = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

}
