<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use Config\Services;
use Config\Session as SessionConfig;

class AuthController extends BaseController
{
    protected $userModel;
    protected $session;
    protected $sessionConfig;

    public function __construct()
    {
        $this->userModel = new UserModel();
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
        log_message('debug', 'POST Data: ' . json_encode($this->request->getPost()));
        log_message('debug', 'Raw POST Data: ' . json_encode($_POST));
        log_message('debug', 'Request Headers: ' . json_encode($this->request->getHeaders()));
        
        if (strtolower($this->request->getMethod()) === 'post') {
            log_message('debug', 'Processing POST request');
            
            $username = $this->request->getPost('username');
            $password = $this->request->getPost('password');
            
            log_message('debug', 'Login attempt - Username: ' . $username);
            
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
                        'logged_in' => true
                    ];
                    
                    $this->session->set($sessionData);
                    log_message('debug', 'Session data set: ' . json_encode($this->session->get()));
                    
                    log_message('debug', 'Redirecting to dashboard...');
                    return redirect()->to(base_url('dashboard'));
                } else {
                    log_message('debug', 'Password verification failed');
                    return redirect()->back()->with('error', 'Username atau password salah');
                }
            } else {
                log_message('debug', 'User not found');
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
        $this->session->destroy();
        log_message('debug', 'Session data after destroy: ' . json_encode($this->session->get()));
        return redirect()->to(base_url('auth/login'))->with('message', 'Berhasil logout');
    }
}
