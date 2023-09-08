<?php

namespace App\Repository\Post;

use App\Entity\Post\PostCategoryCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PostCategoryCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method PostCategoryCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method PostCategoryCategory[]    findAll()
 * @method PostCategoryCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostCategoryCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PostCategoryCategory::class);
    }

}
