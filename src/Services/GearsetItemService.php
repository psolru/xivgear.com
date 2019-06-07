<?php


namespace App\Services;

use App\Entity\GearSet;
use App\Entity\GearsetItem;
use stdClass;

class GearsetItemService extends AbstractService
{
    /**
     * @param stdClass $data
     * @param GearSet $gearSet
     * @return mixed
     */
    public function createOrUpdate(stdClass $data, GearSet $gearSet)
    {
        dump($data);die;
        $gearsetItem = $this->em->getRepository(GearsetItem::class)->findOneBy(['gearset' => $gearSet, 'slot' => $data->Slot]);
        if (!$gearsetItem )
            $gearsetItem = new GearsetItem();
        $gearsetItem->insertXivapiFields($data);

        $gearsetItem->setGearset($gearSet);

        $this->em->persist($gearsetItem);

        return $gearsetItem;
    }
}