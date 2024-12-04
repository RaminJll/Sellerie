<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241123170527 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE historiques (id INT AUTO_INCREMENT NOT NULL, id_produit_id INT NOT NULL, id_user_id INT NOT NULL, date_empreinte DATETIME NOT NULL, date_rendu DATETIME NOT NULL, signalement VARCHAR(255) DEFAULT NULL, INDEX IDX_B25FDE8DAABEFE2C (id_produit_id), INDEX IDX_B25FDE8D79F37AE5 (id_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE historiques ADD CONSTRAINT FK_B25FDE8DAABEFE2C FOREIGN KEY (id_produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE historiques ADD CONSTRAINT FK_B25FDE8D79F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE historiques DROP FOREIGN KEY FK_B25FDE8DAABEFE2C');
        $this->addSql('ALTER TABLE historiques DROP FOREIGN KEY FK_B25FDE8D79F37AE5');
        $this->addSql('DROP TABLE historiques');
    }
}
