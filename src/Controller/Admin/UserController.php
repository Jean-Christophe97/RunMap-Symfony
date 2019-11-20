<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    /**
     * @Route("/admin/user", name="admin_user")
     */
    public function allUsers(UserRepository $repository)
    {
        $users = $repository->findAll();

        return $this->render('admin/user/index.html.twig', [
            'users' => $users
        ]);
    }

    /**
     * @Route("/delete/user/{id}", name="delete_user")
     */
    public function deleteUser($id)
    {
        //je recherche les utilisateurs par id
        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(User::class)->find($id);
        // suppression de l'utilisateur puis flush
        $entityManager->remove($user);
        $entityManager->flush();
        //redirection sur la page admin
        return $this->redirectToRoute('admin_user');
    }
}
