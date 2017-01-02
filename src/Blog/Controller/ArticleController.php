<?php

namespace Blog\Controller;

use DI\Annotation\Inject;
use InvalidArgumentException;

class ArticleController extends BaseController
{
    /**
     * @Inject()
     * @var \Blog\Model\ArticleRepository
     */
    private $repository;

    /**
     * @Inject("predis")
     * @var \Predis\Client
     */
    private $predis;

    public function post()
    {
        $this->db->insert('article', $_POST);
        $this->json(array('lastInsId' => $this->db->lastInsertId()));
    }

    public function update($id)
    {
        $affect = $this->db->update('article', array_merge($_POST, array('update_at' => (new \DateTime())->format('Y-m-d H:i:s'))), array('id' => $id));
        $this->json(array('affect' => $affect));
    }

    public function all()
    {
        $this->json($this->repository->getArticles());
    }

    public function delete($id)
    {
        try {
            $this->db->delete('article', array('id' => $id));
            $this->json(array('msg' => '删除成功'));
            return;
        } catch (InvalidArgumentException $e) {
            $this->json(array('msg' => $e->getMessage()));
        }
    }

    public function get($id)
    {
        $article = $this->repository->getArticle($id);
        $this->json($article);
    }

    public function show($id)
    {
        $article = $this->repository->getArticle($id);

        echo $this->render('article.twig', [
            'article' => $article,
        ]);
    }
}
