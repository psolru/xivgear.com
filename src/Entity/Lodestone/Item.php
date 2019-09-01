<?php


namespace App\Entity\Lodestone;


class Item
{
    private $id;
    private $name;
    private $description;
    private $icon_url;
    private $levelItem;
    private $levelEquip;

    public function __construct(array $initialValues=[])
    {
        if ($initialValues)
        {
            $this->setId($initialValues['id']);
            $this->setName($initialValues['name']);
            $this->setDescription($initialValues['description']);
            $this->setIconUrl($initialValues['icon_url']);
            $this->setLevelItem($initialValues['levelItem']);
            $this->setLevelEquip($initialValues['levelEquip']);
        }
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description): void
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getIconUrl()
    {
        return $this->icon_url;
    }

    /**
     * @param mixed $icon_url
     */
    public function setIconUrl($icon_url): void
    {
        $this->icon_url = $icon_url;
    }

    /**
     * @return mixed
     */
    public function getLevelItem()
    {
        return $this->levelItem;
    }

    /**
     * @param mixed $levelItem
     */
    public function setLevelItem($levelItem): void
    {
        $this->levelItem = $levelItem;
    }

    /**
     * @return mixed
     */
    public function getLevelEquip()
    {
        return $this->levelEquip;
    }

    /**
     * @param mixed $levelEquip
     */
    public function setLevelEquip($levelEquip): void
    {
        $this->levelEquip = $levelEquip;
    }
}