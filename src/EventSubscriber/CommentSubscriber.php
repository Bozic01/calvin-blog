<?php

namespace App\EventSubscriber;

use App\Event\CommentEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CommentSubscriber implements EventSubscriberInterface
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
          CommentEvent::class => 'sendCommentEmail'
        ];
    }

    /**
     * @param CommentEvent $commentEvent
     */
    public function sendCommentEmail(CommentEvent $commentEvent) {

        $message = (new \Swift_Message('New comment'))
            ->setFrom($_ENV['MAILER_USERNAME'])
            ->setTo($commentEvent->getEmailTo());

        $emailBody = "<p>Content: {$commentEvent->getContent()}</p><p>By: {$commentEvent->getEmailFrom()}</p>";

        $message->setBody(
            $emailBody,
            'text/html'
        );

        $this->mailer->send($message);
    }
}
