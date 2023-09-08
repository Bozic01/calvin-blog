<?php

namespace App\Controller\Admin\Page;

use App\Entity\Page\Page;
use App\Form\Admin\Page\PageType;
use App\Traits\Admin\SearchTrait;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/page")
 */
class PageController extends AbstractController
{

    const ADMIN_DEFAULT_PER_PAGE_NUMBER = 10;
    use SearchTrait;

    /**
     * @Route("/", name="admin_page_index", methods={"GET"})
     */
    public function index(
        EntityManagerInterface $entityManager,
        PaginatorInterface     $paginator,
        Request                $request
    ): Response
    {

        $pageRepository = $entityManager->getRepository(Page::class);
        $baseQuery = $pageRepository->createQueryBuilder('page');
        $baseQuery = $this->extendQueryWithSearch($request, $baseQuery, ['page.title', 'page.content']);
        $pages = $paginator->paginate(
            $baseQuery->getQuery(),
            $request->query->getInt('page', '1'),
            $_ENV['ADMIN_DEFAULT_PER_PAGE_NUMBER'] ?? self::ADMIN_DEFAULT_PER_PAGE_NUMBER
        );


        return $this->render('admin/page/index.html.twig', [
            'pages' => $pages,
        ]);
    }

    /**
     * @Route("/new", name="admin_page_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $page = new Page();
        $page->setUser($this->getUser());
        $form = $this->createForm(PageType::class, $page);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($page);
            $entityManager->flush();

            return $this->redirectToRoute('admin_page_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/page/new.html.twig', [
            'page' => $page,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="admin_page_show", methods={"GET"})
     */
    public function show(Page $page): Response
    {
        return $this->render('admin/page/show.html.twig', [
            'page' => $page,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_page_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Page $page, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PageType::class, $page);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('admin_page_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/page/edit.html.twig', [
            'page' => $page,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="admin_page_delete", methods={"POST"})
     */
    public function delete(Request $request, Page $page, EntityManagerInterface $entityManager): Response
    {
        $checkIfPageIsUsed = $entityManager->getRepository(Page::class)->findOneBy(['title' => $page]);

        if (
            $this->isCsrfTokenValid('delete' . $page->getId(), $request->request->get('_token')) &&
            is_null($checkIfPageIsUsed)
        ) {
            $entityManager->remove($page);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_page_index', [], Response::HTTP_SEE_OTHER);
    }
}
