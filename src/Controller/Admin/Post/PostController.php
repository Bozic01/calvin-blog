<?php

namespace App\Controller\Admin\Post;

use App\Entity\Common\Newsletter;
use App\Entity\Post\Post;
use App\Entity\Post\PostCategory;
use App\Entity\Post\PostCategoryCategory;
use App\Entity\Post\PostTag;
use App\Event\NewsletterEvent;
use App\Form\Admin\Post\PostType;
use App\Traits\Admin\SearchTrait;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/post")
 */
class PostController extends AbstractController
{

    const ADMIN_DEFAULT_PER_PAGE_NUMBER = 10;
    use SearchTrait;

    /**
     * @Route("/", name="admin_post_index", methods={"GET"})
     */
    public function index(
        PaginatorInterface     $paginator,
        Request                $request,
        EntityManagerInterface $entityManager
    ): Response
    {

        $postRepository = $entityManager->getRepository(Post::class);
        $baseQuery = $postRepository->createQueryBuilder('post');
        $baseQuery = $this->extendQueryWithSearch($request, $baseQuery, ['post.title', 'post.content']);

        $posts = $paginator->paginate(
            $baseQuery->getQuery(),
            $request->query->getInt('page', '1'),
            $_ENV['ADMIN_DEFAULT_PER_PAGE_NUMBER'] ?? self::ADMIN_DEFAULT_PER_PAGE_NUMBER
        );

        return $this->render('admin/post/post/index.html.twig', [
            'posts' => $posts,
        ]);
    }

    /**
     * @Route("/new", name="admin_post_new", methods={"GET", "POST"})
     */
    public function new(Request                  $request,
                        EventDispatcherInterface $eventDispatcher,
                        EntityManagerInterface   $entityManager
    ): Response
    {

        $post = new Post();
        $post->setUser($this->getUser());
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->createPostTags($request, $post, $entityManager);
            $postCategoryIds = array_keys((array)$request->request->get('categories'));

            $this->assignNewCategories($postCategoryIds, [], $entityManager, $post);

            $entityManager->persist($post);
            $entityManager->flush();

            $newsletters = $entityManager->getRepository(Newsletter::class)->findBy(['status' => 1]);

            foreach ($newsletters as $newsletter) {

                $email = $newsletter->getEmail();
                $link = $this->generateUrl('homepage_post_show', ['id' => $post->getId()]);

                $content = "Hello there, there is a new post on our blog. If you want to check it out please follow the "
                    . "<a href='" . $_ENV['BASE_URL'] . $link . "'>link</a>" . " thank you.";

                $newsletterEvent = new NewsletterEvent();
                $newsletterEvent->setContent($content)->setEmailTo($email)->setEmailFrom($_ENV['MAILER_USERNAME']);

                $eventDispatcher->dispatch($newsletterEvent);
            }

            return $this->redirectToRoute('admin_post_index', [], Response::HTTP_SEE_OTHER);
        }

        $postCategories = $entityManager->getRepository(PostCategory::class)->findAll();

        return $this->renderForm('admin/post/post/new.html.twig', [
            'post' => $post,
            'form' => $form,
            'postCategories' => $postCategories
        ]);
    }

    /**
     * @Route("/{id}", name="admin_post_show", methods={"GET"})
     */
    public function show(Post $post, EntityManagerInterface $entityManager): Response
    {
        $post->setNumberOfViews($post->getNumberOfViews() + 1);
        $entityManager->persist($post);
        $entityManager->flush();
        return $this->render('admin/post/post/show.html.twig', [
            'post' => $post,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_post_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Post $post, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        $selectedPostCategoryIds = $this->getSelectedPostCategoryIds($post);
        $postCategories = $entityManager->getRepository(PostCategory::class)->findAll();
        $postTagsStrings = $this->generateExistingPostTagsStrings($entityManager, $post);

        if ($form->isSubmitted() && $form->isValid()) {

            /** removing deleted/changed post tags */
            $this->removePostTags($request, $entityManager, $post);

            /** add new post tags */
            $this->createPostTags($request, $post, $entityManager);

            $postCategoryIds = array_keys((array)$request->request->get('categories'));

            /** assign new categories */
            $this->assignNewCategories($postCategoryIds, $selectedPostCategoryIds, $entityManager, $post);

            /** removing deselected post categories */
            $this->removePostCategories($selectedPostCategoryIds, $postCategoryIds, $entityManager, $post);

            $entityManager->flush();
            $this->addFlash('success', 'Post successfully updated');
            return $this->redirectToRoute('admin_post_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/post/post/edit.html.twig', [
            'post' => $post,
            'form' => $form,
            'postCategories' => $postCategories,
            'selectedPostCategoryIds' => $selectedPostCategoryIds,
            'postTagsStrings' => $postTagsStrings,
        ]);
    }

    /**
     * @Route("/{id}", name="admin_post_delete", methods={"POST"})
     */
    public function delete(Request $request, Post $post, EntityManagerInterface $entityManager): Response
    {
        $checkIfPostIsUsed = $entityManager->getRepository(Post::class)->findOneBy(['id' => $post]);

        if (
            $this->isCsrfTokenValid('delete' . $post->getId(), $request->request->get('_token')) &&
            is_null($checkIfPostIsUsed)
        ) {
            $entityManager->remove($post);
            $entityManager->flush();
        } else {
            $this->addFlash('error', 'This post cannot be deleted due to usage.');
        }

        return $this->redirectToRoute('admin_post_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @param Post $post
     * @return array
     */
    protected function getSelectedPostCategoryIds(Post $post): array
    {
        $selectedPostCategoryIds = [];
        $selectedPostCategories = $post->getPostCategoryCategories();

        foreach ($selectedPostCategories as $selectedPostCategory) {
            array_push($selectedPostCategoryIds, $selectedPostCategory->getPostCategory()->getId());
        }
        return $selectedPostCategoryIds;
    }

    /**
     * @param Request $request
     * @param Post $post
     * @param EntityManagerInterface $entityManager
     */
    protected function createPostTags(Request $request, Post $post, EntityManagerInterface $entityManager): void
    {

        $postTags = explode(',', $request->request->get('tags'));

        foreach ($postTags as $postTagTitle) {

            $postTagTitle = trim($postTagTitle);

            $checkPostTag = $entityManager->getRepository(PostTag::class)
                ->findOneBy(['title' => $postTagTitle, 'post' => $post]);

            if (!$postTagTitle || !is_null($checkPostTag)) {
                continue;
            }
            $postTag = new PostTag();
            $postTag->setTitle(trim($postTagTitle));
            $postTag->setPost($post);
            $entityManager->persist($postTag);

        }
    }

    /**
     * @param EntityManagerInterface $entityManager
     * @param Post $post
     * @return string
     */
    protected function generateExistingPostTagsStrings(EntityManagerInterface $entityManager, Post $post): string
    {
        $postTags = $entityManager->getRepository(PostTag::class)->findBy(['post' => $post]);

        $postTagsStrings = '';

        foreach ($postTags as $postTag) {
            $postTagsStrings .= $postTag->getTitle();
            if ($postTag != end($postTags)) {
                $postTagsStrings .= ', ';
            }
        }
        return $postTagsStrings;
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param Post $post
     */
    protected function removePostTags(Request $request, EntityManagerInterface $entityManager, Post $post): void
    {
        $postTags = explode(',', str_replace(', ', ',', $request->request->get('tags')));

        $existingPostTags = $entityManager->getRepository(PostTag::class)->findBy(['post' => $post]);

        foreach ($existingPostTags as $postTag) {
            if (!in_array($postTag->getTitle(), $postTags)) {
                $entityManager->remove($postTag);
            }
        }
    }

    /**
     * @param array $incomingPostCategoryIds
     * @param array $selectedPostCategoryIds
     * @param EntityManagerInterface $entityManager
     * @param Post $post
     */
    protected function assignNewCategories(
        array                  $incomingPostCategoryIds,
        array                  $selectedPostCategoryIds,
        EntityManagerInterface $entityManager,
        Post                   $post
    ): void
    {
        foreach ($incomingPostCategoryIds as $postCategoryId) {
            if (in_array($postCategoryId, $selectedPostCategoryIds)) {
                continue;
            }
            $postCategory = $entityManager->getRepository(PostCategory::class)->find($postCategoryId);
            $postCategoryCategory = new PostCategoryCategory();
            $postCategoryCategory->setPost($post);
            $postCategoryCategory->setPostCategory($postCategory);
            $entityManager->persist($postCategoryCategory);
        }
    }

    /**
     * @param array $selectedPostCategoryIds
     * @param array $postCategoryIds
     * @param EntityManagerInterface $entityManager
     * @param Post $post
     */
    protected function removePostCategories(
        array                  $selectedPostCategoryIds,
        array                  $postCategoryIds,
        EntityManagerInterface $entityManager,
        Post                   $post
    ): void
    {
        foreach ($selectedPostCategoryIds as $selectedPostCategoryId) {
            if (!in_array($selectedPostCategoryId, $postCategoryIds)) {
                $postCategoryToRemove = $entityManager->getRepository(PostCategoryCategory::class)->findOneBy([
                    'post' => $post,
                    'postCategory' => $selectedPostCategoryId
                ]);

                if (!is_null($postCategoryToRemove)) {
                    $entityManager->remove($postCategoryToRemove);
                }
            }
        }
    }


}
