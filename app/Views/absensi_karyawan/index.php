<?= $this->extend('layouts/main'); ?>

<?= $this->section('content'); ?>
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Absensi Karyawan</h1>
        <a href="<?= base_url('absensi_karyawan/laporan'); ?>" class="d-none d-sm-inline-block btn btn-sm btn-info shadow-sm">
            <i class="fas fa-file-alt fa-sm text-white-50"></i> Laporan Absensi
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

    <!-- Tanggal -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Pilih Tanggal</h6>
        </div>
        <div class="card-body">
            <form method="get" action="<?= base_url('absensi_karyawan'); ?>" class="form-inline">
                <div class="form-group mr-2">
                    <label for="tanggal" class="mr-2">Tanggal:</label>
                    <input type="date" id="tanggal" name="tanggal" class="form-control" value="<?= $tanggal; ?>">
                </div>
                <button type="submit" class="btn btn-primary">Tampilkan</button>
            </form>
        </div>
    </div>

    <div class="row">
        <!-- Absen Masuk -->
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Absen Masuk</h6>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('absensi_karyawan/clockin'); ?>" method="post">
                        <?= csrf_field(); ?>
                        <input type="hidden" name="tanggal" value="<?= $tanggal; ?>">
                        
                        <div class="form-group">
                            <label for="id_karyawan_masuk">Karyawan</label>
                            <select class="form-control select2" id="id_karyawan_masuk" name="id_karyawan" required>
                                <option value="">Pilih Karyawan</option>
                                <?php foreach ($karyawan as $k) : ?>
                                    <option value="<?= $k['id_karyawan']; ?>"><?= $k['kode_karyawan']; ?> - <?= $k['nama']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="jam_masuk">Jam Masuk</label>
                            <input type="time" class="form-control" id="jam_masuk" name="jam_masuk" value="<?= date('H:i'); ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="keterangan_masuk">Keterangan</label>
                            <textarea class="form-control" id="keterangan_masuk" name="keterangan" rows="2"></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-success btn-block">
                            <i class="fas fa-sign-in-alt"></i> Absen Masuk
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Absen Keluar -->
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Absen Keluar</h6>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('absensi_karyawan/clockout'); ?>" method="post">
                        <?= csrf_field(); ?>
                        <input type="hidden" name="tanggal" value="<?= $tanggal; ?>">
                        
                        <div class="form-group">
                            <label for="id_karyawan_keluar">Karyawan</label>
                            <select class="form-control select2" id="id_karyawan_keluar" name="id_karyawan" required>
                                <option value="">Pilih Karyawan</option>
                                <?php foreach ($karyawan as $k) : ?>
                                    <option value="<?= $k['id_karyawan']; ?>"><?= $k['kode_karyawan']; ?> - <?= $k['nama']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="jam_keluar">Jam Keluar</label>
                            <input type="time" class="form-control" id="jam_keluar" name="jam_keluar" value="<?= date('H:i'); ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="keterangan_keluar">Keterangan</label>
                            <textarea class="form-control" id="keterangan_keluar" name="keterangan" rows="2"></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-danger btn-block">
                            <i class="fas fa-sign-out-alt"></i> Absen Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Absensi Hari Ini -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Data Absensi Tanggal: <?= date('d-m-Y', strtotime($tanggal)); ?></h6>
            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#tambahAbsensiModal">
                <i class="fas fa-plus"></i> Tambah Manual
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>Jam Masuk</th>
                            <th>Jam Keluar</th>
                            <th>Status</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; ?>
                        <?php foreach ($absensi as $a) : ?>
                            <tr>
                                <td><?= $i++; ?></td>
                                <td><?= $a['kode_karyawan']; ?></td>
                                <td><?= $a['nama']; ?></td>
                                <td><?= $a['jam_masuk'] ? date('H:i', strtotime($a['jam_masuk'])) . ' WIB' : '-'; ?></td>
                                <td><?= $a['jam_keluar'] ? date('H:i', strtotime($a['jam_keluar'])) . ' WIB' : '-'; ?></td>
                                <td>
                                    <?php if ($a['status'] == 'Hadir') : ?>
                                        <span class="badge badge-success">Hadir</span>
                                    <?php elseif ($a['status'] == 'Sakit') : ?>
                                        <span class="badge badge-warning">Sakit</span>
                                    <?php elseif ($a['status'] == 'Izin') : ?>
                                        <span class="badge badge-info">Izin</span>
                                    <?php else : ?>
                                        <span class="badge badge-danger">Alpa</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= $a['keterangan'] ?: '-'; ?></td>
                                <td>
                                    <a href="<?= base_url('absensi_karyawan/edit/' . $a['id_absensi']); ?>" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="#" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?') ? window.location.href='<?= base_url('absensi_karyawan/delete/' . $a['id_absensi']); ?>' : false" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($absensi)) : ?>
                            <tr>
                                <td colspan="8" class="text-center">Tidak ada data absensi untuk tanggal ini</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Absensi Manual -->
<div class="modal fade" id="tambahAbsensiModal" tabindex="-1" role="dialog" aria-labelledby="tambahAbsensiModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahAbsensiModalLabel">Tambah Absensi Manual</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url('absensi_karyawan/add'); ?>" method="post">
                <div class="modal-body">
                    <?= csrf_field(); ?>
                    <input type="hidden" name="tanggal" value="<?= $tanggal; ?>">
                    
                    <div class="form-group">
                        <label for="id_karyawan_manual">Karyawan <span class="text-danger">*</span></label>
                        <select class="form-control select2" id="id_karyawan_manual" name="id_karyawan" required>
                            <option value="">Pilih Karyawan</option>
                            <?php foreach ($karyawan as $k) : ?>
                                <option value="<?= $k['id_karyawan']; ?>"><?= $k['kode_karyawan']; ?> - <?= $k['nama']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="status_manual">Status <span class="text-danger">*</span></label>
                        <select class="form-control" id="status_manual" name="status" required>
                            <option value="Hadir">Hadir</option>
                            <option value="Sakit">Sakit</option>
                            <option value="Izin">Izin</option>
                            <option value="Alpa">Alpa</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="jam_masuk_manual">Jam Masuk</label>
                        <input type="time" class="form-control" id="jam_masuk_manual" name="jam_masuk">
                    </div>
                    
                    <div class="form-group">
                        <label for="jam_keluar_manual">Jam Keluar</label>
                        <input type="time" class="form-control" id="jam_keluar_manual" name="jam_keluar">
                    </div>
                    
                    <div class="form-group">
                        <label for="keterangan_manual">Keterangan</label>
                        <textarea class="form-control" id="keterangan_manual" name="keterangan" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
    $(document).ready(function() {
        // Initialize Select2
        $('.select2').select2({
            placeholder: "Pilih Karyawan",
            width: '100%',
            theme: 'bootstrap4',
            allowClear: true
        });
        
        // Initialize DataTable
        $('#dataTable').DataTable({
            language: {
                "url": "<?= base_url('assets/js/datatables/i18n/Indonesian.json') ?>"
            }
        });
        
        // Inisialisasi Select2 di dalam modal
        $('.select2-modal').select2({
            placeholder: "Pilih Karyawan",
            width: '100%',
            dropdownParent: $('#tambahAbsensiModal')
        });
    });
</script>
<?= $this->endSection(); ?>
