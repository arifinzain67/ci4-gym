<?php

namespace App\Models;

use CodeIgniter\Model;

class LoginAttemptModel extends Model
{
    protected $table = 'tb_login_attempts';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = ['ip_address', 'username', 'time'];
    protected $useTimestamps = false;
    
    /**
     * Menambahkan percobaan login yang gagal
     */
    public function addAttempt(string $ipAddress, string $username)
    {
        $this->insert([
            'ip_address' => $ipAddress,
            'username' => $username,
            'time' => time()
        ]);
    }
    
    /**
     * Memeriksa apakah IP telah mencapai batas percobaan login
     */
    public function isIPBlocked(string $ipAddress): bool
    {
        $timeLimit = time() - (15 * 60); // 15 menit
        $maxAttempts = 5; // 5 percobaan
        
        $attempts = $this->where('ip_address', $ipAddress)
                         ->where('time >', $timeLimit)
                         ->countAllResults();
        
        return $attempts >= $maxAttempts;
    }
    
    /**
     * Memeriksa apakah username telah mencapai batas percobaan login
     */
    public function isUsernameBlocked(string $username): bool
    {
        $timeLimit = time() - (15 * 60); // 15 menit
        $maxAttempts = 5; // 5 percobaan
        
        $attempts = $this->where('username', $username)
                         ->where('time >', $timeLimit)
                         ->countAllResults();
        
        return $attempts >= $maxAttempts;
    }
    
    /**
     * Membersihkan percobaan login lama
     */
    public function purgeOldAttempts()
    {
        $this->where('time <', time() - (24 * 60 * 60))->delete();
    }
}
