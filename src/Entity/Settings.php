<?php

namespace App\Entity;

use App\Entity\Common\BaseEntity;
use App\Repository\SettingsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SettingsRepository::class)
 */
class Settings extends BaseEntity
{

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $settingKey;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $settingValue;


    public function getSettingKey(): ?string
    {
        return $this->settingKey;
    }

    public function setSettingKey(string $settingKey): self
    {
        $this->settingKey = $settingKey;

        return $this;
    }

    public function getSettingValue(): ?string
    {
        return $this->settingValue;
    }

    public function setSettingValue(string $settingValue): self
    {
        $this->settingValue = $settingValue;

        return $this;
    }
}
