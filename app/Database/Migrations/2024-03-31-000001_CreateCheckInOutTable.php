<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCheckInOutTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_check' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'id_member' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'references' => [
                    'table' => 'tb_members',
                    'field' => 'id_member'
                ]
            ],
            'check_in' => [
                'type' => 'DATETIME',
                'null' => false
            ],
            'check_out' => [
                'type' => 'DATETIME',
                'null' => true
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['active', 'completed'],
                'default' => 'active'
            ]
        ]);

        $this->forge->addKey('id_check', true);
        $this->forge->addForeignKey('id_member', 'tb_members', 'id_member', 'CASCADE', 'CASCADE');
        $this->forge->createTable('tb_check_in_out');
    }

    public function down()
    {
        $this->forge->dropTable('tb_check_in_out');
    }
}
