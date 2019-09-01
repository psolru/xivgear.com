<?php

namespace App\Services\Importer\Lodestone;

use App\Services\Lodestone\ItemService;
use App\Services\Redis\Redis;
use League\Csv\Reader;
use League\Csv\Statement;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\OutputInterface;

class Item extends CSVImportHelper
{
    /**
     * @var array
     */
    private static $data;
    protected $resource_url = 'https://raw.githubusercontent.com/xivapi/ffxiv-datamining/master/csv/Item.csv';
    protected $importFields = [
        '#',
        'Name',
        'Description',
        'Icon',
        'Level{Item}',
        'Level{Equip}'
    ];
    private $consoleMode;

    public function __construct(string $projectDir)
    {
        parent::__construct($projectDir);
        $this->csvHeaderStatement = (new Statement())
             ->offset(1)
             ->limit(1);

        $this->csvContentStatement = (new Statement())
            ->offset(3);
    }

    /**
     * Could be faster, but hey, it got a Progressbar...i like it - more progress for the world! :-)
     * @param OutputInterface $output
     * @throws \Exception
     */
    public function importAll(OutputInterface $output)
    {
        $this->downloadData();
        $this->loadDataIntoArrayByFile();

        $progressBar = new ProgressBar($output, count(self::$data));
        $progressBar->start();

        foreach(self::$data as $item)
        {
            $this->import($item['Name']);
            $progressBar->advance();
        }
        $progressBar->finish();
    }

    public function importByName(string $name)
    {
        $this->import($name);
    }

    private function import(string $name=null)
    {
        if ($name)
            $name = ItemService::clearName($name);

        // download data if expired / not already loaded
        $this->downloadData(false);

        $this->loadDataIntoArrayByFile();

        foreach (self::$data as $key => $content)
        {
            $clearName = ItemService::clearName($content['Name']);

            if ($name === null || $clearName === $name) {
                if ($content['Name'] !== '')
                    $this->storeAsRedisHash($content);

                // unset data to make future loops shorter - sneaky booy
                unset(self::$data[$key]);

                break;
            }
        }
    }

    private function loadDataIntoArrayByFile()
    {
        // check if data is already loaded
        if (self::$data) return;

        $csv = Reader::createFromPath($this->getDataFileName(), 'r');

        $header = $this->getHeader($csv);
        $header = $this->filterHeader($header);

        $content = $this->getContent($csv);
        $content = $this->filterContentByHeader($content, $header);

        self::$data = $content;
    }

    private function storeAsRedisHash(array $item)
    {
        $data = [
            'id' => $item['#'],
            'name' => $item['Name'],
            'description' => $item['Description'],
            'icon_url' => '/i/0'.substr($item['Icon'],0,2).'000/0'.$item['Icon'].'.png',
            'levelItem' => $item['Level{Item}'],
            'levelEquip' => $item['Level{Equip}']
        ];
        Redis::Cache()->hMSet('Item_'.$item['#']   , $data);
        Redis::Cache()->hMSet('Item_'.ItemService::clearName($item['Name']), $data);
    }
}








