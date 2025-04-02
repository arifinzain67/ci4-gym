<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= base_url('dashboard') ?>">
        <div class="sidebar-brand-icon"><i class="fas fa-dumbbell"></i></div>
        <div class="sidebar-brand-text mx-3">Revive Gym</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Dashboard -->
    <li class="nav-item">
        <a class="nav-link" href="<?= base_url('dashboard') ?>">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <!-- Member - Semua role bisa akses -->
    <li class="nav-item">
        <a class="nav-link" href="<?= base_url('member') ?>">
            <i class="fas fa-fw fa-user"></i>
            <span>Member</span>
        </a>
    </li>

    <!-- Paket Membership - Admin dan Pemilik saja -->
    <?php if (session()->get('role') === 'admin' || session()->get('role') === 'pemilik'): ?>
    <li class="nav-item">
        <a class="nav-link" href="<?= base_url('membershiptype') ?>">
            <i class="fas fa-fw fa-crown"></i>
            <span>Paket Membership</span>
        </a>
    </li>
    <?php endif; ?>

    <!-- Check In/Out - Semua role bisa akses -->
    <li class="nav-item">
        <a class="nav-link" href="<?= base_url('check-in-out') ?>">
            <i class="fas fa-fw fa-sign-in-alt"></i>
            <span>Check In/Out</span>
        </a>
    </li>

    <!-- Laporan Keuangan - Admin dan Pemilik saja -->
    <?php if (session()->get('role') === 'admin' || session()->get('role') === 'pemilik'): ?>
    <li class="nav-item">
        <a class="nav-link" href="<?= base_url('financial-report') ?>">
            <i class="fas fa-fw fa-file-alt"></i>
            <span>Laporan Keuangan</span>
        </a>
    </li>
    <?php endif; ?>

    <!-- Transaksi - Semua role bisa akses -->
    <li class="nav-item">
        <a class="nav-link" href="<?= base_url('transaction') ?>">
            <i class="fas fa-fw fa-shopping-cart"></i>
            <span>Transaksi</span>
        </a>
    </li>

    <!-- Karyawan - Admin dan Pemilik saja -->
    <?php if (session()->get('role') === 'admin' || session()->get('role') === 'pemilik'): ?>
    <li class="nav-item">
        <a class="nav-link" href="<?= base_url('karyawan') ?>">
            <i class="fas fa-fw fa-id-card"></i>
            <span>Karyawan</span>
        </a>
    </li>
    <?php endif; ?>

    <!-- Absensi Karyawan - Admin dan Pemilik saja -->
    <?php if (session()->get('role') === 'admin' || session()->get('role') === 'pemilik'): ?>
    <li class="nav-item">
        <a class="nav-link" href="<?= base_url('absensi_karyawan') ?>">
            <i class="fas fa-fw fa-calendar-check"></i>
            <span>Absensi Karyawan</span>
        </a>
    </li>
    <?php endif; ?>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Logout -->
    <li class="nav-item">
        <a class="nav-link" href="<?= base_url('auth/logout') ?>">
            <i class="fas fa-fw fa-sign-out-alt"></i>
            <span>Logout</span>
        </a>
    </li>

    <!-- Toggle Sidebar -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle">
            <i class="fas fa-angle-left"></i>
        </button>
    </div>
</ul>