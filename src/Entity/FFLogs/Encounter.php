<?php

namespace App\Entity\FFLogs;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FFLogs\EncounterRepository")
 * @ORM\Table(name="fflogs_encounter")
 */
class Encounter
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
     * @ORM\ManyToOne(targetEntity="App\Entity\FFLogs\Zone", inversedBy="encounters")
     * @ORM\JoinColumn(nullable=false)
     */
    private $fflogs_zone;

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
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $importActive;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\FFLogs\Ranking", mappedBy="encounter")
     */
    private $rankings;

    public function __construct()
    {
        $this->rankings = new ArrayCollection();
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

    public function getFflogsZone(): ?Zone
    {
        return $this->fflogs_zone;
    }

    public function setFflogsZone(?Zone $fflogs_zone): self
    {
        $this->fflogs_zone = $fflogs_zone;

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

    public function importIsActive(): ?bool
    {
        return $this->importActive;
    }

    public function activateImport(): self
    {
        $this->importActive = true;

        return $this;
    }

    public function deactivateImport(): self
    {
        $this->importActive = false;

        return $this;
    }

    /**
     * @return Collection|Ranking[]
     */
    public function getRankings(): Collection
    {
        return $this->rankings;
    }

    public function addRanking(Ranking $ranking): self
    {
        if (!$this->rankings->contains($ranking)) {
            $this->rankings[] = $ranking;
            $ranking->setEncounter($this);
        }

        return $this;
    }

    public function removeRanking(Ranking $ranking): self
    {
        if ($this->rankings->contains($ranking)) {
            $this->rankings->removeElement($ranking);
            // set the owning side to null (unless already changed)
            if ($ranking->getEncounter() === $this) {
                $ranking->setEncounter(null);
            }
        }

        return $this;
    }
}
