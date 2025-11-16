<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251104063837 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE participation (id SERIAL NOT NULL, passager_id INT NOT NULL, covoiturage_id INT NOT NULL, commentaire TEXT DEFAULT NULL, statut VARCHAR(50) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_AB55E24F71A51189 ON participation (passager_id)');
        $this->addSql('CREATE INDEX IDX_AB55E24F62671590 ON participation (covoiturage_id)');
        $this->addSql('ALTER TABLE participation ADD CONSTRAINT FK_AB55E24F71A51189 FOREIGN KEY (passager_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE participation ADD CONSTRAINT FK_AB55E24F62671590 FOREIGN KEY (covoiturage_id) REFERENCES covoiturage (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE covoiturage_user DROP CONSTRAINT fk_f862cc4962671590');
        $this->addSql('ALTER TABLE covoiturage_user DROP CONSTRAINT fk_f862cc49a76ed395');
        $this->addSql('DROP TABLE covoiturage_user');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE TABLE covoiturage_user (covoiturage_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(covoiturage_id, user_id))');
        $this->addSql('CREATE INDEX idx_f862cc4962671590 ON covoiturage_user (covoiturage_id)');
        $this->addSql('CREATE INDEX idx_f862cc49a76ed395 ON covoiturage_user (user_id)');
        $this->addSql('ALTER TABLE covoiturage_user ADD CONSTRAINT fk_f862cc4962671590 FOREIGN KEY (covoiturage_id) REFERENCES covoiturage (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE covoiturage_user ADD CONSTRAINT fk_f862cc49a76ed395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE participation DROP CONSTRAINT FK_AB55E24F71A51189');
        $this->addSql('ALTER TABLE participation DROP CONSTRAINT FK_AB55E24F62671590');
        $this->addSql('DROP TABLE participation');
    }
}
