<?php


namespace App\Services;

use Doctrine\ORM\EntityManagerInterface;

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