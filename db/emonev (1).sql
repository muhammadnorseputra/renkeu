-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 12, 2024 at 01:23 AM
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
  `fid_sub_kegiatan` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `ref_indikators`
--

INSERT INTO `ref_indikators` (`id`, `nama`, `fid_program`, `fid_kegiatan`, `fid_sub_kegiatan`) VALUES
(10, 'Persentase Penempatan Pegawai sesuai SOP (sesuai Pohon Kinerja)', NULL, NULL, 33),
(113, 'Persentase Pegawai yang Memenuhi Kualifikasi dan Kompetensi Minimal (aspek kompetensi manajerial, teknis, dan fungsional - di luar tenaga pendidik dan tenaga kesehatan)', NULL, 38, NULL),
(116, 'Persentase Penyelenggaraan Layanan Administrasi dan Sertifikasi Kompetensi sesuai SOP (sesuai Pohon Kinerja)', NULL, NULL, 32),
(117, 'Jumlah Dokumen Hasil Pengelolaan Administrasi Diklat dan Sertifikasi ASN (sesuai Kepmendagri)', NULL, NULL, 32),
(118, 'Persentase Jabatan Pimpinan dan Administrasi yang Terisi (pembatasan pada jabatan Eselon II, III, dan IV)', NULL, 39, NULL),
(119, 'Persentase Jabatan Fungsional yang Terisi sesuai Prioritas Aktual Reformasi Birokrasi', NULL, 39, NULL),
(120, 'Jumlah Dokumen Hasil Pelaksanaan Mutasi Jabatan Pimpinan Tinggi, Jabatan Administrasi, Jabatan Pelaksana dan Mutasi ASN antar Daerah (sesuai Kepmendagri)', NULL, NULL, 33),
(121, 'Persentase Kenaikan Pangkat Pegawai Tepat Waktu sesuai SOP (sesuai Pohon Kinerja)', NULL, NULL, 38),
(122, 'Jumlah Dokumen Pengelolaan Kenaikan Pangkat ASN (sesuai Kepmendagri)', NULL, NULL, 38),
(124, 'Nilai SAKIP Perangkat Daerah', 37, NULL, NULL),
(131, 'Nilai Implementasi Manajemen Talenta Pegawai ASN', 39, NULL, NULL);

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
(38, 3, 39, '5.03.02.2.03', 'Pengembangan Kompetensi ASN'),
(39, 11, 37, '5.03.02.2.02', 'Mutasi dan Promosi ASN');

-- --------------------------------------------------------

--
-- Table structure for table `ref_parts`
--

CREATE TABLE `ref_parts` (
  `id` int(11) NOT NULL,
  `fid_program` int(11) DEFAULT NULL,
  `nama` varchar(80) NOT NULL,
  `singkatan` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `ref_parts`
--

INSERT INTO `ref_parts` (`id`, `fid_program`, `nama`, `singkatan`) VALUES
(1, 37, 'SEKRETARIAT', 'SEKRETARIAT'),
(2, 37, 'PENGADAAN, PEMBERHENTIAN, INFORMASI KEPEGAWAIAN', 'PPIK'),
(3, 39, 'PENGEMBANGAN SUMBER DAYA MANUSIA', 'PSDM'),
(11, 37, 'MUTASI, PROMOSI DAN KINERJA', 'MPK'),
(12, NULL, 'KEPALA BADAN', 'KABAN');

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
(37, 1, '5.03.02', 'PROGRAM PENUNJANG URUSAN PEMERINTAHAN DAERAH KABUPATEN/KOTA'),
(39, 1, '5.04.02', 'PROGRAM PENGEMBANGAN SUMBER DAYA MANUSIA');

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
(33, 39, '5.03.02.2.02.0001', 'Pengelolaan Mutasi ASN'),
(38, 39, '5.03.02.2.02.0002', 'Pengelolaan Kenaikan Pangkat ASN');

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
(8, 38, 32, '5.1.02.01.01.00521', 'Belanja Makanan dan Minuman Rapat'),
(9, 38, 32, '5.1.02.02.01.0026', 'Belanja Jasa Tenaga Administrasi'),
(10, 38, 32, '5.1.02.02.12.0001', 'Belanja Kursus Singkat/Pelatihan'),
(12, 39, 33, '5.1.02.01.01.0052', 'Belanja Makanan dan Minuman Rapat'),
(13, 39, 33, '5.1.02.02.01.0003', 'Honorarium Narasumber atau Pembahas, Moderator, Pembawa Acara, dan Panitia'),
(16, 39, 38, '5.1.02.01.01.0026', 'Belanja Alat/Bahan untuk Kegiatan Kantor- Bahan Cetak');

-- --------------------------------------------------------

--
-- Table structure for table `spj`
--

CREATE TABLE `spj` (
  `id` int(11) NOT NULL,
  `token` varchar(40) NOT NULL,
  `fid_periode` int(11) NOT NULL,
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

--
-- Dumping data for table `spj`
--

INSERT INTO `spj` (`id`, `token`, `fid_periode`, `fid_part`, `fid_program`, `fid_kegiatan`, `fid_sub_kegiatan`, `fid_uraian`, `koderek`, `nomor_pembukuan`, `bulan`, `tahun`, `tanggal_pembukuan`, `jumlah`, `uraian`, `is_status`, `is_realisasi`, `catatan`, `approve_by`, `approve_at`, `entri_at`, `entri_by`, `entri_by_part`, `verify_at`, `verify_by`, `berkas_file`, `berkas_link`) VALUES
(45, 'WYRkF5ksHl8uSp6LKS', 1, 3, 39, 38, 32, 9, '5.03.02.2.03.5.03.02.2.03.0003.5.1.02.02.01.0026', '001', '02', 2024, '2008-02-20', '1500000', 'Belanja Jasa Tenaga Administrasi', 'SELESAI', 'LS', '', 'abduh', '2024-02-10 06:42:35', '2024-02-08 16:31:58', 'psdm', 3, '2024-02-10 06:42:02', 'mika', '', 'https://www.google.com'),
(46, 'R8UVJ3xv1oJK4OTFOw', 1, 11, 37, 39, 33, 13, '5.03.02.2.02.5.03.02.2.02.0001.5.1.02.02.01.0003', '001', '01', 2024, '2024-02-09', '8500000', 'Honor Narasumber ASN', 'SELESAI', 'LS', '', 'abduh', '2024-02-09 04:09:54', '2024-02-09 04:08:50', 'mpkasn', 11, '2024-02-09 04:09:32', 'mika', '', 'https://www.google.com'),
(49, 'xhiXAIt4FAH8hatfOh', 1, 11, 37, 39, 33, 12, '5.03.02.2.02.5.03.02.2.02.0001.5.1.02.01.01.0052', '001', '02', 2024, '2024-02-09', '1000000', 'test', 'SELESAI', 'GU', '', 'abduh', '2024-02-09 14:04:10', '2024-02-09 14:00:32', 'mpkasn', 11, '2024-02-09 14:03:08', 'mika', '', 'https://www.google.com'),
(50, 'WHwQpakdGpJHtJo8MV', 1, 11, 37, 39, 38, 16, '5.03.02.2.02.5.03.02.2.02.0002.5.1.02.01.01.0026', '001', '02', 2024, '2024-02-09', '500000', 'test 2', 'SELESAI', 'LS', '', 'abduh', '2024-02-09 14:03:36', '2024-02-09 14:01:47', 'mpkasn', 11, '2024-02-09 14:03:18', 'mika', '', 'https://www.google.com'),
(51, 'HP1bLoMsUOlSXwrAyA', 1, 11, 37, 39, 33, 12, '5.03.02.2.02.5.03.02.2.02.0001.5.1.02.01.01.0052', '', '02', 2024, '0000-00-00', '1000000', 'tes', 'SELESAI_TMS', '', 'KADA SESUAI MISALNYA', 'abduh', '2024-02-10 05:16:33', '2024-02-10 05:10:48', 'mpkasn', 11, '2024-02-10 05:16:21', 'abduh', '', 'abc');

-- --------------------------------------------------------

--
-- Table structure for table `spj_riwayat`
--

CREATE TABLE `spj_riwayat` (
  `id` int(11) NOT NULL,
  `token` varchar(40) NOT NULL,
  `fid_periode` int(11) NOT NULL,
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

--
-- Dumping data for table `spj_riwayat`
--

INSERT INTO `spj_riwayat` (`id`, `token`, `fid_periode`, `nama_part`, `nama_program`, `nama_kegiatan`, `nama_sub_kegiatan`, `nama_uraian`, `kode_program`, `kode_kegiatan`, `kode_sub_kegiatan`, `kode_uraian`, `koderek`, `nomor_pembukuan`, `bulan`, `tahun`, `tanggal_pembukuan`, `jumlah`, `uraian`, `is_status`, `is_realisasi`, `catatan`, `approve_by`, `approve_at`, `entri_at`, `entri_by`, `entri_by_part`, `verify_at`, `verify_by`, `berkas_file`, `berkas_link`) VALUES
(2, 'R8UVJ3xv1oJK4OTFOw', 1, 'MUTASI, PROMOSI DAN KINERJA', 'PROGRAM KEPEGAWAIAN DAERAH', 'Mutasi dan Promosi ASN', 'Pengelolaan Mutasi ASN', 'Honorarium Narasumber atau Pembahas, Moderator, Pembawa Acara, dan Panitia', '5.03.02', '5.03.02.2.02', '5.03.02.2.02.0001', '5.1.02.02.01.0003', '5.03.02.2.02.5.03.02.2.02.0001.5.1.02.02.01.0003', '001', '01', 2024, '2024-02-09', '8500000', 'Honor Narasumber ASN', 'APPROVE', 'LS', '', 'abduh', '2024-02-09 04:09:54', '2024-02-09 04:08:50', 'mpkasn', 11, '2024-02-09 04:09:32', 'mika', '', 'https://www.google.com'),
(3, 'WHwQpakdGpJHtJo8MV', 1, 'MUTASI, PROMOSI DAN KINERJA', 'PROGRAM KEPEGAWAIAN DAERAH', 'Mutasi dan Promosi ASN', 'Pengelolaan Kenaikan Pangkat ASN', 'Belanja Alat/Bahan untuk Kegiatan Kantor- Bahan Cetak', '5.03.02', '5.03.02.2.02', '5.03.02.2.02.0002', '5.1.02.01.01.0026', '5.03.02.2.02.5.03.02.2.02.0002.5.1.02.01.01.0026', '001', '02', 2024, '2024-02-09', '500000', 'test 2', 'APPROVE', 'LS', '', 'abduh', '2024-02-09 14:03:36', '2024-02-09 14:01:47', 'mpkasn', 11, '2024-02-09 14:03:18', 'mika', '', 'https://www.google.com'),
(4, 'xhiXAIt4FAH8hatfOh', 1, 'MUTASI, PROMOSI DAN KINERJA', 'PROGRAM KEPEGAWAIAN DAERAH', 'Mutasi dan Promosi ASN', 'Pengelolaan Mutasi ASN', 'Belanja Makanan dan Minuman Rapat', '5.03.02', '5.03.02.2.02', '5.03.02.2.02.0001', '5.1.02.01.01.0052', '5.03.02.2.02.5.03.02.2.02.0001.5.1.02.01.01.0052', '001', '02', 2024, '2024-02-09', '1000000', 'test', 'APPROVE', 'GU', '', 'abduh', '2024-02-09 14:04:10', '2024-02-09 14:00:32', 'mpkasn', 11, '2024-02-09 14:03:08', 'mika', '', 'https://www.google.com'),
(5, 'HP1bLoMsUOlSXwrAyA', 1, 'MUTASI, PROMOSI DAN KINERJA', 'PROGRAM KEPEGAWAIAN DAERAH', 'Mutasi dan Promosi ASN', 'Pengelolaan Mutasi ASN', 'Belanja Makanan dan Minuman Rapat', '5.03.02', '5.03.02.2.02', '5.03.02.2.02.0001', '5.1.02.01.01.0052', '5.03.02.2.02.5.03.02.2.02.0001.5.1.02.01.01.0052', '', '02', 2024, '0000-00-00', '1000000', 'tes', 'TMS', '', 'KADA SESUAI MISALNYA', '', '0000-00-00 00:00:00', '2024-02-10 05:10:48', 'mpkasn', 11, '2024-02-10 05:16:21', 'abduh', '', 'abc'),
(6, 'WYRkF5ksHl8uSp6LKS', 1, 'PENGEMBANGAN SUMBER DAYA MANUSIA', 'PROGRAM PENGEMBANGAN SUMBER DAYA MANUSIA', 'Pengembangan Kompetensi ASN', 'Pengeloaan Administrasi Diklat dan Sertifikasi ASN', 'Belanja Jasa Tenaga Administrasi', '5.04.02', '5.03.02.2.03', '5.03.02.2.03.0003', '5.1.02.02.01.0026', '5.03.02.2.03.5.03.02.2.03.0003.5.1.02.02.01.0026', '001', '02', 2024, '2008-02-20', '1500000', 'Belanja Jasa Tenaga Administrasi', 'APPROVE', 'LS', '', 'abduh', '2024-02-10 06:42:35', '2024-02-08 16:31:58', 'psdm', 3, '2024-02-10 06:42:02', 'mika', '', 'https://www.google.com');

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
(22, 'SUCCESS', '<i class=\"fa fa-exclamation-circle\"></i>', 1, 'GLOBAL', 'assalamualaikum bapak/ibu, untuk cetak laporan sudah bisa, silahkan cek inbox masing - masing PIC : <a href=\"/emonev/app/inbox\" class=\"btn btn-sm btn-success\">Download</a>', 'N', '2023-10-26 18:51:13');

-- --------------------------------------------------------

--
-- Table structure for table `t_pagu`
--

CREATE TABLE `t_pagu` (
  `id` int(11) NOT NULL,
  `fid_part` int(11) DEFAULT NULL,
  `fid_uraian` int(11) DEFAULT NULL,
  `total_pagu_awal` varchar(20) NOT NULL,
  `tahun` year(4) NOT NULL,
  `created_at` datetime NOT NULL,
  `created_by` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `t_pagu`
--

INSERT INTO `t_pagu` (`id`, `fid_part`, `fid_uraian`, `total_pagu_awal`, `tahun`, `created_at`, `created_by`) VALUES
(3, 3, 7, '5000000', 2024, '2024-01-21 12:26:11', 'psdm'),
(4, 3, 8, '28000000', 2024, '2024-01-21 12:26:24', 'psdm'),
(5, 3, 10, '104000000', 2024, '2024-01-21 12:44:32', 'psdm'),
(11, 11, 16, '1000000', 2024, '2024-01-31 10:41:38', 'mpkasn'),
(12, 11, 12, '9000000', 2024, '2024-02-07 11:54:09', 'mpkasn'),
(13, 11, 13, '18000000', 2024, '2024-02-07 13:26:39', 'mpkasn'),
(14, 3, 9, '18000000', 2024, '2024-02-08 16:35:40', 'psdm');

-- --------------------------------------------------------

--
-- Table structure for table `t_periode`
--

CREATE TABLE `t_periode` (
  `id` int(11) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `is_open` enum('Y','N') NOT NULL DEFAULT 'N',
  `tgl_mulai` date NOT NULL,
  `tgl_selesai` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `t_periode`
--

INSERT INTO `t_periode` (`id`, `nama`, `is_open`, `tgl_mulai`, `tgl_selesai`) VALUES
(1, 'TRIWULAN I', 'Y', '2024-01-01', '2024-03-31'),
(2, 'TRIWULAN II', 'N', '2024-04-01', '2024-06-30'),
(3, 'TRIWULAN III', 'N', '2024-07-01', '2024-09-30'),
(4, 'TRIWULAN IV', 'N', '2024-10-01', '2024-12-31');

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
  `priv_spj` enum('Y','N') NOT NULL DEFAULT 'N',
  `priv_riwayat_spj` enum('Y','N') NOT NULL DEFAULT 'N',
  `priv_bukujaga` enum('Y','N') NOT NULL DEFAULT 'N',
  `priv_anggarankinerja` enum('Y','N') NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `t_privilages`
--

INSERT INTO `t_privilages` (`id`, `fid_user`, `priv_default`, `priv_users`, `priv_notify`, `priv_settings`, `priv_programs`, `priv_verifikasi`, `priv_approve`, `priv_spj`, `priv_riwayat_spj`, `priv_bukujaga`, `priv_anggarankinerja`) VALUES
(1, 3, 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'N', 'N', 'N', 'N', 'N'),
(3, 4, 'Y', 'N', 'N', 'N', 'Y', 'N', 'N', 'Y', 'Y', 'Y', 'Y'),
(5, 8, 'Y', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'Y', 'Y'),
(7, 10, 'Y', 'N', 'N', 'N', 'Y', 'N', 'N', 'Y', 'Y', 'Y', 'N'),
(8, 11, 'Y', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'Y', 'Y'),
(9, 12, 'Y', 'Y', 'Y', 'N', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y'),
(10, 13, 'Y', 'N', 'N', 'N', 'Y', 'N', 'N', 'Y', 'Y', 'Y', 'Y'),
(11, 14, 'Y', 'N', 'N', 'N', 'Y', 'N', 'N', 'Y', 'Y', 'Y', 'Y'),
(12, 15, 'Y', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'Y', 'Y'),
(13, 16, 'Y', 'N', 'N', 'N', 'Y', 'N', 'N', 'Y', 'Y', 'Y', 'Y'),
(14, 17, 'Y', 'N', 'N', 'N', 'Y', 'N', 'N', 'Y', 'Y', 'Y', 'Y'),
(15, 18, 'Y', 'N', 'N', 'N', 'Y', 'N', 'N', 'N', 'Y', 'N', 'N'),
(16, 19, 'Y', 'N', 'N', 'N', 'Y', 'N', 'N', 'Y', 'Y', 'Y', 'Y');

-- --------------------------------------------------------

--
-- Table structure for table `t_realisasi`
--

CREATE TABLE `t_realisasi` (
  `id` int(11) NOT NULL,
  `fid_indikator` int(11) NOT NULL,
  `fid_periode` int(11) NOT NULL,
  `persentase` varchar(8) NOT NULL,
  `eviden` int(11) NOT NULL,
  `eviden_jenis` varchar(50) NOT NULL,
  `faktor_pendorong` text DEFAULT NULL,
  `faktor_penghambat` text DEFAULT NULL,
  `tindak_lanjut` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `t_realisasi`
--

INSERT INTO `t_realisasi` (`id`, `fid_indikator`, `fid_periode`, `persentase`, `eviden`, `eviden_jenis`, `faktor_pendorong`, `faktor_penghambat`, `tindak_lanjut`) VALUES
(6, 10, 1, '30', 0, '0', 'Tersedianya anggaran', '-', '-'),
(7, 120, 1, '0', 1, 'Dokumen', '-', '-', '-'),
(8, 121, 1, '80', 0, '0', '-', '-', '-'),
(9, 122, 1, '0', 3, 'Dokumen', '-', '-', '-'),
(11, 119, 1, '10', 0, '0', NULL, NULL, NULL),
(12, 118, 1, '50', 0, '0', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `t_settings`
--

CREATE TABLE `t_settings` (
  `id` int(11) NOT NULL,
  `key` varchar(50) NOT NULL,
  `val` longtext NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `status` enum('Y','N') NOT NULL DEFAULT 'Y',
  `order` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `t_settings`
--

INSERT INTO `t_settings` (`id`, `key`, `val`, `deskripsi`, `status`, `order`) VALUES
(1, 'toggleNavbar', 'nav-md', NULL, 'Y', 7),
(2, 'copyright', 'Badan Kepegawaian dan PengembanganSumber Daya Manusia', 'Hak cipta aplikasi', 'Y', 6),
(3, 'version_app', 'App Version. 1.0', 'Versi Major dan Minor Update', 'Y', 5),
(4, 'FooterFix', 'footer_fixed', NULL, 'N', 4),
(5, 'TopBarFix', 'sticky-top', NULL, 'N', 3),
(10, 'APPName', 'SIMEV', 'nama aplikasi', 'Y', 1),
(11, 'APPDescription', 'Penyelesaian laporan anggaran kantor lebih efektif, fleksibel dan efisien terhadap penyesuaian realisasi anggaran saat ini.', 'deskripsi aplikasi', 'Y', 2),
(12, 'SideBarFix', 'menu_fixed', 'menu mengambang, mengikuti scrollbar', 'Y', 8),
(13, 'APPLogo', 'logo.png', 'logo aplikasi', 'Y', 1);

-- --------------------------------------------------------

--
-- Table structure for table `t_target`
--

CREATE TABLE `t_target` (
  `id` int(11) NOT NULL,
  `fid_indikator` int(11) NOT NULL,
  `persentase` varchar(8) NOT NULL,
  `eviden_jumlah` int(11) NOT NULL,
  `eviden_jenis` varchar(50) NOT NULL,
  `tahun` year(4) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(50) NOT NULL,
  `update_at` datetime NOT NULL,
  `update_by` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `t_target`
--

INSERT INTO `t_target` (`id`, `fid_indikator`, `persentase`, `eviden_jumlah`, `eviden_jenis`, `tahun`, `created_at`, `created_by`, `update_at`, `update_by`) VALUES
(1, 122, '0', 5, 'Dokumen', 2024, '2024-02-07 19:18:07', 'mpkasn', '2024-02-09 05:03:50', 'mpkasn'),
(2, 121, '80', 0, '0', 2024, '2024-02-07 19:19:55', 'mpkasn', '2024-02-09 05:03:47', 'mpkasn'),
(3, 120, '0', 1, 'Dokumen', 2024, '2024-02-07 19:20:09', 'mpkasn', '2024-02-09 05:03:45', 'mpkasn'),
(4, 10, '100', 0, '0', 2024, '2024-02-07 19:20:14', 'mpkasn', '2024-02-09 05:03:41', 'mpkasn'),
(5, 119, '100', 0, '0', 2024, '2024-02-07 19:20:19', 'mpkasn', '2024-02-09 05:03:39', 'mpkasn'),
(6, 118, '100', 0, '0', 2024, '2024-02-07 19:20:22', 'mpkasn', '2024-02-09 05:03:36', 'mpkasn'),
(7, 124, '67', 0, '0', 2024, '2024-02-07 19:20:36', 'abduh', '2024-02-10 06:39:27', 'abduh'),
(8, 116, '50', 0, '0', 2024, '2024-02-07 20:37:50', 'abduh', '0000-00-00 00:00:00', ''),
(9, 117, '0', 2, 'Dokumen', 2024, '2024-02-07 20:37:57', 'abduh', '0000-00-00 00:00:00', ''),
(10, 113, '100', 0, '0', 2024, '2024-02-07 20:38:02', 'abduh', '0000-00-00 00:00:00', '');

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
(3, 1, 2, 'ppik-d0FLTjcxR05LaTRUNDlHc0pQMmtPdz09.png', 'M. NOR SEPUTRA, S.AP', 'redzone', '6311042705990001', '082151815132', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'SUPER_ADMIN', 'Putra sebagai super admin', 'N', 'N', '2024-02-11 15:13:20', '2024-02-11 15:12:34', '2021-11-10 16:51:34'),
(4, 1, 3, 'psdm-NnNGODdoYTFoZ1lOSkRCbG4yc0N5dz09.png', 'WULAN GALUH PERMANI, S.M.', 'psdm', '199704122020122023', '082151815132', '7bb1b27cef98a308cc48fe19f7dffed57f8d53d5', 'USER', 'Operator Bidang Pengembangan Sumber Daya Manusia', 'N', 'N', '2024-02-10 12:01:55', '2024-02-10 12:01:53', '2021-11-11 17:52:35'),
(8, 1, 12, 'kaban.png', 'SUFRIANNOR, S.Sos, M.AP', 'kaban', '196810121989031009', '08234354535', '5ff05e09c5d5e686fef56b32188d3086ed380717', 'SUPER_USER', 'Kepala Badan', 'N', 'N', '2024-02-11 15:12:08', '2024-02-11 15:59:04', '2023-11-05 04:29:43'),
(10, 1, 1, 'mika.png', 'MIKA AUDINI, S.M.', 'mika', '199605092022022002', '085753359786', '7bb1b27cef98a308cc48fe19f7dffed57f8d53d5', 'VERIFICATOR', 'Verifikasi SPJ', 'N', 'N', '2024-02-11 07:50:06', '2024-02-11 07:59:04', '2024-01-15 05:08:21'),
(11, 1, 3, 'psdmkabid.png', 'MUHAMMAD, S.AP', 'bidpsdm', '196901151989031006', '081349514399', '262297f85ed12c4d18ee00f487d11fc87567136d', 'SUPER_USER', 'Kepala Bidang PSDM', 'N', 'N', '2024-02-11 15:59:01', '2024-02-11 15:56:14', '2024-01-16 05:39:15'),
(12, 1, 1, 'abduh.png', 'M. HAFIZHULLAH ABDUH, S.A.B., M.Sos', 'abduh', '198510022006041004', '081311421495', '7bb1b27cef98a308cc48fe19f7dffed57f8d53d5', 'ADMIN', 'Sekretariat sebagai Admin', 'N', 'N', '2024-02-11 14:52:48', '2024-02-11 14:52:36', '2024-01-16 05:41:47'),
(13, 1, 11, 'mutasi.png', 'ERWAN ADITYA PUTRA, S.Tr.IP', 'mpkasn', '199809272021081001', '08215181532', '7bb1b27cef98a308cc48fe19f7dffed57f8d53d5', 'USER', 'Operator Bidang Mutasi, Promosi dan Kinerja', 'N', 'N', '2024-02-11 15:56:05', '2024-02-11 15:52:45', '2024-01-16 07:16:17'),
(14, 1, 2, 'ppik2.png', 'FITRIANI, A.Md', 'ppik', '199412242019032007', '085751683871', '7bb1b27cef98a308cc48fe19f7dffed57f8d53d5', 'USER', 'Operator Bidang PPIK', 'N', 'N', '2024-01-22 15:06:18', '2024-01-26 07:31:03', '2024-01-22 09:20:35'),
(15, 1, 2, 'bidppik.png', 'SUPRAPTO, S.Pd, MT', 'bidppik', '197103021994011001', '08115115731', '7bb1b27cef98a308cc48fe19f7dffed57f8d53d5', 'SUPER_USER', 'KEPALA BIDANG PPIK', 'N', 'N', NULL, NULL, '2024-01-22 10:23:33'),
(16, 1, 1, 'dillah.png', 'MUHAMMAD UBBAY DILLAH, S.I.Pust.', 'dillah', '6307062201900006', '082155622640', '7bb1b27cef98a308cc48fe19f7dffed57f8d53d5', 'USER', 'Operator Sekretariat', 'N', 'N', NULL, NULL, '2024-01-22 11:03:40'),
(17, 1, 1, 'yugi.png', 'YUGI TULLAH, S.Sos', 'yugi', '6311011506960001', '082251366456', '7bb1b27cef98a308cc48fe19f7dffed57f8d53d5', 'USER', 'Operator Sekretariat', 'N', 'N', '2024-02-10 12:49:30', '2024-02-10 12:47:38', '2024-01-22 11:07:56'),
(18, 1, 11, 'mpkasn2.png', 'PAHRIATI, S.Pd.AUD', 'mpkasn2', '198612202010012025', '085248791273', '7bb1b27cef98a308cc48fe19f7dffed57f8d53d5', 'USER', 'Operator Bidang Mutasi, Promosi dan Kinerja', 'N', 'N', '2024-02-10 12:46:45', '2024-02-10 12:46:26', '2024-01-22 11:12:25'),
(19, 1, 2, 'putra.png', 'M. NOR SEPUTRA, S.AP', 'putra', '6311042705990001', '082151815132', '7bb1b27cef98a308cc48fe19f7dffed57f8d53d5', 'USER', 'Operator Bidang PPIK', 'N', 'N', '2024-02-10 06:30:45', '2024-02-10 06:27:47', '2024-01-22 11:14:59');

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
  ADD PRIMARY KEY (`id`),
  ADD KEY `token` (`token`);

--
-- Indexes for table `spj_riwayat`
--
ALTER TABLE `spj_riwayat`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_TO_SPJ` (`token`);

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
-- Indexes for table `t_periode`
--
ALTER TABLE `t_periode`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_privilages`
--
ALTER TABLE `t_privilages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fid_user` (`fid_user`);

--
-- Indexes for table `t_realisasi`
--
ALTER TABLE `t_realisasi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_settings`
--
ALTER TABLE `t_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_target`
--
ALTER TABLE `t_target`
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=133;

--
-- AUTO_INCREMENT for table `ref_kegiatans`
--
ALTER TABLE `ref_kegiatans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `ref_parts`
--
ALTER TABLE `ref_parts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `ref_programs`
--
ALTER TABLE `ref_programs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `ref_sub_kegiatans`
--
ALTER TABLE `ref_sub_kegiatans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `ref_unors`
--
ALTER TABLE `ref_unors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `ref_uraians`
--
ALTER TABLE `ref_uraians`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `spj`
--
ALTER TABLE `spj`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `spj_riwayat`
--
ALTER TABLE `spj_riwayat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `t_notify`
--
ALTER TABLE `t_notify`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `t_pagu`
--
ALTER TABLE `t_pagu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `t_periode`
--
ALTER TABLE `t_periode`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `t_privilages`
--
ALTER TABLE `t_privilages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `t_realisasi`
--
ALTER TABLE `t_realisasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `t_settings`
--
ALTER TABLE `t_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `t_target`
--
ALTER TABLE `t_target`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

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
  ADD CONSTRAINT `FK_TO_PROGRAM` FOREIGN KEY (`fid_program`) REFERENCES `ref_programs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ref_sub_kegiatans`
--
ALTER TABLE `ref_sub_kegiatans`
  ADD CONSTRAINT `FK_TO_KEGIATAN` FOREIGN KEY (`fid_kegiatan`) REFERENCES `ref_kegiatans` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `spj_riwayat`
--
ALTER TABLE `spj_riwayat`
  ADD CONSTRAINT `FK_TO_SPJ` FOREIGN KEY (`token`) REFERENCES `spj` (`token`) ON DELETE CASCADE ON UPDATE CASCADE;

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
