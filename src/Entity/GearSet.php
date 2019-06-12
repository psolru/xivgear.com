<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GearSetRepository")
 */
class GearSet
{
    use TimestampableEntity;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\LodestoneCharacter", inversedBy="gearSets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $lodestone_character;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\LodestoneClass", inversedBy="gearSets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $lodestone_class;

    /**
     * @ORM\Column(type="integer")
     */
    private $iLevel;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $strength;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $dexterity;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $vitality;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $intelligence;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $mind;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $criticalHit;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $determination;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $directHitRate;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $defense;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $magicDefense;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $attackPower;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $skillSpeed;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $attackMagicPotency;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $healingMagicPotency;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $spellSpeed;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $tenacity;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $piety;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $gathering;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $perception;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $craftsmanship;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $control;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cP;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $gP;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $hP;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $mP;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $tP;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\GearsetItem", mappedBy="gearset")
     */
    private $gearsetItems;

    public function __construct()
    {
        $this->gearsetItems = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLodestoneCharacter(): ?LodestoneCharacter
    {
        return $this->lodestone_character;
    }

    public function setLodestoneCharacter(?LodestoneCharacter $lodestone_character): self
    {
        $this->lodestone_character = $lodestone_character;

        return $this;
    }

    public function getLodestoneClass(): ?LodestoneClass
    {
        return $this->lodestone_class;
    }

    public function setLodestoneClass(?LodestoneClass $lodestone_class): self
    {
        $this->lodestone_class = $lodestone_class;

        return $this;
    }

    public function getILevel(): ?int
    {
        return $this->iLevel;
    }

    public function setILevel(int $iLevel): self
    {
        $this->iLevel = $iLevel;

        return $this;
    }

    public function getStrength(): ?int
    {
        return $this->strength;
    }

    public function setStrength(?int $strength): self
    {
        $this->strength = $strength;

        return $this;
    }

    /**
     * @param string $name
     * @param int|null $val
     * @return GearSet
     */
    public function setAttribute(string $name, ?int $val): self
    {
        $this->{$name} = $val;
        return $this;
    }

    public function getAttribute(string $name): ?int
    {
        return $this->{$name};
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
            $gearsetItem->setGearset($this);
        }

        return $this;
    }

    public function removeGearsetItem(GearsetItem $gearsetItem): self
    {
        if ($this->gearsetItems->contains($gearsetItem)) {
            $this->gearsetItems->removeElement($gearsetItem);
            // set the owning side to null (unless already changed)
            if ($gearsetItem->getGearset() === $this) {
                $gearsetItem->setGearset(null);
            }
        }

        return $this;
    }

    /**
     * @param string $slot
     * @return GearsetItem|null
     */
    public function getGearsetItemBySlot(string $slot): ?GearsetItem
    {
        foreach ($this->getGearsetItems() as $gearsetItem) {
            if ($slot == $gearsetItem->getSlot())
                return $gearsetItem;
        }
        return null;
    }
}
