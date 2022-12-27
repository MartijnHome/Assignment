<?php
namespace App\Service;

use App\Entity\Blog;
use App\Entity\Image;
use Exception;
use phpDocumentor\Reflection\Types\Integer;
use Psr\Log\LoggerInterface;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileManager
{
    private SluggerInterface $slugger;
    private FileSystem $fileSystem;
    private String $blogDirectory;
    private String $avatarDirectory;
    private LoggerInterface $logger;

    public function __construct(SluggerInterface $slugger, Filesystem $fileSystem, LoggerInterface $logger, String $blogDirectory, String $avatarDirectory)
    {
        $this->slugger = $slugger;
        $this->fileSystem = $fileSystem;
        $this->blogDirectory = $blogDirectory;
        $this->logger = $logger;
        $this->avatarDirectory = $avatarDirectory;
    }

    public function upload(UploadedFile $file, int $folder = 0): ?string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();
        try {
            $directory = ($folder == 0) ? $this->blogDirectory : $this->avatarDirectory;
            $file->move($directory, $fileName);
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
            $this->fileSystem->remove($this->blogDirectory . "/" . $file);
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