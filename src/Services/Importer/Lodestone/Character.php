<?php

namespace App\Services\Importer\Lodestone;


use App\Entity\Lodestone\Character as CharacterEntity;
use App\Entity\Lodestone\CharacterLodestoneClass;
use App\Entity\Lodestone\LodestoneClass;
use App\Services\AbstractService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Lodestone\Api;
use Lodestone\Entity\Character\CharacterProfile;
use Lodestone\Entity\ListView\ListView;

class Character extends AbstractService
{
    /** @var Api*/
    private $api;

    /** @var CharacterProfile */
    private $lodestoneData;

    /** @var CharacterEntity */
    private $entity;

    /** @var LodestoneClass[] */
    private $classes = [];
    /**
     * @var GearSet
     */
    private $gearsetImporter;
    private $projectDir;

    public function __construct(EntityManagerInterface $em, GearSet $gearsetImporter, $projectDir)
    {
        parent::__construct($em);
        $this->api = new Api();
        $this->gearsetImporter = $gearsetImporter;
        $this->projectDir = $projectDir;
    }

    private function checkCharacterForPerfectMatch(ListView $res, string $name)
    {
        foreach ($res->Results as $character)
        {
            if (
                $character->Name === $name
            ) {
                return $character;
            }
        }
        return null;
    }

    private function searchForCharacter(string $name, string $server): ListView
    {
        $api = new Api();
        return $api->searchCharacter($name, $server);
    }

    public function getCharacterIDBySearch($name, $server)
    {
        /**
         * FFLogs doesn't store the character ID
         * We need to look up name and server via XIVAPI-search...
         * fucked up if a char transfers to another server...¯\_(ツ)_/¯
         */
        $res = $this->searchForCharacter($name, $server);

        /**
         * Only process 100% matches (name / server)
         */
        $character = $this->checkCharacterForPerfectMatch($res, $name);

        if ($character)
            return $character->ID;

        return null;
    }

    public function import(string $lodestoneId)
    {
        $this->getOrCreateEntity($lodestoneId);

        $this->parseLodestone($lodestoneId);

        /**
         * If Character would be created directly via LodestoneID, eg. over /characters/{lodestone_id}, check by name and server if there already is one
         * Needed since we load characters from FFLogs, which are mapped by name/server INSTEAD of LodestoneID
         */
        if (!$this->entity->getId())
            $this->loadCharacterByNameAndServer();

        $this->loadBasicInfo();
        $this->loadClasses();
        $this->loadGearSet();

        $this->entity->setUpdatedAt(new \DateTime());
        $this->entity->setAutoAdded(false);

        $this->em->persist($this->entity);
        $this->em->flush();

        return $this->entity;
    }

    private function getLodestoneClass(string $id)
    {
        /**
         * Get by Cache
         */
        if ($this->classes)
            return $this->classes[$id];

        /**
         * else create Cache
         */
        foreach ($this->em->getRepository(LodestoneClass::class)->findAll() as $lodestoneClass)
        {
            $this->classes[$lodestoneClass->getLodestoneId()] = $lodestoneClass;
        }
        return $this->classes[$id];
    }

    private function parseLodestone($id)
    {
        try {
            $this->lodestoneData = $this->api->getCharacter($id);
        }
        catch (Exception $e) {
            throw new Exception('request failed for '.$id);
        }
    }

    private function getOrCreateEntity(string $id)
    {
        $character = $this->em->getRepository(CharacterEntity::class)->findOneBy(['lodestone_id' => $id]);

        $this->entity = $character ?: new CharacterEntity();
    }

    private function loadBasicInfo()
    {
        $data = $this->lodestoneData;
        $this->entity->setLodestoneId($data->ID)
                     ->setName($data->Name)
                     ->setServer($data->Server)
                     ->setAvatarUrl($data->Avatar)
                     ->setPortraitUrl($data->Portrait);
    }

    private function loadClasses()
    {
        foreach ($this->lodestoneData->ClassJobs as $classJob)
        {
            $map = null;
            if ($this->entity->getId())
            {
                $map = $this->em->getRepository(CharacterLodestoneClass::class)->findOneBy([
                    'lodestone_class' => $this->getLodestoneClass($classJob->JobID),
                    'lodestone_character' => $this->entity
                ]);
            }

            if (!$map)
                $map = new CharacterLodestoneClass();

            $map->setLodestoneClass($this->getLodestoneClass($classJob->JobID))
                ->setLevel($classJob->Level)
                ->setExperience($classJob->ExpLevel)
                ->setExperienceTotal($classJob->ExpLevelMax);

            $this->em->persist($map);
            $this->entity->addLodestoneClassMapping($map);
        }
    }

    private function loadGearSet()
    {
        $lodestoneClass = $this->getLodestoneClass($this->lodestoneData->ActiveClassJob->JobID);
        $gearSet = $this->gearsetImporter->getOrCreateEntity([
            'lodestone_character' => $this->entity,
            'lodestone_class' => $lodestoneClass
        ]);

        $gearSet->setLodestoneCharacter($this->entity)
                ->setLodestoneClass($lodestoneClass)
                ->addAttributes($this->lodestoneData->Attributes);

        foreach ($this->lodestoneData->Gear as $item)
        {
            $gearSet->addGearSetItem($item);
        }
        $gearSet->calculateILevel();

        // store Character Image
        file_put_contents(
            $this->projectDir.'/public/data/gearset/'.$this->entity->getLodestoneId().'_'.strtolower($lodestoneClass->getShortEn()).'.jpg',
            file_get_contents($this->entity->getPortraitUrl().'?='.time())
        );

        $this->em->persist($gearSet->entity);
        $this->entity->addGearSet($gearSet->entity);
    }

    public function setUpdateFailed(CharacterEntity $character)
    {
        $character->setUpdateFailed(true);
        $this->em->persist($character);
        $this->em->flush();
    }

    private function loadCharacterByNameAndServer()
    {
        $character = $this->em->getRepository(CharacterEntity::class)->findOneBy([
            'name' => $this->lodestoneData->Name,
            'server' => $this->lodestoneData->Server
        ]);
        if ($character)
            $this->entity = $character;
    }
}
