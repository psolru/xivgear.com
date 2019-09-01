<?php

namespace App\Command\Import\Lodestone;

use App\Entity\Lodestone\LodestoneClass;
use App\Repository\Lodestone\LodestoneClassRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Classes extends Command
{
    protected static $defaultName = 'import:lodestone:classes';
    /**
     * @var LodestoneClassRepository
     */
    private $lodestoneClassRepository;
    /** @var EntityManagerInterface */
    private $em;

    /**
     * Classes constructor.
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
        $progressBar = new ProgressBar($output, 39);
        $progressBar->setFormat("%message%\n%current%/%max% [%bar%] %percent:3s%% %memory:6s%\n");
        $progressBar->setMessage('Starting…');

        $progressBar->start();
        for ($i=0;$i<=38;$i++) {
            $item = json_decode(file_get_contents('https://xivapi.com/ClassJob/'.$i.'?private_key='.$_ENV['XIVAPI_KEY']));

            $lodestoneClass = $this->lodestoneClassRepository->findOneBy(['lodestone_id' => $item->ID]);
            if (!$lodestoneClass)
                $lodestoneClass = new LodestoneClass();

            $lodestoneClass
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
                $lodestoneClass->setParentLodestoneId($item->ClassJobParent->ID);

            $this->em->persist($lodestoneClass);
            $this->em->flush();

            $progressBar->setMessage($item->Name_en.' imported');
            $progressBar->advance();
        }
        $progressBar->finish();
    }
}
