<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260310101045 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'add brand column to customer table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE customer ADD brand VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE customer CHANGE sales_channel sales_channel VARCHAR(20) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE customer DROP brand');
        $this->addSql('ALTER TABLE customer CHANGE sales_channel sales_channel VARCHAR(20) NOT NULL');
    }
}
