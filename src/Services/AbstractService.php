<?php


namespace App\Services;

use Doctrine\Common\Persistence\ObjectRepository;
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

    /**
     * @param $class
     * @return ObjectRepository
     */
    public function getRepository($class)
    {
        return $this->em->getRepository($class);
    }

    /**
     * @return XivapiWrapper
     */
    public function getXivapiWrapper(): XivapiWrapper
    {
        return new XivapiWrapper();
    }
}