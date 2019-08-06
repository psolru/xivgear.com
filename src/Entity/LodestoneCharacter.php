<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LodestoneCharacterRepository")
 */
class LodestoneCharacter
{
    use TimestampableEntity;
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $server;

    /**
     * @var bool
     */
    private $justCreated = false;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $avatarUrl;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $portraitUrl;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\LodestoneCharacterLodestoneClass", mappedBy="lodestone_character")
     */
    private $lodestoneClassMappings;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\GearSet", mappedBy="lodestone_character")
     */
    private $gearSets;

    public function __construct()
    {
        $this->lodestoneClassMappings = new ArrayCollection();
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getServer(): ?string
    {
        return $this->server;
    }

    public function setServer(?string $server): self
    {
        $this->server = $server;

        return $this;
    }

    public function isJustCreated(): bool
    {
        return $this->justCreated;
    }

    public function setJustCreated(bool $justCreated): void
    {
        $this->justCreated = $justCreated;
    }

    public function getAvatarUrl(): ?string
    {
        return $this->avatarUrl;
    }

    public function setAvatarUrl(?string $avatarUrl): self
    {
        $this->avatarUrl = $avatarUrl;

        return $this;
    }

    public function getPortraitUrl(): ?string
    {
        return $this->portraitUrl;
    }

    public function setPortraitUrl(?string $portraitUrl): self
    {
        $this->portraitUrl = $portraitUrl;

        return $this;
    }

    /**
     * @return Collection|LodestoneCharacterLodestoneClass[]
     */
    public function getLodestoneClassMappings(): Collection
    {
        return $this->lodestoneClassMappings;
    }

    /**
     * @param string $short
     * @return LodestoneCharacterLodestoneClass|null
     */
    public function getLodestoneClassMapping(string $short): ?LodestoneCharacterLodestoneClass
    {
        foreach ($this->getLodestoneClassMappings() as $map) {
            if ($map->getShort() == $short) {
                return $map;
            }
        }
        return null;
    }

    /**
     * @param LodestoneCharacterLodestoneClass $lodestoneClassMapping
     * @return LodestoneCharacter
     */
    public function addLodestoneClassMapping(LodestoneCharacterLodestoneClass $lodestoneClassMapping): self
    {
        if (!$this->lodestoneClassMappings->contains($lodestoneClassMapping)) {
            $this->lodestoneClassMappings[] = $lodestoneClassMapping;
            $lodestoneClassMapping->setLodestoneCharacter($this);
        }

        return $this;
    }

    /**
     * @param LodestoneCharacterLodestoneClass $lodestoneClassMapping
     * @return LodestoneCharacter
     */
    public function removeLodestoneClassMapping(LodestoneCharacterLodestoneClass $lodestoneClassMapping): self
    {
        if ($this->lodestoneClassMappings->contains($lodestoneClassMapping)) {
            $this->lodestoneClassMappings->removeElement($lodestoneClassMapping);
            // set the owning side to null (unless already changed)
            if ($lodestoneClassMapping->getLodestoneCharacter() === $this) {
                $lodestoneClassMapping->setLodestoneCharacter(null);
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
            $gearSet->setLodestoneCharacter($this);
        }

        return $this;
    }

    public function removeGearSet(GearSet $gearSet): self
    {
        if ($this->gearSets->contains($gearSet)) {
            $this->gearSets->removeElement($gearSet);
            // set the owning side to null (unless already changed)
            if ($gearSet->getLodestoneCharacter() === $this) {
                $gearSet->setLodestoneCharacter(null);
            }
        }

        return $this;
    }
}
