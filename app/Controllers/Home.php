<?php

namespace App\Controllers;

use App\Controllers\KaryawanController;

class Home extends BaseController
{
    
    public function index()
    {
        return redirect()->to('/dashboard');
    }
    
}
