<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\BlogRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Twig\Environment;

class AccountController extends AbstractController
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    #[Route('/login', name: 'app_login')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('login/index.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }

    #[Route('/logout', name: 'app_logout', methods: ['GET'])]
    #[IsGranted('IS_AUTHENTICATED')]
    public function logout()
    {
        throw new Exception('This will never throw');
    }

    #[Route('/account', name: 'app_account')]
    #[IsGranted('IS_AUTHENTICATED')]
    public function accountPage(BlogRepository $blogRepository): Response
    {
        $user = $this->security->getUser();

        return $this->render('blogsite/account.html.twig', [
            'name' => $user->getName(),
            'blogs' => $user->getBlogs(),
            'commentaries' => $user->getCommentaries(),
        ]);
    }

}
