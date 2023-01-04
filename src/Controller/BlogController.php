<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Entity\Blogtag;
use App\Entity\Commentary;
use App\Entity\Image;
use App\Form\BlogType;
use App\Form\CommentaryType;
use App\Repository\BlogRepository;
use App\Repository\BlogtagRepository;
use App\Repository\ImageRepository;
use App\Service\FileManager;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

#[Route('/blog')]
class BlogController extends AbstractController
{
    protected Security $security;
    protected Serializer $serializer;

    public function __construct(Security $security)
    {
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $this->serializer = new Serializer($normalizers, $encoders);
        $this->security = $security;
    }

    #[Route('/page/{page<[1-9]\d*>}', name: 'app_blog_paginated', defaults: ['_format' => 'html'], methods: ['GET'])]
    public function index(BlogRepository $blogRepository, int $page): Response
    {
        return $this->render('blog/index.html.twig', [
            'paginator' => $blogRepository->findLatest($page),
        ]);
    }

    #[Route('/user/{userId}/page/{page<[1-9]\d*>}', name: 'app_blog_user_paginated', defaults: ['_format' => 'html'], methods: ['GET'])]
    public function indexByUser(BlogRepository $blogRepository, int $page, int $userId): Response
    {
        return $this->render('blog/index.html.twig', [
            'paginator' => $blogRepository->findLatestByUser($userId, $page),
        ]);
    }

    #[Route('/blogtag/{blogtagId}/page/{page<[1-9]\d*>}', name: 'app_blog_tag_paginated', defaults: ['_format' => 'html'], methods: ['GET'])]
    public function indexByTag(BlogRepository $blogRepository, int $page, int $blogtagId): Response
    {

        return $this->render('blog/index.html.twig', [
            'paginator' => $blogRepository->findLatestByTag($blogtagId, $page),
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

    #[Route('/{id}', name: 'app_blog_show', methods: ['GET'])]
    public function show(Request $request, Blog $blog): Response
    {
        $json = $this->serializer->serialize($blog, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            },
            AbstractNormalizer::IGNORED_ATTRIBUTES =>
                ['password', 'blogs', 'commentaries', 'roles', 'email', 'userIdentifier', 'location'],
        ]);

        return $this->render('blog/show.html.twig', [
            'blog' => $blog,
            'route' => $request->headers->get('referer'),
            'json' => $json,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_blog_edit', methods: ['GET', 'POST'])]
    #[IsGranted('IS_AUTHENTICATED')]
    public function edit(Request $request, Blog $blog, BlogRepository $blogRepository, FileManager $fileManager, ImageRepository $imageRepository): Response
    {
        if ($this->security->getUser() !== $blog->getUser())
            throw new AccessDeniedException();

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
        }

        return $this->renderForm('blog/edit.html.twig', [
            'blog' => $blog,
            'images' => $blogRepository->getImageFiles($blog),
            'form' => $form,
            'json' => $this->json($blog, Response::HTTP_OK, [], [
                AbstractNormalizer::GROUPS => ['show_blog']
            ])->getContent()
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

        return $this->redirect($request->headers->get('referer'));
    }

    #[Route('/{id}/delete', name: 'app_blog_delete', methods: ['POST'])]
    #[IsGranted('IS_AUTHENTICATED')]
    public function delete(Request $request, Blog $blog, BlogRepository $blogRepository, ManagerRegistry $doctrine): Response
    {
        if ($this->security->getUser() !== $blog->getUser()
            || !$this->isCsrfTokenValid('delete-blog-' . $blog->getId(), $request->request->get('token')))
            return new Response('Operation not allowed', Response::HTTP_BAD_REQUEST,
                ['content-type' => 'text/plain']);

        $blogRepository->remove($blog, true);

        return $this->redirectToRoute('app_account', [], Response::HTTP_SEE_OTHER);
    }
}
