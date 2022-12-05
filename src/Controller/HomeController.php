<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends AbstractController
{
    public function index(): Response
    {
        return $this->render('blogsite/home.html.twig');
    }

    public function about(): Response
    {
        return $this->render('blogsite/about.html.twig');
    }
}