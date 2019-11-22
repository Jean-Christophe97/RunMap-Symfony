<?php

namespace App\Controller;

use App\Repository\PlaceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(PlaceRepository $repo)
    {
        // je récupere le dernier lieu crée
        $places = $repo->findPlaceByDate();
        
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'places' => $places
        ]);
    }
}
