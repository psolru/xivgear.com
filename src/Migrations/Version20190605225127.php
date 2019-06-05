<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190605225127 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE lodestone_character_lodestone_class DROP FOREIGN KEY FK_97F059C65C68A39');
        $this->addSql('ALTER TABLE lodestone_character_lodestone_class DROP FOREIGN KEY FK_97F059C693EB0F7D');
        $this->addSql('DROP INDEX IDX_97F059C65C68A39 ON lodestone_character_lodestone_class');
        $this->addSql('DROP INDEX IDX_97F059C693EB0F7D ON lodestone_character_lodestone_class');
        $this->addSql('ALTER TABLE lodestone_character_lodestone_class ADD lodestone_character_id INT NOT NULL, ADD lodestone_class_id INT NOT NULL, DROP lodestone_character_id_id, DROP lodestone_class_id_id');
        $this->addSql('ALTER TABLE lodestone_character_lodestone_class ADD CONSTRAINT FK_97F059C6741A3286 FOREIGN KEY (lodestone_character_id) REFERENCES lodestone_character (id)');
        $this->addSql('ALTER TABLE lodestone_character_lodestone_class ADD CONSTRAINT FK_97F059C614A77B85 FOREIGN KEY (lodestone_class_id) REFERENCES lodestone_class (id)');
        $this->addSql('CREATE INDEX IDX_97F059C6741A3286 ON lodestone_character_lodestone_class (lodestone_character_id)');
        $this->addSql('CREATE INDEX IDX_97F059C614A77B85 ON lodestone_character_lodestone_class (lodestone_class_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE lodestone_character_lodestone_class DROP FOREIGN KEY FK_97F059C6741A3286');
        $this->addSql('ALTER TABLE lodestone_character_lodestone_class DROP FOREIGN KEY FK_97F059C614A77B85');
        $this->addSql('DROP INDEX IDX_97F059C6741A3286 ON lodestone_character_lodestone_class');
        $this->addSql('DROP INDEX IDX_97F059C614A77B85 ON lodestone_character_lodestone_class');
        $this->addSql('ALTER TABLE lodestone_character_lodestone_class ADD lodestone_character_id_id INT NOT NULL, ADD lodestone_class_id_id INT NOT NULL, DROP lodestone_character_id, DROP lodestone_class_id');
        $this->addSql('ALTER TABLE lodestone_character_lodestone_class ADD CONSTRAINT FK_97F059C65C68A39 FOREIGN KEY (lodestone_class_id_id) REFERENCES lodestone_class (id)');
        $this->addSql('ALTER TABLE lodestone_character_lodestone_class ADD CONSTRAINT FK_97F059C693EB0F7D FOREIGN KEY (lodestone_character_id_id) REFERENCES lodestone_character (id)');
        $this->addSql('CREATE INDEX IDX_97F059C65C68A39 ON lodestone_character_lodestone_class (lodestone_class_id_id)');
        $this->addSql('CREATE INDEX IDX_97F059C693EB0F7D ON lodestone_character_lodestone_class (lodestone_character_id_id)');
    }
}
