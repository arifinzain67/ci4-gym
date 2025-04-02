<?= $this->extend('layouts/main'); ?>

<?= $this->section('content'); ?>
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Karyawan</h1>
        <a href="<?= base_url('karyawan'); ?>" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Edit Karyawan</h6>
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

            <form action="<?= base_url('karyawan/update/' . $karyawan['id_karyawan']); ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field(); ?>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="kode_karyawan">Kode Karyawan</label>
                            <input type="text" class="form-control" id="kode_karyawan" name="kode_karyawan" value="<?= old('kode_karyawan', $karyawan['kode_karyawan']); ?>" readonly>
                        </div>
                        
                        <div class="form-group">
                            <label for="nama">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nama" name="nama" value="<?= old('nama', $karyawan['nama']); ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="jenis_kelamin">Jenis Kelamin <span class="text-danger">*</span></label>
                            <select class="form-control" id="jenis_kelamin" name="jenis_kelamin" required>
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="Laki-laki" <?= (old('jenis_kelamin', $karyawan['jenis_kelamin']) == 'Laki-laki') ? 'selected' : ''; ?>>Laki-laki</option>
                                <option value="Perempuan" <?= (old('jenis_kelamin', $karyawan['jenis_kelamin']) == 'Perempuan') ? 'selected' : ''; ?>>Perempuan</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="tanggal_lahir">Tanggal Lahir</label>
                            <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" value="<?= old('tanggal_lahir', $karyawan['tanggal_lahir']); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="alamat">Alamat</label>
                            <textarea class="form-control" id="alamat" name="alamat" rows="3"><?= old('alamat', $karyawan['alamat']); ?></textarea>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="no_telepon">No. Telepon</label>
                            <input type="text" class="form-control" id="no_telepon" name="no_telepon" value="<?= old('no_telepon', $karyawan['no_telepon']); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?= old('email', $karyawan['email']); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="posisi">Posisi <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="posisi" name="posisi" value="<?= old('posisi', $karyawan['posisi']); ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="tanggal_bergabung">Tanggal Bergabung <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="tanggal_bergabung" name="tanggal_bergabung" value="<?= old('tanggal_bergabung', $karyawan['tanggal_bergabung']); ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="status">Status <span class="text-danger">*</span></label>
                            <?php $userExists = model('UserModel')->where('id_karyawan', $karyawan['id_karyawan'])->first(); ?>
                            
                            <?php if (!$userExists): ?>
                                <p class="form-text text-muted">Status akan otomatis 'Tidak Aktif' hingga akun karyawan dibuat.</p>
                                <input type="text" class="form-control" value="Tidak Aktif" readonly>
                                <input type="hidden" name="status" value="Tidak Aktif">
                            <?php else: ?>
                                <div class="input-group">
                                    <input type="text" class="form-control" value="<?= $karyawan['status'] ?>" readonly>
                                    <div class="input-group-append">
                                        <a href="<?= base_url('karyawan/' . $karyawan['id_karyawan'] . '/togglestatus'); ?>" class="btn <?= ($karyawan['status'] == 'Aktif') ? 'btn-secondary' : 'btn-success' ?>" onclick="return confirm('Apakah Anda yakin ingin <?= ($karyawan['status'] == 'Aktif') ? 'menonaktifkan' : 'mengaktifkan' ?> akun karyawan ini?');">
                                            <i class="fas <?= ($karyawan['status'] == 'Aktif') ? 'fa-user-slash' : 'fa-user-check' ?>"></i> <?= ($karyawan['status'] == 'Aktif') ? 'Nonaktifkan' : 'Aktifkan' ?>
                                        </a>
                                    </div>
                                </div>
                                <input type="hidden" name="status" value="<?= $karyawan['status'] ?>">
                                <small class="form-text text-muted">Status hanya dapat diubah melalui tombol aktivasi di sebelah kanan.</small>
                            <?php endif; ?>
                        </div>
                        
                        <div class="form-group">
                            <label for="gaji">Gaji</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="number" class="form-control" id="gaji" name="gaji" value="<?= old('gaji', $karyawan['gaji']); ?>">
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="foto">Foto Karyawan</label>
                    <?php if ($karyawan['foto']) : ?>
                        <div class="mb-2">
                            <img src="<?= base_url('uploads/karyawan/' . $karyawan['foto']); ?>" alt="Foto Karyawan" class="img-thumbnail" style="max-height: 150px;">
                        </div>
                    <?php endif; ?>
                    <input type="file" class="form-control-file" id="foto" name="foto" accept="image/*">
                    <small class="text-muted">Format: JPEG, PNG, JPG. Maks: 2MB. Biarkan kosong jika tidak ingin mengubah foto.</small>
                </div>
                
                <button type="submit" class="btn btn-primary mt-4">Simpan Perubahan</button>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>
