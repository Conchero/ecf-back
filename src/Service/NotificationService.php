<?php
namespace App\Service;

use App\Entity\Notification;
use App\Repository\ReservationRepository;
use App\Entity\Reservation;
use App\Entity\User;
use DateTime;

class NotificationService
{
    private ReservationRepository $reservationRepository;

    public function __construct(ReservationRepository $reservationRepository)
    {
        $this->reservationRepository = $reservationRepository;
    }

    public function getNotifications(): array
    {
        $now = new \DateTime();
        $limitDate = (clone $now)->modify('+5 days');

        $reservations = $this->reservationRepository->createQueryBuilder('r')
            ->andWhere('r.status = :status')
            ->andWhere('r.reservationStart BETWEEN :now AND :limit')
            ->setParameter('status', 'pending')
            ->setParameter('now', $now)
            ->setParameter('limit', $limitDate)
            ->getQuery()
            ->getResult();

        $notifications = [];
        foreach ($reservations as $reservation) {
            $notifications[] = [
                'reservationId' => $reservation->getId(),
                'message' => sprintf(
                    'Réservation #%d à valider avant le %s',
                    $reservation->getId(),
                    $reservation->getReservationStart()->format('d/m/Y')
                ),
            ];
        }

        return $notifications;
    }


    public function CheckReservation5DayLimits(\DateTime $_start): bool
    {
              if( intval($_start->format("m")) === intval(getdate()["mon"]) )
                {
                    if (intval($_start->format("d")) - intval(getdate()["mday"]) <= 5  )
                    {
                        return true;
                    }
                }
                return false;
    }


    public function CreateNotification(Reservation $reservation,string $message, User $user): Notification
    {
        $notifToSend = new Notification();
        $notifToSend->setReservation($reservation)->setMessage($message)->setReceiver($user);

        return $notifToSend;

    }


    public function countNotifications(): int
    {
        return count($this->getNotifications());
    }
}
