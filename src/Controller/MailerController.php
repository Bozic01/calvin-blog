<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MailerController extends AbstractController
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
     * @Route("/mailer", name="mailer")
     */
    public function send() {

        try {

            $message = (new \Swift_Message('Calvin Mail'))
                ->setFrom('nikolakisin@gmail.com')
                ->addReplyTo('nikolakisin@gmail.com')
                ->setTo('branimir.itmedia@gmail.com');

            $message->setBody(

                'Test email',
                'text/html'

            );

            $this->mailer->send($message);
            return new Response('Sent');

        } catch (\Exception $exception) {
            return new Response('Error');
        }

    }

}
