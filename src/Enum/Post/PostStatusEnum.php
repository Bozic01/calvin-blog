<?php

namespace App\Enum\Post;

class PostStatusEnum
{
    const DRAFT = "DRAFT";
    const PUBLISHED = "PUBLISHED";
    const ARCHIVED = "ARCHIVED";


    public static function all(): array {
        return(new \ReflectionClass(self::class))->getConstants();

    }
}
