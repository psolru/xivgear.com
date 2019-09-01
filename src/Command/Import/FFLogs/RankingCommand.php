<?php

namespace App\Command\Import\FFLogs;

use App\Services\Importer\FFLogs\Client;
use App\Services\Importer\FFLogs\RankingHandler;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RankingCommand extends Command
{
    protected static $defaultName = 'import:fflogs:rankings';

    /** @var Client */
    private $client;

    public function __construct(Client $client)
    {
        parent::__construct();
        $this->client = $client;
    }

    protected function configure()
    {
        $this->setDescription('Importing FFLogs Encounters');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        RankingHandler::$outputInterfaceSectionList = [
            'encounters' => $output->section(),
            'metrics' => $output->section(),
            'classes' => $output->section(),
            'characters' => $output->section()
        ];
        $this->client->importActiveRankings();
    }
}
