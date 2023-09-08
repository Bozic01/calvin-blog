<?php

namespace App\Repository\Post;

use App\Entity\Post\PostTag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PostTag|null find($id, $lockMode = null, $lockVersion = null)
 * @method PostTag|null findOneBy(array $criteria, array $orderBy = null)
 * @method PostTag[]    findAll()
 * @method PostTag[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostTagRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PostTag::class);
    }

    public function findTagByTitle() {

        $qb = $this->createQueryBuilder('postTag')
            ->select('COUNT(postTag.id) as tagCount, (postTag.title) as tagTitle')
            ->groupBy('postTag.title');

        return $qb->getQuery()->getResult();
    }
}
