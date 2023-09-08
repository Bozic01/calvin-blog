<?php

namespace App\Entity\Post;

use App\Entity\Common\BaseEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Post\PostTagRepository")
 */
class PostTag extends BaseEntity
{

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @ORM\ManyToOne (targetEntity="App\Entity\Post\Post", inversedBy="postTags")
     */
    private $post;


    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * @param mixed $post
     */
    public function setPost($post): void
    {
        $this->post = $post;
    }

}
