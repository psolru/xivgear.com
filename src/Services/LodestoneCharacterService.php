<?php


namespace App\Services;

use App\Entity\LodestoneCharacter;
use App\Entity\LodestoneCharacterLodestoneClass;
use App\Entity\LodestoneClass;
use XIVAPI\XIVAPI;

class LodestoneCharacterService extends AbstractService
{
    /**
     * @var XIVAPI
     */
    private $api;

    /**
     * @param $lodestone_id
     * @return LodestoneCharacter
     */
    public function get($lodestone_id): LodestoneCharacter
    {
        $character = $this->getRepository(LodestoneCharacter::class)->findOneByLodestoneId($lodestone_id);
        if (!$character) {
            $character = $this->create($lodestone_id);
            $character->setJustCreated(true);
        }
        return $character;
    }

    /**
     * @param $lodestone_id
     * @return LodestoneCharacter
     */
    public function create($lodestone_id): LodestoneCharacter
    {
        $character = new LodestoneCharacter();
        $character->setLodestoneId($lodestone_id);

        $character = $this->update($character);

        return $character;
    }

    /**
     * @param LodestoneCharacter $character
     * @param null $data
     * @return LodestoneCharacter
     */
    public function update(LodestoneCharacter $character, $data=null)
    {
        if (!$data)
            $data = $this->api->character->get($character->getLodestoneId(), [], true);

        if ($data->Info->Character->State == 2) {
            $character->setName($data->Character->Name);
            $character->setServer($data->Character->Server);
            $character->setAvatarUrl($data->Character->Avatar);
            $character->setPortraitUrl($data->Character->Portrait);

            foreach ($data->Character->ClassJobs as $idMashup => $classJob) {
                $classId = explode('_', $idMashup)[1];
                $class = $this->getRepository(LodestoneClass::class)->findOneBy(['lodestone_id' => $classId]);
                if (!$class)
                    continue;

                $map = null;
                if ($character->getId())
                    $map = $this->getRepository(LodestoneCharacterLodestoneClass::class)->findOneBy(['lodestone_character' => $character, 'lodestone_class' => $class]);
                if (!$map) {
                    $map = new LodestoneCharacterLodestoneClass();
                    $map->setLodestoneCharacter($character);
                    $map->setLodestoneClass($class);
                }
                $map->setLevel($classJob->Level);
                $map->setExperience($classJob->ExpLevel);
                $map->setExperienceTotal($classJob->ExpLevelMax);
                $this->em->persist($map);
            }
        }
        $this->em->persist($character);
        $this->em->flush();

        return $character;
    }
}