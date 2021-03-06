<?php

namespace VideoHosterMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20140730081350 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is autogenerated, please modify it to your needs
        $this->addSql('truncate table tblstatus');
        $this->addSql('INSERT INTO "tblstatus" VALUES (\'1\', \'active\');');
        $this->addSql('INSERT INTO "tblstatus" VALUES (\'2\', \'draft\');');

        $this->addSql('truncate table tblpaymentrequirement');
        $this->addSql('INSERT INTO "tblpaymentrequirement" VALUES (\'1\', \'paid\');');
        $this->addSql('INSERT INTO "tblpaymentrequirement" VALUES (\'2\', \'free\');');
    }

    public function down(Schema $schema)
    {
        // this down() migration is autogenerated, please modify it to your needs
        $this->addSql('truncate table tblpaymentrequirement');
        $this->addSql('truncate table tblstatus');
    }
}
