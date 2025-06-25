<?php

namespace App\Repository;

use App\Entity\Notification;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class NotificationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Notification::class);
    }

    public function countNotificationsWithinFiveDays(): int
    {
        $now = new \DateTime();
        $limit = (new \DateTime())->modify('+5 days');

        return $this->createQueryBuilder('n')
            ->join('n.reservation', 'r')
            ->andWhere('r.date BETWEEN :now AND :limit')
            ->andWhere('n.is_read = false') 
            ->setParameter('now', $now)
            ->setParameter('limit', $limit)
            ->select('COUNT(n.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }
}
