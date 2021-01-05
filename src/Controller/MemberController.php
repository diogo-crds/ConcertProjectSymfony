<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Member;
use App\Entity\Band;
use App\Form\MemberType;

class MemberController extends AbstractController
{
    /**
     * @Route("/member/form/", name="create_member")
     * @isGranted("ROLE_ADMIN")
     */
    public function addMember(Request $request): Response {
        $member = new Member();
        $form = $this->createForm(MemberType::class, $member);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $member = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($member);
            $entityManager->flush();

            $this->addFlash('success', 'Membre ajouté avec succés !');
            return $this->redirectToRoute('band_list');
        }   

        return $this->render('member/newmember.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    /**
     * @Route("/member/edit/{id}", name="edit_member")
     * @isGranted("ROLE_ADMIN")
     */
    public function editMember(Request $request, Member $member): Response {
        $form = $this->createForm(MemberType::class, $member);        
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            $band = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($member);
            $entityManager->flush();

            $this->addFlash('success', 'Membre modifié !');
            return $this->redirectToRoute('band_list');
        }

        return $this->render('member/newmember.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/member/delete/{id}", name="delete_member")
     * @isGranted("ROLE_ADMIN")
     */
    public function deleteMember(Request $request, Member $member): Response {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($member);
        $entityManager->flush();

        $this->addFlash('success', 'Membre supprimé !');
        return $this->redirectToRoute('band_list');
    }
}