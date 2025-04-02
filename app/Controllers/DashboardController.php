<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\MemberModel;
use App\Models\TransactionModel;
use App\Models\CheckInOutModel;
use App\Models\MembershipTypeModel;
use Config\Services;

class DashboardController extends BaseController
{
    protected $memberModel;
    protected $transactionModel;
    protected $checkInOutModel;
    protected $membershipTypeModel;
    protected $session;

    public function __construct()
    {
        $this->memberModel = new MemberModel();
        $this->transactionModel = new TransactionModel();
        $this->checkInOutModel = new CheckInOutModel();
        $this->membershipTypeModel = new MembershipTypeModel();
        $this->session = Services::session();
        log_message('debug', 'DashboardController - Current session data: ' . json_encode($this->session->get()));
    }

    public function index()
    {
        if (!$this->session->get('logged_in')) {
            log_message('debug', 'DashboardController - User not logged in, redirecting to login');
            return redirect()->to('auth/login');
        }

        $data = [
            'title' => 'Dashboard',
            'total_members' => $this->memberModel->countAll(),
            'active_members' => $this->transactionModel->where('status', 'paid')
                ->where('expired_at >=', date('Y-m-d'))
                ->countAllResults(),
            'monthly_income' => $this->transactionModel->where('status', 'paid')
                ->where('MONTH(payment_date)', date('m'))
                ->where('YEAR(payment_date)', date('Y'))
                ->selectSum('amount', 'total')
                ->get()
                ->getRow()
                ->total ?? 0,
            'monthly_transactions' => $this->transactionModel->where('MONTH(payment_date)', date('m'))
                ->where('YEAR(payment_date)', date('Y'))
                ->countAllResults(),
            'today_checkins' => $this->checkInOutModel->where('DATE(check_in)', date('Y-m-d'))
                ->countAllResults(),
            'expiring_memberships' => $this->transactionModel->where('status', 'paid')
                ->where('expired_at <=', date('Y-m-d', strtotime('+30 days')))
                ->countAllResults(),
            'recent_transactions' => $this->transactionModel->select('tb_transaction.*, tb_members.name as member_name, tb_membership_types.name as package_name')
                ->join('tb_members', 'tb_members.id_member = tb_transaction.id_member')
                ->join('tb_membership_types', 'tb_membership_types.id_type = tb_transaction.id_type')
                ->orderBy('payment_date', 'DESC')
                ->limit(5)
                ->findAll(),
            'recent_checkins' => $this->checkInOutModel->select('tb_check_in_out.*, tb_members.name as member_name')
                ->join('tb_members', 'tb_members.id_member = tb_check_in_out.id_member')
                ->where('DATE(check_in)', date('Y-m-d'))
                ->orderBy('check_in', 'DESC')
                ->limit(5)
                ->findAll(),
            'chart_data' => [
                'monthly' => $this->getMonthlyChartData()
            ],
            'user' => $this->session->get()
        ];

        log_message('debug', 'Dashboard session data: ' . json_encode($this->session->get()));
        log_message('debug', 'DashboardController - Loading dashboard with data: ' . json_encode($data));
        return view('dashboard/index', $data);
    }

    private function getMonthlyChartData()
    {
        $data = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = date('Y-m', strtotime("-$i months"));
            $total = $this->transactionModel->where('status', 'paid')
                ->where('DATE_FORMAT(payment_date, "%Y-%m")', $date)
                ->selectSum('amount', 'total')
                ->get()
                ->getRow()
                ->total ?? 0;

            $data[] = [
                'month' => date('M Y', strtotime($date)),
                'total' => $total
            ];
        }
        return $data;
    }
}
