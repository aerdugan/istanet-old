<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateQualitycertificatesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'constraint' => 11, 'null' => false],
            'title' => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => false],
            'imgUrl' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => false],
            'iconUrl' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => false],
            'rank' => ['type' => 'INT', 'unsigned' => true, 'constraint' => 4, 'null' => true, 'default' => null],
            'isActive' => ['type' => 'TINYINT', 'unsigned' => true, 'constraint' => 1, 'null' => true, 'default' => null],
            'data_lang' => ['type' => 'VARCHAR', 'constraint' => 5, 'null' => true, 'default' => null],
            'created_at' => ['type' => 'TIMESTAMP', 'null' => false, 'default' => 'CURRENT_TIMESTAMP'],
            'createdUser' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => false],
            'updated_at' => ['type' => 'TIMESTAMP', 'null' => false, 'default' => '0000-00-00 00:00:00'],
            'lastUpdatedUser' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => false],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('qualityCertificates', true, ['ENGINE' => 'InnoDB', 'DEFAULT' => 'utf8mb4']);
    }

    public function down()
    {
        $this->forge->dropTable('qualityCertificates', true);
    }
}
