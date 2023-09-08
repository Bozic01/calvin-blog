<?php

namespace App\Controller\Homepage\Common;

use App\Entity\Common\Newsletter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NewsletterController extends AbstractController
{

    /**
     * @Route("/newsletter", name="homepage_newsletter_signup", methods={"POST"})
     */
    public function index(EntityManagerInterface $entityManager, Request $request): Response
    {

        $requestContent = json_decode($request->getContent());
        $email = $requestContent->email;

        $checkEmail = $entityManager->getRepository(Newsletter::class)->findOneBy(['email' => $email]);

        if (!is_null($checkEmail)) {
            return new JsonResponse(['message' => 'You are already subscribed.'], 400);
        }

        if (!$email) {
            return new JsonResponse(['message' => 'Invalid email.'], 400);
        }

        $newsletter = new Newsletter();
        $newsletter->setEmail($email);
        $entityManager->persist($newsletter);
        $entityManager->flush();


        return new JsonResponse(['message' => 'You are successfully subscribed.']);

    }
}
