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
        $city = new City();
        $formCity = $this->createForm(CityType::class, $city);
        $formCity->handleRequest($request);
        //dd($formCity);
        $place = new Place();
        $formPlace = $this->createForm(PlaceType::class, $place);
        $formPlace->handleRequest($request);

        $city = $formCity["name"]->getData();
        //dump($city);
        $cityNameBdd = $cityRepository->findOneByName($city);
        //dd($cityNameBdd);

        if ($cityNameBdd === null){

                $newCity = new City();
                $formCity = $this->createForm(CityType::class, $newCity);
                $formCity->handleRequest($request);

                
                    $cityName = $formCity["name"]->getData();
                    //dump($cityName);
                    $cityPostal = $formCity["postalcode"]->getData();
                    //dd($cityPostal);

                    $newCity->setName($cityName);
                    $newCity->setPostalcode($cityPostal);
                    
                    if ($formCity->isSubmitted() && $formCity->isValid()){
                    $manager->persist($newCity);
                    $manager->flush();
                    //dd($newCity);
                }
                    $cityId = $newCity;
                } else {
                    $cityId = $cityNameBdd;
                }

        $placeName = $formPlace["name"]->getData();
        //dump($placeName);
        $placeAdress = $formPlace["adress"]->getData();
        //dump($placeAdress);
        $placeSchedule = $formPlace["schedule"]->getData();
        //dump($placeSchedule);
        $placeComplementInfo = $formPlace["complementinfo"]->getData();
        //dump($placeComplementInfo);

        $place->setName($placeName);
        $place->setAdress($placeAdress);
        $place->setSchedule($placeSchedule);
        $place->setComplementinfo($placeComplementInfo);
        //dd($cityId);
        $place->setCity($cityId);
        //dd($place);

        if ($formPlace->isSubmitted() && $formPlace->isValid()){
        
            // $manager = $this->getDoctrine()->getManager();
            $manager->persist($place);
            //dd($place);
            $manager->flush();
            
            return $this->redirectToRoute('place', ['id' => $place->getId()]);
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
