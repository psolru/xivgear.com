<?php

namespace App\Repository\Lodestone;

use App\Entity\Lodestone\CharacterLodestoneClass;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CharacterLodestoneClass|null find($id, $lockMode = null, $lockVersion = null)
 * @method CharacterLodestoneClass|null findOneBy(array $criteria, array $orderBy = null)
 * @method CharacterLodestoneClass[]    findAll()
 * @method CharacterLodestoneClass[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CharacterLodestoneClassRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CharacterLodestoneClass::class);
    }
}
