-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 19 Jan 2026 pada 10.53
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
-- Database: `poin_pelanggaran_siswa`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `program_keahlian`
--

CREATE TABLE `program_keahlian` (
  `Id` int(11) NOT NULL,
  `Program_Keahlian` varchar(100) NOT NULL,
  `Deskripsi` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `program_keahlian`
--

INSERT INTO `program_keahlian` (`Id`, `Program_Keahlian`, `Deskripsi`) VALUES
(1, 'RPL', 'Rekayasa Perangkat Lunak'),
(2, 'DKV', 'Desain Komunikasi Visual'),
(3, 'TKJ', 'Teknik Komputer Jaringan'),
(4, 'AN', 'Animasi'),
(5, 'BD', 'Bisnis Digital');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tingkat`
--

CREATE TABLE `tingkat` (
  `Id` int(11) NOT NULL,
  `Nama_Tingkat` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tingkat`
--

INSERT INTO `tingkat` (`Id`, `Nama_Tingkat`) VALUES
(1, 'X'),
(2, 'XI'),
(3, 'XII');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tahun_ajaran`
--

CREATE TABLE `tahun_ajaran` (
  `Id` int(11) NOT NULL,
  `Tahun` varchar(20) NOT NULL,
  `Aktif` char(1) DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tahun_ajaran`
--

INSERT INTO `tahun_ajaran` (`Id`, `Tahun`, `Aktif`) VALUES
(1, '2021/2022', 'N'),
(2, '2022/2023', 'N'),
(3, '2023/2024', 'N'),
(4, '2024/2025', 'N'),
(5, '2025/2026', 'Y');

-- --------------------------------------------------------

--
-- Struktur dari tabel `guru`
--

CREATE TABLE `guru` (
  `Kode_Guru` varchar(20) NOT NULL,
  `Nama_Pengguna` varchar(100) NOT NULL,
  `Role` varchar(50) DEFAULT 'Guru',
  `Username` varchar(50) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Aktif` char(1) DEFAULT 'Y',
  `Jabatan` varchar(100) DEFAULT NULL,
  `Telp` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `guru`
--

INSERT INTO `guru` (`Kode_Guru`, `Nama_Pengguna`, `Role`, `Username`, `Password`, `Aktif`, `Jabatan`, `Telp`) VALUES
('0021.001', 'Drs. I Gusti Made Murjana,M.Pd.', 'Guru', 'made', 'B:i+}j77,£l^b;95RE0^#%U?&&x+k~2_:CYpjY|w\'^J~%NE=:n', 'Y', 'Kepala Sekolah', '081805474228'),
('0021.002', 'I Nyoman Sucana,M.Kom.', 'Guru', 'sucana', '#8,_3~[h}D!c>=k:"o)@Be2&$2WxT<}}0u58eC#DbD,=D<zjSI', 'Y', 'Waka Kurikulum', '083117769593'),
('0021.003', 'Bagus Putu Eka Wijaya,S.Kom.', 'Guru', 'guseka', '56},F}j3p09A9\';VR}$`4pi9{dn[QOIHwm(D3^&dLi;r:Ij!m[', 'Y', 'Waka Kesiswaan', '082146503026'),
('0021.004', 'Dewa Made Indra Suarmika,S.Kom.', 'Guru', 'indra', '#8,_3~[h}D!c>=k:"o)@Be2&$2WxT<}}0u58eC#DbD,=D<zjSI', 'Y', 'Waka Sarana Prasarana', '081999976038'),
('0021.005', 'I Gede Pradipta Adi Nugraha,M.Kom.', 'Guru', 'dipta', '56},F}j3p09A9\';VR}$`4pi9{dn[QOIHwm(D3^&dLi;r:Ij!m[', 'Y', 'Waka Humas', '082247033044'),
('0021.006', 'I Gede Agung Abdi Prasetya,S.Ak.', 'Guru', 'abdi', 'B:i+}j77,£l^b;95RE0^#%U?&&x+k~2_:CYpjY|w\'^J~%NE=:n', 'Y', 'Komka AN', '087862656412'),
('0021.007', 'A.A Gede Putra Dwi Artajaya,S.Si.,M.Kom.', 'Guru', 'artajaya', '56},F}j3p09A9\';VR}$`4pi9{dn[QOIHwm(D3^&dLi;r:Ij!m[', 'Y', 'Komka RPL', '082237442222'),
('0021.008', 'I Komang Arta Wijaya,M.Kom.', 'Guru', 'arta', '#8,_3~[h}D!c>=k:"o)@Be2&$2WxT<}}0u58eC#DbD,=D<zjSI', 'Y', 'Komka DKV', '085953912558'),
('0021.009', 'I Made Sastrawan Adi Putra,S.Kom.', 'Guru', 'sastra', 'B:i+}j77,£l^b;95RE0^#%U?&&x+k~2_:CYpjY|w\'^J~%NE=:n', 'Y', 'Komka TKJ', '08563769773'),
('0021.010', 'Ni Wayan Sri Arini,ST.,M.Kom.', 'Guru', 'sriarini', 'Guru12345*!', 'Y', 'Guru Mapel', '087863112233'),
('0021.011', 'I Putu Urip Sutresna Mantra,S.Kom.', 'Guru', 'urip', 'Guru12345*!', 'Y', 'Ketua Lab', '085739990443'),
('0021.012', 'Ni Wayan Rumasni,S.Pd.,M.Pd.', 'Guru', 'rum', 'Guru12345*!', 'Y', 'Guru Mapel', '087861863842'),
('0021.013', 'I Wayan Agus Wiranata,S.Pd.', 'Guru', 'wiranata', 'Guru12345*!', 'Y', 'Guru Mapel', '082291355411'),
('0021.014', 'Nyoman Hendra Adi Wijaya,S.Pd.,M.Kom.', 'Guru', 'hendra', 'Guru12345*!', 'Y', 'Guru Mapel', '082247033484'),
('0021.015', 'Dra. Ni Made Ayu Adnyani', 'Guru', 'ayuadnyani', 'Guru12345*!', 'Y', 'Guru Mapel', '08123650940'),
('0021.016', 'Nama_Pengguna', 'Guru', 'pengguna016', 'Guru12345*!', 'N', 'Guru Mapel', '083114537674'),
('0021.017', 'Nama_Pengguna', 'Guru', 'pengguna017', 'Guru12345*!', 'N', 'Guru Mapel', '085738235218'),
('0021.018', 'Putu Yenny Suryantari,S.Pd.', 'Guru', 'yenny', 'Guru12345*!', 'Y', 'Guru Mapel', '082146167817'),
('0021.019', 'I Gusti Ayu Sri Erna Dewi,SE.', 'Guru', 'erna', 'Guru12345*!', 'Y', 'Guru Mapel', '081239588346'),
('0021.020', 'Nama_Pengguna', 'Guru', 'pengguna020', 'Guru12345*!', 'N', 'Guru Mapel', '081338401856'),
('0021.021', 'Ida Ayu Indra Pratiwi,S.Sn.', 'Guru', 'dayuindra', 'Guru12345*!', 'Y', 'Guru Mapel', '085738216181'),
('0021.022', 'I Wayan Sudarsa,S.Kom.', 'Guru', 'sudarsa', 'Guru12345*!', 'Y', 'Guru Mapel', '083834969500'),
('0021.023', 'Nama_Pengguna', 'Guru', 'pengguna023', 'Guru12345*!', 'N', 'Guru Mapel', '082247033088'),
('0021.024', 'Nama_Pengguna', 'Guru', 'pengguna024', 'Guru12345*!', 'N', 'Guru Mapel', '082247033030'),
('0021.025', 'I Putu Dedy Karsana,S.Pd.', 'Guru', 'dedy', 'Guru12345*!', 'Y', 'Guru Mapel', '0895347674833'),
('0021.026', 'Nama_Pengguna', 'Guru', 'pengguna026', 'Guru12345*!', 'N', 'Guru Mapel', '081238437877'),
('0021.027', 'I Putu Agus Sujana Adi Putra,S.Pd.', 'Guru', 'gussujana', 'Guru12345*!', 'Y', 'Guru Mapel', '081237896743'),
('0021.028', 'Nama_Pengguna', 'Guru', 'pengguna028', 'Guru12345*!', 'N', 'Guru Mapel', '081933019479'),
('0021.029', 'Drs. I Gusti Putu Tirta Yasa,M.Pd.', 'Guru', 'tirta', 'Guru12345*!', 'Y', 'Guru Mapel', '087837454455'),
('0021.030', 'Nama_Pengguna', 'Guru', 'pengguna030', 'Guru12345*!', 'N', 'Guru Mapel', '081220692219'),
('0021.031', 'Nama_Pengguna', 'Guru', 'pengguna031', 'Guru12345*!', 'N', 'Guru Mapel', '081353285810'),
('0021.032', 'Nama_Pengguna', 'Guru', 'pengguna032', 'Guru12345*!', 'N', 'Guru Mapel', '081338512340'),
('0021.033', 'Ni Putu Anita Prahandari,S.Pd.', 'Guru', 'anita', 'Guru12345*!', 'Y', 'Guru Mapel', '081933106676'),
('0021.034', 'Nama_Pengguna', 'Guru', 'pengguna034', 'Guru12345*!', 'N', 'Guru Mapel', '081338638999'),
('0021.035', 'I Kadek Yogi Mayudana,M.Pd.', 'Guru', 'yogi', 'Guru12345*!', 'Y', 'Guru Mapel', '081999022333'),
('0021.036', 'Luh Putu Ayu Desiani,S.Kom.,MM.', 'Guru', 'desi', 'Guru12345*!', 'Y', 'Guru Mapel', '081805474228'),
('0021.037', 'I Kadek Puji Aksama,S.Pd.', 'Guru', 'aksama', 'Guru12345*!', 'Y', 'Guru Mapel', '083117769593'),
('0021.038', 'Nama_Pengguna', 'Guru', 'pengguna038', 'Guru12345*!', 'N', 'Guru Mapel', '082146503026'),
('0021.039', 'Ida Gusti Ayu Rinjani,M.Pd.', 'Guru', 'anjani', 'Guru12345*!', 'Y', 'Guru BK XII', '081999976038'),
('0021.040', 'Ainul Mubsiroh,S.Pd.I, M.Pd.', 'Guru', 'ain', 'Guru12345*!', 'Y', 'Guru Mapel', '082247033044'),
('0021.041', 'Nama_Pengguna', 'Guru', 'pengguna041', 'Guru12345*!', 'N', 'Guru Mapel', '087862656412'),
('0021.042', 'Nama_Pengguna', 'Guru', 'pengguna042', 'Guru12345*!', 'N', 'Guru Mapel', '082237442222'),
('0021.043', 'Nama_Pengguna', 'Guru', 'pengguna043', 'Guru12345*!', 'N', 'Guru Mapel', '085953912558'),
('0021.044', 'Nama_Pengguna', 'Guru', 'pengguna044', 'Guru12345*!', 'N', 'Guru Mapel', '08563769773'),
('0021.045', 'Nama_Pengguna', 'Guru', 'pengguna045', 'Guru12345*!', 'N', 'Guru Mapel', '087863112233'),
('0021.046', 'Masri Kagatanaribe,M.Pd.', 'Guru', 'masri', 'Guru12345*!', 'Y', 'Guru Mapel', '085739990443'),
('0021.047', 'Nama_Pengguna', 'Guru', 'pengguna047', 'Guru12345*!', 'N', 'Guru Mapel', '087861863842'),
('0021.048', 'Luh Putu Trisma Prabawati,S.Kom.', 'Guru', 'trisma', 'Guru12345*!', 'Y', 'Guru Mapel', '082291355411'),
('0021.049', 'Kadek Diah Kertiana,S.Kom.', 'Guru', 'diah', 'Guru12345*!', 'Y', 'Guru Mapel', '082247033484'),
('0021.050', 'Nama_Pengguna', 'Guru', 'pengguna050', 'Guru12345*!', 'N', 'Guru Mapel', '08123650940'),
('0021.051', 'Nama_Pengguna', 'Guru', 'pengguna051', 'Guru12345*!', 'N', 'Guru Mapel', '083114537674'),
('0021.052', 'Nama_Pengguna', 'Guru', 'pengguna052', 'Guru12345*!', 'N', 'Guru Mapel', '085738235218'),
('0021.053', 'Nama_Pengguna', 'Guru', 'pengguna053', 'Guru12345*!', 'N', 'Guru Mapel', '082146167817'),
('0021.054', 'Nama_Pengguna', 'Guru', 'pengguna054', 'Guru12345*!', 'N', 'Guru Mapel', '081239588346'),
('0021.055', 'Nama_Pengguna', 'Guru', 'pengguna055', 'Guru12345*!', 'N', 'Guru Mapel', '081338401856'),
('0021.056', 'Nama_Pengguna', 'Guru', 'pengguna056', 'Guru12345*!', 'N', 'Guru Mapel', '085738216181'),
('0021.057', 'Nama_Pengguna', 'Guru', 'pengguna057', 'Guru12345*!', 'N', 'Guru Mapel', '083834969500'),
('0021.058', 'I Wayan Arik Sukariawan,S.Kom.', 'Guru', 'arik', 'Guru12345*!', 'Y', 'Guru Mapel', '082247033088'),
('0021.059', 'Nama_Pengguna', 'Guru', 'pengguna059', 'Guru12345*!', 'N', 'Guru Mapel', '082247033030'),
('0021.060', 'I Putu Eka Mahendra,S.Kom.', 'Guru', 'eka', 'Guru12345*!', 'Y', 'Guru Mapel', '0895347674833'),
('0021.061', 'Nama_Pengguna', 'Guru', 'pengguna061', 'Guru12345*!', 'N', 'Guru Mapel', '081238437877'),
('0021.062', 'Bella Cintya Devi,S.Kom.', 'Guru', 'bella', 'Guru12345*!', 'Y', 'Guru Mapel', '081237896743'),
('0021.063', 'Darsusanto,S.Ag.', 'Guru', 'darsusanto', 'Guru12345*!', 'Y', 'Guru Mapel', '081933019479'),
('0021.064', 'I Gusti Made Gunawan,S.Pd.', 'Guru', 'gun', 'Guru12345*!', 'Y', 'Guru Mapel', '087837454455'),
('0021.065', 'Ni Wayan Sriyaningsih,S.Sos.', 'Guru', 'anik', 'Guru12345*!', 'Y', 'Guru Mapel', '081220692219'),
('0021.066', 'Nama_Pengguna', 'Guru', 'pengguna066', 'Guru12345*!', 'N', 'Guru Mapel', '081353285810'),
('0021.067', 'Nengah Dwi Rahayu,SE', 'Guru', 'dwirahayu', 'Guru12345*!', 'Y', 'Guru Mapel', '081338512340'),
('0021.068', 'Nama_Pengguna', 'Guru', 'pengguna068', 'Guru12345*!', 'N', 'Guru Mapel', '081933106676'),
('0021.069', 'Nama_Pengguna', 'Guru', 'pengguna069', 'Guru12345*!', 'N', 'Guru Mapel', '081338638999'),
('0021.070', 'Ni Putu Tirta Purnama Dewi,S.Pd', 'Guru', 'tirta2', 'Guru12345*!', 'N', 'Guru Mapel', '081999022333'),
('0021.071', 'Ni Nyoman Damayanti,S.Pd.,M.Pd.', 'Guru', 'kotika', 'Guru12345*!', 'Y', 'Guru Mapel', '081805474228'),
('0021.072', 'Nama_Pengguna', 'Guru', 'pengguna072', 'Guru12345*!', 'Y', 'Guru Mapel', '083117769593'),
('0021.073', 'Ni Wayan Lina Valentine,S.Pd.', 'Guru', 'lina', 'Guru12345*!', 'Y', 'Guru Mapel', '082146503026'),
('0021.074', 'Nama_Pengguna', 'Guru', 'pengguna074', 'Guru12345*!', 'N', 'Guru Mapel', '081999976038'),
('0021.075', 'Nama_Pengguna', 'Guru', 'pengguna075', 'Guru12345*!', 'N', 'Guru Mapel', '082247033044'),
('0021.076', 'Triono Doni Wijaya,S.Kom.', 'Guru', 'doni', 'Guru12345*!', 'Y', 'Guru Mapel', '087862656412'),
('0021.077', 'Nuri Sutiyaningsih,M.Kom.', 'Guru', 'nuri', 'Guru12345*!', 'Y', 'Guru Mapel', '082237442222'),
('0021.078', 'Kadek Arie Wira Kusuma,S.Kom.', 'Guru', 'ariewira', 'Guru12345*!', 'Y', 'Guru Mapel', '085953912558'),
('0021.079', 'Ni Putu Linda Agustini,S.Pd.', 'Guru', 'linda', 'Guru12345*!', 'Y', 'Guru Mapel', '08563769773'),
('0021.080', 'Ida Bagus Angga Baskara,S.Pd.', 'Guru', 'baskara', 'Guru12345*!', 'Y', 'Guru Mapel', '087863112233'),
('0021.081', 'Tjok Istri Agung Rai Sintha Devi,S.Pd.', 'Guru', 'sintha', 'Guru12345*!', 'Y', 'Guru Mapel', '085739990443'),
('0021.082', 'Ida Bagus Maha Indra Prasada,S.Kom.', 'Guru', 'mahaindra', 'Guru12345*!', 'Y', 'Guru Mapel', '087861863842'),
('0021.083', 'Ida Ayu Dewi Paramita,S.Pd.', 'Guru', 'dayumita', 'Guru12345*!', 'Y', 'Guru Mapel', '082291355411'),
('0021.084', 'Ni Luh Rosa Diarsanthi,S.Pd.', 'Guru', 'rosa', 'Guru12345*!', 'Y', 'Guru Mapel', '082247033484'),
('0021.085', 'Yustina Mariana Odang,S.Pd.', 'Guru', 'yustina', 'Guru12345*!', 'Y', 'Guru Mapel', '08123650940'),
('0021.086', 'Ida Ayu Amrita Pancajania,SE.', 'Guru', 'amrita', 'Guru12345*!', 'Y', 'Guru Mapel', '083114537674'),
('0021.087', 'A.A Gde Radika Mahaprasta Putra', 'Guru', 'radika', 'Guru12345*!', 'Y', 'Guru Mapel', '085738235218'),
('0021.088', 'Ni Ketut Supartini,SS.', 'Guru', 'tini', 'Guru12345*!', 'Y', 'Guru Mapel', '082146167817'),
('0021.089', 'M. Agus Wahyudi,S.Pd.', 'Guru', 'yudi', 'Guru12345*!', 'Y', 'Guru Mapel', '081239588346'),
('0021.090', 'I Made Pranayama,S.Pd.', 'Guru', 'pranayama', 'Guru12345*!', 'Y', 'Guru Mapel', '081338401856'),
('0021.091', 'Ni Kadek Chandra Putri Irani,S.Pd.,M.Pd.', 'Guru', 'chandra', 'Guru12345*!', 'Y', 'Guru Mapel', '085738216181'),
('0021.092', 'I Dewa Ayu Setiyawati,S.Pd.', 'Guru', 'dayu', 'Guru12345*!', 'Y', 'Guru Mapel', '083834969500'),
('0021.093', 'Finsensius Ratuaki,M.Pd.', 'Guru', 'finsen', 'Guru12345*!', 'Y', 'Guru BK X', '082247033088'),
('0021.094', 'Ni Putu Chintya Pradnya Suari,S.Pd.', 'Guru', 'chintya', 'Guru12345*!', 'Y', 'Guru BK XI', '082247033030'),
('0021.095', 'Adventina Puspita', 'Guru', 'puspita', 'Guru12345*!', 'Y', 'Guru Mapel', '0895347674833'),
('0021.096', 'Aprianus Anjelius Foutnine,S.Fil', 'Guru', 'anjel', 'Guru12345*!', 'Y', 'Guru Mapel', '081238437877');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kelas`
--

CREATE TABLE `kelas` (
  `Id` int(11) NOT NULL,
  `Id_Tingkat` int(11) NOT NULL,
  `Id_Program_Keahlian` int(11) NOT NULL,
  `Rombel` int(11) NOT NULL,
  `Wali_Kelas` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kelas`
--

INSERT INTO `kelas` (`Id`, `Id_Tingkat`, `Id_Program_Keahlian`, `Rombel`, `Wali_Kelas`) VALUES
(1, 3, 1, 1, '0021.003'),
(2, 3, 1, 2, '0021.039'),
(3, 3, 1, 3, '0021.002'),
(4, 3, 1, 4, '0021.003'),
(7, 3, 2, 1, '0021.006');

-- --------------------------------------------------------

--
-- Struktur dari tabel `ortu_wali`
--

CREATE TABLE `ortu_wali` (
  `Id` int(11) NOT NULL,
  `Ayah` varchar(100) DEFAULT NULL,
  `Ibu` varchar(100) DEFAULT NULL,
  `Wali` varchar(100) DEFAULT NULL,
  `Pekerjaan_Ayah` varchar(100) DEFAULT NULL,
  `Pekerjaan_Ibu` varchar(100) DEFAULT NULL,
  `Pekerjaan_Wali` varchar(100) DEFAULT NULL,
  `No_Telp_Ayah` varchar(20) DEFAULT NULL,
  `No_Telp_Ibu` varchar(20) DEFAULT NULL,
  `No_Telp_Wali` varchar(20) DEFAULT NULL,
  `Alamat_Ayah` text DEFAULT NULL,
  `Alamat_Ibu` text DEFAULT NULL,
  `Alamat_Wali` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `ortu_wali`
--

INSERT INTO `ortu_wali` (`Id`, `Ayah`, `Ibu`, `Wali`, `Pekerjaan_Ayah`, `Pekerjaan_Ibu`, `Pekerjaan_Wali`, `No_Telp_Ayah`, `No_Telp_Ibu`, `No_Telp_Wali`, `Alamat_Ayah`, `Alamat_Ibu`, `Alamat_Wali`) VALUES
(1, 'Bagus Ahmad', 'Wati Utari', NULL, 'Dokter Spesialis', 'Dokter Spesialis', NULL, '6281679485408', '6281679485408', NULL, 'Jalan Nangka 2 A, Gang Rujak', 'Jalan Nangka 2 A, Gang Rujak', NULL),
(2, 'Lukman Halimah', 'Mega Rahma', NULL, 'Penjahit', 'Penjahit', NULL, '628621323300', '628621323300', NULL, 'Jalan Antasura, Perumahan Elit', 'Jalan Antasura, Perumahan Elit', NULL),
(3, 'Calvin Connor', 'Natalia Glover', NULL, 'Pengelola Properti', 'Pengelola Properti', NULL, '6282540268936', '628785988574', NULL, NULL, NULL, 'Jalan Sesetan, Gang No 5'),
(4, NULL, NULL, 'Safiya Hartman', NULL, NULL, 'Designer', NULL, NULL, '62860589252', 'Psr Jatinegara Bl BKS/30, Dki Jakarta', 'Psr Jatinegara Bl BKS/30, Dki Jakarta', NULL),
(5, 'Wayan Surya', 'Putu Ayu', NULL, 'Wiraswasta', 'IRT', NULL, '628123456789', '628987654321', NULL, 'Jl Janur 11 43 RT 003/07 Pd Kelapa, Dki Jakarta', 'Jl Janur 11 43 RT 003/07 Pd Kelapa, Dki Jakarta', NULL),
(6, 'Made Adi', 'Ketut Sri', NULL, 'PNS', 'PNS', NULL, '628111222333', '628444555666', NULL, 'Jl Salemba Raya 2 Ged Kenari Baru Bl C/4, Dki Jakarta', 'Jl Salemba Raya 2 Ged Kenari Baru Bl C/4, Dki Jakarta', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `siswa`
--

CREATE TABLE `siswa` (
  `NIS` int(11) NOT NULL,
  `Nama_Siswa` varchar(100) NOT NULL,
  `Jenis_Kelamin` enum('Laki - Laki','Perempuan') NOT NULL,
  `Alamat` text DEFAULT NULL,
  `Password` varchar(255) DEFAULT NULL,
  `Status` enum('aktif','lulus','keluar') DEFAULT 'aktif',
  `Id_Ortu_Wali` int(11) DEFAULT NULL,
  `Id_Kelas` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `siswa`
--

INSERT INTO `siswa` (`NIS`, `Nama_Siswa`, `Jenis_Kelamin`, `Alamat`, `Password`, `Status`, `Id_Ortu_Wali`, `Id_Kelas`) VALUES
(7012, 'Abdullah Musa', 'Laki - Laki', 'Jl Psr Paseban Bl B Lt3, Dki Jakarta', 'Globaliti', 'aktif', 1, 1),
(8312, 'Juni Budi', 'Laki - Laki', 'Jl Gn Krakatau 387 A, Sumatera Utara', 'Globaliti', 'lulus', 2, 2),
(9123, 'Wahyuni Yuliana', 'Perempuan', 'Jl MH Thamrin Kav 28-30 Plaza, Jakarta', '8GbPKgSd01?$£6NP6!-!ZE~{X\\<R!E&+qI!V=K.;~A@£V=>6u2', 'keluar', 3, 4),
(9124, 'Ryan', 'Laki - Laki', 'Psr Jatinegara Bl BKS/30, Dki Jakarta', 'Globaliti', 'aktif', 4, 1),
(9125, 'Narin', 'Perempuan', 'Jl Janur 11 43 RT 003/07 Pd Kelapa, Dki Jakarta', 'Globaliti', 'aktif', 5, 1),
(9126, 'Dayu', 'Perempuan', 'Jl Salemba Raya 2 Ged Kenari Baru Bl C/4, Dki Jakarta', 'Globaliti', 'aktif', 6, 7);

-- --------------------------------------------------------

--
-- Struktur dari tabel `profil_sekolah`
--

CREATE TABLE `profil_sekolah` (
  `Id` int(11) NOT NULL,
  `Nama_Sekolah` varchar(100) NOT NULL,
  `Alamat_Sekolah` text DEFAULT NULL,
  `Kota` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `profil_sekolah`
--

INSERT INTO `profil_sekolah` (`Id`, `Nama_Sekolah`, `Alamat_Sekolah`, `Kota`) VALUES
(1, 'SMKS TI Bali Global Denpasar', 'Kecamatan Denpasar Selatan, Kota Denpasar, Provinsi Bali', 'Denpasar');

-- --------------------------------------------------------

--
-- Struktur dari tabel `surat_pindah_sekolah`
--

CREATE TABLE `surat_pindah_sekolah` (
  `Id` int(11) NOT NULL,
  `Sekolah_Tujuan` varchar(100) NOT NULL,
  `Alasan_Pindah` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `surat_pindah_sekolah`
--

INSERT INTO `surat_pindah_sekolah` (`Id`, `Sekolah_Tujuan`, `Alasan_Pindah`) VALUES
(1, 'SMAN 1 Surakarta', 'Mengikuti perpindahan dinas orang tua'),
(2, 'SMKS Harapan', 'Mengikuti perpindahan dinas orang tua'),
(3, 'SMKN 2 Denpasar', 'Mengikuti perpindahan dinas orang tua');

-- --------------------------------------------------------

--
-- Struktur dari tabel `surat_keluar`
--

CREATE TABLE `surat_keluar` (
  `Id` int(11) NOT NULL,
  `No_Surat` varchar(50) NOT NULL,
  `Id_Tingkat` int(11) DEFAULT NULL,
  `Jenis_Surat` enum('Pindah Sekolah','Panggilan Orang Tua','Perjanjian Siswa') NOT NULL,
  `Id_Pindah_Sekolah` int(11) DEFAULT NULL,
  `NIS` int(11) DEFAULT NULL,
  `Tanggal_Pembuatan_Surat` date DEFAULT NULL,
  `Id_Profil_Sekolah` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `surat_keluar`
--

INSERT INTO `surat_keluar` (`Id`, `No_Surat`, `Id_Tingkat`, `Jenis_Surat`, `Id_Pindah_Sekolah`, `NIS`, `Tanggal_Pembuatan_Surat`, `Id_Profil_Sekolah`) VALUES
(1, '548/SMKTI/BG/XII/2025', 2, 'Pindah Sekolah', 1, 7012, '2026-01-08', 1),
(2, '549/SMKTI/BG/XII/2025', 3, 'Panggilan Orang Tua', NULL, 8312, '2026-01-08', 1),
(3, '550/SMKTI/BG/I/2026', 1, 'Pindah Sekolah', 2, 9123, '2026-01-10', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `jenis_pelanggaran`
--

CREATE TABLE `jenis_pelanggaran` (
  `Id` int(11) NOT NULL,
  `Jenis_Pelanggaran` varchar(100) NOT NULL,
  `Poin` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `jenis_pelanggaran`
--

INSERT INTO `jenis_pelanggaran` (`Id`, `Jenis_Pelanggaran`, `Poin`) VALUES
(1, 'Seragam Sekolah', 2),
(2, 'Kehadiran Di Sekolah', 5),
(3, 'Proses Belajar Mengajar', 6),
(4, 'Pelanggaran Norma Norma', 9),
(5, 'Pelanggaran Berat', 10),
(6, 'Kesopanan Berkendara', 8),
(7, 'Upacara Bendera', 4);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pelanggaran_siswa`
--

CREATE TABLE `pelanggaran_siswa` (
  `Id` int(11) NOT NULL,
  `Tanggal` datetime NOT NULL,
  `NIS` int(11) NOT NULL,
  `Id_Pelanggaran` int(11) NOT NULL,
  `Keterangan` text DEFAULT NULL,
  `Tingkat` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pelanggaran_siswa`
--

INSERT INTO `pelanggaran_siswa` (`Id`, `Tanggal`, `NIS`, `Id_Pelanggaran`, `Keterangan`, `Tingkat`) VALUES
(1, '2026-01-02 09:26:32', 9124, 2, 'Terlambat masuk hari Senin', 'X'),
(2, '2026-01-02 09:26:35', 9125, 3, 'Tidak mengerjakan PR', 'XI'),
(3, '2026-01-02 11:26:35', 9126, 3, 'Makan di kelas saat pelajaran Matematika', 'XI');

-- --------------------------------------------------------

--
-- Struktur dari tabel `perjanjian_siswa`
--

CREATE TABLE `perjanjian_siswa` (
  `Id` int(11) NOT NULL,
  `Tanggal` datetime NOT NULL,
  `Id_Pelanggaran_Siswa` int(11) NOT NULL,
  `Status` enum('Masih Proses','Selesai','Dibatalkan') DEFAULT 'Masih Proses',
  `Foto_Dokumen` varchar(255) DEFAULT NULL,
  `Tingkat` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `perjanjian_siswa`
--

INSERT INTO `perjanjian_siswa` (`Id`, `Tanggal`, `Id_Pelanggaran_Siswa`, `Status`, `Foto_Dokumen`, `Tingkat`) VALUES
(1, '2026-01-02 09:26:31', 1, 'Masih Proses', NULL, 'X'),
(2, '2026-01-03 09:26:32', 2, 'Selesai', 'IMG_20260923.jpg', 'XI'),
(3, '2026-01-05 08:26:32', 3, 'Masih Proses', NULL, 'XI');

-- --------------------------------------------------------

--
-- Struktur dari tabel `perjanjian_orang_tua`
--

CREATE TABLE `perjanjian_orang_tua` (
  `Id` int(11) NOT NULL,
  `Tanggal` datetime NOT NULL,
  `Id_Pelanggaran_Siswa` int(11) NOT NULL,
  `Id_Ortu_Wali` int(11) NOT NULL,
  `Status` enum('Masih Proses','Selesai','Dibatalkan') DEFAULT 'Masih Proses',
  `Foto_Dokumen` varchar(255) DEFAULT NULL,
  `Tingkat` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `perjanjian_orang_tua`
--

INSERT INTO `perjanjian_orang_tua` (`Id`, `Tanggal`, `Id_Pelanggaran_Siswa`, `Id_Ortu_Wali`, `Status`, `Foto_Dokumen`, `Tingkat`) VALUES
(1, '2026-01-02 09:26:31', 1, 1, 'Masih Proses', NULL, 'X'),
(2, '2026-01-03 09:26:32', 2, 4, 'Selesai', 'IMG_20260923.jpg', 'XI'),
(3, '2026-01-05 08:26:32', 3, 3, 'Masih Proses', NULL, 'XI');

--
-- Indexes for dumped tables
--

ALTER TABLE `program_keahlian`
  ADD PRIMARY KEY (`Id`);

ALTER TABLE `tingkat`
  ADD PRIMARY KEY (`Id`);

ALTER TABLE `tahun_ajaran`
  ADD PRIMARY KEY (`Id`);

ALTER TABLE `guru`
  ADD PRIMARY KEY (`Kode_Guru`),
  ADD UNIQUE KEY `username` (`Username`);

ALTER TABLE `kelas`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `fk_kelas_tingkat` (`Id_Tingkat`),
  ADD KEY `fk_kelas_program` (`Id_Program_Keahlian`),
  ADD KEY `fk_kelas_wali` (`Wali_Kelas`);

ALTER TABLE `ortu_wali`
  ADD PRIMARY KEY (`Id`);

ALTER TABLE `siswa`
  ADD PRIMARY KEY (`NIS`),
  ADD KEY `fk_siswa_ortu` (`Id_Ortu_Wali`),
  ADD KEY `fk_siswa_kelas` (`Id_Kelas`);

ALTER TABLE `profil_sekolah`
  ADD PRIMARY KEY (`Id`);

ALTER TABLE `surat_pindah_sekolah`
  ADD PRIMARY KEY (`Id`);

ALTER TABLE `surat_keluar`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `fk_surat_tingkat` (`Id_Tingkat`),
  ADD KEY `fk_surat_pindah` (`Id_Pindah_Sekolah`),
  ADD KEY `fk_surat_siswa` (`NIS`),
  ADD KEY `fk_surat_profil` (`Id_Profil_Sekolah`);

ALTER TABLE `jenis_pelanggaran`
  ADD PRIMARY KEY (`Id`);

ALTER TABLE `pelanggaran_siswa`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `fk_pelanggar_siswa` (`NIS`),
  ADD KEY `fk_pelanggar_jenis` (`Id_Pelanggaran`);

ALTER TABLE `perjanjian_siswa`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `fk_perjanjian_pelanggar` (`Id_Pelanggaran_Siswa`);

ALTER TABLE `perjanjian_orang_tua`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `fk_perjanjian_ortu_pelanggar` (`Id_Pelanggaran_Siswa`),
  ADD KEY `fk_perjanjian_ortu` (`Id_Ortu_Wali`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

ALTER TABLE `program_keahlian`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

ALTER TABLE `tingkat`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

ALTER TABLE `tahun_ajaran`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

ALTER TABLE `kelas`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

ALTER TABLE `ortu_wali`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

ALTER TABLE `surat_pindah_sekolah`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

ALTER TABLE `surat_keluar`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

ALTER TABLE `jenis_pelanggaran`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

ALTER TABLE `pelanggaran_siswa`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

ALTER TABLE `perjanjian_siswa`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

ALTER TABLE `perjanjian_orang_tua`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

ALTER TABLE `kelas`
  ADD CONSTRAINT `fk_kelas_tingkat` FOREIGN KEY (`Id_Tingkat`) REFERENCES `tingkat` (`Id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_kelas_program` FOREIGN KEY (`Id_Program_Keahlian`) REFERENCES `program_keahlian` (`Id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_kelas_wali` FOREIGN KEY (`Wali_Kelas`) REFERENCES `guru` (`Kode_Guru`) ON UPDATE CASCADE;

ALTER TABLE `siswa`
  ADD CONSTRAINT `fk_siswa_ortu` FOREIGN KEY (`Id_Ortu_Wali`) REFERENCES `ortu_wali` (`Id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_siswa_kelas` FOREIGN KEY (`Id_Kelas`) REFERENCES `kelas` (`Id`) ON UPDATE CASCADE;

ALTER TABLE `surat_keluar`
  ADD CONSTRAINT `fk_surat_tingkat` FOREIGN KEY (`Id_Tingkat`) REFERENCES `tingkat` (`Id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_surat_pindah` FOREIGN KEY (`Id_Pindah_Sekolah`) REFERENCES `surat_pindah_sekolah` (`Id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_surat_siswa` FOREIGN KEY (`NIS`) REFERENCES `siswa` (`NIS`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_surat_profil` FOREIGN KEY (`Id_Profil_Sekolah`) REFERENCES `profil_sekolah` (`Id`) ON UPDATE CASCADE;

ALTER TABLE `pelanggaran_siswa`
  ADD CONSTRAINT `fk_pelanggar_siswa` FOREIGN KEY (`NIS`) REFERENCES `siswa` (`NIS`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pelanggar_jenis` FOREIGN KEY (`Id_Pelanggaran`) REFERENCES `jenis_pelanggaran` (`Id`) ON UPDATE CASCADE;

ALTER TABLE `perjanjian_siswa`
  ADD CONSTRAINT `fk_perjanjian_pelanggar` FOREIGN KEY (`Id_Pelanggaran_Siswa`) REFERENCES `pelanggaran_siswa` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `perjanjian_orang_tua`
  ADD CONSTRAINT `fk_perjanjian_ortu_pelanggar` FOREIGN KEY (`Id_Pelanggaran_Siswa`) REFERENCES `pelanggaran_siswa` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_perjanjian_ortu` FOREIGN KEY (`Id_Ortu_Wali`) REFERENCES `ortu_wali` (`Id`) ON UPDATE CASCADE;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
