<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateContactsettingsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'constraint' => 11, 'null' => false],
            'title' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => false],
            'url' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => false],
            'phone_1' => ['type' => 'VARCHAR', 'constraint' => 17, 'null' => true, 'default' => null],
            'phone_2' => ['type' => 'VARCHAR', 'constraint' => 17, 'null' => true, 'default' => null],
            'fax_1' => ['type' => 'VARCHAR', 'constraint' => 17, 'null' => true, 'default' => null],
            'fax_2' => ['type' => 'VARCHAR', 'constraint' => 17, 'null' => true, 'default' => null],
            'email' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true, 'default' => null],
            'address' => ['type' => 'TEXT', 'null' => true, 'default' => null],
            'address_location' => ['type' => 'LONGTEXT', 'null' => true, 'default' => null],
            'taxNumber' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => false],
            'taxAdministration' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => false],
            'registrationNumber' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => false],
            'rank' => ['type' => 'INT', 'unsigned' => true, 'constraint' => 11, 'null' => false, 'default' => 0],
            'isActive' => ['type' => 'TINYINT', 'unsigned' => true, 'constraint' => 4, 'null' => false],
            'createdUser' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => false],
            'createdAt' => ['type' => 'DATETIME', 'null' => false],
            'updatedAt' => ['type' => 'DATETIME', 'null' => true, 'default' => null],
            'lastUpdatedUser' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => false],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('contactSettings', true, ['ENGINE' => 'InnoDB', 'DEFAULT' => 'utf8mb4']);
    }

    public function down()
    {
        $this->forge->dropTable('contactSettings', true);
    }
}
