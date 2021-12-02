<?php

namespace App\Repository;

use App\Entity\ApiRessource;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ApiRessource|null find($id, $lockMode = null, $lockVersion = null)
 * @method ApiRessource|null findOneBy(array $criteria, array $orderBy = null)
 * @method ApiRessource[]    findAll()
 * @method ApiRessource[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ApiRessourceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ApiRessource::class);
    }

    // /**
    //  * @return ApiRessource[] Returns an array of ApiRessource objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ApiRessource
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
