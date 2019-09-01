<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190901153035 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE lodestone_character DROP INDEX UNIQ_13E1708F2BF9E802, ADD INDEX lodestone_id_idx (lodestone_id)');
        $this->addSql('ALTER TABLE lodestone_character CHANGE lodestone_id lodestone_id INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE lodestone_character DROP INDEX lodestone_id_idx, ADD UNIQUE INDEX UNIQ_13E1708F2BF9E802 (lodestone_id)');
        $this->addSql('ALTER TABLE lodestone_character CHANGE lodestone_id lodestone_id INT NOT NULL');
    }
}
