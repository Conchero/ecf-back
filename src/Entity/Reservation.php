<?php

namespace App\Entity;

use Symfony\Component\String\Slugger\AsciiSlugger;
use App\Repository\ReservationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\ManyToOne(targetEntity: Room::class, inversedBy: 'reservations')]
#[ORM\JoinColumn( onDelete: 'SET NULL')] // ou 'CASCADE' si tu veux tout supprimer
private ?Room $rentedRoom = null;


    #[ORM\ManyToOne(inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $client = null;

    #[ORM\Column]
    private ?\DateTime $reservationStart = null;

    #[ORM\Column]
    private ?\DateTime $reservationEnd = null;

    #[ORM\Column(length: 20)]
    private ?string $status = 'pending'; // Valeurs possibles : 'pending', 'accepted', 'rejected'

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;
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
        $prefix = substr(strtolower($this->getClient()?->getFirstName() ?? 'res'), 0, 3);
        $this->slug = $prefix . '-reservation-' . $_id ;
        return $this->slug;
    }

    public function getRentedRoom(): ?Room
    {
        return $this->rentedRoom;
    }

    public function setRentedRoom(?Room $rentedRoom): static
    {
        $this->rentedRoom = $rentedRoom;
        return $this;
    }

    public function getClient(): ?User
    {
        return $this->client;
    }

    public function setClient(?User $client): static
    {
        $this->client = $client;
        return $this;
    }

    public function getReservationStart(): ?\DateTime
    {
        return $this->reservationStart;
    }

    public function setReservationStart(\DateTime $reservationStart): static
    {
        $this->reservationStart = $reservationStart;
        return $this;
    }

    public function getReservationEnd(): ?\DateTime
    {
        return $this->reservationEnd;
    }

    public function setReservationEnd(\DateTime $reservationEnd): static
    {
        $this->reservationEnd = $reservationEnd;
        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;
        return $this;
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isAccepted(): bool
    {
        return $this->status === 'accepted';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }
    


}
