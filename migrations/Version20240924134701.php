<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240924134701 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Jointure Table Payment et MeetingOrder - Reliées les commandes aux ordres de paiements';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE payment ADD meeting_order_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840D1E0E40ED FOREIGN KEY (meeting_order_id) REFERENCES meeting_order (id)');
        $this->addSql('CREATE INDEX IDX_6D28840D1E0E40ED ON payment (meeting_order_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE payment DROP FOREIGN KEY FK_6D28840D1E0E40ED');
        $this->addSql('DROP INDEX IDX_6D28840D1E0E40ED ON payment');
        $this->addSql('ALTER TABLE payment DROP meeting_order_id');
    }
}
