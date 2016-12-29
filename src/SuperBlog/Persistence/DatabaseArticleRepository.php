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
        $rows = $this->db->createQueryBuilder()->select('*')->from('article')->execute()->fetchAll();
        $articles = array();
        foreach ($rows as $row) {
            $articles[] = new Article($row['id'], $row['title'], $row['content']);
        }
        return $articles;
    }

    /**
     * @param string $id
     *
     * @return Article
     */
    public function getArticle($id)
    {
        $row = $this->db->createQueryBuilder()->select('*')->from('article')->where('id=?')->setParameters(array($id))->execute()->fetch();
        return new Article($row['id'], $row['title'], $row['content']);
    }
}