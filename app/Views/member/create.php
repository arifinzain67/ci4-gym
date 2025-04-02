<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Tambah Member</h1>
        <a href="<?= base_url('member') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <?php if (session()->has('error')) : ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session('error') ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Tambah Member</h6>
        </div>
        <div class="card-body">
            <form action="<?= base_url('member/store') ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>

                <div class="row">
                    <!-- Kolom Kiri -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control <?= (session('errors.name')) ? 'is-invalid' : '' ?>"
                                id="name" name="name" value="<?= old('name') ?>" required>
                            <?php if (session('errors.name')) : ?>
                                <div class="invalid-feedback">
                                    <?= session('errors.name') ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control <?= (session('errors.email')) ? 'is-invalid' : '' ?>"
                                id="email" name="email" value="<?= old('email') ?>">
                            <?php if (session('errors.email')) : ?>
                                <div class="invalid-feedback">
                                    <?= session('errors.email') ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label for="phone">Nomor Telepon <span class="text-danger">*</span></label>
                            <input type="text" class="form-control <?= (session('errors.phone')) ? 'is-invalid' : '' ?>"
                                id="phone" name="phone" value="<?= old('phone') ?>" required>
                            <?php if (session('errors.phone')) : ?>
                                <div class="invalid-feedback">
                                    <?= session('errors.phone') ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label for="gender">Jenis Kelamin <span class="text-danger">*</span></label>
                            <select class="form-control <?= (session('errors.gender')) ? 'is-invalid' : '' ?>"
                                id="gender" name="gender" required>
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="L" <?= (old('gender') == 'L') ? 'selected' : '' ?>>Laki-laki</option>
                                <option value="P" <?= (old('gender') == 'P') ? 'selected' : '' ?>>Perempuan</option>
                            </select>
                            <?php if (session('errors.gender')) : ?>
                                <div class="invalid-feedback">
                                    <?= session('errors.gender') ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Kolom Kanan -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="address">Alamat</label>
                            <textarea class="form-control <?= (session('errors.address')) ? 'is-invalid' : '' ?>"
                                id="address" name="address" rows="3"><?= old('address') ?></textarea>
                            <?php if (session('errors.address')) : ?>
                                <div class="invalid-feedback">
                                    <?= session('errors.address') ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label for="photo">Foto Member</label>
                            <div class="custom-file-upload">
                                <input type="file" class="custom-file-input <?= (session('errors.photo')) ? 'is-invalid' : '' ?>"
                                    id="photo" name="photo" accept="image/*">
                                <div class="file-upload-wrapper">
                                    <div class="file-upload-message">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                        <p>Drag & drop foto di sini atau klik untuk memilih</p>
                                        <small class="text-muted">Format: JPG, JPEG, PNG. Maksimal 2MB</small>
                                    </div>
                                    <div class="file-upload-preview d-none">
                                        <img id="photoPreview" src="" alt="Preview" class="img-thumbnail">
                                        <button type="button" class="btn btn-sm btn-danger remove-photo">
                                            <i class="fas fa-times"></i> Hapus Foto
                                        </button>
                                    </div>
                                </div>
                                <?php if (session('errors.photo')) : ?>
                                    <div class="invalid-feedback">
                                        <?= session('errors.photo') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-right mt-4">
                    <button type="button" class="btn btn-primary" id="previewButton">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi -->
<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmModalLabel">Konfirmasi Tambah Member</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menambahkan member baru dengan data berikut?</p>
                <div class="member-data-preview">
                    <p><strong>Nama:</strong> <span id="previewName"></span></p>
                    <p><strong>Email:</strong> <span id="previewEmail"></span></p>
                    <p><strong>Telepon:</strong> <span id="previewPhone"></span></p>
                    <p><strong>Jenis Kelamin:</strong> <span id="previewGender"></span></p>
                    <p><strong>Alamat:</strong> <span id="previewAddress"></span></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="confirmSubmit">Ya, Simpan</button>
            </div>
        </div>
    </div>
</div>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        // Trigger file input saat area upload diklik
        $('.file-upload-wrapper').on('click', function() {
            $('#photo').click();
        });

        // Preview foto sebelum upload
        $('#photo').on('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#photoPreview').attr('src', e.target.result);
                    $('.file-upload-message').addClass('d-none');
                    $('.file-upload-preview').removeClass('d-none');
                }
                reader.readAsDataURL(file);
            }
        });

        // Hapus foto
        $('.remove-photo').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $('#photo').val('');
            $('#photoPreview').attr('src', '');
            $('.file-upload-message').removeClass('d-none');
            $('.file-upload-preview').addClass('d-none');
        });

        // Drag and drop
        $('.file-upload-wrapper').on('dragover', function(e) {
            e.preventDefault();
            $(this).addClass('dragover');
        }).on('dragleave', function() {
            $(this).removeClass('dragover');
        }).on('drop', function(e) {
            e.preventDefault();
            $(this).removeClass('dragover');
            const file = e.originalEvent.dataTransfer.files[0];
            if (file && file.type.startsWith('image/')) {
                $('#photo')[0].files = e.originalEvent.dataTransfer.files;
                $('#photo').trigger('change');
            }
        });

        // Preview data sebelum submit
        $('#previewButton').on('click', function() {
            // Validasi form
            if (!$('form')[0].checkValidity()) {
                $('form')[0].reportValidity();
                return;
            }

            // Tampilkan data di modal
            $('#previewName').text($('#name').val());
            $('#previewEmail').text($('#email').val() || '-');
            $('#previewPhone').text($('#phone').val());
            $('#previewGender').text($('#gender option:selected').text());
            $('#previewAddress').text($('#address').val() || '-');

            // Tampilkan modal
            $('#confirmModal').modal('show');
        });

        // Submit form setelah konfirmasi
        $('#confirmSubmit').on('click', function() {
            $('#confirmModal').modal('hide');
            // Submit form menggunakan form element
            document.querySelector('form').submit();
        });
    });
</script>

<style>
    .custom-file-upload {
        border: 2px dashed #ccc;
        border-radius: 4px;
        padding: 20px;
        text-align: center;
        background: #f8f9fc;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
    }

    .custom-file-upload:hover {
        border-color: #4e73df;
        background: #f1f3f9;
    }

    .custom-file-upload.dragover {
        border-color: #4e73df;
        background: #f1f3f9;
    }

    .custom-file-input {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
        z-index: 1;
    }

    .file-upload-wrapper {
        position: relative;
        z-index: 0;
    }

    .file-upload-message {
        color: #858796;
        pointer-events: none;
    }

    .file-upload-message i {
        font-size: 48px;
        color: #4e73df;
        margin-bottom: 10px;
    }

    .file-upload-preview {
        text-align: center;
        pointer-events: none;
    }

    .file-upload-preview img {
        max-width: 200px;
        max-height: 200px;
        margin-bottom: 10px;
    }

    .remove-photo {
        pointer-events: auto;
        position: relative;
        z-index: 2;
    }

    .member-data-preview {
        background: #f8f9fc;
        padding: 15px;
        border-radius: 4px;
        margin-top: 15px;
    }

    .member-data-preview p {
        margin-bottom: 8px;
    }

    .member-data-preview strong {
        color: #4e73df;
        min-width: 100px;
        display: inline-block;
    }
</style>
<?= $this->endSection() ?>

<?= $this->endSection() ?>