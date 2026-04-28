-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 28 Apr 2026 pada 12.42
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
-- Database: `ventura`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `barang`
--

CREATE TABLE `barang` (
  `id_barang` int(11) NOT NULL,
  `nama_barang` varchar(255) NOT NULL,
  `kategori` varchar(100) NOT NULL,
  `stok` int(11) NOT NULL DEFAULT 0,
  `harga_sewa` decimal(10,2) NOT NULL DEFAULT 0.00,
  `kondisi` varchar(50) NOT NULL,
  `foto_barang` text DEFAULT 'tenda.jpg',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `views` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `barang`
--

INSERT INTO `barang` (`id_barang`, `nama_barang`, `kategori`, `stok`, `harga_sewa`, `kondisi`, `foto_barang`, `created_at`, `updated_at`, `views`) VALUES
(1, 'Tenda Dome Kapasitas 4', 'Tenda', 2, 50000.00, 'Baik', '1775965237_cf467619023f4dee7179.png,1775965251_0d4f2f1eca7d66c7210d.png', '2026-04-12 01:48:03', '2026-04-28 10:12:48', 19),
(2, 'Tracking pool', 'Tracking Pool', 8, 20000.00, 'Baik', '1775965180_2e90ae4b7d87a67e5bf2.png,1775965180_6ddd6344f4fe64209fff.png', '2026-04-12 03:39:40', '2026-04-28 07:13:00', 23),
(3, 'Sepatu Lavio', 'Sepatu', 20, 30000.00, 'Baik', '1775965375_70e66a5530020ab8508f.png,1775965388_d6034488336a4aa52373.png', '2026-04-12 03:42:38', '2026-04-18 06:09:46', 3),
(4, 'Sandal Reward', 'Sandal', -1, 20000.00, 'Baik', '1775965472_ba926e6f5c2fe4e16ea1.png,1775965472_ba7da16fd15565f542fd.png', '2026-04-12 03:44:32', '2026-04-28 07:10:01', 3),
(5, 'Sandal TapiLaku', 'Sandal', 23, 20000.00, 'Baik', '1775965521_d71d4e165bf01788919e.png,1775965543_39353c8cf2283053b51f.png', '2026-04-12 03:45:21', '2026-04-18 05:17:01', 3),
(6, 'Kompor Portable Hi-Cook', 'Kompor', 35, 15000.00, 'Baik', '1775965624_727f125b8a627635b317.png,1775965644_a29b35ad4b6dabc92c2f.png,1775965644_237c804605b7929ab8ec.png', '2026-04-12 03:47:04', '2026-04-27 13:45:17', 9),
(7, 'Tenda Dome Arpenaz 4P', 'Tenda', 49, 50000.00, 'Baik', '1775965693_c85c2cd3667fc10261c2.png,1775965711_353419dfd56893bed6f5.png,1775965711_30adc7954e1c73f7fca3.png', '2026-04-12 03:48:13', '2026-04-16 06:03:50', 0),
(8, 'sepatu salomon', 'Sepatu', 30, 50000.00, 'Baik', '1775965763_4eefeacaece93f529162.png', '2026-04-12 03:49:23', '2026-04-27 02:01:37', 2),
(12, 'jaket', 'Jaket', 1, 20000.00, 'Baik', '1777256086_f757b846d979e163fd26.png,1777256086_3b05be8f83deb5aa146e.png,1777256086_e8da8f6736c5407f59a3.png', '2026-04-27 02:14:46', '2026-04-28 07:12:16', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `gunung`
--

CREATE TABLE `gunung` (
  `id_gunung` int(11) NOT NULL,
  `nama_gunung` varchar(100) DEFAULT NULL,
  `lokasi` varchar(100) DEFAULT NULL,
  `ketinggian` int(11) DEFAULT NULL,
  `status` enum('Buka','Tutup','Waspada') DEFAULT 'Buka',
  `foto` varchar(255) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `gunung`
--

INSERT INTO `gunung` (`id_gunung`, `nama_gunung`, `lokasi`, `ketinggian`, `status`, `foto`, `deskripsi`, `created_at`) VALUES
(1, 'merbabu', 'Suroteleng, Selo, Boyolali Regency, Central Java', 3145, 'Buka', '1776540599_88be635d28b4b62d9dd1.jpg', 'Jalur Selo (Boyolali):\r\nKarakteristik: Terpopuler, akses mudah, pemandangan sabana luas, pemandangan Gunung Merapi dari dekat.\r\nWaktu Tempuh: Sekitar \r\n\r\n jam.\r\nCocok untuk: Semua pendaki (pemula maupun berpengalaman).\r\nJalur Suwanting (Magelang):\r\nKarakteristik: Terkenal berat, menanjak, terjal, pemandangan sabana sabana yang sangat indah, suhu dingin (\r\n\r\n derajat Celsius).\r\nWaktu Tempuh: Sekitar \r\n\r\n jam.\r\nCocok untuk: Pendaki berpengalaman/fisik prima.\r\nJalur Wekas (Magelang):\r\nKarakteristik: Jalur paling cepat, sumber air ada di sekitar pos 2, ramah pemula.\r\nWaktu Tempuh: Sekitar \r\n\r\n jam.\r\nCocok untuk: Pemula.\r\nJalur Cuntel (Kopeng):\r\nKarakteristik: Jalur cukup panjang dengan medan menanjak dan melalui hutan, pemandangan indah.\r\nWaktu Tempuh: Sekitar \r\n\r\n jam.\r\nJalur Thekelan (Kopeng):\r\nKarakteristik: Salah satu jalur tertua, melewati 7 puncak (Watu Gubuk, Pemancar, Geger Sapi, Syarif, Ondo Rante, Kenteng Songo, Triangulasi), pemandangan dramatis.\r\nWaktu Tempuh: Sekitar \r\n\r\n jam.', '2026-04-18 19:29:59'),
(2, 'slamet', 'Cipendawa, Pacet, Cianjur Regency, West Java', 3458, 'Tutup', '1776541410_02b176822493fd3416d6.jpg', 'Jalur Utama:\r\nBambangan (Purbalingga): Jalur terpopuler, 9 pos, via hutan tropis, waktu tempuh ~10 jam.\r\nPermadi Guci (Tegal): Populer, ada ojek hingga pos 1 (Rp50.000) atau pintu rimba (Rp25.000), sumber air melimpah..\r\nBaturraden (Banyumas): Jalur yang menantang dan terjal.', '2026-04-18 19:43:30'),
(3, 'Papandayan', 'Karamat Wangi, Cisurupan, Garut Regency, West Java', 2665, 'Buka', '1776545995_9501324e9b166774abab.webp,1776545995_9f5a1528f21c3ec0383a.jpg,1776545995_7f4c803f69550d54e9d2.jpg,1776545995_edfaf292abb88740e1f4.jpeg', 'Informasi Jalur & Estimasi Waktu\r\nTotal Jalur: Terdapat 10 pos, namun pendakian sesungguhnya sering dihitung dari Pos 4.\r\nEstimasi Waktu:\r\nCamp David - Kawah: 30–45 menit.\r\nKawah - Pondok Saladah: 1,5–2 jam.\r\nPondok Saladah - Hutan Mati: 15–30 menit.\r\nPondok Saladah - Tegal Alun: 2,5–3 jam.\r\nKondisi Jalur: Didominasi tanah, berbatu, dan area terbuka yang terik. Terdapat jembatan dan sungai kecil setelah Goberhood. \r\nEiger Adventure Official\r\nEiger Adventure Official\r\n +3\r\nInformasi Pendakian\r\nLokasi: Kecamatan Cisurupan, Kabupaten Garut.\r\nBuka: 24 jam.\r\nBiaya (Nusantara - Hari Kerja): Tiket masuk Rp30.000, Roda 2 Rp17.000, Roda 4 Rp35.000 (harga dapat berubah).\r\nFasilitas: Warung, toilet, dan mushola di area camp, serta banyak opsi ojek motor dari Camp David ke area dekat Kawah.\r\nWaktu Terbaik: Bulan Februari-Juni dan Oktober-Desember. \r\nTrip.com Indonesia\r\nTrip.com Indonesia\r\n +4\r\nTips: Gunakan masker karena jalur melewati kawah aktif dengan aroma belerang yang menyengat. Pendakian tektok (satu hari) sangat dimungkinkan karena medannya tidak terlalu berat.', '2026-04-18 20:45:58'),
(4, 'Gunung gede', 'Cipendawa, Pacet, Cianjur Regency, West Java', 2958, 'Buka', '1776546480_16c4388e325d28ce3f75.webp,1776546480_de334f9038298043f0bd.webp,1776546480_fad073f866d96170b2f0.jpeg', 'Berikut adalah detail informasi mengenai Gunung Gede:\r\nStatus Pendakian: Dibuka per 13 April 2026, berdasarkan Surat Edaran Nomor 06 Tahun 2026, setelah sempat ditutup untuk pemulihan ekosistem.\r\nKetinggian: 2.958 meter di atas permukaan laut (mdpl).\r\nLokasi: Taman Nasional Gunung Gede Pangrango (TNGGP), Jawa Barat.\r\nJalur Pendakian:\r\nCibodas (Cianjur): Jalur paling populer, terawat, dan banyak sumber air (6-8 jam).\r\nGunung Putri (Cianjur): Jalur lebih cepat namun lebih terjal.\r\nSelabintana (Sukabumi): Jalur terpanjang dengan pemandangan hutan lebat.\r\nBiaya (Tiket Masuk): Sekitar Rp29.000 (hari kerja) - Rp34.000 (hari libur) untuk WNI.\r\nAturan Penting:\r\nWajib booking online.\r\nMembawa turun kembali sampah.\r\nDilarang melakukan pendakian ilegal. ', '2026-04-18 21:08:00'),
(5, 'Sagara', ' Kampung Sagara, Desa Tenjonagara, Kecamatan Sucinaraja, Kabupaten Garut, Jawa Barat.', 2231, 'Buka', '1777054916_5a1db001ffbd65a79c14.jpg,1777054916_8c6748e6bbac5854eb64.jpg', 'Daya Tarik: Pemandangan langsung ke Kawah Talaga Bodas, pemandangan Gunung Cikuray dan Papandayan, serta \"samudra awan\" yang indah.\r\nJalur Pendakian: Terkenal via Basecamp Sagara (lebih ramai) dan jalur Tajur.\r\nMedan: Cocok untuk pemula dengan vegetasi hutan pinus dan tropis.\r\nEstimasi Waktu: Tektok (tidak menginap) dimungkinkan, namun sering dijadikan tempat camping. \r\nWikipedia\r\nWikipedia\r\n +5\r\nAkses Lokasi:\r\nDari pusat kota Garut, menuju ke arah Kecamatan Sucinaraja.\r\nDapat diakses menggunakan kendaraan pribadi.\r\nAkses jalan menuju basecamp (terutama dari arah Terminal Garut) disarankan melalui Gapura Sadang dan di beberapa titik sempit/ekstrem.', '2026-04-24 18:21:56'),
(6, 'Guntur', 'Desa Pasawahan, Kecamatan Tarogong Kaler, Kabupaten Garut, Jawa Barat.', 2249, 'Buka', '1777055119_0b4f78ccf8f8c0bf758d.jpg,1777055119_3d3f2166d1d0cb1255ef.jpg', 'Deskripsi Gunung Guntur:\r\nKetinggian: 2.249 meter di atas permukaan laut (mdpl).\r\nKarakteristik: Memiliki medan pasir/kerikil terjal di bagian atas, vegetasi ilalang, dan panorama kawah yang menawan.\r\nAktivitas: Salah satu gunung paling aktif di Jawa pada tahun 1800-an, namun kini aktif secara \"tidur\" sejak erupsi terakhir tahun 1847.\r\nDaya Tarik: Pemandangan matahari terbit, hamparan pasir, dan lokasi camping yang favorit. \r\nWikipedia\r\nWikipedia\r\n +4\r\nLokasi dan Akses:\r\nAlamat: Desa Pasawahan, Kecamatan Tarogong Kaler, Kabupaten Garut, Jawa Barat.\r\nAkses: Dapat dijangkau dari Jakarta/Bandung menuju terminal Garut, dilanjutkan dengan transportasi lokal ke basecamp (contoh: Basecamp Bu Tati/Citiis).\r\nJalur Pendakian: Umumnya melalui jalur Citiis atau jalur lain di kawasan Tarogong Kaler, dengan waktu tempuh 3-4 jam. \r\nwww.tripadvisor.co.id\r\nwww.tripadvisor.co.id\r\n +4\r\nGunung Guntur merupakan tujuan populer bagi pendaki di Jawa Barat, namun pendaki disarankan selalu memeriksa status aktivitas gunung dan mematuhi peraturan, mengingat sebagian area merupakan cagar alam. ', '2026-04-24 18:25:19'),
(7, 'Bromo', ' Probolinggo, Pasuruan, Lumajang, dan Malang, Jawa Timur.', 2325, 'Buka', '1777055322_091a162593736f4d74b7.jpg,1777055322_e513b132e599c024fd75.jpg', 'Deskripsi Gunung Bromo:\r\nKawah Aktif & Lautan Pasir: Wisatawan dapat mendaki ke bibir kawah yang masih aktif mengeluarkan asap, melintasi hamparan pasir vulkanik yang sering disebut \"Pasir Berbisik\".\r\nGolden Sunrise: Pemandangan matahari terbit yang memukau dari spot seperti Penanjakan, Kingkong Hill, atau Seruni Point.\r\nMedan & Suhu: Kontur berpasir, berbatu, dan terjal. Suhu udara sangat dingin, seringkali di bawah \r\n, membutuhkan pakaian tebal.\r\nKawasan Wisata: Dikelilingi oleh Gunung Batok, Gunung Widodaren, dan Gunung Semeru sebagai latar belakang.\r\nKondisi Fisik: Pemandangan menakjubkan berupa \"negeri di atas awan\" saat matahari terbit. \r\nBATIQA Hotels\r\nBATIQA Hotels\r\n +4\r\nLokasi dan Akses:\r\nAdministrasi: Berada di perbatasan empat kabupaten: Probolinggo, Pasuruan, Lumajang, dan Malang, Jawa Timur.\r\nAkses Utama: Umumnya diakses via Probolinggo (Cemoro Lawang) atau Malang (Tumpang).\r\nTransportasi: Memerlukan kendaraan Jeep 4x4 untuk melintasi lautan pasir. \r\nBATIQA Hotels\r\nBATIQA Hotels\r\n +4\r\nTips Wisata:\r\nGunakan pakaian tebal, syal, sarung tangan, masker (melindungi dari debu pasir), dan sepatu hiking.\r\nWaktu terbaik untuk berkunjung adalah saat musim kemarau untuk memastikan pemandangan sunrise cerah. ', '2026-04-24 18:28:42'),
(8, 'Samalengoh', 'Blok Gunung Gajah, Desa Gunturmekar, Kec. Tanjungkerta, Kabupaten Sumedang.', 804, 'Buka', '1777055452_938503bc9c8629fb3ad0.jpg,1777055452_b0f51a15d1c2e48fadd3.jpg', 'Info dan Deskripsi Samalengoh Camp:\r\nLokasi: Blok Gunung Gajah, Desa Gunturmekar, Kec. Tanjungkerta, Kabupaten Sumedang.\r\nKetinggian: ± 804 meter di atas permukaan laut (MDPL).\r\nDaya Tarik: Tempat kemping dengan pemandangan perbukitan yang asri dan pemandangan kota Sumedang. Tempat ini terkenal dengan pemandangan matahari terbit (sunrise) yang menawan dan sering dijadikan tempat kemping santai.\r\nAkses: Dapat diakses melalui empat desa, yaitu Desa Gunturmekar, Pamarisen, Sukamaju, dan Cibungur.\r\nFasilitas: Tersedia area camping ground, glamping (glamorous camping), dan spot foto. \r\nEiger Adventure Official\r\nEiger Adventure Official\r\n +4\r\nSamalengoh Camp menjadi alternatif tujuan wisata alam yang sejuk dan tidak terlalu jauh dari pusat kota', '2026-04-24 18:30:52'),
(9, 'Semeru', 'Ngampo, Pasrujambe, Lumajang Regency, East Java', 3676, 'Buka', '1777256249_a95cddae32f2232fbaa2.jpg,1777256249_b37d70f1efa86634a2f0.jpg', 'Info Penting Jalur Pendakian Semeru 2026:\r\nStatus: Dibuka terbatas (hanya sampai Ranu Kumbolo) per 24 April 2026 berdasarkan rekomendasi TNBTS.\r\nTitik Awal (Basecamp): Desa Ranu Pani, Lumajang.\r\nBatas Pendakian: Ranu Kumbolo (Pendakian ke puncak Mahameru TIDAK diperbolehkan).\r\nRute Utama: Ranu Pani \r\n Landengan Dowo \r\n Watu Rejeng \r\n Ranu Kumbolo.\r\nEstimasi Waktu: 3-4 jam untuk sampai Ranu Kumbolo. \r\nAntara News jatim\r\nAntara News jatim\r\n +5\r\nLokasi & Geografis:\r\nWilayah: Kabupaten Lumajang dan Kabupaten Malang, Jawa Timur.\r\nKawasan: Taman Nasional Bromo Tengger Semeru (TNBTS).\r\nPuncak: Mahameru.', '2026-04-27 02:17:29');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pesan`
--

CREATE TABLE `pesan` (
  `id_pesan` int(11) NOT NULL,
  `id_pengirim` int(11) NOT NULL,
  `id_penerima` int(11) NOT NULL,
  `isi_pesan` text DEFAULT NULL,
  `file_lampiran` varchar(255) DEFAULT NULL,
  `tipe_pesan` enum('text','image','audio') DEFAULT 'text',
  `status_baca` enum('0','1') DEFAULT '0',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pesan`
--

INSERT INTO `pesan` (`id_pesan`, `id_pengirim`, `id_penerima`, `isi_pesan`, `file_lampiran`, `tipe_pesan`, `status_baca`, `created_at`) VALUES
(117, 10, 2, '', '1777255620_76604e651700c129d93c.png', 'image', '1', '2026-04-27 02:07:00'),
(118, 10, 2, '', '1777255635_22f9cc6b19479e6b9a62.webm', 'audio', '1', '2026-04-27 02:07:15'),
(119, 10, 2, 'hello', NULL, 'text', '1', '2026-04-27 02:07:28'),
(120, 2, 10, 'hai', NULL, 'text', '0', '2026-04-27 02:11:39'),
(121, 2, 10, '', '1777263399_29bc7ef36c39714f7457.png', 'image', '0', '2026-04-27 04:16:39'),
(122, 2, 10, '', '1777263410_9a164acfe0d9f579196a.webm', 'audio', '0', '2026-04-27 04:16:50');

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksi`
--

CREATE TABLE `transaksi` (
  `id_transaksi` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_barang` int(11) NOT NULL,
  `tgl_pinjam` date NOT NULL,
  `tgl_kembali` date NOT NULL,
  `total_harga` decimal(12,2) DEFAULT 0.00,
  `denda` decimal(12,2) DEFAULT 0.00,
  `status_denda` int(11) DEFAULT 0,
  `status_transaksi` enum('Waiting','Booking','Dipinjam','Selesai','Dibatalkan') DEFAULT 'Booking',
  `is_read` tinyint(1) DEFAULT 0,
  `bukti_bayar` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `transaksi`
--

INSERT INTO `transaksi` (`id_transaksi`, `id_user`, `id_barang`, `tgl_pinjam`, `tgl_kembali`, `total_harga`, `denda`, `status_denda`, `status_transaksi`, `is_read`, `bukti_bayar`, `created_at`, `updated_at`) VALUES
(68, 10, 1, '2026-04-21', '2026-04-22', 50000.00, 60000.00, 1, 'Selesai', 1, '1777368391_119393619649266481a4.png', '2026-04-28 09:26:31', '2026-04-28 10:12:48');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `foto` varchar(255) DEFAULT 'default.png',
  `no_wa` varchar(20) DEFAULT NULL,
  `ktp` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id_user`, `nama`, `username`, `password`, `role`, `foto`, `no_wa`, `ktp`, `created_at`, `updated_at`) VALUES
(2, 'administrator', 'fahmi', '$2y$10$FMQRdH0ecCbZZVWtN2n7/u1YZN/gr7X98Er4NG4sqDAWCXlZwmB6S', 'admin', '1777198485_2baf124d588198cbd533.jpg', '08123456789', '1777198485_b4d239da93ae82314bd7.png', '2026-04-26 17:13:44', '2026-04-26 17:25:13'),
(10, 'raafi', 'raafi', '$2y$10$9dFno4Koc69y9Rd4U0kHHejvrqLGJ6K/woSm/smRWcj.qR8FClyW6', 'user', '1777255150_7247e68e9c90bf74bd3a.png', '1234567890', '1777255150_68b7b835047db0f7c452.png', '2026-04-27 08:59:10', '2026-04-27 08:59:10'),
(11, 'ulhaqq', 'ulhaqq', '$2y$10$xICAkWBos39hJ1x6WBqPKuQSBOdpOY7nJJbFnfE6FdSTuQ9Adp/T.', 'user', '1777362105_5d666df5d1cfbe136e5a.jpg', '123', '1777362105_5f1b1a4cc2f9f9fef446.png', '2026-04-28 14:41:45', '2026-04-28 14:41:45');

-- --------------------------------------------------------

--
-- Struktur dari tabel `wishlist`
--

CREATE TABLE `wishlist` (
  `id_wishlist` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `id_barang` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `wishlist`
--

INSERT INTO `wishlist` (`id_wishlist`, `id_user`, `id_barang`, `created_at`) VALUES
(6, 10, 6, '2026-04-27 05:05:49'),
(7, 10, 2, '2026-04-27 05:07:29'),
(9, 10, 12, '2026-04-27 16:29:08'),
(11, 11, 8, '2026-04-28 07:42:19');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`id_barang`);

--
-- Indeks untuk tabel `gunung`
--
ALTER TABLE `gunung`
  ADD PRIMARY KEY (`id_gunung`);

--
-- Indeks untuk tabel `pesan`
--
ALTER TABLE `pesan`
  ADD PRIMARY KEY (`id_pesan`),
  ADD KEY `fk_pengirim` (`id_pengirim`),
  ADD KEY `fk_penerima` (`id_penerima`);

--
-- Indeks untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD KEY `fk_user` (`id_user`),
  ADD KEY `fk_barang` (`id_barang`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indeks untuk tabel `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id_wishlist`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_barang` (`id_barang`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `barang`
--
ALTER TABLE `barang`
  MODIFY `id_barang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `gunung`
--
ALTER TABLE `gunung`
  MODIFY `id_gunung` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `pesan`
--
ALTER TABLE `pesan`
  MODIFY `id_pesan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=123;

--
-- AUTO_INCREMENT untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id_transaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id_wishlist` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `pesan`
--
ALTER TABLE `pesan`
  ADD CONSTRAINT `fk_penerima` FOREIGN KEY (`id_penerima`) REFERENCES `users` (`id_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_pengirim` FOREIGN KEY (`id_pengirim`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `fk_barang` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `wishlist`
--
ALTER TABLE `wishlist`
  ADD CONSTRAINT `wishlist_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `wishlist_ibfk_2` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
