<?= $this->extend('layouts/main'); ?>

<?= $this->section('content'); ?>
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detail Karyawan</h1>
        <div>
            <a href="<?= base_url('karyawan/edit/' . $karyawan['id_karyawan']); ?>" class="btn btn-warning btn-sm shadow-sm">
                <i class="fas fa-edit fa-sm text-white-50"></i> Edit
            </a>
            <a href="<?= base_url('karyawan'); ?>" class="btn btn-secondary btn-sm shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Foto Karyawan</h6>
                </div>
                <div class="card-body text-center">
                    <?php if ($karyawan['foto']) : ?>
                        <img src="<?= base_url('uploads/karyawan/' . $karyawan['foto']); ?>" alt="Foto Karyawan" class="img-fluid rounded mb-3" style="max-height: 300px;">
                    <?php else : ?>
                        <img src="<?= base_url('assets/img/undraw_profile.svg'); ?>" alt="Default Profile" class="img-fluid rounded mb-3" style="max-height: 300px;">
                    <?php endif; ?>
                    <h5 class="font-weight-bold text-primary"><?= $karyawan['nama']; ?></h5>
                    <p class="mb-0"><?= $karyawan['posisi']; ?></p>
                    <p>
                        <?php if ($karyawan['status'] == 'Aktif') : ?>
                            <span class="badge badge-success">Aktif</span>
                        <?php else : ?>
                            <span class="badge badge-danger">Nonaktif</span>
                        <?php endif; ?>
                    </p>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Karyawan</h6>
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <th style="width: 30%">Kode Karyawan</th>
                            <td><?= $karyawan['kode_karyawan']; ?></td>
                        </tr>
                        <tr>
                            <th>Nama Lengkap</th>
                            <td><?= $karyawan['nama']; ?></td>
                        </tr>
                        <tr>
                            <th>Jenis Kelamin</th>
                            <td><?= $karyawan['jenis_kelamin']; ?></td>
                        </tr>
                        <tr>
                            <th>Tanggal Lahir</th>
                            <td><?= $karyawan['tanggal_lahir'] ? date('d-m-Y', strtotime($karyawan['tanggal_lahir'])) : '-'; ?></td>
                        </tr>
                        <tr>
                            <th>Alamat</th>
                            <td><?= $karyawan['alamat'] ?: '-'; ?></td>
                        </tr>
                        <tr>
                            <th>No. Telepon</th>
                            <td><?= $karyawan['no_telepon'] ?: '-'; ?></td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td><?= $karyawan['email'] ?: '-'; ?></td>
                        </tr>
                        <tr>
                            <th>Posisi</th>
                            <td><?= $karyawan['posisi']; ?></td>
                        </tr>
                        <tr>
                            <th>Tanggal Bergabung</th>
                            <td><?= date('d-m-Y', strtotime($karyawan['tanggal_bergabung'])); ?></td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                <?php if ($karyawan['status'] == 'Aktif') : ?>
                                    <span class="badge badge-success">Aktif</span>
                                <?php else : ?>
                                    <span class="badge badge-danger">Nonaktif</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Gaji</th>
                            <td><?= $karyawan['gaji'] ? 'Rp ' . number_format($karyawan['gaji'], 0, ',', '.') : '-'; ?></td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <!-- Tombol Aksi -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Aksi</h6>
                </div>
                <div class="card-body">
                    <a href="<?= base_url('absensi_karyawan/rekap/' . $karyawan['id_karyawan']); ?>" class="btn btn-info">
                        <i class="fas fa-calendar-check"></i> Rekap Absensi
                    </a>
                    
                    <?php
                    // Ambil data session
                    $session = session();
                    // Cek apakah user adalah admin atau pemilik
                    if ($session->get('role') === 'admin' || $session->get('role') === 'pemilik') :
                        // Cek apakah karyawan sudah memiliki akun
                        $userModel = new \App\Models\UserModel();
                        $hasAccount = $userModel->where('id_karyawan', $karyawan['id_karyawan'])->countAllResults() > 0;
                        
                        if ($hasAccount) :
                    ?>
                            <a href="<?= base_url('karyawan/' . $karyawan['id_karyawan'] . '/editaccount'); ?>" class="btn btn-warning">
                                <i class="fas fa-user-edit"></i> Edit Akun User
                            </a>
                        <?php else : ?>
                            <a href="<?= base_url('karyawan/' . $karyawan['id_karyawan'] . '/createaccount'); ?>" class="btn btn-primary">
                                <i class="fas fa-user-plus"></i> Buat Akun User
                            </a>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>
