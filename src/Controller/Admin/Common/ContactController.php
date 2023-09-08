<?php

namespace App\Controller\Admin\Common;

use App\Entity\Common\Contact;
use App\Traits\Admin\SearchTrait;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{

    const ADMIN_DEFAULT_PER_PAGE_NUMBER = 10;
    use SearchTrait;

    /**
     * @Route("/admin/contact", name="admin_contact_list", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager, PaginatorInterface $paginator, Request $request)
    {

        $baseQuery = $entityManager->getRepository(Contact::class);
        $baseQuery = $baseQuery->createQueryBuilder('contact');
        $baseQuery = $this->extendQueryWithSearch($request, $baseQuery, ['contact.name', 'contact.email']);
        $baseQuery->getQuery();

        $contacts = $paginator->paginate(
            $baseQuery,
            $request->query->getInt('page', '1'),
            $_ENV['ADMIN_DEFAULT_PER_PAGE_NUMBER'] ?? self::ADMIN_DEFAULT_PER_PAGE_NUMBER
        );

        return $this->render('admin/contact/index.html.twig', [
            'contacts' => $contacts,
        ]);
    }

}
