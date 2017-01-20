<?php
namespace Blog\Provider;

use Doctrine\DBAL\Migrations\Provider\SchemaProviderInterface;
use Doctrine\DBAL\Schema\Schema;

class CustomSchemaProvider implements SchemaProviderInterface
{
    /**
     * Create the schema to which the database should be migrated.
     *
     * @return  \Doctrine\DBAL\Schema\Schema
     */
    public function createSchema()
    {
        $schema = new Schema();
        $this->createArticleTable($schema);
        $this->createUserTable($schema);
        $this->createGoodsTable($schema);
        $this->oauthTables($schema);
        return $schema;
    }

    private function createUserTable(Schema $schema)
    {
        $table = $schema->createTable('user');
        $table->addColumn('id', 'integer', array('unsigned' => true, 'autoincrement' => true));
        $table->addColumn('email', 'string', array('length' => 255, 'comment' => '电子邮箱'));
        $table->addColumn('password', 'string', array('length' => 255, 'comment' => '密码'));
        $table->addColumn('create_at', 'datetime', array('comment' => '创建时间'));
        $table->addColumn('update_at', 'datetime', array('comment' => '更新时间'));
        $table->setPrimaryKey(array('id'));
    }

    private function createArticleTable(Schema $schema)
    {
        $table = $schema->createTable('article');
        $table->addColumn('id', 'integer', array('unsigned' => true, 'autoincrement' => true));
        $table->addColumn('title', 'string', array('length' => 255, 'comment' => '标题'));
        $table->addColumn('tags', 'string', array('length' => 1024, 'comment' => '标签'));
        $table->addColumn('content', 'text', array('comment' => '内容'));
        $table->addColumn('create_at', 'datetime', array('comment' => '创建时间'));
        $table->addColumn('update_at', 'datetime', array('comment' => '更新时间'));
        $table->setPrimaryKey(array('id'));
    }

    private function createGoodsTable(Schema $schema)
    {
        $table = $schema->createTable('goods');
        $table->addColumn('id', 'integer', array('unsigned' => true, 'autoincrement' => true));
        $table->addColumn('title', 'string', array('length' => 255, 'comment' => '商品标题'));
        $table->addColumn('stock', 'integer', array('unsigned' => true, 'comment' => '库存'));
        $table->addColumn('content', 'text', array('comment' => '商品详情'));
        $table->addColumn('create_at', 'datetime', array('comment' => '创建时间'));
        $table->addColumn('update_at', 'datetime', array('comment' => '更新时间'));
        $table->setPrimaryKey(array('id'));
    }

    private function oauthTables(Schema $schema)
    {
        $client = $schema->createTable('oauth_clients');
        $client->addColumn('id', 'integer', array('autoincrement' => true));
        $client->addColumn('client_identifier', 'string', array('length' => 50, 'unique' => true));
        $client->addColumn('client_secret', 'string', array('length' => 20));
        $client->addColumn('redirect_uri', 'string', array('length' => 255));
        $client->setPrimaryKey(array('id'));

        $user = $schema->createTable('oauth_users');
        $user->addColumn('id', 'integer', array('autoincrement' => true));
        $user->addColumn('email', 'string', array('unique' => true));
        $user->addColumn('password', 'string');
        $user->addIndex(array('email'), 'email_index');
        $user->setPrimaryKey(array('id'));

        $accessToken = $schema->createTable('oauth_access_tokens');
        $accessToken->addColumn('id', 'integer', array('autoincrement' => true));
        $accessToken->addColumn('token', 'string', array('unique' => true, 'length' => 40));
        $accessToken->addColumn('client_id', 'integer');
        $accessToken->addColumn('user_id', 'integer', array('nullable' => true));
        $accessToken->addColumn('expires', 'datetime');
        $accessToken->addColumn('scope', 'string', array('length' => 50, 'nullable' => true));
        $accessToken->setPrimaryKey(array('id'));
        $accessToken->addForeignKeyConstraint($client, array('client_id'), array('id'));
        $accessToken->addForeignKeyConstraint($user, array('user_id'), array('id'));

        $authorizationCode = $schema->createTable('oauth_authorization_codes');
        $authorizationCode->addColumn('id', 'integer', array('autoincrement' => true));
        $authorizationCode->addColumn('code', 'string', array('length' => 40, 'unique' => true));
        $authorizationCode->addColumn('client_id', 'integer');
        $authorizationCode->addColumn('user_id', 'integer', array('nullable' => true));
        $authorizationCode->addColumn('expires', 'datetime');
        $authorizationCode->addColumn('redirect_uri', 'string', array('length' => 200));
        $authorizationCode->addColumn('scope', 'string', array('lenght' => 50, 'nullable' => true));
        $authorizationCode->setPrimaryKey(array('id'));
        $authorizationCode->addForeignKeyConstraint($client, array('client_id'), array('id'));
        $authorizationCode->addForeignKeyConstraint($user, array('user_id'), array('id'));

        $refreshToken = $schema->createTable('oauth_refresh_tokens');
        $refreshToken->addColumn('id', 'integer', array('autoincrement' => true));
        $refreshToken->addColumn('refresh_token', 'string', array('length' => 40, 'unique' => true));
        $refreshToken->addColumn('client_id', 'integer');
        $refreshToken->addColumn('user_id', 'integer', array('nullable' => true));
        $refreshToken->addColumn('expires', 'datetime');
        $refreshToken->addColumn('scope', 'string', array('length' => 50));
        $refreshToken->setPrimaryKey(array('id'));
        $refreshToken->addForeignKeyConstraint($client, array('client_id'), array('id'));
        $refreshToken->addForeignKeyConstraint($user, array('user_id'), array('id'));
    }
}