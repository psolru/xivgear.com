<?php

namespace App\Entity\FFLogs;

use App\Entity\Lodestone\Character;
use App\Entity\Lodestone\LodestoneClass;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FFLogs\RankingRepository")
 * @ORM\Table(name="fflogs_ranking")
 */
class Ranking
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Lodestone\Character", inversedBy="rankings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $lodestone_character;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Lodestone\LodestoneClass", inversedBy="rankings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $lodestone_class;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\FFLogs\Encounter", inversedBy="rankings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $encounter;

    /**
     * @ORM\Column(type="integer")
     */
    private $rank;

    /**
     * @ORM\Column(type="float")
     */
    private $total;

    /**
     * @ORM\Column(type="integer")
     */
    private $duration;

    /**
     * @ORM\Column(type="datetime")
     */
    private $datetime;

    /**
     * @ORM\Column(type="integer")
     */
    private $fight_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $report_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $server;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $region;

    /**
     * @ORM\Column(type="float")
     */
    private $patch;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $metric;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLodestoneCharacter(): ?Character
    {
        return $this->lodestone_character;
    }

    public function setLodestoneCharacter(?Character $lodestone_character): self
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

    public function getEncounter(): ?Encounter
    {
        return $this->encounter;
    }

    public function setEncounter(?Encounter $encounter): self
    {
        $this->encounter = $encounter;

        return $this;
    }

    public function getRank(): ?int
    {
        return $this->rank;
    }

    public function setRank(int $rank): self
    {
        $this->rank = $rank;

        return $this;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(float $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getDatetime(): ?\DateTimeInterface
    {
        return $this->datetime;
    }

    public function setDatetime(\DateTimeInterface $datetime): self
    {
        $this->datetime = $datetime;

        return $this;
    }

    public function getFightId(): ?int
    {
        return $this->fight_id;
    }

    public function setFightId(int $fight_id): self
    {
        $this->fight_id = $fight_id;

        return $this;
    }

    public function getReportId(): ?string
    {
        return $this->report_id;
    }

    public function setReportId(string $report_id): self
    {
        $this->report_id = $report_id;

        return $this;
    }

    public function getServer(): ?string
    {
        return $this->server;
    }

    public function setServer(string $server): self
    {
        $this->server = $server;

        return $this;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function setRegion(string $region): self
    {
        $this->region = $region;

        return $this;
    }

    public function getPatch(): ?float
    {
        return $this->patch;
    }

    public function setPatch(float $patch): self
    {
        $this->patch = $patch;

        return $this;
    }

    public function getMetric(): ?string
    {
        return $this->metric;
    }

    public function setMetric(string $metric): self
    {
        $this->metric = $metric;

        return $this;
    }
}
