<?php

namespace App\Controller;

use App\Entity\City;
use App\Entity\Place;

use App\Entity\Review;
use App\Form\CityType;
use App\Form\PlaceType;

use App\Form\ReviewType;
use App\Form\PlaceEditType;
use App\Repository\CityRepository;

use App\Repository\PlaceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PlaceController extends AbstractController
{
    /**
     * @Route("/places", name="allPlaces")
     */
    public function allPlaces(PlaceRepository $repository)
    {
        $places = $repository->findAll();

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
        $review = new Review();
        $user = $this->getUser();
        // je crée le formulaire reliée à l'entitée review 
        $form = $this->createForm(ReviewType::class, $review);
        //je récupere la requete
        $form->handleRequest($request);
        // condition si le form et soumis est valide 
        if ($form->isSubmitted() && $form->isValid())
        {
            // j'associe l'utilisateur connecté à la review
            $review->setUser($user);
            // j'associe la review à la place actuelle
            $review->setPlace($place);
            // j'appelle entitymanager pour sauvegarder mes données en BDD persist et flush
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($review);
            $entityManager->flush();
        
            // je retourne un message flash
            $this->addFlash('success', 'Avis ajouté');
            // je renvoie l'utilisateur sur la place actuelle
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
     * @IsGranted("ROLE_USER")
     */
    public function newPlace(Request $request, CityRepository $cityRepository, ObjectManager $manager)
    {
        $newCity = new City();
        $formCity = $this->createForm(CityType::class, $newCity);
        $formCity->handleRequest($request);
        //dd($formCity);
        $newPlace = new Place();
        $formPlace = $this->createForm(PlaceType::class, $newPlace);
        $formPlace->handleRequest($request);
        $city = $formCity["name"]->getData();
        //dump($city);
        $cityNameBdd = $cityRepository->findOneByName($city);
        //dd($cityNameBdd);
        if ($formCity->isSubmitted() && $formCity->isValid()){
            if ($cityNameBdd === null){
                $cityName = $formCity["name"]->getData();
                //dump($cityName);
                $cityPostal = $formCity["postalcode"]->getData();
                //dd($cityPostal);
                $newCity->setName($cityName);
                $newCity->setPostalcode($cityPostal);

                $manager->persist($newCity);
                $manager->flush();
                //dd($newCity);
            
                $cityId = $newCity;
                //dd($cityId);
            } else {
                $cityId = $cityNameBdd;
                //dd($cityId);
            }
        
        if ($formPlace->isSubmitted() && $formPlace->isValid()){
            $placeName = $formPlace["name"]->getData();
            //dump($placeName);
            $placeAdress = $formPlace["adress"]->getData();
            //dump($placeAdress);
            $placeSchedule = $formPlace["schedule"]->getData();
            //dump($placeSchedule);
            $placeComplementInfo = $formPlace["complementinfo"]->getData();
            //dump($placeComplementInfo);
            $newPlace->setName($placeName);
            $newPlace->setAdress($placeAdress);
            $newPlace->setSchedule($placeSchedule);
            $newPlace->setComplementinfo($placeComplementInfo);
            //dd($cityId);
            $newPlace->setCity($cityId);
            //dd($place);
            $manager->persist($newPlace);
            //dd($place);
            $manager->flush();
            
            return $this->redirectToRoute('place', ['id' => $newPlace->getId()]);
        }
    }        


        return $this->render('place/new_place.html.twig', [
            'formCity' => $formCity->createView(),
            'formPlace' => $formPlace->createView(),
        ]);
    }

    /**
     * @Route("/edit/place/{id}", name="edit_place")
     * @IsGranted("ROLE_USER")
     * 
     */
    public function edit_place($id, Request $request)
    {
        // je récupere une place par id
        $place = $this->getDoctrine()->getRepository(Place::class)->find($id);
        $form = $this->createForm(PlaceEditType::class, $place);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            $this->addFlash('warning', 'lieu modifié');
            return $this->redirectToRoute('place', ['id' => $place->getId()]);
        }
        return $this->render('place/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
