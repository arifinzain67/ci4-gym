<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Validation\StrictRules\CreditCardRules;
use CodeIgniter\Validation\StrictRules\FileRules;
use CodeIgniter\Validation\StrictRules\FormatRules;
use CodeIgniter\Validation\StrictRules\Rules;

class Validation extends BaseConfig
{
    // --------------------------------------------------------------------
    // Setup
    // --------------------------------------------------------------------

    /**
     * Stores the classes that contain the
     * rules that are available.
     *
     * @var list<string>
     */
    public array $ruleSets = [
        Rules::class,
        FormatRules::class,
        FileRules::class,
        CreditCardRules::class,
    ];

    /**
     * Specifies the views that are used to display the
     * errors.
     *
     * @var array<string, string>
     */
    public array $templates = [
        'list'   => 'CodeIgniter\Validation\Views\list',
        'single' => 'CodeIgniter\Validation\Views\single',
    ];

    // --------------------------------------------------------------------
    // Rules
    // --------------------------------------------------------------------
    
    /**
     * Aturan validasi untuk login
     */
    public $login = [
        'username' => [
            'rules' => 'required|min_length[3]|max_length[50]',
            'errors' => [
                'required' => 'Username harus diisi',
                'min_length' => 'Username minimal 3 karakter',
                'max_length' => 'Username maksimal 50 karakter'
            ]
        ],
        'password' => [
            'rules' => 'required',
            'errors' => [
                'required' => 'Password harus diisi'
            ]
        ]
    ];
    
    /**
     * Aturan validasi untuk input umum
     */
    public $safeInput = [
        'input' => [
            'rules' => 'required|regex_match[/^[a-zA-Z0-9\s\-_.,:;()?!@#$%^&*+=\'\"]+$/]',
            'errors' => [
                'required' => 'Input tidak boleh kosong',
                'regex_match' => 'Input mengandung karakter yang tidak diizinkan'
            ]
        ]
    ];
    
    /**
     * Aturan validasi untuk ganti password
     */
    public $changePassword = [
        'current_password' => [
            'rules' => 'required',
            'errors' => [
                'required' => 'Password saat ini harus diisi'
            ]
        ],
        'new_password' => [
            'rules' => 'required|min_length[8]|regex_match[/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/]',
            'errors' => [
                'required' => 'Password baru harus diisi',
                'min_length' => 'Password baru minimal 8 karakter',
                'regex_match' => 'Password harus mengandung huruf besar, huruf kecil, angka, dan karakter khusus'
            ]
        ],
        'confirm_password' => [
            'rules' => 'required|matches[new_password]',
            'errors' => [
                'required' => 'Konfirmasi password harus diisi',
                'matches' => 'Konfirmasi password tidak sesuai dengan password baru'
            ]
        ]
    ];
}
