<?php

use function DI\object;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Logging\DebugStack;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use SuperBlog\Model\ArticleRepository;
use SuperBlog\Persistence\InMemoryArticleRepository;
use SuperBlog\Provider\CustomSchemaProvider;

return [
    // Bind an interface to an implementation
    ArticleRepository::class => object(\SuperBlog\Persistence\DatabaseArticleRepository::class),

    // Configure Twig
    Twig_Environment::class  => function () {
        $loader = new Twig_Loader_Filesystem(__DIR__ . '/../src/SuperBlog/Views');
        return new Twig_Environment($loader);
    },

    'db.url' => 'sqlite:///' . __DIR__ . '/db.sqlite3',

    //'db.url' => 'mysql://root:root@127.0.0.1/di-demo?charset=utf8',

    \Predis\Client::class => function (\DI\Container $container) {
        $predis = new \Predis\Client();
        return $predis;
    },

    'app.logger' => function () {
        $logger = new \Monolog\Logger('app');
        $logger->pushHandler(new StreamHandler(__DIR__ . '/../var/log/'.date('Y-m-d').'.log', Logger::WARNING));
        return $logger;
    },

    Connection::class => function (\DI\Container $container) {
        $config           = new \Doctrine\DBAL\Configuration();
        $connectionParams = ['url' => $container->get('db.url')];

        $sqlLoger = new DebugStack();
        $config->setSQLLogger($sqlLoger);

        return \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);
    }
];
