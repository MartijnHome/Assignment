<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Entity\Commentary;
use App\Entity\User;
use App\Form\BlogType;
use App\Form\CommentaryType;
use App\Repository\BlogRepository;
use App\Repository\CommentaryRepository;
use App\Service\FileManager;
use App\Service\MailManager;
use DateTime;
use DateTimeZone;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
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
    private Security $security;

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
    public function new(Request $request, FileManager $fileManager, BlogRepository $blogRepository, MailManager $mailManager): Response
    {
        $blog = new Blog();
        $form = $this->createForm(BlogType::class, $blog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //Set remaining fields
            //Publish date
            $datetime = new DateTime('now');
            $datetime->setTimezone(new DateTimeZone('Europe/Amsterdam'));
            $blog->setPublishDate($datetime);

            //User
            $blog->setUser($this->security->getUser());

            //Upload main image
            $mainImage = $form->get('main_image')->getData();
            if ($mainImage) {
                $file = $fileManager->upload($mainImage, $this->getParameter('blogimage_directory'));
                $blog->setMainImage($file);
            }

            //Upload additional images
            $additionalImages = $form->get('additional_images')->getData();
            if ($additionalImages) {
                $files = array();
                foreach ($additionalImages as $image)
                {
                    $file = $fileManager->upload($image, $this->getParameter('blogimage_directory'));
                    $files[] = $file;
                }
                $blog->setAdditionalImages(implode(";", $files));
            }

            //Save and email user
            $blogRepository->save($blog, true);
            $mailManager->blogPublished($blog);

            return $this->redirectToRoute('app_blog_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('blog/new.html.twig', [
            'blog' => $blog,
            'form' => $form,
        ]);
    }




    #[Route('/{id}', name: 'app_blog_show', methods: ['GET', 'POST'])]
    public function show(Request $request, Blog $blog, CommentaryRepository $commentaryRepository): Response
    {
        $commentary = new Commentary();
        $form = $this->createForm(CommentaryType::class, $commentary);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->denyAccessUnlessGranted('ROLE_USER');
            $commentary->setBlog($blog);
            $commentary->setUser($this->security->getUser());
            $commentaryRepository->save($commentary, true);
            return $this->redirectToRoute('app_blog_show', ['id' => $blog->getId()]);
        }

        return $this->render('blog/show.html.twig', [
            'blog' => $blog,
            'commentaries' => $blog->getCommentaries(),
            'commentary_form' => $form->createView(),
            'images' => ($blog->getAdditionalImages() != null) ? explode(";", $blog->getAdditionalImages()) : null,
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

    #[Route('/{id}/delete', name: 'app_blog_delete', methods: ['POST'])]
    #[IsGranted('IS_AUTHENTICATED')]
    public function delete(Request $request, Blog $blog, FileManager $fileManager, BlogRepository $blogRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$blog->getId(), $request->request->get('_token'))) {
            //Clear up images
            $fileManager->delete($blog->getMainImage(), $this->getParameter('blogimage_directory'));
            if ($blog->getAdditionalImages())
                foreach(explode(";",$blog->getAdditionalImages()) as $file)
                    $fileManager->delete($file, $this->getParameter('blogimage_directory'));

            $blogRepository->remove($blog, true);
        }

        return $this->redirectToRoute('app_account', [], Response::HTTP_SEE_OTHER);
    }
}
