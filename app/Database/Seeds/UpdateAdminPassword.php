<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UpdateAdminPassword extends Seeder
{
    public function run()
    {
        $data = [
            'password' => password_hash('password', PASSWORD_DEFAULT)
        ];

        // Using Query Builder
        $this->db->table('tb_user')
            ->where('username', 'admin')
            ->update($data);
    }
}
