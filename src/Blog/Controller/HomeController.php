<?php

namespace Blog\Controller;

use DI\Annotation\Inject;
use Blog\Model\ArticleRepository;
use Doctrine\DBAL\Cache\QueryCacheProfile;

class HomeController extends BaseController
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
        $qb = $this->db->createQueryBuilder()->select('*')->from('article')->setMaxResults(1);

        $stmt = $this->db->executeQuery($qb->getSQL(), array(), array(), new QueryCacheProfile(30, $qb->getSQL()));
        $rows = $stmt->fetchAll();
        $stmt->closeCursor();

        echo $this->render('home.twig', [
            'articles' => $this->repository->getArticles(),
        ]);
    }
}
