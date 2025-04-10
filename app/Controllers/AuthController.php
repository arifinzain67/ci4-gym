<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\LoginAttemptModel;
use Config\Services;
use Config\Session as SessionConfig;

class AuthController extends BaseController
{
    protected $userModel;
    protected $loginAttemptModel;
    protected $session;
    protected $sessionConfig;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->loginAttemptModel = new LoginAttemptModel();
        $this->session = Services::session();
        $this->sessionConfig = new SessionConfig();
        
        log_message('debug', 'Session configuration: ' . json_encode([
            'driver' => $this->sessionConfig->driver ?? 'not set',
            'savePath' => $this->sessionConfig->savePath ?? 'not set'
        ]));
        
        log_message('debug', 'Current session data: ' . json_encode($this->session->get()));
    }

    public function index()
    {
        return redirect()->to(base_url('auth/login'));
    }

    public function login()
    {
        log_message('debug', '=== Login Method Start ===');
        log_message('debug', 'Raw Request Method: ' . $this->request->getMethod());
        log_message('debug', 'Server Request Method: ' . $_SERVER['REQUEST_METHOD']);
        
        // Bersihkan percobaan login lama
        $this->loginAttemptModel->purgeOldAttempts();
        
        if (strtolower($this->request->getMethod()) === 'post') {
            log_message('debug', 'Processing POST request');
            
            // Validasi input login - lebih sederhana
            if (!$this->validate('login')) {
                log_message('debug', 'Login validation failed: ' . json_encode($this->validator->getErrors()));
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Username dan password harus diisi');
            }
            
            // Ambil input yang telah divalidasi
            $username = $this->request->getPost('username');
            $password = $this->request->getPost('password');
            $ipAddress = $this->request->getIPAddress();
            
            log_message('debug', 'Login attempt - Username: ' . $username . ', IP: ' . $ipAddress);
            
            // Periksa apakah IP atau username diblokir karena terlalu banyak percobaan
            if ($this->loginAttemptModel->isIPBlocked($ipAddress)) {
                log_message('debug', 'IP address is blocked due to too many failed attempts');
                return redirect()->back()->with('error', 'Terlalu banyak percobaan login gagal. Silakan coba lagi setelah 15 menit.');
            }
            
            if ($this->loginAttemptModel->isUsernameBlocked($username)) {
                log_message('debug', 'Username is blocked due to too many failed attempts');
                return redirect()->back()->with('error', 'Terlalu banyak percobaan login gagal. Silakan coba lagi setelah 15 menit.');
            }
            
            $user = $this->userModel->findByUsername($username);
            log_message('debug', 'User data: ' . json_encode($user));
            
            if ($user) {
                log_message('debug', 'User found in database');
                
                // Cek status karyawan jika user terkait dengan karyawan
                if (isset($user['id_karyawan']) && $user['id_karyawan'] !== null) {
                    log_message('debug', 'User is linked to employee with status: ' . ($user['karyawan_status'] ?? 'unknown'));
                    
                    // Jika karyawan nonaktif, tolak login
                    if (isset($user['karyawan_status']) && strtolower($user['karyawan_status']) === 'nonaktif') {
                        log_message('debug', 'Login denied - employee is inactive');
                        return redirect()->back()->with('error', 'Akun Anda telah dinonaktifkan. Silahkan hubungi administrator.');
                    }
                }
                
                if ($this->userModel->verifyPassword($user, $password)) {
                    log_message('debug', 'Password verified successfully');
                    
                    $sessionData = [
                        'user_id' => $user['id_user'],
                        'username' => $user['username'],
                        'name' => $user['name'],
                        'role' => $user['role'],
                        'logged_in' => true,
                        'last_activity' => time()
                    ];
                    
                    $this->session->set($sessionData);
                    
                    log_message('debug', 'Session data set: ' . json_encode($this->session->get()));
                    log_message('debug', 'Redirecting to dashboard...');
                    return redirect()->to(base_url('dashboard'));
                } else {
                    log_message('debug', 'Password verification failed');
                    // Rekam percobaan login yang gagal
                    $this->loginAttemptModel->addAttempt($ipAddress, $username);
                    return redirect()->back()->with('error', 'Username atau password salah');
                }
            } else {
                log_message('debug', 'User not found');
                // Rekam percobaan login yang gagal
                $this->loginAttemptModel->addAttempt($ipAddress, $username);
                return redirect()->back()->with('error', 'Username atau password salah');
            }
        } else {
            log_message('debug', 'Not a POST request');
        }

        log_message('debug', 'Showing login form. Current session: ' . json_encode($this->session->get()));
        return view('auth/login');
    }

    public function logout()
    {
        log_message('debug', 'Logging out. Session data before destroy: ' . json_encode($this->session->get()));
        
        // Log aktivitas logout
        if ($this->session->has('user_id')) {
            log_message('info', 'User logged out: ' . $this->session->get('username') . ' (ID: ' . $this->session->get('user_id') . ')');
        }
        
        $this->session->destroy();
        log_message('debug', 'Session data after destroy: ' . json_encode($this->session->get()));
        return redirect()->to(base_url('auth/login'))->with('message', 'Berhasil logout');
    }
}
