<?php

namespace App\Controller\Homepage;


use App\Entity\Post\Post;
use App\Traits\Admin\SearchTrait;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class HomepageController extends AbstractController
{
    use SearchTrait;

    /**
     * @Route("/", name="homepage", methods={"GET"})
     */
    public function index(
        EntityManagerInterface $entityManager,
        Request                $request,
        PaginatorInterface     $paginator
    ): Response
    {
        $baseQuery = $entityManager->getRepository(Post::class)->createQueryBuilder('post');
        $searchPost = $this->extendQueryWithSearch($request, $baseQuery, ['post.title', 'post.content']);

        $page = $request->query->get('page') ?: 1;

        $pagedPosts = $paginator->paginate($searchPost->getQuery(), $page, 2);

        return $this->render('homepage/homepage_posts.html.twig', [
            'posts' => $pagedPosts,
        ]);
    }

}
