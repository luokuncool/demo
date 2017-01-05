<?php

use DI\Container;
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
        //return new Twig_Environment($loader, ['cache' => __DIR__ . '/../var/cache/twig']);
        return new Twig_Environment($loader);
    },

    'predis' => object(\Predis\Client::class),

    'logger' => function (Container $container) {
        $logger = new \Monolog\Logger('app');
        $logger->pushHandler(new StreamHandler(__DIR__ . '/../var/log/' . date('Y-m-d') . '.log', Logger::DEBUG));
        //$logger->pushHandler(new \Monolog\Handler\RedisHandler($container['predis'], 'logger'));
        return $logger;
    },

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
