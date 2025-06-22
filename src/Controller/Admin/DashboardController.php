<?php

namespace App\Controller\Admin;

use App\Entity\Notification;
use App\Entity\Reservation;
use App\Entity\Room;
use App\Entity\User;
use App\Entity\Software;
use App\Entity\Advantage;
use App\Entity\Equipment;
use App\Service\NotificationService;
use App\Controller\Admin\NotificationCrudController;
use App\Controller\Admin\ReservationCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

class DashboardController extends AbstractDashboardController
{
    private NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        // Rendu du dashboard personnalisé
        return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Tableau de bord');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::section('Location salles');

        yield MenuItem::linkToRoute('Retour au site', 'fas fa-home', 'app_home');

        $urgentCount = $this->notificationService->countNotifications();

        yield MenuItem::linkToCrud('Notifications', 'fas fa-bell', Notification::class)
            ->setController(NotificationCrudController::class)
            ->setBadge($urgentCount > 0 ? (string) $urgentCount : null);

        yield MenuItem::linkToCrud('Réservations', 'fas fa-calendar-alt', Reservation::class)
            ->setController(ReservationCrudController::class);

        yield MenuItem::subMenu('Salles', 'fas fa-building')->setSubItems([
            MenuItem::linkToCrud('Toutes', 'fas fa-list', Room::class),
            MenuItem::linkToCrud('Créer', 'fas fa-plus', Room::class)->setAction(Crud::PAGE_NEW),
        ]);

        yield MenuItem::subMenu('Équipements', 'fas fa-tools')->setSubItems([
            MenuItem::linkToCrud('Tous', 'fas fa-list', Equipment::class),
            MenuItem::linkToCrud('Créer', 'fas fa-plus', Equipment::class)->setAction(Crud::PAGE_NEW),
        ]);

        yield MenuItem::subMenu('Ergonomiques', 'fas fa-heart')->setSubItems([
            MenuItem::linkToCrud('Tous', 'fas fa-list', Advantage::class),
            MenuItem::linkToCrud('Créer', 'fas fa-plus', Advantage::class)->setAction(Crud::PAGE_NEW),
        ]);

        yield MenuItem::subMenu('Logiciels', 'fas fa-laptop-code')->setSubItems([
            MenuItem::linkToCrud('Tous', 'fas fa-list', Software::class),
            MenuItem::linkToCrud('Créer', 'fas fa-plus', Software::class)->setAction(Crud::PAGE_NEW),
        ]);

        yield MenuItem::subMenu('Clients', 'fas fa-users')->setSubItems([
            MenuItem::linkToCrud('Tous', 'fas fa-list', User::class),
            MenuItem::linkToCrud('Créer', 'fas fa-plus', User::class)->setAction(Crud::PAGE_NEW),
        ]);
    }
}
