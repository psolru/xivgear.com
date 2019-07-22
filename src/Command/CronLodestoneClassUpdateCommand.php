<?php

namespace App\Command;

use App\Entity\LodestoneClass;
use App\Repository\LodestoneClassRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CronLodestoneClassUpdateCommand extends Command
{
    protected static $defaultName = 'cron:lodestone_class:update';
    /**
     * @var LodestoneClassRepository
     */
    private $lodestoneClassRepository;
    /** @var EntityManagerInterface */
    private $em;

    /**
     * CronLodestoneClassUpdateCommand constructor.
     * @param LodestoneClassRepository $lodestoneClassRepository
     * @param EntityManagerInterface $em
     */
    public function __construct(
        LodestoneClassRepository $lodestoneClassRepository,
        EntityManagerInterface $em
    ) {
        $this->lodestoneClassRepository = $lodestoneClassRepository;

        parent::__construct();
        $this->em = $em;
    }

    protected function configure()
    {
        $this->setDescription('Updates Lodestone Classes');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        for ($i=0;$i<=38;$i++) {
            $item = json_decode(file_get_contents('https://xivapi.com/ClassJob/'.$i.'?private_key='.$_ENV['XIVAPI_KEY']));

            echo $item->Abbreviation_en."\n";

            $ffxivClass = $this->lodestoneClassRepository->findOneBy(['lodestone_id' => $item->ID]);
            if (!$ffxivClass)
                $ffxivClass = new LodestoneClass();

            $ffxivClass
                ->setLodestoneId($item->ID)
                ->setIconUrl($item->Icon)
                ->setNameDe($item->Name_de)
                ->setShortDe($item->Abbreviation_de)
                ->setNameEn($item->Name_en)
                ->setShortEn($item->Abbreviation_en)
                ->setNameFr($item->Name_fr)
                ->setShortFr($item->Abbreviation_fr)
                ->setNameJp($item->Name_ja)
                ->setShortJp($item->Abbreviation_ja)
            ;

            if (isset($item->ClassJobParent->ID))
                $ffxivClass->setParentLodestoneId($item->ClassJobParent->ID);

            $this->em->persist($ffxivClass);
            sleep(1);
        }
        $this->em->flush();
    }
}
