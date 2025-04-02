<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Daftar Member</h1>
        <a href="<?= base_url('member/create') ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Member
        </a>
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

    <!-- Data Member Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-0">
                        <select id="genderFilter" class="form-control">
                            <option value="">Semua Jenis Kelamin</option>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th class="text-center align-middle" style="width: 5%; padding: 12px;">NO</th>
                            <th class="text-center align-middle" style="width: 10%; padding: 12px;">Foto</th>
                            <th class="align-middle" style="width: 15%; padding: 12px;">Kode Member</th>
                            <th class="align-middle" style="width: 20%; padding: 12px;">Nama</th>
                            <th class="align-middle" style="width: 15%; padding: 12px;">Kontak</th>
                            <th class="text-center align-middle" style="width: 10%; padding: 12px;">Jenis Kelamin</th>
                            <th class="text-center align-middle" style="width: 15%; padding: 12px;">Status</th>
                            <th class="text-center align-middle" style="width: 15%; padding: 12px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($members) && is_array($members)) : ?>
                            <?php foreach ($members as $key => $member) : ?>
                                <tr>
                                    <td class="text-center align-middle"><?= $key + 1 ?></td>
                                    <td class="text-center align-middle">
                                        <?php if ($member['photo']) : ?>
                                            <img src="<?= base_url('uploads/members/' . $member['photo']) ?>" alt="Foto Member"
                                                class="rounded-circle" style="width: 50px; height: 50px; object-fit: cover;">
                                        <?php else : ?>
                                            <img src="<?= base_url('img/default-avatar.png') ?>" alt="Default Avatar"
                                                class="rounded-circle" style="width: 50px; height: 50px; object-fit: cover;">
                                        <?php endif; ?>
                                    </td>
                                    <td class="align-middle"><?= esc($member['member_code']) ?></td>
                                    <td class="align-middle"><?= esc($member['name']) ?></td>
                                    <td class="align-middle">
                                        <div>📱 <?= esc($member['phone']) ?></div>
                                        <?php if ($member['email']) : ?>
                                            <div>📧 <?= esc($member['email']) ?></div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center align-middle">
                                        <?php if ($member['gender'] == 'L') : ?>
                                            <span class="badge badge-info">Laki-laki</span>
                                        <?php else : ?>
                                            <span class="badge" style="background-color: #ff69b4; color: white;">Perempuan</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center align-middle">
                                        <?php
                                        $status = $memberStatuses[$member['id_member']];
                                        if ($status) {
                                            echo '<span class="badge badge-success">Aktif</span>';
                                            echo '<br><small>Expired: ' . date('d/m/Y', strtotime($status['expired_at'])) . '</small>';
                                        } else {
                                            echo '<span class="badge badge-danger">Tidak Aktif</span>';
                                        }
                                        ?>
                                    </td>
                                    <td class="text-center align-middle">
                                        <div class="btn-group">
                                            <a href="<?= base_url('member/edit/' . $member['id_member']) ?>"
                                                class="btn btn-warning btn-sm" data-toggle="tooltip" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                                data-target="#deleteModal<?= $member['id_member'] ?>" data-toggle="tooltip"
                                                title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            <a href="<?= base_url('member/detail/' . $member['id_member']) ?>" class="btn btn-info btn-sm" data-toggle="tooltip" title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Modal Konfirmasi Hapus -->
                                <div class="modal fade" id="deleteModal<?= $member['id_member'] ?>" tabindex="-1" role="dialog"
                                    aria-labelledby="deleteModalLabel<?= $member['id_member'] ?>" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title" id="deleteModalLabel<?= $member['id_member'] ?>">
                                                    <i class="fas fa-exclamation-triangle mr-2"></i>Konfirmasi Hapus
                                                </h5>
                                                <button type="button" class="close text-white" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Apakah Anda yakin ingin menghapus member ini?</p>
                                                <div class="alert alert-warning">
                                                    <i class="fas fa-info-circle mr-2"></i>
                                                    Data yang dihapus tidak dapat dikembalikan!
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                    <i class="fas fa-times mr-1"></i> Batal
                                                </button>
                                                <a href="<?= base_url('member/delete/' . $member['id_member']) ?>"
                                                    class="btn btn-danger">
                                                    <i class="fas fa-trash mr-1"></i> Hapus
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada data member</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        // Initialize DataTable
        var table = $('#dataTable').DataTable({
            language: {
                "url": "<?= base_url('assets/js/datatables/i18n/Indonesian.json') ?>"
            },
            "pageLength": 10,
            "order": [
                [0, 'asc']
            ],
            "responsive": true,
            "searching": true,
            "info": true,
            "paging": true,
            "dom": '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                '<"row"<"col-sm-12"tr>>' +
                '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>'
        });

        // Filter jenis kelamin
        $('#genderFilter').on('change', function() {
            var gender = $(this).val().toLowerCase();
            table.column(5).search(gender).draw();
        });

        // Initialize tooltips
        $('[data-toggle="tooltip"]').tooltip();

        // Handle konfirmasi submit
        $('#submitBtn').off('click').on('click', function() {
            // Disable tombol submit
            $(this).prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');

            // Siapkan data untuk dikirim
            var formData = {
                name: $('#name').val(),
                phone: $('#phone').val(),
                address: $('#address').val(),
                birth_date: $('#birth_date').val(),
                gender: $('#gender').val(),
                <?= csrf_token() ?>: '<?= csrf_hash() ?>'
            };

            // Kirim data ke server
            $.ajax({
                url: '<?= base_url('member/store') ?>',
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Member berhasil ditambahkan',
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
                        text: 'Terjadi kesalahan saat menyimpan member'
                    });
                },
                complete: function() {
                    // Enable tombol submit
                    $('#submitBtn').prop('disabled', false).html('Simpan');
                }
            });
        });

        // Handle konfirmasi hapus
        $('#confirmDelete').on('click', function() {
            if (memberToDelete) {
                $.ajax({
                    url: '<?= base_url('member/delete') ?>/' + memberToDelete,
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
                                text: 'Member berhasil dihapus',
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
                            text: 'Terjadi kesalahan saat menghapus member'
                        });
                    }
                });
            }
            $('#deleteModal').modal('hide');
        });

        // Handle edit member
        $('.edit-member').on('click', function() {
            var id = $(this).data('id');
            var name = $(this).data('name');
            var phone = $(this).data('phone');
            var address = $(this).data('address');
            var birthDate = $(this).data('birth-date');
            var gender = $(this).data('gender');

            $('#edit_id').val(id);
            $('#edit_name').val(name);
            $('#edit_phone').val(phone);
            $('#edit_address').val(address);
            $('#edit_birth_date').val(birthDate);
            $('#edit_gender').val(gender);

            $('#editModal').modal('show');
        });

        // Handle submit edit
        $('#editSubmitBtn').on('click', function() {
            var id = $('#edit_id').val();
            var formData = {
                name: $('#edit_name').val(),
                phone: $('#edit_phone').val(),
                address: $('#edit_address').val(),
                birth_date: $('#edit_birth_date').val(),
                gender: $('#edit_gender').val(),
                <?= csrf_token() ?>: '<?= csrf_hash() ?>'
            };

            $.ajax({
                url: '<?= base_url('member/update') ?>/' + id,
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Data member berhasil diperbarui',
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
                        text: 'Terjadi kesalahan saat memperbarui data member'
                    });
                }
            });
        });
    });
</script>

<style>
    .badge {
        padding: 0.5em 0.75em;
        font-size: 0.85em;
        font-weight: 500;
        border-radius: 0.25rem;
    }

    .badge-pink {
        background-color: #ff69b4;
        color: white;
    }

    .table td {
        vertical-align: middle !important;
        padding: 12px;
    }

    .table th {
        vertical-align: middle !important;
        font-weight: 600;
        background-color: #f8f9fc;
        border-bottom: 2px solid #e3e6f0;
        color: #4e73df;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
    }

    .btn-group .btn {
        margin: 0 2px;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(0, 0, 0, .075);
    }

    .thead-light th {
        background-color: #f8f9fc !important;
        border-bottom: 2px solid #e3e6f0;
    }

    /* Tambahan style untuk filter */
    .form-control:focus {
        border-color: #4e73df;
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
    }

    select.form-control {
        cursor: pointer;
    }

    /* Style untuk DataTable info */
    .dataTables_info {
        color: #858796;
        font-size: 0.875rem;
    }

    /* Style untuk tombol pagination */
    .dataTables_paginate .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
        line-height: 1.5;
        border-radius: 0.2rem;
    }

    .dataTables_paginate .btn:disabled {
        cursor: not-allowed;
        opacity: 0.65;
    }

    .modal-header {
        border-bottom: none;
    }

    .modal-footer {
        border-top: none;
    }

    .input-group-text {
        background-color: #f8f9fc;
        border-color: #d1d3e2;
    }

    .input-group .form-control:focus {
        border-color: #4e73df;
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
    }
</style>
<?= $this->endSection() ?>

<?= $this->endSection() ?>