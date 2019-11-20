<?php

namespace App\Controller;

use App\Entity\Review;
use App\Form\ReviewType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReviewController extends AbstractController
{
    /**
     * @Route("/create/review", name="create_review")
     */
    public function createReview(Request $request)
    {
        $review = new Review();

        $user = $this->getUser();

        $form = $this->createForm(ReviewType::class, $review);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {

            $review->setUser($user);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($review);
            $entityManager->flush();
            
            return $this->redirectToRoute('home');
        }



        return $this->render('review/index.html.twig', [
            'controller_name' => 'ReviewController',
            'form' => $form->createView()
        ]);
    }
}
