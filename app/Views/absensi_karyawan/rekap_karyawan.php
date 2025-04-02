<?= $this->extend('layouts/main'); ?>

<?= $this->section('content'); ?>
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Rekap Absensi Karyawan</h1>
        <div>
            <button onclick="window.print()" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-print fa-sm text-white-50"></i> Cetak
            </button>
            <a href="<?= base_url('karyawan/' . $karyawan['id_karyawan']); ?>" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Data Karyawan -->
    <div class="row">
        <div class="col-md-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Data Karyawan</h6>
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <th style="width: 40%">Kode Karyawan</th>
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
                    </table>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <!-- Filter Periode -->
            <div class="card shadow mb-4 no-print">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Filter Periode</h6>
                </div>
                <div class="card-body">
                    <form method="get" action="<?= base_url('absensi_karyawan/rekap/' . $karyawan['id_karyawan']); ?>" class="form-inline">
                        <div class="form-group mr-2">
                            <label for="bulan" class="mr-2">Bulan:</label>
                            <select name="bulan" id="bulan" class="form-control">
                                <?php foreach ($nama_bulan as $kode => $nama) : ?>
                                    <option value="<?= $kode; ?>" <?= $bulan == $kode ? 'selected' : ''; ?>><?= $nama; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group mr-2">
                            <label for="tahun" class="mr-2">Tahun:</label>
                            <select name="tahun" id="tahun" class="form-control">
                                <?php for ($y = date('Y'); $y >= date('Y') - 5; $y--) : ?>
                                    <option value="<?= $y; ?>" <?= $tahun == $y ? 'selected' : ''; ?>><?= $y; ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Tampilkan</button>
                    </form>
                </div>
            </div>
            
            <!-- Statistik Absensi -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Statistik Absensi - <?= $nama_bulan[$bulan]; ?> <?= $tahun; ?></h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Hari</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_hari; ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Hadir</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_hadir; ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Sakit</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_sakit; ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-medkit fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Izin</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_izin; ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <div class="card border-left-danger shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Alpa</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_alpa; ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Persentase Kehadiran -->
                        <div class="col-md-9 mb-3">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Persentase Kehadiran</div>
                                            <div class="row no-gutters align-items-center">
                                                <div class="col-auto">
                                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                                        <?= number_format(($total_hadir / $total_hari) * 100, 1); ?>%
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="progress progress-sm mr-2">
                                                        <div class="progress-bar bg-primary" role="progressbar" style="width: <?= ($total_hadir / $total_hari) * 100; ?>%" aria-valuenow="<?= ($total_hadir / $total_hari) * 100; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-clipboard-check fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Absensi -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Detail Absensi - <?= $nama_bulan[$bulan]; ?> <?= $tahun; ?></h6>
        </div>
        <div class="card-body">
            <?php if (empty($absensi)) : ?>
                <div class="alert alert-info">
                    Tidak ada data absensi untuk periode yang dipilih.
                </div>
            <?php else : ?>
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Jam Masuk</th>
                                <th>Jam Keluar</th>
                                <th>Status</th>
                                <th>Keterangan</th>
                                <th class="no-print">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            <?php foreach ($absensi as $a) : ?>
                                <tr>
                                    <td><?= $i++; ?></td>
                                    <td><?= date('d-m-Y', strtotime($a['tanggal'])); ?></td>
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
                                    <td class="no-print">
                                        <a href="<?= base_url('absensi_karyawan/' . $a['id_absensi'] . '/edit'); ?>" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="<?= base_url('absensi_karyawan/' . $a['id_absensi']); ?>" method="post" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                            <?= csrf_field(); ?>
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
    @media print {
        .no-print { display: none !important; }
        .main-content { width: 100%; margin: 0; padding: 0; }
        body { font-size: 12pt; }
        table { width: 100%; }
    }
</style>
<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable({
            language: {
                "url": "<?= base_url('assets/js/datatables/i18n/Indonesian.json') ?>"
            },
            "ordering": false,
            "paging": true,
            "info": true,
            "searching": true
        });
    });
</script>
<?= $this->endSection(); ?>
