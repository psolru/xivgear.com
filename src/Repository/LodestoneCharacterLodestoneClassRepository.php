<?php

namespace App\Repository;

use App\Entity\LodestoneCharacterLodestoneClass;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method LodestoneCharacterLodestoneClass|null find($id, $lockMode = null, $lockVersion = null)
 * @method LodestoneCharacterLodestoneClass|null findOneBy(array $criteria, array $orderBy = null)
 * @method LodestoneCharacterLodestoneClass[]    findAll()
 * @method LodestoneCharacterLodestoneClass[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LodestoneCharacterLodestoneClassRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, LodestoneCharacterLodestoneClass::class);
    }

    // /**
    //  * @return LodestoneCharacterLodestoneClass[] Returns an array of LodestoneCharacterLodestoneClass objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?LodestoneCharacterLodestoneClass
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
