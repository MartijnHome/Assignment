<?php
namespace App\Controller;

use App\Repository\BlogRepository;
use App\Repository\CommentaryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    public function index(BlogRepository $blogRepository, CommentaryRepository $commentaryRepository): Response
    {
        return $this->render('blogsite/home.html.twig', [
            'latest_five' => $blogRepository->getLatestFive(),
            'commentaries' => $commentaryRepository->getLatestFive(),
            'top_commented' => $blogRepository->getFiveMostCommented(),
        ]);
    }

    public function about(): Response
    {
        return $this->render('blogsite/about.html.twig');
    }

    #[Route('/api', name: 'api_home', methods: ['GET'])]
    public function api(): Response
    {
        return $this->json([
            'message' => 'Hello World!',
        ]);
    }
}