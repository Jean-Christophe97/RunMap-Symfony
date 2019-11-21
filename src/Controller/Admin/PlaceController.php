<?php

namespace App\Controller\Admin;

use App\Entity\Place;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PlaceController extends AbstractController
{
    /**
    * @Route("/delete/place/{id}", name="delete_place")
    * @IsGranted("ROLE_ADMIN")
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
