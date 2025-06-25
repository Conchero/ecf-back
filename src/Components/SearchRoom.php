<?php

namespace App\Components;

use App\Repository\RoomRepository;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;

#[AsLiveComponent('SearchRoom', template: 'components/SearchRoom.html.twig')]
final class SearchRoom
{
    use DefaultActionTrait;

    #[LiveProp(writable: true, url: true)]
    public ?string $query = null;

    public function __construct(private RoomRepository $roomRepository)
    {
    }

    public function getRooms(): array
    {
        if ($this->query) {
            return $this->roomRepository->searchByTitle($this->query);
        }

        return $this->roomRepository->findBy([], ['id' => 'DESC'], 10);
    }
}
