<?php
/**
 * The bootstrap file creates and returns the container.
 */

use Blog\Application;
use DI\ContainerBuilder;
use Symfony\Component\Debug\Debug;
use Symfony\Component\Debug\ErrorHandler;

require __DIR__ . '/vendor/autoload.php';

define('ENV', 'dev');
Debug::enable();

$builder = new ContainerBuilder(\Blog\Container::class);
$builder->useAnnotations(true);
$builder->addDefinitions(__DIR__ . '/config/parameters.' . ENV . '.php');
$builder->addDefinitions(__DIR__ . '/config/container.php');
$container = $builder->build();
Application::setContainer($container);

$handler = ErrorHandler::register();
$handler->setDefaultLogger($container['logger']);

return $container;
