<?php

namespace App\Command\Import\FFLogs;

use App\Entity\FFLogs\Encounter;
use App\Entity\FFLogs\Zone;
use App\Services\Importer\FFLogs\Client;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class EncounterCommand extends Command
{
    protected static $defaultName = 'import:fflogs:encounters';

    /** @var Client */
    private $client;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * EncounterCommand constructor.
     * @param EntityManagerInterface $em
     * @param Client $client
     */
    public function __construct(EntityManagerInterface $em, Client $client)
    {
        parent::__construct();
        $this->client = $client;
        $this->em = $em;
    }

    protected function configure()
    {
        $this->setDescription('Importing FFLogs Encounters');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->client->importEncounters();
    }
}
