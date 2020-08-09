<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200804104244 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Init';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, group_id INT NOT NULL, name VARCHAR(128) NOT NULL, INDEX IDX_C53D045FFE54D947 (group_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image_install_dependency (image_id INT NOT NULL, docker_install_id INT NOT NULL, INDEX IDX_2AE62F073DA5256D (image_id), INDEX IDX_2AE62F072AD5AAB6 (docker_install_id), PRIMARY KEY(image_id, docker_install_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `group` (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(32) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image_port (id INT AUTO_INCREMENT NOT NULL, image_id INT NOT NULL, inward INT NOT NULL, outward INT NOT NULL, INDEX IDX_B1ABB7523DA5256D (image_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image_version (id INT AUTO_INCREMENT NOT NULL, image_id INT NOT NULL, version VARCHAR(128) NOT NULL, INDEX IDX_2A0C841F3DA5256D (image_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image_environment (id INT AUTO_INCREMENT NOT NULL, image_id INT NOT NULL, code VARCHAR(128) NOT NULL, default_value VARCHAR(256) NOT NULL, INDEX IDX_89F095AC3DA5256D (image_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE docker_install (id INT AUTO_INCREMENT NOT NULL, image_id INT NOT NULL, name VARCHAR(128) NOT NULL, INDEX IDX_964B7E103DA5256D (image_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045FFE54D947 FOREIGN KEY (group_id) REFERENCES `group` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE image_install_dependency ADD CONSTRAINT FK_2AE62F073DA5256D FOREIGN KEY (image_id) REFERENCES image (id)');
        $this->addSql('ALTER TABLE image_install_dependency ADD CONSTRAINT FK_2AE62F072AD5AAB6 FOREIGN KEY (docker_install_id) REFERENCES docker_install (id)');
        $this->addSql('ALTER TABLE image_port ADD CONSTRAINT FK_B1ABB7523DA5256D FOREIGN KEY (image_id) REFERENCES image (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE image_version ADD CONSTRAINT FK_2A0C841F3DA5256D FOREIGN KEY (image_id) REFERENCES image (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE image_environment ADD CONSTRAINT FK_89F095AC3DA5256D FOREIGN KEY (image_id) REFERENCES image (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE docker_install ADD CONSTRAINT FK_964B7E103DA5256D FOREIGN KEY (image_id) REFERENCES image (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE image_install_dependency DROP FOREIGN KEY FK_2AE62F073DA5256D');
        $this->addSql('ALTER TABLE image_port DROP FOREIGN KEY FK_B1ABB7523DA5256D');
        $this->addSql('ALTER TABLE image_version DROP FOREIGN KEY FK_2A0C841F3DA5256D');
        $this->addSql('ALTER TABLE image_environment DROP FOREIGN KEY FK_89F095AC3DA5256D');
        $this->addSql('ALTER TABLE docker_install DROP FOREIGN KEY FK_964B7E103DA5256D');
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045FFE54D947');
        $this->addSql('ALTER TABLE image_install_dependency DROP FOREIGN KEY FK_2AE62F072AD5AAB6');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE image_install_dependency');
        $this->addSql('DROP TABLE `group`');
        $this->addSql('DROP TABLE image_port');
        $this->addSql('DROP TABLE image_version');
        $this->addSql('DROP TABLE image_environment');
        $this->addSql('DROP TABLE docker_install');
    }
}
