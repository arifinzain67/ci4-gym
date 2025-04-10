<?= $this->extend('layouts/main'); ?>

<?= $this->section('content'); ?>
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Buat Akun User</h1>
        <a href="<?= base_url('karyawan/' . $karyawan['id_karyawan']); ?>" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
        </a>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Data Karyawan</h6>
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <th>Kode Karyawan</th>
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
                    </table>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Form Buat Akun User</h6>
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

                    <form action="<?= base_url('karyawan/' . $karyawan['id_karyawan'] . '/storeaccount'); ?>" method="post">
                        <?= csrf_field(); ?>
                        
                        <div class="form-group">
                            <label for="username">Username <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="username" name="username" value="<?= old('username'); ?>" required>
                            <small class="text-muted">Username minimal 5 karakter dan harus unik</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="password">Password <span class="text-danger">*</span></label>
                            <div class="password-field-wrapper">
                                <input type="password" class="form-control" id="password" name="password" required>
                                <button type="button" class="toggle-password" data-target="#password" title="Lihat/Sembunyikan Password">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <small class="text-muted">Password minimal 8 karakter, harus mengandung huruf besar (A-Z), huruf kecil (a-z), angka (0-9), dan karakter khusus (@$!%*?&#)</small>
                            <div class="password-strength-info mt-1 small">
                                <p class="text-info mb-1"><i class="fas fa-info-circle"></i> Contoh password yang kuat: Example123@, Secure!987, Gym2025#</p>
                                <p class="text-danger mb-0"><i class="fas fa-exclamation-triangle"></i> Jangan gunakan informasi pribadi seperti nama, tanggal lahir, atau kata-kata umum dalam password Anda</p>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="confirm_password">Konfirmasi Password <span class="text-danger">*</span></label>
                            <div class="password-field-wrapper">
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                <button type="button" class="toggle-password" data-target="#confirm_password" title="Lihat/Sembunyikan Password">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="role">Role <span class="text-danger">*</span></label>
                            <select class="form-control" id="role" name="role" required>
                                <option value="">Pilih Role</option>
                                <?php if (session()->get('role') === 'pemilik'): ?>
                                <option value="admin" <?= old('role') == 'admin' ? 'selected' : ''; ?>>Admin</option>
                                <option value="staff" <?= old('role') == 'staff' ? 'selected' : ''; ?>>Kasir</option>
                                <option value="pemilik" <?= old('role') == 'pemilik' ? 'selected' : ''; ?>>Pemilik</option>
                                <?php else: ?>
                                <option value="staff" <?= old('role') == 'staff' ? 'selected' : ''; ?>>Kasir</option>
                                <?php endif; ?>
                            </select>
                            <small class="text-muted">
                                <strong>Admin:</strong> Dapat mengakses fitur manajemen member, paket, transaksi, laporan keuangan, dan karyawan<br>
                                <strong>Kasir:</strong> Hanya dapat mengakses dashboard, member, check-in & check-out, dan transaksi<br>
                                <?php if (session()->get('role') === 'pemilik'): ?>
                                <strong>Pemilik:</strong> Memiliki akses penuh ke seluruh fitur termasuk hapus transaksi
                                <?php endif; ?>
                            </small>
                        </div>
                        
                        <button type="submit" class="btn btn-primary mt-4">Buat Akun</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Styling for toggle password button */
    .toggle-password {
        border: none;
        background: none;
        color: #4e73df;
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        z-index: 10;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 1.1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        opacity: 1 !important; /* Memastikan tombol selalu terlihat */
        visibility: visible !important; /* Memastikan tombol selalu terlihat */
    }

    .toggle-password:hover {
        background-color: rgba(78, 115, 223, 0.1);
        color: #2e59d9;
    }

    .toggle-password:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(78, 115, 223, 0.25);
    }

    .toggle-password i {
        transition: all 0.3s ease;
        opacity: 1 !important; /* Memastikan ikon selalu terlihat */
    }

    .toggle-password.active {
        color: #2e59d9;
    }

    /* Make sure the password input has padding for the icon */
    .password-field-wrapper {
        position: relative;
    }

    .password-field-wrapper .form-control {
        padding-right: 40px;
    }
</style>

<script>
    // Toggle password visibility with animation
    document.addEventListener('DOMContentLoaded', function() {
        const togglePasswordButtons = document.querySelectorAll('.toggle-password');
        
        togglePasswordButtons.forEach(button => {
            button.addEventListener('click', function() {
                this.classList.toggle('active');
                
                const input = document.querySelector(this.dataset.target);
                const icon = this.querySelector('i');
                
                // Animate the icon change
                icon.style.transform = 'scale(0)';
                
                setTimeout(() => {
                    if (input.type === 'password') {
                        input.type = 'text';
                        icon.classList.remove('fa-eye');
                        icon.classList.add('fa-eye-slash');
                    } else {
                        input.type = 'password';
                        icon.classList.remove('fa-eye-slash');
                        icon.classList.add('fa-eye');
                    }
                    
                    icon.style.transform = 'scale(1)';
                }, 150);
            });
        });
    });
</script>
<?= $this->endSection(); ?>
