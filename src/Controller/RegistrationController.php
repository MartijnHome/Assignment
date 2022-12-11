<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    private EmailVerifier $emailVerifier;
    private LoggerInterface $logger;

    public function __construct(EmailVerifier $emailVerifier, LoggerInterface $logger)
    {
        $this->emailVerifier = $emailVerifier;
        $this->logger = $logger;
    }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();

            try {
                $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                    (new TemplatedEmail())
                        ->from(new Address('noreply@blogsite.nl', 'BlogSite.nl'))
                        ->to($user->getEmail())
                        ->subject('Please Confirm your Email')
                        ->htmlTemplate('registration/confirmation_email.html.twig')
                );
            } catch (TransportExceptionInterface $e) {
                $this->logger->error("Verification email could not be sent", [
                    'message' => $e->getMessage(),
                    'user' => $user,
                ]);

                $entityManager->remove($user);
                $entityManager->flush();

                $this->addFlash(
                    'verify_email_error',
                    'Something went wrong sending a verification email. Please try again or contact us'
                );

                return $this->render('registration/register.html.twig', [
                    'registrationForm' => $form->createView(),
                ]);
            }
            return $this->redirectToRoute('app_sent_verification');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/register/verification', name: 'app_sent_verification')]
    public function verification(Request $request): Response
    {
        return $this->render('registration/verify.html.twig');
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, UserRepository $userRepository, TranslatorInterface $translator): Response
    {
        $user = $userRepository->find($request->query->get('id'));

        try {
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $e) {
            $this->logger->error("User could not be verified", [
                'message' => $e->getMessage(),
                'reason' => $e->getReason(),
                'user' => $user,
            ]);

            $this->addFlash('verify_email_error', $translator->trans($e->getReason(), [], 'VerifyEmailBundle'));
            return $this->redirectToRoute('app_register');
        }

        $this->addFlash('success', 'Your email address has been verified.');

        return $this->render('registration/verified.html.twig', [
            'name' => $user->getName(),
        ]);
    }
}
