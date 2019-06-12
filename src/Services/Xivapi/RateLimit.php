<?php


namespace App\Services\Xivapi;

use App\Entity\Param;
use App\Repository\ParamRepository;
use App\Services\AbstractService;
use Doctrine\ORM\EntityManagerInterface;

class RateLimit extends AbstractService
{
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em);
    }

    public function creationLimitReached()
    {}
}