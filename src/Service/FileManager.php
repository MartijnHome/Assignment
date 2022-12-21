<?php
namespace App\Service;

use App\Entity\Blog;
use App\Entity\Image;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileManager
{
    private SluggerInterface $slugger;
    private FileSystem $fileSystem;
    private String $blogimage_directory;
    private LoggerInterface $logger;

    public function __construct(SluggerInterface $slugger, Filesystem $fileSystem, LoggerInterface $logger, String $blogimage_directory)
    {
        $this->slugger = $slugger;
        $this->fileSystem = $fileSystem;
        $this->blogimage_directory = $blogimage_directory;
        $this->logger = $logger;
    }

    public function upload(UploadedFile $file): ?string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();
        try {
            $file->move($this->blogimage_directory, $fileName);
        } catch (IOException $e) {
            $this->logger->error("File could not be uploaded", [
                'message' => $e->getMessage(),
                'filename' => $file,
            ]);
        }
        return $fileName;
    }

    public function delete(String $file): void
    {
        try {
            $this->fileSystem->remove($this->blogimage_directory . "/" . $file);
        } catch (IOException $e) {
            $this->logger->error("File could not be deleted", [
                'message' => $e->getMessage(),
                'filename' => $file,
            ]);
        }
    }

    public function getBlogImageFilenames(Blog $blog, bool $ignoreLead = true): array
    {
        $fileNames = array();
        foreach($blog->getImages() as $image)
            if (!$ignoreLead || $image->getBlog() !== $blog)
            $fileNames[] = $image->getFilename();
        return $fileNames;
    }

}