<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251025072748 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE marque (id SERIAL NOT NULL, libelle VARCHAR(50) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE voiture (id SERIAL NOT NULL, marque_id INT NOT NULL, chauffeur_id INT NOT NULL, modele VARCHAR(50) NOT NULL, immatriculation VARCHAR(20) NOT NULL, energie VARCHAR(4) NOT NULL, couleur VARCHAR(50) NOT NULL, date_premiere_immatriculation DATE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E9E2810F4827B9B2 ON voiture (marque_id)');
        $this->addSql('CREATE INDEX IDX_E9E2810F85C0B3BE ON voiture (chauffeur_id)');
        $this->addSql('ALTER TABLE voiture ADD CONSTRAINT FK_E9E2810F4827B9B2 FOREIGN KEY (marque_id) REFERENCES marque (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE voiture ADD CONSTRAINT FK_E9E2810F85C0B3BE FOREIGN KEY (chauffeur_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE covoiturage ADD voiture_id INT NOT NULL');
        $this->addSql('ALTER TABLE covoiturage ADD CONSTRAINT FK_28C79E89181A8BA FOREIGN KEY (voiture_id) REFERENCES voiture (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_28C79E89181A8BA ON covoiturage (voiture_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE covoiturage DROP CONSTRAINT FK_28C79E89181A8BA');
        $this->addSql('ALTER TABLE voiture DROP CONSTRAINT FK_E9E2810F4827B9B2');
        $this->addSql('ALTER TABLE voiture DROP CONSTRAINT FK_E9E2810F85C0B3BE');
        $this->addSql('DROP TABLE marque');
        $this->addSql('DROP TABLE voiture');
        $this->addSql('DROP INDEX IDX_28C79E89181A8BA');
        $this->addSql('ALTER TABLE covoiturage DROP voiture_id');
    }
}
