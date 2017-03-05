<?php

namespace Blog\Controller;

use DI\Annotation\Inject;
use Blog\Model\ArticleRepository;
use DI\Container;
use Doctrine\DBAL\Cache\QueryCacheProfile;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends BaseController
{
    /**
     * @Inject()
     * @var ArticleRepository
     */
    private $repository;

    /**
     * @var Container
     */
    private $container;

    /**
     * Example of an invokable class, i.e. a class that has an __invoke() method.
     *
     * @see http://php.net/manual/en/language.oop5.magic.php#object.invoke
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function __invoke(Request $request)
    {
        $data['articles'] = $this->repository->getArticles();
        return $this->render('home.twig', $data, $request->headers->get('X-PJAX'));
    }
}
