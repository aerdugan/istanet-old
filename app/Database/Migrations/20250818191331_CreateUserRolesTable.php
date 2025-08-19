<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUserRolesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'constraint' => 10, 'null' => false],
            'user_id' => ['type' => 'INT', 'unsigned' => true, 'constraint' => 10, 'null' => false],
            'role_id' => ['type' => 'INT', 'unsigned' => true, 'constraint' => 10, 'null' => false],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('user_roles', true, ['ENGINE' => 'InnoDB', 'DEFAULT' => 'utf8mb4']);
    }

    public function down()
    {
        $this->forge->dropTable('user_roles', true);
    }
}
