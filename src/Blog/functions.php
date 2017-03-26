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
        return \Blog\Application::getContainer()->get($name);
    }
}