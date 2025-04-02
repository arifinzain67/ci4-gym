<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Laporan Keuangan</h3>
                </div>
                <div class="card-body">
                    <!-- Filter Form -->
                    <form action="<?= base_url('financial-report') ?>" method="get" class="mb-4">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Filter Berdasarkan</label>
                                    <select name="filter_type" id="filter_type" class="form-control">
                                        <option value="date" <?= $filter_type == 'date' ? 'selected' : '' ?>>Tanggal
                                        </option>
                                        <option value="month" <?= $filter_type == 'month' ? 'selected' : '' ?>>Bulan
                                        </option>
                                        <option value="year" <?= $filter_type == 'year' ? 'selected' : '' ?>>Tahun
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3" id="date_range">
                                <div class="form-group">
                                    <label>Tanggal Mulai</label>
                                    <input type="date" name="start_date" class="form-control"
                                        value="<?= $start_date ?>">
                                </div>
                            </div>
                            <div class="col-md-3" id="date_range_end">
                                <div class="form-group">
                                    <label>Tanggal Selesai</label>
                                    <input type="date" name="end_date" class="form-control" value="<?= $end_date ?>">
                                </div>
                            </div>
                            <div class="col-md-3" id="month_filter" style="display: none;">
                                <div class="form-group">
                                    <label>Bulan</label>
                                    <select name="month" class="form-control">
                                        <option value="01" <?= $month == '01' ? 'selected' : '' ?>>Januari</option>
                                        <option value="02" <?= $month == '02' ? 'selected' : '' ?>>Februari</option>
                                        <option value="03" <?= $month == '03' ? 'selected' : '' ?>>Maret</option>
                                        <option value="04" <?= $month == '04' ? 'selected' : '' ?>>April</option>
                                        <option value="05" <?= $month == '05' ? 'selected' : '' ?>>Mei</option>
                                        <option value="06" <?= $month == '06' ? 'selected' : '' ?>>Juni</option>
                                        <option value="07" <?= $month == '07' ? 'selected' : '' ?>>Juli</option>
                                        <option value="08" <?= $month == '08' ? 'selected' : '' ?>>Agustus</option>
                                        <option value="09" <?= $month == '09' ? 'selected' : '' ?>>September</option>
                                        <option value="10" <?= $month == '10' ? 'selected' : '' ?>>Oktober</option>
                                        <option value="11" <?= $month == '11' ? 'selected' : '' ?>>November</option>
                                        <option value="12" <?= $month == '12' ? 'selected' : '' ?>>Desember</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3" id="year_filter" style="display: none;">
                                <div class="form-group">
                                    <label>Tahun</label>
                                    <select name="year" class="form-control">
                                        <?php foreach ($available_years as $available_year): ?>
                                            <option value="<?= $available_year ?>"
                                                <?= $year == $available_year ? 'selected' : '' ?>><?= $available_year ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <button type="submit" class="btn btn-primary btn-block">Filter</button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- Statistik Cards -->
                    <div class="row">
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Pendapatan Hari Ini</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                Rp <?= number_format($daily_total, 0, ',', '.') ?>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-calendar-day fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Pendapatan Bulan Ini</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                Rp <?= number_format($monthly_total, 0, ',', '.') ?>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                                Pendapatan Tahun Ini</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                Rp <?= number_format($yearly_total, 0, ',', '.') ?>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                Total Transaksi</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <?= number_format($total_transactions, 0, ',', '.') ?>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Grafik -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Pendapatan Bulanan</h3>
                                </div>
                                <div class="card-body">
                                    <canvas id="monthlyChart"
                                        style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Pendapatan Tahunan</h3>
                                </div>
                                <div class="card-body">
                                    <canvas id="yearlyChart"
                                        style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabel Detail -->
                    <div class="card mt-4">
                        <div class="card-header">
                            <h3 class="card-title">Detail Transaksi</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Member</th>
                                        <th>Paket</th>
                                        <th>Jumlah</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($transactions)): ?>
                                        <?php
                                        $total_filtered = 0;
                                        foreach ($transactions as $index => $transaction):
                                            $total_filtered += $transaction['amount'];
                                        ?>
                                            <tr>
                                                <td><?= $index + 1 ?></td>
                                                <td><?= date('d/m/Y H:i', strtotime($transaction['payment_date'])) ?></td>
                                                <td><?= $transaction['member_name'] ?></td>
                                                <td><?= $transaction['membership_name'] ?></td>
                                                <td class="text-right">
                                                    <?= number_format($transaction['amount'], 0, ',', '.') ?></td>
                                                <td>
                                                    <span
                                                        class="badge badge-<?= $transaction['status'] == 'paid' ? 'success' : 'warning' ?>">
                                                        <?= ucfirst($transaction['status']) ?>
                                                    </span>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                        <tr class="table-primary">
                                            <td colspan="4" class="text-right"><strong>Total Pendapatan:</strong></td>
                                            <td class="text-right"><strong>Rp
                                                    <?= number_format($total_filtered, 0, ',', '.') ?></strong></td>
                                            <td></td>
                                        </tr>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6" class="text-center">Tidak ada data transaksi</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Filter Type Change Handler
        const filterType = document.getElementById('filter_type');
        const dateRange = document.getElementById('date_range');
        const dateRangeEnd = document.getElementById('date_range_end');
        const monthFilter = document.getElementById('month_filter');
        const yearFilter = document.getElementById('year_filter');

        function updateFilterFields() {
            const type = filterType.value;
            dateRange.style.display = type === 'date' ? 'block' : 'none';
            dateRangeEnd.style.display = type === 'date' ? 'block' : 'none';
            monthFilter.style.display = type === 'month' ? 'block' : 'none';
            yearFilter.style.display = type === 'year' ? 'block' : 'none';
        }

        filterType.addEventListener('change', updateFilterFields);
        updateFilterFields();

        // Monthly Chart
        const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
        new Chart(monthlyCtx, {
            type: 'line',
            data: {
                labels: <?= json_encode(array_column($monthly_data, 'month')) ?>,
                datasets: [{
                    label: 'Pendapatan Bulanan',
                    data: <?= json_encode(array_column($monthly_data, 'total')) ?>,
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });

        // Yearly Chart
        const yearlyCtx = document.getElementById('yearlyChart').getContext('2d');
        new Chart(yearlyCtx, {
            type: 'doughnut',
            data: {
                labels: <?= json_encode(array_column($yearly_data, 'year')) ?>,
                datasets: [{
                    data: <?= json_encode(array_column($yearly_data, 'total')) ?>,
                    backgroundColor: [
                        'rgb(255, 99, 132)',
                        'rgb(54, 162, 235)',
                        'rgb(255, 205, 86)',
                        'rgb(75, 192, 192)',
                        'rgb(153, 102, 255)'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    });
</script>
<?= $this->endSection() ?>