<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240324131832 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE media_object (id INT AUTO_INCREMENT NOT NULL, file_path VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE answer CHANGE question_id question_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD avatar_id INT DEFAULT NULL, DROP avatar, CHANGE roles roles JSON NOT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64986383B10 FOREIGN KEY (avatar_id) REFERENCES media_object (id)');
        $this->addSql('CREATE INDEX IDX_8D93D64986383B10 ON user (avatar_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D64986383B10');
        $this->addSql('DROP TABLE media_object');
        $this->addSql('DROP INDEX IDX_8D93D64986383B10 ON `user`');
        $this->addSql('ALTER TABLE `user` ADD avatar LONGBLOB DEFAULT NULL, DROP avatar_id, CHANGE roles roles JSON NOT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE answer CHANGE question_id question_id INT NOT NULL');
    }
}
