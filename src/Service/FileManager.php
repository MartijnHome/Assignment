<?php
namespace App\Service;

use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileManager
{
    private SluggerInterface $slugger;
    private FileSystem $fileSystem;
    private String $blogimage_directory;

    public function __construct(SluggerInterface $slugger, Filesystem $fileSystem, String $blogimage_directory)
    {
        $this->slugger = $slugger;
        $this->fileSystem = $fileSystem;
        $this->blogimage_directory = $blogimage_directory;
    }

    public function upload(UploadedFile $file): ?string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();
        $file->move($this->blogimage_directory, $fileName);;

        return $fileName;
    }

    public function delete(String $file): void
    {
        $this->fileSystem->remove($this->blogimage_directory . "/" . $file);
    }

}