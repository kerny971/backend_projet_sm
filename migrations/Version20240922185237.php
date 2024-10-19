<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240922185237 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Création de la table Meeting Room';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE meeting_room (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', slug VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE meeting_order ADD meeting_room_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE meeting_order ADD CONSTRAINT FK_9D18D198CCC5381E FOREIGN KEY (meeting_room_id) REFERENCES meeting_room (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9D18D198CCC5381E ON meeting_order (meeting_room_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE meeting_order DROP FOREIGN KEY FK_9D18D198CCC5381E');
        $this->addSql('DROP TABLE meeting_room');
        $this->addSql('DROP INDEX UNIQ_9D18D198CCC5381E ON meeting_order');
        $this->addSql('ALTER TABLE meeting_order DROP meeting_room_id');
    }
}
