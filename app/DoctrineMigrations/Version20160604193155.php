<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160604193155 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE settings');
        $this->addSql('ALTER TABLE zone ADD active TINYINT(1) NOT NULL DEFAULT "1", CHANGE relay relay SMALLINT NOT NULL, CHANGE image image SMALLINT NOT NULL');
        $this->addSql('ALTER TABLE zone RENAME INDEX relay TO UNIQ_A0EBC0075D3AE2B9');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE settings (setting VARCHAR(40) NOT NULL COLLATE latin1_swedish_ci, value VARCHAR(40) NOT NULL COLLATE latin1_swedish_ci, UNIQUE INDEX `key` (setting)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE zone DROP active, CHANGE relay relay TINYINT(1) NOT NULL, CHANGE image image TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE zone RENAME INDEX uniq_a0ebc0075d3ae2b9 TO relay');
    }
}
