<?php

namespace App\Enum\Page;

class PageTagsEnum
{
    const ABOUT = "about";
    const CONTACT = "contact";
    const BLOG = "blog";
    const FAQ = "faq";
    const TERMS = "terms";
    const PRIVACY_POLICY = "privacy-policy";

    public static function all(): array {
        return(new \ReflectionClass(self::class))->getConstants();
    }

}
