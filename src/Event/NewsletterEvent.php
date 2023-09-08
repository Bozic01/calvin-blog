<?php

namespace App\Event;


use Symfony\Contracts\EventDispatcher\Event;

class NewsletterEvent extends Event
{
    private $emailTo;
    private $emailFrom;
    private $content;

    /**
     * @return mixed
     */
    public function getEmailTo()
    {
        return $this->emailTo;
    }

    /**
     * @param mixed $emailTo
     */
    public function setEmailTo($emailTo): self
    {
        $this->emailTo = $emailTo;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getEmailFrom()
    {
        return $this->emailFrom;
    }

    /**
     * @param mixed $emailFrom
     */
    public function setEmailFrom($emailFrom): self
    {
        $this->emailFrom = $emailFrom;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content): self
    {
        $this->content = $content;

        return $this;
    }

}