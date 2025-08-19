<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAuthRememberTokensTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'constraint' => 11, 'null' => false],
            'selector' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => false],
            'hashedValidator' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => false],
            'user_id' => ['type' => 'INT', 'unsigned' => true, 'constraint' => 11, 'null' => false],
            'expires' => ['type' => 'DATETIME', 'null' => false],
            'created_at' => ['type' => 'DATETIME', 'null' => false],
            'updated_at' => ['type' => 'DATETIME', 'null' => false],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('auth_remember_tokens', true, ['ENGINE' => 'InnoDB', 'DEFAULT' => 'utf8mb4']);
    }

    public function down()
    {
        $this->forge->dropTable('auth_remember_tokens', true);
    }
}
