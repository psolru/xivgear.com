<?php


namespace App\Services;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use XIVAPI\XIVAPI;

class AbstractService
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * AbstractService constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
}