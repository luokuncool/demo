<?php

namespace Blog\Controller;

use DI\Annotation\Inject;
use Blog\Model\ArticleRepository;

class HomeController extends Controller
{
    /**
     * @Inject()
     * @var ArticleRepository
     */
    private $repository;

    /**
     * Example of an invokable class, i.e. a class that has an __invoke() method.
     *
     * @see http://php.net/manual/en/language.oop5.magic.php#object.invoke
     */
    public function __invoke()
    {
        echo $this->render('home.twig', [
            'articles' => $this->repository->getArticles(),
        ]);
    }
}
