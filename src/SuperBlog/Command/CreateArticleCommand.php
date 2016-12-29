<?php
namespace SuperBlog\Command;

use DI\Annotation\Inject;
use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Output\OutputInterface;

class CreateArticleCommand
{
    /**
     * @Inject()
     * @var Connection
     */
    private $db;

    public function __invoke(OutputInterface $output)
    {
        $this->db->insert('article', array('title' => 'title', 'content' => 'content'));
        $output->writeln("<info>最后插入id {$this->db->lastInsertId()}</info>");
    }

}