<?php

namespace App\Controller;

use App\Entity\Place;
use App\Entity\Review;
use App\Form\PlaceType;
use App\Form\ReviewType;
use App\Form\PlaceEditType;
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
    public function showPlace(Place $place, Request $request) 
    // https://symfony.com/doc/current/bundles/SensioFrameworkExtraBundle/index.html
    {
        $reviews = $place->getReviews();
        // dd($reviews);


        $review = new Review();

        $user = $this->getUser();


        $form = $this->createForm(ReviewType::class, $review);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {

            $review->setUser($user);
            $review->setPlace($place);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($review);
            $entityManager->flush();
            
            return $this->redirectToRoute('place', ['id' => $place->getId()]);
        }




        return $this->render('place/show.html.twig', [
            'reviews' => $reviews,
            'place' => $place,
            'form' => $form->createView()
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

    /**
     * @Route("/edit/place/{id}", name="edit_place")
     */
    public function edit_place($id, Request $request)
    {
        $place = $this->getDoctrine()->getRepository(Place::class)->find($id);

        $form = $this->createForm(PlaceEditType::class, $place);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return $this->redirectToRoute('allPlaces');
        }
        return $this->render('place/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
