<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190606213716 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE gearset_item (id INT AUTO_INCREMENT NOT NULL, gearset_id INT NOT NULL, item_id INT NOT NULL, slot VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_912E571CA7E08 (gearset_id), INDEX IDX_912E571126F525E (item_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE gearset_item_item (gearset_item_id INT NOT NULL, item_id INT NOT NULL, INDEX IDX_A94DE127E3C2B1BF (gearset_item_id), INDEX IDX_A94DE127126F525E (item_id), PRIMARY KEY(gearset_item_id, item_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE item (id INT AUTO_INCREMENT NOT NULL, lodestone_id INT NOT NULL, icon_url VARCHAR(255) NOT NULL, name_en VARCHAR(255) DEFAULT NULL, name_de VARCHAR(255) DEFAULT NULL, name_jp VARCHAR(255) DEFAULT NULL, name_fr VARCHAR(255) DEFAULT NULL, category_id INT NOT NULL, level_equip INT NOT NULL, level_item INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE gearset_item ADD CONSTRAINT FK_912E571CA7E08 FOREIGN KEY (gearset_id) REFERENCES gear_set (id)');
        $this->addSql('ALTER TABLE gearset_item ADD CONSTRAINT FK_912E571126F525E FOREIGN KEY (item_id) REFERENCES item (id)');
        $this->addSql('ALTER TABLE gearset_item_item ADD CONSTRAINT FK_A94DE127E3C2B1BF FOREIGN KEY (gearset_item_id) REFERENCES gearset_item (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE gearset_item_item ADD CONSTRAINT FK_A94DE127126F525E FOREIGN KEY (item_id) REFERENCES item (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE gearset_item_item DROP FOREIGN KEY FK_A94DE127E3C2B1BF');
        $this->addSql('ALTER TABLE gearset_item DROP FOREIGN KEY FK_912E571126F525E');
        $this->addSql('ALTER TABLE gearset_item_item DROP FOREIGN KEY FK_A94DE127126F525E');
        $this->addSql('DROP TABLE gearset_item');
        $this->addSql('DROP TABLE gearset_item_item');
        $this->addSql('DROP TABLE item');
    }
}
