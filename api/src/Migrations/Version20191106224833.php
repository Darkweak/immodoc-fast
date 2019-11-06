<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20191106224833 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Initialize database';
    }

    public function up(Schema $schema) : void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE EXTENSION IF NOT EXISTS "uuid-ossp"');
        $this->addSql('CREATE TABLE file (id UUID NOT NULL, description TEXT NOT NULL, name VARCHAR(255) NOT NULL, path VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE users (id UUID NOT NULL, password VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE payment (id UUID NOT NULL, transaction_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, amount VARCHAR(255) NOT NULL, intent_id VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE crypted_file (id UUID NOT NULL, payment_id UUID DEFAULT NULL, file_id UUID DEFAULT NULL, used BOOLEAN DEFAULT \'false\' NOT NULL, token VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_DC1FE504C3A3BB ON crypted_file (payment_id)');
        $this->addSql('CREATE INDEX IDX_DC1FE5093CB796C ON crypted_file (file_id)');
        $this->addSql('ALTER TABLE crypted_file ADD CONSTRAINT FK_DC1FE504C3A3BB FOREIGN KEY (payment_id) REFERENCES payment (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE crypted_file ADD CONSTRAINT FK_DC1FE5093CB796C FOREIGN KEY (file_id) REFERENCES file (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA IF NOT EXISTS public');
        $this->addSql('ALTER TABLE crypted_file DROP CONSTRAINT FK_DC1FE5093CB796C');
        $this->addSql('ALTER TABLE crypted_file DROP CONSTRAINT FK_DC1FE504C3A3BB');
        $this->addSql('DROP TABLE file');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE payment');
        $this->addSql('DROP TABLE crypted_file');
    }
}
