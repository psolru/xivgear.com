<?php


namespace App\Services;


use App\Entity\GearSet;
use App\Entity\GearsetItem;
use App\Entity\LodestoneCharacter;
use App\Entity\LodestoneClass;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use stdClass;

class GearSetService extends AbstractService
{
    /**
     * @var ItemService
     */
    private $itemService;
    /**
     * @var GearsetItem
     */
    private $gearsetItemService;

    /**
     * GearSetService constructor.
     * @param EntityManagerInterface $em
     * @param ItemService $itemService
     * @param GearsetItemService $gearsetItemService
     */
    public function __construct(EntityManagerInterface $em, ItemService $itemService, GearsetItemService $gearsetItemService)
    {
        parent::__construct($em);
        $this->itemService = $itemService;
        $this->gearsetItemService = $gearsetItemService;
    }

    /**
     * @param stdClass $gearsetData
     * @param LodestoneCharacter $character
     * @return GearSet
     * @throws Exception
     */
    public function createOrUpdate(stdClass $gearsetData, LodestoneCharacter $character): GearSet
    {
        $class = $this->getRepository(LodestoneClass::class)->findOneBy(['lodestone_id' => $gearsetData->Job->ID]);
        if (!$class)
            throw new Exception("Class not found. Maybe there was a Patch? owo");

        $gearSet = $this->getRepository(GearSet::class)->findOneBy(['lodestone_character' => $character, 'lodestone_class' => $class]);
        if (!$gearSet) {
            $gearSet = new GearSet();
            $gearSet->setLodestoneClass($class);
        }

        foreach ($gearsetData->Attributes as $attribute) {
            $name = str_replace(
                ' ',
                '',
                lcfirst($attribute->Attribute->Name)
            );
            $gearSet->setAttribute($name, $attribute->Value);
        }
        $gearSet->setILevel(0);

        foreach ($gearsetData->Gear as $slot => $gear) {
            $slot = lcfirst($slot);
            $gear->Item->Slot = $slot;

            $this->gearsetItemService->createOrUpdate($gear->Item, $gearSet);

            die();
        }

        $this->em->persist($gearSet);
        return $gearSet;
    }
}