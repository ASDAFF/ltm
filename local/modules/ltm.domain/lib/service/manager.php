<?php

namespace Ltm\Domain\Service;

class Manager
{
    protected static $container;

    public function __construct()
    {
        if (!static::$container instanceof \Pimple\Container) {
            static::$container = new \Pimple\Container();
        }
    }

    public static function getContainer()
    {
        return static::$container;
    }

    public static function getService($serviceId)
    {
        return static::$container[$serviceId];
    }
}
