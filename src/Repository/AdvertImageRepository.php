<?php

namespace App\Repository;

use App\Entity\AdvertImage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AdvertImage>
 *
 * @method AdvertImage|null find($id, $lockMode = null, $lockVersion = null)
 * @method AdvertImage|null findOneBy(array $criteria, array $orderBy = null)
 * @method AdvertImage[]    findAll()
 * @method AdvertImage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdvertImageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AdvertImage::class);
    }

//    /**
//     * @return AdvertImage[] Returns an array of AdvertImage objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?AdvertImage
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
