<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241216200433 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE historiques ADD retard INT NOT NULL');
        $this->addSql('ALTER TABLE statistiques DROP duree_utilise');
        $this->addSql('ALTER TABLE user DROP nombre_prets');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE historiques DROP retard');
        $this->addSql('ALTER TABLE statistiques ADD duree_utilise DATETIME NOT NULL');
        $this->addSql('ALTER TABLE user ADD nombre_prets INT DEFAULT NULL');
    }
}
