<?php

namespace VideoHosterMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20140728171851 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql('DROP SEQUENCE IF EXISTS "seq_tblcategory";');
        $this->addSql('CREATE SEQUENCE "seq_tblcategory" INCREMENT 1 START 1 MAXVALUE
        9223372036854775807 MINVALUE 1 CACHE 1;');
        $this->addSql('ALTER TABLE "seq_tblcategory" OWNER TO "homestead";');
        $this->addSql('DROP SEQUENCE IF EXISTS "seq_tbllevel";');
        $this->addSql('CREATE SEQUENCE "seq_tbllevel" INCREMENT 1 START 3 MAXVALUE 9223372036854775807 MINVALUE 1 CACHE 1;');
        $this->addSql('ALTER TABLE "seq_tbllevel" OWNER TO "homestead";');
        $this->addSql('DROP SEQUENCE IF EXISTS "seq_tblseries";');
        $this->addSql('CREATE SEQUENCE "seq_tblseries" INCREMENT 1 START 1 MAXVALUE 9223372036854775807 MINVALUE 1 CACHE 1;');
        $this->addSql('ALTER TABLE "seq_tblseries" OWNER TO "homestead";');
        $this->addSql('DROP SEQUENCE IF EXISTS "seq_tblstatus";');
        $this->addSql('CREATE SEQUENCE "seq_tblstatus" INCREMENT 1 START 2 MAXVALUE 9223372036854775807 MINVALUE 1 CACHE 1;');
        $this->addSql('ALTER TABLE "seq_tblstatus" OWNER TO "homestead";');
        $this->addSql('DROP SEQUENCE IF EXISTS "seq_tblvideo";');
        $this->addSql('CREATE SEQUENCE "seq_tblvideo" INCREMENT 1 START 1 MAXVALUE 9223372036854775807 MINVALUE 1 CACHE 1;');
        $this->addSql('ALTER TABLE "seq_tblvideo" OWNER TO "homestead";');
        $this->addSql('DROP SEQUENCE IF EXISTS "user_user_id_seq";');
        $this->addSql('CREATE SEQUENCE "user_user_id_seq" INCREMENT 1 START 1 MAXVALUE 9223372036854775807 MINVALUE 1 CACHE 1;');
        $this->addSql('ALTER TABLE "user_user_id_seq" OWNER TO "homestead";');
        $this->addSql('DROP TABLE IF EXISTS "tbllevel";');
        $this->addSql('
          CREATE TABLE "tbllevel" ("levelId" int8 NOT NULL DEFAULT nextval(\'seq_tbllevel\'::regclass),"name" char(15) NOT NULL)
            WITH (OIDS=FALSE);');
        $this->addSql('ALTER TABLE "tbllevel" OWNER TO "homestead";');
        $this->addSql('INSERT INTO "tbllevel" VALUES (\'1\', \'basic\');');
        $this->addSql('INSERT INTO "tbllevel" VALUES (\'2\', \'intermediate\');');
        $this->addSql('INSERT INTO "tbllevel" VALUES (\'3\', \'advanced\');');
        $this->addSql('DROP TABLE IF EXISTS "tblvideo";');
        $this->addSql('
            CREATE TABLE "tblvideo" (
                "videoId" int8 NOT NULL DEFAULT nextval(\'seq_tblvideo\'::regclass),
                "name" char(200) NOT NULL,
                "authorId" int8 NOT NULL,
                "statusId" int8 NOT NULL,
                "description" char(150) NOT NULL,
                "extract" char(50),
                "duration" int4 NOT NULL,
                "publishDate" date NOT NULL,
                "publishTime" time(6) NOT NULL,
                "levelId" int8 NOT NULL
            )
            WITH (OIDS=FALSE);');
        $this->addSql('ALTER TABLE "tblvideo" OWNER TO "homestead";');
        $this->addSql('DROP TABLE IF EXISTS "tblvideocategories";');
        $this->addSql('
            CREATE TABLE "tblvideocategories" (
                "videoId" int8 NOT NULL,
                "categoryId" int8 NOT NULL
            )
            WITH (OIDS=FALSE);');
        $this->addSql('ALTER TABLE "tblvideocategories" OWNER TO "homestead";');
        $this->addSql('DROP TABLE IF EXISTS "tblcategory";');
        $this->addSql('
            CREATE TABLE "tblcategory" (
                "categoryId" int8 NOT NULL DEFAULT nextval(\'seq_tblcategory\'::regclass),
                "name" char(50) NOT NULL
            )
            WITH (OIDS=FALSE);');
        $this->addSql('ALTER TABLE "tblcategory" OWNER TO "homestead";');
        $this->addSql('INSERT INTO "tblcategory" VALUES (\'1\', \'open\');');
        $this->addSql('INSERT INTO "tblcategory" VALUES (\'2\', \'closed\');');
        $this->addSql('DROP TABLE IF EXISTS "tblseries";');
        $this->addSql('
            CREATE TABLE "tblseries" (
                "seriesId" int8 NOT NULL DEFAULT nextval(\'seq_tblseries\'::regclass),
                "name" char(50) NOT NULL,
                "description" char(150) NOT NULL
            )
            WITH (OIDS=FALSE);');
        $this->addSql('ALTER TABLE "tblseries" OWNER TO "homestead";');
        $this->addSql('DROP TABLE IF EXISTS "tblseriesvideos";');
        $this->addSql('
            CREATE TABLE "tblseriesvideos" (
                "seriesId" int8 NOT NULL,
                "videoId" int8 NOT NULL
            )
            WITH (OIDS=FALSE);');
        $this->addSql('ALTER TABLE "tblseriesvideos" OWNER TO "homestead";');
        $this->addSql('DROP TABLE IF EXISTS "tblstatus";');
        $this->addSql('
            CREATE TABLE "tblstatus" (
                "statusId" int8 NOT NULL DEFAULT nextval(\'seq_tblstatus\'::regclass),
                "name" char(15) NOT NULL
            )
            WITH (OIDS=FALSE);');
        $this->addSql('ALTER TABLE "tblstatus" OWNER TO "homestead";');
        $this->addSql('INSERT INTO "tblstatus" VALUES (\'1\', \'free\');');
        $this->addSql('INSERT INTO "tblstatus" VALUES (\'2\', \'paid\');');
        $this->addSql('DROP TABLE IF EXISTS "tbluser";');
        $this->addSql('
            CREATE TABLE "tbluser" (
                "user_id" int4 NOT NULL DEFAULT nextval(\'user_user_id_seq\'::regclass),
                "username" varchar(255) DEFAULT NULL::character varying,
                "email" varchar(255) DEFAULT NULL::character varying,
                "display_name" varchar(50) DEFAULT NULL::character varying,
                "password" varchar(128) NOT NULL,
                "state" int2
            )
            WITH (OIDS=FALSE);
            ');
        $this->addSql('ALTER TABLE "tbluser" OWNER TO "homestead";');
        $this->addSql('ALTER SEQUENCE "seq_tblcategory" OWNED BY "tblcategory"."categoryId";');
        $this->addSql('ALTER SEQUENCE "seq_tbllevel" OWNED BY "tbllevel"."levelId";');
        $this->addSql('ALTER SEQUENCE "seq_tblseries" OWNED BY "tblseries"."seriesId";');
        $this->addSql('ALTER SEQUENCE "seq_tblstatus" OWNED BY "tblstatus"."statusId";');
        $this->addSql('ALTER SEQUENCE "seq_tblvideo" OWNED BY "tblvideo"."videoId";');
        $this->addSql('ALTER SEQUENCE "user_user_id_seq" OWNED BY "tbluser"."user_id";');
        $this->addSql('ALTER TABLE "tbllevel" ADD CONSTRAINT "tbllevel_pkey" PRIMARY KEY ("levelId") NOT DEFERRABLE INITIALLY IMMEDIATE;');
        $this->addSql('CREATE UNIQUE INDEX "tbllevel_levelId_key" ON "tbllevel" USING btree("levelId" ASC NULLS LAST);');
        $this->addSql('ALTER TABLE "tblvideo" ADD CONSTRAINT "tblvideos_pkey" PRIMARY KEY ("videoId") NOT DEFERRABLE INITIALLY IMMEDIATE;');
        $this->addSql('CREATE UNIQUE INDEX "tblvideo_videoId_key" ON "tblvideo" USING btree("videoId" ASC NULLS LAST);');
        $this->addSql('CREATE UNIQUE INDEX "uniq_lc_videoname" ON "tblvideo" USING btree(lower(name::text) ASC NULLS LAST);');
        $this->addSql('ALTER TABLE "tblvideocategories" ADD CONSTRAINT "tblvideocategories_pkey" PRIMARY KEY ("categoryId", "videoId") NOT DEFERRABLE INITIALLY IMMEDIATE;');
        $this->addSql('ALTER TABLE "tblcategory" ADD CONSTRAINT "tblcategory_pkey" PRIMARY KEY ("categoryId") NOT DEFERRABLE INITIALLY IMMEDIATE;');
        $this->addSql('CREATE UNIQUE INDEX "tblcategory_categoryId_key" ON "tblcategory" USING btree("categoryId" ASC NULLS LAST);');
        $this->addSql('ALTER TABLE "tblseries" ADD CONSTRAINT "tblseries_pkey" PRIMARY KEY ("seriesId") NOT DEFERRABLE INITIALLY IMMEDIATE;');
        $this->addSql('CREATE UNIQUE INDEX "tblseries_seriesId_key" ON "tblseries" USING btree("seriesId" ASC NULLS LAST);');
        $this->addSql('ALTER TABLE "tblseriesvideos" ADD CONSTRAINT "tblseriesvideos_pkey" PRIMARY KEY ("seriesId", "videoId") NOT DEFERRABLE INITIALLY IMMEDIATE;');
        $this->addSql('ALTER TABLE "tblstatus" ADD CONSTRAINT "tblstatus_pkey" PRIMARY KEY ("statusId") NOT DEFERRABLE INITIALLY IMMEDIATE;');
        $this->addSql('CREATE UNIQUE INDEX "tblstatus_statusId_key" ON "tblstatus" USING btree("statusId" ASC NULLS LAST);');
        $this->addSql('ALTER TABLE "tbluser" ADD CONSTRAINT "user_pkey" PRIMARY KEY ("user_id") NOT DEFERRABLE INITIALLY IMMEDIATE;');
        $this->addSql('CREATE UNIQUE INDEX "tbluser_user_id_key" ON "tbluser" USING btree(user_id ASC NULLS LAST);
        ');

    }

    public function down(Schema $schema)
    {
        // this down() migration is autogenerated, please modify it to your needs
        $this->addSql('drop table "tblcategory";');
        $this->addSql('drop table "tbllevel";');
        $this->addSql('drop table "tblseries";');
        $this->addSql('drop table "tblseriesvideos";');
        $this->addSql('drop table "tblstatus";');
        $this->addSql('drop table "tbluser";');
        $this->addSql('drop table "tblvideo";');
        $this->addSql('drop table "tblvideocategories";');
    }
}
