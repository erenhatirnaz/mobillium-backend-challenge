<?php

namespace App\Enums;

abstract class Roles
{
    public const READER = 1;
    public const WRITER = 2;
    public const MODERATOR = 3;
    public const ADMIN = 4;

    public static function getRoleFromString($str)
    {
        $reflect = new \ReflectionClass(Roles::class);
        $constants = $reflect->getConstants();

        $str = strtoupper($str);

        return $constants[$str];
    }
}
