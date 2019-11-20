<?php

namespace App\Controller;

use App\Entity\City;
use App\Entity\Place;
use App\Form\CityType;
use App\Form\PlaceType;
use App\Repository\CityRepository;
use App\Repository\PlaceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
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
    public function showPlace(Place $place) 
    // https://symfony.com/doc/current/bundles/SensioFrameworkExtraBundle/index.html
    {
        $reviews = $place->getReviews();
        // dd($reviews);

        return $this->render('place/show.html.twig', [
            'reviews' => $reviews,
            'place' => $place
        ]);
    }

    /**
     * @Route("/create/place", name="new_place")
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
            
            return $this->redirectToRoute('home');
        }

        return $this->render('place/new_place.html.twig', [
            'formCity' => $formCity->createView(),
            'formPlace' => $formPlace->createView(),
        ]);
    }

}
