<?php

namespace App\Controller;

use App\Entity\Place;
use App\Form\PlaceType;
use App\Repository\PlaceRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PlaceController extends AbstractController
{
    /**
     * @Route("/places", name="allPlaces")
     */
    public function allPlaces(PlaceRepository $repository)
    {
        $places = $repository->findAll();
        // dd($places);
        

        return $this->render('place/index.html.twig', [
            'places' => $places,
        ]);
    }

    /**
     * @Route("/place/{id}", name="place")
     */
    public function showPlace(Place $place) // https://symfony.com/doc/current/bundles/SensioFrameworkExtraBundle/index.html
    {
        $reviews = $place->getReviews();
        // dump($reviews);

        return $this->render('place/show.html.twig', [
            'reviews' => $reviews,
            'place' => $place
        ]);
    }

    /**
     * @Route("/create/place", name="new_place")
     */
    public function newPlace(Request $request)
    {
        $place = new Place();

        $form = $this->createForm(PlaceType::class, $place);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($place);
            $entityManager->flush();
            
            return $this->redirectToRoute('home');
        }




        return $this->render('place/new_place.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
