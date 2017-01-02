<?php

use FastRoute\RouteCollector;

$container = require __DIR__ . '/../bootstrap.php';

$dispatcher = FastRoute\simpleDispatcher(function (RouteCollector $r) {
    $r->addRoute('GET', '/', 'Blog\Controller\HomeController');
    $r->addRoute('GET', '/article/{id}', ['Blog\Controller\ArticleController', 'show']);

    $r->addRoute('GET', '/article', ['Blog\Controller\ArticleController', 'all']);
    $r->addRoute('GET', '/article/get/{id}', ['Blog\Controller\ArticleController', 'get']);
    $r->addRoute('POST', '/article/post', ['Blog\Controller\ArticleController', 'post']);
    $r->addRoute('POST', '/article/update/{id}', ['Blog\Controller\ArticleController', 'update']);
    $r->addRoute('DELETE', '/article/delete/{id}', ['Blog\Controller\ArticleController', 'delete']);
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

        // We could do $container->get($controller) but $container->call()
        // does that automatically
        $container->call($controller, $parameters);
        /** @var \Monolog\Logger $logger */
        $logger = $container->get('logger');
        foreach ($container['db.sql.logger']->queries as $query) {
            $logger->addDebug(json_encode($route, JSON_UNESCAPED_UNICODE), $query);
        }
        break;
}
