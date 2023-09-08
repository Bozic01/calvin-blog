<?php

namespace App\Controller\Homepage\Post;

use App\Entity\Post\Comment;
use App\Entity\Post\Post;
use App\Entity\Post\PostCategory;
use App\Event\CommentEvent;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    /**
     * @Route("/post-comment/{id}", name="homepage_post_comment_new", methods={"POST"})
     */
    public function new(
        $id,
        EntityManagerInterface $entityManager,
        Request $request,
        EventDispatcherInterface $eventDispatcher
    ): Response
    {
        $post = $entityManager->getRepository(Post::class)->find($id);
        $user = $this->getUser();
        $content = $request->request->get('cMessage');
        $parentCommentId = $request->request->get('parentCommentId');
        $parentComment = null;

        if (!$post || !$user) {
            throw new NotFoundHttpException("Post not found!");
        }

        if (!$content){
            $this->addFlash('error', 'The comment content cannot be empty');
			return $this->render('homepage/post/single_post.html.twig', [
			'post' => $post
                ]);
        }

        if ($parentCommentId) {
            $parentComment = $entityManager->getRepository(Comment::class)->findOneBy(['id' => $parentCommentId]);

            if (!$parentComment) {
                throw new NotFoundHttpException("Main comment not found!");
            }

            $emailTo = $parentComment->getUser()->getEmail();
        }

        $comment = new Comment();

        $comment->setPost($post);
        $comment->setContent($content);
        $comment->setUser($user);
        $comment->setCreatedAt(new \DateTimeImmutable());
        $comment->setParentComment($parentComment);

        $entityManager->persist($comment);
        $entityManager->flush();

      //  $commentEvent = new CommentEvent();
      //  $commentEvent->setContent($content)
      //      ->setEmailTo($emailTo)
      //      ->setEmailFrom($user->getEmail());

   //     $eventDispatcher->dispatch($commentEvent);

        return $this->render('homepage/post/single_post.html.twig', [
            'post' => $post
        ]);

//        return $this->redirectToRoute('homepage_post_show', ['id' => $id]);

    }
}
