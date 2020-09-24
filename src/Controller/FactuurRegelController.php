<?php

namespace App\Controller;

use App\Entity\FactuurRegel;
use App\Form\FactuurRegelType;
use App\Repository\FactuurRegelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/factuur/regel")
 */
class FactuurRegelController extends AbstractController
{
    /**
     * @Route("/", name="factuur_regel_index", methods={"GET"})
     */
    public function index(FactuurRegelRepository $factuurRegelRepository): Response
    {
        return $this->render('factuur_regel/index.html.twig', [
            'factuur_regels' => $factuurRegelRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="factuur_regel_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $factuurRegel = new FactuurRegel();
        $form = $this->createForm(FactuurRegelType::class, $factuurRegel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($factuurRegel);
            $entityManager->flush();

            return $this->redirectToRoute('factuur_regel_index');
        }

        return $this->render('factuur_regel/new.html.twig', [
            'factuur_regel' => $factuurRegel,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="factuur_regel_show", methods={"GET"})
     */
    public function show(FactuurRegel $factuurRegel): Response
    {
        return $this->render('factuur_regel/show.html.twig', [
            'factuur_regel' => $factuurRegel,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="factuur_regel_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, FactuurRegel $factuurRegel): Response
    {
        $form = $this->createForm(FactuurRegelType::class, $factuurRegel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('factuur_regel_index');
        }

        return $this->render('factuur_regel/edit.html.twig', [
            'factuur_regel' => $factuurRegel,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="factuur_regel_delete", methods={"DELETE"})
     */
    public function delete(Request $request, FactuurRegel $factuurRegel): Response
    {
        if ($this->isCsrfTokenValid('delete'.$factuurRegel->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($factuurRegel);
            $entityManager->flush();
        }

        return $this->redirectToRoute('factuur_regel_index');
    }
}
