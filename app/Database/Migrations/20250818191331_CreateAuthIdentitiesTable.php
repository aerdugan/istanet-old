<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAuthIdentitiesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'constraint' => 11, 'null' => false],
            'user_id' => ['type' => 'INT', 'unsigned' => true, 'constraint' => 11, 'null' => false],
            'type' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => false],
            'name' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true, 'default' => null],
            'secret' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => false],
            'secret2' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true, 'default' => null],
            'expires' => ['type' => 'DATETIME', 'null' => true, 'default' => null],
            'extra' => ['type' => 'TEXT', 'null' => true, 'default' => null],
            'force_reset' => ['type' => 'TINYINT', 'unsigned' => true, 'constraint' => 1, 'null' => false, 'default' => 0],
            'last_used_at' => ['type' => 'DATETIME', 'null' => true, 'default' => null],
            'created_at' => ['type' => 'DATETIME', 'null' => true, 'default' => null],
            'updated_at' => ['type' => 'DATETIME', 'null' => true, 'default' => null],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('auth_identities', true, ['ENGINE' => 'InnoDB', 'DEFAULT' => 'utf8mb4']);
    }

    public function down()
    {
        $this->forge->dropTable('auth_identities', true);
    }
}
