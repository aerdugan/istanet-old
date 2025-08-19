<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSettingsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'constraint' => 9, 'null' => false],
            'class' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => false],
            'key' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => false],
            'value' => ['type' => 'LONGTEXT', 'null' => true, 'default' => null],
            'type' => ['type' => 'VARCHAR', 'constraint' => 31, 'null' => false, 'default' => 'STRING'],
            'context' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true, 'default' => null],
            'created_at' => ['type' => 'DATETIME', 'null' => false],
            'updated_at' => ['type' => 'DATETIME', 'null' => false],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('settings', true, ['ENGINE' => 'InnoDB', 'DEFAULT' => 'utf8mb4']);
    }

    public function down()
    {
        $this->forge->dropTable('settings', true);
    }
}
