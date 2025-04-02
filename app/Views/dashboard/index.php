<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
    <div>
        <span class="text-muted">Last updated: <?= date('d/m/Y H:i') ?> WIB</span>
    </div>
</div>

<!-- Content Row -->
<div class="row">
    <!-- Total Member Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Member</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_members ?></div>
                        <div class="mt-2 text-xs text-success">
                            <i class="fas fa-user-check"></i> <?= $active_members ?> Member Aktif
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Income Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Pendapatan Bulan Ini</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">Rp <?= number_format($monthly_income, 0, ',', '.') ?></div>
                        <div class="mt-2 text-xs text-success">
                            <i class="fas fa-calendar"></i> <?= $monthly_transactions ?> Transaksi
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Today Check-ins Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Check-in Hari Ini</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $today_checkins ?></div>
                        <div class="mt-2 text-xs text-info">
                            <i class="fas fa-clock"></i> Update real-time
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clipboard-check fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Expiring Memberships Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Membership Akan Berakhir</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $expiring_memberships ?></div>
                        <div class="mt-2 text-xs text-warning">
                            <i class="fas fa-exclamation-triangle"></i> Dalam 30 hari ke depan
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar-times fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Content Row -->
<div class="row">
    <!-- Monthly Income Chart -->
    <div class="col-xl-8 col-lg-7">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Pendapatan Bulanan</h6>
            </div>
            <div class="card-body">
                <div class="chart-area">
                    <canvas id="monthlyIncomeChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Today's Check-ins -->
    <div class="col-xl-4 col-lg-5">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Check-in Hari Ini</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Member</th>
                                <th>Waktu</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recent_checkins as $checkin): ?>
                                <tr>
                                    <td><?= esc($checkin['member_name'] ?? 'Member Tidak Ditemukan') ?></td>
                                    <td><?= date('H:i', strtotime($checkin['check_in'])) ?></td>
                                    <td>
                                        <?php if ($checkin['check_out']): ?>
                                            <span class="badge badge-success">Selesai</span>
                                        <?php else: ?>
                                            <span class="badge badge-warning">Masih Ada</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Content Row -->
<div class="row">
    <!-- Recent Transactions -->
    <div class="col-xl-12 col-lg-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Transaksi Terbaru</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Member</th>
                                <th>Paket</th>
                                <th>Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recent_transactions as $transaction): ?>
                                <tr>
                                    <td><?= date('d/m/Y H:i', strtotime($transaction['payment_date'])) ?></td>
                                    <td><?= esc($transaction['member_name']) ?></td>
                                    <td><?= esc($transaction['package_name']) ?></td>
                                    <td>Rp <?= number_format($transaction['amount'], 0, ',', '.') ?></td>
                                    <td>
                                        <span class="badge badge-<?= $transaction['status'] == 'paid' ? 'success' : ($transaction['status'] == 'pending' ? 'warning' : 'danger') ?>">
                                            <?= ucfirst($transaction['status']) ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Monthly Income Chart
        const monthlyData = <?= json_encode($chart_data['monthly']) ?>;
        const monthlyLabels = monthlyData.map(item => item.month);
        const monthlyValues = monthlyData.map(item => item.total);
    
        const monthlyCtx = document.getElementById('monthlyIncomeChart').getContext('2d');
        new Chart(monthlyCtx, {
            type: 'line',
            data: {
                labels: monthlyLabels,
                datasets: [{
                    label: 'Pendapatan Bulanan',
                    data: monthlyValues,
                    borderColor: '#4e73df',
                    backgroundColor: 'rgba(78, 115, 223, 0.05)',
                    tension: 0.3,
                    fill: true
                }]
            },
            options: {
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
        
        // Initialize DataTables if they exist
        if ($.fn && $.fn.DataTable) {
            $('.table').DataTable({
                "ordering": false,
                "info": false,
                "paging": false
            });
        }
    });
</script>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    .chart-area {
        position: relative;
        height: 320px;
        width: 100%;
    }

    .border-left-primary {
        border-left: 4px solid #4e73df !important;
    }

    .border-left-success {
        border-left: 4px solid #1cc88a !important;
    }

    .border-left-info {
        border-left: 4px solid #36b9cc !important;
    }

    .border-left-warning {
        border-left: 4px solid #f6c23e !important;
    }

    .card {
        position: relative;
        display: flex;
        flex-direction: column;
        min-width: 0;
        word-wrap: break-word;
        background-color: #fff;
        background-clip: border-box;
        border: 1px solid #e3e6f0;
        border-radius: 0.35rem;
    }

    .card-header {
        background-color: #f8f9fc;
        border-bottom: 1px solid #e3e6f0;
    }

    .card-body {
        flex: 1 1 auto;
        min-height: 1px;
        padding: 1.25rem;
    }

    .text-xs {
        font-size: .7rem;
    }

    .text-gray-300 {
        color: #dddfeb !important;
    }

    .text-gray-800 {
        color: #5a5c69 !important;
    }

    .badge {
        padding: 0.5em 0.75em;
        font-size: 0.85em;
        font-weight: 500;
        border-radius: 0.25rem;
    }

    .badge-success {
        background-color: #1cc88a;
        color: white;
    }

    .badge-warning {
        background-color: #f6c23e;
        color: white;
    }

    .badge-danger {
        background-color: #e74a3b;
        color: white;
    }

    .table th {
        background-color: #f8f9fc;
        border-bottom: 2px solid #e3e6f0;
        color: #4e73df;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
    }

    .table td {
        vertical-align: middle !important;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(0, 0, 0, .075);
    }

    .shadow {
        box-shadow: 0 .15rem 1.75rem 0 rgba(58, 59, 69, .15) !important;
    }

    .text-primary {
        color: #4e73df !important;
    }

    .text-success {
        color: #1cc88a !important;
    }

    .text-info {
        color: #36b9cc !important;
    }

    .text-warning {
        color: #f6c23e !important;
    }

    .font-weight-bold {
        font-weight: 700 !important;
    }

    .text-uppercase {
        text-transform: uppercase !important;
    }
</style>
<?= $this->endSection() ?>

<?= $this->endSection() ?>