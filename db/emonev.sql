-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 23, 2024 at 03:39 AM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 7.3.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `emonev`
--

-- --------------------------------------------------------

--
-- Table structure for table `ref_indikators`
--

CREATE TABLE `ref_indikators` (
  `id` int(11) NOT NULL,
  `nama` text NOT NULL,
  `fid_program` int(11) DEFAULT NULL,
  `fid_kegiatan` int(11) DEFAULT NULL,
  `fid_sub_kegiatan` int(11) DEFAULT NULL,
  `kinerja_persentase` varchar(8) NOT NULL,
  `kinerja_eviden` int(11) NOT NULL,
  `keterangan_eviden` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `ref_indikators`
--

INSERT INTO `ref_indikators` (`id`, `nama`, `fid_program`, `fid_kegiatan`, `fid_sub_kegiatan`, `kinerja_persentase`, `kinerja_eviden`, `keterangan_eviden`) VALUES
(1, 'Nilai Implementasi Manajemen Talenta Pegawai ASN', 37, NULL, NULL, '20', 0, '0'),
(4, 'Persentase Jabatan Pimpinan dan Administrasi yang Terisi (pembatasan pada jabatan Eselon II, III, dan IV)', NULL, 39, NULL, '100', 0, '0'),
(5, 'Persentase Jabatan Fungsional yang Terisi sesuai Prioritas Aktual Reformasi Birokrasi', NULL, 39, NULL, '32', 0, '0'),
(10, 'Persentase Penempatan Pegawai sesuai SOP (sesuai Pohon Kinerja)', NULL, NULL, 33, '100', 0, '0'),
(11, 'Jumlah Dokumen Hasil Pelaksanaan Mutasi Jabatan Pimpinan Tinggi, Jabatan Administrasi, Jabatan Pelaksana dan Mutasi ASN antar Daerah (sesuai Kepmendagri)', NULL, NULL, 33, '0', 1, 'Dokumen');

-- --------------------------------------------------------

--
-- Table structure for table `ref_kegiatans`
--

CREATE TABLE `ref_kegiatans` (
  `id` int(11) NOT NULL,
  `fid_part` int(11) DEFAULT NULL,
  `fid_program` int(11) NOT NULL,
  `kode` varchar(30) NOT NULL,
  `nama` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `ref_kegiatans`
--

INSERT INTO `ref_kegiatans` (`id`, `fid_part`, `fid_program`, `kode`, `nama`) VALUES
(38, 3, 37, '5.03.02.2.03', 'Pengembangan Kompetensi ASN'),
(39, 11, 37, '5.03.02.2.02', 'Mutasi dan Promosi ASN');

-- --------------------------------------------------------

--
-- Table structure for table `ref_parts`
--

CREATE TABLE `ref_parts` (
  `id` int(11) NOT NULL,
  `nama` varchar(80) NOT NULL,
  `singkatan` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `ref_parts`
--

INSERT INTO `ref_parts` (`id`, `nama`, `singkatan`) VALUES
(1, 'SEKRETARIAT', 'SEKRETARIAT'),
(2, 'PENGADAAN, PEMBERHENTIAN, INFORMASI KEPEGAWAIAN', 'PPIK'),
(3, 'PENGEMBANGAN SUMBER DAYA MANUSIA', 'PSDM'),
(11, 'MUTASI, PROMOSI DAN KINERJA', 'MPK'),
(12, 'KEPALA BADAN', 'KABAN');

-- --------------------------------------------------------

--
-- Table structure for table `ref_programs`
--

CREATE TABLE `ref_programs` (
  `id` int(11) NOT NULL,
  `fid_unor` int(11) DEFAULT NULL,
  `kode` varchar(80) NOT NULL,
  `nama` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `ref_programs`
--

INSERT INTO `ref_programs` (`id`, `fid_unor`, `kode`, `nama`) VALUES
(37, 1, '5.03.02', 'PROGRAM KEPEGAWAIAN DAERAH');

-- --------------------------------------------------------

--
-- Table structure for table `ref_sub_kegiatans`
--

CREATE TABLE `ref_sub_kegiatans` (
  `id` int(11) NOT NULL,
  `fid_kegiatan` int(11) DEFAULT NULL,
  `kode` varchar(50) NOT NULL,
  `nama` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `ref_sub_kegiatans`
--

INSERT INTO `ref_sub_kegiatans` (`id`, `fid_kegiatan`, `kode`, `nama`) VALUES
(32, 38, '5.03.02.2.03.0003', 'Pengeloaan Administrasi Diklat dan Sertifikasi ASN'),
(33, 39, '5.03.02.2.02.0001', 'Pengelolaan Mutasi ASN');

-- --------------------------------------------------------

--
-- Table structure for table `ref_unors`
--

CREATE TABLE `ref_unors` (
  `id` int(11) NOT NULL,
  `nama` varchar(80) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `ref_unors`
--

INSERT INTO `ref_unors` (`id`, `nama`) VALUES
(1, 'BADAN KEPEGAWAIN DAN PENGEMBAGAN SUMBER DAYA MANUSIA');

-- --------------------------------------------------------

--
-- Table structure for table `ref_uraians`
--

CREATE TABLE `ref_uraians` (
  `id` int(11) NOT NULL,
  `fid_kegiatan` int(11) NOT NULL,
  `fid_sub_kegiatan` int(11) NOT NULL,
  `kode` varchar(50) NOT NULL,
  `nama` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `ref_uraians`
--

INSERT INTO `ref_uraians` (`id`, `fid_kegiatan`, `fid_sub_kegiatan`, `kode`, `nama`) VALUES
(7, 38, 32, '5.1.02.01.01.0026', 'Belanja Alat/Bahan untuk Kegiatan Kantor- Bahan Cetak'),
(8, 38, 32, '5.1.02.01.01.0052', 'Belanja Makanan dan Minuman Rapat'),
(9, 38, 32, '5.1.02.02.01.0026', 'Belanja Jasa Tenaga Administrasi'),
(10, 38, 32, '5.1.02.02.12.0001', 'Belanja Kursus Singkat/Pelatihan'),
(12, 39, 33, '5.1.02.01.01.0052', 'Belanja Makanan dan Minuman Rapat'),
(13, 39, 33, '5.1.02.02.01.0003', 'Honorarium Narasumber atau Pembahas, Moderator, Pembawa Acara, dan Panitia');

-- --------------------------------------------------------

--
-- Table structure for table `spj`
--

CREATE TABLE `spj` (
  `id` int(11) NOT NULL,
  `token` varchar(40) NOT NULL,
  `fid_part` int(11) NOT NULL,
  `fid_program` int(11) NOT NULL,
  `fid_kegiatan` int(11) NOT NULL,
  `fid_sub_kegiatan` int(11) NOT NULL,
  `fid_uraian` int(11) DEFAULT NULL,
  `koderek` varchar(50) NOT NULL,
  `nomor_pembukuan` varchar(40) NOT NULL,
  `bulan` varchar(5) NOT NULL,
  `tahun` year(4) NOT NULL,
  `tanggal_pembukuan` date NOT NULL,
  `jumlah` varchar(15) NOT NULL,
  `uraian` text NOT NULL,
  `is_status` enum('ENTRI','VERIFIKASI','VERIFIKASI_ADMIN','BTL','TMS','SELESAI','SELESAI_TMS','SELESAI_BTL') NOT NULL DEFAULT 'ENTRI',
  `is_realisasi` enum('LS','UP','GU','TU') DEFAULT NULL,
  `catatan` text NOT NULL,
  `approve_by` varchar(30) NOT NULL,
  `approve_at` datetime NOT NULL,
  `entri_at` datetime NOT NULL,
  `entri_by` varchar(30) NOT NULL,
  `entri_by_part` int(11) DEFAULT NULL,
  `verify_at` datetime NOT NULL,
  `verify_by` varchar(30) NOT NULL,
  `berkas_file` varchar(30) NOT NULL,
  `berkas_link` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `spj_riwayat`
--

CREATE TABLE `spj_riwayat` (
  `id` int(11) NOT NULL,
  `token` varchar(40) NOT NULL,
  `nama_part` varchar(100) NOT NULL,
  `nama_program` varchar(100) NOT NULL,
  `nama_kegiatan` varchar(100) NOT NULL,
  `nama_sub_kegiatan` varchar(100) NOT NULL,
  `nama_uraian` varchar(100) NOT NULL,
  `kode_program` varchar(30) DEFAULT NULL,
  `kode_kegiatan` varchar(30) NOT NULL,
  `kode_sub_kegiatan` varchar(30) NOT NULL,
  `kode_uraian` varchar(100) NOT NULL,
  `koderek` varchar(50) NOT NULL,
  `nomor_pembukuan` varchar(40) DEFAULT NULL,
  `bulan` varchar(5) NOT NULL,
  `tahun` year(4) NOT NULL,
  `tanggal_pembukuan` date DEFAULT NULL,
  `jumlah` varchar(15) NOT NULL,
  `uraian` text NOT NULL,
  `is_status` enum('APPROVE','BTL','TMS') DEFAULT NULL,
  `is_realisasi` enum('LS','UP','GU','TU') DEFAULT NULL,
  `catatan` text NOT NULL,
  `approve_by` varchar(30) NOT NULL,
  `approve_at` datetime NOT NULL,
  `entri_at` datetime NOT NULL,
  `entri_by` varchar(30) NOT NULL,
  `entri_by_part` int(11) DEFAULT NULL,
  `verify_at` datetime NOT NULL,
  `verify_by` varchar(30) NOT NULL,
  `berkas_file` varchar(30) NOT NULL,
  `berkas_link` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `t_notify`
--

CREATE TABLE `t_notify` (
  `id` int(11) NOT NULL,
  `type` enum('SUCCESS','WARNING','INFO','DANGER') NOT NULL DEFAULT 'INFO',
  `type_icon` varchar(50) DEFAULT '<i class="fa fa-exclamation-circle"></i>',
  `to` int(11) DEFAULT NULL,
  `mode` enum('GLOBAL','PRIVATE','PRIVATE_ALL') NOT NULL DEFAULT 'PRIVATE',
  `message` text NOT NULL,
  `is_aktif` enum('Y','N') NOT NULL DEFAULT 'Y',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `t_notify`
--

INSERT INTO `t_notify` (`id`, `type`, `type_icon`, `to`, `mode`, `message`, `is_aktif`, `created_at`) VALUES
(22, 'SUCCESS', '<i class=\"fa fa-exclamation-circle\"></i>', 1, 'GLOBAL', 'assalamualaikum bapak/ibu, untuk cetak laporan sudah bisa, silahkan cek inbox masing - masing PIC : <a href=\"/emonev/app/inbox\" target=\"_blank\" class=\"btn btn-sm btn-success\">Download</a>', 'N', '2023-10-26 18:51:13');

-- --------------------------------------------------------

--
-- Table structure for table `t_pagu`
--

CREATE TABLE `t_pagu` (
  `id` int(11) NOT NULL,
  `fid_part` int(11) DEFAULT NULL,
  `fid_kegiatan` int(11) DEFAULT NULL,
  `fid_sub_kegiatan` int(11) DEFAULT NULL,
  `fid_uraian` int(11) DEFAULT NULL,
  `is_perubahan` enum('Y','N') NOT NULL DEFAULT 'N',
  `total_pagu_awal` varchar(20) NOT NULL,
  `total_pagu_realisasi` varchar(30) NOT NULL,
  `total_pagu_akhir` varchar(20) NOT NULL,
  `tahun` year(4) NOT NULL,
  `created_at` datetime NOT NULL,
  `created_by` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `t_pagu`
--

INSERT INTO `t_pagu` (`id`, `fid_part`, `fid_kegiatan`, `fid_sub_kegiatan`, `fid_uraian`, `is_perubahan`, `total_pagu_awal`, `total_pagu_realisasi`, `total_pagu_akhir`, `tahun`, `created_at`, `created_by`) VALUES
(2, 3, NULL, 32, NULL, 'N', '2256999990', '', '', 2024, '2024-01-21 12:10:54', 'psdm'),
(3, 3, NULL, NULL, 7, 'N', '0', '', '', 2024, '2024-01-21 12:26:11', 'psdm'),
(4, 3, NULL, NULL, 8, 'N', '0', '', '', 2024, '2024-01-21 12:26:24', 'psdm'),
(5, 3, NULL, NULL, 10, 'N', '0', '', '', 2024, '2024-01-21 12:44:32', 'psdm'),
(6, 11, NULL, 33, NULL, 'N', '91828600', '', '', 2024, '2024-01-22 15:16:23', 'mpkasn');

-- --------------------------------------------------------

--
-- Table structure for table `t_privilages`
--

CREATE TABLE `t_privilages` (
  `id` int(11) NOT NULL,
  `fid_user` int(11) NOT NULL,
  `priv_default` enum('Y','N') NOT NULL DEFAULT 'N',
  `priv_users` enum('Y','N') NOT NULL DEFAULT 'N',
  `priv_notify` enum('Y','N') NOT NULL DEFAULT 'N',
  `priv_settings` enum('Y','N') NOT NULL DEFAULT 'N',
  `priv_programs` enum('Y','N') NOT NULL DEFAULT 'N',
  `priv_verifikasi` enum('Y','N') NOT NULL DEFAULT 'N',
  `priv_approve` enum('Y','N') NOT NULL DEFAULT 'N',
  `priv_riwayat_spj` enum('Y','N') NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `t_privilages`
--

INSERT INTO `t_privilages` (`id`, `fid_user`, `priv_default`, `priv_users`, `priv_notify`, `priv_settings`, `priv_programs`, `priv_verifikasi`, `priv_approve`, `priv_riwayat_spj`) VALUES
(1, 3, 'Y', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'N'),
(3, 4, 'Y', 'N', 'N', 'N', 'Y', 'N', 'N', 'Y'),
(5, 8, 'Y', 'N', 'N', 'N', 'N', 'N', 'N', 'N'),
(7, 10, 'Y', 'N', 'N', 'N', 'Y', 'N', 'N', 'N'),
(8, 11, 'Y', 'N', 'N', 'N', 'N', 'N', 'N', 'N'),
(9, 12, 'Y', 'N', 'N', 'N', 'N', 'N', 'N', 'N'),
(10, 13, 'Y', 'N', 'N', 'N', 'Y', 'N', 'N', 'Y'),
(11, 14, 'Y', 'N', 'N', 'N', 'Y', 'N', 'N', 'Y'),
(12, 15, 'Y', 'N', 'N', 'N', 'N', 'N', 'N', 'N'),
(13, 16, 'Y', 'N', 'N', 'N', 'Y', 'N', 'N', 'Y'),
(14, 17, 'Y', 'N', 'N', 'N', 'Y', 'N', 'N', 'Y'),
(15, 18, 'Y', 'N', 'N', 'N', 'Y', 'N', 'N', 'Y'),
(16, 19, 'Y', 'N', 'N', 'N', 'Y', 'N', 'N', 'Y');

-- --------------------------------------------------------

--
-- Table structure for table `t_settings`
--

CREATE TABLE `t_settings` (
  `id` int(11) NOT NULL,
  `key` varchar(50) NOT NULL,
  `val` text NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `status` enum('Y','N') NOT NULL DEFAULT 'Y',
  `order` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `t_settings`
--

INSERT INTO `t_settings` (`id`, `key`, `val`, `deskripsi`, `status`, `order`) VALUES
(1, 'toggleNavbar', 'nav-md', NULL, 'Y', 7),
(2, 'copyright', 'Badan Kepegawaian dan Pengembangan Sumber Daya Manusia', 'Hak cipta aplikasi', 'Y', 6),
(3, 'version_app', 'App Version. 1.0', 'Versi Major dan Minor Update', 'Y', 5),
(4, 'FooterFix', 'footer_fixed', NULL, 'N', 4),
(5, 'TopBarFix', 'sticky-top', NULL, 'N', 3),
(10, 'APPName', 'SIMEV', 'nama aplikasi', 'Y', 1),
(11, 'APPDescription', 'Penyelesaian laporan anggaran kantor lebih efektif, fleksibel dan efisien terhadap penyesuaian realisasi anggaran saat ini.', 'deskripsi aplikasi', 'Y', 2),
(12, 'SideBarFix', 'menu_fixed', 'menu mengambang, mengikuti scrollbar', 'Y', 8),
(13, 'APPLogo', 'logo.png', 'logo aplikasi', 'Y', 1);

-- --------------------------------------------------------

--
-- Table structure for table `t_users`
--

CREATE TABLE `t_users` (
  `id` int(11) NOT NULL,
  `fid_unor` int(11) NOT NULL,
  `fid_part` int(11) DEFAULT NULL,
  `pic` varchar(100) NOT NULL,
  `nama` varchar(80) NOT NULL,
  `username` varchar(50) NOT NULL,
  `nip` varchar(20) DEFAULT NULL,
  `nohp` varchar(12) DEFAULT NULL,
  `password` varchar(50) NOT NULL,
  `role` enum('SUPER_ADMIN','SUPER_USER','ADMIN','USER','VERIFICATOR') NOT NULL DEFAULT 'USER',
  `jobdesk` text NOT NULL,
  `is_block` enum('Y','N') NOT NULL DEFAULT 'Y',
  `is_restricted` enum('Y','N') NOT NULL DEFAULT 'N',
  `check_out` datetime DEFAULT NULL,
  `check_in` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `t_users`
--

INSERT INTO `t_users` (`id`, `fid_unor`, `fid_part`, `pic`, `nama`, `username`, `nip`, `nohp`, `password`, `role`, `jobdesk`, `is_block`, `is_restricted`, `check_out`, `check_in`, `created_at`) VALUES
(3, 1, 2, 'ppik-d0FLTjcxR05LaTRUNDlHc0pQMmtPdz09.png', 'M. NOR SEPUTRA, S.AP', 'redzone', '6311042705990001', '082151815132', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'SUPER_ADMIN', 'Putra sebagai super admin', 'N', 'N', '2024-01-22 14:58:42', '2024-01-22 14:23:51', '2021-11-10 16:51:34'),
(4, 1, 3, 'psdm-NnNGODdoYTFoZ1lOSkRCbG4yc0N5dz09.png', 'WULAN GALUH PERMANI, S.M.', 'psdm', '199704122020122023', '082151815132', '7bb1b27cef98a308cc48fe19f7dffed57f8d53d5', 'USER', 'Operator Bidang Pengembangan Sumber Daya Manusia', 'N', 'N', '2024-01-22 15:14:57', '2024-01-22 15:06:22', '2021-11-11 17:52:35'),
(8, 1, 12, 'kaban.png', 'SUFRIANNOR, S.Sos, M.AP', 'kaban', '196810121989031009', '08234354535', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'SUPER_USER', 'Kepala Badan', 'N', 'N', '2023-11-05 15:13:04', '2023-11-05 15:12:58', '2023-11-05 04:29:43'),
(10, 1, 1, 'mika.png', 'MIKA AUDINI, S.M.', 'mika', '199605092022022002', '085753359786', '7bb1b27cef98a308cc48fe19f7dffed57f8d53d5', 'VERIFICATOR', 'Verifikasi SPJ', 'N', 'N', '2024-01-22 11:01:36', '2024-01-22 10:59:31', '2024-01-15 05:08:21'),
(11, 1, 3, 'psdmkabid.png', 'MUHAMMAD, S.AP', 'bidpsdm', '196901151989031006', '081349514399', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'SUPER_USER', 'Kepala Bidang PSDM', 'N', 'N', NULL, NULL, '2024-01-16 05:39:15'),
(12, 1, 1, 'abduh.png', 'M. HAFIZHULLAH ABDUH, S.A.B., M.Sos', 'abduh', '198510022006041004', '081311421495', '7bb1b27cef98a308cc48fe19f7dffed57f8d53d5', 'ADMIN', 'Sekretariat sebagai Admin', 'N', 'N', '2024-01-18 15:33:34', '2024-01-18 15:09:36', '2024-01-16 05:41:47'),
(13, 1, 11, 'mutasi.png', 'ERWAN ADITYA PUTRA, S.Tr.IP', 'mpkasn', '199809272021081001', '08215181532', '7bb1b27cef98a308cc48fe19f7dffed57f8d53d5', 'USER', 'Operator Bidang Mutasi, Promosi dan Kinerja', 'N', 'N', '2024-01-22 12:20:15', '2024-01-22 15:15:08', '2024-01-16 07:16:17'),
(14, 1, 2, 'ppik2.png', 'FITRIANI, A.Md', 'ppik', '199412242019032007', '085751683871', '7bb1b27cef98a308cc48fe19f7dffed57f8d53d5', 'USER', 'Operator Bidang PPIK', 'N', 'N', '2024-01-22 15:06:18', '2024-01-22 15:06:06', '2024-01-22 09:20:35'),
(15, 1, 2, 'bidppik.png', 'SUPRAPTO, S.Pd, MT', 'bidppik', '197103021994011001', '08115115731', '7bb1b27cef98a308cc48fe19f7dffed57f8d53d5', 'SUPER_USER', 'KEPALA BIDANG PPIK', 'N', 'N', NULL, NULL, '2024-01-22 10:23:33'),
(16, 1, 1, 'dillah.png', 'MUHAMMAD UBBAY DILLAH, S.I.Pust.', 'dillah', '6307062201900006', '082155622640', '7bb1b27cef98a308cc48fe19f7dffed57f8d53d5', 'USER', 'Operator Sekretariat', 'N', 'N', NULL, NULL, '2024-01-22 11:03:40'),
(17, 1, 1, 'yugi.png', 'YUGI TULLAH, S.Sos', 'yugi', '6311011506960001', '082251366456', '7bb1b27cef98a308cc48fe19f7dffed57f8d53d5', 'USER', 'Operator Sekretariat', 'N', 'N', NULL, NULL, '2024-01-22 11:07:56'),
(18, 1, 11, 'mpkasn2.png', 'PAHRIATI, S.Pd.AUD', 'mpkasn2', '198612202010012025', '085248791273', '7bb1b27cef98a308cc48fe19f7dffed57f8d53d5', 'USER', 'Operator Bidang Mutasi, Promosi dan Kinerja', 'N', 'N', NULL, NULL, '2024-01-22 11:12:25'),
(19, 1, 2, 'putra.png', 'M. NOR SEPUTRA, S.AP', 'putra', '6311042705990001', '082151815132', '7bb1b27cef98a308cc48fe19f7dffed57f8d53d5', 'USER', 'Operator Bidang PPIK', 'N', 'N', '2024-01-22 12:05:21', '2024-01-22 12:05:14', '2024-01-22 11:14:59');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ref_indikators`
--
ALTER TABLE `ref_indikators`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ref_kegiatans`
--
ALTER TABLE `ref_kegiatans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_TO_PROGRAM` (`fid_program`),
  ADD KEY `fid_part` (`fid_part`);

--
-- Indexes for table `ref_parts`
--
ALTER TABLE `ref_parts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `singkatan` (`singkatan`);

--
-- Indexes for table `ref_programs`
--
ALTER TABLE `ref_programs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ref_sub_kegiatans`
--
ALTER TABLE `ref_sub_kegiatans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_TO_KEGIATAN` (`fid_kegiatan`);

--
-- Indexes for table `ref_unors`
--
ALTER TABLE `ref_unors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ref_uraians`
--
ALTER TABLE `ref_uraians`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `spj`
--
ALTER TABLE `spj`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `spj_riwayat`
--
ALTER TABLE `spj_riwayat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_notify`
--
ALTER TABLE `t_notify`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_pagu`
--
ALTER TABLE `t_pagu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_privilages`
--
ALTER TABLE `t_privilages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fid_user` (`fid_user`);

--
-- Indexes for table `t_settings`
--
ALTER TABLE `t_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_users`
--
ALTER TABLE `t_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nama` (`nama`),
  ADD KEY `fid_part` (`fid_part`),
  ADD KEY `fid_unor` (`fid_unor`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ref_indikators`
--
ALTER TABLE `ref_indikators`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `ref_kegiatans`
--
ALTER TABLE `ref_kegiatans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `ref_parts`
--
ALTER TABLE `ref_parts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `ref_programs`
--
ALTER TABLE `ref_programs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `ref_sub_kegiatans`
--
ALTER TABLE `ref_sub_kegiatans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `ref_unors`
--
ALTER TABLE `ref_unors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `ref_uraians`
--
ALTER TABLE `ref_uraians`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `spj`
--
ALTER TABLE `spj`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `spj_riwayat`
--
ALTER TABLE `spj_riwayat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `t_notify`
--
ALTER TABLE `t_notify`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `t_pagu`
--
ALTER TABLE `t_pagu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `t_privilages`
--
ALTER TABLE `t_privilages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `t_settings`
--
ALTER TABLE `t_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `t_users`
--
ALTER TABLE `t_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ref_kegiatans`
--
ALTER TABLE `ref_kegiatans`
  ADD CONSTRAINT `FK_TO_PROGRAM` FOREIGN KEY (`fid_program`) REFERENCES `ref_programs` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `ref_sub_kegiatans`
--
ALTER TABLE `ref_sub_kegiatans`
  ADD CONSTRAINT `FK_TO_KEGIATAN` FOREIGN KEY (`fid_kegiatan`) REFERENCES `ref_kegiatans` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `t_privilages`
--
ALTER TABLE `t_privilages`
  ADD CONSTRAINT `fk_to_users` FOREIGN KEY (`fid_user`) REFERENCES `t_users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `t_users`
--
ALTER TABLE `t_users`
  ADD CONSTRAINT `FK_TO_PART` FOREIGN KEY (`fid_part`) REFERENCES `ref_parts` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;