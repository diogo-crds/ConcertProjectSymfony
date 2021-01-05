<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Concert;
use App\Form\ConcertType;

class ConcertController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index(): Response
    {
        $repository = $this->getDoctrine()->getRepository(Concert::class);
        $concerts = $repository->findNextConcert();

        return $this->render('index.html.twig', [
            'concerts' => $concerts,
        ]);
    }

    /**
     * Affiche une liste de concerts
     * 
     * @return Response
     * 
     * @Route("/concerts", name="list")
     */
    public function list(): Response
    {
        $repository = $this->getDoctrine()->getRepository(Concert::class);

        try{
            $results = $repository->findAll();
        }
        catch(\Exception $e)
        {
            dump($e); die;
        }

        return $this->render('concert/list.html.twig', [
            'concerts' => $results,
            ]
        );
    }


    /**
     * Créer un nouveau concert
     * 
     * @Route("/concert/create", name="concert_create", methods={"GET","POST"})
     * @isGranted("ROLE_ADMIN")
     */
    public function createConcert(Request $request): Response
    {
        $concert = new Concert();

        $form = $this->createForm(ConcertType::class, $concert);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $concert = $form->getData();

             $entityManager = $this->getDoctrine()->getManager();
             $entityManager->persist($concert);
             $entityManager->flush();

            $this->addFlash('success', 'Concert crée! Music is power!');
            return $this->redirectToRoute('list');
        }

        return $this->render('concert/new.html.twig', [
                'form' => $form->createView()
            ]
        );
    }


    /**
     * Modifier un concert
     * 
     * @Route("/concert/edit/{id}", name="concert_edit", methods={"GET","POST"})
     * @isGranted("ROLE_ADMIN")
     */
    public function editConcert(Request $request, Concert $concert): Response {
        
        $form = $this->createForm(ConcertType::class, $concert);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $concert = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($concert);
            $entityManager->flush();

            $this->addFlash('success', 'Concert update! Music is power!');
            return $this->redirectToRoute('list');
        }
        
        return $this->render('concert/new.html.twig', [
                'form' => $form->createView()
            ]
        );
    }



    /**
     * @param Concert $concert
     * 
     * @Route("/delete/{id}", name="concert_delete", methods={"GET","POST"})
     * @isGranted("ROLE_ADMIN")
     */
    public function deleteConcert(Request $request, Concert $concert): Response {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($concert);
        $entityManager->flush();

        return $this->redirectToRoute('list');
    }
}