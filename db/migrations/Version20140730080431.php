<?php

namespace VideoHosterMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20140730080431 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is autogenerated, please modify it to your needs
        $this->addSql('ALTER TABLE "tblvideo" add column "paymentRequirementId" int8 NOT NULL;');
        $this->addSql('DROP SEQUENCE IF EXISTS "seq_tblpaymentrequirement";');
        $this->addSql('CREATE SEQUENCE "seq_tblpaymentrequirement" INCREMENT 1 START 1 MAXVALUE 9223372036854775807 MINVALUE 1 CACHE 1;');
        $this->addSql('
            CREATE TABLE "tblpaymentrequirement" (
                "paymentRequirementId" int8 NOT NULL
                  DEFAULT nextval(\'seq_tblpaymentrequirement\'::regclass),
                "name" char(200) NOT NULL,
                "description" char(150) NULL
            )
            WITH (OIDS=FALSE);');
        $this->addSql('ALTER SEQUENCE "seq_tblpaymentrequirement" OWNED BY "tblpaymentrequirement"."paymentRequirementId";');
    }

    public function down(Schema $schema)
    {
        // this down() migration is autogenerated, please modify it to your needs
        $this->addSql('ALTER TABLE "tblvideo" drop column "paymentRequirementId"');
        $this->addSql('drop table tblpaymentrequirement');
    }
}
