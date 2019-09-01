<?php

namespace App\Command\Import\Lodestone\Queue;

use App\Entity\Lodestone\Character as CharacterEntity;
use App\Services\Importer\Lodestone\Character;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CharacterCommand extends Command
{
    protected static $defaultName = 'import:lodestone:queue:character';
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var Character
     */
    private $api;

    public function __construct(EntityManagerInterface $em, Character $api, string $name = null)
    {
        parent::__construct($name);
        $this->em = $em;
        $this->api = $api;
    }

    protected function configure()
    {
        $this ->setDescription('Add a short description for your command');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $charactersToUpdate = 2;
        $queue = $this->em->getRepository(CharacterEntity::class)->getUpdateQueue($charactersToUpdate);

        if (!$queue) {
            $io->block('Nothing to update.');
            return;
        }

        $progressBar = new ProgressBar($output, $charactersToUpdate);

        $progressBar->setFormat("%message%\n%current%/%max% [%bar%] %percent:3s%% %memory:6s%\n");
        $progressBar->setMessage('Starting…');

        $progressBar->start();
        /** @var $character CharacterEntity */
        foreach ($queue as $character)
        {
            $progressBar->setMessage("[{$character->getLodestoneId()} ] {$character->getName()} - {$character->getServer()}");

            try {
                $this->api->import($character->getLodestoneId());
            } catch (\Exception $e) {
                $io->error($e);
            }
            $progressBar->advance();
        }
        $progressBar->finish();
    }
}

















