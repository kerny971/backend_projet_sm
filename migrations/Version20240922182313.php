<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240922182313 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Modification table MeetingOffer - Changement du type Id - Int to UUID';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE meeting_offer CHANGE id id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE meeting_offer CHANGE id id INT AUTO_INCREMENT NOT NULL');
    }
}
