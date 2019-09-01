<?php

namespace App\Services\Importer\FFLogs;

use App\Entity\Lodestone\LodestoneClass;

class ClassesHandler extends Base
{
    protected $endpoint = '/classes';

    private function clearName(string $name)
    {
        $name = strtolower($name);
        $name = preg_replace('/[^A-z]/', '', $name);
        return $name;
    }

    public function getList(string $response)
    {
        $allClasses = $this->em->getRepository(LodestoneClass::class)->findAll();

        $data = json_decode($response);

        $classes = [];
        foreach ($data[0]->specs as $job)
        {
            foreach ($allClasses as $lodestoneClass)
            {
                if ($this->clearName($lodestoneClass->getNameEn()) == $this->clearName($job->name)) {
                    $classes[$job->id] = $lodestoneClass;
                }
            }
        }
        return $classes;
    }
}