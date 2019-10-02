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

    public function getRecentlyAdded(int $count)
    {
        return $this->defaultQueryBuilder()
            ->orderBy('lc.createdAt', 'DESC')
            ->setMaxResults($count)
            ->getQuery()
            ->getResult();
    }

    public function getRecentlyUpdated(int $count)
    {
        return $this->defaultQueryBuilder()
            ->orderBy('lc.updatedAt', 'DESC')
            ->setMaxResults($count)
            ->getQuery()
            ->getResult();
    }

    public function getUpdateQueue(int $itemCount)
    {
        return $this->createQueryBuilder('lc')
            ->andWhere('lc.updateFailed IS NULL')
            ->andWhere('lc.updatedAt <= :date')
            ->setParameter('date', new DateTime('- 6 hours'))
            ->orderBy('lc.updatedAt', 'ASC')
            ->setMaxResults($itemCount)
            ->getQuery()
            ->getResult();
    }

    public function findAllExistingOnes()
    {
        return $this->defaultQueryBuilder()
            ->getQuery()
            ->getResult();
    }

    public function getCreationByHourMetric()
    {
        $res = $this->defaultQueryBuilder()
            ->addSelect('COUNT(lc) as creations')
            ->addSelect('HOUR(createdAt) as hour')
            ->addGroupBy('hour')
            ->addOrderBy('hour')
            ->getQuery()
            ->getResult();
        dump($res);
        die;
    }

    private function defaultQueryBuilder() {
        return $this->createQueryBuilder('lc')
            ->andWhere('lc.autoAdded = 0')
            ->andWhere('lc.updateFailed IS NULL');
    }

}
