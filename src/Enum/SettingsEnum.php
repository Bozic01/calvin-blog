<?php

namespace App\Enum;

class SettingsEnum
{
    const FACEBOOK = 'FACEBOOK';
    const TWITTER = 'TWITTER';
    const INSTAGRAM = 'INSTAGRAM';
    const DRIBBBLE = 'DRIBBBLE';
    const PINTEREST = 'PINTEREST';
    const PHONE = 'PHONE';
    const COPYRIGHT = 'COPYRIGHT';
    const ABOUT_OUR_SITE = 'ABOUT_OUR_SITE';

    public static function all(): array {
        return(new \ReflectionClass(self::class))->getConstants();

    }
}
