<?php

namespace App\Entity\Post;

use App\Entity\Common\BaseEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Post\PostCategoryRepository")
 */
class PostCategory extends BaseEntity
{

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $title;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Post\PostCategoryCategory", mappedBy="postCategory")
     */
    private $postCategoryCategories;

    public function __construct()
    {
        $this->postCategoryCategories = new ArrayCollection();
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

    /**
     * @return Collection|PostCategoryCategory[]
     */
    public function getPostCategoryCategories(): Collection
    {
        return $this->postCategoryCategories;
    }
}
