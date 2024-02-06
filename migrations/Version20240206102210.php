<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240206102210 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE animal (id INT AUTO_INCREMENT NOT NULL, species_id INT DEFAULT NULL, pen_id INT DEFAULT NULL, name VARCHAR(30) NOT NULL, gender VARCHAR(1) NOT NULL, description VARCHAR(255) DEFAULT NULL, weight DOUBLE PRECISION DEFAULT NULL, size DOUBLE PRECISION DEFAULT NULL, birth_date DATETIME DEFAULT NULL, INDEX IDX_6AAB231FB2A1D860 (species_id), INDEX IDX_6AAB231FA9CBC84D (pen_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE answer (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, question_id INT NOT NULL, description LONGTEXT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_DADD4A25F675F31B (author_id), INDEX IDX_DADD4A251E27F6BF (question_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(30) DEFAULT NULL, date DATETIME DEFAULT NULL, hstart DATETIME DEFAULT NULL, hend DATETIME DEFAULT NULL, maxi_num_place INT DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE family (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(60) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pen (id INT AUTO_INCREMENT NOT NULL, spot_id INT DEFAULT NULL, capacity INT DEFAULT NULL, type VARCHAR(50) DEFAULT NULL, size DOUBLE PRECISION DEFAULT NULL, INDEX IDX_193062FF2DF1D37C (spot_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, title VARCHAR(50) NOT NULL, description LONGTEXT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', is_resolved TINYINT(1) NOT NULL, INDEX IDX_B6F7494EF675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_question_like (question_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_1C03000C1E27F6BF (question_id), INDEX IDX_1C03000CA76ED395 (user_id), PRIMARY KEY(question_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE species (id INT AUTO_INCREMENT NOT NULL, family_id INT DEFAULT NULL, name VARCHAR(50) DEFAULT NULL, diet VARCHAR(255) DEFAULT NULL, origin VARCHAR(60) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, INDEX IDX_A50FF712C35E566A (family_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE spot (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(30) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ticket (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, date DATETIME DEFAULT NULL, price INT DEFAULT NULL, type VARCHAR(50) DEFAULT NULL, INDEX IDX_97A0ADA3A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ticket_event (ticket_id INT NOT NULL, event_id INT NOT NULL, INDEX IDX_139E692C700047D2 (ticket_id), INDEX IDX_139E692C71F7E88B (event_id), PRIMARY KEY(ticket_id, event_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE animal ADD CONSTRAINT FK_6AAB231FB2A1D860 FOREIGN KEY (species_id) REFERENCES species (id)');
        $this->addSql('ALTER TABLE animal ADD CONSTRAINT FK_6AAB231FA9CBC84D FOREIGN KEY (pen_id) REFERENCES pen (id)');
        $this->addSql('ALTER TABLE answer ADD CONSTRAINT FK_DADD4A25F675F31B FOREIGN KEY (author_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE answer ADD CONSTRAINT FK_DADD4A251E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE pen ADD CONSTRAINT FK_193062FF2DF1D37C FOREIGN KEY (spot_id) REFERENCES spot (id)');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494EF675F31B FOREIGN KEY (author_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE user_question_like ADD CONSTRAINT FK_1C03000C1E27F6BF FOREIGN KEY (question_id) REFERENCES question (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_question_like ADD CONSTRAINT FK_1C03000CA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE species ADD CONSTRAINT FK_A50FF712C35E566A FOREIGN KEY (family_id) REFERENCES family (id)');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA3A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE ticket_event ADD CONSTRAINT FK_139E692C700047D2 FOREIGN KEY (ticket_id) REFERENCES ticket (id)');
        $this->addSql('ALTER TABLE ticket_event ADD CONSTRAINT FK_139E692C71F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE animal DROP FOREIGN KEY FK_6AAB231FB2A1D860');
        $this->addSql('ALTER TABLE animal DROP FOREIGN KEY FK_6AAB231FA9CBC84D');
        $this->addSql('ALTER TABLE answer DROP FOREIGN KEY FK_DADD4A25F675F31B');
        $this->addSql('ALTER TABLE answer DROP FOREIGN KEY FK_DADD4A251E27F6BF');
        $this->addSql('ALTER TABLE pen DROP FOREIGN KEY FK_193062FF2DF1D37C');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494EF675F31B');
        $this->addSql('ALTER TABLE user_question_like DROP FOREIGN KEY FK_1C03000C1E27F6BF');
        $this->addSql('ALTER TABLE user_question_like DROP FOREIGN KEY FK_1C03000CA76ED395');
        $this->addSql('ALTER TABLE species DROP FOREIGN KEY FK_A50FF712C35E566A');
        $this->addSql('ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA3A76ED395');
        $this->addSql('ALTER TABLE ticket_event DROP FOREIGN KEY FK_139E692C700047D2');
        $this->addSql('ALTER TABLE ticket_event DROP FOREIGN KEY FK_139E692C71F7E88B');
        $this->addSql('DROP TABLE animal');
        $this->addSql('DROP TABLE answer');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE family');
        $this->addSql('DROP TABLE pen');
        $this->addSql('DROP TABLE question');
        $this->addSql('DROP TABLE user_question_like');
        $this->addSql('DROP TABLE species');
        $this->addSql('DROP TABLE spot');
        $this->addSql('DROP TABLE ticket');
        $this->addSql('DROP TABLE ticket_event');
    }
}
