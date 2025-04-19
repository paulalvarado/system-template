<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class UserMeta extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_usermeta' => [
                'type'           => 'int',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_user' => [
                'type'           => 'int',
                'constraint'     => 11,
                'not_null'       => true,
            ],
            'meta_key' => [
                'type'       => 'varchar',
                'constraint' => 150,
            ],
            'meta_value' => [
                'type'       => 'text',
                'default' => null,
            ],
            'created_at' => [
                'type'       => 'datetime',
                'default'    => new RawSql('CURRENT_TIMESTAMP'),
            ],
            'updated_at' => [
                'type'       => 'datetime',
                'default'    => new RawSql('CURRENT_TIMESTAMP'),
            ],
        ]);
        $this->forge->addKey('id_usermeta', true);
        $this->forge->createTable('usermeta');
    }

    public function down()
    {
        $this->forge->dropTable('usermeta');
    }
}
