<?= $this->extend('layouts/main'); ?>

<?= $this->section('content'); ?>
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Akun User</h1>
        <a href="<?= base_url('karyawan/' . $karyawan['id_karyawan']); ?>" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
        </a>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Data Karyawan</h6>
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <th>Kode Karyawan</th>
                            <td><?= $karyawan['kode_karyawan']; ?></td>
                        </tr>
                        <tr>
                            <th>Nama</th>
                            <td><?= $karyawan['nama']; ?></td>
                        </tr>
                        <tr>
                            <th>Posisi</th>
                            <td><?= $karyawan['posisi']; ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Form Edit Akun User</h6>
                </div>
                <div class="card-body">
                    <?php if (session()->has('errors')) : ?>
                        <div class="alert alert-danger">
                            <ul>
                                <?php foreach (session('errors') as $error) : ?>
                                    <li><?= esc($error) ?></li>
                                <?php endforeach ?>
                            </ul>
                        </div>
                    <?php endif ?>

                    <form action="<?= base_url('karyawan/' . $karyawan['id_karyawan'] . '/updateaccount'); ?>" method="post">
                        <?= csrf_field(); ?>
                        
                        <div class="form-group">
                            <label for="username">Username <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="username" name="username" value="<?= old('username', $user['username']); ?>" required>
                            <small class="text-muted">Username minimal 5 karakter dan harus unik</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="password">Password Baru</label>
                            <input type="password" class="form-control" id="password" name="password">
                            <small class="text-muted">Password minimal 6 karakter. Kosongkan jika tidak ingin mengubah password.</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="confirm_password">Konfirmasi Password Baru</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                        </div>
                        
                        <div class="form-group">
                            <label for="role">Role <span class="text-danger">*</span></label>
                            <select class="form-control" id="role" name="role" required>
                                <option value="">Pilih Role</option>
                                <?php if (session()->get('role') === 'pemilik'): ?>
                                <option value="admin" <?= (old('role', $user['role']) == 'admin') ? 'selected' : ''; ?>>Admin</option>
                                <option value="staff" <?= (old('role', $user['role']) == 'staff') ? 'selected' : ''; ?>>Kasir</option>
                                <option value="pemilik" <?= (old('role', $user['role']) == 'pemilik') ? 'selected' : ''; ?>>Pemilik</option>
                                <?php else: ?>
                                <?php if ((old('role', $user['role']) == 'admin') && $user['role'] == 'admin'): ?>
                                <option value="admin" selected>Admin</option>
                                <?php endif; ?>
                                <option value="staff" <?= (old('role', $user['role']) == 'staff') ? 'selected' : ''; ?>>Kasir</option>
                                <?php endif; ?>
                            </select>
                            <small class="text-muted">
                                <strong>Admin:</strong> Dapat mengakses fitur manajemen member, paket, transaksi, laporan keuangan, dan karyawan<br>
                                <strong>Kasir:</strong> Hanya dapat mengakses dashboard, member, check-in & check-out, dan transaksi<br>
                                <?php if (session()->get('role') === 'pemilik'): ?>
                                <strong>Pemilik:</strong> Memiliki akses penuh ke seluruh fitur termasuk hapus transaksi
                                <?php endif; ?>
                            </small>
                        </div>
                        
                        <div class="d-flex justify-content-between mt-4">
                            <button type="submit" class="btn btn-primary">Update Akun</button>
                            
                            <a href="<?= base_url('karyawan/' . $karyawan['id_karyawan'] . '/deleteaccount'); ?>" class="btn btn-danger" 
                               onclick="return confirm('Apakah Anda yakin ingin menghapus akun user ini? Status karyawan akan diubah menjadi Tidak Aktif.');">
                                <i class="fas fa-trash"></i> Hapus Akun
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>
