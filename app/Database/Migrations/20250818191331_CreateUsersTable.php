<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'constraint' => 11, 'null' => false],
            'username' => ['type' => 'VARCHAR', 'constraint' => 30, 'null' => true, 'default' => null],
            'status' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true, 'default' => null],
            'status_message' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true, 'default' => null],
            'active' => ['type' => 'TINYINT', 'unsigned' => true, 'constraint' => 1, 'null' => false, 'default' => 0],
            'last_active' => ['type' => 'DATETIME', 'null' => true, 'default' => null],
            'created_at' => ['type' => 'DATETIME', 'null' => true, 'default' => null],
            'updated_at' => ['type' => 'DATETIME', 'null' => true, 'default' => null],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true, 'default' => null],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('users', true, ['ENGINE' => 'InnoDB', 'DEFAULT' => 'utf8mb4']);
    }

    public function down()
    {
        $this->forge->dropTable('users', true);
    }
}
