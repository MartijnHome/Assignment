<?php

namespace App\Controller;

use App\Entity\Blogtag;
use App\Entity\Commentary;
use App\Repository\BlogRepository;
use App\Repository\BlogtagRepository;
use App\Repository\CommentaryRepository;
use App\Service\FileManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

#[Route('/blogtag')]
class BlogtagController extends AbstractController
{
    protected Security $security;
    protected Serializer $serializer;

    public function __construct(Security $security)
    {
        $this->security = $security;
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $this->serializer = new Serializer($normalizers, $encoders);
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

        /*
        return $this->json([
            'tags' => $blogtagRepository->findAll(),
            Response::HTTP_OK, [], [
                AbstractNormalizer::GROUPS => ['show_blogtag']
            ]
        ]);*/
    }

    #[Route('/new/{blogId}', name: 'api_blogtag_new', methods: ['POST'])]
    #[IsGranted('IS_AUTHENTICATED')]
    public function new(Request $request, BlogRepository $blogRepository, BlogtagRepository $blogtagRepository, int $blogId): Response
    {
        $blogtag = new BlogTag();
        $blogtag->setName(strtoupper(json_decode($request->getContent(), true)['blogtag-name']));
        $blogtag->addBlog($blogRepository->find($blogId));
        $blogtagRepository->save($blogtag, true);
        return $this->json([
            'message' => 'Blogtag created',
        ]);
    }

    #[Route('/set/{blogtagId}/{blogId}', name: 'api_blogtag_set', methods: ['POST'])]
    #[IsGranted('IS_AUTHENTICATED')]
    public function addTagtoBlog(Request $request, BlogRepository $blogRepository, BlogtagRepository $blogtagRepository, int $blogtagId, int $blogId): Response
    {
        $blogtag = $blogtagRepository->find($blogtagId);
        $blogtag->addBlog($blogRepository->find($blogId));
        $blogtagRepository->save($blogtag, true);
        return $this->json([
            'message' => 'Blogtag added',
        ]);
    }
}
