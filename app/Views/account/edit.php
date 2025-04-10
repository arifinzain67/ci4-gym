<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Akun</h1>
    </div>

    <!-- Display Messages -->
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

    <?php if (session()->has('errors')) : ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                <?php foreach (session('errors') as $error) : ?>
                    <li><?= $error ?></li>
                <?php endforeach; ?>
            </ul>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Akun</h6>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('account/update') ?>" method="post">
                        <?= csrf_field() ?>
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" id="username" name="username" value="<?= $user['username'] ?>" required>
                            <small class="form-text text-muted">Username digunakan untuk login ke sistem.</small>
                        </div>
                        
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="m-0 font-weight-bold text-primary">Ubah Password</h6>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="current_password">Password Saat Ini</label>
                                    <div class="password-field-wrapper">
                                        <input type="password" class="form-control" id="current_password" name="current_password">
                                        <button type="button" class="toggle-password" data-target="#current_password" title="Lihat/Sembunyikan Password">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <small class="form-text text-muted">Kosongkan semua field password jika tidak ingin mengubah password.</small>
                                </div>
                                <div class="form-group">
                                    <label for="new_password">Password Baru</label>
                                    <div class="password-field-wrapper">
                                        <input type="password" class="form-control" id="new_password" name="new_password">
                                        <button type="button" class="toggle-password" data-target="#new_password" title="Lihat/Sembunyikan Password">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <small class="form-text text-muted">Password minimal 8 karakter, harus mengandung huruf besar (A-Z), huruf kecil (a-z), angka (0-9), dan karakter khusus (@$!%*?&#).</small>
                                    <div class="password-strength-info mt-1 small">
                                        <p class="text-info mb-1"><i class="fas fa-info-circle"></i> Contoh password yang kuat: Example123@, Secure!987, Gym2025#</p>
                                        <p class="text-danger mb-0"><i class="fas fa-exclamation-triangle"></i> Jangan gunakan informasi pribadi seperti nama, tanggal lahir, atau kata-kata umum dalam password Anda</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="confirm_password">Konfirmasi Password Baru</label>
                                    <div class="password-field-wrapper">
                                        <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                                        <button type="button" class="toggle-password" data-target="#confirm_password" title="Lihat/Sembunyikan Password">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            <a href="<?= base_url('profile') ?>" class="btn btn-secondary">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Styling untuk tombol toggle password */
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

    /* Memastikan input password memiliki padding untuk ikon */
    .password-field-wrapper {
        position: relative;
    }

    .password-field-wrapper .form-control {
        padding-right: 40px;
    }
</style>

<script>
    // Form validation
    document.querySelector('form').addEventListener('submit', function(e) {
        const newPassword = document.getElementById('new_password').value;
        const confirmPassword = document.getElementById('confirm_password').value;
        const currentPassword = document.getElementById('current_password').value;
        
        // Jika ada password baru, pastikan current password diisi
        if (newPassword && !currentPassword) {
            e.preventDefault();
            alert('Silakan masukkan password saat ini.');
            return;
        }
        
        // Jika ada password baru, pastikan sesuai dengan konfirmasi
        if (newPassword && newPassword !== confirmPassword) {
            e.preventDefault();
            alert('Password baru dan konfirmasi password tidak sama.');
            return;
        }
    });

    // Toggle password visibility dengan animasi
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
<?= $this->endSection() ?>
