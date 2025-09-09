-- Database: `ppdbonline`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_lengkap` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('superadmin','verifikator') NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`nama_lengkap`, `username`, `password`, `role`) VALUES
('Administrator', 'admin', '$2y$10$example.password.hash', 'superadmin');

-- --------------------------------------------------------

--
-- Table structure for table `dokumen`
--

CREATE TABLE `dokumen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pendaftaran_id` int(11) NOT NULL,
  `jenis_dokumen` enum('foto','rapor','sktm','prestasi') NOT NULL,
  `nama_file` varchar(255) NOT NULL,
  `path_file` varchar(255) NOT NULL,
  `status_verifikasi` enum('Belum Dicek','Valid','Tidak Valid') NOT NULL DEFAULT 'Belum Dicek',
  `catatan_verifikator` text DEFAULT NULL,
  `tgl_upload` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `pendaftaran_id` (`pendaftaran_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jadwal`
--

CREATE TABLE `jadwal` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tahapan` varchar(100) NOT NULL,
  `tgl_mulai` datetime NOT NULL,
  `tgl_selesai` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jadwal`
--

INSERT INTO `jadwal` (`tahapan`, `tgl_mulai`, `tgl_selesai`) VALUES
('Pendaftaran Online', '2025-06-10 00:00:00', '2025-06-15 23:59:59'),
('Verifikasi Dokumen', '2025-06-16 00:00:00', '2025-06-18 23:59:59'),
('Seleksi & Ranking', '2025-06-19 00:00:00', '2025-06-22 23:59:59'),
('Pengumuman Hasil', '2025-06-23 00:00:00', '2025-06-23 23:59:59'),
('Daftar Ulang', '2025-06-25 00:00:00', '2025-06-27 23:59:59');

-- --------------------------------------------------------

--
-- Table structure for table `jalur`
--

CREATE TABLE `jalur` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_jalur` varchar(100) NOT NULL,
  `kuota` int(11) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jalur`
--

INSERT INTO `jalur` (`nama_jalur`, `kuota`, `deskripsi`) VALUES
('Prestasi Akademik', 30, 'Untuk siswa dengan prestasi akademik unggul.'),
('Prestasi Non-Akademik', 20, 'Untuk siswa dengan prestasi di bidang non-akademik (olahraga, seni, dll).'),
('Afirmasi', 50, 'Untuk siswa dari keluarga kurang mampu (dibuktikan dengan KIP/SKTM).');

-- --------------------------------------------------------

--
-- Table structure for table `log_aktivitas`
--

CREATE TABLE `log_aktivitas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) DEFAULT NULL,
  `aktivitas` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `admin_id` (`admin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pendaftar`
--

CREATE TABLE `pendaftar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nik` varchar(16) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `jenis_kelamin` enum('L','P') NOT NULL,
  `tempat_lahir` varchar(50) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `alamat` text NOT NULL,
  `no_hp` varchar(15) NOT NULL,
  `sekolah_asal` varchar(100) NOT NULL,
  `tgl_registrasi_akun` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `nik` (`nik`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pendaftaran`
--

CREATE TABLE `pendaftaran` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `no_pendaftaran` varchar(20) NOT NULL,
  `pendaftar_id` int(11) NOT NULL,
  `jalur_id` int(11) NOT NULL,
  `status_pendaftaran` enum('Proses','Diverifikasi','Lulus','Tidak Lulus','Daftar Ulang') NOT NULL DEFAULT 'Proses',
  `skor_akhir` float DEFAULT NULL,
  `tgl_daftar` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `no_pendaftaran` (`no_pendaftaran`),
  KEY `pendaftar_id` (`pendaftar_id`),
  KEY `jalur_id` (`jalur_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pengaturan`
--

CREATE TABLE `pengaturan` (
  `setting_name` varchar(50) NOT NULL,
  `setting_value` text NOT NULL,
  PRIMARY KEY (`setting_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pengaturan`
--

INSERT INTO `pengaturan` (`setting_name`, `setting_value`) VALUES
('nama_sekolah', 'MTs Negeri 1 Banjarnegara'),
('tahun_ajaran', '2025/2026'),
('alamat_sekolah', 'Jl. Letjend Suprapto No.1A, Kutabanjarnegara, Kec. Banjarnegara, Kab. Banjarnegara, Jawa Tengah 53418'),
('email_panitia', 'ppdb@mtsn1banjarnegara.sch.id'),
('wa_panitia', '6281234567890');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `dokumen`
--
ALTER TABLE `dokumen`
  ADD CONSTRAINT `dokumen_ibfk_1` FOREIGN KEY (`pendaftaran_id`) REFERENCES `pendaftaran` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  ADD CONSTRAINT `log_aktivitas_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `pendaftaran`
--
ALTER TABLE `pendaftaran`
  ADD CONSTRAINT `pendaftaran_ibfk_1` FOREIGN KEY (`pendaftar_id`) REFERENCES `pendaftar` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pendaftaran_ibfk_2` FOREIGN KEY (`jalur_id`) REFERENCES `jalur` (`id`);
COMMIT;
