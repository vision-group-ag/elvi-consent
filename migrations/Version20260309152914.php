<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260309152914 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'add data import status and timestamp to customer table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE customer ADD data_import_status VARCHAR(255) DEFAULT NULL, ADD data_imported_at DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE customer DROP data_import_status, DROP data_imported_at');
    }
}
