<?php

namespace Blog\Controller;

use DI\Annotation\Inject;
use Blog\Model\ArticleRepository;
use Doctrine\DBAL\Cache\QueryCacheProfile;

class HomeController extends Controller
{
    /**
     * @Inject()
     * @var ArticleRepository
     */
    private $repository;

    /**
     * @Inject("db.sql.logger")
     */
    private $sqlLogger;

    /**
     * Example of an invokable class, i.e. a class that has an __invoke() method.
     *
     * @see http://php.net/manual/en/language.oop5.magic.php#object.invoke
     */
    public function __invoke()
    {
        $stmt = $this->db->executeQuery('select * from article limit 1', array(), array(), new QueryCacheProfile(3, 'query'));
        $rows = $stmt->fetchAll();
        $stmt->closeCursor();

        $this->repository->getArticles();

        print_r($this->sqlLogger->queries);


        echo $this->render('home.twig', [
            'articles' => $this->repository->getArticles(),
        ]);


    }
}
