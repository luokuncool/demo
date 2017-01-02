<?php
namespace Blog\Controller;

use DI\Annotation\Inject;
use Monolog\Logger;

class PanicBuyingController extends BaseController
{
    /**
     * @Inject("logger")
     * @var Logger
     */
    private $logger;

    public function __invoke($id)
    {
        $row = $this->db->createQueryBuilder()->select('stock')->from('goods')->where('id=?')->setParameters(array($id))->execute()->fetch();
        $stock = $row['stock'];
        //$stock = $this->predis->decr("stock$id");
        $this->logger->addError($stock);
        //$this->db->beginTransaction();
        if ($stock <= 0) {
            echo 'sold out！';
            return;
        }
        $this->db->executeUpdate('UPDATE goods SET stock = stock - 1 WHERE id = ?', array($id));
        //$this->db->commit();
    }
}