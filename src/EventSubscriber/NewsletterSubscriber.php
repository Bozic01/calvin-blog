<?php

namespace App\EventSubscriber;

use App\Event\CommentEvent;
use App\Event\NewsletterEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class NewsletterSubscriber implements EventSubscriberInterface
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    public function __construct(\Swift_Mailer $mailer) {

        $this->mailer = $mailer;
    }


    public static function getSubscribedEvents()
    {
        return [
          NewsletterEvent::class => 'sendNewsletterEmail'
        ];
    }

    /**
     * @param NewsletterEvent $event
     */
    public function sendNewsletterEmail(NewsletterEvent $event)
    {
        $message = (new \Swift_Message('New post'))
            ->setFrom($_ENV['MAILER_USERNAME'])
            ->setTo($event->getEmailTo());

        $emailBody = "<p>Content: {$event->getContent()}</p><p>By: {$event->getEmailFrom()}</p>";

        $message->setBody(
            $emailBody,
            'text/html'
        );

        $this->mailer->send($message);
    }
}