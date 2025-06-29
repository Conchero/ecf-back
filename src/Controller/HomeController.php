<?php

namespace App\Controller;

use App\Repository\RoomRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(RoomRepository $roomRepository): Response
    {
        $rooms = $roomRepository->findAll(); // Récupère toutes les salles depuis le repository

        return $this->render('home/index.html.twig', [
            'rooms' => $rooms,
        ]);
    }
}
