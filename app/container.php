<?php

use Blog\Container;
use function DI\object;
use Doctrine\Common\Cache\FilesystemCache;
use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Blog\Model\ArticleRepository;

return [
    // Bind an interface to an implementation
    ArticleRepository::class => object(\Blog\Persistence\DatabaseArticleRepository::class),

    // Configure Twig
    'twig'                   => function () {
        $loader = new Twig_Loader_Filesystem(__DIR__ . '/../src/Blog/Views');
        return new Twig_Environment($loader, ['cache' => __DIR__ . '/../var/cache/twig']);
    },

    'predis' => function (Container $container) {
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

    'db' => function (Container $container) {
        $config           = new Configuration();
        $connectionParams = ['url' => $container['db.url']];

        $sqlLogger = $container['db.sql.logger'];
        $config->setSQLLogger($sqlLogger);

        //$cache = new \Doctrine\Common\Cache\PredisCache($container['predis']);
        $cache = new FilesystemCache(__DIR__ . '/../var/cache/doctrine/');
        $config->setResultCacheImpl($cache);

        return DriverManager::getConnection($connectionParams, $config);
    },

    'db.sql.logger' => object(\Doctrine\DBAL\Logging\DebugStack::class),
];
