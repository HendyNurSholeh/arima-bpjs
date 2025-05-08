<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStatusHipertensiTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_status' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'id_pasien' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['ya', 'tidak'],
                'default' => 'tidak',
            ],
        ]);
        $this->forge->addKey('id_status', true);
        $this->forge->addForeignKey('id_pasien', 'pasien', 'id_pasien', 'CASCADE', 'CASCADE');
        $this->forge->createTable('status_hipertensi');
    }

    public function down()
    {
        $this->forge->dropTable('status_hipertensi');
    }
}