<?php

namespace App\Models;

use CodeIgniter\Model;

class KaryawanModel extends Model
{
    protected $table = 'tb_karyawan';
    protected $primaryKey = 'id_karyawan';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    
    protected $allowedFields = [
        'kode_karyawan', 'nama', 'jenis_kelamin', 'tanggal_lahir', 
        'alamat', 'no_telepon', 'email', 'posisi', 'tanggal_bergabung', 
        'status', 'gaji', 'foto'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'kode_karyawan' => 'required',
        'nama' => 'required|min_length[3]|max_length[100]',
        'jenis_kelamin' => 'required',
        'tanggal_lahir' => 'permit_empty|valid_date',
        'no_telepon' => 'permit_empty|max_length[15]',
        'email' => 'permit_empty|valid_email|max_length[100]',
        'posisi' => 'required|max_length[50]',
        'tanggal_bergabung' => 'required|valid_date',
        'status' => 'required',
        'gaji' => 'permit_empty|numeric',
    ];

    protected $validationMessages = [
        'kode_karyawan' => [
            'required' => 'Kode karyawan harus diisi',
        ],
        'nama' => [
            'required' => 'Nama karyawan harus diisi',
            'min_length' => 'Nama karyawan minimal 3 karakter',
            'max_length' => 'Nama karyawan maksimal 100 karakter'
        ],
        'jenis_kelamin' => [
            'required' => 'Jenis kelamin harus dipilih'
        ],
        'tanggal_lahir' => [
            'valid_date' => 'Format tanggal lahir tidak valid'
        ],
        'email' => [
            'valid_email' => 'Format email tidak valid',
            'max_length' => 'Email maksimal 100 karakter'
        ],
        'posisi' => [
            'required' => 'Posisi harus diisi',
            'max_length' => 'Posisi maksimal 50 karakter'
        ],
        'tanggal_bergabung' => [
            'required' => 'Tanggal bergabung harus diisi',
            'valid_date' => 'Format tanggal bergabung tidak valid'
        ],
        'status' => [
            'required' => 'Status harus dipilih'
        ],
        'gaji' => [
            'numeric' => 'Gaji harus berupa angka'
        ]
    ];

    /**
     * Mendapatkan data karyawan berdasarkan ID
     */
    public function getKaryawan($id = null)
    {
        if ($id === null) {
            return $this->findAll();
        }
        
        return $this->find($id);
    }

    /**
     * Mendapatkan data karyawan aktif
     */
    public function getActiveKaryawan()
    {
        return $this->where('status', 'Aktif')->findAll();
    }

    /**
     * Menghasilkan kode karyawan otomatis dengan format KRY-YYYYMMDD-XXX
     */
    public function generateKodeKaryawan()
    {
        $date = date('Ymd');
        $lastKaryawan = $this->orderBy('id_karyawan', 'DESC')->first();
        
        if (!$lastKaryawan) {
            return "KRY-{$date}-001";
        }
        
        // Cek apakah kode sudah ada dengan pola yang sama
        $lastCode = $lastKaryawan['kode_karyawan'];
        $lastNumber = 0;
        
        if (preg_match("/KRY-{$date}-(\d{3})/", $lastCode, $matches)) {
            $lastNumber = (int)$matches[1];
        }
        
        $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        return "KRY-{$date}-{$newNumber}";
    }

    // Untuk membantu debug process update
    public function update($id = null, $data = null): bool
    {
        log_message('debug', 'KaryawanModel - Update dipanggil dengan ID: ' . $id);
        log_message('debug', 'KaryawanModel - Data: ' . json_encode($data));
        
        $result = parent::update($id, $data);
        
        log_message('debug', 'KaryawanModel - Hasil update: ' . ($result ? 'Berhasil' : 'Gagal'));
        log_message('debug', 'KaryawanModel - Error (jika ada): ' . json_encode($this->errors()));
        
        return $result;
    }
}
