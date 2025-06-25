<?php

namespace App\Controller;

use App\Entity\Room;
use App\Form\RoomForm;
use App\Model\SearchData;
use Cocur\Slugify\Slugify;
use App\Form\SearchTypeForm;
use App\Form\ReservationForm;
use App\Service\UploadService;
use App\Repository\RoomRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReservationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

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

    // Vérifier que l'utilisateur est connecté (au moins ROLE_USER)
    $this->denyAccessUnlessGranted('ROLE_USER');

    // Vérifie la disponibilité et le propriétaire
    if (!$room->isAvailable() && $room->getOwner() !== $this->getUser()) {
        $this->addFlash('danger', "La salle n'est pas accessible pour le moment.");
        return $this->redirectToRoute('app_room');
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
                if ($room->getImage()) {
                    $us->delete($room->getImage(), 'image');
                }
                $room->setImage($us->upload($image, 'image'));
            }

            // Le slug est géré automatiquement si champ slug ajouté au formulaire
            if (!$room->getSlug()) {
                $slugify = new Slugify();
                $room->setSlug($slugify->slugify($room->getTitle()));
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
        'form' => $form->createView(),
        'room' => $room,
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

    public function search(Request $request, RoomRepository $roomRepository): Response
    {
        $searchData = new SearchData();
        $form = $this->createForm(SearchTypeForm::class, $searchData, ['method' => 'GET']);
        $form->handleRequest($request);

        $rooms = [];
        if ($form->isSubmitted() && $form->isValid()) {
            // Recherche des salles filtrées
            $rooms = $roomRepository->findByFilters($searchData);
        }

        return $this->render('room/search.html.twig', [
            'form' => $form->createView(),
            'rooms' => $rooms,
        ]);
    }

    #[Route('/mes-reservations', name: 'my_reservations', methods: ['GET'])]
    public function myReservations(ReservationRepository $reservationRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $user = $this->getUser();
        $reservations = $reservationRepository->findBy(['client' => $user], ['reservationStart' => 'DESC']);

        return $this->render('reservation/reservations.html.twig', [
            'reservations' => $reservations,
        ]);
    }
    #[Route('/reservation/{id}/edit', name: 'reservation_edit', methods: ['GET', 'POST'])]
    public function reservationEdit(int $id, Request $request, ReservationRepository $reservationRepository): Response
    {
        $reservation = $reservationRepository->find($id);
        if (!$reservation || $reservation->getClient() !== $this->getUser()) {
            throw $this->createNotFoundException('Réservation non trouvée.');
        }

        $now = new \DateTime();
        $start = $reservation->getReservationStart();
        $diff = $start->diff($now)->days;
        $isFuture = $start > $now;

        // Règle : peut modifier si en attente OU acceptée et pas encore commencée
        if (!($reservation->getStatus() === 'pending' || ($reservation->getStatus() === 'accepted' && $isFuture))) {
            $this->addFlash('danger', 'Vous ne pouvez pas modifier cette réservation.');
            return $this->redirectToRoute('my_reservations');
        }

        $form = $this->createForm(ReservationForm::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Si la réservation était acceptée, elle repasse en attente
            if ($reservation->getStatus() === 'accepted') {
                $reservation->setStatus('pending');
            }
            $this->em->flush();
            $this->addFlash('success', 'Réservation modifiée.');
            return $this->redirectToRoute('my_reservations');
        }

        return $this->render('reservation/edit.html.twig', [
            'form' => $form->createView(),
            'reservation' => $reservation,
        ]);
    }

    #[Route('/reservation/{id}/delete', name: 'reservation_delete', methods: ['POST'])]
    public function reservationDelete(int $id, Request $request, ReservationRepository $reservationRepository): RedirectResponse
    {
        $reservation = $reservationRepository->find($id);
        if (!$reservation || $reservation->getClient() !== $this->getUser()) {
            throw $this->createNotFoundException('Réservation non trouvée.');
        }

        $now = new \DateTime();
        $start = $reservation->getReservationStart();
        $diff = $start->diff($now)->days;
        $isFuture = $start > $now;

        //supprimer si en attente OU acceptée et plus de 3 jours avant la date de début
        if (!($reservation->getStatus() === 'pending' || ($reservation->getStatus() === 'accepted' && $isFuture && $diff > 3))) {
            $this->addFlash('danger', 'Vous ne pouvez pas supprimer cette réservation.');
            return $this->redirectToRoute('my_reservations');
        }

        if ($this->isCsrfTokenValid('delete' . $reservation->getId(), $request->request->get('_token'))) {
            $this->em->remove($reservation);
            $this->em->flush();
            $this->addFlash('success', 'Réservation supprimée.');
        } else {
            $this->addFlash('danger', 'Token CSRF invalide.');
        }
        return $this->redirectToRoute('my_reservations');
    }
}