<?php

namespace App\Twig;

use App\Entity\Post\PostCategoryCategory;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class PostCategoryExtension extends AbstractExtension
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
            new TwigFunction('get_post_count_per_category', [$this, 'getPostCountPerCategory']),
        ];
    }

    public function getPostCountPerCategory($postCategoryId) {
        return $this->entityManager->getRepository(PostCategoryCategory::class)->count(['postCategory' => $postCategoryId]);
    }
}
