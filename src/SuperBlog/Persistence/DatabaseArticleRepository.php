<?php
namespace SuperBlog\Persistence;

use DI\Annotation\Inject;
use Doctrine\DBAL\Connection;
use SuperBlog\Model\Article;
use SuperBlog\Model\ArticleRepository;

class DatabaseArticleRepository implements ArticleRepository
{

    /**
     * @Inject()
     * @var Connection
     */
    private $db;

    /**
     * @return Article[]
     */
    public function getArticles()
    {
        return $this->db->createQueryBuilder()->select('*')->from('article')->execute()->fetchAll();
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