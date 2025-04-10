<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class AccountController extends BaseController
{
    protected $userModel;
    protected $session;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->session = \Config\Services::session();
    }

    public function edit()
    {
        // Pastikan user telah login
        if (!$this->session->get('logged_in')) {
            return redirect()->to(base_url('auth/login'))->with('error', 'Silahkan login terlebih dahulu');
        }

        $userId = $this->session->get('user_id');
        $user = $this->userModel->find($userId);
        
        if (!$user) {
            return redirect()->to(base_url('dashboard'))->with('error', 'User tidak ditemukan');
        }

        $data = [
            'title' => 'Edit Akun',
            'user' => $user
        ];

        return view('account/edit', $data);
    }

    public function update()
    {
        // Pastikan user telah login
        if (!$this->session->get('logged_in')) {
            return redirect()->to(base_url('auth/login'))->with('error', 'Silahkan login terlebih dahulu');
        }

        $userId = $this->session->get('user_id');
        $user = $this->userModel->find($userId);
        
        if (!$user) {
            return redirect()->to(base_url('dashboard'))->with('error', 'User tidak ditemukan');
        }

        $username = $this->request->getPost('username');
        $currentPassword = $this->request->getPost('current_password');
        $newPassword = $this->request->getPost('new_password');
        $confirmPassword = $this->request->getPost('confirm_password');
        
        // Validasi input
        $errors = [];
        
        // Cek username jika diubah
        if ($username != $user['username']) {
            // Cek apakah username sudah dipakai
            $existingUser = $this->userModel->findByUsername($username);
            if ($existingUser) {
                $errors[] = 'Username sudah digunakan. Silakan pilih username lain.';
            }
        }
        
        // Cek password jika diubah
        if (!empty($newPassword)) {
            // Verifikasi password saat ini
            if (!$this->userModel->verifyPassword($user, $currentPassword)) {
                $errors[] = 'Password saat ini tidak sesuai.';
            }
            
            // Verifikasi password baru dan konfirmasi
            if ($newPassword != $confirmPassword) {
                $errors[] = 'Password baru dan konfirmasi password tidak sama.';
            }
            
            // Validasi kekuatan password
            if (strlen($newPassword) < 6) {
                $errors[] = 'Password baru minimal 6 karakter.';
            }
        }
        
        // Jika ada error, kembalikan ke halaman edit
        if (!empty($errors)) {
            return redirect()->to(base_url('account/edit'))
                            ->with('errors', $errors)
                            ->withInput();
        }
        
        // Persiapkan data user yang akan diupdate
        $userData = [
            'username' => $username
        ];
        
        // Hash password baru jika ada
        if (!empty($newPassword)) {
            $userData['password'] = password_hash($newPassword, PASSWORD_DEFAULT);
        }
        
        // Update data user
        if ($this->userModel->update($userId, $userData)) {
            // Update session data
            $this->session->set('username', $username);
            
            return redirect()->to(base_url('account/edit'))->with('success', 'Akun berhasil diupdate');
        } else {
            return redirect()->to(base_url('account/edit'))->with('error', 'Gagal update akun');
        }
    }
}
