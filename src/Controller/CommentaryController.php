<?php

namespace App\Controller;

use App\Entity\Commentary;
use App\Form\CommentaryType;
use App\Repository\CommentaryRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

#[Route('/commentary')]
class CommentaryController extends AbstractController
{
    protected Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    #[Route('/{id}/edit', name: 'app_commentary_edit', methods: ['GET', 'POST'])]
    #[IsGranted('IS_AUTHENTICATED')]
    public function edit(Request $request, Commentary $commentary, CommentaryRepository $commentaryRepository): Response
    {
        if ($this->security->getUser() !== $commentary->getUser())
            return new Response('Operation not allowed', Response::HTTP_BAD_REQUEST,
                ['content-type' => 'text/plain']);

        $form = $this->createForm(CommentaryType::class, $commentary);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commentaryRepository->save($commentary, true);
            return $this->redirectToRoute('app_blog_show', ['id' => $commentary->getBlog()->getId()]);
        }

        $route = $request->headers->get('referer');
        return $this->renderForm('commentary/edit.html.twig', [
            'commentary' => $commentary,
            'form' => $form,
            'route' => $route,
        ]);
    }

    #[Route('/{id}', name: 'app_commentary_delete', methods: ['POST'])]
    #[IsGranted('IS_AUTHENTICATED')]
    public function delete(Request $request, Commentary $commentary, CommentaryRepository $commentaryRepository): Response
    {
        if ($this->security->getUser() !== $commentary->getUser()
            || !$this->isCsrfTokenValid('delete-item', $request->request->get('token')))
            return new Response('Operation not allowed', Response::HTTP_BAD_REQUEST,
                ['content-type' => 'text/plain']);

        $commentaryRepository->remove($commentary, true);
        $route = $request->headers->get('referer');
        return $this->redirect($route);
    }
}
