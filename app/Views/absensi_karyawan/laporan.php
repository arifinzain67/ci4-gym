<?= $this->extend('layouts/main'); ?>

<?= $this->section('content'); ?>
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Laporan Absensi Karyawan</h1>
        <div>
            <button onclick="window.print()" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-print fa-sm text-white-50"></i> Cetak
            </button>
            <a href="<?= base_url('absensi_karyawan'); ?>" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Filter Laporan -->
    <div class="card shadow mb-4 no-print">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Laporan</h6>
        </div>
        <div class="card-body">
            <form method="get" action="<?= base_url('absensi_karyawan/laporan'); ?>" class="form-inline">
                <div class="form-group mr-2 mb-2">
                    <label for="bulan" class="mr-2">Bulan:</label>
                    <select name="bulan" id="bulan" class="form-control">
                        <?php foreach ($nama_bulan as $kode => $nama) : ?>
                            <option value="<?= $kode; ?>" <?= $bulan == $kode ? 'selected' : ''; ?>><?= $nama; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group mr-2 mb-2">
                    <label for="tahun" class="mr-2">Tahun:</label>
                    <select name="tahun" id="tahun" class="form-control">
                        <?php for ($y = date('Y'); $y >= date('Y') - 5; $y--) : ?>
                            <option value="<?= $y; ?>" <?= $tahun == $y ? 'selected' : ''; ?>><?= $y; ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="form-group mr-2 mb-2">
                    <label for="id_karyawan" class="mr-2">Karyawan:</label>
                    <select name="id_karyawan" id="id_karyawan" class="form-control select2">
                        <option value="">Semua Karyawan</option>
                        <?php foreach ($karyawan as $k) : ?>
                            <option value="<?= $k['id_karyawan']; ?>" <?= $id_karyawan == $k['id_karyawan'] ? 'selected' : ''; ?>>
                                <?= $k['kode_karyawan']; ?> - <?= $k['nama']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary mb-2">Tampilkan</button>
            </form>
        </div>
    </div>

    <!-- Laporan Absensi -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                Laporan Absensi Periode: <?= $nama_bulan[$bulan]; ?> <?= $tahun; ?>
                <?php if ($id_karyawan) : 
                    $selected_karyawan = null;
                    foreach ($karyawan as $k) {
                        if ($k['id_karyawan'] == $id_karyawan) {
                            $selected_karyawan = $k;
                            break;
                        }
                    }
                    if ($selected_karyawan) : ?>
                        - <?= $selected_karyawan['nama']; ?>
                    <?php endif; ?>
                <?php endif; ?>
            </h6>
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
                                <th>Kode</th>
                                <th>Nama</th>
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
            dom: 'Bfrtip',
            buttons: [
                'excel', 'pdf'
            ]
        });
        
        $('.select2').select2({
            placeholder: "Pilih Karyawan",
            width: '100%'
        });
    });
</script>
<?= $this->endSection(); ?>
