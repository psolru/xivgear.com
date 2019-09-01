<?php

namespace App\Command\Import\Lodestone;

use App\Services\Importer\Lodestone\Item;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ItemCommand extends Command
{
    protected static $defaultName = 'import:lodestone:item';
    /**
     * @var Item
     */
    private $api;

    public function __construct(Item $api, string $name = null)
    {
        parent::__construct($name);
        $this->api = $api;
    }

    protected function configure()
    {
        $this
            ->setDescription('Importing specific or all items')
            ->addOption('name', null, InputOption::VALUE_OPTIONAL, 'Import specific item. eg. --name=ronkan\ grimoire')
            ->addOption('all', null, InputOption::VALUE_OPTIONAL, 'import/update all items', false)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io  = new SymfonyStyle($input, $output);
        $name  = $input->getOption('name');
        $all = $input->getOption('all');


        if ($name) {

            $io->note(sprintf('Importing %s', $name));
            $this->api->importByName($name);

        } else if ($all !== false) {

            $io->note('Updating all Items…');
            $this->api->importAll($output);

        } else {
            $io->error('Nothing done. Please see --help for info.');
            return;
        }
    }
}
