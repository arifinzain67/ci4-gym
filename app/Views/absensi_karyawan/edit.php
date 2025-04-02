<?= $this->extend('layouts/main'); ?>

<?= $this->section('content'); ?>
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Absensi Karyawan</h1>
        <a href="<?= base_url('absensi_karyawan'); ?>" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Edit Absensi</h6>
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

            <form action="<?= base_url('absensi_karyawan/' . $absensi['id_absensi']); ?>" method="post">
                <?= csrf_field(); ?>
                <input type="hidden" name="_method" value="PUT">
                
                <div class="form-group">
                    <label for="id_karyawan">Karyawan</label>
                    <select class="form-control select2" id="id_karyawan" name="id_karyawan" disabled>
                        <?php foreach ($karyawan as $k) : ?>
                            <option value="<?= $k['id_karyawan']; ?>" <?= $absensi['id_karyawan'] == $k['id_karyawan'] ? 'selected' : ''; ?>>
                                <?= $k['kode_karyawan']; ?> - <?= $k['nama']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <small class="text-muted">Karyawan tidak dapat diubah</small>
                </div>
                
                <div class="form-group">
                    <label for="tanggal">Tanggal</label>
                    <input type="date" class="form-control" id="tanggal" name="tanggal" value="<?= old('tanggal', $absensi['tanggal']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="status">Status</label>
                    <select class="form-control" id="status" name="status" required>
                        <option value="Hadir" <?= old('status', $absensi['status']) == 'Hadir' ? 'selected' : ''; ?>>Hadir</option>
                        <option value="Sakit" <?= old('status', $absensi['status']) == 'Sakit' ? 'selected' : ''; ?>>Sakit</option>
                        <option value="Izin" <?= old('status', $absensi['status']) == 'Izin' ? 'selected' : ''; ?>>Izin</option>
                        <option value="Alpa" <?= old('status', $absensi['status']) == 'Alpa' ? 'selected' : ''; ?>>Alpa</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="jam_masuk">Jam Masuk</label>
                    <input type="time" class="form-control" id="jam_masuk" name="jam_masuk" value="<?= old('jam_masuk', substr($absensi['jam_masuk'] ?? '', 0, 5)); ?>">
                </div>
                
                <div class="form-group">
                    <label for="jam_keluar">Jam Keluar</label>
                    <input type="time" class="form-control" id="jam_keluar" name="jam_keluar" value="<?= old('jam_keluar', substr($absensi['jam_keluar'] ?? '', 0, 5)); ?>">
                </div>
                
                <div class="form-group">
                    <label for="keterangan">Keterangan</label>
                    <textarea class="form-control" id="keterangan" name="keterangan" rows="3"><?= old('keterangan', $absensi['keterangan']); ?></textarea>
                </div>
                
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
    $(document).ready(function() {
        $('.select2').select2({
            width: '100%'
        });
    });
</script>
<?= $this->endSection(); ?>
