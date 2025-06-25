<?php


namespace App\Repository;

use App\Entity\Room;
use App\Model\SearchData;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Room>
 */
class RoomRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Room::class);
    }

    public function searchByTitle(string $query): array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.title LIKE :query')
            ->setParameter('query', '%'.$query.'%')
            ->orderBy('r.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Recherche filtrée selon SearchData
     */
    public function findByFilters(SearchData $searchData): array
    {
        $qb = $this->createQueryBuilder('r');

        if (!empty($searchData->equipments)) {
            $qb->join('r.equipments', 'e')
               ->andWhere('e IN (:equipments)')
               ->setParameter('equipments', $searchData->equipments);
        }

        if ($searchData->capacity !== null) {
            $qb->andWhere('r.capacity >= :capacity')
               ->setParameter('capacity', $searchData->capacity);
        }

        if (!empty($searchData->softwares)) {
            $qb->join('r.softwares', 's')
               ->andWhere('s IN (:softwares)')
               ->setParameter('softwares', $searchData->softwares);
        }

        if (!empty($searchData->advantages)) {
            $qb->join('r.advantages', 'a')
               ->andWhere('a IN (:advantages)')
               ->setParameter('advantages', $searchData->advantages);
        }

        $qb->groupBy('r.id'); 

        return $qb->getQuery()->getResult();
    }


//    /**
//     * @return Room[] Returns an array of Room objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Room
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
