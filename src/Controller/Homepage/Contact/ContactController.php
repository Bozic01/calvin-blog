<?php

namespace App\Controller\Homepage\Contact;


use App\Entity\Common\Contact;
use App\Entity\Page\Page;
use App\Enum\Page\PageStatusEnum;
use App\Enum\Page\PageTagsEnum;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @var \Swift_Mailer
     */
    public $mailer;

    /**
     * MailerController constructor
     * @param \Swift_Mailer $mailer
     */
    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @Route("/contact", name="homepage_contact", methods={"GET", "POST"})
     */
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $page = $entityManager->getRepository(Page::class)->findOneBy(['tag' => PageTagsEnum::CONTACT]);

        $name = $request->request->get('name');
        $email = $request->request->get('email');
        $website = $request->request->get('website');
        $content = $request->request->get('content');

        if ($email) {

            $contact = new Contact();
            $contact->setName($name);
            $contact->setEmail($email);
            $contact->setWebsite($website);
            $contact->setContent($content);
            $entityManager->persist($contact);
            $entityManager->flush();

            try {

                $message = (new \Swift_Message('Calvin Blog'))
                    ->setFrom($email)
                    ->addReplyTo($email)
                    ->setTo('haristomos@gmail.com');

                $message->setBody(
                    $content,
                    'text\html'
                );

                $this->mailer->send($message);

            } catch (\Exception $exception) {
                return new Response('Error');
            }

        }

        return $this->render('homepage/contact/contact.html.twig', [
            'name' => $name,
            'email' => $email,
            'website' => $website,
            'content' => $content,
            'page' => $page
        ]);
    }

}
