<?php

namespace App\Repository;

use App\Entity\Response;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Response>
 *
 * @method Response|null find($id, $lockMode = null, $lockVersion = null)
 * @method Response|null findOneBy(array $criteria, array $orderBy = null)
 * @method Response[]    findAll()
 * @method Response[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResponseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Response::class);
    }

    /**
     * Récupère la moyenne globale des notes de Satisfaction.
     *
     * @return float La note moyenne.
     */
    public function findAverageRating()
    {
        return $this->createQueryBuilder('r')
            ->select('avg(r.value) as averageRating')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Récupère la note moyenne par client.
     *
     * @return array Retourne un tableau avec le nom du client et sa note moyenne.
     */
    public function findAverageRatingPerCustomer()
    {
        return $this->createQueryBuilder('r')
            ->innerJoin('r.delivery', 'd')
            ->innerJoin('d.order', 'o')
            ->innerJoin('o.customer', 'c') // Jointure avec la table customer
            ->groupBy('c.name')
            ->select('c.name as customerName, avg(r.value) as averageRating')
            ->getQuery()
            ->getResult();
    }

    /**
     * Récupère la note moyenne au fil du temps en fonction de l'année et du mois spécifiés.
     *
     * @param int|null $year L'année pour laquelle récupérer la note moyenne. Si null, récupère la note moyenne pour toutes les années.
     * @param int|null $month Le mois pour lequel récupérer la note moyenne. Si null, récupère la note moyenne pour tous les mois.
     * @throws Some_Exception_Class Une description de l'exception qui peut être levée.
     * @return mixed Le résultat de la requête pour récupérer la note moyenne au fil du temps.
     */
    public function findAverageRatingOverTime($year = null, $month = null)
    {
        $qb = $this->createQueryBuilder('r')
            ->innerJoin('r.delivery', 'd')
            ->groupBy('year(d.delivered_at), month(d.delivered_at)')
            ->select('year(d.delivered_at) as year, month(d.delivered_at) as month, avg(r.value) as averageRating')
            ->orderBy('year', 'ASC')
            ->addOrderBy('month', 'ASC');

        if ($year !== null) {
            $qb->andWhere('year(d.delivered_at) = :year')
                ->setParameter('year', $year);
        }

        if ($month !== null) {
            $qb->andWhere('month(d.delivered_at) = :month')
                ->setParameter('month', $month);
        }

        return $qb->getQuery()->getResult();
    }

    public function getAverageRecommandationScore()
    {
        $query = $this->createQueryBuilder('r')
            ->select('SUM(r.value) as totalValue, COUNT(r.id) as totalCount')
            ->join('r.question', 'q')
            ->where('q.description = :description')
            ->setParameter('description', 'Recommanderiez-vous notre entreprise à vos amis ou à votre famille ?')
            ->getQuery();

        return $query->getSingleResult();
    }
}
