<?= $this->extend('layouts/main'); ?>

<?= $this->section('content'); ?>
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Tambah Karyawan</h1>
        <a href="<?= base_url('karyawan'); ?>" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Tambah Karyawan</h6>
        </div>
        <div class="card-body">
            <?php if (isset($validation)) : ?>
                <div class="alert alert-danger">
                    <?= $validation->listErrors(); ?>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('karyawan/create'); ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field(); ?>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="kode_karyawan">Kode Karyawan</label>
                            <input type="text" class="form-control" id="kode_karyawan" name="kode_karyawan" value="<?= old('kode_karyawan', $kode_karyawan ?? ''); ?>" readonly>
                        </div>
                        
                        <div class="form-group">
                            <label for="nama">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control <?= (isset($validation) && $validation->hasError('nama')) ? 'is-invalid' : ''; ?>" id="nama" name="nama" value="<?= old('nama'); ?>" required>
                            <div class="invalid-feedback">
                                <?= (isset($validation) && $validation->hasError('nama')) ? $validation->getError('nama') : ''; ?>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="jenis_kelamin">Jenis Kelamin <span class="text-danger">*</span></label>
                            <select class="form-control <?= (isset($validation) && $validation->hasError('jenis_kelamin')) ? 'is-invalid' : ''; ?>" id="jenis_kelamin" name="jenis_kelamin" required>
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="Laki-laki" <?= old('jenis_kelamin') == 'Laki-laki' ? 'selected' : ''; ?>>Laki-laki</option>
                                <option value="Perempuan" <?= old('jenis_kelamin') == 'Perempuan' ? 'selected' : ''; ?>>Perempuan</option>
                            </select>
                            <div class="invalid-feedback">
                                <?= (isset($validation) && $validation->hasError('jenis_kelamin')) ? $validation->getError('jenis_kelamin') : ''; ?>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="tanggal_lahir">Tanggal Lahir</label>
                            <input type="date" class="form-control <?= (isset($validation) && $validation->hasError('tanggal_lahir')) ? 'is-invalid' : ''; ?>" id="tanggal_lahir" name="tanggal_lahir" value="<?= old('tanggal_lahir'); ?>">
                            <div class="invalid-feedback">
                                <?= (isset($validation) && $validation->hasError('tanggal_lahir')) ? $validation->getError('tanggal_lahir') : ''; ?>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="alamat">Alamat</label>
                            <textarea class="form-control" id="alamat" name="alamat" rows="3"><?= old('alamat'); ?></textarea>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="no_telepon">No. Telepon</label>
                            <input type="text" class="form-control <?= (isset($validation) && $validation->hasError('no_telepon')) ? 'is-invalid' : ''; ?>" id="no_telepon" name="no_telepon" value="<?= old('no_telepon'); ?>">
                            <div class="invalid-feedback">
                                <?= (isset($validation) && $validation->hasError('no_telepon')) ? $validation->getError('no_telepon') : ''; ?>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control <?= (isset($validation) && $validation->hasError('email')) ? 'is-invalid' : ''; ?>" id="email" name="email" value="<?= old('email'); ?>">
                            <div class="invalid-feedback">
                                <?= (isset($validation) && $validation->hasError('email')) ? $validation->getError('email') : ''; ?>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="posisi">Posisi <span class="text-danger">*</span></label>
                            <input type="text" class="form-control <?= (isset($validation) && $validation->hasError('posisi')) ? 'is-invalid' : ''; ?>" id="posisi" name="posisi" value="<?= old('posisi'); ?>" required>
                            <div class="invalid-feedback">
                                <?= (isset($validation) && $validation->hasError('posisi')) ? $validation->getError('posisi') : ''; ?>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="tanggal_bergabung">Tanggal Bergabung <span class="text-danger">*</span></label>
                            <input type="date" class="form-control <?= (isset($validation) && $validation->hasError('tanggal_bergabung')) ? 'is-invalid' : ''; ?>" id="tanggal_bergabung" name="tanggal_bergabung" value="<?= old('tanggal_bergabung', date('Y-m-d')); ?>" required>
                            <div class="invalid-feedback">
                                <?= (isset($validation) && $validation->hasError('tanggal_bergabung')) ? $validation->getError('tanggal_bergabung') : ''; ?>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="status">Status <span class="text-danger">*</span></label>
                            <p class="form-text text-muted">Status akan otomatis 'Tidak Aktif' hingga akun karyawan dibuat.</p>
                            <input type="text" class="form-control" id="status" name="status" value="Tidak Aktif" readonly>
                        </div>
                        
                        <div class="form-group">
                            <label for="gaji">Gaji</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="number" class="form-control <?= (isset($validation) && $validation->hasError('gaji')) ? 'is-invalid' : ''; ?>" id="gaji" name="gaji" value="<?= old('gaji'); ?>">
                            </div>
                            <div class="invalid-feedback">
                                <?= (isset($validation) && $validation->hasError('gaji')) ? $validation->getError('gaji') : ''; ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="foto">Foto Karyawan</label>
                    <input type="file" class="form-control-file" id="foto" name="foto" accept="image/*">
                    <small class="text-muted">Format: JPEG, PNG, JPG. Maks: 2MB</small>
                </div>
                
                <button type="submit" class="btn btn-primary mt-4">Simpan Data</button>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>
