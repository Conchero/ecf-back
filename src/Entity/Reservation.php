<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTime $reservationStart = null;

    #[ORM\Column]
    private ?\DateTime $reservationEnd = null;

    #[ORM\Column]
    private ?bool $pending = null;

    #[ORM\Column(length: 255)]
    private ?string $relation = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Room $rentedRoom = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $client = null;

    public function getId(): ?int
    {
        return $this->id;
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
        return $this->pending;
    }

    public function setPending(bool $pending): static
    {
        $this->pending = $pending;

        return $this;
    }

    public function getRelation(): ?string
    {
        return $this->relation;
    }

    public function setRelation(string $relation): static
    {
        $this->relation = $relation;

        return $this;
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
}
