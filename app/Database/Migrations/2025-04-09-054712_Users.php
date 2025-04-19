<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class Users extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_user' => [
                'type'           => 'int',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'firstname' => [
                'type'       => 'varchar',
                'constraint' => 150,
            ],
            'lastname' => [
                'type'       => 'varchar',
                'constraint' => 150,
            ],
            'email' => [
                'type'       => 'varchar',
                'constraint' => 150,
            ],
            'password' => [
                'type'       => 'varchar',
                'constraint' => 255,
            ],
            'last_login_at' => [
                'type'       => 'datetime',
                'default'    => null,
            ],
            'role' => [
                'type'       => 'ENUM',
                'constraint' => ['admin', 'user'],
                'default'    => 'user',
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['active', 'inactive'],
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
        $this->forge->addKey('id_user', true);
        $this->forge->createTable('users');
    }

    public function down()
    {
        $this->forge->dropTable('users');
    }
}
