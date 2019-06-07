<?php

namespace App\Repository;

use App\Entity\GearsetItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method GearsetItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method GearsetItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method GearsetItem[]    findAll()
 * @method GearsetItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GearsetItemRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GearsetItem::class);
    }

    // /**
    //  * @return GearsetItem[] Returns an array of GearsetItem objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?GearsetItem
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
