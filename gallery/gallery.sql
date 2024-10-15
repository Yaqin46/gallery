-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 15 Okt 2024 pada 01.14
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
-- Database: `gallery`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `album`
--

CREATE TABLE `album` (
  `AlbumID` int(11) NOT NULL,
  `NamaAlbum` varchar(255) NOT NULL,
  `Deskripsi` text NOT NULL,
  `TanggalDibuat` date NOT NULL,
  `UserID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `album`
--

INSERT INTO `album` (`AlbumID`, `NamaAlbum`, `Deskripsi`, `TanggalDibuat`, `UserID`) VALUES
(1, 'Milan', 'Klub Italy', '0000-00-00', 6);

-- --------------------------------------------------------

--
-- Struktur dari tabel `foto`
--

CREATE TABLE `foto` (
  `FotoID` int(11) NOT NULL,
  `JudulFoto` varchar(255) NOT NULL,
  `DeskripsiFoto` text NOT NULL,
  `TanggalUnggah` date NOT NULL,
  `LokasiFile` varchar(255) NOT NULL,
  `AlbumID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `jumlah_like` int(11) NOT NULL,
  `liked_by` text NOT NULL,
  `kategori` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `foto`
--

INSERT INTO `foto` (`FotoID`, `JudulFoto`, `DeskripsiFoto`, `TanggalUnggah`, `LokasiFile`, `AlbumID`, `UserID`, `jumlah_like`, `liked_by`, `kategori`) VALUES
(6, 'Cristiano Ronaldo', '\"The Young The Master\"', '0000-00-00', 'cr7b2.jfif', 0, 0, 7, '4', 'Legenda'),
(7, 'Real Madrid', 'Real Madrid C. F (Club de Fútbol)', '0000-00-00', 'Real_Madrid_CF.svg', 0, 0, 2, '', 'Real Madrid'),
(8, 'Barcelona', 'FCB (Fútbol Club Barcelona)', '0000-00-00', 'FC_Barcelona_(crest).svg.png', 0, 0, 1, '', 'Barcelona'),
(9, 'Indonesia', 'Timnas Indonesia', '0000-00-00', 'timnas-indonesia_169.jpeg', 0, 0, 3, '', 'Negara'),
(10, 'Spanyol', 'Espanol National Team', '0000-00-00', 'timnas-spanyol-2_169.jpeg', 0, 0, 0, '', 'Negara'),
(14, 'Paolo Maldini', 'Italy Legends', '2024-10-14', '670d384834a7f4.39654521.jpg', 1, 0, 1, ',6', '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `komentarfoto`
--

CREATE TABLE `komentarfoto` (
  `KomentarID` int(11) NOT NULL,
  `FotoID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `IsiKomentar` text NOT NULL,
  `TanggalKomentar` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `komentarfoto`
--

INSERT INTO `komentarfoto` (`KomentarID`, `FotoID`, `UserID`, `IsiKomentar`, `TanggalKomentar`) VALUES
(1, 2, 5, 'gg', '2024-10-10'),
(2, 6, 6, 'p', '2024-10-14'),
(3, 6, 6, 'konsol\r\n', '2024-10-14'),
(4, 14, 6, 'Yolo', '2024-10-14');

-- --------------------------------------------------------

--
-- Struktur dari tabel `likefoto`
--

CREATE TABLE `likefoto` (
  `LikeID` int(11) NOT NULL,
  `FotoID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `TanggalLike` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `UserID` int(11) NOT NULL,
  `Username` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `NamaLengkap` varchar(255) NOT NULL,
  `Alamat` text NOT NULL,
  `role` enum('user','admin','','') NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`UserID`, `Username`, `Password`, `Email`, `NamaLengkap`, `Alamat`, `role`) VALUES
(3, 'Yaqings46', '$2y$10$87YZAlKDAXPlUnBoXcvbKeD26iTWzsMfYXKwuySBfTBUMfMWbQOoa', 'nurruwwyqinn321@gmail.com', 'Nurul Yaqinnsu', 'Niggeria', 'user'),
(4, 'Naufys1', '$2y$10$ZBFuYdBPQWCZZwx9ZPnxRu1LJ9/GSfiEUqhKvumwEHqkmDzufVzk.', 'naufsyfshgre2@gmail.com', 'NaufyFresh', 'Niggss', 'admin'),
(5, 'Yaqeens1', '$2y$10$pUxd8c4kTOYCT5O0fvFYB.jYgiiUFCXyb/8wwJj3eSRgGvfin1Fq.', 'nuryaqinssg123@gmail.com', 'Muhammad Qins', 'Ningsa', 'admin'),
(6, 'NurYaqin1', '$2y$10$nVNR.jHH7sF/b8k0sCLZf.Dj.2TyDlwRVD8UFpkGw5ZSAWSLXGfPW', 'nuryaqinssggs46@gmail.com', 'Muhammad YQins', 'Nungsa', 'user'),
(7, 'Yaqings467', '$2y$10$zEbJmvS1dt83Pg.Ybkrl0.Uuny8WCV4zSrFQ9Ngmz5oK.vTetgbqK', 'nuryaqinsgggsgs123@gmail.com', 'Muammad sQinss', 'Ningsa', 'user');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `album`
--
ALTER TABLE `album`
  ADD PRIMARY KEY (`AlbumID`);

--
-- Indeks untuk tabel `foto`
--
ALTER TABLE `foto`
  ADD PRIMARY KEY (`FotoID`);

--
-- Indeks untuk tabel `komentarfoto`
--
ALTER TABLE `komentarfoto`
  ADD PRIMARY KEY (`KomentarID`);

--
-- Indeks untuk tabel `likefoto`
--
ALTER TABLE `likefoto`
  ADD PRIMARY KEY (`UserID`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`UserID`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `album`
--
ALTER TABLE `album`
  MODIFY `AlbumID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `foto`
--
ALTER TABLE `foto`
  MODIFY `FotoID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT untuk tabel `komentarfoto`
--
ALTER TABLE `komentarfoto`
  MODIFY `KomentarID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
