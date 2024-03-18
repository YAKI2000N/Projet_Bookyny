<?php

namespace App\Repository;

use App\Entity\GBooky;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<GBooky>
 *
 * @method GBooky|null find($id, $lockMode = null, $lockVersion = null)
 * @method GBooky|null findOneBy(array $criteria, array $orderBy = null)
 * @method GBooky[]    findAll()
 * @method GBooky[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GBookyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GBooky::class);
    }

//    /**
//     * @return GBooky[] Returns an array of GBooky objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('g.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?GBooky
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
