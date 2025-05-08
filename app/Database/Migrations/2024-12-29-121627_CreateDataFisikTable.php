<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDataFisikTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_fisik' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'id_pasien' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
            'berat_badan' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
            ],
            'tinggi_badan' => [
                'type' => 'INT',
            ],
            'lingkar_perut' => [
                'type' => 'INT',
            ]
        ]);
        $this->forge->addKey('id_fisik', true);
        $this->forge->addForeignKey('id_pasien', 'pasien', 'id_pasien', 'CASCADE', 'CASCADE');
        $this->forge->createTable('data_fisik');
    }

    public function down()
    {
        $this->forge->dropTable('data_fisik');
    }
}