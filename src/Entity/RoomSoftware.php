<?php

namespace App\Entity;

use App\Repository\RoomSoftwareRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RoomSoftwareRepository::class)]
class RoomSoftware
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Room $assignedRoom = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Software $assignedSoftware = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAssignedRoom(): ?Room
    {
        return $this->assignedRoom;
    }

    public function setAssignedRoom(?Room $assignedRoom): static
    {
        $this->assignedRoom = $assignedRoom;

        return $this;
    }

    public function getAssignedSoftware(): ?Software
    {
        return $this->assignedSoftware;
    }

    public function setAssignedSoftware(?Software $assignedSoftware): static
    {
        $this->assignedSoftware = $assignedSoftware;

        return $this;
    }
}
