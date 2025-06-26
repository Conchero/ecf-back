<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Service\NotificationService;
use App\Repository\UserRepository;
use App\Repository\NotificationRepository;
use App\Entity\Notification;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;

#[AsCommand(
    name: 'app:check-urgent-reservations',
    description: 'Vérifie les réservations en attente et envoie des notifications urgentes 5 jours avant.',
)]
class CheckUrgentReservationsCommand extends Command
{
    private $notificationService;
    private $userRepository;
    private $notificationRepository;
    private $reservationRepository;
    private $entityManager;

    public function __construct(
        NotificationService $notificationService,
        UserRepository $userRepository,
        NotificationRepository $notificationRepository,
        ReservationRepository $reservationRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->notificationService = $notificationService;
        $this->userRepository = $userRepository;
        $this->notificationRepository = $notificationRepository;
        $this->reservationRepository = $reservationRepository;
        $this->entityManager = $entityManager;

        parent::__construct();
    }

    protected function configure(): void
    {
        // Configuration si nécessaire
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->info('Début de la vérification des réservations urgentes...');

        // 1. Récupérer les réservations urgentes
        // Le service retourne un tableau, on va chercher les entités complètes
        $urgentReservationData = $this->notificationService->getNotifications();
        $reservationIds = array_column($urgentReservationData, 'reservationId');

        if (empty($reservationIds)) {
            $io->success('Aucune réservation urgente à notifier.');
            return Command::SUCCESS;
        }

        $reservations = $this->reservationRepository->findBy(['id' => $reservationIds]);

        // 2. Trouver l'admin (on suppose qu'il y en a un avec le rôle ADMIN)
        $admin = $this->userRepository->findOneByRole('ROLE_ADMIN');
        if (!$admin) {
            $io->error('Aucun utilisateur avec le rôle "ROLE_ADMIN" n\'a été trouvé.');
            return Command::FAILURE;
        }

        $notificationsCreated = 0;

        foreach ($reservations as $reservation) {
            //  Vérifier si une notification pour cette réservation et cet admin existe déjà
            $existingNotification = $this->notificationRepository->findOneBy([
                'reservation' => $reservation,
                'receiver' => $admin
            ]);

            if (!$existingNotification) {
                //  Créer la notification
                $notification = new Notification();
                $notification->setReservation($reservation);
                $notification->setReceiver($admin);
                $notification->setMessage(sprintf(
                    'URGENT : La réservation pour la salle "%s" commence le %s et est toujours en attente.',
                    $reservation->getRentedRoom()->getTitle(),
                    $reservation->getReservationStart()->format('d/m/Y')
                ));
                $notification->setIsRead(false);

                $this->entityManager->persist($notification);
                $notificationsCreated++;
            }
        }

        if ($notificationsCreated > 0) {
            $this->entityManager->flush();
            $io->success(sprintf('%d notification(s) urgente(s) créée(s) pour l\'admin.', $notificationsCreated));
        } else {
            $io->info('Toutes les réservations urgentes avaient déjà une notification.');
        }

        return Command::SUCCESS;
    }
}
