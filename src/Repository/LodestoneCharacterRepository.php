<?php

namespace App\Repository;

use App\Entity\LodestoneCharacter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method LodestoneCharacter|null find($id, $lockMode = null, $lockVersion = null)
 * @method LodestoneCharacter|null findOneBy(array $criteria, array $orderBy = null)
 * @method LodestoneCharacter[]    findAll()
 * @method LodestoneCharacter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LodestoneCharacterRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, LodestoneCharacter::class);
    }

    public function findOneByLodestoneId($lodestone_id)
    {
        return $this->createQueryBuilder('lc')
            ->andWhere('lc.lodestone_id = :id')
            ->setParameter('id', $lodestone_id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    // /**
    //  * @return LodestoneCharacter[] Returns an array of LodestoneCharacter objects
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
    public function findOneBySomeField($value): ?LodestoneCharacter
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
