<?php


namespace App\Services\Lodestone;

use App\Entity\Lodestone\Item;
use App\Services\AbstractService;
use App\Services\Importer\Lodestone\Item as Importer;
use App\Services\Redis\Redis;
use Doctrine\ORM\EntityManagerInterface;

class ItemService extends AbstractService
{
    /** @var Importer */
    private $importer;

    public function __construct(EntityManagerInterface $em, Importer $importer)
    {
        parent::__construct($em);
        $this->importer = $importer;
    }

    /**
     * Trim everything than A-z0-9 and convert to snake_case
     * @param string $name
     * @return string
     */
    public static function clearName(string $name): string
    {
        $name = preg_replace("/([^\w]|\s)+/", "", $name);
        $name = preg_replace_callback("/([A-Z])/", function($letter) {
            return '_'.strtolower($letter[1]);
        }, $name);
        return trim($name, '_');
    }

    /**
     * @param string $key
     * @param bool $asArray
     * @return Item|array
     */
    public static function get(string $key, bool $asArray=false)
    {
        $cachedItem = Redis::Cache()->hGetAll(
            'Item_'.self::clearName($key)
        );

        $item = new Item($cachedItem);

        if ($asArray)
        {
            return [
                'id' => $item->getId(),
                'name' => $item->getName(),
                'iconUrl' => $item->getIconUrl(),
                'levelItem' => $item->getLevelItem(),
                'levelEquip' => $item->getLevelEquip()
            ];
        }
        return $item;
    }
}