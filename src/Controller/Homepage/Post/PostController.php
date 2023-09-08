<?php

namespace App\Controller\Homepage\Post;

use App\Entity\Post\Post;
use App\Entity\Post\PostCategory;
use App\Entity\Post\PostTag;
use App\Entity\User\User;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    /**
     * @Route("/posts", name="homepage_post_list", methods={"GET"})
     */
    public function index(
        EntityManagerInterface $entityManager,
        Request                $request,
        PaginatorInterface     $paginator
    ): Response
    {
        $categoryId = $request->query->get('category-id');
        $authorId = $request->query->get('author-id');
        $tag = $request->query->get('tag');
        $page = $request->query->get('page') ?: 1;

        if (!$categoryId && !$authorId && !$tag) {
            throw new \Exception('Missing mandatory parameters.');
        }

        $baseQuery = $entityManager->getRepository(Post::class)->findByCustomParams([
            'categoryId' => $categoryId,
            'authorId' => $authorId,
            'tag' => $tag
        ]);

        $pagedPosts = $paginator->paginate($baseQuery, $page, 12);

        $postCategory = $categoryId ? $entityManager->getRepository(PostCategory::class)->findOneBy(['id' => $categoryId]) : null;

        $postAuthor = $authorId ? $entityManager->getRepository(User::class)->findOneBy(['id' => $authorId]) : null;

        return $this->render('homepage/post/index.html.twig', [
            'posts' => $pagedPosts,
            'postCategory' => $postCategory,
            'postAuthor' => $postAuthor,
            'tag' => $tag

        ]);
    }

    /**
     * @Route("/single-post/{id}", name="homepage_post_show", methods={"GET"})
     */
    public function show(Post $post): Response
    {
        return $this->render('homepage/post/single_post.html.twig', [
            'post' => $post
        ]);
    }
}
