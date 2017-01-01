<?php

namespace Blog\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170101124956 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TEMPORARY TABLE __temp__article AS SELECT id, title, tags, content, create_at, update_at FROM article');
        $this->addSql('DROP TABLE article');
        $this->addSql('CREATE TABLE article (id INTEGER NOT NULL, title VARCHAR(255) NOT NULL, tags VARCHAR(1024) NOT NULL, content CLOB NOT NULL, create_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, update_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO article (id, title, tags, content, create_at, update_at) SELECT id, title, tags, content, create_at, update_at FROM __temp__article');
        $this->addSql('DROP TABLE __temp__article');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, email, password, create_at, update_at FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, create_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, update_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO user (id, email, password, create_at, update_at) SELECT id, email, password, create_at, update_at FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TEMPORARY TABLE __temp__article AS SELECT id, title, tags, content, create_at, update_at FROM article');
        $this->addSql('DROP TABLE article');
        $this->addSql('CREATE TABLE article (id INTEGER NOT NULL, title VARCHAR(255) NOT NULL COLLATE BINARY, tags VARCHAR(1024) NOT NULL COLLATE BINARY, content CLOB NOT NULL COLLATE BINARY, create_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, update_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO article (id, title, tags, content, create_at, update_at) SELECT id, title, tags, content, create_at, update_at FROM __temp__article');
        $this->addSql('DROP TABLE __temp__article');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, email, password, create_at, update_at FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER NOT NULL, email VARCHAR(255) NOT NULL COLLATE BINARY, password VARCHAR(255) NOT NULL COLLATE BINARY, create_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, update_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO user (id, email, password, create_at, update_at) SELECT id, email, password, create_at, update_at FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
    }
}
