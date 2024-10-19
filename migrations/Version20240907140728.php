<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240907140728 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX `primary` ON roles');
        $this->addSql('ALTER TABLE roles CHANGE uuid id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE roles ADD PRIMARY KEY (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX `PRIMARY` ON roles');
        $this->addSql('ALTER TABLE roles CHANGE id uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE roles ADD PRIMARY KEY (uuid)');
    }
}
