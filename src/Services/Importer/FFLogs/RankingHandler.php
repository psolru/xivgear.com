<?php


namespace App\Services\Importer\FFLogs;


use App\Entity\FFLogs\Encounter;
use App\Entity\FFLogs\Ranking;
use App\Entity\Lodestone\Character;
use App\Entity\Lodestone\LodestoneClass;
use DateTime;
use Lodestone\Api;
use Lodestone\Entity\ListView\ListView;
use stdClass;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\OutputInterface;

class RankingHandler extends Base
{
    protected $endpoint = '/rankings/encounter/${encounterId}';
    public static $consoleMode = false;

    /** @var OutputInterface[] $outputinterfaceSectionList */
    public static $outputInterfaceSectionList = [];

    public function import(Encounter $encounter, LodestoneClass $lodestoneClass, string $response, string $metric, ProgressBar $characterBar)
    {
        $data = json_decode($response);

        $characterBar->start(count($data->rankings));

        foreach ($data->rankings as $index => $rankingData)
        {
            $rank = $index+1;

            $characterBar->setMessage("Importing [Rank $rank] - {$rankingData->name} | {$rankingData->serverName}");
            $characterBar->advance();
            /**
             * If character is private - skip
             */
            if ($this->isRankingAnonymous($rankingData)) {
                $characterBar->setMessage('Anonymous character - skip');
                continue;
            }

            /**
             * DISABLED - SE is blocking after a few requests.
             * Todo - find a way around SE bot-protection
             */
//            $lodestoneId = $this->getCharacterID($rankingData);

            /**
             * continue when lookup failed
             */
//            if (!$lodestoneId)
//                continue;

//            $character = $this->getCharacterByLodestoneId($lodestoneId);
            $character = $this->em->getRepository(Character::class)->findOneBy([
                'name' => $rankingData->name,
                'server' => $rankingData->serverName
            ]);

            /**
             * Create character
             */
            if (!$character) {
                $character = new Character();
                $character->setName($rankingData->name)
                          ->setServer($rankingData->serverName)
                          ->setUpdatedAt(new DateTime(date('Y-m-d H:i:s', 0)))
                          ->setAutoAdded(true);

                $this->em->persist($character);
            }

            $ranking = $this->em->getRepository(Ranking::class)->findOneBy([
                'lodestone_class' => $lodestoneClass,
                'encounter' => $encounter,
                'metric' => $metric,
                'rank' => $rank
            ]);
            if (!$ranking)
                $ranking = new Ranking();

            $ranking->setLodestoneCharacter($character)
                    ->setLodestoneClass($lodestoneClass)
                    ->setEncounter($encounter)
                    ->setRank($rank)
                    ->setTotal($rankingData->total)
                    ->setDuration($rankingData->duration)
                    ->setDatetime(new DateTime(date('Y-m-d H:i:s', round($rankingData->startTime/1000))))
                    ->setFightId($rankingData->fightID)
                    ->setReportId($rankingData->reportID)
                    ->setServer($rankingData->serverName)
                    ->setRegion($rankingData->regionName)
                    ->setPatch($rankingData->patch)
                    ->setMetric($metric);

            $this->em->persist($ranking);
        }
        $this->em->flush();
        $characterBar->finish();
    }
    
    private function isRankingAnonymous(stdClass $data)
    {
        return $data->name == 'Anonymous' && $data->serverName == '';
    }

    private function searchForCharacter(string $name, string $server)
    {
        $api = new Api();
        return $api->searchCharacter($name, $server);
    }

    private function checkCharacterForPerfectMatch(ListView $res, stdClass $data)
    {
        foreach ($res->Results as $character)
        {
            if (
                $character->Name === $data->name &&
                 $data->serverName !== ''
            ) {
                return $character;
            }
        }
        return null;
    }

    private function getCharacterID(stdClass $data)
    {
        /**
         * FFLogs doesn't store the character ID
         * We need to look up name and server via XIVAPI-search...
         * fucked up if a char transfers to another server...¯\_(ツ)_/¯
         */
         $res = $this->searchForCharacter($data->name, $data->serverName);

        /**
         * Only process 100% matches (name / server)
         */
        $character = $this->checkCharacterForPerfectMatch($res, $data);

        if ($character)
            return $character->ID;

        return null;
    }

    private function getCharacterByLodestoneId(string $id)
    {
        return $this->em->getRepository(Character::class)->findOneBy(['lodestone_id' => $id]);
    }
}