<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDataMetabolikTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_metabolik' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'id_pasien' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
            'tekanan_darah' => [
                'type' => 'VARCHAR',
                'constraint' => '20',
            ],
            'gula_darah' => [
                'type' => 'INT',
            ],
            'asam_urat' => [
                'type' => 'FLOAT',
            ],
            'kolesterol' => [
                'type' => 'INT',
            ],
        ]);
        $this->forge->addKey('id_metabolik', true);
        $this->forge->addForeignKey('id_pasien', 'pasien', 'id_pasien', 'CASCADE', 'CASCADE');
        $this->forge->createTable('data_metabolik');
    }

    public function down()
    {
        $this->forge->dropTable('data_metabolik');
    }
}