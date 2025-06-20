<?php
// src/Controller/NotificationController.php

namespace App\Controller;

use App\Service\NotificationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NotificationController extends AbstractController
{
    private NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    #[Route('/notifications', name: 'app_notifications')]
    public function index(): Response
    {
        // On récupère les notifications (réservations à valider dans les 5 jours)
        $notifications = $this->notificationService->getNotifications();

        return $this->render('notifications/index.html.twig', [
            'notifications' => $notifications,
        ]);
    }
}
