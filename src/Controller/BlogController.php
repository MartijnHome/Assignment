<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Entity\Commentary;
use App\Entity\User;
use App\Form\BlogType;
use App\Form\CommentaryType;
use App\Repository\BlogRepository;
use App\Repository\CommentaryRepository;
use DateTime;
use DateTimeZone;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\String\Slugger\SluggerInterface;
use Twig\Environment;

#[Route('/blog')]
class BlogController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    
    #[Route('/', name: 'app_blog_index', methods: ['GET'])]
    public function index(BlogRepository $blogRepository): Response
    {
        return $this->render('blog/index.html.twig', [
            'blogs' => $blogRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_blog_new', methods: ['GET', 'POST'])]
    #[IsGranted('IS_AUTHENTICATED')]
    public function new(Request $request, BlogRepository $blogRepository, SluggerInterface $slugger, MailerInterface $mailer): Response
    {
        $blog = new Blog();
        $form = $this->createForm(BlogType::class, $blog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mainImage = $form->get('main_image')->getData();
            if ($mainImage) {
                $originalFilename = pathinfo($mainImage->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $mainImage->guessExtension();

                try {
                    $mainImage->move(
                        $this->getParameter('blogimage_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                $blog->setMainImage($newFilename);
            }

            $additionalImages = $form->get('additional_images')->getData();
            if ($additionalImages) {
                $originalFilename = pathinfo($additionalImages->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $additionalImages->guessExtension();

                try {
                    $additionalImages->move(
                        $this->getParameter('blogimage_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                $blog->setAdditionalImages($newFilename);
            }

            $datetime = new DateTime('now');
            $datetime->setTimezone(new DateTimeZone('Europe/Amsterdam'));
            $blog->setPublishDate($datetime);

            $blog->setUser($this->security->getUser());
            $blogRepository->save($blog, true);

            $email = (new TemplatedEmail())
                ->from('noreply@blogsite.nl')
                ->to($this->security->getUser()->getEmail())
                ->subject('Your blog has been published')
                ->text('Sending emails is fun again!')
                ->htmlTemplate('blog/new_blog_email.html.twig')
                ->context([
                    'url' => 'http://localhost:8000/blog/' . $blog->getId(),
                ]);
            try {
                $mailer->send($email);
            } catch (TransportExceptionInterface $e) {

            }

            return $this->redirectToRoute('app_blog_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('blog/new.html.twig', [
            'blog' => $blog,
            'form' => $form,
        ]);
    }


    #[Route('/{id}', name: 'app_blog_show', methods: ['GET'])]
    public function show(Request $request, Blog $blog, CommentaryRepository $commentaryRepository): Response
    {
        $commentary = new Commentary();
        $commentary->setBlog($blog);
        $commentary->setUser($this->security->getUser());

        $form = $this->createForm(CommentaryType::class, $commentary);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //$this->entityManager->persist($commentary);
            //$this->entityManager->flush();
            $commentaryRepository->save($commentary, true);
            return $this->redirectToRoute('app_blog_show', ['id' => $blog->getId()]);
        }

        return $this->render('blog/show.html.twig', [
            'blog' => $blog,
            'commentaries' => $blog->getCommentaries(),
            'commentary_form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_blog_edit', methods: ['GET', 'POST'])]
    #[IsGranted('IS_AUTHENTICATED')]
    public function edit(Request $request, Blog $blog, BlogRepository $blogRepository): Response
    {
        $form = $this->createForm(BlogType::class, $blog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $blogRepository->save($blog, true);

            return $this->redirectToRoute('app_blog_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('blog/edit.html.twig', [
            'blog' => $blog,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_blog_delete', methods: ['POST'])]
    #[IsGranted('IS_AUTHENTICATED')]
    public function delete(Request $request, Blog $blog, BlogRepository $blogRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$blog->getId(), $request->request->get('_token'))) {
            $blogRepository->remove($blog, true);
        }

        return $this->redirectToRoute('app_account', [], Response::HTTP_SEE_OTHER);
    }
}
