<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePagesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'constraint' => 11, 'null' => false],
            'referenceID' => ['type' => 'INT', 'unsigned' => true, 'constraint' => 4, 'null' => true, 'default' => null],
            'title' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => false],
            'parent_id' => ['type' => 'INT', 'unsigned' => true, 'constraint' => 11, 'null' => false],
            'url' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => false],
            'mobileUrl' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => false],
            'rank' => ['type' => 'INT', 'unsigned' => true, 'constraint' => 11, 'null' => false],
            'mobileRank' => ['type' => 'INT', 'unsigned' => true, 'constraint' => 11, 'null' => false],
            'breadcrumbStatus' => ['type' => 'TINYINT', 'unsigned' => true, 'constraint' => 4, 'null' => false],
            'breadcrumbTitle' => ['type' => 'TEXT', 'null' => false],
            'breadcrumbSlogan' => ['type' => 'TEXT', 'null' => false],
            'breadcrumbImageStatus' => ['type' => 'TINYINT', 'unsigned' => true, 'constraint' => 4, 'null' => false],
            'breadcrumbImage' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => false],
            'inpHtml' => ['type' => 'LONGTEXT', 'null' => false],
            'mobileHtml' => ['type' => 'LONGTEXT', 'null' => false],
            'cBoxMainCss' => ['type' => 'LONGTEXT', 'null' => false],
            'cBoxSectionCss' => ['type' => 'LONGTEXT', 'null' => false],
            'cBoxContent' => ['type' => 'LONGTEXT', 'null' => false],
            'cBoxMobileMainCss' => ['type' => 'LONGTEXT', 'null' => false],
            'cBoxMobileSectionCss' => ['type' => 'LONGTEXT', 'null' => false],
            'cBoxMobileContent' => ['type' => 'LONGTEXT', 'null' => false],
            'isActive' => ['type' => 'TINYINT', 'unsigned' => true, 'constraint' => 4, 'null' => false],
            'isHeader' => ['type' => 'TINYINT', 'unsigned' => true, 'constraint' => 4, 'null' => false],
            'isFooter' => ['type' => 'TINYINT', 'unsigned' => true, 'constraint' => 4, 'null' => false],
            'isMobile' => ['type' => 'TINYINT', 'unsigned' => true, 'constraint' => 4, 'null' => false],
            'isMobileFooter' => ['type' => 'TINYINT', 'unsigned' => true, 'constraint' => 4, 'null' => false],
            'isWebEditor' => ['type' => 'TINYINT', 'unsigned' => true, 'constraint' => 4, 'null' => false],
            'isMobileEditor' => ['type' => 'TINYINT', 'unsigned' => true, 'constraint' => 4, 'null' => false],
            'data_lang' => ['type' => 'VARCHAR', 'constraint' => 5, 'null' => true, 'default' => null],
            'seoKeywords' => ['type' => 'TEXT', 'null' => true, 'default' => null],
            'seoDesc' => ['type' => 'TEXT', 'null' => true, 'default' => null],
            'createdAt' => ['type' => 'TIMESTAMP', 'null' => false, 'default' => 'CURRENT_TIMESTAMP'],
            'createdUser' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => false],
            'updated_at' => ['type' => 'TIMESTAMP', 'null' => false, 'default' => '0000-00-00 00:00:00'],
            'lastUpdatedUser' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => false],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('pages', true, ['ENGINE' => 'InnoDB', 'DEFAULT' => 'utf8mb4']);
    }

    public function down()
    {
        $this->forge->dropTable('pages', true);
    }
}
