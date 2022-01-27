<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version0001ApiLog extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<SQL
            CREATE TABLE `api_log` (
              `id` bigint(20) NOT NULL AUTO_INCREMENT,
              `status` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
              `headers` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '(DC2Type:json)',
              `request` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
              `response` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
              `status_code` int(11) DEFAULT NULL,
              `uri` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
              `query_string` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
              `method` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
              `host` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
              `port` int(11) DEFAULT NULL,
              `additional_info` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '(DC2Type:json)',
              `created_at` datetime NOT NULL COMMENT '(DC2Type:datetimetz_immutable)',
              PRIMARY KEY (`id`),
              KEY `IDX_CREATED_AT` (`created_at`),
              KEY `IDX_URI` (`uri`(768))
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        SQL);
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE api_log');
    }

    public function isTransactional(): bool
    {
        return false;
    }
}
