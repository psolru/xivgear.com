<?php


namespace App\Services\Lodestone;

use App\Entity\Item;
use App\Services\AbstractService;

class ItemService extends AbstractService
{
    /**
     * @param array $criteria
     * @return Item
     */
    public function getOrCreate(array $criteria): Item
    {
        $gearsetItem = $this->getRepository(Item::class)->findOneBy($criteria);
        if ($gearsetItem)
            return $gearsetItem;
        return new Item();
    }
}