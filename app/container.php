<?php

use function DI\object;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Blog\Model\ArticleRepository;

return [
    // Bind an interface to an implementation
    ArticleRepository::class => object(\Blog\Persistence\DatabaseArticleRepository::class),

    // Configure Twig
    'twig'                   => function () {
        $loader = new Twig_Loader_Filesystem(__DIR__ . '/../src/Blog/Views');
        return new Twig_Environment($loader);
    },

    'predis' => function (\DI\Container $container) {
        $predis = new \Predis\Client();
        return $predis;
    },

    'logger' => function () {
        $logger = new \Monolog\Logger('app');
        $logger->pushHandler(new StreamHandler(__DIR__ . '/../var/log/' . date('Y-m-d') . '.log', Logger::WARNING));
        return $logger;
    },

    'db.url' => 'sqlite:///' . __DIR__ . '/db.sqlite3',
    //'db.url' => 'mysql://root:root@127.0.0.1/di-demo?charset=utf8',

    'db' => function (\DI\Container $container) {
        $config           = new \Doctrine\DBAL\Configuration();
        $connectionParams = ['url' => $container->get('db.url')];

        $sqlLogger = $container->get('db.sql.logger');
        $config->setSQLLogger($sqlLogger);

        //$cache = new \Doctrine\Common\Cache\PredisCache($container->get('predis'));
        $cache = new \Doctrine\Common\Cache\FilesystemCache(__DIR__ . '/../var/cache/');
        $config->setResultCacheImpl($cache);

        return \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);
    },

    'db.sql.logger' => object(\Doctrine\DBAL\Logging\DebugStack::class),
];
