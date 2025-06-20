<?php

namespace App\Controller\Admin;

use App\Entity\Notification;
use App\Entity\Reservation;
use App\Entity\Room;
use App\Entity\User;
use App\Entity\Software;
use App\Entity\Advantage;
use App\Entity\Equipment;
use App\Controller\Admin\NotificationCrudController;
use App\Controller\Admin\ReservationCrudController;
use App\Service\NotificationService;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    private NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function index(): Response
    {
        $routeBuilder = $this->container->get(AdminUrlGenerator::class);
        $url = $routeBuilder->setController(ReservationCrudController::class)->generateUrl();

        return $this->redirect($url);
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

        yield MenuItem::subMenu('Salles', 'fas fa-building', 'fas fa-bar')->setSubItems([
            MenuItem::linkToCrud('toutes', 'fas fa-list', Room::class),
            MenuItem::linkToCrud('créer', 'fas fa-plus', Room::class)->setAction(Crud::PAGE_NEW),
        ]);

        yield MenuItem::subMenu('Equipements', 'fas fa-tools')->setSubItems([
            MenuItem::linkToCrud('tous', 'fas fa-list', Equipment::class),
            MenuItem::linkToCrud('créer', 'fas fa-plus', Equipment::class)->setAction(Crud::PAGE_NEW),
        ]);

        yield MenuItem::subMenu('Ergonomiques', 'fas fa-heart')->setSubItems([
            MenuItem::linkToCrud('tous', 'fas fa-list', Advantage::class),
            MenuItem::linkToCrud('créer', 'fas fa-plus', Advantage::class)->setAction(Crud::PAGE_NEW),
        ]);

        yield MenuItem::subMenu('Logiciels', 'fas fa-laptop-code')->setSubItems([
            MenuItem::linkToCrud('tous', 'fas fa-list', Software::class),
            MenuItem::linkToCrud('créer', 'fas fa-plus', Software::class)->setAction(Crud::PAGE_NEW),
        ]);

        yield MenuItem::subMenu('Clients', 'fas fa-users')->setSubItems([
            MenuItem::linkToCrud('tous', 'fas fa-list', User::class),
            MenuItem::linkToCrud('créer', 'fas fa-plus', User::class)->setAction(Crud::PAGE_NEW),
        ]);
    }
}
