<?php

namespace App\Controller\Admin;

use App\Entity\Review;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReviewController extends AbstractController
{
    /**
     * @Route("/delete/review/{id}", name="delete_review")
     */
    public function deleteReview($id)
    {
        //je recherche les review par id
        $entityManager = $this->getDoctrine()->getManager();
        $review = $entityManager->getRepository(Review::class)->find($id);
        // suppression de la review puis flush
        $entityManager->remove($review);
        $entityManager->flush();
        //redirection sur la page admin
        return $this->redirectToRoute('allPlaces');
    }
}
