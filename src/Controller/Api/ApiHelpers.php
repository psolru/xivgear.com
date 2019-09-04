<?php


namespace App\Controller\Api;


use App\Entity\Lodestone\GearsetItem;
use App\Services\Lodestone\ItemService;

class ApiHelpers
{
    public static function convertGearSetItemToArray(GearsetItem $gearsetItem, bool $extended=false)
    {
        if ($extended)
        {
            $arr = ItemService::getAsArray($gearsetItem->getItemId());

            $materiaList = [];
            foreach ($gearsetItem->getMateria() as $materiaItem)
            {
                $materiaList[] = ItemService::getAsArray($materiaItem->getId());
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