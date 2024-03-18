<?php

namespace App\Repository;

use App\Entity\MyBooks;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MyBooks>
 *
 * @method MyBooks|null find($id, $lockMode = null, $lockVersion = null)
 * @method MyBooks|null findOneBy(array $criteria, array $orderBy = null)
 * @method MyBooks[]    findAll()
 * @method MyBooks[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MyBooksRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MyBooks::class);
    }

//    /**
//     * @return MyBooks[] Returns an array of MyBooks objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?MyBooks
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
