<?php


namespace App\Services;

use App\Entity\GearSet;
use App\Entity\LodestoneCharacter;
use App\Entity\LodestoneClass;
use App\Services\Lodestone\ItemService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use stdClass;

class GearSetService extends AbstractService
{
    /**
     * @var GearsetItemService
     */
    private $gearsetItemService;
    /**
     * @var ItemService
     */
    private $itemService;
    /**
     * @var string
     */
    private $projectDir;

    /**
     * GearSetService constructor.
     * @param EntityManagerInterface $em
     * @param GearsetItemService $gearsetItemService
     * @param ItemService $itemService
     * @param string $projectDir
     */
    public function __construct(EntityManagerInterface $em, GearsetItemService $gearsetItemService, ItemService $itemService, string $projectDir)
    {
        parent::__construct($em);
        $this->gearsetItemService = $gearsetItemService;
        $this->itemService = $itemService;
        $this->projectDir = $projectDir;
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

        $total = 0;
        $offHand = false;
        foreach ($gearsetData->Gear as $slot => $gear) {
            $slot = lcfirst($slot);

            $gearsetItem = $this->gearsetItemService->getOrCreate(['slot' => $slot, 'gearset' => $gearSet]);
            $gearsetItem->setGearset($gearSet)
                ->setSlot($slot);

            $item = $this->itemService->getOrCreate(['lodestone_id' => $gear->Item->ID])
                ->setLodestoneId($gear->Item->ID)
                ->setNameEn($gear->Item->Name)
                ->setLevelItem($gear->Item->LevelItem)
                ->setLevelEquip($gear->Item->LevelEquip)
                ->setCategoryId($gear->Item->ItemUICategory->ID)
                ->setIconUrl($gear->Item->Icon);

            $this->em->persist($item);
            $gearsetItem->setItem($item);

            foreach($gear->Materia as $materia) {
                $materiaItem = $this->itemService->getOrCreate(['lodestone_id' => $materia->ID])
                    ->setLodestoneId($materia->ID)
                    ->setNameEn($materia->Name)
                    ->setIconUrl($materia->Icon);
                $this->em->persist($materiaItem);

                $gearsetItem->addMateria($materiaItem);
            }
            $this->em->persist($gearsetItem);

            if ($slot != 'soulCrystal')
                $total += $item->getLevelItem();

            if ($slot == 'offHand')
                $offHand = true;
        }

        $gearSet->setILevel(
            $offHand ? floor($total/13) : floor($total/12)
        );

        // store Character Image
        file_put_contents($this->projectDir.'/public/data/gearset/'.$character->getLodestoneId().'_'.strtolower($gearSet->getLodestoneClass()->getShortEn()).'.jpg', file_get_contents($character->getPortraitUrl().'?='.time()));

        $this->em->persist($gearSet);
        return $gearSet;
    }
}
