<?php
namespace Blog\Command;

use DI\Annotation\Inject;
use Doctrine\DBAL\Connection;
use Silly\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateClientCommand extends Command
{
    /**
     * @Inject("db")
     * @var Connection
     */
    private $db;

    protected function configure()
    {
        $this
            ->setName('app:create_client')
            ->setDescription('create some default articles');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->db->insert('oauth_clients', ['client_identifier' => 'client_identifier', 'client_secret' => sha1('client_secret'), 'redirect_uri' => '/']);
        $output->writeln("<info>最后插入id {$this->db->lastInsertId()}</info>");
    }

}