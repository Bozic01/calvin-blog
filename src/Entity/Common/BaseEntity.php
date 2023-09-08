<?php

namespace App\Entity\Common;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass()
 */
class BaseEntity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    public function getId(): ?int
    {
        return $this->id;
    }
}
