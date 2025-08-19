<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLanguagesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'constraint' => 10, 'null' => false],
            'title' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => false],
            'shorten' => ['type' => 'VARCHAR', 'constraint' => 10, 'null' => false],
            'rank' => ['type' => 'INT', 'unsigned' => true, 'constraint' => 10, 'null' => false, 'default' => 0],
            'data_lang' => ['type' => 'VARCHAR', 'constraint' => 10, 'null' => true, 'default' => null],
            'isActive' => ['type' => 'TINYINT', 'unsigned' => true, 'constraint' => 1, 'null' => false, 'default' => 1],
            'created_at' => ['type' => 'DATETIME', 'null' => false, 'default' => 'CURRENT_TIMESTAMP'],
            'updated_at' => ['type' => 'DATETIME', 'null' => false, 'default' => 'CURRENT_TIMESTAMP'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('languages', true, ['ENGINE' => 'InnoDB', 'DEFAULT' => 'utf8mb4']);
    }

    public function down()
    {
        $this->forge->dropTable('languages', true);
    }
}
