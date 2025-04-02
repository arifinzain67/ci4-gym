<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AjaxFilter implements FilterInterface
{
    /**
     * Filter untuk menangani request AJAX
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // Menggunakan header X-Requested-With untuk mendeteksi request AJAX
        if ($request->getHeaderLine('X-Requested-With') === 'XMLHttpRequest') {
            return;
        }
        
        // Jika ini bukan request AJAX, tetap proses dengan controller normal
        return service('response')->setStatusCode(200);
    }

    /**
     * Invoke after the controller has been executed.
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Nothing to do
    }
}
