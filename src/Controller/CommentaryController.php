<?php

namespace App\Controller;

use App\Entity\Commentary;
use App\Form\CommentaryType;
use App\Repository\BlogRepository;
use App\Repository\CommentaryRepository;
use App\Service\FileManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

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

    #[Route('/blog/{blogId}', name: 'api_commentary_blog', methods: ['GET'])]
    public function showFromBlog(BlogRepository $blogRepository, String $blogId, FileManager $fileManager): Response
    {
        return $this->json(['commentaries' => $blogRepository->find($blogId)->getCommentaries(), 'avatarDirectory' => 'uploads/avatar/image'],
            Response::HTTP_OK, [], [
                AbstractNormalizer::GROUPS => ['show_commentary']
            ]);
    }

    #[Route('/{id}/edit', name: 'api_commentary_edit', methods: ['POST'])]
    #[IsGranted('IS_AUTHENTICATED')]
    public function edit(Request $request, Commentary $commentary, CommentaryRepository $commentaryRepository): Response
    {
        $blog = $commentary->getBlog();
        if ($this->security->getUser() !== $commentary->getUser()
            || !$this->isCsrfTokenValid('delete-commentary-blog-' . $blog->getId(), json_decode($request->getContent(), true)['token']))
            return new Response('Operation not allowed', Response::HTTP_BAD_REQUEST,
                ['content-type' => 'text/plain']);

        $commentary->setText(json_decode($request->getContent(), true)['commentary-text']);
        $commentaryRepository->save($commentary, true);
        return $this->json([
            'message' => 'Comment edited',
        ]);
    }

    #[Route('/{id}', name: 'api_commentary_delete', methods: ['POST'])]
    #[IsGranted('IS_AUTHENTICATED')]
    public function delete(Request $request, Commentary $commentary, CommentaryRepository $commentaryRepository): Response
    {
        $blog = $commentary->getBlog();
        if ($this->security->getUser() !== $commentary->getUser()
            || !$this->isCsrfTokenValid('delete-commentary-blog-' . $blog->getId(), json_decode($request->getContent(), true)['token']))
            return new Response('Operation not allowed', Response::HTTP_BAD_REQUEST,
                ['content-type' => 'text/plain']);

        $commentaryRepository->remove($commentary, true);

        return $this->json([
            'message' => 'Comment deleted',
        ]);
    }
}
