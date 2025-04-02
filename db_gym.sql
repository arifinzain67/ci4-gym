-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 02 Apr 2025 pada 05.11
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_gym`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `ci_sessions`
--

CREATE TABLE `ci_sessions` (
  `id` varchar(128) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `ci_sessions`
--

INSERT INTO `ci_sessions` (`id`, `ip_address`, `timestamp`, `data`) VALUES
('ci_session:030df9f74997040ab9e37e89d0035b71', '::1', 4294967295, '__ci_last_regenerate|i:1743515856;_ci_previous_url|s:35:\"http://localhost/ci4-gym/auth/login\";csrf_token_name|s:32:\"a08fbce14e0ed81f74aa20e30da91de7\";'),
('ci_session:08196ac26e56ab74df7a131aa30858ae', '::1', 4294967295, '__ci_last_regenerate|i:1743517619;_ci_previous_url|s:35:\"http://localhost/ci4-gym/auth/login\";csrf_token_name|s:32:\"a08fbce14e0ed81f74aa20e30da91de7\";'),
('ci_session:0cf38f0a3478d1034fa92b6135f7aff7', '::1', 4294967295, '__ci_last_regenerate|i:1743517308;_ci_previous_url|s:35:\"http://localhost/ci4-gym/auth/login\";csrf_token_name|s:32:\"a08fbce14e0ed81f74aa20e30da91de7\";'),
('ci_session:2bde890052ce08f6b2307486c6133bdc', '::1', 4294967295, '__ci_last_regenerate|i:1743514065;_ci_previous_url|s:35:\"http://localhost/ci4-gym/auth/login\";'),
('ci_session:562b2aa08b12bcdfa95c9ac63ed9a835', '::1', 4294967295, '__ci_last_regenerate|i:1743516937;_ci_previous_url|s:35:\"http://localhost/ci4-gym/auth/login\";csrf_token_name|s:32:\"a08fbce14e0ed81f74aa20e30da91de7\";'),
('ci_session:9691991b38eca4e9aa19efb3afcfe8b0', '::1', 4294967295, '__ci_last_regenerate|i:1743514781;_ci_previous_url|s:35:\"http://localhost/ci4-gym/auth/login\";'),
('ci_session:dce7364096daf2188d556b075c5a6bb6', '::1', 4294967295, '__ci_last_regenerate|i:1743517619;_ci_previous_url|s:35:\"http://localhost/ci4-gym/auth/login\";csrf_token_name|s:32:\"a08fbce14e0ed81f74aa20e30da91de7\";'),
('ci_session:eb22739020e860a98b37c929585527fe', '::1', 4294967295, '__ci_last_regenerate|i:1743513043;_ci_previous_url|s:35:\"http://localhost/ci4-gym/auth/login\";'),
('ci_session:ece8e929091e1ef155c385b6b9065a23', '::1', 4294967295, '__ci_last_regenerate|i:1743512624;_ci_previous_url|s:35:\"http://localhost/ci4-gym/auth/login\";');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_absensi_karyawan`
--

CREATE TABLE `tb_absensi_karyawan` (
  `id_absensi` int(11) NOT NULL,
  `id_karyawan` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `jam_masuk` time DEFAULT NULL,
  `jam_keluar` time DEFAULT NULL,
  `status` enum('Hadir','Izin','Sakit','Alpa') NOT NULL DEFAULT 'Hadir',
  `keterangan` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_check_in_out`
--

CREATE TABLE `tb_check_in_out` (
  `id_check` int(11) NOT NULL,
  `id_member` int(11) NOT NULL,
  `check_in` datetime NOT NULL,
  `check_out` datetime DEFAULT NULL,
  `status` enum('active','completed') NOT NULL DEFAULT 'active',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tb_check_in_out`
--

INSERT INTO `tb_check_in_out` (`id_check`, `id_member`, `check_in`, `check_out`, `status`, `created_at`, `updated_at`) VALUES
(1, 7, '2025-03-31 05:00:16', '2025-03-31 05:00:35', 'completed', '2025-03-31 12:00:16', '2025-03-31 12:00:35'),
(3, 9, '2025-03-31 05:01:01', '2025-03-31 05:01:47', 'completed', '2025-03-31 12:01:01', '2025-03-31 12:01:47'),
(4, 7, '2025-03-31 05:01:13', '2025-03-31 05:05:50', 'completed', '2025-03-31 12:01:13', '2025-03-31 12:05:50'),
(5, 9, '2025-03-31 05:06:04', '2025-03-31 05:21:37', 'completed', '2025-03-31 12:06:04', '2025-03-31 12:21:37'),
(6, 9, '2025-03-31 05:21:46', '2025-03-31 05:21:51', 'completed', '2025-03-31 12:21:46', '2025-03-31 12:21:51'),
(7, 7, '2025-03-31 05:24:12', '2025-03-31 05:24:17', 'completed', '2025-03-31 12:24:12', '2025-03-31 12:24:17'),
(9, 11, '2025-03-31 22:47:44', '2025-03-31 22:47:54', 'completed', '2025-04-01 05:47:44', '2025-04-01 05:47:54'),
(10, 9, '2025-03-31 22:52:37', '2025-03-31 22:53:16', 'completed', '2025-04-01 05:52:37', '2025-04-01 05:53:16'),
(11, 11, '2025-03-31 22:52:46', '2025-03-31 22:53:40', 'completed', '2025-04-01 05:52:46', '2025-04-01 05:53:40'),
(12, 9, '2025-03-31 22:53:23', '2025-03-31 22:53:45', 'completed', '2025-04-01 05:53:23', '2025-04-01 05:53:45'),
(14, 9, '2025-04-01 01:56:38', '2025-04-01 02:03:24', 'completed', '2025-04-01 08:56:38', '2025-04-01 09:03:24'),
(15, 11, '2025-04-01 02:03:13', '2025-04-01 02:03:18', 'completed', '2025-04-01 09:03:13', '2025-04-01 09:03:18'),
(16, 16, '2025-04-01 02:06:30', '2025-04-01 02:06:35', 'completed', '2025-04-01 09:06:30', '2025-04-01 09:06:35'),
(17, 16, '2025-04-01 02:06:42', '2025-04-01 02:06:46', 'completed', '2025-04-01 09:06:42', '2025-04-01 09:06:46'),
(18, 7, '2025-04-01 02:15:21', '2025-04-01 02:15:28', 'completed', '2025-04-01 09:15:21', '2025-04-01 09:15:28'),
(19, 7, '2025-04-01 10:38:25', '2025-04-01 15:06:31', 'completed', '2025-04-01 17:38:25', '2025-04-01 22:06:31'),
(20, 9, '2025-04-01 15:22:28', '2025-04-01 15:22:32', 'completed', '2025-04-01 22:22:28', '2025-04-01 22:22:32');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_karyawan`
--

CREATE TABLE `tb_karyawan` (
  `id_karyawan` int(11) NOT NULL,
  `kode_karyawan` varchar(20) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') NOT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `no_telepon` varchar(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `posisi` varchar(50) NOT NULL,
  `tanggal_bergabung` date NOT NULL,
  `status` enum('Aktif','Nonaktif') NOT NULL DEFAULT 'Aktif',
  `gaji` decimal(10,2) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tb_karyawan`
--

INSERT INTO `tb_karyawan` (`id_karyawan`, `kode_karyawan`, `nama`, `jenis_kelamin`, `tanggal_lahir`, `alamat`, `no_telepon`, `email`, `posisi`, `tanggal_bergabung`, `status`, `gaji`, `foto`, `created_at`, `updated_at`) VALUES
(5, 'KRY-20250402-001', 'Bayu Pangestu', 'Laki-laki', '0000-00-00', '', '087654352635', '', 'Kasir', '2025-04-02', 'Aktif', 0.00, NULL, '2025-04-02 02:50:54', '2025-04-02 03:02:08');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_members`
--

CREATE TABLE `tb_members` (
  `id_member` int(11) NOT NULL,
  `member_code` varchar(10) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `gender` enum('L','P') NOT NULL,
  `address` text DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tb_members`
--

INSERT INTO `tb_members` (`id_member`, `member_code`, `name`, `email`, `phone`, `gender`, `address`, `photo`, `created_at`, `updated_at`) VALUES
(7, 'RG250003', 'kadita', 'kadita@gmail.com', '081234789084', 'P', '', '1743401014_568ae0e570d900793209.png', '2025-03-28 19:14:50', '2025-03-31 18:47:27'),
(8, 'RG250004', 'dio', 'dio@gmail.com', '085427162831', 'L', '', '1743401028_92c8ec5d095460f2a7bf.png', '2025-03-28 19:24:19', '2025-03-30 23:03:48'),
(9, 'RG250005', 'didi', '', '089787654321', 'L', '', '1743192383_8b673fdbc2573cdd9f09.jpg', '2025-03-28 19:28:28', '2025-03-30 22:51:45'),
(11, 'RG250007', 'Rasya', NULL, '083748483912', 'L', NULL, '1743233825_25963cf2a59672572cc0.jpg', '2025-03-29 07:37:05', '2025-03-29 07:37:05'),
(16, 'RG250009', 'Bisma', NULL, '0821723645898', 'P', NULL, '1743473083_19c82d4bcce0597a46c8.webp', '2025-03-31 19:04:43', '2025-03-31 19:04:43');

--
-- Trigger `tb_members`
--
DELIMITER $$
CREATE TRIGGER `generate_member_code` BEFORE INSERT ON `tb_members` FOR EACH ROW BEGIN
    DECLARE new_code VARCHAR(10);
    DECLARE last_number INT;

    -- Ambil angka terakhir dari kode member tahun ini
    SELECT COALESCE(MAX(SUBSTRING(member_code, 5, 4)) + 1, 1) INTO last_number
    FROM tb_members
    WHERE SUBSTRING(member_code, 3, 2) = DATE_FORMAT(NOW(), '%y');

    -- Format kode member (RGYYXXXX)
    SET new_code = CONCAT('RG', DATE_FORMAT(NOW(), '%y'), LPAD(last_number, 4, '0'));

    -- Set nilai member_code untuk record baru
    SET NEW.member_code = new_code;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_membership_types`
--

CREATE TABLE `tb_membership_types` (
  `id_type` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` int(11) UNSIGNED NOT NULL,
  `duration` int(11) NOT NULL COMMENT 'Dalam hari (contoh: 30 untuk 1 bulan)',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tb_membership_types`
--

INSERT INTO `tb_membership_types` (`id_type`, `name`, `description`, `price`, `duration`, `created_at`, `updated_at`) VALUES
(4, 'Gold', 'Bisa Akses Gym Selama 90 Hari', 700000, 90, '2025-03-28 20:42:44', '2025-03-30 06:42:02'),
(5, 'Silver', 'Bisa Akses Gym Selama 30 Hari', 30000, 30, '2025-03-29 07:50:42', '2025-03-29 12:13:54'),
(7, 'Bronze', 'Bisa Akses Gym 1 Hari,', 25000, 1, '2025-03-30 14:46:08', '2025-04-01 02:21:18');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_transaction`
--

CREATE TABLE `tb_transaction` (
  `id_transaction` int(11) NOT NULL,
  `id_member` int(11) NOT NULL,
  `id_type` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `amount_paid` decimal(10,2) NOT NULL,
  `payment_type` enum('cash','transfer') NOT NULL DEFAULT 'cash',
  `payment_date` datetime NOT NULL,
  `status` enum('pending','paid','cancelled') NOT NULL DEFAULT 'pending',
  `expired_at` datetime NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tb_transaction`
--

INSERT INTO `tb_transaction` (`id_transaction`, `id_member`, `id_type`, `amount`, `amount_paid`, `payment_type`, `payment_date`, `status`, `expired_at`, `created_at`, `updated_at`) VALUES
(23, 7, 4, 700000.00, 700000.00, 'cash', '2025-01-15 10:30:00', 'paid', '2025-02-15 10:30:00', '2025-01-15 10:30:00', '2025-01-15 10:30:00'),
(24, 8, 5, 30000.00, 30000.00, 'transfer', '2025-01-20 14:15:00', 'paid', '2025-02-20 14:15:00', '2025-01-20 14:15:00', '2025-01-20 14:15:00'),
(25, 9, 4, 700000.00, 700000.00, 'cash', '2025-02-01 09:45:00', 'paid', '2025-02-28 09:45:00', '2025-02-01 09:45:00', '2025-02-01 09:45:00'),
(47, 16, 7, 25000.00, 40000.00, 'cash', '2025-04-01 09:05:21', 'paid', '2025-04-02 09:05:21', '2025-04-01 09:05:21', '2025-04-01 09:05:21'),
(48, 7, 7, 25000.00, 30000.00, 'cash', '2025-04-01 09:14:53', 'paid', '2025-04-02 09:14:53', '2025-04-01 09:14:53', '2025-04-01 09:14:53'),
(49, 8, 7, 25000.00, 30000.00, 'cash', '2025-04-01 09:18:53', 'paid', '2025-04-02 09:18:53', '2025-04-01 09:18:53', '2025-04-01 09:18:53'),
(50, 9, 7, 25000.00, 30000.00, 'cash', '2025-04-01 22:21:53', 'paid', '2025-04-02 22:21:53', '2025-04-01 22:21:53', '2025-04-01 22:21:53');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_user`
--

CREATE TABLE `tb_user` (
  `id_user` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `role` enum('admin','staff','pemilik') NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `id_karyawan` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tb_user`
--

INSERT INTO `tb_user` (`id_user`, `username`, `password`, `name`, `role`, `created_at`, `updated_at`, `id_karyawan`) VALUES
(1, 'admin', '$2y$10$0XE9pGNbT/PFBVolMa4uCu3KIgqk3Lfr/fhWn4w1pYqHNG.7sPIfm', 'Bryan', 'admin', '2025-04-01 18:53:42', '2025-04-01 21:45:51', NULL),
(6, 'bayuu', '$2y$10$6CcMzuFxHV22adgcS6v/QOWBR7/RPT0OYmUL8UYrQTRTojAbG3mNm', 'Bayu Pangestu', 'staff', '2025-04-02 02:51:42', '2025-04-02 02:51:42', 5),
(7, 'pemilik', '$2y$10$0XE9pGNbT/PFBVolMa4uCu3KIgqk3Lfr/fhWn4w1pYqHNG.7sPIfm', 'Nama Pemilik', 'pemilik', '2025-04-02 10:10:34', '2025-04-02 10:10:34', NULL);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `ci_sessions`
--
ALTER TABLE `ci_sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ci_sessions_timestamp` (`timestamp`);

--
-- Indeks untuk tabel `tb_absensi_karyawan`
--
ALTER TABLE `tb_absensi_karyawan`
  ADD PRIMARY KEY (`id_absensi`),
  ADD KEY `idx_absensi_tanggal` (`tanggal`),
  ADD KEY `idx_absensi_karyawan` (`id_karyawan`);

--
-- Indeks untuk tabel `tb_check_in_out`
--
ALTER TABLE `tb_check_in_out`
  ADD PRIMARY KEY (`id_check`),
  ADD KEY `member_id` (`id_member`);

--
-- Indeks untuk tabel `tb_karyawan`
--
ALTER TABLE `tb_karyawan`
  ADD PRIMARY KEY (`id_karyawan`),
  ADD KEY `idx_nama_karyawan` (`nama`);

--
-- Indeks untuk tabel `tb_members`
--
ALTER TABLE `tb_members`
  ADD PRIMARY KEY (`id_member`),
  ADD UNIQUE KEY `member_code` (`member_code`),
  ADD KEY `email` (`email`);

--
-- Indeks untuk tabel `tb_membership_types`
--
ALTER TABLE `tb_membership_types`
  ADD PRIMARY KEY (`id_type`);

--
-- Indeks untuk tabel `tb_transaction`
--
ALTER TABLE `tb_transaction`
  ADD PRIMARY KEY (`id_transaction`),
  ADD KEY `member_id` (`id_member`),
  ADD KEY `membership_type_id` (`id_type`);

--
-- Indeks untuk tabel `tb_user`
--
ALTER TABLE `tb_user`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `id_karyawan` (`id_karyawan`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `tb_absensi_karyawan`
--
ALTER TABLE `tb_absensi_karyawan`
  MODIFY `id_absensi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `tb_check_in_out`
--
ALTER TABLE `tb_check_in_out`
  MODIFY `id_check` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT untuk tabel `tb_karyawan`
--
ALTER TABLE `tb_karyawan`
  MODIFY `id_karyawan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `tb_members`
--
ALTER TABLE `tb_members`
  MODIFY `id_member` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT untuk tabel `tb_membership_types`
--
ALTER TABLE `tb_membership_types`
  MODIFY `id_type` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `tb_transaction`
--
ALTER TABLE `tb_transaction`
  MODIFY `id_transaction` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT untuk tabel `tb_user`
--
ALTER TABLE `tb_user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `tb_absensi_karyawan`
--
ALTER TABLE `tb_absensi_karyawan`
  ADD CONSTRAINT `tb_absensi_karyawan_ibfk_1` FOREIGN KEY (`id_karyawan`) REFERENCES `tb_karyawan` (`id_karyawan`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `tb_check_in_out`
--
ALTER TABLE `tb_check_in_out`
  ADD CONSTRAINT `tb_check_in_out_ibfk_1` FOREIGN KEY (`id_member`) REFERENCES `tb_members` (`id_member`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `tb_transaction`
--
ALTER TABLE `tb_transaction`
  ADD CONSTRAINT `tb_transaction_ibfk_1` FOREIGN KEY (`id_member`) REFERENCES `tb_members` (`id_member`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_transaction_ibfk_2` FOREIGN KEY (`id_type`) REFERENCES `tb_membership_types` (`id_type`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `tb_user`
--
ALTER TABLE `tb_user`
  ADD CONSTRAINT `tb_user_ibfk_1` FOREIGN KEY (`id_karyawan`) REFERENCES `tb_karyawan` (`id_karyawan`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
