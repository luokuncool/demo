<?php

namespace Blog\Controller;

use DI\Annotation\Inject;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ArticleController extends BaseController
{
    /**
     * @Inject()
     * @var \Blog\Model\ArticleRepository
     */
    private $repository;

    public function post()
    {
        $this->db->insert('article', $_POST);
        return new JsonResponse(['lastInsId' => $this->db->lastInsertId()]);
    }

    public function update($id)
    {
        $affect = $this->db->update('article', array_merge($_POST, array('update_at' => (new \DateTime())->format('Y-m-d H:i:s'))), array('id' => $id));
        return new JsonResponse(['affect' => $affect]);
    }

    public function all()
    {
        return new JsonResponse($this->repository->getArticles());
    }

    public function delete($id)
    {
        try {
            $this->db->delete('article', array('id' => $id));
            return new JsonResponse(['msg' => '删除成功']);
        } catch (InvalidArgumentException $e) {
            return new JsonResponse(['msg' => $e->getMessage()]);
        }
    }

    public function get($id)
    {
        $article = $this->repository->getArticle($id);
        return new JsonResponse($article);
    }

    public function show($id, Request $request)
    {
        $data['article'] = $this->repository->getArticle($id);
        return $this->render('article.twig', $data, $request->headers->get('X-PJAX'));
    }
}
