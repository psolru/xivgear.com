<?php

namespace App\Command\Import\Lodestone;

use App\Services\Importer\Lodestone\Character;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CharacterCommand extends Command
{
    protected static $defaultName = 'import:lodestone:character';
    /**
     * @var Character
     */
    private $api;

    public function __construct(Character $api, string $name = null)
    {
        parent::__construct($name);
        $this->api = $api;
    }

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addOption('id', null, InputOption::VALUE_REQUIRED, 'Import specific character by ID. eg. --id=11756305')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io  = new SymfonyStyle($input, $output);
        $id = $input->getOption('id');

        $io->note(sprintf('Importing %s', $id));

        $this->api->import($id);
    }
}
