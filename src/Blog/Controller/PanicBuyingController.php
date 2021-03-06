<?php
namespace Blog\Controller;

use DI\Annotation\Inject;
use Monolog\Logger;
use Symfony\Component\HttpFoundation\Response;

class PanicBuyingController extends BaseController
{
    /**
     * @Inject("logger")
     * @var Logger
     */
    private $logger;

    public function __invoke($id)
    {
        /*$row = $this->db->createQueryBuilder()->select('stock')->from('goods')->where('id=?')->setParameters(array($id))->execute()->fetch();
        $stock = $row['stock'];
        */
        $stock = $this->predis->decr("stock$id") + 1;
        $this->logger->addError($stock);
        $this->db->beginTransaction();
        if ($stock <= 0) {
            return new Response('sold out！');
        }
        $this->db->executeUpdate('UPDATE goods SET stock = stock - 1 WHERE id = ?', array($id));
        $this->db->commit();
        return new Response('buy success!');
    }
}