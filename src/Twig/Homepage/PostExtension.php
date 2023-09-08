<?php

namespace App\Twig\Homepage;

use App\Entity\Post\Comment;
use App\Entity\Post\Post;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class PostExtension extends AbstractExtension
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('get_featured_posts', [$this, 'getFeaturedPosts']),
            new TwigFunction('get_related_post', [$this, 'getRelatedPost']),
            new TwigFunction('get_post_comments', [$this, 'getPostComments']),
        ];
    }

    public function getFeaturedPosts() {
        return $this->entityManager->getRepository(Post::class)->findBy(['isInSlider' => true], [], 3);
    }

    public function getRelatedPost($postId, $operand){
        return $this->entityManager->getRepository(Post::class)
            ->getRelatedPostByOperand($postId, $operand);
    }

    public function getPostComments($postId){
        return $this->entityManager->getRepository(Comment::class)
            ->findBy(['post' => $postId, 'parentComment' => null]);
    }
}
