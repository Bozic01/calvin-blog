<?php

namespace App\Controller\Admin\Common;

use App\Entity\Common\Newsletter;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NewsletterController extends AbstractController
{

    const ADMIN_DEFAULT_PER_PAGE_NUMBER = 10;

    /**
     * @Route("/admin/newsletter", name="admin_newsletter_list")
     */
    public function index(
        EntityManagerInterface $entityManager,
        PaginatorInterface $paginator,
        Request $request
    ): Response
    {
        $newsletterQuery = $entityManager->getRepository(Newsletter::class)
            ->createQueryBuilder('newsletter')
            ->getQuery();

        $newsletters = $paginator->paginate(
            $newsletterQuery,
            $request->query->getInt('page', '1'),
            $_ENV['ADMIN_DEFAULT_PER_PAGE_NUMBER'] ?? self::ADMIN_DEFAULT_PER_PAGE_NUMBER
        );

        return $this->render('admin/newsletter/index.html.twig', [
            'newsletters' => $newsletters,
        ]);
    }

    /**
     * @Route("/{id}/enable", name="admin_newsletter_enable", methods={"GET"})
     */
    public function update(Newsletter $newsletter, EntityManagerInterface $entityManager): Response
    {

        if (!is_null($newsletter)) {
            $newsletter->setStatus(!$newsletter->getStatus());
            $entityManager->persist($newsletter);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_newsletter_list');

    }

}
