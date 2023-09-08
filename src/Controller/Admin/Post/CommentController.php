<?php

namespace App\Controller\Admin\Post;

use App\Entity\Post\Comment;
use App\Traits\Admin\SearchTrait;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/post/comment")
 */
class CommentController extends AbstractController
{

    const ADMIN_DEFAULT_PER_PAGE_NUMBER = 10;
    use SearchTrait;

    /**
     * @Route("/", name="admin_post_comment_index", methods={"GET"})
     */
    public function index(
        PaginatorInterface     $paginator,
        EntityManagerInterface $entityManager,
        Request                $request
    ): Response
    {
        $commentRepository = $entityManager->getRepository(Comment::class);
        $baseQuery = $commentRepository->createQueryBuilder('comments');
        $baseQuery = $this->extendQueryWithSearch($request, $baseQuery, ['comments.content']);
        $baseQuery->getQuery();

        $comments = $paginator->paginate(
            $baseQuery,
            $request->query->getInt('page', 1),
            $_ENV['ADMIN_DEFAULT_PER_PAGE_NUMBER'] ?? self::ADMIN_DEFAULT_PER_PAGE_NUMBER
        );

        return $this->render('admin/post/comment/index.html.twig', [
            'comments' => $comments,
        ]);
    }


    /**
     * @Route("/{id}/enable", name="admin_post_comment_enable", methods={"GET"})
     */
    public function update(Comment $comment, EntityManagerInterface $entityManager): Response
    {

        if (!is_null($comment)) {
            $comment->setIsEnabled(!$comment->getIsEnabled());
            $entityManager->persist($comment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_post_comment_index');

    }

}





