<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateThemesettingsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'constraint' => 11, 'null' => false],
            'colorCode' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => false],
            'colorCode2' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => false],
            'setHeader' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => false],
            'setFooter' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => false],
            'setSlider' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => false],
            'setBreadcrumbStatus' => ['type' => 'TINYINT', 'unsigned' => true, 'constraint' => 4, 'null' => false],
            'setBreadcrumb' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => false],
            'setLogo' => ['type' => 'INT', 'unsigned' => true, 'constraint' => 11, 'null' => false],
            'setFooterLogo' => ['type' => 'INT', 'unsigned' => true, 'constraint' => 11, 'null' => false],
            'isMobile' => ['type' => 'TINYINT', 'unsigned' => true, 'constraint' => 4, 'null' => false],
            'copyright' => ['type' => 'TEXT', 'null' => false],
            'openClose' => ['type' => 'TEXT', 'null' => false],
            'updatedAt' => ['type' => 'DATETIME', 'null' => false, 'default' => 'CURRENT_TIMESTAMP'],
            'lastUpdatedUser' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => false],
            'headerSocial' => ['type' => 'TINYINT', 'unsigned' => true, 'constraint' => 4, 'null' => false],
            'headerLang' => ['type' => 'TINYINT', 'unsigned' => true, 'constraint' => 4, 'null' => false],
            'footerSocial' => ['type' => 'TINYINT', 'unsigned' => true, 'constraint' => 4, 'null' => false],
            'footerContact' => ['type' => 'TINYINT', 'unsigned' => true, 'constraint' => 4, 'null' => false],
            'footerNewsLetter' => ['type' => 'TINYINT', 'unsigned' => true, 'constraint' => 4, 'null' => false],
            'cookiePolicyTitle' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => false],
            'cookiePolicyText' => ['type' => 'TEXT', 'null' => false],
            'siteStatus' => ['type' => 'TINYINT', 'unsigned' => true, 'constraint' => 4, 'null' => false],
            'siteCloseMessage' => ['type' => 'LONGTEXT', 'null' => false],
            'siteDisableTheme' => ['type' => 'INT', 'unsigned' => true, 'constraint' => 4, 'null' => false],
            'created_at' => ['type' => 'DATETIME', 'null' => false, 'default' => 'CURRENT_TIMESTAMP'],
            'createdUser' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => false],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('themeSettings', true, ['ENGINE' => 'InnoDB', 'DEFAULT' => 'utf8mb4']);
    }

    public function down()
    {
        $this->forge->dropTable('themeSettings', true);
    }
}
