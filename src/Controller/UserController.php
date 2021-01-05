<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use App\Form\UserType;



class UserController extends AbstractController
{
    /**
     * @Route("/account", name="account_user")
     */
    public function checkAccount(): Response {
        $repository = $this->getDoctrine()->getRepository(User::class);
        $account = $repository->findAll();
        return $this->render('user/account.html.twig', [
            'accounts' => $account,
        ]);
    }

    /**
     * @Route("/account/edit/{id}", name="edit_account_user")
     */
    public function editAccount(Request $request, User $user): Response {
        $form = $this->createForm(UserType::class, $user);        
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            $users = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Modification de votre compte réussi !');
            return $this->redirectToRoute('account_user');
        }

        return $this->render('user/formEdit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}