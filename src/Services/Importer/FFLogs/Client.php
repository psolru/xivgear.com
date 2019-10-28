<?php

namespace App\Services\Importer\FFLogs;

use App\Entity\FFLogs\Encounter;
use App\Entity\Lodestone\LodestoneClass;
use App\Services\AbstractService;
use App\Services\Request\Http;
use Symfony\Component\Console\Helper\ProgressBar;

class Client extends AbstractService
{
    /** @var LodestoneClass[] $classes */
    private $classes = [];
    private $consoleMode;

    public function setConsoleMode(bool $state)
    {
        $this->consoleMode = $state;
    }

    private function loadClasses()
    {
        if ($this->classes)
            return;

        $handler = new ClassesHandler($this->em);

        $response = $this->requestFFLogs($handler->generateUrl());

        $this->classes = $handler->getList($response);
    }

    /**
     * @param array $langList
     */
    public function importEncounters(array $langList=['de', 'en', 'fr', 'jp'])
    {
        foreach ($langList as $lang)
        {
            $handler = new EncounterHandler($this->em, $lang);

            $response = $this->requestFFLogs($handler->generateUrl($lang));

            $handler->import($response);
        }
    }

    public function importActiveRankings()
    {
        $this->loadClasses();
        /**
         * https://www.fflogs.com/help/rdps
         * dps => adps
         */
        $metricList = ['dps', 'hps'];

        $handler = new RankingHandler($this->em);

        $encounterList = $this->em->getRepository(Encounter::class)->findAll();

        if (!$encounterList)
            throw new \Exception('No Encounter activated.');

        $encounterBar = new ProgressBar(RankingHandler::$outputInterfaceSectionList['encounters']);
        $encounterBar->setFormat("%message%\n%current%/%max% [%bar%] %percent:3s%% %memory:6s%\n");

        $metricBar = new ProgressBar(RankingHandler::$outputInterfaceSectionList['metrics']);
        $metricBar->setFormat("%message%\n%current%/%max% [%bar%] %percent:3s%% %memory:6s%\n");

        $classesBar = new ProgressBar(RankingHandler::$outputInterfaceSectionList['classes']);
        $classesBar->setFormat("%message%\n%current%/%max% [%bar%] %percent:3s%% %memory:6s%\n");

        $characterBar = new ProgressBar(RankingHandler::$outputInterfaceSectionList['characters']);
        $characterBar->setFormat("%message%\n%current%/%max% [%bar%] %percent:3s%% %memory:6s%\n");

        $encounterBar->start(count($encounterList));
        foreach ($encounterList as $encounter)
        {
            if (!$encounter->getFflogsZone()->isActive())
                continue;

            $encounterBar->setMessage("Importing {$encounter->getNameEn()}");
            $encounterBar->advance();

            $metricBar->start(count($metricList));
            $metricBar->setMessage("Starting Metrics");

            foreach ($metricList as $metric)
            {
                $metricBar->setMessage("Importing $metric");
                $metricBar->advance();

                $classesBar->start(count($this->classes));
                $classesBar->setMessage("Starting Classes");

                foreach ($this->classes as $spec => $lodestoneClass)
                {
                    $classesBar->setMessage("Importing {$lodestoneClass->getNameEn()}");
                    $classesBar->advance();
                    $handler->setParam([
                        'class' => 1, /** class is fix, since there are no different subclasses (specs) like in wow */
                        'encounterId' => $encounter->getFflogsId(),
                        'metric' => $metric,
                        'spec' => $spec
                    ]);

                    $response = $this->requestFFLogs($handler->generateUrl());
                    $handler->import(
                        $encounter,
                        $lodestoneClass,
                        $response,
                        $metric,
                        $characterBar
                    );
                }
                $classesBar->finish();
            }
            $metricBar->finish();
        }
        $encounterBar->finish();
    }

    private function requestFFLogs(string $url): string
    {
        return (new Http($url))->request()->getBody();
    }
}