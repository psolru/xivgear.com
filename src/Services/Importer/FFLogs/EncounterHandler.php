<?php


namespace App\Services\Importer\FFLogs;


use App\Entity\FFLogs\Encounter;
use App\Entity\FFLogs\Zone;
use Doctrine\ORM\EntityManagerInterface;

class EncounterHandler extends Base
{
    private static $activeZones = [
        'The Unending Coil of Bahamut' => 19,
        'The Weapon\'s Refrain' => 23,
        'Eden\'s Gate' => 29
    ];

    protected $endpoint = '/zones';
    /** @var string */
    private $lang;

    public function __construct(EntityManagerInterface $em, string $lang='en')
    {
        parent::__construct($em);
        $this->lang = $lang;
    }

    public function import(string $response)
    {
        $response = json_decode($response);
        $nameField = 'setName'.ucfirst($this->lang);

        foreach ($response as $zoneData) {
            $zone = $this->em->getRepository(Zone::class)->findOneBy(['fflogs_id' => $zoneData->id])
                ?: new Zone();

            if (in_array($zoneData->id, self::$activeZones))
                $zone->setActive(true);

            $zone->setFflogsId($zoneData->id)
                ->$nameField($zoneData->name);

            foreach ($zoneData->encounters as $encounterData)
            {
                $encounter = $this->em->getRepository(Encounter::class)->findOneBy(['fflogs_id' => $encounterData->id])
                    ?: new Encounter();

                $encounter->setFflogsId($encounterData->id)
                    ->$nameField($encounterData->name);

                $this->em->persist($encounter);
                $zone->addEncounter($encounter);
            }
            $this->em->persist($zone);
            $this->em->flush();
        }
    }
}