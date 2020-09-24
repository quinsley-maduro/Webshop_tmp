<?php

namespace App\Controller;

use App\Entity\MEMO;
use App\Form\MEMOType;
use App\Repository\MEMORepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/m/e/m/o")
 */
class MEMOController extends AbstractController
{
    /**
     * @Route("/", name="m_e_m_o_index", methods={"GET"})
     */
    public function index(MEMORepository $mEMORepository): Response
    {
        return $this->render('memo/index.html.twig', [
            'm_e_m_os' => $mEMORepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="m_e_m_o_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $mEMO = new MEMO();
        $form = $this->createForm(MEMOType::class, $mEMO);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($mEMO);
            $entityManager->flush();

            return $this->redirectToRoute('m_e_m_o_index');
        }

        return $this->render('memo/new.html.twig', [
            'm_e_m_o' => $mEMO,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="m_e_m_o_show", methods={"GET"})
     */
    public function show(MEMO $mEMO): Response
    {
        return $this->render('memo/show.html.twig', [
            'm_e_m_o' => $mEMO,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="m_e_m_o_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, MEMO $mEMO): Response
    {
        $form = $this->createForm(MEMOType::class, $mEMO);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('m_e_m_o_index');
        }

        return $this->render('memo/edit.html.twig', [
            'm_e_m_o' => $mEMO,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="m_e_m_o_delete", methods={"DELETE"})
     */
    public function delete(Request $request, MEMO $mEMO): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mEMO->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($mEMO);
            $entityManager->flush();
        }

        return $this->redirectToRoute('m_e_m_o_index');
    }
}
