<?php

namespace App\Controller\Admin;

use App\Entity\Page\Page;
use App\Entity\Post\Comment;
use App\Entity\Post\Post;
use App\Entity\User\User;
use App\Enum\Post\PostStatusEnum;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class DashboardController extends AbstractController
{
    const ADMIN_DASHBOARD_POST_NUMBER = 5;

    /**
     * @Route("/admin", name="admin_dashboard", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $pageCount = $entityManager->getRepository(Page::class)->countPagesPerStatus();
        $postCount = $entityManager->getRepository(Post::class)->countPostsPerStatus();
        $mostViewed = $entityManager->getRepository(Post::class)->findBy(
            ['status' => PostStatusEnum::PUBLISHED],
            ['numberOfViews' => 'DESC'],
            $_ENV['ADMIN_DASHBOARD_POST_NUMBER'] ?? self::ADMIN_DASHBOARD_POST_NUMBER
        );
        $usersCount = $entityManager->getRepository(User::class)->countRegisterUsers();
        $commentCount = $entityManager->getRepository(Comment::class)->countComments();

        return $this->render('admin/dashboard.html.twig', [
            'pageCount' => $pageCount,
            'postCount' => $postCount,
            'mostViewed' => $mostViewed,
            'userCount' => $usersCount ,
            'commentCount' => $commentCount
        ]);
    }
}




