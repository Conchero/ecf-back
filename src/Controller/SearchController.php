<?php

namespace App\Controller;

use App\Form\SearchTypeForm;
use App\Model\SearchData;
use App\Repository\RoomRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class SearchController extends AbstractController
{
    #[Route('/search', name: 'search')]
    public function index(Request $request, RoomRepository $roomRepository): Response
    {
        $searchData = new SearchData();

        $rooms = [];

        $form = $this->createForm(SearchTypeForm::class, $searchData, [
            'method' => 'GET',
            'csrf_protection' => false,
        ]);
        $form->handleRequest($request);
        
      if ($form->isSubmitted() && $form->isValid()) {
            $rooms = $roomRepository->findByFilters($searchData);

           
        }else{
            $rooms = $roomRepository->findBy(['is_available' => true]);
        }
   

        return $this->render('room/search.html.twig', [
            'form' => $form->createView(),
            'rooms' => $rooms,
        ]);
    }
}
