<?php
namespace App\Service;

use App\Entity\Blog;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;

class MailManager
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }


    /**
     * @throws TransportExceptionInterface
     */
    public function blogPublished(Blog $blog): void
    {
        $email = (new TemplatedEmail())
            ->from('noreply@blogsite.nl')
            ->to($blog->getUser()->getEmail())
            ->subject('Your blog has been published')
            ->text('Sending emails is fun again!')
            ->htmlTemplate('blog/new_blog_email.html.twig')
            ->context([
                'url' => 'http://localhost:8000/blog/' . $blog->getId(),
            ]);

        $this->mailer->send($email);
    }
}