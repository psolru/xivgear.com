<?php

namespace App\Entity\Lodestone;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Lodestone\CharacterLodestoneClassRepository")
 * @ORM\Table(name="lodestone_character_lodestone_class")
 */
class CharacterLodestoneClass
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
    private $level;

    /**
     * @ORM\Column(type="integer")
     */
    private $experience;

    /**
     * @ORM\Column(type="integer")
     */
    private $experience_total;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Lodestone\Character", inversedBy="lodestoneClassMappings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $lodestone_character;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Lodestone\LodestoneClass", inversedBy="lodestoneCharacterMappings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $lodestone_class;

    /**
     * @ORM\Column(type="string", length=3)
     */
    private $short;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(int $level): self
    {
        $this->level = $level;

        return $this;
    }

    public function getExperience(): ?int
    {
        return $this->experience;
    }

    public function setExperience(int $experience): self
    {
        $this->experience = $experience;

        return $this;
    }

    public function getExperienceTotal(): ?int
    {
        return $this->experience_total;
    }

    public function setExperienceTotal(int $experience_total): self
    {
        $this->experience_total = $experience_total;

        return $this;
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
        $this->setShort(strtolower($lodestone_class->getShortEn()));
        $this->lodestone_class = $lodestone_class;

        return $this;
    }

    public function getShort(): ?string
    {
        return $this->short;
    }

    public function setShort(string $short): self
    {
        $this->short = $short;

        return $this;
    }
}
