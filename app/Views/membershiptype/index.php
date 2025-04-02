<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Daftar Paket Membership</h1>
        <a href="<?= base_url('membershiptype/create') ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Paket
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

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th class="text-center" style="width: 5%">#</th>
                            <th>Nama Paket</th>
                            <th>Harga</th>
                            <th>Durasi</th>
                            <th>Deskripsi</th>
                            <th class="text-center" style="width: 15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($membershipTypes) && is_array($membershipTypes)) : ?>
                            <?php foreach ($membershipTypes as $index => $type) : ?>
                                <tr>
                                    <td class="text-center"><?= $index + 1 ?></td>
                                    <td><?= esc($type['name']) ?></td>
                                    <td>Rp <?= number_format($type['price'], 0, ',', '.') ?></td>
                                    <td><?= $type['duration'] ?> Hari</td>
                                    <td><?= esc($type['description'] ?? '-') ?></td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="<?= base_url('membershiptype/edit/' . $type['id_type']) ?>"
                                                class="btn btn-warning btn-sm" data-toggle="tooltip" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-danger btn-sm delete-btn"
                                                data-id="<?= $type['id_type'] ?>" data-name="<?= esc($type['name']) ?>"
                                                data-toggle="modal" data-target="#deleteModal" data-toggle="tooltip"
                                                title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Hapus Paket -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus paket membership <span id="deleteName"></span>?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <a href="#" id="deleteLink" class="btn btn-danger">Hapus</a>
            </div>
        </div>
    </div>
</div>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        // Initialize DataTable
        $('#dataTable').DataTable({
            language: {
                "url": "<?= base_url('assets/js/datatables/i18n/Indonesian.json') ?>"
            },
            "pageLength": 10,
            "order": [
                [0, 'asc']
            ],
            "responsive": true
        });

        // Edit button click
        $('.edit-btn').click(function() {
            const id = $(this).data('id');
            const name = $(this).data('name');
            const price = $(this).data('price');
            const duration = $(this).data('duration');
            const description = $(this).data('description');

            $('#editForm').attr('action', '<?= base_url('membershiptype/update/') ?>' + id);
            $('#edit_name').val(name);
            $('#edit_price').val(price);
            $('#edit_duration').val(duration);
            $('#edit_description').val(description);
        });

        // Delete button click
        $('.delete-btn').click(function() {
            const id = $(this).data('id');
            const name = $(this).data('name');

            $('#deleteName').text(name);
            $('#deleteLink').attr('href', '<?= base_url('membershiptype/delete/') ?>' + id);
        });

        // Format number input
        $('#price, #edit_price').on('input', function() {
            let value = $(this).val().replace(/\D/g, '');
            $(this).val(value);
        });

        // Initialize tooltips
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>

<style>
    .modal-header {
        background-color: #f8f9fc;
        border-bottom: 1px solid #e3e6f0;
    }

    .modal-footer {
        background-color: #f8f9fc;
        border-top: 1px solid #e3e6f0;
    }

    #deleteName {
        font-weight: 600;
        color: #e74a3b;
    }
</style>
<?= $this->endSection() ?>

<?= $this->endSection() ?>