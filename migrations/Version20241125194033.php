<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241125194033 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE maintenances (id INT AUTO_INCREMENT NOT NULL, id_produit_id INT NOT NULL, duree_maintenance DATETIME DEFAULT NULL, cout_maintenance DOUBLE PRECISION DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, INDEX IDX_C2F7112FAABEFE2C (id_produit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notifications (id INT AUTO_INCREMENT NOT NULL, id_produit_id INT NOT NULL, type VARCHAR(255) NOT NULL, date_notification DATETIME NOT NULL, INDEX IDX_6000B0D3AABEFE2C (id_produit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reparations (id INT AUTO_INCREMENT NOT NULL, id_produit_id INT NOT NULL, description_probleme VARCHAR(255) NOT NULL, date_signalement DATETIME NOT NULL, cout_reparation DOUBLE PRECISION NOT NULL, reparation_fini DATETIME NOT NULL, INDEX IDX_953FFFD3AABEFE2C (id_produit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE statistiques (id INT AUTO_INCREMENT NOT NULL, id_produit_id INT NOT NULL, id_user_id INT NOT NULL, nombre_prets INT NOT NULL, duree_utilise DATETIME NOT NULL, retard INT NOT NULL, INDEX IDX_B31AB066AABEFE2C (id_produit_id), INDEX IDX_B31AB06679F37AE5 (id_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE maintenances ADD CONSTRAINT FK_C2F7112FAABEFE2C FOREIGN KEY (id_produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE notifications ADD CONSTRAINT FK_6000B0D3AABEFE2C FOREIGN KEY (id_produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE reparations ADD CONSTRAINT FK_953FFFD3AABEFE2C FOREIGN KEY (id_produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE statistiques ADD CONSTRAINT FK_B31AB066AABEFE2C FOREIGN KEY (id_produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE statistiques ADD CONSTRAINT FK_B31AB06679F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE produit ADD categorie_rayon VARCHAR(255) NOT NULL, ADD etagere INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE maintenances DROP FOREIGN KEY FK_C2F7112FAABEFE2C');
        $this->addSql('ALTER TABLE notifications DROP FOREIGN KEY FK_6000B0D3AABEFE2C');
        $this->addSql('ALTER TABLE reparations DROP FOREIGN KEY FK_953FFFD3AABEFE2C');
        $this->addSql('ALTER TABLE statistiques DROP FOREIGN KEY FK_B31AB066AABEFE2C');
        $this->addSql('ALTER TABLE statistiques DROP FOREIGN KEY FK_B31AB06679F37AE5');
        $this->addSql('DROP TABLE maintenances');
        $this->addSql('DROP TABLE notifications');
        $this->addSql('DROP TABLE reparations');
        $this->addSql('DROP TABLE statistiques');
        $this->addSql('ALTER TABLE produit DROP categorie_rayon, DROP etagere');
    }
}
