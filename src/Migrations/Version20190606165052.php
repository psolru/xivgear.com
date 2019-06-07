<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190606165052 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE gear_set (id INT AUTO_INCREMENT NOT NULL, lodestone_character_id INT NOT NULL, lodestone_class_id INT NOT NULL, i_level INT NOT NULL, strength INT DEFAULT NULL, dexterity INT DEFAULT NULL, vitality INT DEFAULT NULL, intelligence INT DEFAULT NULL, mind INT DEFAULT NULL, critical_hit INT DEFAULT NULL, determination INT DEFAULT NULL, direct_hit_rate INT DEFAULT NULL, defense INT DEFAULT NULL, magic_defense INT DEFAULT NULL, attack_power INT DEFAULT NULL, skill_speed INT DEFAULT NULL, attack_magic_potency INT DEFAULT NULL, healing_magic_potency INT DEFAULT NULL, spell_speed INT DEFAULT NULL, tenacity INT DEFAULT NULL, piety INT DEFAULT NULL, gathering INT DEFAULT NULL, perception INT DEFAULT NULL, craftsmanship INT DEFAULT NULL, control INT DEFAULT NULL, c_p INT DEFAULT NULL, g_p INT DEFAULT NULL, h_p INT DEFAULT NULL, m_p INT DEFAULT NULL, t_p INT DEFAULT NULL, INDEX IDX_AC0399E3741A3286 (lodestone_character_id), INDEX IDX_AC0399E314A77B85 (lodestone_class_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE gear_set ADD CONSTRAINT FK_AC0399E3741A3286 FOREIGN KEY (lodestone_character_id) REFERENCES lodestone_character (id)');
        $this->addSql('ALTER TABLE gear_set ADD CONSTRAINT FK_AC0399E314A77B85 FOREIGN KEY (lodestone_class_id) REFERENCES lodestone_class (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE gear_set');
    }
}
