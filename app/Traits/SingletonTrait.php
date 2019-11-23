<?php

namespace App\Traits;

trait SingletonTrait
{
    protected static $instances;

    public static function getInstance()
    {
        if (!static::$instances) {
            static::$instances = new static();
        }

        return self::$instances;
    }
}