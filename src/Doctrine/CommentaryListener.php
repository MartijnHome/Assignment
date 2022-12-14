<?php
namespace App\Doctrine;

use App\Entity\Commentary;
use DateTime;
use DateTimeZone;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Security;

class CommentaryListener
{
    protected Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;

    }
    public function prePersist(Commentary $commentary, LifecycleEventArgs $event): void
    {
        $commentary->setUser($this->security->getUser());
        $datetime = new DateTime('now');
        $datetime->setTimezone(new DateTimeZone('Europe/Amsterdam'));
        $commentary->setDate($datetime);
    }
    
}