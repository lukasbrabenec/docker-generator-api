<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210126155003 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE compose_format_version (id INT AUTO_INCREMENT NOT NULL, compose_version NUMERIC(3, 1) NOT NULL, docker_engine_release VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE environment (id INT AUTO_INCREMENT NOT NULL, image_version_id INT NOT NULL, code VARCHAR(128) NOT NULL, default_value VARCHAR(256) DEFAULT NULL, required TINYINT(1) DEFAULT \'0\' NOT NULL, hidden TINYINT(1) DEFAULT \'0\' NOT NULL, INDEX IDX_4626DE2227623033 (image_version_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE extension (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(32) NOT NULL, special TINYINT(1) DEFAULT \'0\', custom_command VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(128) NOT NULL, code VARCHAR(256) NOT NULL, dockerfile_location VARCHAR(128) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image_version (id INT AUTO_INCREMENT NOT NULL, image_id INT NOT NULL, version VARCHAR(128) NOT NULL, INDEX IDX_2A0C841F3DA5256D (image_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image_version_extension (id INT AUTO_INCREMENT NOT NULL, image_version_id INT NOT NULL, extension_id INT NOT NULL, config VARCHAR(128) DEFAULT NULL, INDEX IDX_35564D127623033 (image_version_id), INDEX IDX_35564D1812D5EB (extension_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE port (id INT AUTO_INCREMENT NOT NULL, image_version_id INT NOT NULL, inward INT NOT NULL, outward INT NOT NULL, INDEX IDX_43915DCC27623033 (image_version_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE restart_type (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(32) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE volume (id INT AUTO_INCREMENT NOT NULL, image_version_id INT NOT NULL, host_path VARCHAR(255) NOT NULL, container_path VARCHAR(255) NOT NULL, INDEX IDX_B99ACDDE27623033 (image_version_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE environment ADD CONSTRAINT FK_4626DE2227623033 FOREIGN KEY (image_version_id) REFERENCES image_version (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE image_version ADD CONSTRAINT FK_2A0C841F3DA5256D FOREIGN KEY (image_id) REFERENCES image (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE image_version_extension ADD CONSTRAINT FK_35564D127623033 FOREIGN KEY (image_version_id) REFERENCES image_version (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE image_version_extension ADD CONSTRAINT FK_35564D1812D5EB FOREIGN KEY (extension_id) REFERENCES extension (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE port ADD CONSTRAINT FK_43915DCC27623033 FOREIGN KEY (image_version_id) REFERENCES image_version (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE volume ADD CONSTRAINT FK_B99ACDDE27623033 FOREIGN KEY (image_version_id) REFERENCES image_version (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE image_version_extension DROP FOREIGN KEY FK_35564D1812D5EB');
        $this->addSql('ALTER TABLE image_version DROP FOREIGN KEY FK_2A0C841F3DA5256D');
        $this->addSql('ALTER TABLE environment DROP FOREIGN KEY FK_4626DE2227623033');
        $this->addSql('ALTER TABLE image_version_extension DROP FOREIGN KEY FK_35564D127623033');
        $this->addSql('ALTER TABLE port DROP FOREIGN KEY FK_43915DCC27623033');
        $this->addSql('ALTER TABLE volume DROP FOREIGN KEY FK_B99ACDDE27623033');
        $this->addSql('DROP TABLE compose_format_version');
        $this->addSql('DROP TABLE environment');
        $this->addSql('DROP TABLE extension');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE image_version');
        $this->addSql('DROP TABLE image_version_extension');
        $this->addSql('DROP TABLE port');
        $this->addSql('DROP TABLE restart_type');
        $this->addSql('DROP TABLE volume');
    }
}
