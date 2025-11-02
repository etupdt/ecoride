<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251029155353 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE covoiturage (id SERIAL NOT NULL, lieu_depart_id INT NOT NULL, lieu_arrivee_id INT NOT NULL, voiture_id INT NOT NULL, date_depart DATE NOT NULL, heure_depart TIME(0) WITHOUT TIME ZONE NOT NULL, date_arrivee DATE NOT NULL, heure_arrivee TIME(0) WITHOUT TIME ZONE NOT NULL, statut VARCHAR(50) DEFAULT NULL, nb_place INT NOT NULL, prix_personne DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_28C79E89C16565FC ON covoiturage (lieu_depart_id)');
        $this->addSql('CREATE INDEX IDX_28C79E89BF9A3FF6 ON covoiturage (lieu_arrivee_id)');
        $this->addSql('CREATE INDEX IDX_28C79E89181A8BA ON covoiturage (voiture_id)');
        $this->addSql('CREATE TABLE covoiturage_user (covoiturage_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(covoiturage_id, user_id))');
        $this->addSql('CREATE INDEX IDX_F862CC4962671590 ON covoiturage_user (covoiturage_id)');
        $this->addSql('CREATE INDEX IDX_F862CC49A76ED395 ON covoiturage_user (user_id)');
        $this->addSql('CREATE TABLE energie (id SERIAL NOT NULL, libelle VARCHAR(50) NOT NULL, ecologique BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE marque (id SERIAL NOT NULL, libelle VARCHAR(50) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE "user" (id SERIAL NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, pseudo VARCHAR(50) NOT NULL, nom VARCHAR(100) NOT NULL, prenom VARCHAR(100) NOT NULL, telephone VARCHAR(16) DEFAULT NULL, adresse VARCHAR(255) DEFAULT NULL, date_naissance DATE DEFAULT NULL, photo VARCHAR(255) NOT NULL, is_verified BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON "user" (email)');
        $this->addSql('CREATE TABLE ville (id SERIAL NOT NULL, libelle VARCHAR(50) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE voiture (id SERIAL NOT NULL, marque_id INT NOT NULL, chauffeur_id INT NOT NULL, energie_id INT NOT NULL, modele VARCHAR(50) NOT NULL, immatriculation VARCHAR(20) NOT NULL, couleur VARCHAR(50) NOT NULL, date_premiere_immatriculation DATE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E9E2810F4827B9B2 ON voiture (marque_id)');
        $this->addSql('CREATE INDEX IDX_E9E2810F85C0B3BE ON voiture (chauffeur_id)');
        $this->addSql('CREATE INDEX IDX_E9E2810FB732A364 ON voiture (energie_id)');
        $this->addSql('CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
        $this->addSql('COMMENT ON COLUMN messenger_messages.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.available_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.delivered_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE OR REPLACE FUNCTION notify_messenger_messages() RETURNS TRIGGER AS $$
            BEGIN
                PERFORM pg_notify(\'messenger_messages\', NEW.queue_name::text);
                RETURN NEW;
            END;
        $$ LANGUAGE plpgsql;');
        $this->addSql('DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages;');
        $this->addSql('CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON messenger_messages FOR EACH ROW EXECUTE PROCEDURE notify_messenger_messages();');
        $this->addSql('ALTER TABLE covoiturage ADD CONSTRAINT FK_28C79E89C16565FC FOREIGN KEY (lieu_depart_id) REFERENCES ville (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE covoiturage ADD CONSTRAINT FK_28C79E89BF9A3FF6 FOREIGN KEY (lieu_arrivee_id) REFERENCES ville (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE covoiturage ADD CONSTRAINT FK_28C79E89181A8BA FOREIGN KEY (voiture_id) REFERENCES voiture (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE covoiturage_user ADD CONSTRAINT FK_F862CC4962671590 FOREIGN KEY (covoiturage_id) REFERENCES covoiturage (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE covoiturage_user ADD CONSTRAINT FK_F862CC49A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE voiture ADD CONSTRAINT FK_E9E2810F4827B9B2 FOREIGN KEY (marque_id) REFERENCES marque (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE voiture ADD CONSTRAINT FK_E9E2810F85C0B3BE FOREIGN KEY (chauffeur_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE voiture ADD CONSTRAINT FK_E9E2810FB732A364 FOREIGN KEY (energie_id) REFERENCES energie (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE covoiturage DROP CONSTRAINT FK_28C79E89C16565FC');
        $this->addSql('ALTER TABLE covoiturage DROP CONSTRAINT FK_28C79E89BF9A3FF6');
        $this->addSql('ALTER TABLE covoiturage DROP CONSTRAINT FK_28C79E89181A8BA');
        $this->addSql('ALTER TABLE covoiturage_user DROP CONSTRAINT FK_F862CC4962671590');
        $this->addSql('ALTER TABLE covoiturage_user DROP CONSTRAINT FK_F862CC49A76ED395');
        $this->addSql('ALTER TABLE voiture DROP CONSTRAINT FK_E9E2810F4827B9B2');
        $this->addSql('ALTER TABLE voiture DROP CONSTRAINT FK_E9E2810F85C0B3BE');
        $this->addSql('ALTER TABLE voiture DROP CONSTRAINT FK_E9E2810FB732A364');
        $this->addSql('DROP TABLE covoiturage');
        $this->addSql('DROP TABLE covoiturage_user');
        $this->addSql('DROP TABLE energie');
        $this->addSql('DROP TABLE marque');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE ville');
        $this->addSql('DROP TABLE voiture');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
