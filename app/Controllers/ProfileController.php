<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\KaryawanModel;
use App\Models\UserModel;

class ProfileController extends BaseController
{
    protected $karyawanModel;
    protected $userModel;
    protected $session;

    public function __construct()
    {
        $this->karyawanModel = new KaryawanModel();
        $this->userModel = new UserModel();
        $this->session = \Config\Services::session();
    }

    public function index()
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
            'title' => 'My Profile',
            'user' => $user,
            'karyawan' => null
        ];

        // Jika user adalah karyawan, ambil data karyawan
        if (!empty($user['id_karyawan'])) {
            $data['karyawan'] = $this->karyawanModel->find($user['id_karyawan']);
        }

        return view('profile/my_profile', $data);
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
            'title' => 'Edit Profil',
            'user' => $user,
            'karyawan' => null
        ];

        // Jika user adalah karyawan, ambil data karyawan
        if (!empty($user['id_karyawan'])) {
            $data['karyawan'] = $this->karyawanModel->find($user['id_karyawan']);
        }

        return view('profile/edit', $data);
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

        // Update data profil jika user adalah karyawan
        if (!empty($user['id_karyawan'])) {
            $karyawanId = $user['id_karyawan'];
            
            // Persiapkan data karyawan yang akan diupdate
            $karyawanData = [
                'nama' => $this->request->getPost('nama'),
                'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
                'tanggal_lahir' => $this->request->getPost('tanggal_lahir'),
                'alamat' => $this->request->getPost('alamat'),
                'no_telepon' => $this->request->getPost('no_telepon'),
                'email' => $this->request->getPost('email')
            ];

            // Handle foto profil upload
            $foto = $this->request->getFile('foto');
            if ($foto && $foto->isValid() && !$foto->hasMoved()) {
                // Hapus foto lama jika ada
                $karyawan = $this->karyawanModel->find($karyawanId);
                if (!empty($karyawan['foto']) && file_exists('uploads/karyawan/' . $karyawan['foto'])) {
                    unlink('uploads/karyawan/' . $karyawan['foto']);
                }

                // Generate random name dan simpan foto baru
                $newName = $foto->getRandomName();
                $foto->move('uploads/karyawan', $newName);
                $karyawanData['foto'] = $newName;
            }

            // Update data karyawan
            if ($this->karyawanModel->update($karyawanId, $karyawanData)) {
                // Update nama di tabel user
                $this->userModel->update($userId, ['name' => $karyawanData['nama']]);
                // Update session data
                $this->session->set('name', $karyawanData['nama']);
                
                return redirect()->to(base_url('profile/edit'))->with('success', 'Profil berhasil diupdate');
            } else {
                return redirect()->to(base_url('profile/edit'))->with('error', 'Gagal update profil: ' . implode(', ', $this->karyawanModel->errors()));
            }
        } else {
            // Jika bukan karyawan, update data di tabel user saja
            $name = $this->request->getPost('name');
            if ($this->userModel->update($userId, ['name' => $name])) {
                // Update session data
                $this->session->set('name', $name);
                
                return redirect()->to(base_url('profile/edit'))->with('success', 'Profil berhasil diupdate');
            } else {
                return redirect()->to(base_url('profile/edit'))->with('error', 'Gagal update profil');
            }
        }
    }
}
