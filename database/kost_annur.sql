-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 11 Okt 2026 pada 10.51
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kost_annur`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `kamar`
--

CREATE TABLE `kamar` (
  `id` int(11) NOT NULL,
  `nomor` varchar(10) NOT NULL,
  `tipe` varchar(20) NOT NULL,
  `status` enum('kosong','terisi','segera_kosong') NOT NULL DEFAULT 'kosong',
  `diubah_pada` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `kamar`
--

INSERT INTO `kamar` (`id`, `nomor`, `tipe`, `status`, `diubah_pada`) VALUES
(1, '1', 'kipas_kecil', 'kosong', '2026-10-03 16:06:40'),
(2, '2', 'kipas_besar', 'terisi', '2026-10-03 16:06:40'),
(3, '3', 'ac', 'kosong', '2026-10-03 16:06:40');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pesan`
--

CREATE TABLE `pesan` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `whatsapp` varchar(30) NOT NULL,
  `isi` text NOT NULL,
  `dibuat_pada` timestamp NOT NULL DEFAULT current_timestamp(),
  `sudah_dibaca` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pesanan`
--

CREATE TABLE `pesanan` (
  `id` int(11) NOT NULL,
  `tipe_kamar` varchar(100) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `hp` varchar(20) NOT NULL,
  `status_penghuni` varchar(50) NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `durasi` int(11) NOT NULL,
  `catatan` text DEFAULT NULL,
  `total` bigint(20) NOT NULL,
  `status` enum('Baru','Dikonfirmasi','Ditolak','Selesai') NOT NULL DEFAULT 'Baru',
  `dibuat_pada` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `pesanan`
--

INSERT INTO `pesanan` (`id`, `tipe_kamar`, `nama`, `hp`, `status_penghuni`, `tanggal_mulai`, `durasi`, `catatan`, `total`, `status`, `dibuat_pada`) VALUES
(2, 'Kamar Kipas Luas (Besar)', 'misyal', '085241476627', 'Putri (Mahasiswi/Pekerja)', '2026-10-08', 1, 'oopp', 800000, 'Selesai', '2026-10-06 13:37:01'),
(3, 'Kamar Kipas Luas (Besar)', 'misyal', '085241476627', 'Putri (Mahasiswi/Pekerja)', '2026-10-08', 1, 'kzcjckzkc', 800000, 'Baru', '2026-10-07 02:19:03'),
(4, 'Kamar Kipas Reguler (Kecil)', 'iggi', '085421458875', 'Putri (Mahasiswi/Pekerja)', '2027-01-06', 1, '', 500000, 'Dikonfirmasi', '2026-10-11 08:14:54'),
(5, 'Kamar Kipas Reguler (Kecil)', 'iggi', '085421458444', 'Pasangan Suami Istri', '2027-01-06', 1, '', 500000, 'Selesai', '2026-10-11 08:20:17'),
(6, 'Kamar Kipas Reguler (Kecil)', 'iggi', '085421458454', 'Putri (Mahasiswi/Pekerja)', '2027-01-06', 1, '', 500000, 'Dikonfirmasi', '2026-10-11 08:31:35');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tipe_kamar`
--

CREATE TABLE `tipe_kamar` (
  `kode` varchar(20) NOT NULL,
  `nama` varchar(80) NOT NULL,
  `harga` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `tipe_kamar`
--

INSERT INTO `tipe_kamar` (`kode`, `nama`, `harga`) VALUES
('ac', 'Kamar AC', 1200000),
('kipas_besar', 'Kamar Kipas Luas (Besar)', 800000),
('kipas_kecil', 'Kamar Kipas Reguler (Kecil)', 500000);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `kamar`
--
ALTER TABLE `kamar`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nomor` (`nomor`),
  ADD KEY `tipe` (`tipe`);

--
-- Indeks untuk tabel `pesan`
--
ALTER TABLE `pesan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `pesanan`
--
ALTER TABLE `pesanan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tipe_kamar`
--
ALTER TABLE `tipe_kamar`
  ADD PRIMARY KEY (`kode`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `kamar`
--
ALTER TABLE `kamar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `pesan`
--
ALTER TABLE `pesan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `pesanan`
--
ALTER TABLE `pesanan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `kamar`
--
ALTER TABLE `kamar`
  ADD CONSTRAINT `kamar_ibfk_1` FOREIGN KEY (`tipe`) REFERENCES `tipe_kamar` (`kode`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
