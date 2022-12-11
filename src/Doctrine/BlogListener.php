<?php
namespace App\Doctrine;

use App\Entity\Blog;
use App\Service\MailManager;
use DateTime;
use DateTimeZone;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Security\Core\Security;

class BlogListener
{
    protected Security $security;
    protected MailManager $mailManager;
    protected LoggerInterface $logger;
    public function __construct(Security $security, MailManager $mailManager, LoggerInterface $logger)
    {
        $this->security = $security;
        $this->mailManager = $mailManager;
        $this->logger = $logger;
    }
    public function prePersist(Blog $blog, LifecycleEventArgs $event): void
    {
        // Publish Date
        $datetime = new DateTime('now');
        $datetime->setTimezone(new DateTimeZone('Europe/Amsterdam'));
        $blog->setPublishDate($datetime);

        // User
        $blog->setUser($this->security->getUser());
    }

    public function postPersist(Blog $blog, LifecycleEventArgs $event): void
    {
        // Email blog link to user
        try {
            $this->mailManager->blogPublished($blog);
        } catch (TransportExceptionInterface $e) {
            $this->logger->error("Blog published mail cannot be sent", [
                'message' => $e->getMessage(),
            ]);
        }
    }
}