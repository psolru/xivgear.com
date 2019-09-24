<?php


namespace App\Controller\Api;


use App\Entity\Lodestone\GearsetItem;
use App\Services\Lodestone\ItemService;

class ApiHelpers
{
    /**
     * @param GearsetItem|null $gearsetItem
     * @param bool $extended
     * @return array|null
     */
    public static function convertGearSetItemToArray($gearsetItem, $extended=false)
    {
        if (!$gearsetItem)
            return null;

        if ($extended)
        {
            $arr = ItemService::get($gearsetItem->getItemId(), true);

            $materiaList = [];
            foreach ($gearsetItem->getMateria() as $materiaItem)
            {
                $materiaList[] = ItemService::get($materiaItem->getId(), true);
            }

            $arr['materia'] = $materiaList ?: null;
        } else {
            $arr = [
                'id' => $gearsetItem->getItemId()
            ];

            $materiaList = [];
            foreach ($gearsetItem->getMateria() as $materiaItem)
            {
                $materiaList[] = $materiaItem->getId();
            }

            $arr['materia'] = $materiaList ?: null;
        }
        return $arr;
    }
}