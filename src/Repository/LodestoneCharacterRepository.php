<?php

namespace App\Repository;

use App\Entity\LodestoneCharacter;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Exception;
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

    /**
     * @param $lodestone_id
     * @return mixed
     * @throws NonUniqueResultException
     */
    public function findOneByLodestoneId($lodestone_id)
    {
        return $this->createQueryBuilder('lc')
            ->andWhere('lc.lodestone_id = :id')
            ->setParameter('id', $lodestone_id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getRecentlyAdded()
    {
        return $this->createQueryBuilder('lc')
            ->orderBy('lc.createdAt', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();
    }

    public function getRecentlyUpdated()
    {
        return $this->createQueryBuilder('lc')
            ->orderBy('lc.updatedAt', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param int $itemCount
     * @return mixed
     * @throws Exception
     */
    public function getUpdateQueue(int $itemCount)
    {
        $qb = $this->createQueryBuilder('lc');

        return $qb->andWhere('lc.updatedAt <= :date')
            ->setParameter('date', new DateTime('- 6 hours'))
            ->orderBy('lc.updatedAt', 'ASC')
            ->setMaxResults($itemCount)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return mixed
     */
    public function findAllActiveOnes()
    {
        $qb = $this->createQueryBuilder('lc');

        return $qb->andWhere('lc.xivapiStatus = 2')
            ->getQuery()
            ->getResult();
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
