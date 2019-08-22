<?php


namespace App\Services\Metrics;

use App\Entity\Lodestone\Character as Repo;
use App\Services\AbstractService;
use Doctrine\ORM\EntityManagerInterface;

class Character extends AbstractService
{
    /**
     * Character constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em);
    }

    public function getCreationByHourMetric()
    {
        $this->em->getRepository(Repo::class)->getCreationByHourMetric();
    }
}