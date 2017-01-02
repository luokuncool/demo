#!/usr/bin/env php
<?php

use Blog\Model\ArticleRepository;
use Symfony\Component\Console\Output\OutputInterface;

/** @var \DI\Container $container */
$container = require __DIR__ . '/../bootstrap.php';

$app = new Silly\Application();

// Silly will use PHP-DI for dependency injection based on type-hints
$app->useContainer($container, $injectWithTypeHint = true);

// Show the article list
// This command is implemented using a closure. We can still benefit from dependency
// injection in the parameters of the closure because Silly + PHP-DI is awesome.
$app->command('articles', function (OutputInterface $output, ArticleRepository $repository) {
    $output->writeln('<comment>Here are the articles in the blog:</comment>');

    $articles = $repository->getArticles();

    foreach ($articles as $article) {
        $output->writeln(sprintf(
            'Article #%d: <info>%s</info>',
            $article['id'],
            $article['title']
        ));
    }
});

// Show an article
// For this command we provide an invokable class instead of a closure
// That allows to use dependency injection in the constructor
$app->add($container->get(Blog\Command\CreateArticleCommand::class));

$app->setHelperSet(
    new \Symfony\Component\Console\Helper\HelperSet([
        'db'     => new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($container->get("db")),
        'dialog' => new \Symfony\Component\Console\Helper\QuestionHelper(),
    ])
);

$app->addCommands([
    new \Doctrine\DBAL\Migrations\Tools\Console\Command\DiffCommand(new \Blog\Provider\CustomSchemaProvider()),
    new \Doctrine\DBAL\Migrations\Tools\Console\Command\ExecuteCommand(),
    new \Doctrine\DBAL\Migrations\Tools\Console\Command\GenerateCommand(),
    new \Doctrine\DBAL\Migrations\Tools\Console\Command\MigrateCommand(),
    new \Doctrine\DBAL\Migrations\Tools\Console\Command\StatusCommand(),
    new \Doctrine\DBAL\Migrations\Tools\Console\Command\VersionCommand()
]);

$app->run();
