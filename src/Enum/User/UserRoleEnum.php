<?php

namespace App\Enum\User;

class UserRoleEnum
{
    const ROLE_USER = "ROLE_USER";
    const ROLE_ADMIN = "ROLE_ADMIN";


    public static function all(): array {
        return(new \ReflectionClass(self::class))->getConstants();

    }
}
