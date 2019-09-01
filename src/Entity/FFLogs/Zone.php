<?php

namespace App\Entity\FFLogs;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FFLogs\ZoneRepository")
 * @ORM\Table(name="fflogs_zone")
 */
class Zone
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $fflogs_id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name_de;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name_en;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name_fr;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name_jp;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\FFLogs\Encounter", mappedBy="fflogs_zone")
     */
    private $encounters;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isActive;

    public function __construct()
    {
        $this->encounters = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFflogsId(): ?int
    {
        return $this->fflogs_id;
    }

    public function setFflogsId(int $fflogs_id): self
    {
        $this->fflogs_id = $fflogs_id;

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

    public function getNameEn(): ?string
    {
        return $this->name_en;
    }

    public function setNameEn(?string $name_en): self
    {
        $this->name_en = $name_en;

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

    public function getNameJp(): ?string
    {
        return $this->name_jp;
    }

    public function setNameJp(?string $name_jp): self
    {
        $this->name_jp = $name_jp;

        return $this;
    }

    /**
     * @return Collection|Encounter[]
     */
    public function getEncounters(): Collection
    {
        return $this->encounters;
    }

    public function addEncounter(Encounter $encounter): self
    {
        if (!$this->encounters->contains($encounter)) {
            $this->encounters[] = $encounter;
            $encounter->setFflogsZone($this);
        }

        return $this;
    }

    public function removeEncounter(Encounter $encounter): self
    {
        if ($this->encounters->contains($encounter)) {
            $this->encounters->removeElement($encounter);
            // set the owning side to null (unless already changed)
            if ($encounter->getFflogsZone() === $this) {
                $encounter->setFflogsZone(null);
            }
        }

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->isActive;
    }

    public function setActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }
}
