<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\MembershipTypeModel;

class MembershipTypeController extends BaseController
{
    protected $membershipTypeModel;

    public function __construct()
    {
        $this->membershipTypeModel = new MembershipTypeModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Daftar Tipe Membership',
            'membershipTypes' => $this->membershipTypeModel->findAll()
        ];
        return view('membershiptype/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Tipe Membership'
        ];
        return view('membershiptype/create', $data);
    }

    public function store()
    {
        // Validasi input
        $rules = [
            'name' => 'required|min_length[3]|max_length[100]',
            'description' => 'permit_empty',
            'price' => 'required|numeric|greater_than[0]',
            'duration' => 'required|numeric|greater_than[0]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Prepare data
        $data = [
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'price' => $this->request->getPost('price'),
            'duration' => $this->request->getPost('duration')
        ];

        // Insert data
        if ($this->membershipTypeModel->insert($data)) {
            return redirect()->to('/membershiptype')->with('success', 'Tipe membership berhasil ditambahkan');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal menambahkan tipe membership');
    }

    public function edit($id)
    {
        $type = $this->membershipTypeModel->find($id);
        if (!$type) {
            return redirect()->to('/membershiptype')->with('error', 'Tipe membership tidak ditemukan');
        }

        $data = [
            'title' => 'Edit Tipe Membership',
            'type' => $type
        ];
        return view('membershiptype/edit', $data);
    }

    public function update($id)
    {
        // Validasi input
        $rules = [
            'name' => 'required|min_length[3]|max_length[100]',
            'description' => 'permit_empty',
            'price' => 'required|numeric|greater_than[0]',
            'duration' => 'required|numeric|greater_than[0]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Get membership type data
        $type = $this->membershipTypeModel->find($id);
        if (!$type) {
            return redirect()->to('/membershiptype')->with('error', 'Tipe membership tidak ditemukan');
        }

        // Prepare data
        $data = [
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'price' => $this->request->getPost('price'),
            'duration' => $this->request->getPost('duration')
        ];

        // Update data
        if ($this->membershipTypeModel->update($id, $data)) {
            return redirect()->to('/membershiptype')->with('success', 'Tipe membership berhasil diperbarui');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal memperbarui tipe membership');
    }

    public function delete($id)
    {
        // Get membership type data
        $type = $this->membershipTypeModel->find($id);
        if (!$type) {
            return redirect()->to('/membershiptype')->with('error', 'Tipe membership tidak ditemukan');
        }

        // Delete membership type
        if ($this->membershipTypeModel->delete($id)) {
            return redirect()->to('/membershiptype')->with('success', 'Tipe membership berhasil dihapus');
        }

        return redirect()->back()->with('error', 'Gagal menghapus tipe membership');
    }
}
