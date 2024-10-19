<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240922211832 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'ajout Index Key UNIQUE sur le champ SLUG table MeetingOffer et modification du type champs DESCRIPTION en MediumText';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE UNIQUE INDEX UNIQ_41E7C53E989D9B62 ON meeting_offer (slug)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_41E7C53E989D9B62 ON meeting_offer');
    }
}
