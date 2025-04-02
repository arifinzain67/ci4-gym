<?= $this->extend('layouts/main'); ?>

<?= $this->section('content'); ?>
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Data Karyawan</h1>
        <a href="<?= base_url('karyawan/new'); ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-user-plus fa-sm text-white-50"></i> Tambah Karyawan
        </a>
    </div>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success'); ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('error'); ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Karyawan</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>Posisi</th>
                            <th>Jenis Kelamin</th>
                            <th>No. Telepon</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; ?>
                        <?php foreach ($karyawan as $k) : ?>
                            <tr>
                                <td><?= $i++; ?></td>
                                <td><?= $k['kode_karyawan']; ?></td>
                                <td><?= $k['nama']; ?></td>
                                <td><?= $k['posisi']; ?></td>
                                <td><?= $k['jenis_kelamin']; ?></td>
                                <td><?= $k['no_telepon']; ?></td>
                                <td>
                                    <?php if ($k['status'] == 'Aktif') : ?>
                                        <span class="badge badge-success">Aktif</span>
                                    <?php else : ?>
                                        <span class="badge badge-danger">Nonaktif</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?= base_url('karyawan/' . $k['id_karyawan']); ?>" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?= base_url('karyawan/edit/' . $k['id_karyawan']); ?>" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="<?= base_url('karyawan/delete/' . $k['id_karyawan']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                    <?php
                                    // Ambil data session
                                    $session = session();
                                    // Cek apakah user adalah admin atau pemilik
                                    if ($session->get('role') === 'admin' || $session->get('role') === 'pemilik') :
                                    ?>
                                        <?php 
                                        // Periksa apakah karyawan sudah memiliki akun
                                        $userModel = new \App\Models\UserModel();
                                        $userExists = $userModel->where('id_karyawan', $k['id_karyawan'])->first();
                                        
                                        if (!$userExists): ?>
                                            <a href="<?= base_url('karyawan/' . $k['id_karyawan'] . '/createaccount'); ?>" class="btn btn-primary btn-sm mt-1">
                                                <i class="fas fa-user-plus"></i> Buat Akun
                                            </a>
                                        <?php else: ?>
                                            <!-- Tombol aktivasi hanya jika sudah memiliki akun -->
                                            <a href="<?= base_url('karyawan/' . $k['id_karyawan'] . '/togglestatus'); ?>" class="btn <?= ($k['status'] == 'Aktif') ? 'btn-secondary' : 'btn-success' ?> btn-sm mt-1" onclick="return confirm('Apakah Anda yakin ingin <?= ($k['status'] == 'Aktif') ? 'menonaktifkan' : 'mengaktifkan' ?> akun karyawan ini?');">
                                                <i class="fas <?= ($k['status'] == 'Aktif') ? 'fa-user-slash' : 'fa-user-check' ?>"></i> <?= ($k['status'] == 'Aktif') ? 'Nonaktifkan' : 'Aktifkan' ?>
                                            </a>
                                        <?php endif; ?>
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
<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable({
            language: {
                "url": "<?= base_url('assets/js/datatables/i18n/Indonesian.json') ?>"
            }
        });
    });
</script>
<?= $this->endSection(); ?>
