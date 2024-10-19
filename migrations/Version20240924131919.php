<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240924131919 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Création table PAYMENT - Enregistrements des transactions de paiements liées au ordres MEETING_ORDERS';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE payment ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD updated_at DATETIME NOT NULL, ADD cost INT DEFAULT NULL, ADD is_payed TINYINT(1) DEFAULT NULL, ADD fee_taxes_cost INT DEFAULT NULL, ADD stripe_reference VARCHAR(255) DEFAULT NULL, CHANGE id id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE payment DROP created_at, DROP updated_at, DROP cost, DROP is_payed, DROP fee_taxes_cost, DROP stripe_reference, CHANGE id id INT AUTO_INCREMENT NOT NULL');
    }
}
