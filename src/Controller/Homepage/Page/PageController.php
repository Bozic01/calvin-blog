<?php

namespace App\Controller\Homepage\Page;

use App\Entity\Page\Page;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    /**
     * @Route("/page/{tag}", name="homepage_page", methods={"GET"})
     */
    public function show($tag, EntityManagerInterface $entityManager)
    {
        $page = $entityManager->getRepository(Page::class)->findOneBy(['tag' => $tag]);

        if(is_null($page)) {
            throw new NotFoundHttpException('This page does not exist');
        }
        return $this->render('homepage/page/index.html.twig',[
            'page' => $page,
        ]);
    }
}
