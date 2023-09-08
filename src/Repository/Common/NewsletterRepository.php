<?php

namespace App\Repository\Common;

use App\Entity\Common\Newsletter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


class NewsletterRepository extends ServiceEntityRepository
{
    /**
     * @method Newsletter|null find($id, $lockMode = null, $lockVersion = null)
     * @method Newsletter|null findOneBy(array $criteria, array $orderBy = null)
     * @method Newsletter[]    findAll()
     * @method Newsletter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
     */

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Newsletter::class);
    }


}