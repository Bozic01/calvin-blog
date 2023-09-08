<?php

namespace App\Controller\Homepage\Contact;
use App\Entity\Common\Contact;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SendContactController extends AbstractController
{

    /**
     * @Route("/new_contact", name="new_contact", methods={"POST"})
     */
    public function index(EntityManagerInterface $entityManager, Request $request): Response
    {

        $requestContent = json_decode($request->getContent());
        $name = $requestContent->name;
        $email = $requestContent->email;
        $website = $requestContent->website;
        $message = $requestContent->message;

        $contact = new Contact();
        $contact->setName($name);
        $contact->setEmail($email);
        $contact->setWebsite($website);
        $contact->setContent($message);

        $entityManager->persist($contact);
        $entityManager->flush();

        return new JsonResponse(['message' => 'You have successfully sent message.']);
        
    }


}