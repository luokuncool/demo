<?php

namespace SuperBlog\Controller;

use DI\Annotation\Inject;
use InvalidArgumentException;
use SuperBlog\Model\ArticleRepository;

class ArticleController extends Controller
{
    /**
     * @Inject()
     * @var ArticleRepository
     */
    private $repository;

    public function post()
    {
        $this->db->insert('article', $_POST);
        $this->jsonResponse(array('lastInsId' => $this->db->lastInsertId()));
    }

    public function update($id)
    {
        $affect = $this->db->update('article', $_POST, array('id' => $id));
        $this->jsonResponse(array('affect' => $affect));
    }

    public function all()
    {
        $this->jsonResponse($this->repository->getArticles());
    }

    public function delete($id)
    {
        try {
            $this->db->delete('article', array('id' => $id));
            $this->jsonResponse(array('msg' => '删除成功'));
            return;
        } catch (InvalidArgumentException $e) {
            $this->jsonResponse(array('msg' => $e->getMessage()));
        }
    }

    public function get($id)
    {
        $article = $this->repository->getArticle($id);
        $this->jsonResponse($article);
    }

    public function show($id)
    {
        $article = $this->repository->getArticle($id);

        echo $this->render('article.twig', [
            'article' => $article,
        ]);
    }
}
