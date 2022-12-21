<?php
namespace App\Doctrine;

use App\Entity\Commentary;
use App\Entity\Image;
use App\Service\FileManager;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Security;

class ImageListener
{
    protected FileManager $fileManager;

    public function __construct(FileManager $fileManager)
    {
        $this->fileManager = $fileManager;
    }

    public function preRemove(Image $image): void
    {
        $image->setBlog(null);
        $this->fileManager->delete($image->getFilename());
    }
}