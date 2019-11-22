<?php

namespace App\Controller\Admin;

use App\Entity\Review;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReviewController extends AbstractController
{
    /**
     * @Route("/delete/review/{id}", name="delete_review")
     * @IsGranted("ROLE_ADMIN")
     */
    public function deleteReview(Review $review)
    {
        $entityManager = $this->getDoctrine()->getManager();

        // suppression de la review puis flush
        $entityManager->remove($review);
        $entityManager->flush();
        //redirection sur la Place 
        return $this->redirectToRoute('place',
        ['id' => $review->getPlace()->getId(),
        ]);
    }
}
