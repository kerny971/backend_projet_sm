<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240907140134 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE roles MODIFY id INT NOT NULL');
        $this->addSql('DROP INDEX UNIQ_B63E2EC7D17F50A6 ON roles');
        $this->addSql('DROP INDEX `primary` ON roles');
        $this->addSql('ALTER TABLE roles DROP id');
        $this->addSql('ALTER TABLE roles ADD PRIMARY KEY (uuid)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE roles ADD id INT AUTO_INCREMENT NOT NULL, DROP PRIMARY KEY, ADD PRIMARY KEY (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B63E2EC7D17F50A6 ON roles (uuid)');
    }
}
