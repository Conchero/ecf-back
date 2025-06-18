<?php

namespace App\Entity;

use App\Repository\RoomEquipmentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RoomEquipmentRepository::class)]
class RoomEquipment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Equipement $assignedRoom = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Room $assignedEquipement = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAssignedRoom(): ?Equipement
    {
        return $this->assignedRoom;
    }

    public function setAssignedRoom(?Equipement $assignedRoom): static
    {
        $this->assignedRoom = $assignedRoom;

        return $this;
    }

    public function getAssignedEquipement(): ?Room
    {
        return $this->assignedEquipement;
    }

    public function setAssignedEquipement(?Room $assignedEquipement): static
    {
        $this->assignedEquipement = $assignedEquipement;

        return $this;
    }
}
