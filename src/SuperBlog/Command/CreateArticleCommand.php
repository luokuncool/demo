<?php
namespace SuperBlog\Command;

use DI\Annotation\Inject;
use Doctrine\DBAL\Connection;
use Silly\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateArticleCommand extends Command
{
    /**
     * @Inject()
     * @var Connection
     */
    private $db;

    protected function configure()
    {
        $this
            ->setName('app:create_article')
            ->setDescription('create some default articles');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->db->insert('article', ['title' => 'title', 'content' => 'content', 'tags' => 'php']);
        $output->writeln("<info>最后插入id {$this->db->lastInsertId()}</info>");
    }

}