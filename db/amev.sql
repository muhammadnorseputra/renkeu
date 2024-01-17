-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 17, 2024 at 03:47 AM
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
-- Table structure for table `ref_kegiatans`
--

CREATE TABLE `ref_kegiatans` (
  `id` int(11) NOT NULL,
  `fid_part` int(11) DEFAULT NULL,
  `fid_program` int(11) NOT NULL,
  `kode` varchar(10) NOT NULL,
  `nama` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `ref_kegiatans`
--

INSERT INTO `ref_kegiatans` (`id`, `fid_part`, `fid_program`, `kode`, `nama`) VALUES
(28, 3, 7, '1.2.01', 'Perencanaan, Penganggaran, dan Evaluasi Kinerja Perangkat Daerah');

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
(3, 'PENDIDIKAN, PELATIHAN DAN PENGEMBANGAN KOMPETENSI', 'DIKLAT'),
(4, 'PEMBINAAN DISIPLIN PEGAWAI', 'PDP'),
(11, 'MUTASI DAN PROMOSI ASN', 'MP ASN');

-- --------------------------------------------------------

--
-- Table structure for table `ref_programs`
--

CREATE TABLE `ref_programs` (
  `id` int(11) NOT NULL,
  `fid_unor` int(11) DEFAULT NULL,
  `nama` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `ref_programs`
--

INSERT INTO `ref_programs` (`id`, `fid_unor`, `nama`) VALUES
(7, 1, 'PROGRAM PENUNJANG URUSAN PEMERINTAHAN DAERAH KABUPATEN/KOTA');

-- --------------------------------------------------------

--
-- Table structure for table `ref_sub_kegiatans`
--

CREATE TABLE `ref_sub_kegiatans` (
  `id` int(11) NOT NULL,
  `fid_kegiatan` int(11) DEFAULT NULL,
  `kode` varchar(20) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `total_pagu_before` varchar(10) DEFAULT NULL,
  `total_pagu_after` varchar(10) DEFAULT NULL,
  `total_pagu_realisasi` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `ref_sub_kegiatans`
--

INSERT INTO `ref_sub_kegiatans` (`id`, `fid_kegiatan`, `kode`, `nama`, `total_pagu_before`, `total_pagu_after`, `total_pagu_realisasi`) VALUES
(5, 28, '1.2.01.1', 'Penyusunan Dokumen Perencanaan Perangkat Daerah', NULL, NULL, NULL),
(6, 28, '1.2.01.6', 'Koordinasi dan Penyusunan Laporan Capaian Kinerja dan Ikhtisar Realisasi Kinerja SKPD', NULL, NULL, NULL),
(7, 28, '1.2.01.7', 'Evaluasi Kinerja Perangkat Daerah', NULL, NULL, NULL);

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
  `fid_sub_kegiatan` int(11) NOT NULL,
  `fid_kegiatan` int(11) NOT NULL,
  `kode` varchar(30) NOT NULL,
  `nama` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
  `koderek` varchar(40) NOT NULL,
  `nomor_pembukuan` varchar(40) NOT NULL,
  `bulan` varchar(5) NOT NULL,
  `tahun` year(4) NOT NULL,
  `tanggal_pembukuan` datetime NOT NULL,
  `jumlah` varchar(15) NOT NULL,
  `uraian` text NOT NULL,
  `is_status` enum('ENTRI','VERIFIKASI','VERIFIKASI_ADMIN','BTL','TMS','SELESAI','SELESAI_TMS','SELESAI_BTL') NOT NULL DEFAULT 'ENTRI',
  `is_realisasi` enum('LS','UP','GU','TU') NOT NULL,
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

--
-- Dumping data for table `spj`
--

INSERT INTO `spj` (`id`, `token`, `fid_part`, `fid_program`, `fid_kegiatan`, `fid_sub_kegiatan`, `fid_uraian`, `koderek`, `nomor_pembukuan`, `bulan`, `tahun`, `tanggal_pembukuan`, `jumlah`, `uraian`, `is_status`, `is_realisasi`, `catatan`, `approve_by`, `approve_at`, `entri_at`, `entri_by`, `entri_by_part`, `verify_at`, `verify_by`, `berkas_file`, `berkas_link`) VALUES
(36, 'k0PR3q1hE1TFHOmVYM', 3, 7, 28, 5, NULL, '1.2.01.1.2.01.1', '11223344', '01', 2024, '0000-00-00 00:00:00', '3200000', 'Rapat Koordinasi Penyusunan Dokument', 'VERIFIKASI_ADMIN', 'LS', '', 'abduh', '2024-01-16 07:15:03', '2024-01-16 05:44:50', 'psdm', 3, '2024-01-16 10:29:50', 'mika', '', 'https://www.youtube.com/watch?v=gXnMLo8bDEA');

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
  `kode_kegiatan` varchar(30) NOT NULL,
  `kode_sub_kegiatan` varchar(30) NOT NULL,
  `kode_uraian` varchar(100) NOT NULL,
  `koderek` varchar(40) NOT NULL,
  `nomor_pembukuan` varchar(40) NOT NULL,
  `bulan` varchar(5) NOT NULL,
  `tahun` year(4) NOT NULL,
  `tanggal_pembukuan` datetime NOT NULL,
  `jumlah` varchar(15) NOT NULL,
  `uraian` text NOT NULL,
  `is_status` enum('APPROVE','BTL','TMS') DEFAULT NULL,
  `is_realisasi` enum('LS','UP','GU','TU') NOT NULL,
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
-- Table structure for table `t_periode`
--

CREATE TABLE `t_periode` (
  `id` int(11) NOT NULL,
  `is_perubahan` enum('Y','N') NOT NULL DEFAULT 'N',
  `total_pagu_awal` varchar(20) NOT NULL,
  `total_pagu_realisasi` varchar(30) NOT NULL,
  `total_pagu_akhir` varchar(20) NOT NULL,
  `tahun` year(4) NOT NULL,
  `bulan` varchar(2) NOT NULL,
  `created_at` datetime NOT NULL,
  `created_by` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
(3, 4, 'Y', 'N', 'N', 'N', 'N', 'N', 'N', 'Y'),
(5, 8, 'Y', 'N', 'N', 'N', 'N', 'N', 'N', 'N'),
(7, 10, 'Y', 'N', 'N', 'N', 'Y', 'N', 'N', 'N'),
(8, 11, 'Y', 'N', 'N', 'N', 'N', 'N', 'N', 'N'),
(9, 12, 'Y', 'N', 'N', 'N', 'N', 'N', 'N', 'N'),
(10, 13, 'Y', 'N', 'N', 'N', 'N', 'N', 'N', 'Y');

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
(3, 1, 2, 'ppik-d0FLTjcxR05LaTRUNDlHc0pQMmtPdz09.png', 'Putrabungsu6', 'ppik', '6311042705990001', '082151815132', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'SUPER_ADMIN', 'Putra sebagai super admin', 'N', 'N', '2024-01-16 10:22:17', '2024-01-17 03:19:02', '2021-11-10 16:51:34'),
(4, 1, 3, 'psdm-NnNGODdoYTFoZ1lOSkRCbG4yc0N5dz09.png', 'Antung Rima', 'psdm', '12', '082151815132', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'USER', 'admin sebagai kabid psdm', 'N', 'N', '2024-01-16 07:17:04', '2024-01-16 07:16:52', '2021-11-11 17:52:35'),
(8, 1, NULL, 'kaban.png', 'Sufriannor', 'kaban', '123', '08234354535', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'SUPER_USER', 'Kepala Badan', 'N', 'N', '2023-11-05 15:13:04', '2023-11-05 15:12:58', '2023-11-05 04:29:43'),
(10, 1, 1, 'mika.png', 'Mika Audini', 'mika', '123', '082151815132', '8f80b44968ea7b99a59587385bb3f599630f6150', 'VERIFICATOR', 'Verifikasi Keuangan', 'N', 'N', '2024-01-16 07:14:30', '2024-01-16 10:22:19', '2024-01-15 05:08:21'),
(11, 1, 3, 'psdmkabid.png', 'Muhammad, S.Pd', 'psdmkabid', '6311042705990001', '082151815132', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'SUPER_USER', 'Kabid', 'N', 'N', NULL, NULL, '2024-01-16 05:39:15'),
(12, 1, 1, 'abduh.png', 'Abduh', 'abduh', '6311042705990001', '082151815132', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'ADMIN', 'Sekretariat Admin', 'N', 'N', '2024-01-16 07:15:19', '2024-01-16 07:14:37', '2024-01-16 05:41:47'),
(13, 1, 11, 'mutasi.png', 'Erwan', 'mutasi', '6311042705990001', '08215181532', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'USER', 'User Mutasi', 'N', 'N', '2024-01-16 07:17:17', '2024-01-16 08:31:42', '2024-01-16 07:16:17');

--
-- Indexes for dumped tables
--

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
-- AUTO_INCREMENT for table `ref_kegiatans`
--
ALTER TABLE `ref_kegiatans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `ref_parts`
--
ALTER TABLE `ref_parts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `ref_programs`
--
ALTER TABLE `ref_programs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `ref_sub_kegiatans`
--
ALTER TABLE `ref_sub_kegiatans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `ref_unors`
--
ALTER TABLE `ref_unors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `ref_uraians`
--
ALTER TABLE `ref_uraians`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `spj`
--
ALTER TABLE `spj`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `spj_riwayat`
--
ALTER TABLE `spj_riwayat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `t_notify`
--
ALTER TABLE `t_notify`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `t_privilages`
--
ALTER TABLE `t_privilages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `t_settings`
--
ALTER TABLE `t_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `t_users`
--
ALTER TABLE `t_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

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
