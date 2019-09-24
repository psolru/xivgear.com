<?php

namespace App\Entity\Lodestone;

use App\Services\Lodestone\ItemService;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Lodestone\GearsetItemRepository")
 * @ORM\Table(name="gearset_item")
 */
class GearsetItem
{
    use TimestampableEntity;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Lodestone\GearSet", inversedBy="gearsetItems")
     * @ORM\JoinColumn(nullable=false)
     */
    private $gearset;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slot;

    /**
     * @ORM\Column(type="integer")
     */
    private $itemId;

    /**
     * @var Item $item
     */
    private $item;

    /**
     * stored like "|123|541|7456|"
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $materiaIds;

    /**
     * @var Item[] $materiaCollection
     */
    private $materiaCollection=[];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGearset(): ?GearSet
    {
        return $this->gearset;
    }

    public function setGearset(?GearSet $gearset): self
    {
        $this->gearset = $gearset;

        return $this;
    }

    public function getSlot(): ?string
    {
        return $this->slot;
    }

    public function setSlot(string $slot): self
    {
        $this->slot = $slot;

        return $this;
    }

    public function getItemId(): ?int
    {
        return $this->itemId;
    }

    public function setItemId(int $itemId): self
    {
        $this->itemId = $itemId;

        return $this;
    }

    /**
     * @param bool $asArray
     * @return Item|array
     */
    public function getItem(bool $asArray=false)
    {
        if ($this->item)
            return $this->item;

        if (!$this->getItemId())
            return new Item();

        return ItemService::get($this->getItemId(), $asArray);
    }

    /**
     * @param bool $asArray
     * @return Item[]
     */
    public function getMateria(bool $asArray=false): array
    {
        if (!$this->materiaIds || $this->materiaCollection)
            return $this->materiaCollection;

        $this->materiaCollection = [];
        foreach (explode('|', trim($this->materiaIds, '|')) as $id)
        {
            $this->materiaCollection[] = ItemService::get($id, $asArray);
        }
        return $this->materiaCollection;
    }

    public function clearMateria(): GearsetItem
    {
        $this->materiaIds = null;
        return $this;
    }

    public function addMateria(Item $materia): GearsetItem
    {
        $toAdd = $materia->getId();

        $list = [];
        foreach (explode('|', trim($this->materiaIds, '|')) as $el)
        {
            if ($el) {
                $list[] = $el;
            }
        }
        $list[] = $toAdd;

        $this->materiaIds = '|'.trim(implode('|', $list), '|').'|';

        return $this;
    }

    public function toArray()
    {
        $array = [
            'id' => $this->id,
            'slot' => $this->slot,
            'itemId' => $this->itemId,
            'Item' => $this->getItem(true)
        ];
        foreach ($this->getMateria(true) as $materia)
        {
            $array['materia'][] = $materia;
        }
        return $array;
    }
}
