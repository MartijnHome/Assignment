<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use App\Repository\BlogRepository;
use App\Repository\CommentaryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

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
}