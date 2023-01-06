<?php

namespace App\Controller;

use App\Entity\Blogtag;
use App\Repository\BlogRepository;
use App\Repository\BlogtagRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

#[Route('/blogtag')]
class BlogtagController extends AbstractController
{
    protected Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    #[Route('/', name: 'api_blogtag_index', methods: ['GET'])]
    #[IsGranted('IS_AUTHENTICATED')]
    public function index(BlogtagRepository $blogtagRepository): Response
    {
        $tags = [];
        foreach($blogtagRepository->findAll() as $tag)
            $tags[] = [$tag->getId(), $tag->getName()];

        return $this->json([
            'tags' => $tags,
        ]);
    }

    #[Route('/new/{blogId}', name: 'api_blogtag_new', methods: ['POST'])]
    #[IsGranted('IS_AUTHENTICATED')]
    public function new(Request $request, BlogRepository $blogRepository, BlogtagRepository $blogtagRepository, int $blogId): Response
    {
        $blog = $blogRepository->find($blogId);
        if ($this->security->getUser() !== $blog->getUser())
            return new Response('Operation not allowed', Response::HTTP_BAD_REQUEST,
                ['content-type' => 'text/plain']);

        $name = strtoupper(json_decode($request->getContent(), true)['blogtag-name']);
        $blogtag = $blogtagRepository->findOneBy(array('name' => $name));
        if ($blogtag)
            return $this->forward('App\Controller\BlogtagController::addTagToBlog', [
                'blogtagId' => $blogtag->getId(),
                'blogId' => $blogId,
            ]);

        $blogtag = new BlogTag();
        $blogtag->setName($name);
        $blogtag->addBlog($blog);
        $blogtagRepository->save($blogtag, true);

        return $this->json([
            'message' => 'Blogtag created',
            'tag' => [$blogtag->getId(), $blogtag->getName()]
        ]);
    }

    #[Route('/set/{blogtagId}/{blogId}', name: 'api_blogtag_set', methods: ['POST'])]
    #[IsGranted('IS_AUTHENTICATED')]
    public function addTagToBlog(BlogRepository $blogRepository, BlogtagRepository $blogtagRepository, int $blogtagId, int $blogId): Response
    {
        $blog = $blogRepository->find($blogId);
        if ($this->security->getUser() !== $blog->getUser())
            return new Response('Operation not allowed', Response::HTTP_BAD_REQUEST,
                ['content-type' => 'text/plain']);

        $blogtag = $blogtagRepository->find($blogtagId);
        if ($blogtag->getBlogs()->contains($blog))
            return new Response('Duplicate tag', Response::HTTP_BAD_REQUEST,
                ['content-type' => 'text/plain']);

        $blogtag->addBlog($blog);
        $blogtagRepository->save($blogtag, true);
        return $this->json([
            'message' => 'Blogtag added',
            'tag' => [$blogtag->getId(), $blogtag->getName()],
        ]);
    }

    #[Route('/unset/{blogtagId}/{blogId}', name: 'api_blogtag_unset', methods: ['POST'])]
    #[IsGranted('IS_AUTHENTICATED')]
    public function removeTagFromBlog(BlogRepository $blogRepository, BlogtagRepository $blogtagRepository, int $blogtagId, int $blogId): Response
    {
        $blog = $blogRepository->find($blogId);
        if ($this->security->getUser() !== $blog->getUser())
            return new Response('Operation not allowed', Response::HTTP_BAD_REQUEST,
                ['content-type' => 'text/plain']);

        $blogtag = $blogtagRepository->find($blogtagId);
        $blogtag->removeBlog($blogRepository->find($blogId));
        $blogtagRepository->save($blogtag, true);
        return $this->json([
            'message' => 'Blogtag removed',
        ]);
    }
}
