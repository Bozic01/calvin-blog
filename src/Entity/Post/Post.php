<?php

namespace App\Entity\Post;

use App\Entity\Common\BaseEntity;
use App\Entity\User\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Post\PostRepository")
 * @Vich\Uploadable()
 */
class Post extends BaseEntity
{
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="text")
     */
    private $title;

    /**
     * @ORM\Column(type="text" , nullable=true)
     */
    private $content;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $featuredImage;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $status;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Post\Comment", mappedBy="post")
     */
    private $comments;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User\User", inversedBy="posts")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Post\PostTag", mappedBy="post")
     */
    private $postTags;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Post\PostCategoryCategory", mappedBy="post")
     */
    private $postCategoryCategories;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $numberOfViews = 0;

    /**
     * @Vich\UploadableField (mapping="posts", fileNameProperty="featuredImage")
     */
    private $imageFile;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isInSlider = false;


    public function __construct()
    {
        $this->postCategoryCategories = new ArrayCollection();
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getFeaturedImage(): ?string
    {
        return $this->featuredImage;
    }

    public function setFeaturedImage(?string $featuredImage): self
    {
        $this->featuredImage = $featuredImage;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
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
    /**
     * @return Collection|PostCategoryCategory[]
     */
    public function getPostCategoryCategories(): Collection
    {
        return $this->postCategoryCategories;
    }

    /**
     * @return mixed
     */

    public function getPostTags()
    {
        return $this->postTags;
    }

    /**
     * @param mixed $postTags
     */
    public function setPostTags($postTags): void
    {
        $this->postTags = $postTags;
    }

    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @param mixed $comments
     */
    public function setComments($comments): void
    {
        $this->comments = $comments;
    }

    /**
     * @return mixed
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * @param mixed $imageFile
     */
    public function setImageFile($imageFile): void
    {
        $this->imageFile = $imageFile;
    }

    public function getNumberOfViews(): ?int
    {
        return $this->numberOfViews;
    }

    public function setNumberOfViews(?int $numberOfViews): self
    {
        $this->numberOfViews = $numberOfViews;

        return $this;
    }

    public function getIsInSlider(): ?bool
    {
        return $this->isInSlider;
    }

    public function setIsInSlider(?bool $isInSlider): self
    {
        $this->isInSlider = $isInSlider;

        return $this;
    }

}
