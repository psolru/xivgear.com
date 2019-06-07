<?php


namespace App\Entity;

use stdClass;

trait itemInsertTrait
{
    /**
     * @param stdClass $data
     */
    public function insertXivapiFields(stdClass $data)
    {
        /** @var Item $this */
        $this->setLodestoneId($data->ID)
            ->setIconUrl($data->Icon)
            ->setCategoryId($data->ItemUICategory->ID)
            ->setLevelEquip($data->LevelEquip)
            ->setLevelItem($data->LevelItem)
            ->setNameEn($data->Name);
    }
}