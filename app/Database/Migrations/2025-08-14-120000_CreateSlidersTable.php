<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSlidersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'referenceID' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
                // Not: self-referential FK istersen aşağıda addForeignKey ile ekleyebiliriz.
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'null'       => false,
            ],
            'title' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'null'       => false,
            ],
            'desc1' => [
                'type' => 'MEDIUMTEXT',
                'null' => false,
            ],
            'desc2' => [
                'type' => 'MEDIUMTEXT',
                'null' => false,
            ],
            'imgUrl' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => false,
            ],
            'allowButton' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'null'       => false,
                'default'    => 0,
            ],
            'buttonCaption' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => false,
                'default'    => '',
            ],
            'buttonUrl' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => false,
                'default'    => '',
            ],
            'rank' => [
                'type'       => 'INT',
                'unsigned'   => true,
                'null'       => false,
                'default'    => 0,
            ],
            'isActive' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'null'       => false,
                'default'    => 1,
            ],
            'data_lang' => [
                'type'       => 'VARCHAR',
                'constraint' => 5,
                'null'       => true,
            ],
            'createdUser' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => false,
            ],
            'lastUpdatedUser' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true); // primary
        $this->forge->addKey('referenceID'); // idx
        $this->forge->addKey('data_lang');   // idx

        // Self-referential FK istersen (MySQL 8+ ve uygun engine ile genelde sorunsuz):
        // $this->forge->addForeignKey('referenceID', 'sliders', 'id', 'SET NULL', 'CASCADE');

        $this->forge->createTable('sliders', true, ['ENGINE' => 'InnoDB']);
    }

    public function down()
    {
        $this->forge->dropTable('sliders', true);
    }
}
