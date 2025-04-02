<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <a href="<?= base_url('member') ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
    <div class="row">
        <!-- Profil Member -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Profil Member</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <?php if ($member['photo']): ?>
                            <img src="<?= base_url('uploads/members/' . $member['photo']) ?>" alt="Foto <?= $member['name'] ?>" class="rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                        <?php else: ?>
                            <img src="<?= base_url('img/default-avatar.png') ?>" alt="Default Avatar" class="rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                        <?php endif; ?>
                        <h4><?= $member['name'] ?></h4>
                        <p class="text-muted"><?= $member['member_code'] ?></p>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <tr>
                                <th>Email</th>
                                <td><?= $member['email'] ?? '-' ?></td>
                            </tr>
                            <tr>
                                <th>No. Telepon</th>
                                <td><?= $member['phone'] ?? '-' ?></td>
                            </tr>
                            <tr>
                                <th>Jenis Kelamin</th>
                                <td><?= $member['gender'] == 'L' ? 'Laki-laki' : 'Perempuan' ?></td>
                            </tr>
                            <tr>
                                <th>Alamat</th>
                                <td><?= $member['address'] ?? '-' ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Status Keanggotaan -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title">Status Keanggotaan</h5>
                </div>
                <div class="card-body">
                    <?php if ($active_transaction): ?>
                        <div class="alert alert-success">
                            <h6 class="alert-heading">Member Aktif</h6>
                            <p class="mb-0">Paket: <?= $active_transaction['package_name'] ?></p>
                            <p class="mb-0">Berakhir: <?= date('d/m/Y H:i', strtotime($active_transaction['expired_at'])) ?></p>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-danger">
                            <h6 class="alert-heading">Member Tidak Aktif</h6>
                            <p class="mb-0">Tidak ada paket membership yang aktif</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Riwayat Transaksi -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Riwayat Transaksi</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Paket</th>
                                    <th>Jumlah</th>
                                    <th>Status</th>
                                    <th>Berakhir</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($transactions)): ?>
                                    <?php foreach ($transactions as $transaction): ?>
                                        <tr>
                                            <td><?= date('d/m/Y', strtotime($transaction['payment_date'])) ?></td>
                                            <td><?= $transaction['package_name'] ?></td>
                                            <td>Rp <?= number_format($transaction['amount'], 0, ',', '.') ?></td>
                                            <td>
                                                <?php if ($transaction['status'] == 'paid'): ?>
                                                    <span class="badge badge-success">Lunas</span>
                                                <?php elseif ($transaction['status'] == 'pending'): ?>
                                                    <span class="badge badge-warning">Pending</span>
                                                <?php else: ?>
                                                    <span class="badge badge-danger">Dibatalkan</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= date('d/m/Y', strtotime($transaction['expired_at'])) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center">Tidak ada riwayat transaksi</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Riwayat Check In/Out -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title">Riwayat Check In/Out</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Check In</th>
                                    <th>Check Out</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($check_in_history)): ?>
                                    <?php foreach ($check_in_history as $history): ?>
                                        <tr>
                                            <td><?= date('d/m/Y', strtotime($history['check_in'])) ?></td>
                                            <td><?= date('H:i', strtotime($history['check_in'])) ?></td>
                                            <td><?= $history['check_out'] ? date('H:i', strtotime($history['check_out'])) : '-' ?></td>
                                            <td>
                                                <span class="badge badge-<?= $history['status'] == 'active' ? 'success' : 'secondary' ?>">
                                                    <?= ucfirst($history['status']) ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center">Tidak ada riwayat check in/out</td>
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
<?= $this->endSection() ?>