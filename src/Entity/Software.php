<?php

namespace App\Entity;

use App\Repository\SoftwareRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\String\Slugger\AsciiSlugger;

#[ORM\Entity(repositoryClass: SoftwareRepository::class)]
class Software
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    /**
     * @var Collection<int, Room>
     */
    #[ORM\ManyToMany(targetEntity: Room::class, mappedBy: 'softwares')]
    private Collection $rooms;

    public function __construct()
    {
        $this->rooms = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug($slug): ?string
    {
        $this->slug = $slug;
        return $this->slug;
    }

    public function makeSlug($_id): string
    {
        $slugger = new AsciiSlugger();
        $this->slug = strtolower($slugger->slug($this->title . '-' . $_id));
        return $this->slug;
    }


    /**
     * @return Collection<int, Room>
     */
    public function getRooms(): Collection
    {
        return $this->rooms;
    }

    public function addRooms(Room $_room): static
    {
        if (!$this->rooms->contains($_room)) {
            $this->rooms->add($_room);
            $_room->addSoftware($this);
        }

        return $this;
    }

    public function removeRooms(Room $_room): static
    {
        if ($this->rooms->removeElement($_room)) {
            $_room->removeSoftware($this);
        }

        return $this;
    }
     public function toSlug(): string
    {
        $slugger = new AsciiSlugger();
        return strtolower($slugger->slug($this->title . '-' . $this->id));
    }
    public function __toString(): string
{
    return $this->title; 
}

}
