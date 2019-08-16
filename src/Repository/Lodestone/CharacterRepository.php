<?php

namespace App\Repository\Lodestone;

use App\Entity\Lodestone\Character;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Exception;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Character|null find($id, $lockMode = null, $lockVersion = null)
 * @method Character|null findOneBy(array $criteria, array $orderBy = null)
 * @method Character[]    findAll()
 * @method Character[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CharacterRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Character::class);
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
            ->andWhere('lc.updateFailed IS NULL')
            ->orderBy('lc.createdAt', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();
    }

    public function getRecentlyUpdated()
    {
        return $this->createQueryBuilder('lc')
            ->andWhere('lc.updateFailed IS NULL')
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
            ->andWhere('lc.updateFailed IS NULL')
            ->orderBy('lc.updatedAt', 'ASC')
            ->setMaxResults($itemCount)
            ->getQuery()
            ->getResult();
    }

    public function findAllExistingOnes()
    {
        $qb = $this->createQueryBuilder('lc');
        return $qb->andWhere('lc.updateFailed IS NULL')
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return Character[] Returns an array of Character objects
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
    public function findOneBySomeField($value): ?Character
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
