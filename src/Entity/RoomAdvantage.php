<?php

namespace App\Entity;

use App\Repository\RoomAdvantageRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RoomAdvantageRepository::class)]
class RoomAdvantage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Advantage $assignedRoom = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Room $assignedAdvantage = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAssignedRoom(): ?Advantage
    {
        return $this->assignedRoom;
    }

    public function setAssignedRoom(?Advantage $assignedRoom): static
    {
        $this->assignedRoom = $assignedRoom;

        return $this;
    }

    public function getAssignedAdvantage(): ?Room
    {
        return $this->assignedAdvantage;
    }

    public function setAssignedAdvantage(?Room $assignedAdvantage): static
    {
        $this->assignedAdvantage = $assignedAdvantage;

        return $this;
    }
}
