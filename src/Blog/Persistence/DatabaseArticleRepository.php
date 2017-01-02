<?php
namespace Blog\Persistence;

use DI\Annotation\Inject;
use Doctrine\DBAL\Cache\QueryCacheProfile;
use Doctrine\DBAL\Connection;
use Blog\Model\Article;
use Blog\Model\ArticleRepository;

class DatabaseArticleRepository implements ArticleRepository
{
    /**
     * @Inject("db")
     * @var Connection
     */
    private $db;

    /**
     * @return Article[]
     */
    public function getArticles()
    {
        $qb   = $this->db->createQueryBuilder()->select('*')->from('article')->orderBy('id', 'desc');
        $stmt = $this->db->executeQuery($qb->getSQL(), [], [], new QueryCacheProfile(10, $qb->getSQL()));
        $rows = $stmt->fetchAll();
        $stmt->closeCursor();
        return $rows;
    }

    /**
     * @param string $id
     *
     * @return array
     */
    public function getArticle($id)
    {
        return $this->db->createQueryBuilder()->select('*')->from('article')->where('id=?')->setParameters(array($id))->execute()->fetch();
    }
}