<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/inscription", name="register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();

        

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        
         // Encodage du mot de passe
         $user->setPassword($encoder->encodePassword($user, $user->getPassword()));

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        $this->addFlash('sucess', 'utilisateur crée');
        return $this->redirectToRoute('home');
    }

        return $this->render('user/index.html.twig', [
                'form' => $form->createView()
        ]);
    }
}
