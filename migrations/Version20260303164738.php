<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260303164738 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create customer table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE customer (id CHAR(36) NOT NULL COLLATE `ascii_bin`, external_identifier VARCHAR(255) NOT NULL, sales_channel VARCHAR(20) NOT NULL, raw_data JSON NOT NULL, consent_status VARCHAR(20) NOT NULL, decision_date DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, INDEX idx_customer_external_channel (external_identifier, sales_channel), INDEX idx_customer_consent_status (consent_status), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE TABLE app_demo_entity (id VARCHAR(36) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin` COMMENT \'UUID\', name VARCHAR(191) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, is_public TINYINT NOT NULL, UNIQUE INDEX UNIQ_2B3115AF5E237E06 (name), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
    }
}
