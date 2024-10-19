<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240922182023 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Création de la table meeting_offer';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE meeting_offer (id INT AUTO_INCREMENT NOT NULL, user_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', slug VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, content LONGTEXT DEFAULT NULL, price INT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL, INDEX IDX_41E7C53EA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE meeting_offer ADD CONSTRAINT FK_41E7C53EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE meeting_offer DROP FOREIGN KEY FK_41E7C53EA76ED395');
        $this->addSql('DROP TABLE meeting_offer');
    }
}
