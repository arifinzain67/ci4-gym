<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Edit Paket Membership</h1>
</div>

<!-- Form Card -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Form Edit Paket</h6>
    </div>
    <div class="card-body">
        <?php if (session()->has('errors')) : ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach (session('errors') as $error) : ?>
                        <li><?= $error ?></li>
                    <?php endforeach ?>
                </ul>
            </div>
        <?php endif ?>

        <form action="<?= base_url('membershiptype/update/' . $type['id_type']) ?>" method="post">
            <?= csrf_field() ?>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">Nama Tipe <span class="text-danger">*</span></label>
                        <input type="text" class="form-control <?= (session('errors.name')) ? 'is-invalid' : '' ?>"
                            id="name" name="name" value="<?= old('name', $type['name']) ?>" required>
                        <?php if (session('errors.name')) : ?>
                            <div class="invalid-feedback">
                                <?= session('errors.name') ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="price">Harga <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Rp</span>
                            </div>
                            <input type="number" class="form-control <?= (session('errors.price')) ? 'is-invalid' : '' ?>"
                                id="price" name="price" value="<?= old('price', $type['price']) ?>" required min="0">
                        </div>
                        <?php if (session('errors.price')) : ?>
                            <div class="invalid-feedback">
                                <?= session('errors.price') ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="duration">Durasi (Hari) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control <?= (session('errors.duration')) ? 'is-invalid' : '' ?>"
                            id="duration" name="duration" value="<?= old('duration', $type['duration']) ?>" required min="1">
                        <?php if (session('errors.duration')) : ?>
                            <div class="invalid-feedback">
                                <?= session('errors.duration') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="description">Deskripsi</label>
                        <textarea class="form-control <?= (session('errors.description')) ? 'is-invalid' : '' ?>"
                            id="description" name="description" rows="3"><?= old('description', $type['description']) ?></textarea>
                        <?php if (session('errors.description')) : ?>
                            <div class="invalid-feedback">
                                <?= session('errors.description') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end mt-4">
                <a href="<?= base_url('membershiptype') ?>" class="btn btn-secondary mr-2">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
                <button type="button" class="btn btn-primary" id="submitBtn">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Konfirmasi -->
<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmModalLabel">Konfirmasi Perubahan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Silakan periksa data yang akan diubah:</p>
                <table class="table table-bordered">
                    <tr>
                        <th style="width: 30%">Nama Paket</th>
                        <td id="confirm_name"></td>
                    </tr>
                    <tr>
                        <th>Harga</th>
                        <td id="confirm_price"></td>
                    </tr>
                    <tr>
                        <th>Durasi</th>
                        <td id="confirm_duration"></td>
                    </tr>
                    <tr>
                        <th>Deskripsi</th>
                        <td id="confirm_description"></td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times"></i> Batal
                </button>
                <button type="button" class="btn btn-primary" id="confirmSubmit">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        // Format number input
        $('#price').on('input', function() {
            let value = $(this).val().replace(/\D/g, '');
            $(this).val(value);
        });

        // Submit button click
        $('#submitBtn').click(function(e) {
            e.preventDefault();

            // Validasi form
            if (!$('form')[0].checkValidity()) {
                $('form')[0].reportValidity();
                return;
            }

            // Ambil nilai dari form
            const name = $('#name').val();
            const price = $('#price').val();
            const duration = $('#duration').val();
            const description = $('#description').val();

            // Format harga untuk tampilan
            const formattedPrice = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR'
            }).format(price);

            // Tampilkan data di modal
            $('#confirm_name').text(name);
            $('#confirm_price').text(formattedPrice);
            $('#confirm_duration').text(duration + ' Hari');
            $('#confirm_description').text(description || '-');

            // Tampilkan modal
            $('#confirmModal').modal('show');
        });

        // Konfirmasi submit
        $('#confirmSubmit').click(function() {
            // Tutup modal
            $('#confirmModal').modal('hide');

            // Submit form
            document.querySelector('form').submit();
        });
    });
</script>
<?= $this->endSection() ?>