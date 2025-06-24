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

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Room $rentedRoom = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $client = null;

    #[ORM\Column]
    private ?\DateTime $reservationStart = null;

    #[ORM\Column]
    private ?\DateTime $reservationEnd = null;

    #[ORM\Column(nullable: true)]
    private ?bool $is_pending = null;

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
        return $this->toSlug();
    }

    // public function setSlug(string $slug): static
    // {
    //     $this->slug = $slug;
    //     return $this;
    // }


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

    public function isPending(): ?bool
    {
        return $this->is_pending;
    }

    public function setIsPending(?bool $is_pending): static
    {
        $this->is_pending = $is_pending;

        return $this;
    }
    public function toSlug(): string
    {                   //pour générer  les 3 premières lettres du prénom du client
        $prefix = substr(strtolower($this->getClient()?->getFirstName() ?? 'res'), 0, 3);
        return $prefix . '-reservation-' . $this->getId();
    }
}
