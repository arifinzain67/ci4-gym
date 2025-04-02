<?php

namespace App\Controllers;

use App\Models\TransactionModel;
use App\Models\MemberModel;
use App\Models\MembershipTypeModel;
use DateTime;
use DateTimeZone;
use CodeIgniter\Database\BaseConnection;

class TransactionController extends BaseController
{
    protected $transactionModel;
    protected $memberModel;
    protected $membershipTypeModel;
    protected $validation;
    protected $db;

    public function __construct()
    {
        $this->transactionModel = new TransactionModel();
        $this->memberModel = new MemberModel();
        $this->membershipTypeModel = new MembershipTypeModel();
        $this->validation = \Config\Services::validation();
        $this->db = \Config\Database::connect();

        // Set timezone untuk seluruh operasi di controller ini
        date_default_timezone_set('Asia/Jakarta');
    }

    public function index()
    {
        $data = [
            'title' => 'Transaksi',
            'transactions' => $this->transactionModel->getTransactions(),
            'members' => $this->memberModel->findAll(),
            'membership_types' => $this->membershipTypeModel->findAll(),
            'start_date' => $this->request->getGet('start_date') ?? date('Y-m-d'),
            'end_date' => $this->request->getGet('end_date') ?? date('Y-m-d')
        ];

        return view('transaction/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Transaksi',
            'validation' => $this->validation,
            'members' => $this->memberModel->findAll(),
            'membershipTypes' => $this->membershipTypeModel->findAll()
        ];
        return view('transaction/create', $data);
    }

    public function store()
    {
        try {
            // Log data yang diterima
            log_message('debug', 'Data POST yang diterima: ' . json_encode($this->request->getPost()));

            // Validasi input
            $rules = [
                'id_member' => 'required|numeric',
                'id_type' => 'required|numeric',
                'amount' => 'required|numeric|greater_than[0]',
                'amount_paid' => 'required|numeric|greater_than[0]',
                'payment_type' => 'required|in_list[cash,transfer,e-wallet]',
                'status' => 'required|in_list[pending,paid,cancelled]'
            ];

            if (!$this->validate($rules)) {
                log_message('error', 'Validasi gagal: ' . json_encode($this->validator->getErrors()));
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Data tidak valid. Silakan cek kembali.'
                ]);
            }

            // Ambil data membership type untuk mendapatkan durasi
            $membershipType = $this->membershipTypeModel->find($this->request->getPost('id_type'));
            if (!$membershipType) {
                log_message('error', 'Membership type tidak ditemukan');
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Paket membership tidak ditemukan'
                ]);
            }

            // Hitung expired_at berdasarkan durasi paket dengan waktu Indonesia
            $duration = $membershipType['duration']; // dalam hari
            $now = new DateTime('now', new DateTimeZone('Asia/Jakarta'));
            $expiredAt = clone $now;
            $expiredAt->modify("+{$duration} days");

            // Ambil data dari form
            $data = [
                'id_member' => $this->request->getPost('id_member'),
                'id_type' => $this->request->getPost('id_type'),
                'amount' => $this->request->getPost('amount'),
                'amount_paid' => $this->request->getPost('amount_paid'),
                'payment_type' => $this->request->getPost('payment_type'),
                'status' => $this->request->getPost('status'),
                'payment_date' => $now->format('Y-m-d H:i:s'),
                'expired_at' => $expiredAt->format('Y-m-d H:i:s')
            ];

            // Debug log
            log_message('debug', 'Data yang akan disimpan: ' . json_encode($data));

            // Simpan data menggunakan method insertTransaction
            $result = $this->transactionModel->insertTransaction($data);

            if ($result) {
                log_message('info', 'Transaksi berhasil disimpan dengan ID: ' . $result);
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Transaksi berhasil ditambahkan',
                    'id_transaction' => $result
                ]);
            } else {
                log_message('error', 'Gagal menyimpan data transaksi. Error: ' . json_encode($this->transactionModel->errors()));
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Gagal menambahkan transaksi'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error saat menyimpan transaksi: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan transaksi: ' . $e->getMessage()
            ]);
        }
    }

    public function edit($id)
    {
        $data = [
            'title' => 'Edit Transaksi',
            'validation' => $this->validation,
            'transaction' => $this->transactionModel->getTransactionWithDetails($id),
            'members' => $this->memberModel->findAll(),
            'membershipTypes' => $this->membershipTypeModel->findAll()
        ];

        if (empty($data['transaction'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Transaksi tidak ditemukan');
        }

        return view('transaction/edit', $data);
    }

    public function update($id)
    {
        // Validasi input
        $rules = [
            'id_member' => 'required|numeric',
            'id_type' => 'required|numeric',
            'amount' => 'required|numeric|greater_than[0]',
            'payment_date' => 'required|valid_date',
            'status' => 'required|in_list[pending,paid,cancelled]',
            'expired_at' => 'required|valid_date'
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/transaction/edit/' . $id)->withInput();
        }

        $data = [
            'id_member' => $this->request->getPost('id_member'),
            'id_type' => $this->request->getPost('id_type'),
            'amount' => $this->request->getPost('amount'),
            'payment_date' => $this->request->getPost('payment_date'),
            'status' => $this->request->getPost('status'),
            'expired_at' => $this->request->getPost('expired_at')
        ];

        $this->transactionModel->update($id, $data);
        session()->setFlashdata('success', 'Transaksi berhasil diupdate');
        return redirect()->to('/transaction');
    }

    public function delete($id)
    {
        try {
            // Periksa role, hanya pemilik yang boleh menghapus
            if (session()->get('role') !== 'pemilik') {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Anda tidak memiliki hak akses untuk menghapus transaksi'
                ]);
            }
            
            $this->db->transStart();

            // Cek apakah transaksi ada
            $transaction = $this->transactionModel->find($id);
            if (!$transaction) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Transaksi tidak ditemukan'
                ]);
            }

            // Cek apakah transaksi memiliki relasi dengan data lain
            $checkInModel = new \App\Models\CheckInOutModel();
            $hasCheckIn = $checkInModel->where('id_member', $transaction['id_member'])
                ->where('DATE(check_in)', date('Y-m-d', strtotime($transaction['payment_date'])))
                ->countAllResults() > 0;

            if ($hasCheckIn) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Transaksi tidak dapat dihapus karena member sudah melakukan check-in'
                ]);
            }

            // Hapus transaksi
            if ($this->transactionModel->delete($id)) {
                $this->db->transComplete();
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Transaksi berhasil dihapus'
                ]);
            } else {
                $this->db->transRollback();
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Gagal menghapus transaksi'
                ]);
            }
        } catch (\Exception $e) {
            $this->db->transRollback();
            log_message('error', 'Error saat menghapus transaksi: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus transaksi: ' . $e->getMessage()
            ]);
        }
    }

    public function receipt($id)
    {
        $transaction = $this->transactionModel->getTransactionById($id);

        if (!$transaction) {
            return redirect()->to('transaction')->with('error', 'Transaksi tidak ditemukan');
        }

        return view('transaction/receipt', ['transaction' => $transaction]);
    }
}
