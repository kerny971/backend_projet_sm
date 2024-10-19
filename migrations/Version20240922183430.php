<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240922183430 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Création de la table MeetingOrder - Enregistrement des commandes pour les meetings';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE meeting_order (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', user_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', meeting_offer_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL, url VARCHAR(255) DEFAULT NULL, started_at DATETIME DEFAULT NULL, INDEX IDX_9D18D198A76ED395 (user_id), INDEX IDX_9D18D198C057593B (meeting_offer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE meeting_order ADD CONSTRAINT FK_9D18D198A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE meeting_order ADD CONSTRAINT FK_9D18D198C057593B FOREIGN KEY (meeting_offer_id) REFERENCES meeting_offer (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE meeting_order DROP FOREIGN KEY FK_9D18D198A76ED395');
        $this->addSql('ALTER TABLE meeting_order DROP FOREIGN KEY FK_9D18D198C057593B');
        $this->addSql('DROP TABLE meeting_order');
    }
}
