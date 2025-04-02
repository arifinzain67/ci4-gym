<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AbsensiKaryawanModel;
use App\Models\KaryawanModel;

class AbsensiKaryawanController extends BaseController
{
    protected $absensiModel;
    protected $karyawanModel;

    public function __construct()
    {
        $this->absensiModel = new AbsensiKaryawanModel();
        $this->karyawanModel = new KaryawanModel();
    }

    // Halaman daftar absensi karyawan
    public function index()
    {
        $tanggal = $this->request->getGet('tanggal') ?? date('Y-m-d');
        
        $data = [
            'title' => 'Data Absensi Karyawan',
            'tanggal' => $tanggal,
            'absensi' => $this->absensiModel->select('tb_absensi_karyawan.*, tb_karyawan.nama, tb_karyawan.kode_karyawan')
                                           ->join('tb_karyawan', 'tb_karyawan.id_karyawan = tb_absensi_karyawan.id_karyawan')
                                           ->where('tanggal', $tanggal)
                                           ->findAll(),
            'karyawan' => $this->karyawanModel->findAll()
        ];

        return view('absensi_karyawan/index', $data);
    }

    // Proses absen masuk karyawan
    public function clockIn()
    {
        if (!$this->validate([
            'id_karyawan' => 'required|numeric',
            'tanggal' => 'required|valid_date',
            'jam_masuk' => 'required'
        ])) {
            return redirect()->back()->with('error', 'Data absensi tidak valid')->withInput();
        }

        $id_karyawan = $this->request->getPost('id_karyawan');
        $tanggal = $this->request->getPost('tanggal');
        $jam_masuk = $this->request->getPost('jam_masuk');
        $keterangan = $this->request->getPost('keterangan');

        // Cek apakah sudah absen masuk
        $existing = $this->absensiModel->where('id_karyawan', $id_karyawan)
                                      ->where('tanggal', $tanggal)
                                      ->first();

        if ($existing) {
            if (!empty($existing['jam_masuk'])) {
                return redirect()->back()->with('error', 'Karyawan sudah melakukan absen masuk hari ini');
            }
            
            // Update jam masuk jika sudah ada record tapi belum absen masuk
            $this->absensiModel->update($existing['id_absensi'], [
                'jam_masuk' => $jam_masuk,
                'keterangan' => $keterangan
            ]);
            
            return redirect()->back()->with('success', 'Absen masuk berhasil');
        }

        // Jika belum ada record absensi, buat baru
        $data = [
            'id_karyawan' => $id_karyawan,
            'tanggal' => $tanggal,
            'jam_masuk' => $jam_masuk,
            'status' => 'Hadir',
            'keterangan' => $keterangan
        ];

        $this->absensiModel->insert($data);
        return redirect()->back()->with('success', 'Absen masuk berhasil');
    }

    // Proses absen keluar karyawan
    public function clockOut()
    {
        if (!$this->validate([
            'id_karyawan' => 'required|numeric',
            'tanggal' => 'required|valid_date',
            'jam_keluar' => 'required'
        ])) {
            return redirect()->back()->with('error', 'Data absensi tidak valid')->withInput();
        }

        $id_karyawan = $this->request->getPost('id_karyawan');
        $tanggal = $this->request->getPost('tanggal');
        $jam_keluar = $this->request->getPost('jam_keluar');
        $keterangan = $this->request->getPost('keterangan');

        // Cek apakah sudah absen masuk
        $existing = $this->absensiModel->where('id_karyawan', $id_karyawan)
                                      ->where('tanggal', $tanggal)
                                      ->first();

        if (!$existing) {
            return redirect()->back()->with('error', 'Karyawan belum melakukan absen masuk');
        }

        if (!empty($existing['jam_keluar'])) {
            return redirect()->back()->with('error', 'Karyawan sudah melakukan absen keluar hari ini');
        }

        // Update jam keluar
        $this->absensiModel->update($existing['id_absensi'], [
            'jam_keluar' => $jam_keluar,
            'keterangan' => !empty($keterangan) ? $keterangan : $existing['keterangan']
        ]);

        return redirect()->back()->with('success', 'Absen keluar berhasil');
    }

    // Halaman laporan absensi karyawan
    public function laporan()
    {
        $bulan = $this->request->getGet('bulan') ?? date('m');
        $tahun = $this->request->getGet('tahun') ?? date('Y');
        $id_karyawan = $this->request->getGet('id_karyawan');
        
        $start_date = "{$tahun}-{$bulan}-01";
        $end_date = date('Y-m-t', strtotime($start_date));
        
        $query = $this->absensiModel->select('tb_absensi_karyawan.*, tb_karyawan.nama, tb_karyawan.kode_karyawan')
                                   ->join('tb_karyawan', 'tb_karyawan.id_karyawan = tb_absensi_karyawan.id_karyawan')
                                   ->where('tanggal >=', $start_date)
                                   ->where('tanggal <=', $end_date);
                                   
        if ($id_karyawan) {
            $query->where('tb_absensi_karyawan.id_karyawan', $id_karyawan);
        }
        
        $absensi = $query->findAll();
        
        $data = [
            'title' => 'Laporan Absensi Karyawan',
            'absensi' => $absensi,
            'karyawan' => $this->karyawanModel->findAll(),
            'bulan' => $bulan,
            'tahun' => $tahun,
            'id_karyawan' => $id_karyawan,
            'nama_bulan' => [
                '01' => 'Januari',
                '02' => 'Februari',
                '03' => 'Maret',
                '04' => 'April',
                '05' => 'Mei',
                '06' => 'Juni',
                '07' => 'Juli',
                '08' => 'Agustus',
                '09' => 'September',
                '10' => 'Oktober',
                '11' => 'November',
                '12' => 'Desember'
            ]
        ];
        
        return view('absensi_karyawan/laporan', $data);
    }

    // Halaman rekap absensi untuk karyawan tertentu
    public function rekapKaryawan($id_karyawan)
    {
        $bulan = $this->request->getGet('bulan') ?? date('m');
        $tahun = $this->request->getGet('tahun') ?? date('Y');
        
        $karyawan = $this->karyawanModel->find($id_karyawan);
        if (!$karyawan) {
            return redirect()->to('absensi_karyawan/laporan')->with('error', 'Data karyawan tidak ditemukan');
        }
        
        $start_date = "{$tahun}-{$bulan}-01";
        $end_date = date('Y-m-t', strtotime($start_date));
        
        $absensi = $this->absensiModel->where('id_karyawan', $id_karyawan)
                                     ->where('tanggal >=', $start_date)
                                     ->where('tanggal <=', $end_date)
                                     ->findAll();
        
        // Hitung statistik absensi
        $total_hari = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
        $total_hadir = 0;
        $total_sakit = 0;
        $total_izin = 0;
        $total_alpa = 0;
        
        foreach ($absensi as $a) {
            if ($a['status'] == 'Hadir') $total_hadir++;
            if ($a['status'] == 'Sakit') $total_sakit++;
            if ($a['status'] == 'Izin') $total_izin++;
            if ($a['status'] == 'Alpa') $total_alpa++;
        }
        
        $data = [
            'title' => 'Rekap Absensi Karyawan',
            'karyawan' => $karyawan,
            'absensi' => $absensi,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'total_hari' => $total_hari,
            'total_hadir' => $total_hadir,
            'total_sakit' => $total_sakit,
            'total_izin' => $total_izin,
            'total_alpa' => $total_alpa,
            'nama_bulan' => [
                '01' => 'Januari',
                '02' => 'Februari',
                '03' => 'Maret',
                '04' => 'April',
                '05' => 'Mei',
                '06' => 'Juni',
                '07' => 'Juli',
                '08' => 'Agustus',
                '09' => 'September',
                '10' => 'Oktober',
                '11' => 'November',
                '12' => 'Desember'
            ]
        ];
        
        return view('absensi_karyawan/rekap_karyawan', $data);
    }

    // Tambah absensi manual
    public function add()
    {
        if (!$this->validate([
            'id_karyawan' => 'required|numeric',
            'tanggal' => 'required|valid_date',
            'status' => 'required',
            'jam_masuk' => 'permit_empty',
            'jam_keluar' => 'permit_empty'
        ])) {
            return redirect()->back()->with('error', 'Data absensi tidak valid')->withInput();
        }

        $id_karyawan = $this->request->getPost('id_karyawan');
        $tanggal = $this->request->getPost('tanggal');
        $status = $this->request->getPost('status');
        $jam_masuk = $this->request->getPost('jam_masuk');
        $jam_keluar = $this->request->getPost('jam_keluar');
        $keterangan = $this->request->getPost('keterangan');

        // Cek apakah sudah ada absensi pada tanggal tersebut
        $existing = $this->absensiModel->where('id_karyawan', $id_karyawan)
                                      ->where('tanggal', $tanggal)
                                      ->first();

        if ($existing) {
            return redirect()->back()->with('error', 'Karyawan sudah memiliki data absensi pada tanggal tersebut');
        }

        // Simpan data absensi
        $data = [
            'id_karyawan' => $id_karyawan,
            'tanggal' => $tanggal,
            'jam_masuk' => $jam_masuk,
            'jam_keluar' => $jam_keluar,
            'status' => $status,
            'keterangan' => $keterangan
        ];

        $this->absensiModel->insert($data);
        return redirect()->back()->with('success', 'Data absensi berhasil ditambahkan');
    }

    // Halaman edit absensi karyawan
    public function edit($id_absensi)
    {
        $absensi = $this->absensiModel->find($id_absensi);
        if (!$absensi) {
            return redirect()->to('absensi_karyawan')->with('error', 'Data absensi tidak ditemukan');
        }
        
        $data = [
            'title' => 'Edit Absensi Karyawan',
            'absensi' => $absensi,
            'karyawan' => $this->karyawanModel->find($absensi['id_karyawan'])
        ];
        
        return view('absensi_karyawan/edit', $data);
    }
    
    // Update data absensi karyawan
    public function update($id_absensi)
    {
        $absensi = $this->absensiModel->find($id_absensi);
        if (!$absensi) {
            return redirect()->to('absensi_karyawan')->with('error', 'Data absensi tidak ditemukan');
        }
        
        if (!$this->validate([
            'tanggal' => 'required|valid_date',
            'status' => 'required|in_list[Hadir,Sakit,Izin,Alpa]'
        ])) {
            return redirect()->back()->with('error', 'Data tidak valid')->withInput();
        }
        
        $data = [
            'tanggal' => $this->request->getPost('tanggal'),
            'jam_masuk' => $this->request->getPost('jam_masuk'),
            'jam_keluar' => $this->request->getPost('jam_keluar'),
            'status' => $this->request->getPost('status'),
            'keterangan' => $this->request->getPost('keterangan')
        ];
        
        $this->absensiModel->update($id_absensi, $data);
        return redirect()->to('absensi_karyawan?tanggal=' . $data['tanggal'])->with('success', 'Data absensi berhasil diperbarui');
    }
    
    // Hapus data absensi karyawan
    public function delete($id_absensi)
    {
        $absensi = $this->absensiModel->find($id_absensi);
        if (!$absensi) {
            return redirect()->to('absensi_karyawan')->with('error', 'Data absensi tidak ditemukan');
        }
        
        $tanggal = $absensi['tanggal'];
        $this->absensiModel->delete($id_absensi);
        return redirect()->to('absensi_karyawan?tanggal=' . $tanggal)->with('success', 'Data absensi berhasil dihapus');
    }
}
