<?php

namespace App\Entity;

use App\Repository\BlogRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: BlogRepository::class)]
class Blog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['show_blog'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'blogs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[Assert\NotBlank]
    #[Assert\Length(min: 3)]
    #[ORM\Column(length: 255)]
    #[Groups(['show_blog'])]
    private ?string $title = null;

    #[Assert\NotBlank]
    #[Assert\Length(min: 3)]
    #[ORM\Column(length: 255)]
    #[Groups(['show_blog'])]
    private ?string $subtitle = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['show_blog'])]
    private ?\DateTimeInterface $publish_date = null;

    #[Assert\NotBlank]
    #[Assert\Length(min: 3)]
    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['show_blog'])]
    private ?string $text = null;

    #[ORM\OneToMany(mappedBy: 'blog', targetEntity: Commentary::class, orphanRemoval: true)]
    private Collection $commentaries;

    #[ORM\Column]
    private ?bool $archived = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['show_blog'])]
    private ?Image $lead = null;

    #[ORM\OneToMany(mappedBy: 'blog', targetEntity: Image::class)]
    #[Groups(['show_blog'])]
    private Collection $images;

    #[ORM\Column]
    private ?int $style = null;

    #[ORM\Column]
    private ?bool $gallery = null;


    public function __construct()
    {
        $this->commentaries = new ArrayCollection();
        $this->archived = false;
        $this->images = new ArrayCollection();
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

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSubtitle(): ?string
    {
        return $this->subtitle;
    }

    public function setSubtitle(string $subtitle): self
    {
        $this->subtitle = $subtitle;

        return $this;
    }

    public function getPublishDate(): ?DateTimeInterface
    {
        return $this->publish_date;
    }

    public function setPublishDate(DateTimeInterface $publish_date): self
    {
        $this->publish_date = $publish_date;

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

    /**
     * @return Collection<int, Commentary>
     */
    public function getCommentaries(): Collection
    {
        return $this->commentaries;
    }

    public function addCommentary(Commentary $commentary): self
    {
        if (!$this->commentaries->contains($commentary)) {
            $this->commentaries->add($commentary);
            $commentary->setBlog($this);
        }

        return $this;
    }

    public function removeCommentary(Commentary $commentary): self
    {
        if ($this->commentaries->removeElement($commentary)) {
            // set the owning side to null (unless already changed)
            if ($commentary->getBlog() === $this) {
                $commentary->setBlog(null);
            }
        }

        return $this;
    }

    public function __toString() {
        return $this->title;
    }

    public function isArchived(): ?bool
    {
        return $this->archived;
    }

    public function setArchived(bool $archived): self
    {
        $this->archived = $archived;

        return $this;
    }


    public function removeImage(Image $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getBlog() === $this) {
                $image->setBlog(null);
            }
        }

        return $this;
    }

    public function getLead(): ?Image
    {
        return $this->lead;
    }

    public function setLead(Image $lead): self
    {
        $this->lead = $lead;

        return $this;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setBlog($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Image>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function getStyle(): ?int
    {
        return $this->style;
    }

    public function setStyle(int $style): self
    {
        $this->style = $style;

        return $this;
    }

    public function isGallery(): ?bool
    {
        return $this->gallery;
    }

    public function setGallery(bool $gallery): self
    {
        $this->gallery = $gallery;

        return $this;
    }
}
