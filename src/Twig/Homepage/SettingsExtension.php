<?php

namespace App\Twig\Homepage;

use App\Entity\Settings;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class SettingsExtension extends AbstractExtension
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('get_setting_value', [$this, 'getSettingValue']),
        ];
    }

    public function getSettingValue($key)
    {
        return $this->entityManager->getRepository(Settings::class)->findOneBy(['settingKey' => $key]);
    }

}
