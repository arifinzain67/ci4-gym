<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Periksa jika user sudah login
        if (!session()->get('logged_in')) {
            return redirect()->to('auth/login')->with('error', 'Silakan login terlebih dahulu');
        }

        // Dapatkan role dari session
        $userRole = session()->get('role');
        
        // Jika tidak ada argument, berarti semua role diperbolehkan
        if (empty($arguments)) {
            return;
        }
        
        // Periksa jika role user termasuk dalam roles yang diizinkan
        if (!in_array($userRole, $arguments)) {
            return redirect()->to('dashboard')->with('error', 'Anda tidak memiliki akses ke halaman tersebut');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
