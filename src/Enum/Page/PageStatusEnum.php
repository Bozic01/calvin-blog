<?php

namespace App\Enum\Page;

class PageStatusEnum
{
    const DRAFT = "DRAFT";
    const PUBLISHED = "PUBLISHED";
    const ARCHIVED = "ARCHIVED";


    public static function all(): array {
        return(new \ReflectionClass(self::class))->getConstants();

    }
}
