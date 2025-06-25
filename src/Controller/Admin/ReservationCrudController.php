<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Reservation;
use App\Entity\Notification;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ReservationCrudController extends AbstractCrudController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public static function getEntityFqcn(): string
    {
        return Reservation::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('slug'),
            AssociationField::new('rentedRoom', 'Salle louée')->setRequired(true),
            AssociationField::new('client', 'Client')->setRequired(true),
            DateTimeField::new('reservationStart', 'Date début'),
            DateTimeField::new('reservationEnd', 'Date fin'),
            TextField::new('status'),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        $accept = Action::new('accept', 'Accepter', 'fas fa-check')
            ->linkToCrudAction('accept')
            ->addCssClass('btn btn-success')
            ->displayIf(static fn ($entity) => $entity instanceof Reservation && $entity->getStatus() === 'pending');

        $reject = Action::new('reject', 'Refuser', 'fas fa-times')
            ->linkToCrudAction('reject')
            ->addCssClass('btn btn-danger')
            ->displayIf(static fn ($entity) => $entity instanceof Reservation && $entity->getStatus() === 'pending');

        return $actions
            ->add(Crud::PAGE_INDEX, $accept)
            ->add(Crud::PAGE_INDEX, $reject)
            ->add(Crud::PAGE_DETAIL, $accept)
            ->add(Crud::PAGE_DETAIL, $reject);
    }

    public function accept(AdminContext $context): RedirectResponse
    {
        $reservation = $context->getEntity()->getInstance();

        if (!$reservation instanceof Reservation) {
            $this->addFlash('danger', 'Réservation introuvable.');
            return $this->redirectToAdminList();
        }

        $reservation->setStatus('accepted');
        $this->em->flush();

        $this->addFlash('success', 'Réservation acceptée.');
        return $this->redirectToPreviousOrList($context);
    }

    public function reject(AdminContext $context): RedirectResponse
    {
        $reservation = $context->getEntity()->getInstance();

        if (!$reservation instanceof Reservation) {
            $this->addFlash('danger', 'Réservation introuvable.');
            return $this->redirectToAdminList();
        }

        $reservation->setStatus('rejected');
        $this->em->flush();

        $this->addFlash('warning', 'Réservation refusée.');
        return $this->redirectToPreviousOrList($context);
    }

    private function redirectToPreviousOrList(AdminContext $context): RedirectResponse
    {
        $referrer = $context->getReferrer();
        if ($referrer) {
            return $this->redirect($referrer);
        }

        return $this->redirectToAdminList();
    }

    private function redirectToAdminList(): RedirectResponse
    {
        return $this->redirect($this->generateUrl('admin', [
            'crudControllerFqcn' => self::class,
        ], UrlGeneratorInterface::ABSOLUTE_URL));
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        parent::persistEntity($entityManager, $entityInstance);

        if ($entityInstance instanceof Reservation) {
            $this->createUrgentNotificationIfNeeded($entityInstance, $entityManager);
        }
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        parent::updateEntity($entityManager, $entityInstance);

        if ($entityInstance instanceof Reservation) {
            $this->createUrgentNotificationIfNeeded($entityInstance, $entityManager);
        }
    }

    private function createUrgentNotificationIfNeeded(Reservation $reservation, EntityManagerInterface $em): void
    {
        if ($reservation->getStatus() === 'pending') {
            $now = new \DateTime();
            $start = $reservation->getReservationStart();
            $days = (int) $now->diff($start)->format('%r%a');

            if ($days < 5 && $days >= 0) {
                $admin = $em->getRepository(User::class)->findOneByRole('ROLE_ADMIN');
                $existing = $em->getRepository(Notification::class)->findOneBy([
                    'reservation' => $reservation,
                    'receiver' => $admin,
                ]);

                if ($admin && !$existing) {
                    $notif = new Notification();
                    $notif->setReservation($reservation);
                    $notif->setReceiver($admin);
                    $notif->setMessage(sprintf(
                        'URGENT : La réservation pour la salle "%s" commence le %s et est toujours en attente.',
                        $reservation->getRentedRoom()->getTitle(),
                        $reservation->getReservationStart()->format('d/m/Y')
                    ));
                    $notif->setIsRead(false);

                    $em->persist($notif);
                    $em->flush();
                }
            }
        }
    }
}
