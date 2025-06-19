<?php

namespace App\Controller\Admin;

use App\Entity\Room;
use App\Entity\User;
use App\Entity\Software;
use App\Entity\Advantage;
use App\Entity\Equipment;
use App\Entity\Reservation;
use App\Entity\Notification;


use Symfony\Component\HttpFoundation\Response;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    // public function __construct(
    //     private AdminUrlGenerator $adminUrlGenerator
    // )
    public function index(): Response
    {
        // return parent::index();
        $routeBuilder = $this->container->get(AdminUrlGenerator::class);
        $url = $routeBuilder->setController(ReservationCrudController::class)->generateUrl();
        return $this->redirect($url);

        //return $this->redirect($adminUrlGenerator-setController(OneOfYourCrudControllers::class)->generateUrl());
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
     

        yield MenuItem::linkToCrud('Notifications', 'fas fa-bell', Notification::class);
  
        yield MenuItem::linkToCrud('Réservations', 'fas fa-calendar-alt', Reservation::class);
        yield MenuItem::subMenu('Salles','fas fa-building','fas fa-bar')->setSubItems([
            MenuItem::linkToCrud('toutes', 'fas fa-list', Room::class),
            MenuItem::linkTocrud('creer', 'fas fa-plus', Room::class)->setAction(Crud::PAGE_NEW),
        ]);
        yield MenuItem::subMenu('Equipements','fas fa-tools')->setSubItems([
            MenuItem::linkToCrud('tous', 'fas fa-list', Equipment::class),
            MenuItem::linkToCrud('creer', 'fas fa-plus', Equipment::class)->setAction(Crud::PAGE_NEW),
        ]);
        yield MenuItem::subMenu('Ergonomiques','fas fa-heart')->setSubItems([
            MenuItem::linkToCrud('tous', 'fas fa-list', Advantage::class),
            MenuItem::linkToCrud('creer', 'fas fa-plus', Advantage::class)->setAction(Crud::PAGE_NEW),
        ]);
        yield MenuItem::subMenu('Logiciels','fas fa-laptop-code')->setSubItems([
            MenuItem::linkToCrud('tous', 'fas fa-list', Software::class),
            MenuItem::linkToCrud('creer', 'fas fa-plus', Software::class)->setAction(Crud::PAGE_NEW),
        ]);


        yield MenuItem::subMenu('Clients','fas fa-users')->setSubItems([
            MenuItem::linkToCrud('tous', 'fas fa-list', User::class),
            MenuItem::linkToCrud('creer', 'fas fa-plus', User::class)->setAction(Crud::PAGE_NEW),
        ]);
    }
}
        
