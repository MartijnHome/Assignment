<?php

namespace App\Controller;

use App\Entity\Avatar;
use App\Form\AvatarType;
use App\Repository\AvatarRepository;
use App\Repository\BlogRepository;
use App\Service\FileManager;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;

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
            'error' => $error,
        ]);
    }

    #[Route('/logout', name: 'app_logout', methods: ['GET'])]
    #[IsGranted('IS_AUTHENTICATED')]
    public function logout()
    {
        throw new Exception('This will never throw');
    }

    #[Route('/account', name: 'app_account', methods: ['GET', 'POST'])]
    #[IsGranted('IS_AUTHENTICATED')]
    public function accountPage(Request $request, AvatarRepository $avatarRepository, FileManager $fileManager): Response
    {
        $user = $this->security->getUser();
        $avatar = new Avatar();
        $form = $this->createForm(AvatarType::class, $avatar);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $avatar->setUser($user);
            $avatar->setFilename($fileManager->upload($form->get('avatar')->getData(), 1));
            $avatarRepository->save($avatar, true);
        }

        return $this->render('blogsite/account.html.twig', [
            'name' => $user->getName(),
            'avatar' => $user->getAvatar(),
            'blogs' => $user->getBlogs(),
            'commentaries' => $user->getCommentaries(),
            'form' => $form->createView(),
        ]);
    }
}