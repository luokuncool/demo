<?php
/**
 * The bootstrap file creates and returns the container.
 */

use DI\ContainerBuilder;
use Symfony\Component\Debug\ErrorHandler;

require __DIR__ . '/../vendor/autoload.php';

$containerBuilder = new ContainerBuilder(\Blog\Container::class);
$containerBuilder->useAnnotations(true);
$containerBuilder->addDefinitions(__DIR__ . '/container.php');
$container = $containerBuilder->build();

$handler = ErrorHandler::register();
$handler->setDefaultLogger($container->get('logger'));

return $container;
