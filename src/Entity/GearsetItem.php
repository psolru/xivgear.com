<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GearsetItemRepository")
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
     * @ORM\ManyToOne(targetEntity="App\Entity\GearSet", inversedBy="gearsetItems")
     * @ORM\JoinColumn(nullable=false)
     */
    private $gearset;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slot;

    /**
     * @ORM\ManyToOne(targetEntity="Item", inversedBy="gearsetItems")
     * @ORM\JoinColumn(nullable=false)
     */
    private $item;

    /**
     * @ORM\ManyToMany(targetEntity="Item", inversedBy="gearsetItemsMateria")
     */
    private $materia;

    public function __construct()
    {
        $this->materia = new ArrayCollection();
    }

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

    public function getItem(): ?Item
    {
        return $this->item;
    }

    public function setItem(?Item $item): self
    {
        $this->item = $item;

        return $this;
    }

    /**
     * @return Collection|Item[]
     */
    public function getMateria(): Collection
    {
        return $this->materia;
    }

    public function addMateria(Item $materia): self
    {
        if (!$this->materia->contains($materia)) {
            $this->materia[] = $materia;
        }

        return $this;
    }

    public function removeMateria(Item $materia): self
    {
        if ($this->materia->contains($materia)) {
            $this->materia->removeElement($materia);
        }

        return $this;
    }
}
