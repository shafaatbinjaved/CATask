<?php

namespace App\Repository;

use App\Entity\Hotel;
use App\Entity\Review;
use DateTimeInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ReviewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Review::class);
    }

    public function getReviewsByHotelGroupedByInterval(
        Hotel $hotel,
        DateTimeInterface $from,
        DateTimeInterface $to,
        string $interval
    ): mixed
    {
        $datesInterval = match ($interval) {
            'DAY' => 'DAY(r.createdDate) AS interval',
            'WEEK' => 'WEEK(r.createdDate) AS interval',
            'MONTH' => 'MONTH(r.createdDate) AS interval',
        };

        $queryBuilder = $this->createQueryBuilder('r')
            ->select(['COUNT(r) as count', $datesInterval, 'AVG(r.score) as avgScore'])
            ->where('r.hotel = :hotel')
            ->andWhere('r.createdDate BETWEEN :from AND :to')
            ->groupBy('interval')
            ->setParameter('hotel', $hotel)
            ->setParameter('from', $from)
            ->setParameter('to', $to);

        $query = $queryBuilder->getQuery();

        return $query->getResult();
    }
}
