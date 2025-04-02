<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Check In/Out Member</h3>
                </div>
                <div class="card-body">
                    <!-- Form Check In -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Check In Member</h5>
                                </div>
                                <div class="card-body">
                                    <form id="checkInForm">
                                        <div class="form-group">
                                            <label>Pilih Member</label>
                                            <select name="id_member" class="form-control" required>
                                                <option value="">Pilih Member</option>
                                                <?php foreach ($members as $member): ?>
                                                <option value="<?= $member['id_member'] ?>">
                                                    <?= $member['member_code'] ?> - <?= $member['name'] ?>
                                                </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Check In</button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Daftar Member yang Sedang Check In -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Member yang Sedang Check In</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Kode</th>
                                                    <th>Nama</th>
                                                    <th>Check In</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (!empty($active_members)): ?>
                                                <?php foreach ($active_members as $member): ?>
                                                <tr>
                                                    <td><?= $member['member_code'] ?></td>
                                                    <td><?= $member['member_name'] ?></td>
                                                    <td><?= date('d/m/Y H:i', strtotime($member['check_in'] . ' +7 hours')) ?>
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-danger btn-sm check-out"
                                                            data-id="<?= $member['id_member'] ?>">
                                                            Check Out
                                                        </button>
                                                    </td>
                                                </tr>
                                                <?php endforeach; ?>
                                                <?php else: ?>
                                                <tr>
                                                    <td colspan="4" class="text-center">Tidak ada member yang sedang
                                                        check in</td>
                                                </tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Riwayat Check In/Out -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Riwayat Check In/Out</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Kode</th>
                                            <th>Nama</th>
                                            <th>Check In</th>
                                            <th>Check Out</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($check_in_history)): ?>
                                        <?php foreach ($check_in_history as $history): ?>
                                        <tr>
                                            <td><?= $history['member_code'] ?></td>
                                            <td><?= $history['member_name'] ?></td>
                                            <td><?= date('d/m/Y H:i', strtotime($history['check_in'] . ' +7 hours')) ?>
                                            </td>
                                            <td>
                                                <?= $history['check_out'] ? date('d/m/Y H:i', strtotime($history['check_out'] . ' +7 hours')) : '-' ?>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge badge-<?= $history['status'] == 'active' ? 'success' : 'secondary' ?>">
                                                    <?= ucfirst($history['status']) ?>
                                                </span>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                        <?php else: ?>
                                        <tr>
                                            <td colspan="5" class="text-center">Tidak ada riwayat check in/out</td>
                                        </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Check In -->
<div class="modal fade" id="checkInModal" tabindex="-1" role="dialog" aria-labelledby="checkInModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="checkInModalLabel">Konfirmasi Check In</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin melakukan check in untuk member:</p>
                <p class="font-weight-bold" id="checkInMemberName"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="confirmCheckIn">Ya, Check In</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Check Out -->
<div class="modal fade" id="checkOutModal" tabindex="-1" role="dialog" aria-labelledby="checkOutModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="checkOutModalLabel">Konfirmasi Check Out</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin melakukan check out untuk member:</p>
                <p class="font-weight-bold" id="checkOutMemberInfo"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="confirmCheckOut">Ya, Check Out</button>
            </div>
        </div>
    </div>
</div>

<!-- Tambahkan SweetAlert2 CSS dan JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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
</style>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    // Inisialisasi Select2
    $('select[name="id_member"]').select2({
        placeholder: 'Pilih Member',
        allowClear: true,
        width: '100%',
        language: {
            noResults: function() {
                return "Tidak ada data yang ditemukan";
            },
            searching: function() {
                return "Mencari...";
            },
            inputTooLong: function(args) {
                return "Terlalu banyak karakter";
            }
        }
    });

    let selectedMemberId = null;
    let selectedMemberName = null;
    let selectedMemberCode = null;

    // Handle Check In
    $('#checkInForm').on('submit', function(e) {
        e.preventDefault();
        const memberSelect = $(this).find('select[name="id_member"]');
        const selectedOption = memberSelect.find('option:selected');

        if (selectedOption.val()) {
            selectedMemberId = selectedOption.val();
            selectedMemberName = selectedOption.text();
            $('#checkInMemberName').text(selectedMemberName);
            $('#checkInModal').modal('show');
        }
    });

    $('#confirmCheckIn').on('click', function() {
        const formData = new FormData();
        formData.append('id_member', selectedMemberId);

        $.ajax({
            url: '<?= base_url('check-in-out/check-in') ?>',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                '<?= csrf_header() ?>': '<?= csrf_hash() ?>'
            },
            success: function(response) {
                console.log('Response check-in:', response);
                $('#checkInModal').modal('hide');
                if (response.success) {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: response.message,
                        icon: 'success',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#3085d6'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.reload();
                        }
                    });
                } else {
                    Swal.fire({
                        title: 'Gagal!',
                        text: response.message,
                        icon: 'error',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#d33'
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('Error saat check-in:', error);
                $('#checkInModal').modal('hide');
                Swal.fire({
                    title: 'Error!',
                    text: 'Terjadi kesalahan saat melakukan check in',
                    icon: 'error',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#d33'
                });
            }
        });
    });

    // Handle Check Out
    $(document).on('click', '.check-out', function() {
        const row = $(this).closest('tr');
        selectedMemberId = $(this).data('id');
        selectedMemberCode = row.find('td:eq(0)').text();
        selectedMemberName = row.find('td:eq(1)').text();
        $('#checkOutMemberInfo').text(`${selectedMemberCode} - ${selectedMemberName}`);
        $('#checkOutModal').modal('show');
    });

    $('#confirmCheckOut').on('click', function() {
        const formData = new FormData();
        formData.append('id_member', selectedMemberId);

        $.ajax({
            url: '<?= base_url('check-in-out/check-out') ?>',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                '<?= csrf_header() ?>': '<?= csrf_hash() ?>'
            },
            success: function(response) {
                console.log('Response check-out:', response);
                $('#checkOutModal').modal('hide');
                if (response.success) {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: response.message,
                        icon: 'success',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#3085d6'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.reload();
                        }
                    });
                } else {
                    Swal.fire({
                        title: 'Gagal!',
                        text: response.message,
                        icon: 'error',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#d33'
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('Error saat check-out:', error);
                $('#checkOutModal').modal('hide');
                Swal.fire({
                    title: 'Error!',
                    text: 'Terjadi kesalahan saat melakukan check out',
                    icon: 'error',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#d33'
                });
            }
        });
    });
});
</script>
<?= $this->endSection() ?>