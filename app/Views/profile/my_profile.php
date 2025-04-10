<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">My Profile</h1>
    </div>

    <!-- Display Messages -->
    <?php if (session()->has('success')) : ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session('success') ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <?php if (session()->has('error')) : ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session('error') ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <div class="row">
        <!-- Kolom Profil -->
        <div class="col-xl-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Profil</h6>
                    <a href="<?= base_url('profile/edit') ?>" class="btn btn-sm btn-primary">
                        <i class="fas fa-edit fa-sm"></i> Edit Profil
                    </a>
                </div>
                <div class="card-body text-center">
                    <?php
                    $profileImage = 'img/undraw_profile.svg'; // Default image
                    if ($karyawan && !empty($karyawan['foto'])) {
                        $profileImage = 'uploads/karyawan/' . $karyawan['foto'];
                    }
                    ?>
                    <img src="<?= base_url($profileImage) ?>" class="img-thumbnail rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                    <h4 class="font-weight-bold"><?= $user['name'] ?></h4>
                    <p class="text-muted"><?= $user['role'] ?></p>
                    
                    <?php if ($karyawan) : ?>
                        <div class="text-left mt-4">
                            <div class="table-responsive">
                                <table class="table table-borderless">
                                    <tbody>
                                        <tr>
                                            <td width="40%"><strong>Kode Karyawan</strong></td>
                                            <td><?= $karyawan['kode_karyawan'] ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Jenis Kelamin</strong></td>
                                            <td><?= $karyawan['jenis_kelamin'] ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Tanggal Lahir</strong></td>
                                            <td><?= $karyawan['tanggal_lahir'] ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Posisi</strong></td>
                                            <td><?= $karyawan['posisi'] ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Tgl Bergabung</strong></td>
                                            <td><?= $karyawan['tanggal_bergabung'] ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Status</strong></td>
                                            <td>
                                                <span class="badge badge-<?= ($karyawan['status'] == 'Aktif') ? 'success' : 'danger' ?>">
                                                    <?= $karyawan['status'] ?>
                                                </span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Kolom Akun & Kontak -->
        <div class="col-xl-8">
            <!-- Informasi Akun -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Akun</h6>
                    <a href="<?= base_url('account/edit') ?>" class="btn btn-sm btn-primary">
                        <i class="fas fa-key fa-sm"></i> Edit Akun
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <td width="30%"><strong>Username</strong></td>
                                    <td><?= $user['username'] ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Role</strong></td>
                                    <td><?= ucfirst($user['role']) ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Terdaftar Pada</strong></td>
                                    <td><?= date('d M Y H:i', strtotime($user['created_at'])) ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Update Terakhir</strong></td>
                                    <td><?= date('d M Y H:i', strtotime($user['updated_at'])) ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Informasi Kontak -->
            <?php if ($karyawan) : ?>
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Kontak</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <td width="30%"><strong>Email</strong></td>
                                    <td><?= $karyawan['email'] ?: '-' ?></td>
                                </tr>
                                <tr>
                                    <td><strong>No. Telepon</strong></td>
                                    <td><?= $karyawan['no_telepon'] ?: '-' ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Alamat</strong></td>
                                    <td><?= $karyawan['alamat'] ?: '-' ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
