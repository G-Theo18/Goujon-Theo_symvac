<?php

namespace App\Controller;

use App\Entity\Vacataire;
use App\Form\VacataireTypeForm;
use App\Repository\VacataireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class VacataireController extends AbstractController
{
    #[Route('/vacataire', name: 'app_vacataire')]
    public function index(
        VacataireRepository $repository,
        PaginatorInterface $paginator,
        Request $request
    ): Response {
        $vacataires = $paginator->paginate(
            $repository->findAll(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('pages/vacataire/index.html.twig', [
            'vacataires' => $vacataires,
        ]);
    }

    #[Route('/vacataire/nouveau', name: 'vacataire_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $vacataire = new Vacataire();
        $form = $this->createForm(VacataireTypeForm::class, $vacataire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($vacataire);
            $em->flush();

            $this->addFlash(
                'success',
                'Vos changements ont été enregistrés !'
            );

            return $this->redirectToRoute('app_vacataire');
        }

        return $this->render('pages/vacataire/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/vacataire/modifier/{id}', name: 'vacataire_edit', methods: ['GET', 'POST'])]
    public function edit(
        Vacataire $vacataire,
        Request $request,
        EntityManagerInterface $em
    ): Response {
        $form = $this->createForm(VacataireTypeForm::class, $vacataire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->flush();

            $this->addFlash(
                'success',
                'Modifier un vacataire'
            );

            return $this->redirectToRoute('app_vacataire');
        }

        return $this->render('pages/vacataire/edit.html.twig', [
            'form' => $form->createView(),
            'vacataire' => $vacataire,
        ]);
    }

    #[Route('/vacataire/supprimer/{id}', name: 'vacataire_delete', methods: ['POST'])]
    public function delete(
        Vacataire $vacataire,
        EntityManagerInterface $em
    ): Response {
        $em->remove($vacataire);
        $em->flush();

        $this->addFlash(
            'success',
            'Le vacataire a bien été supprimé.'
        );

        return $this->redirectToRoute('app_vacataire');
    }
}