<?php

namespace App\Repository;

use App\Entity\Borrow;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Borrow|null find($id, $lockMode = null, $lockVersion = null)
 * @method Borrow|null findOneBy(array $criteria, array $orderBy = null)
 * @method Borrow[]    findAll()
 * @method Borrow[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BorrowRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Borrow::class);
    }

    // /**
    //  * @return Borrow[] Returns an array of Borrow objects
    //  */
    public function findByUser($iduser){
        return $this->createQueryBuilder('b')
            ->andWhere('b.users = :val')
            ->setParameters(['val' =>$iduser])
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(4)
            ->getQuery()
            ->getResult()
            ;
    }



    /*
    public function findOneBySomeField($value): ?Borrow
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
