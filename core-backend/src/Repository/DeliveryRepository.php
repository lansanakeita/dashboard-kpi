<?php

namespace App\Repository;

use App\Entity\Delivery;
use DateInterval;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Delivery>
 *
 * @method Delivery|null find($id, $lockMode = null, $lockVersion = null)
 * @method Delivery|null findOneBy(array $criteria, array $orderBy = null)
 * @method Delivery[]    findAll()
 * @method Delivery[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DeliveryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Delivery::class);
    }

    //    /**
    //     * @return Delivery[] Returns an array of Delivery objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('d.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Delivery
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    // Méthode pour récupérer la liste des retard 
    public function findByDelay()
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.deliveredAt > d.deliveryExpected')
            ->getQuery()
            ->getResult();
    }


    // Méthode pour récupérer la liste des avances 
    public function findByAdvance()
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.deliveredAt < d.deliveryExpected')
            ->getQuery()
            ->getResult();
    }

    public function countByDayTime($dayTime)
    {
        return $this->createQueryBuilder('d')
            ->select('COUNT(d)')
            ->where('d.dayTime = :dayTime')
            ->setParameter('dayTime', $dayTime)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getDeliveryTrends(\DateTime $startDate, \DateTime $endDate)
    {
        $qb = $this->createQueryBuilder('d');

        $qb->select(
            'SUM(CASE WHEN d.deliveredAt > d.deliveryExpected THEN 1 ELSE 0 END) as lateDeliveries',
            'SUM(CASE WHEN d.deliveredAt = d.deliveryExpected THEN 1 ELSE 0 END) as onTimeDeliveries',
            'SUM(CASE WHEN d.deliveredAt < d.deliveryExpected THEN 1 ELSE 0 END) as earlyDeliveries',
            'COUNT(d.id) as totalDeliveries'
        )
            ->where('d.deliveredAt BETWEEN :start AND :end')
            ->setParameter('start', $startDate)
            ->setParameter('end', $endDate);

        return $qb->getQuery()->getSingleResult();
    }

    public function getDeliveryRates()
    {
        $qb = $this->createQueryBuilder('d');

        $qb->select(
            'SUM(CASE WHEN d.dayTime = \'diurne\' AND d.weekTime = \'weekday\' THEN 1 ELSE 0 END) as weekdayDiurne',
            'SUM(CASE WHEN d.dayTime = \'nocturne\' AND d.weekTime = \'weekend\' THEN 1 ELSE 0 END) as weekendNocturne',
            'SUM(CASE WHEN d.dayTime = \'diurne\' AND d.weekTime = \'weekend\' THEN 1 ELSE 0 END) as weekendDiurne',
            'SUM(CASE WHEN d.dayTime = \'nocturne\' AND d.weekTime = \'weekday\' THEN 1 ELSE 0 END) as weekdayNocturne'
        );

        return $qb->getQuery()->getScalarResult();
    }

    // Méthode pour récupérer la liste des livraisons a temps 
    public function findByOnTime()
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.deliveredAt = d.deliveryExpected')
            ->getQuery()
            ->getResult()
        ;
    }
}
