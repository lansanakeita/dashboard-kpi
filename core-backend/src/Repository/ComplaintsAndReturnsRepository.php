<?php

namespace App\Repository;

use App\Entity\ComplaintsAndReturns;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ComplaintsAndReturns>
 *
 * @method ComplaintsAndReturns|null find($id, $lockMode = null, $lockVersion = null)
 * @method ComplaintsAndReturns|null findOneBy(array $criteria, array $orderBy = null)
 * @method ComplaintsAndReturns[]    findAll()
 * @method ComplaintsAndReturns[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComplaintsAndReturnsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ComplaintsAndReturns::class);
    }

//    /**
//     * @return ComplaintsAndReturns[] Returns an array of ComplaintsAndReturns objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ComplaintsAndReturns
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
