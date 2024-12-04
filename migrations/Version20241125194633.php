<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241125194633 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE historiques DROP FOREIGN KEY FK_B25FDE8D79F37AE5');
        $this->addSql('ALTER TABLE historiques DROP FOREIGN KEY FK_B25FDE8DAABEFE2C');
        $this->addSql('DROP INDEX IDX_B25FDE8DAABEFE2C ON historiques');
        $this->addSql('DROP INDEX IDX_B25FDE8D79F37AE5 ON historiques');
        $this->addSql('ALTER TABLE historiques ADD produit_id INT NOT NULL, ADD user_id INT NOT NULL, DROP id_produit_id, DROP id_user_id');
        $this->addSql('ALTER TABLE historiques ADD CONSTRAINT FK_B25FDE8DF347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE historiques ADD CONSTRAINT FK_B25FDE8DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_B25FDE8DF347EFB ON historiques (produit_id)');
        $this->addSql('CREATE INDEX IDX_B25FDE8DA76ED395 ON historiques (user_id)');
        $this->addSql('ALTER TABLE maintenances DROP FOREIGN KEY FK_C2F7112FAABEFE2C');
        $this->addSql('DROP INDEX IDX_C2F7112FAABEFE2C ON maintenances');
        $this->addSql('ALTER TABLE maintenances CHANGE id_produit_id produit_id INT NOT NULL');
        $this->addSql('ALTER TABLE maintenances ADD CONSTRAINT FK_C2F7112FF347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('CREATE INDEX IDX_C2F7112FF347EFB ON maintenances (produit_id)');
        $this->addSql('ALTER TABLE notifications DROP FOREIGN KEY FK_6000B0D3AABEFE2C');
        $this->addSql('DROP INDEX IDX_6000B0D3AABEFE2C ON notifications');
        $this->addSql('ALTER TABLE notifications CHANGE id_produit_id produit_id INT NOT NULL');
        $this->addSql('ALTER TABLE notifications ADD CONSTRAINT FK_6000B0D3F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('CREATE INDEX IDX_6000B0D3F347EFB ON notifications (produit_id)');
        $this->addSql('ALTER TABLE reparations DROP FOREIGN KEY FK_953FFFD3AABEFE2C');
        $this->addSql('DROP INDEX IDX_953FFFD3AABEFE2C ON reparations');
        $this->addSql('ALTER TABLE reparations CHANGE id_produit_id produit_id INT NOT NULL');
        $this->addSql('ALTER TABLE reparations ADD CONSTRAINT FK_953FFFD3F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('CREATE INDEX IDX_953FFFD3F347EFB ON reparations (produit_id)');
        $this->addSql('ALTER TABLE statistiques DROP FOREIGN KEY FK_B31AB06679F37AE5');
        $this->addSql('ALTER TABLE statistiques DROP FOREIGN KEY FK_B31AB066AABEFE2C');
        $this->addSql('DROP INDEX IDX_B31AB066AABEFE2C ON statistiques');
        $this->addSql('DROP INDEX IDX_B31AB06679F37AE5 ON statistiques');
        $this->addSql('ALTER TABLE statistiques ADD produit_id INT NOT NULL, ADD user_id INT NOT NULL, DROP id_produit_id, DROP id_user_id');
        $this->addSql('ALTER TABLE statistiques ADD CONSTRAINT FK_B31AB066F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE statistiques ADD CONSTRAINT FK_B31AB066A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_B31AB066F347EFB ON statistiques (produit_id)');
        $this->addSql('CREATE INDEX IDX_B31AB066A76ED395 ON statistiques (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE maintenances DROP FOREIGN KEY FK_C2F7112FF347EFB');
        $this->addSql('DROP INDEX IDX_C2F7112FF347EFB ON maintenances');
        $this->addSql('ALTER TABLE maintenances CHANGE produit_id id_produit_id INT NOT NULL');
        $this->addSql('ALTER TABLE maintenances ADD CONSTRAINT FK_C2F7112FAABEFE2C FOREIGN KEY (id_produit_id) REFERENCES produit (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_C2F7112FAABEFE2C ON maintenances (id_produit_id)');
        $this->addSql('ALTER TABLE statistiques DROP FOREIGN KEY FK_B31AB066F347EFB');
        $this->addSql('ALTER TABLE statistiques DROP FOREIGN KEY FK_B31AB066A76ED395');
        $this->addSql('DROP INDEX IDX_B31AB066F347EFB ON statistiques');
        $this->addSql('DROP INDEX IDX_B31AB066A76ED395 ON statistiques');
        $this->addSql('ALTER TABLE statistiques ADD id_produit_id INT NOT NULL, ADD id_user_id INT NOT NULL, DROP produit_id, DROP user_id');
        $this->addSql('ALTER TABLE statistiques ADD CONSTRAINT FK_B31AB06679F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE statistiques ADD CONSTRAINT FK_B31AB066AABEFE2C FOREIGN KEY (id_produit_id) REFERENCES produit (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_B31AB066AABEFE2C ON statistiques (id_produit_id)');
        $this->addSql('CREATE INDEX IDX_B31AB06679F37AE5 ON statistiques (id_user_id)');
        $this->addSql('ALTER TABLE historiques DROP FOREIGN KEY FK_B25FDE8DF347EFB');
        $this->addSql('ALTER TABLE historiques DROP FOREIGN KEY FK_B25FDE8DA76ED395');
        $this->addSql('DROP INDEX IDX_B25FDE8DF347EFB ON historiques');
        $this->addSql('DROP INDEX IDX_B25FDE8DA76ED395 ON historiques');
        $this->addSql('ALTER TABLE historiques ADD id_produit_id INT NOT NULL, ADD id_user_id INT NOT NULL, DROP produit_id, DROP user_id');
        $this->addSql('ALTER TABLE historiques ADD CONSTRAINT FK_B25FDE8D79F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE historiques ADD CONSTRAINT FK_B25FDE8DAABEFE2C FOREIGN KEY (id_produit_id) REFERENCES produit (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_B25FDE8DAABEFE2C ON historiques (id_produit_id)');
        $this->addSql('CREATE INDEX IDX_B25FDE8D79F37AE5 ON historiques (id_user_id)');
        $this->addSql('ALTER TABLE reparations DROP FOREIGN KEY FK_953FFFD3F347EFB');
        $this->addSql('DROP INDEX IDX_953FFFD3F347EFB ON reparations');
        $this->addSql('ALTER TABLE reparations CHANGE produit_id id_produit_id INT NOT NULL');
        $this->addSql('ALTER TABLE reparations ADD CONSTRAINT FK_953FFFD3AABEFE2C FOREIGN KEY (id_produit_id) REFERENCES produit (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_953FFFD3AABEFE2C ON reparations (id_produit_id)');
        $this->addSql('ALTER TABLE notifications DROP FOREIGN KEY FK_6000B0D3F347EFB');
        $this->addSql('DROP INDEX IDX_6000B0D3F347EFB ON notifications');
        $this->addSql('ALTER TABLE notifications CHANGE produit_id id_produit_id INT NOT NULL');
        $this->addSql('ALTER TABLE notifications ADD CONSTRAINT FK_6000B0D3AABEFE2C FOREIGN KEY (id_produit_id) REFERENCES produit (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_6000B0D3AABEFE2C ON notifications (id_produit_id)');
    }
}
