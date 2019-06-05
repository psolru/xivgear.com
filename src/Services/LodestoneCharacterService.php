<?php


namespace App\Services;

use App\Entity\LodestoneCharacter;
use App\Repository\LodestoneCharacterRepository;
use Doctrine\ORM\EntityManagerInterface;
use XIVAPI\XIVAPI;

class LodestoneCharacterService
{
    /**
     * @var XIVAPI
     */
    private $api;
    /**
     * @var LodestoneCharacterRepository
     */
    private $repository;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * LodestoneCharacterService constructor.
     * @param LodestoneCharacterRepository $repository
     * @param EntityManagerInterface $em
     */
    public function __construct(LodestoneCharacterRepository $repository, EntityManagerInterface $em)
    {
        $this->api = new XIVAPI();
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * @param $lodestone_id
     * @return LodestoneCharacter
     */
    public function get($lodestone_id): LodestoneCharacter
    {
        $character = $this->repository->findOneByLodestoneId($lodestone_id);
        if (!$character) {
            $character = $this->create($lodestone_id);
        }
        return $character;
    }

    /**
     * @param $lodestone_id
     * @return LodestoneCharacter
     */
    public function create($lodestone_id): LodestoneCharacter
    {
        $data = $this->api->character->get($lodestone_id, [], true);

        $character = new LodestoneCharacter();
        $character->setLodestoneId($lodestone_id);

        $character->setJustCreated(true);

        if ($data->Info->Character->State == 2) {
            $character->setName($data->Character->Name);
            $character->setServer($data->Character->Server);
            $character->setAvatarUrl($data->Character->Avatar);
            $character->setPortraitUrl($data->Character->Portrait);
        }

        $this->em->persist($character);
        $this->em->flush();
        return $character;
    }
}