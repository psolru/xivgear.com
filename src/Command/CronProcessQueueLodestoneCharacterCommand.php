<?php

namespace App\Command;

use App\Entity\LodestoneCharacter;
use App\Repository\LodestoneCharacterRepository;
use App\Services\Lodestone\CharacterService;
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
    /** @var CharacterService */
    private $lcService;
    /** @var EntityManagerInterface */
    private $em;

    /**
     * CronProcessQueueLodestoneCharacterCommand constructor.
     * @param LodestoneCharacterRepository $lcRepository
     * @param EntityManagerInterface $em
     * @param CharacterService $lcService
     */
    public function __construct(
        LodestoneCharacterRepository $lcRepository,
        EntityManagerInterface $em,
        CharacterService $lcService
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
        $charactersToUpdate = 10;
        $queue = $this->lcRepository->getUpdateQueue($charactersToUpdate);

        if ($queue) {

            $ids = [];
            /** @var $character LodestoneCharacter */
            foreach ($queue as $character) {
                $ids[] = $character->getLodestoneId();
            }

            $ids = implode(',', $ids);

            $call = 0;
            do {
                $call++;
                if ($call > 1) {
                    sleep(10);
                }
                $url = 'https://xivapi.com/characters?ids='.$ids.'&extended=1&private_key='.$_ENV['XIVAPI_KEY'];

                $curl = curl_init();
                curl_setopt_array($curl, [
                    CURLOPT_RETURNTRANSFER => 1,
                    CURLOPT_URL => $url,
                    // endpoint takes like 4 seconds for each character
                    CURLOPT_TIMEOUT => $charactersToUpdate*4,
                ]);

                $result = json_decode(curl_exec($curl));
                $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

                if ($httpCode == 404) {
                    if (preg_match("/Could not find: \/lodestone\/character\/([0-9]*)/", $result->Message, $m)) {
                        $ids = str_replace($m[1], '', $ids);
                        $ids = str_replace(',,', ',', $ids);
                        $ids = trim($ids, ',');
                        dump('deleting '.$m[1]);
                        $this->lcService->setUpdateFailure($m[1]);
                    }
                }
            } while ($httpCode == 404 && $ids != '');

            if ($ids == '') {
                dump('nothing left');
                exit;
            }
            if ($httpCode != 200) {
                dump('requestError');
                exit;
            }

            foreach ($result as $data)
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
