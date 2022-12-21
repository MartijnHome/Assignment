<?php

namespace App\Entity;

use App\Repository\ImageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ImageRepository::class)]
class Image
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $filename = null;

    #[ORM\ManyToOne(inversedBy: 'images')]
    private ?Blog $blog = null;

    #[ORM\Column]
    private ?bool $isLead = null;


    /**
     * @param Blog $blog
     */
    public function __construct(Blog $blog, string $filename, bool $isLead = false)
    {
        $this->blog = $blog;
        $this->filename = $filename;
        $this->isLead = $isLead;
        if ($isLead)
            $blog->setLead($this);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(string $filename): self
    {
        $this->filename = $filename;

        return $this;
    }

    public function getBlog(): ?Blog
    {
        return $this->blog;
    }

    public function setBlog(?Blog $blog): self
    {
        $this->blog = $blog;

        return $this;
    }

    public function isIsLead(): ?bool
    {
        return $this->isLead;
    }

    public function setIsLead(bool $isLead): self
    {
        $this->isLead = $isLead;

        return $this;
    }
}
