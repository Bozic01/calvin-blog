<?php

namespace App\Twig\Homepage;

use App\Entity\Post\PostCategory;
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
            new TwigFunction('get_post_categories', [$this, 'getPostCategories']),
        ];
    }

    public function getPostCategories() {
        return $this->entityManager->getRepository(PostCategory::class)->findAll();
    }
}
