<?php


namespace App\Services\Lodestone;

use App\Entity\Lodestone\GearsetItem;
use App\Services\AbstractService;

class GearsetItemService extends AbstractService
{
    /**
     * @param array $criteria
     * @return GearsetItem
     */
    public function getOrCreate(array $criteria): GearsetItem
    {
        $gearsetItem = $this->em->getRepository(GearsetItem::class)->findOneBy($criteria);
        if ($gearsetItem)
            return $gearsetItem;
        return new GearsetItem();
    }
}