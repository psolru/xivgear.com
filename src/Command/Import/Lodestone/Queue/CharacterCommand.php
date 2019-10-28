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

        $charactersToUpdate = 100;
        $queue = $this->em->getRepository(CharacterEntity::class)->getUpdateQueue($charactersToUpdate);

        /** @var $character CharacterEntity */
        foreach ($queue as $character) {
            $character->setInUpdate(true);
            $this->em->persist($character);
        }
        $this->em->flush();

        if (!$queue) {
            $io->block('Nothing to update.');
            return;
        }

        shuffle($queue);

        /** @var $character CharacterEntity */
        foreach ($queue as $key => $character)
        {
            try {
                if (!$character->getLodestoneId())
                {
                    $id = $this->api->getCharacterIDBySearch($character->getName(), $character->getServer());

                    if (!$id) {
                        $this->api->increaseUpdateFailed($character);
                        throw new \Exception('Character not found');
                    }
                    $statusMsg = "done";

                    $character->setLodestoneId($id);
                }
                $this->api->import($character->getLodestoneId());
            } catch (\Exception $e) {
                $this->api->increaseUpdateFailed($character);
                $statusMsg = $e->getMessage();
            }
            $character->setInUpdate(false);
            $this->em->persist($character);
            $this->em->flush();
            echo ($key+1)." / $charactersToUpdate | ".$character->getName()." - ".$statusMsg."\n";
        }
    }
}
