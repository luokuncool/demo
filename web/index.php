<?php

use FastRoute\RouteCollector;
use Symfony\Component\HttpFoundation\Request;

/** @var DI\Container $container */
$container = require __DIR__ . '/../bootstrap.php';

$dispatcher = FastRoute\simpleDispatcher(function (RouteCollector $r) {
    $r->addRoute('GET', '/', 'Blog\Controller\HomeController');
    $r->addRoute('GET', '/article/{id:[0-9]+}', ['Blog\Controller\ArticleController', 'show']);

    $r->addRoute('GET', '/article', ['Blog\Controller\ArticleController', 'all']);
    $r->addRoute('GET', '/article/get/{id:[0-9]+}', ['Blog\Controller\ArticleController', 'get']);
    $r->addRoute('POST', '/article/post', ['Blog\Controller\ArticleController', 'post']);
    $r->addRoute('POST', '/article/update/{id:[0-9]+}', ['Blog\Controller\ArticleController', 'update']);
    $r->addRoute('DELETE', '/article/delete/{id:[0-9]+}', ['Blog\Controller\ArticleController', 'delete']);

    $r->addRoute('GET', '/panic_buying/{id:[0-9]+}', 'Blog\Controller\PanicBuyingController');
});

$route = $dispatcher->dispatch($_SERVER['REQUEST_METHOD'], explode('?', $_SERVER['REQUEST_URI'])[0]);
switch ($route[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        echo '404 Not Found';
        break;

    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        echo '405 Method Not Allowed';
        break;

    case FastRoute\Dispatcher::FOUND:
        $controller = $route[1];
        $parameters = $route[2];

        $parameters['request'] = Request::createFromGlobals();
        // We could do $container->get($controller) but $container->call()
        // does that automatically
        /** @var \Symfony\Component\HttpFoundation\Response $response */
        $response = $container->call($controller, $parameters);
        if (!($response instanceof \Symfony\Component\HttpFoundation\Response)) {
            throw new ErrorException($controller . ' must return an instanceof \Symfony\Component\HttpFoundation\Response');
        }
        $response->send();
        /** @var \Monolog\Logger $logger */
        $logger = $container->get('logger');
        foreach ($container['db.sql.logger']->queries as $query) {
            //$logger->addDebug(json_encode($route, JSON_UNESCAPED_UNICODE), $query);
        }
        break;
}
