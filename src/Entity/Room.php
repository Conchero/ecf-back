<?php

namespace App\Entity;

use Symfony\Component\String\Slugger\AsciiSlugger;
use App\Repository\RoomRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RoomRepository::class)]
class Room
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    #[ORM\Column(length: 255)]
    private ?string $localisation = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $keywords = null;

    #[ORM\Column]
    private ?int $capacity = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column]
    private ?bool $is_available = null;

    #[ORM\ManyToOne(inversedBy: 'rooms')]
    private ?User $owner = null;

    /**
     * @var Collection<int, Equipment>
     */
    #[ORM\ManyToMany(targetEntity: Equipment::class, inversedBy: 'rooms')]
    private Collection $equipments;

    /**
     * @var Collection<int, Software>
     */
    #[ORM\ManyToMany(targetEntity: Software::class, inversedBy: 'advantages')]
    private Collection $softwares;

    /**
     * @var Collection<int, Advantage>
     */
    #[ORM\ManyToMany(targetEntity: Advantage::class, inversedBy: 'rooms')]
    private Collection $advantages;

    /**
     * @var Collection<int, Reservation>
     */
   #[ORM\OneToMany(mappedBy: 'rentedRoom', targetEntity: Reservation::class, orphanRemoval: true, cascade: ['remove'])]
private Collection $reservations;

    public function __construct()
    {
        $this->equipments = new ArrayCollection();
        $this->softwares = new ArrayCollection();
        $this->advantages = new ArrayCollection();
        $this->reservations = new ArrayCollection();
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
           $this->slug =  strtolower($slugger->slug($this->title . '-' . $_id));
            return $this->slug; 
    }


    public function getImage(): ?string
    {
        return $this->image;
    }

     public function getImagePath(): ?string
    {
        $path = '/uploads/images/';
        if ($this->image !== 'default.png') {
            return $path . $this->image;
        }
        return $path = '/images/' . 'default.png';
    }


    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getLocalisation(): ?string
    {
        return $this->localisation;
    }

    public function setLocalisation(string $localisation): static
    {
        $this->localisation = $localisation;

        return $this;
    }

    public function getKeywords(): ?string
    {
        return $this->keywords;
    }

    public function setKeywords(?string $keywords): static
    {
        $this->keywords = $keywords;

        return $this;
    }

    public function getCapacity(): ?int
    {
        return $this->capacity;
    }

    public function setCapacity(int $capacity): static
    {
        $this->capacity = $capacity;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function isAvailable(): ?bool
    {
        return $this->is_available;
    }

    public function setIsAvailable(bool $is_available): static
    {
        $this->is_available = $is_available;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): static
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return Collection<int, Equipment>
     */
    public function getEquipments(): Collection
    {
        return $this->equipments;
    }

    public function addEquipment(Equipment $equipment): static
    {
        if (!$this->equipments->contains($equipment)) {
            $this->equipments->add($equipment);
        }

        return $this;
    }

    public function removeEquipment(Equipment $equipment): static
    {
        $this->equipments->removeElement($equipment);

        return $this;
    }

    /**
     * @return Collection<int, Software>
     */
    public function getSoftwares(): Collection
    {
        return $this->softwares;
    }

    public function addSoftware(Software $software): static
    {
        if (!$this->softwares->contains($software)) {
            $this->softwares->add($software);
        }

        return $this;
    }

    public function removeSoftware(Software $software): static
    {
        $this->softwares->removeElement($software);

        return $this;
    }

    /**
     * @return Collection<int, Advantage>
     */
    public function getAdvantages(): Collection
    {
        return $this->advantages;
    }

    public function addAdvantage(Advantage $advantage): static
    {
        if (!$this->advantages->contains($advantage)) {
            $this->advantages->add($advantage);
        }

        return $this;
    }

    public function removeAdvantage(Advantage $advantage): static
    {
        $this->advantages->removeElement($advantage);

        return $this;
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): static
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations->add($reservation);
            $reservation->setRentedRoom($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): static
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getRentedRoom() === $this) {
                $reservation->setRentedRoom(null);
            }
        }

        return $this;
    }
    public function __toString(): string
{
    return $this->getTitle(); 
}


}
