<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UsersSedeer extends Seeder
{
    public function run()
    {
        // Users
        $users = [
            [
                'firstname' => 'John',
                'lastname' => 'Doe',
                'username' => 'johndoe',
                'email' => 'johndoe@example.com',
                'password' => password_hash('Gen123.', PASSWORD_DEFAULT),
                'role' => 'admin',
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]
        ];

        // Using Query Builder
        $this->db->table('users')->insertBatch($users);
    }
}
