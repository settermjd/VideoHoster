<?php

namespace VideoHosterMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20141117085122 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is autogenerated, please modify it to your needs
        $this->addSql('ALTER TABLE "tblvideo" ADD COLUMN "slug" char(200) NOT NULL');
    }

    public function down(Schema $schema)
    {
        // this down() migration is autogenerated, please modify it to your needs
        $this->addSql('ALTER TABLE "tblvideo" DROP COLUMN "slug"');

    }
}
