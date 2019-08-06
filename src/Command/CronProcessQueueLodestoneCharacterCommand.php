<?php

namespace App\Command;

use App\Entity\LodestoneCharacter;
use App\Repository\LodestoneCharacterRepository;
use App\Services\LodestoneCharacterService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Validator\Constraints\Date;

class CronProcessQueueLodestoneCharacterCommand extends Command
{
    protected static $defaultName = 'cron:process:queue:lodestone-character';

    /** @var LodestoneCharacterRepository */
    private $lcRepository;
    /** @var LodestoneCharacterService */
    private $lcService;
    /** @var EntityManagerInterface */
    private $em;

    /**
     * CronProcessQueueLodestoneCharacterCommand constructor.
     * @param LodestoneCharacterRepository $lcRepository
     * @param LodestoneCharacterService $lcService
     * @param EntityManagerInterface $em
     */
    public function __construct(
        LodestoneCharacterRepository $lcRepository,
        LodestoneCharacterService $lcService,
        EntityManagerInterface $em
    ) {
        $this->lcRepository = $lcRepository;
        $this->lcService = $lcService;

        parent::__construct();
        $this->em = $em;
    }

    protected function configure()
    {
        $this
            ->setDescription('Process next few characters of update-queue.')
        ;
        $this->setHidden(true);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $queue = $this->lcRepository->getUpdateQueue(5);

        if ($queue) {

            $ids = [];
            /** @var $character LodestoneCharacter */
            foreach ($queue as $character) {
                $ids[] = $character->getLodestoneId();
            }

            $ids = implode(',', $ids);

            $url = 'https://xivapi.com/characters?ids='.$ids.'&extended=1&private_key='.$_ENV['XIVAPI_KEY'];
            $res = json_decode(file_get_contents($url));

            foreach ($res as $data)
            {
                foreach ($queue as $character)
                {
                    if ($character->getLodestoneId() == $data->Character->ID)
                        break;
                }

                echo $character->getLodestoneId().'-'.$character->getName().'@'.$character->getServer()."\n";
                try {
                    $this->lcService->update($character, $data);
                }
                catch (Exception $err) {
                    echo $err->getMessage()."\n";
                }
                $character->setUpdatedAt(new DateTime());
                $this->em->persist($character);
                $this->em->flush();
            }
        }
        else {
            echo "Nothing to update.\n";
        }
    }
}
