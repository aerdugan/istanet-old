<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRolePermissionsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'constraint' => 10, 'null' => false],
            'role_id' => ['type' => 'INT', 'unsigned' => true, 'constraint' => 10, 'null' => false],
            'permission_id' => ['type' => 'INT', 'unsigned' => true, 'constraint' => 10, 'null' => false],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('role_permissions', true, ['ENGINE' => 'InnoDB', 'DEFAULT' => 'utf8mb4']);
    }

    public function down()
    {
        $this->forge->dropTable('role_permissions', true);
    }
}
