<?php

namespace App\Controller;

use App\Entity\Avatar;
use App\Form\AvatarType;
use App\Form\UserType;
use App\Repository\AvatarRepository;
use App\Repository\BlogRepository;
use App\Repository\UserRepository;
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
    public function login(AuthenticationUtils $authenticationUtils): Response
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

        return $this->render('blogsite/account.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/account/profile', name: 'app_profile', methods: ['GET', 'POST'])]
    #[IsGranted('IS_AUTHENTICATED')]
    public function profilePage(Request $request, AvatarRepository $avatarRepository, UserRepository $userRepository, FileManager $fileManager): Response
    {
        $user = $this->security->getUser();

        $avatar = new Avatar();
        $avatarForm = $this->createForm(AvatarType::class, $avatar);
        $avatarForm->handleRequest($request);
        if ($avatarForm->isSubmitted() && $avatarForm->isValid()) {
            $avatar->setUser($user);
            $avatar->setFilename($fileManager->upload($avatarForm->get('avatar')->getData(), 1));
            $avatarRepository->save($avatar, true);
        }

        $userForm = $this->createForm(UserType::class, $user);
        $userForm->handleRequest($request);
        if ($userForm->isSubmitted() && $userForm->isValid()) {
           $userRepository->save($user, true);
        }

        return $this->render('blogsite/profile.html.twig', [
            'avatarForm' => $avatarForm->createView(),
            'userForm' => $userForm->createView(),
            'user' => $user,
        ]);
    }
}