<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Revive Gym</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: #f8f9fc;
            height: 100vh;
        }
        
        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        
        .card {
            border: none;
            border-radius: 5px;
            border-left: 4px solid #4e73df;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            background: #fff;
            transition: transform 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
        }
        
        .logo-container {
            text-align: center;
            margin-bottom: 20px;
        }
        
        .logo {
            width: 100px;
            height: 100px;
            margin: 0 auto;
            margin-top: -50px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            border-radius: 50%;
            overflow: hidden;
            background: white;
            padding: 5px;
        }
        
        .logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }
        
        .card-title {
            color: #5a5c69;
            font-weight: 700;
            margin-top: 10px;
        }
        
        .form-control {
            border-radius: 5px;
            padding: 12px;
            border: 1px solid #d1d3e2;
            background-color: #f8f9fc;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            box-shadow: 0 0 0 0.25rem rgba(78, 115, 223, 0.25);
            border-color: #bac8f3;
        }
        
        .input-group-text {
            background-color: #f8f9fc;
            border-right: none;
            border-radius: 5px 0 0 5px;
            border-color: #d1d3e2;
        }
        
        .input-group .form-control {
            border-left: none;
            border-radius: 0 5px 5px 0;
        }
        
        .btn-primary {
            background: #4e73df;
            border: none;
            border-radius: 5px;
            padding: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background: #2e59d9;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(78, 115, 223, 0.4);
        }
        
        .alert {
            border-radius: 5px;
        }
        
        .text-muted {
            color: #858796 !important;
        }

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
</head>
<body>
    <div class="login-container">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body p-4">
                    <div class="logo-container">
                        <div class="logo">
                            <img src="<?= base_url('assets/img/logo.jpg') ?>" alt="Revive Gym Logo">
                        </div>
                    </div>
                    <h3 class="card-title text-center mb-4">Revive Gym Login</h3>
                    
                    <?php if(session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle me-2"></i><?= session()->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>

                    <?php if(session()->getFlashdata('message')): ?>
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i><?= session()->getFlashdata('message') ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?= base_url('auth/login') ?>" method="POST">
                        <?= csrf_field() ?>
                        
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input type="text" class="form-control" id="username" name="username" required value="<?= old('username') ?>" placeholder="Enter your username">
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="password" class="form-label">Password</label>
                            <div class="password-field-wrapper">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control" id="password" name="password" required placeholder="Enter your password">
                                </div>
                                <button type="button" class="toggle-password" data-target="#password" title="Lihat/Sembunyikan Password">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-sign-in-alt me-2"></i>Login
                            </button>
                        </div>
                        
                        <div class="text-center mt-3">
                            <p class="text-muted">Revive Gym Management System</p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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
</body>
</html>