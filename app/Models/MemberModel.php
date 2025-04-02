<?php

namespace App\Models;

use CodeIgniter\Model;

class MemberModel extends Model
{
    protected $table = 'tb_members';
    protected $primaryKey = 'id_member';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['member_code', 'name', 'email', 'phone', 'gender', 'address', 'photo'];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'name' => 'required|min_length[3]|max_length[100]',
        'email' => 'permit_empty|valid_email|max_length[100]',
        'phone' => 'permit_empty|max_length[15]',
        'gender' => 'required|in_list[L,P]',
        'address' => 'permit_empty',
        'photo' => 'permit_empty|max_length[255]'
    ];

    protected $validationMessages = [
        'name' => [
            'required' => 'Nama harus diisi',
            'min_length' => 'Nama minimal 3 karakter',
            'max_length' => 'Nama maksimal 100 karakter'
        ],
        'email' => [
            'valid_email' => 'Format email tidak valid',
            'max_length' => 'Email maksimal 100 karakter'
        ],
        'phone' => [
            'max_length' => 'Nomor telepon maksimal 15 karakter'
        ],
        'gender' => [
            'required' => 'Jenis kelamin harus dipilih',
            'in_list' => 'Jenis kelamin tidak valid'
        ]
    ];
}
