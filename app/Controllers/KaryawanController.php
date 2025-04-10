<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\KaryawanModel;
use App\Models\UserModel;

class KaryawanController extends BaseController
{
    protected $karyawanModel;
    protected $userModel;

    public function __construct()
    {
        $this->karyawanModel = new KaryawanModel();
        $this->userModel = new UserModel();
    }

    // Menampilkan daftar karyawan
    public function index()
    {
        $data = [
            'title' => 'Data Karyawan',
            'karyawan' => $this->karyawanModel->getKaryawan()
        ];

        return view('karyawan/index', $data);
    }

    // Menampilkan form tambah karyawan
    public function new()
    {
        $data = [
            'title' => 'Tambah Karyawan',
            'kode_karyawan' => $this->karyawanModel->generateKodeKaryawan()
        ];

        return view('karyawan/create', $data);
    }

    // Menyimpan data karyawan baru
    public function create()
    {
        if (!$this->validate($this->karyawanModel->getValidationRules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Handle upload foto
        $foto = $this->request->getFile('foto');
        $namaFoto = null;

        if ($foto && $foto->isValid() && !$foto->hasMoved()) {
            $namaFoto = $foto->getRandomName();
            $foto->move(ROOTPATH . 'public/uploads/karyawan', $namaFoto);
        }

        $data = [
            'kode_karyawan' => $this->request->getPost('kode_karyawan'),
            'nama' => $this->request->getPost('nama'),
            'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
            'tanggal_lahir' => $this->request->getPost('tanggal_lahir'),
            'alamat' => $this->request->getPost('alamat'),
            'no_telepon' => $this->request->getPost('no_telepon'),
            'email' => $this->request->getPost('email'),
            'posisi' => $this->request->getPost('posisi'),
            'tanggal_bergabung' => $this->request->getPost('tanggal_bergabung'),
            'status' => $this->request->getPost('status'),
            'gaji' => $this->request->getPost('gaji'),
            'foto' => $namaFoto
        ];

        $this->karyawanModel->insert($data);
        return redirect()->to('karyawan')->with('success', 'Data karyawan berhasil ditambahkan');
    }

    // Menampilkan form edit karyawan
    public function edit($id = null)
    {
        // Cek apakah ini request AJAX berdasarkan header
        if ($this->request->getHeaderLine('X-Requested-With') === 'XMLHttpRequest') {
            $karyawan = $this->karyawanModel->find($id);
            
            if (!$karyawan) {
                return $this->response->setJSON(['error' => 'Data karyawan tidak ditemukan'])->setStatusCode(404);
            }
            
            return $this->response->setJSON([
                'success' => true,
                'data' => $karyawan
            ]);
        }
        
        // Ini adalah request HTML biasa
        $karyawan = $this->karyawanModel->find($id);
        
        if (!$karyawan) {
            return redirect()->to('karyawan')->with('error', 'Data karyawan tidak ditemukan');
        }

        $data = [
            'title' => 'Edit Karyawan',
            'karyawan' => $karyawan
        ];

        return view('karyawan/edit', $data);
    }

    // Update data karyawan
    public function update($id = null)
    {
        $karyawan = $this->karyawanModel->find($id);
        
        if (!$karyawan) {
            return redirect()->to('karyawan')->with('error', 'Data karyawan tidak ditemukan');
        }

        // Debug - Log data yang diterima
        log_message('debug', 'Update Karyawan ID: ' . $id);
        log_message('debug', 'POST data: ' . json_encode($this->request->getPost()));

        // Buat validasi khusus
        $rules = [
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
        
        // Validasi kode_karyawan hanya jika berbeda dari nilai awal
        if ($this->request->getPost('kode_karyawan') != $karyawan['kode_karyawan']) {
            $rules['kode_karyawan'] = 'required|is_unique[tb_karyawan.kode_karyawan]';
        } else {
            $rules['kode_karyawan'] = 'required';
        }
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Handle upload foto
        $foto = $this->request->getFile('foto');
        $namaFoto = $karyawan['foto']; // Pertahankan foto lama jika tidak ada upload baru

        if ($foto && $foto->isValid() && !$foto->hasMoved()) {
            // Hapus foto lama jika ada
            if ($namaFoto && file_exists(ROOTPATH . 'public/uploads/karyawan/' . $namaFoto)) {
                unlink(ROOTPATH . 'public/uploads/karyawan/' . $namaFoto);
            }
            
            $namaFoto = $foto->getRandomName();
            $foto->move(ROOTPATH . 'public/uploads/karyawan', $namaFoto);
        }

        $data = [
            'kode_karyawan' => $this->request->getPost('kode_karyawan'),
            'nama' => $this->request->getPost('nama'),
            'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
            'tanggal_lahir' => $this->request->getPost('tanggal_lahir'),
            'alamat' => $this->request->getPost('alamat'),
            'no_telepon' => $this->request->getPost('no_telepon'),
            'email' => $this->request->getPost('email'),
            'posisi' => $this->request->getPost('posisi'),
            'tanggal_bergabung' => $this->request->getPost('tanggal_bergabung'),
            'status' => $this->request->getPost('status'),
            'gaji' => $this->request->getPost('gaji'),
            'foto' => $namaFoto
        ];

        // Log data yang akan diupdate
        log_message('debug', 'Data yang akan diupdate: ' . json_encode($data));
        
        $result = $this->karyawanModel->update($id, $data);
        
        // Log hasil update
        log_message('debug', 'Hasil update: ' . ($result ? 'Berhasil' : 'Gagal'));
        
        return redirect()->to('karyawan')->with('success', 'Data karyawan berhasil diperbarui');
    }

    // Hapus data karyawan
    public function delete($id = null)
    {
        $karyawan = $this->karyawanModel->find($id);
        
        if (!$karyawan) {
            return redirect()->to('karyawan')->with('error', 'Data karyawan tidak ditemukan');
        }

        // Hapus akun user terkait jika ada
        $user = $this->userModel->where('id_karyawan', $id)->first();
        if ($user) {
            $this->userModel->delete($user['id_user']);
        }

        // Hapus foto jika ada
        if ($karyawan['foto'] && file_exists(ROOTPATH . 'public/uploads/karyawan/' . $karyawan['foto'])) {
            unlink(ROOTPATH . 'public/uploads/karyawan/' . $karyawan['foto']);
        }

        $this->karyawanModel->delete($id);
        return redirect()->to('karyawan')->with('success', 'Data karyawan berhasil dihapus');
    }

    // Detail karyawan
    public function show($id = null)
    {
        $karyawan = $this->karyawanModel->find($id);
        
        if (!$karyawan) {
            return redirect()->to('karyawan')->with('error', 'Data karyawan tidak ditemukan');
        }

        $data = [
            'title' => 'Detail Karyawan',
            'karyawan' => $karyawan
        ];

        return view('karyawan/show', $data);
    }

    // Halaman buat akun user untuk karyawan
    public function createAccount($id = null)
    {
        $karyawan = $this->karyawanModel->find($id);
        
        if (!$karyawan) {
            return redirect()->to('karyawan')->with('error', 'Data karyawan tidak ditemukan');
        }

        // Cek apakah karyawan sudah memiliki akun
        $existingUser = $this->userModel->where('id_karyawan', $id)->first();
        if ($existingUser) {
            return redirect()->to('karyawan')->with('error', 'Karyawan sudah memiliki akun user');
        }

        $data = [
            'title' => 'Buat Akun User',
            'karyawan' => $karyawan
        ];

        return view('karyawan/create_account', $data);
    }

    // Proses pembuatan akun user untuk karyawan
    public function storeAccount($id = null)
    {
        $karyawan = $this->karyawanModel->find($id);
        
        if (!$karyawan) {
            return redirect()->to('karyawan')->with('error', 'Data karyawan tidak ditemukan');
        }

        // Validasi form dengan keamanan yang ditingkatkan
        $rules = [
            'username' => 'required|min_length[5]|max_length[50]|alpha_numeric_punct|is_unique[tb_user.username]',
            'password' => 'required|min_length[8]|regex_match[/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]{8,}$/]',
            'confirm_password' => 'required|matches[password]',
            'role' => 'required'
        ];

        $messages = [
            'username' => [
                'required' => 'Username harus diisi',
                'min_length' => 'Username minimal 5 karakter',
                'max_length' => 'Username maksimal 50 karakter',
                'alpha_numeric_punct' => 'Username hanya boleh berisi huruf, angka, dan karakter _ - .',
                'is_unique' => 'Username sudah digunakan'
            ],
            'password' => [
                'required' => 'Password harus diisi',
                'min_length' => 'Password minimal 8 karakter',
                'regex_match' => 'Password harus mengandung huruf besar, huruf kecil, angka, dan karakter khusus (@$!%*?&#)'
            ],
            'confirm_password' => [
                'required' => 'Konfirmasi password harus diisi',
                'matches' => 'Konfirmasi password tidak sesuai dengan password'
            ],
            'role' => [
                'required' => 'Role harus dipilih'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Validasi role berdasarkan pengguna yang login
        $userRole = session()->get('role');
        $requestedRole = $this->request->getPost('role');

        // Admin hanya boleh membuat akun kasir (staff)
        if ($userRole === 'admin' && $requestedRole !== 'staff') {
            return redirect()->back()->withInput()->with('error', 'Admin hanya boleh membuat akun dengan role kasir');
        }

        // Pastikan tidak ada karakter berbahaya pada input
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        
        if (function_exists('is_valid_input') && !is_valid_input($username)) {
            return redirect()->back()->withInput()->with('error', 'Username mengandung karakter yang tidak diizinkan');
        }

        // Buat akun user dengan password yang di-hash menggunakan algoritma BCRYPT
        $userData = [
            'username' => $username,
            'password' => password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]),
            'name' => $karyawan['nama'],
            'role' => $requestedRole,
            'id_karyawan' => $id
        ];

        $this->userModel->insert($userData);
        
        // Update status karyawan menjadi Aktif
        $this->karyawanModel->update($id, ['status' => 'Aktif']);
        
        return redirect()->to('karyawan')->with('success', 'Akun user berhasil dibuat dan status karyawan telah diaktifkan');
    }

    // Halaman edit akun user untuk karyawan
    public function editAccount($id = null)
    {
        $karyawan = $this->karyawanModel->find($id);
        
        if (!$karyawan) {
            return redirect()->to('karyawan')->with('error', 'Data karyawan tidak ditemukan');
        }

        // Cek apakah karyawan memiliki akun
        $user = $this->userModel->where('id_karyawan', $id)->first();
        if (!$user) {
            return redirect()->to('karyawan/' . $id)->with('error', 'Karyawan belum memiliki akun user');
        }

        $data = [
            'title' => 'Edit Akun User',
            'karyawan' => $karyawan,
            'user' => $user
        ];

        return view('karyawan/edit_account', $data);
    }

    // Proses update akun user untuk karyawan
    public function updateAccount($id = null)
    {
        $karyawan = $this->karyawanModel->find($id);
        
        if (!$karyawan) {
            return redirect()->to('karyawan')->with('error', 'Data karyawan tidak ditemukan');
        }

        // Cek apakah karyawan memiliki akun
        $user = $this->userModel->where('id_karyawan', $id)->first();
        if (!$user) {
            return redirect()->to('karyawan/' . $id)->with('error', 'Karyawan belum memiliki akun user');
        }

        // Buat validasi khusus
        $rules = [
            'username' => 'required|min_length[5]|max_length[50]|alpha_numeric_punct',
            'role' => 'required'
        ];

        // Validasi username harus unik kecuali username-nya sendiri
        if ($this->request->getPost('username') !== $user['username']) {
            $rules['username'] .= '|is_unique[tb_user.username]';
        }

        // Jika password diisi, validasi password baru
        if ($this->request->getPost('password')) {
            $rules['password'] = 'min_length[8]|regex_match[/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]{8,}$/]';
            $rules['confirm_password'] = 'matches[password]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Validasi role berdasarkan pengguna yang login
        $userRole = session()->get('role');
        $requestedRole = $this->request->getPost('role');
        $currentRole = $user['role'];

        // Admin hanya boleh mengubah role menjadi kasir
        if ($userRole === 'admin') {
            // Jika user saat ini memiliki role admin, tetap boleh admin (tidak diubah)
            if ($requestedRole !== 'staff' && $currentRole !== $requestedRole) {
                return redirect()->back()->withInput()->with('error', 'Admin hanya boleh mengubah role menjadi kasir');
            }
        }

        // Update data user
        $userData = [
            'username' => $this->request->getPost('username'),
            'role' => $requestedRole
        ];

        // Update password jika diisi
        if ($this->request->getPost('password')) {
            $userData['password'] = password_hash($this->request->getPost('password'), PASSWORD_BCRYPT, ['cost' => 12]);
        }

        $this->userModel->update($user['id_user'], $userData);
        
        return redirect()->to('karyawan/' . $id)->with('success', 'Akun user berhasil diperbarui');
    }

    // Hapus akun user karyawan
    public function deleteAccount($id = null)
    {
        $karyawan = $this->karyawanModel->find($id);
        
        if (!$karyawan) {
            return redirect()->to('karyawan')->with('error', 'Data karyawan tidak ditemukan');
        }

        // Cek apakah karyawan memiliki akun
        $user = $this->userModel->where('id_karyawan', $id)->first();
        if (!$user) {
            return redirect()->to('karyawan/' . $id)->with('error', 'Karyawan tidak memiliki akun user');
        }

        // Hapus akun user
        $this->userModel->delete($user['id_user']);
        
        // Update status karyawan menjadi Tidak Aktif
        $this->karyawanModel->update($id, ['status' => 'Nonaktif']);
        
        return redirect()->to('karyawan/' . $id)->with('success', 'Akun user berhasil dihapus dan status karyawan diubah menjadi Nonaktif');
    }

    // Mengaktifkan/menonaktifkan karyawan
    public function toggleStatus($id = null)
    {
        $karyawan = $this->karyawanModel->find($id);
        
        if (!$karyawan) {
            return redirect()->to('karyawan')->with('error', 'Data karyawan tidak ditemukan');
        }
        
        // Cek apakah karyawan memiliki akun
        $existingUser = $this->userModel->where('id_karyawan', $id)->first();
        if (!$existingUser) {
            return redirect()->to('karyawan')->with('error', 'Karyawan belum memiliki akun user');
        }
        
        // Toggle status
        $newStatus = ($karyawan['status'] === 'Aktif') ? 'Nonaktif' : 'Aktif';
        $this->karyawanModel->update($id, ['status' => $newStatus]);
        
        $message = ($newStatus === 'Aktif') ? 'Akun karyawan berhasil diaktifkan' : 'Akun karyawan berhasil dinonaktifkan';
        return redirect()->to('karyawan')->with('success', $message);
    }
    
    /**
     * API endpoint untuk mendapatkan data karyawan
     */
    public function getKaryawanAPI($id = null)
    {
        if ($id) {
            $data = $this->karyawanModel->find($id);
            if (!$data) {
                return $this->response->setJSON(['error' => 'Data karyawan tidak ditemukan'])->setStatusCode(404);
            }
            return $this->response->setJSON($data);
        }
        
        $data = $this->karyawanModel->findAll();
        return $this->response->setJSON($data);
    }
    
    /**
     * API endpoint untuk edit karyawan
     */
    public function editKaryawanAPI($id = null)
    {
        $karyawan = $this->karyawanModel->find($id);
        
        if (!$karyawan) {
            return $this->response->setJSON(['error' => 'Data karyawan tidak ditemukan'])->setStatusCode(404);
        }
        
        return $this->response->setJSON([
            'success' => true,
            'data' => $karyawan
        ]);
    }
}
