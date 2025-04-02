<?php

namespace App\Controllers;

use App\Models\MemberModel;
use App\Models\TransactionModel;
use App\Models\CheckInOutModel;
use CodeIgniter\HTTP\Files\UploadedFile;

class MemberController extends BaseController
{
    protected $memberModel;
    protected $transactionModel;
    protected $checkInOutModel;
    protected $validation;

    public function __construct()
    {
        $this->memberModel = new MemberModel();
        $this->transactionModel = new TransactionModel();
        $this->checkInOutModel = new CheckInOutModel();
        $this->validation = \Config\Services::validation();
    }

    public function index()
    {
        $members = $this->memberModel->findAll();
        $memberStatuses = [];

        foreach ($members as $member) {
            $memberStatuses[$member['id_member']] = $this->transactionModel->getMemberStatus($member['id_member']);
        }

        $data = [
            'title' => 'Daftar Member',
            'members' => $members,
            'memberStatuses' => $memberStatuses
        ];
        return view('member/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Member'
        ];
        return view('member/create', $data);
    }

    public function store()
    {
        // Validasi input
        $rules = [
            'name' => 'required|min_length[3]|max_length[100]',
            'email' => 'permit_empty|valid_email|is_unique[tb_members.email,id_member,{id}]',
            'phone' => 'permit_empty|min_length[10]|max_length[15]',
            'gender' => 'required|in_list[L,P]',
            'address' => 'permit_empty',
            'photo' => 'permit_empty|uploaded[photo]|max_size[photo,1024]|is_image[photo]|mime_in[photo,image/jpg,image/jpeg,image/png,image/webp]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Handle foto upload
        $photo = $this->request->getFile('photo');
        $photoName = null;

        if ($photo->isValid() && !$photo->hasMoved()) {
            $photoName = $photo->getRandomName();
            $photo->move('uploads/members', $photoName);
        }

        // Simpan data member
        $data = [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email') ?: null,
            'phone' => $this->request->getPost('phone') ?: null,
            'gender' => $this->request->getPost('gender'),
            'address' => $this->request->getPost('address') ?: null,
            'photo' => $photoName
        ];

        if ($this->memberModel->insert($data)) {
            return redirect()->to('member')->with('success', 'Member berhasil ditambahkan');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal menambahkan member');
    }

    public function edit($id)
    {
        $member = $this->memberModel->find($id);
        if (!$member) {
            return redirect()->to('/member')->with('error', 'Member tidak ditemukan');
        }

        $data = [
            'title' => 'Edit Member',
            'member' => $member
        ];
        return view('member/edit', $data);
    }

    public function update($id)
    {
        // Validasi input
        $rules = [
            'name' => 'required|min_length[3]|max_length[100]',
            'email' => 'permit_empty|valid_email|is_unique[tb_members.email,id_member,' . $id . ']',
            'phone' => 'required|min_length[10]|max_length[15]',
            'gender' => 'required|in_list[L,P]',
            'address' => 'permit_empty',
            'photo' => 'permit_empty|max_size[photo,1024]|is_image[photo]|mime_in[photo,image/jpg,image/jpeg,image/png,image/webp]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Get member data
        $member = $this->memberModel->find($id);
        if (!$member) {
            return redirect()->to('/member')->with('error', 'Member tidak ditemukan');
        }

        // Handle file upload if new photo is uploaded
        $photoName = $member['photo'];
        $photo = $this->request->getFile('photo');
        if ($photo->isValid() && !$photo->hasMoved()) {
            // Delete old photo if exists
            if ($member['photo'] && file_exists('uploads/members/' . $member['photo'])) {
                unlink('uploads/members/' . $member['photo']);
            }
            $photoName = $photo->getRandomName();
            $photo->move('uploads/members', $photoName);
        }

        // Prepare data
        $data = [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone'),
            'gender' => $this->request->getPost('gender'),
            'address' => $this->request->getPost('address'),
            'photo' => $photoName
        ];

        // Update data
        if ($this->memberModel->update($id, $data)) {
            return redirect()->to('/member')->with('success', 'Member berhasil diperbarui');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal memperbarui member');
    }

    public function delete($id)
    {
        // Get member data
        $member = $this->memberModel->find($id);
        if (!$member) {
            return redirect()->to('/member')->with('error', 'Member tidak ditemukan');
        }

        // Delete photo if exists
        if ($member['photo'] && file_exists('uploads/members/' . $member['photo'])) {
            unlink('uploads/members/' . $member['photo']);
        }

        // Delete member
        if ($this->memberModel->delete($id)) {
            return redirect()->to('/member')->with('success', 'Member berhasil dihapus');
        }

        return redirect()->back()->with('error', 'Gagal menghapus member');
    }

    public function detail($id)
    {
        $data = [
            'title' => 'Detail Member',
            'member' => $this->memberModel->find($id),
            'active_transaction' => $this->transactionModel->getMemberStatus($id),
            'transactions' => $this->transactionModel->getTransactionByMember($id),
            'check_in_history' => $this->checkInOutModel->getCheckInHistoryByMember($id)
        ];

        if (empty($data['member'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Member tidak ditemukan');
        }

        return view('member/detail', $data);
    }

    private function generateMemberCode()
    {
        $year = date('y');
        $lastMember = $this->memberModel->where('SUBSTRING(member_code, 3, 2)', $year)
            ->orderBy('member_code', 'DESC')
            ->first();

        if ($lastMember) {
            $lastNumber = intval(substr($lastMember['member_code'], 5));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return 'RG' . $year . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
}
