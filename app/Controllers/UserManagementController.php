<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\KaryawanModel;
use App\Models\LoginAttemptModel;

class UserManagementController extends BaseController
{
    protected $userModel;
    protected $karyawanModel;
    protected $loginAttemptModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->karyawanModel = new KaryawanModel();
        $this->loginAttemptModel = new LoginAttemptModel();
    }

    // Menampilkan daftar akun pengguna
    public function index()
    {
        $data = [
            'title' => 'Manajemen Pengguna',
            'users' => $this->userModel->getUsersWithKaryawan()
        ];

        return view('users/index', $data);
    }

    // Menampilkan form untuk membuat akun baru
    public function new()
    {
        // Ambil semua karyawan yang belum memiliki akun
        $karyawanTanpaAkun = $this->karyawanModel->getKaryawanWithoutAccount();

        // Validasi jika tidak ada karyawan yang tersedia
        if (empty($karyawanTanpaAkun)) {
            return redirect()->to('users')->with('error', 'Tidak ada karyawan yang tersedia untuk dibuatkan akun');
        }

        $data = [
            'title' => 'Buat Akun Pengguna Baru',
            'karyawan' => $karyawanTanpaAkun
        ];

        return view('users/create', $data);
    }

    // Proses pembuatan akun pengguna baru
    public function create()
    {
        // Ambil data karyawan berdasarkan ID
        $idKaryawan = $this->request->getPost('id_karyawan');
        $karyawan = $this->karyawanModel->find($idKaryawan);
        
        if (!$karyawan) {
            return redirect()->to('users/new')->with('error', 'Data karyawan tidak ditemukan');
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
            'id_karyawan' => $idKaryawan
        ];

        $this->userModel->insert($userData);
        
        // Update status karyawan menjadi Aktif
        $this->karyawanModel->update($idKaryawan, ['status' => 'Aktif']);
        
        return redirect()->to('users')->with('success', 'Akun pengguna berhasil dibuat dan status karyawan telah diaktifkan');
    }

    // Menampilkan form edit akun pengguna
    public function edit($id = null)
    {
        $user = $this->userModel->find($id);
        
        if (!$user) {
            return redirect()->to('users')->with('error', 'Akun pengguna tidak ditemukan');
        }

        $data = [
            'title' => 'Edit Akun Pengguna',
            'user' => $user
        ];

        return view('users/edit', $data);
    }

    // Proses update akun pengguna
    public function update($id = null)
    {
        $user = $this->userModel->find($id);
        
        if (!$user) {
            return redirect()->to('users')->with('error', 'Akun pengguna tidak ditemukan');
        }

        // Validasi form
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

        $messages = [
            'username' => [
                'required' => 'Username harus diisi',
                'min_length' => 'Username minimal 5 karakter',
                'max_length' => 'Username maksimal 50 karakter',
                'alpha_numeric_punct' => 'Username hanya boleh berisi huruf, angka, dan karakter _ - .',
                'is_unique' => 'Username sudah digunakan'
            ],
            'password' => [
                'min_length' => 'Password minimal 8 karakter',
                'regex_match' => 'Password harus mengandung huruf besar, huruf kecil, angka, dan karakter khusus (@$!%*?&#)'
            ],
            'confirm_password' => [
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

        $this->userModel->update($id, $userData);
        
        return redirect()->to('users')->with('success', 'Akun pengguna berhasil diperbarui');
    }

    // Hapus akun pengguna
    public function delete($id = null)
    {
        $user = $this->userModel->find($id);
        
        if (!$user) {
            return redirect()->to('users')->with('error', 'Akun pengguna tidak ditemukan');
        }

        // Cek apakah ada karyawan terkait
        if (!empty($user['id_karyawan'])) {
            // Update status karyawan menjadi 'Nonaktif'
            $this->karyawanModel->update($user['id_karyawan'], ['status' => 'Nonaktif']);
        }

        $this->userModel->delete($id);
        
        return redirect()->to('users')->with('success', 'Akun pengguna berhasil dihapus');
    }

    // Reset login attempts untuk username tertentu
    public function resetLoginAttempts($username)
    {
        $this->loginAttemptModel->where('username', $username)->delete();
        return redirect()->to('users')->with('success', 'Login attempts untuk pengguna ' . $username . ' berhasil direset');
    }

    // Metode untuk melihat semua login attempts
    public function viewLoginAttempts()
    {
        $data = [
            'title' => 'Log Login Attempts',
            'attempts' => $this->loginAttemptModel->findAll()
        ];

        return view('users/login_attempts', $data);
    }

    // Metode untuk membersihkan semua login attempts
    public function clearAllLoginAttempts()
    {
        $this->loginAttemptModel->purgeExpired();
        return redirect()->to('users/login-attempts')->with('success', 'Semua login attempts telah dibersihkan');
    }
}
