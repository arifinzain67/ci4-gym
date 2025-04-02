<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'tb_user';
    protected $primaryKey = 'id_user';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['username', 'password', 'name', 'role', 'id_karyawan'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function findByUsername(string $username)
    {
        log_message('debug', '[UserModel] Finding user by username: ' . $username);
        
        // Join dengan tabel karyawan untuk mendapatkan status
        $builder = $this->db->table($this->table);
        $builder->select($this->table.'.*');
        $builder->select('tb_karyawan.status as karyawan_status');
        $builder->join('tb_karyawan', $this->table.'.id_karyawan = tb_karyawan.id_karyawan', 'left');
        $builder->where($this->table.'.username', $username);
        $query = $builder->get();
        
        $user = $query->getRowArray();
        
        if ($user) {
            log_message('debug', '[UserModel] User found with data: ' . json_encode($user));
            // Log khusus untuk status karyawan
            log_message('debug', '[UserModel] Karyawan status: ' . ($user['karyawan_status'] ?? 'not set'));
        } else {
            log_message('debug', '[UserModel] User not found');
        }
        
        return $user;
    }

    public function verifyPassword(array $user, string $password): bool
    {
        log_message('debug', '[UserModel] Verifying password for user: ' . $user['username']);
        $verified = password_verify($password, $user['password']);
        log_message('debug', '[UserModel] Password verified: ' . ($verified ? 'Yes' : 'No'));
        return $verified;
    }
}
