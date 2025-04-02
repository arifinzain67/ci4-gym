<?php

namespace App\Models;

use CodeIgniter\Model;

class MembershipTypeModel extends Model
{
    protected $table = 'tb_membership_types';
    protected $primaryKey = 'id_type';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['name', 'description', 'price', 'duration'];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'name' => 'required|min_length[3]|max_length[100]',
        'description' => 'permit_empty',
        'price' => 'required|numeric|greater_than[0]',
        'duration' => 'required|numeric|greater_than[0]'
    ];

    protected $validationMessages = [
        'name' => [
            'required' => 'Nama tipe membership harus diisi',
            'min_length' => 'Nama tipe membership minimal 3 karakter',
            'max_length' => 'Nama tipe membership maksimal 100 karakter'
        ],
        'price' => [
            'required' => 'Harga harus diisi',
            'numeric' => 'Harga harus berupa angka',
            'greater_than' => 'Harga harus lebih dari 0'
        ],
        'duration' => [
            'required' => 'Durasi harus diisi',
            'numeric' => 'Durasi harus berupa angka',
            'greater_than' => 'Durasi harus lebih dari 0'
        ]
    ];
}
