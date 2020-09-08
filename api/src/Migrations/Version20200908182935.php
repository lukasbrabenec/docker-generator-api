<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200908182935 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE extension (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(32) NOT NULL, special TINYINT(1) DEFAULT \'0\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `group` (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(32) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, group_id INT NOT NULL, name VARCHAR(128) NOT NULL, code VARCHAR(256) NOT NULL, dockerfile_location VARCHAR(128) DEFAULT NULL, INDEX IDX_C53D045FFE54D947 (group_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image_version (id INT AUTO_INCREMENT NOT NULL, image_id INT NOT NULL, version VARCHAR(128) NOT NULL, INDEX IDX_2A0C841F3DA5256D (image_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image_environment (id INT AUTO_INCREMENT NOT NULL, image_version_id INT NOT NULL, code VARCHAR(128) NOT NULL, default_value VARCHAR(256) DEFAULT NULL, required TINYINT(1) DEFAULT \'0\' NOT NULL, hidden TINYINT(1) DEFAULT \'0\' NOT NULL, INDEX IDX_89F095AC27623033 (image_version_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image_version_extension (id INT AUTO_INCREMENT NOT NULL, image_version_id INT NOT NULL, extension_id INT NOT NULL, config VARCHAR(128) DEFAULT NULL, INDEX IDX_35564D127623033 (image_version_id), INDEX IDX_35564D1812D5EB (extension_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image_volume (id INT AUTO_INCREMENT NOT NULL, image_version_id INT NOT NULL, host_path VARCHAR(255) NOT NULL, container_path VARCHAR(255) NOT NULL, INDEX IDX_1687931627623033 (image_version_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image_port (id INT AUTO_INCREMENT NOT NULL, image_version_id INT NOT NULL, inward INT NOT NULL, outward INT NOT NULL, INDEX IDX_B1ABB75227623033 (image_version_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045FFE54D947 FOREIGN KEY (group_id) REFERENCES `group` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE image_version ADD CONSTRAINT FK_2A0C841F3DA5256D FOREIGN KEY (image_id) REFERENCES image (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE image_environment ADD CONSTRAINT FK_89F095AC27623033 FOREIGN KEY (image_version_id) REFERENCES image_version (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE image_version_extension ADD CONSTRAINT FK_35564D127623033 FOREIGN KEY (image_version_id) REFERENCES image_version (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE image_version_extension ADD CONSTRAINT FK_35564D1812D5EB FOREIGN KEY (extension_id) REFERENCES extension (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE image_volume ADD CONSTRAINT FK_1687931627623033 FOREIGN KEY (image_version_id) REFERENCES image_version (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE image_port ADD CONSTRAINT FK_B1ABB75227623033 FOREIGN KEY (image_version_id) REFERENCES image_version (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE image_version DROP FOREIGN KEY FK_2A0C841F3DA5256D');
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045FFE54D947');
        $this->addSql('ALTER TABLE image_version_extension DROP FOREIGN KEY FK_35564D1812D5EB');
        $this->addSql('ALTER TABLE image_port DROP FOREIGN KEY FK_B1ABB75227623033');
        $this->addSql('ALTER TABLE image_volume DROP FOREIGN KEY FK_1687931627623033');
        $this->addSql('ALTER TABLE image_version_extension DROP FOREIGN KEY FK_35564D127623033');
        $this->addSql('ALTER TABLE image_environment DROP FOREIGN KEY FK_89F095AC27623033');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE `group`');
        $this->addSql('DROP TABLE image_port');
        $this->addSql('DROP TABLE image_volume');
        $this->addSql('DROP TABLE image_version_extension');
        $this->addSql('DROP TABLE extension');
        $this->addSql('DROP TABLE image_version');
        $this->addSql('DROP TABLE image_environment');
    }
}
