<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LodestoneClassRepository")
 */
class LodestoneClass
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", unique=true)
     */
    private $lodestone_id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $parent_lodestone_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name_de;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $short_de;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name_en;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $short_en;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name_jp;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $short_jp;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name_fr;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $short_fr;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $tank;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $healer;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $dps;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $crafter;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $gatherer;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\LodestoneCharacterLodestoneClass", mappedBy="lodestone_class")
     */
    private $lodestoneCharacterMappings;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\GearSet", mappedBy="lodestone_class")
     */
    private $gearSets;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $iconUrl;

    public function __construct()
    {
        $this->lodestoneCharacterMappings = new ArrayCollection();
        $this->gearSets = new ArrayCollection();
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

    public function getParentLodestoneId(): ?int
    {
        return $this->parent_lodestone_id;
    }

    public function setParentLodestoneId(int $parent_lodestone_id): self
    {
        $this->parent_lodestone_id = $parent_lodestone_id;

        return $this;
    }

    public function getNameDe(): ?string
    {
        return $this->name_de;
    }

    public function setNameDe(string $name_de): self
    {
        $this->name_de = $name_de;

        return $this;
    }

    public function getShortDe(): ?string
    {
        return $this->short_de;
    }

    public function setShortDe(string $short_de): self
    {
        $this->short_de = $short_de;

        return $this;
    }

    public function getNameEn(): ?string
    {
        return $this->name_en;
    }

    public function setNameEn(string $name_en): self
    {
        $this->name_en = $name_en;

        return $this;
    }

    public function getShortEn(): ?string
    {
        return $this->short_en;
    }

    public function setShortEn(string $short_en): self
    {
        $this->short_en = $short_en;

        return $this;
    }

    public function getNameJp(): ?string
    {
        return $this->name_jp;
    }

    public function setNameJp(string $name_jp): self
    {
        $this->name_jp = $name_jp;

        return $this;
    }

    public function getShortJp(): ?string
    {
        return $this->short_jp;
    }

    public function setShortJp(string $short_jp): self
    {
        $this->short_jp = $short_jp;

        return $this;
    }

    public function getNameFr(): ?string
    {
        return $this->name_fr;
    }

    public function setNameFr(string $name_fr): self
    {
        $this->name_fr = $name_fr;

        return $this;
    }

    public function getShortFr(): ?string
    {
        return $this->short_fr;
    }

    public function setShortFr(string $short_fr): self
    {
        $this->short_fr = $short_fr;

        return $this;
    }

    public function isTank()
    {
        return $this->tank;
    }

    public function setTank($tank): void
    {
        $this->tank = $tank;
    }

    public function isHealer()
    {
        return $this->healer;
    }

    public function setHealer($healer): void
    {
        $this->healer = $healer;
    }

    public function isDps()
    {
        return $this->dps;
    }

    public function setDps($dps): void
    {
        $this->dps = $dps;
    }

    public function isCrafter()
    {
        return $this->crafter;
    }

    public function setCrafter($crafter): void
    {
        $this->crafter = $crafter;
    }

    public function isGatherer()
    {
        return $this->gatherer;
    }

    public function setGatherer($gatherer): void
    {
        $this->gatherer = $gatherer;
    }

    /**
     * @return Collection|LodestoneCharacterLodestoneClass[]
     */
    public function getLodestoneCharacterMappings(): Collection
    {
        return $this->lodestoneCharacterMappings;
    }

    public function addLodestoneCharacterMapping(LodestoneCharacterLodestoneClass $lodestoneCharacterMapping): self
    {
        if (!$this->lodestoneCharacterMappings->contains($lodestoneCharacterMapping)) {
            $this->lodestoneCharacterMappings[] = $lodestoneCharacterMapping;
            $lodestoneCharacterMapping->setLodestoneClass($this);
        }

        return $this;
    }

    public function removeLodestoneCharacterMapping(LodestoneCharacterLodestoneClass $lodestoneCharacterMapping): self
    {
        if ($this->lodestoneCharacterMappings->contains($lodestoneCharacterMapping)) {
            $this->lodestoneCharacterMappings->removeElement($lodestoneCharacterMapping);
            // set the owning side to null (unless already changed)
            if ($lodestoneCharacterMapping->getLodestoneClass() === $this) {
                $lodestoneCharacterMapping->setLodestoneClass(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|GearSet[]
     */
    public function getGearSets(): Collection
    {
        return $this->gearSets;
    }

    public function addGearSet(GearSet $gearSet): self
    {
        if (!$this->gearSets->contains($gearSet)) {
            $this->gearSets[] = $gearSet;
            $gearSet->setLodestoneClass($this);
        }

        return $this;
    }

    public function removeGearSet(GearSet $gearSet): self
    {
        if ($this->gearSets->contains($gearSet)) {
            $this->gearSets->removeElement($gearSet);
            // set the owning side to null (unless already changed)
            if ($gearSet->getLodestoneClass() === $this) {
                $gearSet->setLodestoneClass(null);
            }
        }

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

    public function getType(): string
    {
        if ($this->isTank())
            return 'tank';
        if ($this->isHealer())
            return 'healer';
        if ($this->isDps())
            return 'dps';
        if ($this->isCrafter())
            return 'crafter';
        if ($this->isGatherer())
            return 'gatherer';

        return 'none';
    }
}
