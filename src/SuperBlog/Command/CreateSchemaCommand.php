<?php
namespace SuperBlog\Command;

use DI\Annotation\Inject;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Schema;
use Symfony\Component\Console\Output\OutputInterface;

class CreateSchemaCommand
{
    /**
     * @Inject()
     * @var Schema
     */
    private $schema;

    /**
     * @Inject()
     * @var Connection
     */
    private $db;

    public function __invoke(OutputInterface $output)
    {
        if (!$this->db->getSchemaManager()->tablesExist(array('article'))) {
            $article = $this->schema->createTable('article');
            $article->addColumn('id', 'integer', array('unsigned' => true, 'autoincrement' => true));
            $article->addColumn('title', 'string', array('length' => 255));
            $article->addColumn('content', 'text');
            $article->setPrimaryKey(array('id'));
        }

        $sqls = $this->schema->toSql($this->db->getDatabasePlatform());
        foreach ($sqls as $sql) {
            $output->writeln('<info>' . $sql . '</info>');
            $this->db->executeQuery($sql);
        }
    }
}