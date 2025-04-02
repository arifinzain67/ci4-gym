<?php

namespace App\Controllers;

use App\Models\CheckInOutModel;
use App\Models\MemberModel;

class CheckInOutController extends BaseController
{
    protected $checkInOutModel;
    protected $memberModel;

    public function __construct()
    {
        $this->checkInOutModel = new CheckInOutModel();
        $this->memberModel = new MemberModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Check In/Out Member',
            'members' => $this->memberModel->findAll(),
            'active_members' => $this->checkInOutModel->getActiveMembers(),
            'check_in_history' => $this->checkInOutModel->getCheckInHistory()
        ];

        return view('check_in_out/index', $data);
    }

    public function checkIn()
    {
        if (!$this->request->isAJAX()) {
            log_message('error', 'Check-in request bukan AJAX');
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid request method'
            ]);
        }

        $id_member = $this->request->getPost('id_member');
        log_message('info', 'Check-in request untuk member ID: ' . $id_member);

        // Cek status paket member
        $member = $this->memberModel->select('tb_members.*, tb_transaction.status as transaction_status, tb_transaction.expired_at')
            ->join('tb_transaction', 'tb_transaction.id_member = tb_members.id_member')
            ->where('tb_members.id_member', $id_member)
            ->where('tb_transaction.status', 'paid')
            ->where('tb_transaction.expired_at >=', date('Y-m-d'))
            ->first();

        if (!$member) {
            log_message('error', 'Member tidak aktif atau kadaluarsa: ' . $id_member);
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Member tidak dapat melakukan check-in karena status paket tidak aktif atau sudah kadaluarsa!'
            ]);
        }

        // Cek apakah member sedang check in (status active)
        $activeCheckIn = $this->checkInOutModel->where('id_member', $id_member)
            ->where('status', 'active')
            ->first();

        if ($activeCheckIn) {
            log_message('error', 'Member masih aktif: ' . $id_member);
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Member masih berada di gym, silakan lakukan check out terlebih dahulu!'
            ]);
        }

        // Proses check-in
        $data = [
            'id_member' => $id_member,
            'check_in' => date('Y-m-d H:i:s'),
            'status' => 'active'
        ];

        try {
            $this->checkInOutModel->insert($data);
            log_message('info', 'Check-in berhasil untuk member: ' . $id_member);
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Check-in berhasil!'
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error saat check-in: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Terjadi kesalahan saat melakukan check-in'
            ]);
        }
    }

    public function checkOut()
    {
        if (!$this->request->isAJAX()) {
            log_message('error', 'Check-out request bukan AJAX');
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid request method'
            ]);
        }

        $id_member = $this->request->getPost('id_member');
        log_message('info', 'Check-out request untuk member ID: ' . $id_member);

        // Cek apakah member sedang check in
        $activeCheckIn = $this->checkInOutModel->where('id_member', $id_member)
            ->where('status', 'active')
            ->first();

        if (!$activeCheckIn) {
            log_message('error', 'Member belum check-in: ' . $id_member);
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Member belum melakukan check in'
            ]);
        }

        // Update data check out
        $data = [
            'check_out' => date('Y-m-d H:i:s'),
            'status' => 'completed'
        ];

        try {
            $this->checkInOutModel->update($activeCheckIn['id_check'], $data);
            log_message('info', 'Check-out berhasil untuk member: ' . $id_member);
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Check out berhasil'
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error saat check-out: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Terjadi kesalahan saat melakukan check out'
            ]);
        }
    }
}
