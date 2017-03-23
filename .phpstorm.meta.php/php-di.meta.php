<?php
namespace PHPSTORM_META {
    override(
        \DI\Container::get(0),
        map([
            ""                               => "@",
            "Blog\\Model\\ArticleRepository" => \Blog\Persistence\DatabaseArticleRepository::class,
            "twig"                           => \Twig_Environment::class,
            "predis"                         => \Predis\Client::class,
            "logger"                         => \Monolog\Logger::class,
            "db"                             => \Doctrine\DBAL\Connection::class,
            "db.sql.logger"                  => \Doctrine\DBAL\Logging\DebugStack::class,
            "client_storage"                 => \Blog\OAuth2Storage\ClientStorage::class,
        ]));
    override(
        \app(0),
        map([
            ""                               => "@",
            "Blog\\Model\\ArticleRepository" => \Blog\Persistence\DatabaseArticleRepository::class,
            "twig"                           => \Twig_Environment::class,
            "predis"                         => \Predis\Client::class,
            "logger"                         => \Monolog\Logger::class,
            "db"                             => \Doctrine\DBAL\Connection::class,
            "db.sql.logger"                  => \Doctrine\DBAL\Logging\DebugStack::class,
            "client_storage"                 => \Blog\OAuth2Storage\ClientStorage::class,
        ]));
}
