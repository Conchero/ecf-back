<?php

namespace App\Controller;

use App\Entity\Room;
use App\Form\RoomForm;
use App\Service\UploadService;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class RoomController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    #[Route('/room', name: 'app_room')]
    public function index(): Response
    {
        $rooms = $this->em->getRepository(Room::class)->findAll();

        return $this->render('room/index.html.twig', [
            'rooms' => $rooms,
        ]);
    }

    #[Route('/room/new', name: 'room_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UploadService $uploadService): Response
    {
        $room = new Room();
        $form = $this->createForm(RoomForm::class, $room);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $room->setOwner($this->getUser());

            $slugify = new Slugify();
            $room->setSlug($slugify->slugify($room->getTitle()));

            if ($image = $form->get('image')->getData()) {
                $room->setImage($uploadService->upload($image, 'image'));
            }

            
            $room->setIsAvailable(true); 

            $this->em->persist($room);
            $this->em->flush();

            $this->addFlash('success', "La salle a été créée avec succès.");
            return $this->redirectToRoute('app_room');
        }

        return $this->render('room/new.html.twig', [
            'roomForm' => $form->createView(),
        ]);
    }

    #[Route('/room/{slug}', name: 'room_view', methods: ['GET'])]
    public function view(string $slug): Response
    {
        $room = $this->em->getRepository(Room::class)->findOneBy(['slug' => $slug]);

        if (!$room) {
            throw $this->createNotFoundException('Salle non trouvée.');
        }

        if (!$room->isAvailable()) {
            if ($room->getOwner() !== $this->getUser()) {
                $this->addFlash('danger', "La salle n'est pas accessible pour le moment.");
                return $this->redirectToRoute('app_room');
            }
        }

        return $this->render('room/view.html.twig', [
            'room' => $room
        ]);
    }

    #[Route('/room/{slug}/edit', name: 'room_edit', methods: ['GET', 'POST'])]
    public function edit(string $slug, Request $request, UploadService $us): Response
    {
        $room = $this->em->getRepository(Room::class)->findOneBy(['slug' => $slug]);

        if (!$room) {
            $this->addFlash('danger', "La salle n'existe pas");
            return $this->redirectToRoute('app_room');
        }

        $form = $this->createForm(RoomForm::class, $room);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                if ($image = $form->get('image')->getData()) {
                    $us->delete($room->getImage(), 'image');
                    $room->setImage($us->upload($image, 'image'));
                }

                if ($slugEdit = $request->get('slug-edit')) {
                    $slugify = new Slugify();
                    if ($room->getSlug() !== $slugEdit) {
                        $room->setSlug($slugify->slugify($slugEdit));
                    } else {
                        $room->setSlug($slugify->slugify($room->getTitle()));
                    }
                }

                $this->em->persist($room);
                $this->em->flush();

                $this->addFlash('success', 'Modification bien prise en compte');
            } catch (\Throwable $th) {
                $this->addFlash('danger', 'La modification a rencontré une erreur');
            }

            return $this->redirectToRoute('room_view', ['slug' => $room->getSlug()]);
        }

        return $this->render('room/edit.html.twig', [
            'roomForm' => $form,
            'room' => $room
        ]);
    }

    #[Route('/room/{slug}/delete', name: 'room_delete', methods: ['POST'])]
    public function delete(string $slug): Response
    {
        $room = $this->em->getRepository(Room::class)->findOneBy(['slug' => $slug]);

        if (!$room) {
            $this->addFlash('danger', "La salle n'existe pas");
            return $this->redirectToRoute('app_room');
        }

        $this->em->remove($room);
        $this->em->flush();

        $this->addFlash('success', "Salle supprimée avec succès");
        return $this->redirectToRoute('app_room');
    }
}