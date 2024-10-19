<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240922203314 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Modification table Meeting Offer - Ajout champs - is_valided : L\'offre de meeting à été validé par un administrateur. - is_actived : L\'offre de meeting est visible par les autres utilisateurs';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE meeting_offer ADD is_valided TINYINT(1) DEFAULT NULL, ADD is_actived TINYINT(1) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE meeting_offer DROP is_valided, DROP is_actived');
    }
}
