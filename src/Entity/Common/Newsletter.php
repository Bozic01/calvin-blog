<?php

namespace App\Entity\Common;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Common\NewsletterRepository")
 */
class Newsletter extends BaseEntity
{

    /**
     * @ORM\Column(type="string", length=50, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="boolean")
     */
    private $status = false;

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }
}
