<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionModel extends Model
{
    protected $table = 'tb_transaction';
    protected $primaryKey = 'id_transaction';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['id_member', 'id_type', 'amount', 'amount_paid', 'payment_type', 'payment_date', 'status', 'expired_at'];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'id_member' => 'required',
        'id_type' => 'required',
        'amount' => 'required|numeric',
        'amount_paid' => 'required|numeric',
        'payment_type' => 'required|in_list[cash,transfer,e-wallet]',
        'status' => 'required|in_list[pending,paid,cancelled]'
    ];

    protected $validationMessages = [
        'id_member' => [
            'required' => 'Member harus dipilih'
        ],
        'id_type' => [
            'required' => 'Tipe membership harus dipilih'
        ],
        'amount' => [
            'required' => 'Total harga harus diisi',
            'numeric' => 'Total harga harus berupa angka'
        ],
        'amount_paid' => [
            'required' => 'Jumlah bayar harus diisi',
            'numeric' => 'Jumlah bayar harus berupa angka'
        ],
        'payment_type' => [
            'required' => 'Tipe pembayaran harus dipilih',
            'in_list' => 'Tipe pembayaran tidak valid'
        ],
        'status' => [
            'required' => 'Status harus dipilih',
            'in_list' => 'Status tidak valid'
        ]
    ];

    public function getTransactionWithDetails($id = null)
    {
        $builder = $this->db->table($this->table);
        $builder->select('tb_transaction.*, tb_members.name as member_name, tb_membership_types.name as membership_type');
        $builder->join('tb_members', 'tb_members.id_member = tb_transaction.id_member');
        $builder->join('tb_membership_types', 'tb_membership_types.id_type = tb_transaction.id_type');

        if ($id !== null) {
            $builder->where('tb_transaction.id_transaction', $id);
        }

        return $builder->get()->getResultArray();
    }

    // Mendapatkan data transaksi berdasarkan member
    public function getTransactionByMember($memberId)
    {
        return $this->select('tb_transaction.*, tb_membership_types.name as package_name')
            ->join('tb_membership_types', 'tb_membership_types.id_type = tb_transaction.id_type')
            ->where('tb_transaction.id_member', $memberId)
            ->orderBy('tb_transaction.payment_date', 'DESC')
            ->findAll();
    }

    // Mendapatkan data transaksi berdasarkan ID
    public function getTransactionById($id)
    {
        return $this->select('tb_transaction.*, tb_members.name as member_name, tb_members.member_code, tb_membership_types.name as package_name')
            ->join('tb_members', 'tb_members.id_member = tb_transaction.id_member')
            ->join('tb_membership_types', 'tb_membership_types.id_type = tb_transaction.id_type')
            ->where('tb_transaction.id_transaction', $id)
            ->first();
    }

    // Mendapatkan status membership member
    public function getMemberStatus($memberId)
    {
        return $this->select('tb_transaction.*, tb_membership_types.name as package_name')
            ->join('tb_membership_types', 'tb_membership_types.id_type = tb_transaction.id_type')
            ->where('tb_transaction.id_member', $memberId)
            ->where('tb_transaction.status', 'paid')
            ->where('tb_transaction.expired_at >=', date('Y-m-d'))
            ->orderBy('tb_transaction.expired_at', 'DESC')
            ->first();
    }

    public function getTransactions($start_date = null, $end_date = null)
    {
        $builder = $this->select('tb_transaction.*, tb_members.name as member_name, tb_members.member_code, tb_membership_types.name as package_name')
            ->join('tb_members', 'tb_members.id_member = tb_transaction.id_member')
            ->join('tb_membership_types', 'tb_membership_types.id_type = tb_transaction.id_type');

        if ($start_date && $end_date) {
            $builder->where('tb_transaction.payment_date >=', $start_date)
                ->where('tb_transaction.payment_date <=', $end_date);
        }

        return $builder->orderBy('tb_transaction.payment_date', 'DESC')
            ->findAll();
    }

    public function insertTransaction($data)
    {
        try {
            // Validasi data
            if (empty($data['id_member']) || empty($data['id_type']) || empty($data['amount'])) {
                log_message('error', 'Data tidak lengkap: ' . json_encode($data));
                return false;
            }

            // Cek apakah member dan membership type ada
            $db = \Config\Database::connect();

            // Cek member
            $member = $db->table('tb_members')->where('id_member', $data['id_member'])->get()->getRowArray();
            if (!$member) {
                log_message('error', 'Member tidak ditemukan: ' . $data['id_member']);
                return false;
            }

            // Cek membership type
            $type = $db->table('tb_membership_types')->where('id_type', $data['id_type'])->get()->getRowArray();
            if (!$type) {
                log_message('error', 'Membership type tidak ditemukan: ' . $data['id_type']);
                return false;
            }

            // Validasi amount_paid
            if ($data['amount_paid'] < $data['amount']) {
                log_message('error', 'Jumlah bayar kurang dari total harga');
                return false;
            }

            // Debug log
            log_message('debug', 'Mencoba menyimpan data transaksi: ' . json_encode($data));

            // Simpan data
            $this->db->transStart();

            $this->insert($data);
            $insertId = $this->insertID();

            if ($insertId) {
                $this->db->transComplete();
                log_message('info', 'Transaksi berhasil disimpan dengan ID: ' . $insertId);
                return $insertId;
            } else {
                $this->db->transRollback();
                log_message('error', 'Gagal menyimpan transaksi: ' . json_encode($this->errors()));
                return false;
            }
        } catch (\Exception $e) {
            $this->db->transRollback();
            log_message('error', 'Error saat menyimpan transaksi: ' . $e->getMessage());
            return false;
        }
    }

    public function updateTransaction($id, $data)
    {
        // Validasi apakah id_type ada di tabel tb_membership_types
        $db = \Config\Database::connect();
        $builderMembership = $db->table('tb_membership_types');
        $membershipType = $builderMembership->where('id_type', $data['id_type'])->get()->getRow();

        if (!$membershipType) {
            throw new \Exception('Membership type ID tidak valid.');
        }

        // Validasi apakah id_member ada di tabel tb_members
        $builderMember = $db->table('tb_members');
        $member = $builderMember->where('id_member', $data['id_member'])->get()->getRow();

        if (!$member) {
            throw new \Exception('Member ID tidak valid.');
        }

        // Tambahkan nama member ke data untuk konfirmasi
        $data['member_name'] = $member->name;

        // Jika validasi lolos, perbarui data
        return $this->update($id, $data);
    }

    // Method untuk laporan harian
    public function getDailyTotal($date)
    {
        return $this->selectSum('amount', 'total')
            ->where('DATE(payment_date)', $date)
            ->where('status', 'paid')
            ->get()
            ->getRow()
            ->total ?? 0;
    }

    public function getDailyCount($date)
    {
        return $this->where('DATE(payment_date)', $date)
            ->where('status', 'paid')
            ->countAllResults();
    }

    public function getDailyTransactions($date)
    {
        return $this->select('tb_transaction.*, tb_members.member_code, tb_members.name as member_name, tb_membership_types.name as membership_name')
            ->join('tb_members', 'tb_members.id_member = tb_transaction.id_member')
            ->join('tb_membership_types', 'tb_membership_types.id_type = tb_transaction.id_type')
            ->where('DATE(tb_transaction.payment_date)', $date)
            ->where('tb_transaction.status', 'paid')
            ->orderBy('tb_transaction.payment_date', 'DESC')
            ->findAll();
    }

    // Method untuk laporan bulanan
    public function getMonthlyTotal($month)
    {
        return $this->selectSum('amount', 'total')
            ->where('DATE_FORMAT(payment_date, "%Y-%m")', $month)
            ->where('status', 'paid')
            ->get()
            ->getRow()
            ->total ?? 0;
    }

    public function getMonthlyCount($month)
    {
        return $this->where('DATE_FORMAT(payment_date, "%Y-%m")', $month)
            ->where('status', 'paid')
            ->countAllResults();
    }

    public function getMonthlyTransactions($month)
    {
        return $this->select('tb_transaction.*, tb_members.member_code, tb_members.name as member_name, tb_membership_types.name as membership_name')
            ->join('tb_members', 'tb_members.id_member = tb_transaction.id_member')
            ->join('tb_membership_types', 'tb_membership_types.id_type = tb_transaction.id_type')
            ->where('DATE_FORMAT(tb_transaction.payment_date, "%Y-%m")', $month)
            ->where('tb_transaction.status', 'paid')
            ->orderBy('tb_transaction.payment_date', 'DESC')
            ->findAll();
    }

    public function getMonthlyChartData($startDate = null, $endDate = null)
    {
        $builder = $this->select('DATE_FORMAT(payment_date, "%Y-%m") as month, SUM(amount) as total')
            ->where('status', 'paid');

        if ($startDate && $endDate) {
            $builder->where('payment_date >=', $startDate)
                ->where('payment_date <=', $endDate);
        } else {
            $builder->where('payment_date >= DATE_SUB(NOW(), INTERVAL 12 MONTH)');
        }

        $data = $builder->groupBy('month')
            ->orderBy('month', 'ASC')
            ->findAll();

        return $data;
    }

    // Method untuk laporan tahunan
    public function getYearlyTotal($year)
    {
        return $this->selectSum('amount', 'total')
            ->where('YEAR(payment_date)', $year)
            ->where('status', 'paid')
            ->get()
            ->getRow()
            ->total ?? 0;
    }

    public function getYearlyCount($year)
    {
        return $this->where('YEAR(payment_date)', $year)
            ->where('status', 'paid')
            ->countAllResults();
    }

    public function getYearlyTransactions($year)
    {
        return $this->select('tb_transaction.*, tb_members.member_code, tb_members.name as member_name, tb_membership_types.name as membership_name')
            ->join('tb_members', 'tb_members.id_member = tb_transaction.id_member')
            ->join('tb_membership_types', 'tb_membership_types.id_type = tb_transaction.id_type')
            ->where('YEAR(tb_transaction.payment_date)', $year)
            ->where('tb_transaction.status', 'paid')
            ->orderBy('tb_transaction.payment_date', 'DESC')
            ->findAll();
    }

    public function getYearlyChartData($startDate = null, $endDate = null)
    {
        $builder = $this->select('YEAR(payment_date) as year, SUM(amount) as total')
            ->where('status', 'paid');

        if ($startDate && $endDate) {
            $builder->where('payment_date >=', $startDate)
                ->where('payment_date <=', $endDate);
        } else {
            $builder->where('payment_date >= DATE_SUB(NOW(), INTERVAL 5 YEAR)');
        }

        $data = $builder->groupBy('year')
            ->orderBy('year', 'ASC')
            ->findAll();

        return $data;
    }

    public function getFilteredTransactions($startDate, $endDate)
    {
        return $this->select('tb_transaction.*, tb_members.member_code, tb_members.name as member_name, tb_membership_types.name as membership_name')
            ->join('tb_members', 'tb_members.id_member = tb_transaction.id_member')
            ->join('tb_membership_types', 'tb_membership_types.id_type = tb_transaction.id_type')
            ->where('DATE(tb_transaction.payment_date) >=', $startDate)
            ->where('DATE(tb_transaction.payment_date) <=', $endDate)
            ->where('tb_transaction.status', 'paid')
            ->orderBy('tb_transaction.payment_date', 'DESC')
            ->findAll();
    }

    public function getMonthlyData()
    {
        return $this->select('DATE_FORMAT(payment_date, "%M %Y") as month, SUM(amount) as total')
            ->where('status', 'paid')
            ->groupBy('DATE_FORMAT(payment_date, "%Y-%m")')
            ->orderBy('payment_date', 'DESC')
            ->limit(12)
            ->findAll();
    }

    public function getYearlyData()
    {
        return $this->select('YEAR(payment_date) as year, SUM(amount) as total')
            ->where('status', 'paid')
            ->groupBy('YEAR(payment_date)')
            ->orderBy('year', 'DESC')
            ->findAll();
    }
}
