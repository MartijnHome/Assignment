<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Entity\Commentary;
use App\Entity\Image;
use App\Form\BlogType;
use App\Form\CommentaryType;
use App\Repository\BlogRepository;
use App\Repository\CommentaryRepository;
use App\Repository\ImageRepository;
use App\Service\FileManager;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

#[Route('/blog')]
class BlogController extends AbstractController
{
    protected Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    #[Route('/page/{page<[1-9]\d*>}', name: 'app_blog_paginated', defaults: ['_format' => 'html'], methods: ['GET'])]
    public function paginated(BlogRepository $blogRepository, int $page): Response
    {
        return $this->render('blog/index.html.twig', [
            'paginator' => $blogRepository->findLatest($page),
        ]);
    }

    #[Route('/new', name: 'app_blog_new', methods: ['GET', 'POST'])]
    #[IsGranted('IS_AUTHENTICATED')]
    public function new(Request $request, FileManager $fileManager, BlogRepository $blogRepository, ImageRepository $imageRepository): Response
    {
        $blog = new Blog();
        $form = $this->createForm(BlogType::class, $blog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $leadImage = $form->get('main_image')->getData();
            $imageRepository->save(new Image($blog, $fileManager->upload($leadImage), true));

            foreach ($form->get('additional_images')->getData() as $image)
                $imageRepository->save(new Image($blog, $fileManager->upload($image)));

            $blogRepository->save($blog, true);
            return $this->redirectToRoute('app_account', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('blog/new.html.twig', [
            'blog' => $blog,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_blog_show', methods: ['GET', 'POST'])]
    public function show(Request $request, Blog $blog, FileManager $fileManager, CommentaryRepository $commentaryRepository): Response
    {
        $commentary = new Commentary($blog);
        $form = $this->createForm(CommentaryType::class, $commentary);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->denyAccessUnlessGranted('ROLE_USER');
            $commentaryRepository->save($commentary, true);
            return $this->redirectToRoute('app_blog_show', ['id' => $blog->getId()]);
        }

        return $this->render('blog/show.html.twig', [
            'blog' => $blog,
            'commentaries' => $blog->getCommentaries(),
            'commentary_form' => $form->createView(),
            'images' => $blog->getImages(),
            'route' => $request->headers->get('referer'),
        ]);
    }


    #[Route('/{id}/edit', name: 'app_blog_edit', methods: ['GET', 'POST'])]
    #[IsGranted('IS_AUTHENTICATED')]
    public function edit(Request $request, Blog $blog, BlogRepository $blogRepository, FileManager $fileManager, ImageRepository $imageRepository): Response
    {
        if ($this->security->getUser() !== $blog->getUser())
            return new Response('Operation not allowed', Response::HTTP_BAD_REQUEST,
                ['content-type' => 'text/plain']);

        $form = $this->createForm(BlogType::class, $blog, [
            'main_image_required' => false,
            'main_image_label' => "Optional - Replace main image",
            'additional_images_label' => "Optional - Add additional images"
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $leadImage = $form->get('main_image')->getData();
            if ($leadImage) {
                $oldLead = $blog->getLead();
                $imageRepository->save(new Image($blog, $fileManager->upload($leadImage), true));
                $imageRepository->remove($oldLead, true);
            }

            foreach ($form->get('additional_images')->getData() as $image)
                $imageRepository->save(new Image($blog, $fileManager->upload($image)));

            $blogRepository->save($blog, true);
            return $this->redirectToRoute('app_account', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('blog/edit.html.twig', [
            'blog' => $blog,
            'images' => $blog->getImages(),
            'form' => $form,
        ]);
    }

    #[Route('/{id}/archive', name: 'app_blog_archive', methods: ['GET', 'POST'])]
    #[IsGranted('IS_AUTHENTICATED')]
    public function archive(Request $request, Blog $blog, BlogRepository $blogRepository): Response
    {
        if ($this->security->getUser() !== $blog->getUser())
            return new Response('Operation not allowed', Response::HTTP_BAD_REQUEST,
                ['content-type' => 'text/plain']);

        $blog->setArchived(!$blog->isArchived());
        $blogRepository->save($blog, true);
        $route = $request->headers->get('referer');
        return $this->redirect($route);
    }

    #[Route('/{id}/delete', name: 'app_blog_delete', methods: ['POST'])]
    #[IsGranted('IS_AUTHENTICATED')]
    public function delete(Request $request, Blog $blog, BlogRepository $blogRepository, ManagerRegistry $doctrine): Response
    {
        if ($this->security->getUser() !== $blog->getUser()
            || !$this->isCsrfTokenValid('delete-item', $request->request->get('token')))
            return new Response('Operation not allowed', Response::HTTP_BAD_REQUEST,
                ['content-type' => 'text/plain']);

        $blogRepository->remove($blog, true);
        return $this->redirectToRoute('app_account', [], Response::HTTP_SEE_OTHER);
    }
}
