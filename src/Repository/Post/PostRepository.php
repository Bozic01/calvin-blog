<?php

namespace App\Repository\Post;

use App\Entity\Post\Post;
use App\Enum\Post\PostStatusEnum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    // HOMEPAGE
    public function findByCustomParams($params)
    {
        $qb = $this->createQueryBuilder('post');

        if (isset($params['categoryId'])) {
            $qb->leftJoin('post.postCategoryCategories', 'postCategoryCategories')
                ->andWhere('postCategoryCategories.postCategory = :categoryId')
                ->setParameter('categoryId', $params['categoryId']);
        }

        if (isset($params['tag'])) {
            $qb->leftJoin('post.postTags', 'postTags')
                ->andWhere('postTags.title = :tag')
                ->setParameter('tag', $params['tag']);
        }

        if(isset($params['authorId'])) {
            $qb->andWhere('post.user = :userId')
                ->setParameter('userId', $params['authorId']);
        }

        $qb->andWhere('post.status = :status')
            ->setParameter('status', PostStatusEnum::PUBLISHED);

        return $qb->getQuery();
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\NoResultException
     * $operand < >
     */
    public function getRelatedPostByOperand($postId, $operand = '<')
    {
        $qb = $this->createQueryBuilder('post')
            ->select('post')
            ->where('post.id ' . $operand . ' :currentPostId')
            ->setParameter('currentPostId', $postId)
            ->setMaxResults(1);

        return $qb->getQuery()->getOneOrNullResult();
    }

    // ADMIN

    /**
     * @return int|mixed|string
     */
    public function countPostsPerStatus()
    {

        $qb = $this->createQueryBuilder('post')
            ->select('COUNT(post.id) AS count, post.status AS status')
            ->groupBy('post.status');

        return $qb->getQuery()->getResult();
    }


}
