<?php

namespace App\Models;

use CodeIgniter\Model;

class AbsensiKaryawanModel extends Model
{
    protected $table = 'tb_absensi_karyawan';
    protected $primaryKey = 'id_absensi';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    
    protected $allowedFields = [
        'id_karyawan', 'tanggal', 'jam_masuk', 'jam_keluar', 
        'status', 'keterangan'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'id_karyawan' => 'required|numeric',
        'tanggal' => 'required|valid_date',
        'jam_masuk' => 'permit_empty',
        'jam_keluar' => 'permit_empty',
        'status' => 'required',
    ];

    protected $validationMessages = [
        'id_karyawan' => [
            'required' => 'ID Karyawan harus diisi',
            'numeric' => 'ID Karyawan harus berupa angka'
        ],
        'tanggal' => [
            'required' => 'Tanggal harus diisi',
            'valid_date' => 'Format tanggal tidak valid'
        ],
        'status' => [
            'required' => 'Status absensi harus dipilih'
        ]
    ];

    /**
     * Mendapatkan data absensi berdasarkan ID
     */
    public function getAbsensi($id = null)
    {
        if ($id === null) {
            return $this->findAll();
        }
        
        return $this->find($id);
    }

    /**
     * Mendapatkan absensi karyawan berdasarkan ID karyawan
     */
    public function getAbsensiByKaryawan($id_karyawan)
    {
        return $this->where('id_karyawan', $id_karyawan)->findAll();
    }

    /**
     * Mendapatkan absensi berdasarkan tanggal
     */
    public function getAbsensiByDate($tanggal)
    {
        return $this->where('tanggal', $tanggal)->findAll();
    }

    /**
     * Mendapatkan absensi karyawan berdasarkan rentang tanggal
     */
    public function getAbsensiByDateRange($id_karyawan, $start_date, $end_date)
    {
        return $this->where('id_karyawan', $id_karyawan)
                    ->where('tanggal >=', $start_date)
                    ->where('tanggal <=', $end_date)
                    ->findAll();
    }

    /**
     * Cek apakah karyawan sudah absen masuk hari ini
     */
    public function checkClockIn($id_karyawan, $tanggal)
    {
        return $this->where('id_karyawan', $id_karyawan)
                    ->where('tanggal', $tanggal)
                    ->where('jam_masuk IS NOT NULL', null, false)
                    ->first();
    }

    /**
     * Cek apakah karyawan sudah absen keluar hari ini
     */
    public function checkClockOut($id_karyawan, $tanggal)
    {
        return $this->where('id_karyawan', $id_karyawan)
                    ->where('tanggal', $tanggal)
                    ->where('jam_keluar IS NOT NULL', null, false)
                    ->first();
    }

    /**
     * Mendapatkan data absensi karyawan dengan join ke tabel karyawan
     */
    public function getAbsensiWithKaryawan()
    {
        return $this->select('tb_absensi_karyawan.*, tb_karyawan.nama as nama_karyawan, tb_karyawan.kode_karyawan')
                    ->join('tb_karyawan', 'tb_karyawan.id_karyawan = tb_absensi_karyawan.id_karyawan')
                    ->findAll();
    }

    /**
     * Mendapatkan data absensi hari ini
     */
    public function getTodayAbsensi()
    {
        $today = date('Y-m-d');
        return $this->select('tb_absensi_karyawan.*, tb_karyawan.nama as nama_karyawan, tb_karyawan.kode_karyawan')
                    ->join('tb_karyawan', 'tb_karyawan.id_karyawan = tb_absensi_karyawan.id_karyawan')
                    ->where('tb_absensi_karyawan.tanggal', $today)
                    ->findAll();
    }
}
