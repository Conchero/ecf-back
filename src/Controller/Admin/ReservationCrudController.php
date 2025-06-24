<?php

namespace App\Controller\Admin;

use App\Entity\Reservation;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use Symfony\Component\HttpFoundation\RedirectResponse;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;

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

    public function configureActions(Actions $actions): Actions
    {
        $accept = Action::new('accept', 'Accepter', 'fas fa-check')
            ->linkToCrudAction('acceptReservation')
            ->addCssClass('btn btn-success')
            ->displayIf(fn (Reservation $r) => $r->getStatus() !== 'accepted')
            ->displayAsButton();

        $reject = Action::new('reject', 'Refuser', 'fas fa-times')
            ->linkToCrudAction('rejectReservation')
            ->addCssClass('btn btn-danger')
            ->displayIf(fn (Reservation $r) => $r->getStatus() !== 'rejected')
            ->displayAsButton();

        return $actions
            ->add(Crud::PAGE_INDEX, $accept)
            ->add(Crud::PAGE_INDEX, $reject)
            ->add(Crud::PAGE_DETAIL, $accept)
            ->add(Crud::PAGE_DETAIL, $reject);
    }

    public function acceptReservation(AdminContext $context): RedirectResponse
    {
        $entityDto = $context->getEntity();
        if (!$entityDto) {
            $this->addFlash('danger', 'Réservation non trouvée.');
            return $this->redirect($context->getReferrer());
        }

        /** @var Reservation $reservation */
        $reservation = $entityDto->getInstance();
        $reservation->setStatus('accepted');
        $this->em->flush($reservation);

        $this->addFlash('success', 'Réservation acceptée.');
        return $this->redirect($context->getReferrer());
    }

    public function rejectReservation(AdminContext $context): RedirectResponse
    {
        $entityDto = $context->getEntity();
        if (!$entityDto) {
            $this->addFlash('danger', 'Réservation non trouvée.');
            return $this->redirect($context->getReferrer());
        }

        /** @var Reservation $reservation */
        $reservation = $entityDto->getInstance();
        $reservation->setStatus('rejected');
        $this->em->flush();

        $this->addFlash('danger', 'Réservation refusée.');
        return $this->redirect($context->getReferrer());
    }
    
}
