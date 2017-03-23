<?php
if (!function_exists('app')) {
    /**
     * same as \DI\Container::get
     *
     * @param $name
     *
     * @return mixed
     */
    function app($name)
    {
        /**
         * @var \DI\Container
         */
        global $container;
        return $container->get($name);
    }
}