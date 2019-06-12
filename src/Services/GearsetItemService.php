<?php


namespace App\Services;

use App\Entity\GearsetItem;

class GearsetItemService extends AbstractService
{
    /**
     * @param array $criteria
     * @return GearsetItem
     */
    public function getOrCreate(array $criteria): GearsetItem
    {
        $gearsetItem = $this->getRepository(GearsetItem::class)->findOneBy($criteria);
        if ($gearsetItem)
            return $gearsetItem;
        return new GearsetItem();
    }
}