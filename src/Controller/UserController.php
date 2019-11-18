<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\UserEditType;
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
        return $this->redirectToRoute('app_login');
    }

        return $this->render('user/index.html.twig', [
                'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/profil", name="user_profil")
     */
    public function profil()
    {
        $user = $this->getUser();

        return $this->render('user/profil.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/edit/profil", name="edit_profil")
     */
    public function edit_profil(Request $request)
    {
        $user = $this->getUser();

        $form = $this->createForm(UserEditType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return $this->redirectToRoute('user_profil');
        }
        return $this->render('user/edit_profil.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    }
