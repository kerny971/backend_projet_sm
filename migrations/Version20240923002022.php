<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240923002022 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Création Table MailConfirmation - Enregistre un code qui sera envoyer par mail afin de confirmer son adresse mail';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE mail_confirmation (id INT AUTO_INCREMENT NOT NULL, user_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', code VARCHAR(10) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expired_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_B0EB6328A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE mail_confirmation ADD CONSTRAINT FK_B0EB6328A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mail_confirmation DROP FOREIGN KEY FK_B0EB6328A76ED395');
        $this->addSql('DROP TABLE mail_confirmation');
    }
}
