<?php

namespace App\Entity\Post;

use App\Entity\Common\BaseEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Post\PostCategoryCategoryRepository")
 */
class PostCategoryCategory extends BaseEntity
{

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Post\Post", inversedBy="postCategoryCategories")
     */
    private $post;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Post\PostCategory", inversedBy="postCategoryCategories")
     */
    private $postCategory;

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(?Post $post): self
    {
        $this->post = $post;

        return $this;
    }

    public function getPostCategory(): ?PostCategory
    {
        return $this->postCategory;
    }

    public function setPostCategory(?PostCategory $postCategory): self
    {
        $this->postCategory = $postCategory;

        return $this;
    }
}
