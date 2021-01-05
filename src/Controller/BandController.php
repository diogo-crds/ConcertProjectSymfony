<?php

namespace App\Controller;

use App\Entity\Band;
use App\Form\BandType;
use App\Entity\Concert;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class BandController extends AbstractController {

    /**
     * Affiche une liste de groupe
     * 
     * @return Response
     * 
     * @Route("/bands", name="band_list")
     */
    public function bandsAll(): Response {

        $repository = $this->getDoctrine()->getRepository(Band::class);
        $bands = $repository->findAll();
        return $this->render('band/list.html.twig', [
            'bands' => $bands
        ]);
    }


    /**
     * Affiche une liste de groupe
     * 
     * 
     * @return Response
     * 
     * @Route("/band/{id}", name="band_show")
     */
    public function list(int $id): Response {

        $repository = $this->getDoctrine()->getRepository(Band::class);

        return $this->render('member/band.html.twig', [
            'band' => $repository->find($id)
        ]);

    }

    /**
     * Créer un nouveau groupe
     * 
     * @Route("/bands/create", name="create_band")
     * @isGranted("ROLE_ADMIN")
     */
    public function createBand(Request $request): Response {
        $band = new Band();
        $form = $this->createForm(BandType::class, $band);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $band = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($band);
            $entityManager->flush();

            $this->addFlash('success', 'Groupe ajouté avec succés !');
            return $this->redirectToRoute('band_list');
        }

        return $this->render('band/newband.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/bands/edit/{id}", name="edit_band")
     * @isGranted("ROLE_ADMIN")
     */
    public function editConcert(Request $request, Band $concert): Response {
        $form = $this->createForm(BandType::class, $concert);        
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            $band = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($concert);
            $entityManager->flush();

            $this->addFlash('success', 'Groupe modifié !');
            return $this->redirectToRoute('band_list');
        }

        return $this->render('band/newband.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/bands/delete/{id}", name="delete_band")
     * @isGranted("ROLE_ADMIN")
     */
    public function deleteConcert(Request $request, Band $concert): Response {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($concert);
        $entityManager->flush();

        $this->addFlash('success', 'Groupe supprimé !');
        return $this->redirectToRoute('band_list');
    }
}