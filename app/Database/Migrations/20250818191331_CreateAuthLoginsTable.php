<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAuthLoginsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'constraint' => 11, 'null' => false],
            'ip_address' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => false],
            'user_agent' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true, 'default' => null],
            'id_type' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => false],
            'identifier' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => false],
            'user_id' => ['type' => 'INT', 'unsigned' => true, 'constraint' => 11, 'null' => true, 'default' => null],
            'date' => ['type' => 'DATETIME', 'null' => false],
            'success' => ['type' => 'TINYINT', 'unsigned' => true, 'constraint' => 1, 'null' => false],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('auth_logins', true, ['ENGINE' => 'InnoDB', 'DEFAULT' => 'utf8mb4']);
    }

    public function down()
    {
        $this->forge->dropTable('auth_logins', true);
    }
}
