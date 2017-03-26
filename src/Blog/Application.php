<?php

namespace Blog;

class Application
{
    /**
     * @var \DI\Container
     */
    private static $container;

    public static function setContainer(\DI\Container $container)
    {
        self::$container = $container;
    }

    public static function getContainer()
    {
        return self::$container;
    }
}