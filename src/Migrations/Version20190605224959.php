<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190605224959 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE lodestone_character_lodestone_class (id INT AUTO_INCREMENT NOT NULL, lodestone_character_id_id INT NOT NULL, lodestone_class_id_id INT NOT NULL, level INT NOT NULL, experience INT NOT NULL, experience_total INT NOT NULL, INDEX IDX_97F059C693EB0F7D (lodestone_character_id_id), INDEX IDX_97F059C65C68A39 (lodestone_class_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lodestone_class (id INT AUTO_INCREMENT NOT NULL, lodestone_id INT NOT NULL, parent_lodestone_id INT DEFAULT NULL, name_de VARCHAR(255) NOT NULL, short_de VARCHAR(255) NOT NULL, name_en VARCHAR(255) NOT NULL, short_en VARCHAR(255) NOT NULL, name_jp VARCHAR(255) NOT NULL, short_jp VARCHAR(255) NOT NULL, name_fr VARCHAR(255) NOT NULL, short_fr VARCHAR(255) NOT NULL, tank TINYINT(1) DEFAULT NULL, healer TINYINT(1) DEFAULT NULL, dps TINYINT(1) DEFAULT NULL, crafter TINYINT(1) DEFAULT NULL, gatherer TINYINT(1) DEFAULT NULL, UNIQUE INDEX UNIQ_A50CA5E12BF9E802 (lodestone_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE lodestone_character_lodestone_class ADD CONSTRAINT FK_97F059C693EB0F7D FOREIGN KEY (lodestone_character_id_id) REFERENCES lodestone_character (id)');
        $this->addSql('ALTER TABLE lodestone_character_lodestone_class ADD CONSTRAINT FK_97F059C65C68A39 FOREIGN KEY (lodestone_class_id_id) REFERENCES lodestone_class (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE lodestone_character_lodestone_class DROP FOREIGN KEY FK_97F059C65C68A39');
        $this->addSql('DROP TABLE lodestone_character_lodestone_class');
        $this->addSql('DROP TABLE lodestone_class');
    }
}
