<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Transaksi</h1>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addTransactionModal">
            <i class="fas fa-plus"></i> Tambah Transaksi
        </button>
    </div>

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

    <!-- Tabel Transaksi -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Data Transaksi</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Member</th>
                            <th>Paket</th>
                            <th>Jumlah Bayar</th>
                            <th>Metode Bayar</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1;
                        foreach ($transactions as $transaction): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $transaction['member_name'] ?></td>
                            <td><?= $transaction['package_name'] ?></td>
                            <td>Rp <?= number_format($transaction['amount'], 0, ',', '.') ?></td>
                            <td><?= ucfirst($transaction['payment_type']) ?></td>
                            <td><?php
                                    date_default_timezone_set('Asia/Jakarta');
                                    $timestamp = strtotime($transaction['payment_date']);
                                    $tanggal = date('d', $timestamp);
                                    $bulan = [
                                        1 => 'Januari',
                                        'Februari',
                                        'Maret',
                                        'April',
                                        'Mei',
                                        'Juni',
                                        'Juli',
                                        'Agustus',
                                        'September',
                                        'Oktober',
                                        'November',
                                        'Desember'
                                    ];
                                    $bulanIndex = date('n', $timestamp);
                                    $tahun = date('Y', $timestamp);
                                    $waktu = date('H:i', $timestamp);
                                    echo $tanggal . ' ' . $bulan[$bulanIndex] . ' ' . $tahun . ' ' . $waktu . ' WIB';
                                    ?></td>
                            <td>
                                <?php if ($transaction['status'] == 'pending'): ?>
                                <span class="badge badge-warning">Pending</span>
                                <?php elseif ($transaction['status'] == 'paid'): ?>
                                <span class="badge badge-success">Lunas</span>
                                <?php else: ?>
                                <span class="badge badge-danger">Dibatalkan</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($transaction['status'] == 'pending'): ?>
                                <button class="btn btn-sm btn-success confirm-payment"
                                    data-id="<?= $transaction['id_transaction'] ?>">
                                    Konfirmasi
                                </button>
                                <button class="btn btn-sm btn-danger cancel-transaction"
                                    data-id="<?= $transaction['id_transaction'] ?>">
                                    Batal
                                </button>
                                <?php endif; ?>
                                <?php if (session()->get('role') === 'pemilik'): ?>
                                <button class="btn btn-sm btn-danger delete-transaction"
                                    data-id="<?= $transaction['id_transaction'] ?>"
                                    data-member="<?= $transaction['member_name'] ?>"
                                    data-package="<?= $transaction['package_name'] ?>">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
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

<!-- Modal Tambah Transaksi -->
<div class="modal fade" id="addTransactionModal" tabindex="-1" role="dialog" aria-labelledby="addTransactionModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="addTransactionModalLabel">
                    <i class="fas fa-plus-circle mr-2"></i>Tambah Transaksi
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="transactionForm" action="<?= base_url('transaction/store') ?>" method="post">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Member <span class="text-danger">*</span></label>
                                <select name="id_member" id="id_member" class="form-control select2" required>
                                    <option value="">Pilih Member</option>
                                    <?php foreach ($members as $member) : ?>
                                    <option value="<?= $member['id_member'] ?>">
                                        <?= esc($member['member_code']) ?> - <?= esc($member['name']) ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Paket Membership <span class="text-danger">*</span></label>
                                <select name="id_type" id="id_type" class="form-control select2" required>
                                    <option value="">Pilih Paket</option>
                                    <?php foreach ($membership_types as $type) : ?>
                                    <option value="<?= $type['id_type'] ?>" data-price="<?= $type['price'] ?>" data-duration="<?= $type['duration'] ?>">
                                        <?= esc($type['name']) ?> - Rp <?= number_format($type['price'], 0, ',', '.') ?> (<?= $type['duration'] ?> hari)
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Durasi</label>
                        <div class="input-group">
                            <input type="text" id="duration_info" class="form-control" readonly>
                            <div class="input-group-append">
                                <span class="input-group-text">hari</span>
                            </div>
                        </div>
                        <small class="form-text text-muted">Durasi paket membership yang dipilih</small>
                    </div>

                    <div class="form-group">
                        <label>Total Harga <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Rp</span>
                            </div>
                            <input type="number" step="0.01" name="amount" id="total_price" class="form-control"
                                readonly>
                        </div>
                        <small class="form-text text-muted">Total harga akan otomatis terisi sesuai paket yang
                            dipilih</small>
                    </div>

                    <div class="form-group">
                        <label>Metode Pembayaran <span class="text-danger">*</span></label>
                        <select name="payment_type" id="payment_type" class="form-control" required>
                            <option value="cash">Cash</option>
                            <option value="transfer">Transfer</option>
                            <option value="e-wallet">E-Wallet</option>
                        </select>
                        <small class="form-text text-muted">Pilih metode pembayaran</small>
                    </div>

                    <div class="form-group">
                        <label>Jumlah Bayar <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Rp</span>
                            </div>
                            <input type="number" step="0.01" name="amount_paid" id="amount_paid" class="form-control"
                                required>
                        </div>
                        <small class="form-text text-muted">Masukkan jumlah pembayaran</small>
                    </div>

                    <div class="form-group">
                        <label>Kembalian</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Rp</span>
                            </div>
                            <input type="number" step="0.01" id="change" class="form-control" readonly>
                        </div>
                        <small class="form-text text-muted">Kembalian akan otomatis dihitung</small>
                    </div>

                    <input type="hidden" name="status" value="paid">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi -->
<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmModalLabel">Konfirmasi Transaksi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Silakan periksa data transaksi:</p>
                <table class="table table-bordered">
                    <tr>
                        <th style="width: 30%">Member</th>
                        <td id="confirmMember"></td>
                    </tr>
                    <tr>
                        <th>Paket</th>
                        <td id="confirmPackage"></td>
                    </tr>
                    <tr>
                        <th>Total Harga</th>
                        <td id="confirmAmount"></td>
                    </tr>
                    <tr>
                        <th>Jumlah Bayar</th>
                        <td id="confirmPaid"></td>
                    </tr>
                    <tr>
                        <th>Kembalian</th>
                        <td id="confirmChange"></td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td id="confirmStatus"></td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times"></i> Batal
                </button>
                <button type="button" class="btn btn-primary" id="submitBtn">
                    <i class="fas fa-save"></i> Simpan
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus transaksi ini?</p>
                <table class="table table-bordered">
                    <tr>
                        <th style="width: 30%">Member</th>
                        <td id="deleteMember"></td>
                    </tr>
                    <tr>
                        <th>Paket</th>
                        <td id="deletePackage"></td>
                    </tr>
                    <tr>
                        <th>Tanggal</th>
                        <td id="deleteDate"></td>
                    </tr>
                </table>
                <p class="text-danger"><strong>Perhatian:</strong> Data yang sudah dihapus tidak dapat dikembalikan!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times"></i> Batal
                </button>
                <button type="button" class="btn btn-danger" id="confirmDelete">
                    <i class="fas fa-trash"></i> Hapus
                </button>
            </div>
        </div>
    </div>
</div>

<?= $this->section('styles') ?>
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<!-- DataTables CSS -->
<link href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
<!-- SweetAlert2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
<style>
.select2-container--default .select2-selection--single {
    height: 38px;
    border: 1px solid #ced4da;
    border-radius: 4px;
}

.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 38px;
}

.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 36px;
}

/* Style untuk Select2 di dalam modal */
.modal .select2-container {
    width: 100% !important;
}

.modal .select2-container--default .select2-selection {
    height: calc(1.5em + 0.75rem + 2px);
    padding: 0.375rem 0.75rem;
    font-size: 1rem;
    font-weight: 400;
    line-height: 1.5;
    color: #495057;
    background-color: #fff;
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
}

.modal .select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 1.5;
    padding-left: 0;
}

.modal .select2-container--default .select2-selection--single .select2-selection__arrow {
    height: calc(1.5em + 0.75rem);
}

.modal .select2-dropdown {
    z-index: 1056;
}

.modal .select2-container--default .select2-results__option--highlighted[aria-selected] {
    background-color: #4e73df;
    color: white;
}

.modal .select2-container--default .select2-results__option {
    padding: 0.375rem 0.75rem;
}

.modal .select2-container--default .select2-search__field {
    padding: 0.375rem 0.75rem;
}
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>

<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    // Fungsi helper untuk format tanggal Indonesia
    function formatTanggalIndonesia(date) {
        const bulan = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];
        const tanggal = new Date(date);
        return tanggal.getDate() + ' ' +
            bulan[tanggal.getMonth()] + ' ' +
            tanggal.getFullYear() + ' ' +
            String(tanggal.getHours()).padStart(2, '0') + ':' +
            String(tanggal.getMinutes()).padStart(2, '0') + ' WIB';
    }

    // Inisialisasi Select2 untuk dropdown member
    $('#id_member').select2({
        theme: 'default',
        width: '100%',
        placeholder: 'Pilih Member',
        allowClear: true,
        dropdownParent: $('#addTransactionModal')
    });

    // Inisialisasi Select2 untuk dropdown paket
    $('#id_type').select2({
        theme: 'default',
        width: '100%',
        placeholder: 'Pilih Paket Membership',
        allowClear: true,
        dropdownParent: $('#addTransactionModal')
    });

    // Event handler untuk perubahan paket
    $('#id_type').on('change', function() {
        var selectedOption = $(this).find('option:selected');
        var price = selectedOption.data('price');
        var duration = selectedOption.data('duration');
        var paymentType = $('#payment_type').val();

        if (price) {
            $('#total_price').val(price);
            $('#duration_info').val(duration);

            // Jika metode pembayaran transfer atau e-wallet, set jumlah bayar otomatis
            if (paymentType === 'transfer' || paymentType === 'e-wallet') {
                $('#amount_paid').val(price);
                $('#change').val('0');
                $('#amount_paid').prop('readonly', true);
            } else {
                $('#amount_paid').val('');
                $('#change').val('');
                $('#amount_paid').prop('readonly', false);
            }
        } else {
            $('#total_price').val('');
            $('#duration_info').val('');
            $('#amount_paid').val('');
            $('#change').val('');
            $('#amount_paid').prop('readonly', false);
        }
    });

    // Event handler untuk perubahan metode pembayaran
    $('#payment_type').on('change', function() {
        var paymentType = $(this).val();
        var totalPrice = parseInt($('#total_price').val()) || 0;

        if (paymentType === 'transfer' || paymentType === 'e-wallet') {
            // Jika transfer atau e-wallet, set jumlah bayar sama dengan total harga
            $('#amount_paid').val(totalPrice);
            $('#change').val('0');
            $('#amount_paid').prop('readonly', true);
        } else {
            // Jika cash, reset jumlah bayar dan kembalian
            $('#amount_paid').val('');
            $('#change').val('');
            $('#amount_paid').prop('readonly', false);
        }
    });

    // Event handler untuk input jumlah bayar
    $('#amount_paid').on('input', function() {
        var totalPrice = parseFloat($('#total_price').val()) || 0;
        var amountPaid = parseFloat($(this).val()) || 0;
        var change = amountPaid - totalPrice;

        $('#change').val(change >= 0 ? change : 0);
    });

    // Reset form saat modal ditutup
    $('#addTransactionModal').on('hidden.bs.modal', function() {
        $(this).find('form')[0].reset();
        $('#total_price').val('');
        $('#duration_info').val('');
        $('#change').val('');
        $('#id_type').val('').trigger('change');
        $('#id_member').val('').trigger('change');
    });

    // Handle submit form
    $('#transactionForm').on('submit', function(e) {
        e.preventDefault();
        e.stopPropagation();

        // Validasi form
        if (!$('#id_member').val()) {
            alert('Silakan pilih member!');
            return false;
        }

        if (!$('#id_type').val()) {
            alert('Silakan pilih paket membership!');
            return false;
        }

        var totalPrice = parseInt($('#total_price').val()) || 0;
        var amountPaid = parseInt($('#amount_paid').val()) || 0;

        if (amountPaid < totalPrice) {
            alert('Jumlah bayar tidak boleh kurang dari total harga!');
            return false;
        }

        var selectedMember = $('#id_member option:selected');
        var selectedType = $('#id_type option:selected');

        // Update confirmation modal
        $('#confirmMember').text(selectedMember.text());
        $('#confirmPackage').text(selectedType.text());
        $('#confirmAmount').text('Rp ' + totalPrice.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."));
        $('#confirmPaid').text('Rp ' + amountPaid.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."));
        $('#confirmChange').text('Rp ' + (amountPaid - totalPrice).toString().replace(
            /\B(?=(\d{3})+(?!\d))/g, "."));
        $('#confirmStatus').text('Lunas');

        // Show confirmation modal
        var confirmModal = $('#confirmModal');
        confirmModal.modal('hide');
        setTimeout(function() {
            confirmModal.modal('show');
        }, 100);
        return false;
    });

    // Handle konfirmasi submit
    $('#submitBtn').off('click').on('click', function() {
        // Disable tombol submit
        $(this).prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');

        // Siapkan data untuk dikirim
        var formData = {
            id_member: $('#id_member').val(),
            id_type: $('#id_type').val(),
            amount: $('#total_price').val(),
            amount_paid: $('#amount_paid').val(),
            payment_type: $('#payment_type').val(),
            status: 'paid',
            payment_date: function() {
                // Set timezone ke Asia/Jakarta
                const options = {
                    timeZone: 'Asia/Jakarta',
                    year: 'numeric',
                    month: '2-digit',
                    day: '2-digit',
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                    hour12: false
                };

                // Dapatkan waktu saat ini dalam timezone Asia/Jakarta
                const now = new Date();
                const jakartaDate = new Intl.DateTimeFormat('en-US', options).format(now);

                // Parse komponen waktu
                const [date, time] = jakartaDate.split(', ');
                const [month, day, year] = date.split('/');

                // Format sesuai MySQL datetime (YYYY-MM-DD HH:mm:ss)
                return `${year}-${month}-${day} ${time}:00`;
            }(),
            <?= csrf_token() ?>: '<?= csrf_hash() ?>'
        };

        // Kirim data ke server
        $.ajax({
            url: '<?= base_url('transaction/store') ?>',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Transaksi berhasil disimpan',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        // Buka struk di tab baru
                        window.open('<?= base_url('transaction/receipt') ?>/' +
                            response.id_transaction, '_blank');
                        // Reload halaman utama
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: response.message
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: 'Terjadi kesalahan saat menyimpan transaksi'
                });
            },
            complete: function() {
                // Enable tombol submit
                $('#submitBtn').prop('disabled', false).html('Simpan');
            }
        });
    });

    // Handle konfirmasi pembayaran
    $('.confirm-payment').on('click', function() {
        if (confirm('Apakah Anda yakin ingin mengkonfirmasi pembayaran ini?')) {
            var idTransaction = $(this).data('id');
            $.ajax({
                url: '<?= base_url('transaction/confirm-payment') ?>',
                type: 'POST',
                data: {
                    id_transaction: idTransaction,
                    <?= csrf_token() ?>: '<?= csrf_hash() ?>'
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        alert(response.message);
                        location.reload();
                    } else {
                        alert(response.message);
                    }
                },
                error: function() {
                    alert('Terjadi kesalahan saat mengkonfirmasi pembayaran');
                }
            });
        }
    });

    // Handle batalkan transaksi
    $('.cancel-transaction').on('click', function() {
        if (confirm('Apakah Anda yakin ingin membatalkan transaksi ini?')) {
            var idTransaction = $(this).data('id');
            $.ajax({
                url: '<?= base_url('transaction/cancel') ?>',
                type: 'POST',
                data: {
                    id_transaction: idTransaction,
                    <?= csrf_token() ?>: '<?= csrf_hash() ?>'
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        alert(response.message);
                        location.reload();
                    } else {
                        alert(response.message);
                    }
                },
                error: function() {
                    alert('Terjadi kesalahan saat membatalkan transaksi');
                }
            });
        }
    });

    // Variabel untuk menyimpan ID transaksi yang akan dihapus
    var transactionToDelete = null;

    // Handle klik tombol hapus
    $('.delete-transaction').on('click', function() {
        var idTransaction = $(this).data('id');
        var memberName = $(this).data('member');
        var packageName = $(this).data('package');
        var date = $(this).closest('tr').find('td:eq(5)').text(); // Mengambil tanggal dari kolom ke-6

        // Simpan ID transaksi yang akan dihapus
        transactionToDelete = idTransaction;

        // Update modal konfirmasi hapus
        $('#deleteMember').text(memberName);
        $('#deletePackage').text(packageName);
        $('#deleteDate').text(date);

        // Tampilkan modal konfirmasi
        $('#deleteModal').modal('show');
    });

    // Handle konfirmasi hapus
    $('#confirmDelete').on('click', function() {
        if (transactionToDelete) {
            $.ajax({
                url: '<?= base_url('transaction/delete') ?>/' + transactionToDelete,
                type: 'POST',
                data: {
                    <?= csrf_token() ?>: '<?= csrf_hash() ?>'
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Transaksi berhasil dihapus',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: response.message
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: 'Terjadi kesalahan saat menghapus transaksi'
                    });
                }
            });
        }
        $('#deleteModal').modal('hide');
    });

    // Reset modal saat ditutup
    $('#deleteModal').on('hidden.bs.modal', function() {
        transactionToDelete = null;
        $('#confirmDelete').prop('disabled', false)
            .html('<i class="fas fa-trash"></i> Hapus');
    });

    // Initialize DataTable
    $('#dataTable').DataTable({
        "language": {
            "decimal": ",",
            "thousands": ".",
            "search": "Cari:",
            "lengthMenu": "Tampilkan _MENU_ data per halaman",
            "zeroRecords": "Tidak ada data yang ditemukan",
            "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
            "infoEmpty": "Tidak ada data yang tersedia",
            "infoFiltered": "(difilter dari _MAX_ total data)",
            "paginate": {
                "first": "Pertama",
                "last": "Terakhir",
                "next": "Selanjutnya",
                "previous": "Sebelumnya"
            },
            "datetime": {
                "previous": "Sebelumnya",
                "next": "Selanjutnya",
                "hours": "Jam",
                "minutes": "Menit",
                "seconds": "Detik",
                "unknown": "-",
                "amPm": ["Pagi", "Sore"],
                "weekdays": ["Min", "Sen", "Sel", "Rab", "Kam", "Jum", "Sab"],
                "months": ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus",
                    "September", "Oktober", "November", "Desember"
                ]
            }
        },
        "columnDefs": [{
            "targets": 5, // kolom tanggal
            "render": function(data, type, row) {
                if (type === 'display') {
                    return formatTanggalIndonesia(data.replace(' WIB', ''));
                }
                return data;
            }
        }],
        "pageLength": 10,
        "order": [
            [0, 'asc']
        ],
        "responsive": true,
        "processing": true
    });
});
</script>
<?= $this->endSection() ?>

<?= $this->endSection() ?>