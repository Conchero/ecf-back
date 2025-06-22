<?php

namespace App\Controller;
use App\Entity\Room;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class RoomController extends AbstractController
{
    #[Route('/room', name: 'app_room')]
    public function index(): Response
    {
        return $this->render('room/index.html.twig', [
            'controller_name' => 'RoomController',
        ]);
    }

    
//     #[Route('/new', name: 'room_new')]
//     public function new(EntityManagerInterface $entityManager): Response
//     {
//         $product = new room();
//         $product->setName('Unity');
//         $product->setSlug('room-unity');
//         $product->setDescription('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.');

//         // tell Doctrine you want to (eventually) save the Product (no queries yet)
//         $entityManager->persist($product);

//         // actually executes the queries (i.e. the INSERT query)
//         $entityManager->flush();

//         return new Response('Saved new product with id '.$product->getId());
//     }
// }
}