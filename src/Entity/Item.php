<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ItemRepository")
 */
class Item
{
    use TimestampableEntity;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $lodestone_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $iconUrl;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name_en;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name_de;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name_jp;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name_fr;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $category_id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $levelEquip;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $levelItem;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\GearsetItem", mappedBy="item")
     */
    private $gearsetItems;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\GearsetItem", mappedBy="materia")
     */
    private $gearsetItemsMateria;

    public function __construct()
    {
        $this->gearsetItems = new ArrayCollection();
        $this->gearsetItemsMateria = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLodestoneId(): ?int
    {
        return $this->lodestone_id;
    }

    public function setLodestoneId(int $lodestone_id): self
    {
        $this->lodestone_id = $lodestone_id;

        return $this;
    }

    public function getIconUrl(): ?string
    {
        return $this->iconUrl;
    }

    public function setIconUrl(string $iconUrl): self
    {
        $this->iconUrl = $iconUrl;

        return $this;
    }

    public function getNameEn(): ?string
    {
        return $this->name_en;
    }

    public function setNameEn(?string $name_en): self
    {
        $this->name_en = $name_en;

        return $this;
    }

    public function getNameDe(): ?string
    {
        return $this->name_de;
    }

    public function setNameDe(?string $name_de): self
    {
        $this->name_de = $name_de;

        return $this;
    }

    public function getNameJp(): ?string
    {
        return $this->name_jp;
    }

    public function setNameJp(?string $name_jp): self
    {
        $this->name_jp = $name_jp;

        return $this;
    }

    public function getNameFr(): ?string
    {
        return $this->name_fr;
    }

    public function setNameFr(?string $name_fr): self
    {
        $this->name_fr = $name_fr;

        return $this;
    }

    public function getCategoryId(): ?int
    {
        return $this->category_id;
    }

    public function setCategoryId(int $category_id): self
    {
        $this->category_id = $category_id;

        return $this;
    }

    public function getLevelEquip(): ?int
    {
        return $this->levelEquip;
    }

    public function setLevelEquip(int $levelEquip): self
    {
        $this->levelEquip = $levelEquip;

        return $this;
    }

    public function getLevelItem(): ?int
    {
        return $this->levelItem;
    }

    public function setLevelItem(int $levelItem): self
    {
        $this->levelItem = $levelItem;

        return $this;
    }

    /**
     * @return Collection|GearsetItem[]
     */
    public function getGearsetItems(): Collection
    {
        return $this->gearsetItems;
    }

    public function addGearsetItem(GearsetItem $gearsetItem): self
    {
        if (!$this->gearsetItems->contains($gearsetItem)) {
            $this->gearsetItems[] = $gearsetItem;
            $gearsetItem->setItem($this);
        }

        return $this;
    }

    public function removeGearsetItem(GearsetItem $gearsetItem): self
    {
        if ($this->gearsetItems->contains($gearsetItem)) {
            $this->gearsetItems->removeElement($gearsetItem);
            // set the owning side to null (unless already changed)
            if ($gearsetItem->getItem() === $this) {
                $gearsetItem->setItem(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|GearsetItesrc/Migrations/Version20190606213716.phpm[]
     */
    public function getGearsetItemsMateria(): Collection
    {
        return $this->gearsetItemsMateria;
    }

    public function addGearsetItemsMateria(GearsetItem $gearsetItemsMateria): self
    {
        if (!$this->gearsetItemsMateria->contains($gearsetItemsMateria)) {
            $this->gearsetItemsMateria[] = $gearsetItemsMateria;
            $gearsetItemsMateria->addMateria($this);
        }

        return $this;
    }

    public function removeGearsetItemsMateria(GearsetItem $gearsetItemsMateria): self
    {
        if ($this->gearsetItemsMateria->contains($gearsetItemsMateria)) {
            $this->gearsetItemsMateria->removeElement($gearsetItemsMateria);
            $gearsetItemsMateria->removeMateria($this);
        }

        return $this;
    }
}
