<?php

namespace App\Controller\Admin\Post;

use App\Entity\Post\PostCategory;
use App\Entity\Post\PostCategoryCategory;
use App\Form\Admin\Post\PostCategoryType;
use App\Repository\Post\PostCategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/post/category")
 */
class PostCategoryController extends AbstractController
{

    const ADMIN_DEFAULT_PER_PAGE_NUMBER = 10;

    /**
     * @Route("/", name="admin_post_category_index", methods={"GET"})
     */
    public function index(
        EntityManagerInterface $entityManager,
        PaginatorInterface     $paginator,
        Request                $request
    ): Response
    {

        $postCategoryRepository = $entityManager->getRepository(PostCategory::class);
        $baseQuery = $postCategoryRepository->createQueryBuilder('post_category')->getQuery();
        $postCategory = $paginator->paginate(
            $baseQuery,
            $request->query->getInt('page', '1'),
            $_ENV['ADMIN_DEFAULT_PER_PAGE_NUMBER'] ?? self::ADMIN_DEFAULT_PER_PAGE_NUMBER
        );

        return $this->render('admin/post_category/index.html.twig', [
            'post_categories' => $postCategory
        ]);

    }

    /**
     * @Route("/new", name="admin_post_category_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $postCategory = new PostCategory();
        $form = $this->createForm(PostCategoryType::class, $postCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($postCategory);
            $entityManager->flush();

            return $this->redirectToRoute('admin_post_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/post_category/new.html.twig', [
            'post_category' => $postCategory,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="admin_post_category_show", methods={"GET"})
     */
    public function show(PostCategory $postCategory): Response
    {
        return $this->render('admin/post_category/show.html.twig', [
            'post_category' => $postCategory,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_post_category_edit", methods={"GET", "POST"})
     */
    public function edit(
        Request                $request,
        PostCategory           $postCategory,
        EntityManagerInterface $entityManager
    ): Response
    {
        $form = $this->createForm(PostCategoryType::class, $postCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('admin_post_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/post_category/edit.html.twig', [
            'post_category' => $postCategory,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="admin_post_category_delete", methods={"POST"})
     */
    public function delete(
        Request                $request,
        PostCategory           $postCategory,
        EntityManagerInterface $entityManager
    ): Response
    {
        $checkIfPostCategoryIsUsed = $entityManager->getRepository(PostCategoryCategory::class)->findOneBy([
            'postCategory' => $postCategory
        ]);

        if (
            $this->isCsrfTokenValid('delete' . $postCategory->getId(), $request->request->get('_token')) &&
            is_null($checkIfPostCategoryIsUsed)
        ) {
            $entityManager->remove($postCategory);
            $entityManager->flush();
        } else {
            $this->addFlash('error', 'This post category cannot be deleted due to usage.');
        }

        return $this->redirectToRoute('admin_post_category_index', [], Response::HTTP_SEE_OTHER);
    }
}
