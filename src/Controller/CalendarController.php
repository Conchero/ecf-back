<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;  // <--- à importer
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CalendarController extends AbstractController
{
    #[Route('/calendar', name: 'calendar_form', methods: ['GET', 'POST'])]
    public function form(Request $request): Response
    {
        // Exemple de réservations existantes
        $reservations = [
            ['startDate' => new \DateTime('2025-06-20'), 'endDate' => new \DateTime('2025-06-25')],
            ['startDate' => new \DateTime('2025-07-01'), 'endDate' => new \DateTime('2025-07-03')],
        ];

        // Préparation des plages réservées pour Flatpickr
        $reservedRanges = array_map(function($res) {
            return [
                'from' => $res['startDate']->format('Y-m-d'),
                'to' => $res['endDate']->format('Y-m-d'),
            ];
        }, $reservations);

        if ($request->isMethod('POST')) {
            // Récupérer la valeur postée
            $dateRange = $request->request->get('date_range');

            // Par exemple : afficher un message flash
            $this->addFlash('success', 'Vous avez sélectionné : ' . $dateRange);

            // Rediriger pour éviter le repost du formulaire
            return $this->redirectToRoute('calendar_form');
        }

        return $this->render('calendar/form.html.twig', [
            'reservedRanges' => $reservedRanges,
        ]);
    }
}
