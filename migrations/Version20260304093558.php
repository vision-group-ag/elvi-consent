<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260304093558 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create consent_event table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE consent_event (source VARCHAR(20) NOT NULL, event_type VARCHAR(20) NOT NULL, consent_version_slug VARCHAR(255) DEFAULT NULL, consent_text LONGTEXT DEFAULT NULL, ip_address VARCHAR(255) DEFAULT NULL, user_agent LONGTEXT DEFAULT NULL, metadata JSON NOT NULL, created_at DATETIME NOT NULL, id CHAR(36) NOT NULL COLLATE `ascii_bin`, customer_id CHAR(36) NOT NULL COLLATE `ascii_bin`, INDEX IDX_32B1450C9395C3F3 (customer_id), INDEX idx_consent_event_customer (customer_id, created_at), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE consent_event ADD CONSTRAINT FK_32B1450C9395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE consent_event DROP FOREIGN KEY FK_32B1450C9395C3F3');
        $this->addSql('DROP TABLE consent_event');
    }
}
