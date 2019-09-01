<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190826211752 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE fflogs_ranking (id INT AUTO_INCREMENT NOT NULL, lodestone_character_id INT NOT NULL, lodestone_class_id INT NOT NULL, encounter_id INT NOT NULL, rank INT NOT NULL, dps DOUBLE PRECISION NOT NULL, duration INT NOT NULL, datetime DATETIME NOT NULL, fight_id INT NOT NULL, report_id VARCHAR(255) NOT NULL, server VARCHAR(255) NOT NULL, region VARCHAR(255) NOT NULL, patch DOUBLE PRECISION NOT NULL, INDEX IDX_8E011C71741A3286 (lodestone_character_id), INDEX IDX_8E011C7114A77B85 (lodestone_class_id), INDEX IDX_8E011C71D6E2FADC (encounter_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE fflogs_ranking ADD CONSTRAINT FK_8E011C71741A3286 FOREIGN KEY (lodestone_character_id) REFERENCES lodestone_character (id)');
        $this->addSql('ALTER TABLE fflogs_ranking ADD CONSTRAINT FK_8E011C7114A77B85 FOREIGN KEY (lodestone_class_id) REFERENCES lodestone_class (id)');
        $this->addSql('ALTER TABLE fflogs_ranking ADD CONSTRAINT FK_8E011C71D6E2FADC FOREIGN KEY (encounter_id) REFERENCES fflogs_encounter (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE fflogs_ranking');
    }
}
