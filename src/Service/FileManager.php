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

    public function __construct(SluggerInterface $slugger, Filesystem $fileSystem)
    {
        $this->slugger = $slugger;
        $this->fileSystem = $fileSystem;
    }

    public function upload(UploadedFile $file, String $directory): ?string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();
        $file->move($directory, $fileName);;

        return $fileName;
    }

    public function delete(String $file, String $directory): void
    {
        $this->fileSystem->remove($directory . "/" . $file);
    }
}