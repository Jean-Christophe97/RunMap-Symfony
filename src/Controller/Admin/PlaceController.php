<?php

namespace App\Controller\Admin;

use App\Entity\Place;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PlaceController extends AbstractController
{
    /**
    * @Route("/delete/place/{id}", name="delete_place")
    */
    public function deletePlace($id)
    {
        //je recherche les lieux par id
        $entityManager = $this->getDoctrine()->getManager();
        $place = $entityManager->getRepository(Place::class)->find($id);
        // suppression du lieu puis flush
        $entityManager->remove($place);
        $entityManager->flush();
        //redirection sur la page places
        return $this->redirectToRoute('allPlaces');

    }
}
