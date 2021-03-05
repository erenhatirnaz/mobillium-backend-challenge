<?php

namespace App\Enums;

abstract class BaseEnum
{
    public static function getEnumFromString($str)
    {
        $calledClass = get_called_class();
        $reflect = new \ReflectionClass($calledClass);
        $constants = $reflect->getConstants();

        $str = strtoupper($str);

        return $constants[$str];
    }
}
