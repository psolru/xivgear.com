<?php


namespace App\Services\Importer\Lodestone;

use App\Entity\Lodestone\GearSet as GearSetEntity;
use App\Entity\Lodestone\Character as CharacterEntity;
use App\Entity\Lodestone\LodestoneClass;
use App\Services\AbstractService;
use App\Services\Lodestone\GearsetItemService;
use App\Services\Lodestone\ItemService;
use Doctrine\ORM\EntityManagerInterface;
use Lodestone\Entity\Character\Item as LodestoneApiItem;

class GearSet extends AbstractService
{
    /**
     * @var GearSetEntity
     */
    public $entity;
    /**
     * @var GearsetItemService
     */
    private $gearsetItemService;
    /**
     * @var int
     */
    private $itemLevelSum = 0;

    public function __construct(EntityManagerInterface $em, GearsetItemService $gearsetItemService)
    {
        parent::__construct($em);
        $this->gearsetItemService = $gearsetItemService;
    }

    public function getOrCreateEntity(array $criteria)
    {
        $gearSet = $this->em->getRepository(GearSetEntity::class)->findOneBy($criteria);

        $this->entity = $gearSet ?: new GearSetEntity();

        return $this;
    }

    public function setLodestoneCharacter(CharacterEntity $character)
    {
        $this->entity->setLodestoneCharacter($character);
        return $this;
    }

    public function setLodestoneClass(LodestoneClass $lodestoneClass)
    {
        $this->entity->setLodestoneClass($lodestoneClass);
        return $this;
    }

    private function convertAttributeNaming(string $name)
    {
        return str_replace(
            ' ',
            '',
            lcfirst($name)
        );
    }

    /**
     * @param array $data []
     * @return GearSet
     */
    public function addAttributes(array $data)
    {
        foreach ($data as $attribute)
        {
            $this->entity->setAttribute(
                $this->convertAttributeNaming($attribute->Name),
                $attribute->Value
            );
        }
        return $this;
    }

    /**
     * @param LodestoneApiItem $data
     */
    public function addGearSetItem(LodestoneApiItem $data)
    {
        // get item from redis
        $item = ItemService::get($data->Name);
        // create gearSetItem
        $gearSetItem = $this->gearsetItemService
                    ->getOrCreate(['gearset' => $this->entity])
                    ->setItemId($item->getId())
                    ->setSlot(lcfirst($data->Slot));

        foreach($data->Materia as $materia)
        {
            $item = ItemService::get($materia->Name);
            $gearSetItem->addMateria($item);
        }

        $this->em->persist($gearSetItem);

        // sum iLevel for later
        if (lcfirst($data->Slot) != 'soulCrystal')
            $this->itemLevelSum += $item->getLevelItem();

        // add gearSetItem to gearSet for later
        $this->entity->addGearsetItem($gearSetItem);
    }

    public function calculateILevel()
    {
        $offHand = false;
        foreach ($this->entity->getGearsetItems() as $gearsetItem)
        {
            if ($gearsetItem->getSlot() == 'offHand')
                $offHand = true;
        }

        $this->entity->setILevel(
            $offHand ? floor($this->itemLevelSum/13) : floor($this->itemLevelSum/12)
        );
    }
}




















