<?php

use function DI\object;
use SuperBlog\Model\ArticleRepository;
use SuperBlog\Persistence\InMemoryArticleRepository;

return [
    // Bind an interface to an implementation
    ArticleRepository::class => object(\SuperBlog\Persistence\DatabaseArticleRepository::class),

    // Configure Twig
    Twig_Environment::class  => function () {
        $loader = new Twig_Loader_Filesystem(__DIR__ . '/../src/SuperBlog/Views');
        return new Twig_Environment($loader);
    },

    'db.url' => 'sqlite:///'.__DIR__.'/db.sqlite',

    \Doctrine\DBAL\Connection::class => function (\DI\Container $container) {
        $config = new \Doctrine\DBAL\Configuration();

        $connectionParams = array(
            'url' => $container->get('db.url'),
        );

        return \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);
    }
];
