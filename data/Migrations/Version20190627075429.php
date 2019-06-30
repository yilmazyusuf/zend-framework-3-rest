<?php declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Zend\Crypt\Password\Bcrypt;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190627075429 extends AbstractMigration
{
    /**
     * Returns the description of this migration.
     */
    public function getDescription()
    {
        $description = 'This is the initial migration which creates user and post tables.';
        return $description;
    }

    public function up(Schema $schema): void
    {
        // Post Table
        $table = $schema->createTable('post');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('title', 'string', ['notnull' => true, 'length' => 100]);
        $table->addColumn('content', 'text', ['notnull' => true]);
        $table->addColumn('status', 'smallint', ['notnull' => true,'length' => 3]);
        $table->addColumn('date_created', 'datetime', ['notnull' => true]);
        $table->setPrimaryKey(['id']);
        $table->addOption('engine', 'InnoDB');

        //User Table
        $table = $schema->createTable('user');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('email', 'string', ['notnull' => true, 'length' => 50]);
        $table->addColumn('full_name', 'string', ['notnull' => true, 'length' => 30]);
        $table->addColumn('password', 'string', ['notnull' => true]);
        $table->addColumn('status', 'smallint', ['notnull' => true,'length' => 3]);
        $table->addColumn('date_created', 'datetime', ['notnull' => true]);
        $table->setPrimaryKey(['id']);
        $table->addOption('engine', 'InnoDB');


    }


    /**
     * Fake User Data
     *
     * @param Schema $schema
     * @throws \Doctrine\DBAL\DBALException
     */
    public function postUp(Schema $schema)
    {
        //User
        $bcrypt = new Bcrypt();
        $password = $bcrypt->create('123456');
        $this->connection->insert('user',[
            'email' => 'y.yilmaz@hotmail.com.tr',
            'full_name' => 'yusuf.yilmaz',
            'password' => $password,
            'status' => 1,
            'date_created' => '2019-01-01',
        ]);



        $this->connection->insert('post',[
            'title' => 'Coming Soon!',
            'content' => 'A community-supported, open source continuation of Zend Framework.',
            'status' => 1,
            'date_created' => '2019-01-01',
        ]);

        parent::postUp($schema);
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $schema->dropTable('post');
        $schema->dropTable('user');

    }
}
