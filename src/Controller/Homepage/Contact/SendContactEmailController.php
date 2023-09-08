<?php

namespace App\Controller\Homepage\Contact;

use App\Entity\Common\Contact;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SendContactEmailController extends AbstractController
{
    /**
     * @var \Swift_Mailer
     */
    public $mailer;

    /**
     * MailerController constructor
     * @param \Swift_Mailer $mailer
     */
    public function __construct(\Swift_Mailer $mailer) {
        $this->mailer = $mailer;
    }

    /**
     * @Route("/send-contact", name="homepage_send_contact")
     */
    public function send(Request $request, EntityManagerInterface $entityManager) {
        $name = $request->request->get('name');
        $email = $request->request->get('email');
        $content = $request->request->get('content');
        $website = $request->request->get('website');

        $body = "Your Name : {$name} </br>".
                "Your Email : {$email}</br>".
                "Website : {$website}</br>".
                "Your Message : {$content}";

        try {
            $message = (new \Swift_Message())
                ->setFrom($_ENV['DEFAULT_MAILER_FROM'])
                ->addReplyTo($email)
                ->setTo($_ENV['DEFAULT_MAILER_TO']);

            $message->setBody(
                $body,
                'text/html'
            );

            $this->mailer->send($message);

            $contact = new Contact();
            $contact->setEmail($email);
            $contact->setName($name);
            $contact->setContent($content);
            $contact->setWebsite($website);

            $entityManager->persist($contact);
            $entityManager->flush();

            return new Response('Message is sent');

        } catch (\Exception $exception) {
            return new Response('Error');
        }
    }
}
