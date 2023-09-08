<?php

namespace App\Controller\Admin\Post;

use App\Entity\Post\PostTag;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PostTagController extends AbstractController
{
    /**
     * @Route ("/admin/post-tags", name="admin_post_tag_index")
     */
    public function index(EntityManagerInterface $entityManager)
    {

        $postTags = $entityManager->getRepository(PostTag::class)->findTagByTitle();

        return $this->render('admin/tags.html.twig', [
            'tags' => $postTags
        ]);

    }

}
