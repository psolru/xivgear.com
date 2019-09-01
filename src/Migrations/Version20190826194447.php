<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190826194447 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE fflogs_encounter (id INT AUTO_INCREMENT NOT NULL, fflogs_zone_id INT NOT NULL, fflogs_id INT NOT NULL, name_de VARCHAR(255) DEFAULT NULL, name_en VARCHAR(255) DEFAULT NULL, name_fr VARCHAR(255) DEFAULT NULL, name_jp VARCHAR(255) DEFAULT NULL, INDEX IDX_95CA7E9C91951A0A (fflogs_zone_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fflogs_zone (id INT AUTO_INCREMENT NOT NULL, fflogs_id INT NOT NULL, name_de VARCHAR(255) DEFAULT NULL, name_en VARCHAR(255) DEFAULT NULL, name_fr VARCHAR(255) DEFAULT NULL, name_jp VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE fflogs_encounter ADD CONSTRAINT FK_95CA7E9C91951A0A FOREIGN KEY (fflogs_zone_id) REFERENCES fflogs_zone (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE fflogs_encounter DROP FOREIGN KEY FK_95CA7E9C91951A0A');
        $this->addSql('DROP TABLE fflogs_encounter');
        $this->addSql('DROP TABLE fflogs_zone');
    }
}
