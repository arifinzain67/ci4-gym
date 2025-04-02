<?php

namespace App\Controllers;

use App\Models\TransactionModel;

class FinancialReportController extends BaseController
{
    protected $transactionModel;

    public function __construct()
    {
        $this->transactionModel = new TransactionModel();
    }

    private function getAvailableYears()
    {
        $years = $this->transactionModel->select('YEAR(payment_date) as year')
            ->where('status', 'paid')
            ->groupBy('year')
            ->orderBy('year', 'DESC')
            ->findAll();

        return array_column($years, 'year');
    }

    public function index()
    {
        $filter_type = $this->request->getGet('filter_type') ?? 'date';
        $start_date = $this->request->getGet('start_date') ?? date('Y-m-d');
        $end_date = $this->request->getGet('end_date') ?? date('Y-m-d');
        $month = $this->request->getGet('month') ?? date('m');
        $year = $this->request->getGet('year') ?? date('Y');

        // Get available years from database
        $available_years = $this->getAvailableYears();
        if (empty($available_years)) {
            $available_years = [date('Y')];
        }

        $data = [
            'title' => 'Laporan Keuangan',
            'filter_type' => $filter_type,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'month' => $month,
            'year' => $year,
            'available_years' => $available_years,
            'daily_total' => $this->transactionModel->getDailyTotal(date('Y-m-d')),
            'monthly_total' => $this->transactionModel->getMonthlyTotal(date('Y-m')),
            'yearly_total' => $this->transactionModel->getYearlyTotal(date('Y')),
            'total_transactions' => $this->transactionModel->where('status', 'paid')->countAllResults(),
            'monthly_data' => $this->transactionModel->getMonthlyData(),
            'yearly_data' => $this->transactionModel->getYearlyData(),
            'transactions' => $this->transactionModel->getFilteredTransactions($filter_type, $start_date, $end_date, $month, $year)
        ];

        return view('financial_report/index', $data);
    }

    public function daily()
    {
        $date = $this->request->getGet('date') ?? date('Y-m-d');

        $data = [
            'title' => 'Laporan Harian',
            'date' => $date,
            'transactions' => $this->transactionModel->getDailyTransactions($date),
            'total' => $this->transactionModel->getDailyTotal($date),
            'count' => $this->transactionModel->getDailyCount($date)
        ];

        return view('financial_report/daily', $data);
    }

    public function monthly()
    {
        $month = $this->request->getGet('month') ?? date('Y-m');

        $data = [
            'title' => 'Laporan Bulanan',
            'month' => $month,
            'transactions' => $this->transactionModel->getMonthlyTransactions($month),
            'total' => $this->transactionModel->getMonthlyTotal($month),
            'count' => $this->transactionModel->getMonthlyCount($month)
        ];

        return view('financial_report/monthly', $data);
    }

    public function yearly()
    {
        $year = $this->request->getGet('year') ?? date('Y');

        $data = [
            'title' => 'Laporan Tahunan',
            'year' => $year,
            'transactions' => $this->transactionModel->getYearlyTransactions($year),
            'total' => $this->transactionModel->getYearlyTotal($year),
            'count' => $this->transactionModel->getYearlyCount($year)
        ];

        return view('financial_report/yearly', $data);
    }
}
