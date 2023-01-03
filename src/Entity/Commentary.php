<?php

namespace App\Entity;

use App\Repository\CommentaryRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CommentaryRepository::class)]
class Commentary
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['show_commentary'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'commentaries')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['show_commentary'])]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'commentaries')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Blog $blog = null;

    #[Assert\NotBlank]
    #[Assert\Length(min: 3)]
    #[ORM\Column(length: 255)]
    #[Groups(['show_commentary'])]
    private ?string $text = null;

    /**
     * @param Blog|null $blog
     */
    public function __construct(?Blog $blog = null)
    {
        $this->blog = $blog;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

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

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }
}
