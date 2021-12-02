<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    // /**
    //  * @return Book[] Returns an array of Book objects
    //  */

    public function findByCategory($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.categorie = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
public function findByPrixSup($price,$category){
        return $this->createQueryBuilder('b')
            ->andWhere('b.price >= :val','b.categorie = :val1')
            ->setParameters(['val' =>$price,'val1'=>$category])

            ->orderBy('b.id', 'ASC')
            ->setMaxResults(4)
            ->getQuery()
            ->getResult()
            ;
}

    public function findByPrixPage10Trie($price,$StockQuantity,$categorie){
        return $this->createQueryBuilder('b')
            ->andWhere('b.price >= :val','b.StockQuantity < :val1','b.categorie= :val2')
            ->setParameters(['val' =>$price,'val1'=>$StockQuantity,'val2'=>$categorie])
            ->orderBy('b.price', 'DESC')
            ->setMaxResults(4)
            ->getQuery()
            ->getResult()
            ;
    }

    public function findOneBySomeField($value)
    {
     $em = $this->getEntityManager();
     $req = $em->createQuery(
         "select b from App\Entity\Book b where b.price < :value order by b.editedAt DESC"

     )
         ->setParameter('value',$value)
        ;
     return $req->getResult();
    }

}
