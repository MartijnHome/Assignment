<?php

namespace App\Controller;

use App\Entity\Commentary;
use App\Form\CommentaryType;
use App\Repository\BlogRepository;
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


    #[Route('/new/{blogId}', name: 'app_commentary_new', methods: ['POST'])]
    #[IsGranted('IS_AUTHENTICATED')]
    public function new(Request $request, BlogRepository $blogRepository, CommentaryRepository $commentaryRepository, String $blogId): Response
    {
        $commentary = new Commentary($blogRepository->find($blogId));

        $form = $this->createForm(CommentaryType::class, $commentary)->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
            $commentaryRepository->save($commentary, true);

        return $this->redirect($request->headers->get('referer'));
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

        return $this->renderForm('commentary/edit.html.twig', [
            'commentary' => $commentary,
            'form' => $form,
            'route' => $request->headers->get('referer'),
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
        return $this->redirect($request->headers->get('referer'));
    }
}
