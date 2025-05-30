<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAkunTable extends Migration
{
public function up()
{
    $this->forge->addField([
        'id_akun' => [
            'type'           => 'INT',
            'constraint'     => 5,
            'unsigned'       => true,
            'auto_increment' => true,
        ],
        'username' => [
            'type'       => 'VARCHAR',
            'constraint' => '100',
        ],
        'email' => [
            'type'       => 'VARCHAR',
            'constraint' => '100',
        ],
        'password' => [
            'type'       => 'VARCHAR',
            'constraint' => '255',
        ],
        'level' => [
            'type'       => 'VARCHAR',
            'constraint' => '255',
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
    $this->forge->addKey('id', true);
    $this->forge->createTable('akun');
}

public function down()
{
    $this->forge->dropTable('akun');
}
}