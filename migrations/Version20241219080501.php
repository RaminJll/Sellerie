<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241219080501 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE historiques (id INT AUTO_INCREMENT NOT NULL, produit_id INT NOT NULL, user_id INT NOT NULL, date_empreinte DATETIME NOT NULL, date_rendu DATETIME DEFAULT NULL, signalement VARCHAR(255) DEFAULT NULL, etat_init VARCHAR(255) NOT NULL, retard INT NOT NULL, INDEX IDX_B25FDE8DF347EFB (produit_id), INDEX IDX_B25FDE8DA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE maintenances (id INT AUTO_INCREMENT NOT NULL, produit_id INT NOT NULL, cout_maintenance DOUBLE PRECISION DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, date_fin_maintenance DATETIME NOT NULL, future_date_maintenance DATETIME NOT NULL, INDEX IDX_C2F7112FF347EFB (produit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notifications (id INT AUTO_INCREMENT NOT NULL, produit_id INT NOT NULL, type VARCHAR(255) NOT NULL, date_notification DATETIME NOT NULL, INDEX IDX_6000B0D3F347EFB (produit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produit (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, categorie VARCHAR(255) NOT NULL, type_produit VARCHAR(255) NOT NULL, etat VARCHAR(255) NOT NULL, date_achat DATE NOT NULL, planning DATE NOT NULL, categorie_rayon VARCHAR(255) NOT NULL, etagere INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reparations (id INT AUTO_INCREMENT NOT NULL, produit_id INT NOT NULL, description_probleme VARCHAR(255) NOT NULL, cout_reparation DOUBLE PRECISION NOT NULL, date_fin_reparation DATETIME NOT NULL, etat_init VARCHAR(255) NOT NULL, INDEX IDX_953FFFD3F347EFB (produit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE statistiques (id INT AUTO_INCREMENT NOT NULL, produit_id INT NOT NULL, user_id INT NOT NULL, nombre_prets INT NOT NULL, retard INT NOT NULL, INDEX IDX_B31AB066F347EFB (produit_id), INDEX IDX_B31AB066A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, role VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE historiques ADD CONSTRAINT FK_B25FDE8DF347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE historiques ADD CONSTRAINT FK_B25FDE8DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE maintenances ADD CONSTRAINT FK_C2F7112FF347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE notifications ADD CONSTRAINT FK_6000B0D3F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE reparations ADD CONSTRAINT FK_953FFFD3F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE statistiques ADD CONSTRAINT FK_B31AB066F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE statistiques ADD CONSTRAINT FK_B31AB066A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE historiques DROP FOREIGN KEY FK_B25FDE8DF347EFB');
        $this->addSql('ALTER TABLE historiques DROP FOREIGN KEY FK_B25FDE8DA76ED395');
        $this->addSql('ALTER TABLE maintenances DROP FOREIGN KEY FK_C2F7112FF347EFB');
        $this->addSql('ALTER TABLE notifications DROP FOREIGN KEY FK_6000B0D3F347EFB');
        $this->addSql('ALTER TABLE reparations DROP FOREIGN KEY FK_953FFFD3F347EFB');
        $this->addSql('ALTER TABLE statistiques DROP FOREIGN KEY FK_B31AB066F347EFB');
        $this->addSql('ALTER TABLE statistiques DROP FOREIGN KEY FK_B31AB066A76ED395');
        $this->addSql('DROP TABLE historiques');
        $this->addSql('DROP TABLE maintenances');
        $this->addSql('DROP TABLE notifications');
        $this->addSql('DROP TABLE produit');
        $this->addSql('DROP TABLE reparations');
        $this->addSql('DROP TABLE statistiques');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
