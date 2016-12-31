<?php

namespace SuperBlog\Controller;

use DI\Annotation\Inject;
use SuperBlog\Model\ArticleRepository;
use Twig_Environment;

class HomeController
{
    /**
     * @Inject()
     * @var ArticleRepository
     */
    private $repository;

    /**
     * @Inject()
     * @var Twig_Environment
     */
    private $twig;

    /**
     * Example of an invokable class, i.e. a class that has an __invoke() method.
     *
     * @see http://php.net/manual/en/language.oop5.magic.php#object.invoke
     */
    public function __invoke()
    {
        echo $this->twig->render('home.twig', [
            'articles' => $this->repository->getArticles(),
        ]);
    }
}
