-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: ventura_v2
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `barang`
--

DROP TABLE IF EXISTS `barang`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `barang` (
  `id_barang` int(11) NOT NULL AUTO_INCREMENT,
  `nama_barang` varchar(255) NOT NULL,
  `kategori` varchar(100) NOT NULL,
  `stok` int(11) NOT NULL DEFAULT 0,
  `harga_sewa` decimal(10,2) NOT NULL DEFAULT 0.00,
  `kondisi` varchar(50) NOT NULL,
  `foto_barang` text DEFAULT 'tenda.jpg',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `views` int(11) DEFAULT 0,
  PRIMARY KEY (`id_barang`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `barang`
--

LOCK TABLES `barang` WRITE;
/*!40000 ALTER TABLE `barang` DISABLE KEYS */;
INSERT INTO `barang` VALUES (1,'Tenda Dome Kapasitas 4','Tenda',3,50000.00,'Baik','1775965237_cf467619023f4dee7179.png,1775965251_0d4f2f1eca7d66c7210d.png','2026-04-12 01:48:03','2026-04-20 17:45:24',13),(2,'Tracking pool','Tracking Pool',8,20000.00,'Baik','1775965180_2e90ae4b7d87a67e5bf2.png,1775965180_6ddd6344f4fe64209fff.png','2026-04-12 03:39:40','2026-04-20 04:44:46',14),(3,'Sepatu Lavio','Sepatu',20,30000.00,'Baik','1775965375_70e66a5530020ab8508f.png,1775965388_d6034488336a4aa52373.png','2026-04-12 03:42:38','2026-04-18 06:09:46',3),(4,'Sandal Reward','Sandal',-1,20000.00,'Baik','1775965472_ba926e6f5c2fe4e16ea1.png,1775965472_ba7da16fd15565f542fd.png','2026-04-12 03:44:32','2026-04-19 08:50:36',2),(5,'Sandal TapiLaku','Sandal',23,20000.00,'Baik','1775965521_d71d4e165bf01788919e.png,1775965543_39353c8cf2283053b51f.png','2026-04-12 03:45:21','2026-04-18 05:17:01',3),(6,'Kompor Portable Hi-Cook','Kompor',36,15000.00,'Baik','1775965624_727f125b8a627635b317.png,1775965644_a29b35ad4b6dabc92c2f.png,1775965644_237c804605b7929ab8ec.png','2026-04-12 03:47:04','2026-04-20 04:56:21',5),(7,'Tenda Dome Arpenaz 4P','Tenda',49,50000.00,'Baik','1775965693_c85c2cd3667fc10261c2.png,1775965711_353419dfd56893bed6f5.png,1775965711_30adc7954e1c73f7fca3.png','2026-04-12 03:48:13','2026-04-16 06:03:50',0),(8,'sepatu salomon','Sepatu',31,50000.00,'Baik','1775965763_4eefeacaece93f529162.png','2026-04-12 03:49:23','2026-04-18 15:44:03',1),(10,'jaket','Jaket',11,12000.00,'Baik','1776406697_ce2949f8ef0d9d7de47d.png,1776406708_b768a96506a45bc366bb.png','2026-04-17 02:33:22','2026-04-18 05:17:36',4);
/*!40000 ALTER TABLE `barang` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gunung`
--

DROP TABLE IF EXISTS `gunung`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gunung` (
  `id_gunung` int(11) NOT NULL AUTO_INCREMENT,
  `nama_gunung` varchar(100) DEFAULT NULL,
  `lokasi` varchar(100) DEFAULT NULL,
  `ketinggian` int(11) DEFAULT NULL,
  `status` enum('Buka','Tutup','Waspada') DEFAULT 'Buka',
  `foto` varchar(255) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_gunung`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gunung`
--

LOCK TABLES `gunung` WRITE;
/*!40000 ALTER TABLE `gunung` DISABLE KEYS */;
INSERT INTO `gunung` VALUES (1,'merbabu','Suroteleng, Selo, Boyolali Regency, Central Java',3145,'Buka','1776540599_88be635d28b4b62d9dd1.jpg','Jalur Selo (Boyolali):\r\nKarakteristik: Terpopuler, akses mudah, pemandangan sabana luas, pemandangan Gunung Merapi dari dekat.\r\nWaktu Tempuh: Sekitar \r\n\r\n jam.\r\nCocok untuk: Semua pendaki (pemula maupun berpengalaman).\r\nJalur Suwanting (Magelang):\r\nKarakteristik: Terkenal berat, menanjak, terjal, pemandangan sabana sabana yang sangat indah, suhu dingin (\r\n\r\n derajat Celsius).\r\nWaktu Tempuh: Sekitar \r\n\r\n jam.\r\nCocok untuk: Pendaki berpengalaman/fisik prima.\r\nJalur Wekas (Magelang):\r\nKarakteristik: Jalur paling cepat, sumber air ada di sekitar pos 2, ramah pemula.\r\nWaktu Tempuh: Sekitar \r\n\r\n jam.\r\nCocok untuk: Pemula.\r\nJalur Cuntel (Kopeng):\r\nKarakteristik: Jalur cukup panjang dengan medan menanjak dan melalui hutan, pemandangan indah.\r\nWaktu Tempuh: Sekitar \r\n\r\n jam.\r\nJalur Thekelan (Kopeng):\r\nKarakteristik: Salah satu jalur tertua, melewati 7 puncak (Watu Gubuk, Pemancar, Geger Sapi, Syarif, Ondo Rante, Kenteng Songo, Triangulasi), pemandangan dramatis.\r\nWaktu Tempuh: Sekitar \r\n\r\n jam.','2026-04-18 19:29:59'),(2,'slamet','Cipendawa, Pacet, Cianjur Regency, West Java',3458,'Tutup','1776541410_02b176822493fd3416d6.jpg','Jalur Utama:\r\nBambangan (Purbalingga): Jalur terpopuler, 9 pos, via hutan tropis, waktu tempuh ~10 jam.\r\nPermadi Guci (Tegal): Populer, ada ojek hingga pos 1 (Rp50.000) atau pintu rimba (Rp25.000), sumber air melimpah.\r\nBaturraden (Banyumas): Jalur yang menantang dan terjal.','2026-04-18 19:43:30'),(3,'Papandayan','Karamat Wangi, Cisurupan, Garut Regency, West Java',2665,'Buka','1776545995_9501324e9b166774abab.webp,1776545995_9f5a1528f21c3ec0383a.jpg,1776545995_7f4c803f69550d54e9d2.jpg,1776545995_edfaf292abb88740e1f4.jpeg','Informasi Jalur & Estimasi Waktu\r\nTotal Jalur: Terdapat 10 pos, namun pendakian sesungguhnya sering dihitung dari Pos 4.\r\nEstimasi Waktu:\r\nCamp David - Kawah: 30–45 menit.\r\nKawah - Pondok Saladah: 1,5–2 jam.\r\nPondok Saladah - Hutan Mati: 15–30 menit.\r\nPondok Saladah - Tegal Alun: 2,5–3 jam.\r\nKondisi Jalur: Didominasi tanah, berbatu, dan area terbuka yang terik. Terdapat jembatan dan sungai kecil setelah Goberhood. \r\nEiger Adventure Official\r\nEiger Adventure Official\r\n +3\r\nInformasi Pendakian\r\nLokasi: Kecamatan Cisurupan, Kabupaten Garut.\r\nBuka: 24 jam.\r\nBiaya (Nusantara - Hari Kerja): Tiket masuk Rp30.000, Roda 2 Rp17.000, Roda 4 Rp35.000 (harga dapat berubah).\r\nFasilitas: Warung, toilet, dan mushola di area camp, serta banyak opsi ojek motor dari Camp David ke area dekat Kawah.\r\nWaktu Terbaik: Bulan Februari-Juni dan Oktober-Desember. \r\nTrip.com Indonesia\r\nTrip.com Indonesia\r\n +4\r\nTips: Gunakan masker karena jalur melewati kawah aktif dengan aroma belerang yang menyengat. Pendakian tektok (satu hari) sangat dimungkinkan karena medannya tidak terlalu berat.','2026-04-18 20:45:58'),(4,'Gunung gede','Cipendawa, Pacet, Cianjur Regency, West Java',2958,'Buka','1776546480_16c4388e325d28ce3f75.webp,1776546480_de334f9038298043f0bd.webp,1776546480_fad073f866d96170b2f0.jpeg','Berikut adalah detail informasi mengenai Gunung Gede:\r\nStatus Pendakian: Dibuka per 13 April 2026, berdasarkan Surat Edaran Nomor 06 Tahun 2026, setelah sempat ditutup untuk pemulihan ekosistem.\r\nKetinggian: 2.958 meter di atas permukaan laut (mdpl).\r\nLokasi: Taman Nasional Gunung Gede Pangrango (TNGGP), Jawa Barat.\r\nJalur Pendakian:\r\nCibodas (Cianjur): Jalur paling populer, terawat, dan banyak sumber air (6-8 jam).\r\nGunung Putri (Cianjur): Jalur lebih cepat namun lebih terjal.\r\nSelabintana (Sukabumi): Jalur terpanjang dengan pemandangan hutan lebat.\r\nBiaya (Tiket Masuk): Sekitar Rp29.000 (hari kerja) - Rp34.000 (hari libur) untuk WNI.\r\nAturan Penting:\r\nWajib booking online.\r\nMembawa turun kembali sampah.\r\nDilarang melakukan pendakian ilegal. ','2026-04-18 21:08:00');
/*!40000 ALTER TABLE `gunung` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaksi`
--

DROP TABLE IF EXISTS `transaksi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transaksi` (
  `id_transaksi` int(11) NOT NULL AUTO_INCREMENT,
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
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_transaksi`),
  KEY `fk_user` (`id_user`),
  KEY `fk_barang` (`id_barang`),
  CONSTRAINT `fk_barang` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`) ON DELETE CASCADE,
  CONSTRAINT `fk_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaksi`
--

LOCK TABLES `transaksi` WRITE;
/*!40000 ALTER TABLE `transaksi` DISABLE KEYS */;
INSERT INTO `transaksi` VALUES (10,3,1,'2026-05-11','2026-05-16',250000.00,0.00,0,'Selesai',1,'1776052523_d556ff1ec00a81b32bab.png','2026-04-13 03:55:23','2026-04-13 04:41:10'),(12,2,2,'2026-04-15','2026-04-18',60000.00,0.00,0,'',1,'1776233934_b512ad35fffe4a56fa93.png','2026-04-15 06:18:54','2026-04-16 02:49:51'),(13,2,3,'2026-04-16','2026-04-18',60000.00,0.00,0,'Selesai',1,'1776308257_46148d54d33f28efc15b.png','2026-04-16 02:57:37','2026-04-16 06:11:02'),(14,2,1,'2026-04-16','2026-04-18',100000.00,0.00,0,'',1,'1776315277_229bd3d6dfdf05eccf13.png','2026-04-16 04:54:37','2026-04-16 04:55:20'),(17,2,2,'2026-04-18','2026-04-28',200000.00,0.00,0,'',1,'1776445153_13e30835d71f59cb1f31.png','2026-04-17 16:59:13','2026-04-17 17:00:05'),(19,2,3,'2026-04-17','2026-04-28',330000.00,10000.00,1,'Selesai',1,'1776450191_af1d1274a54bc27ddd42.png','2026-04-17 18:23:11','2026-04-17 18:25:41'),(23,2,1,'2026-04-18','2026-04-21',150000.00,0.00,0,'Dibatalkan',1,'1776478065_964a47b7dd3b44f7d3e1.png','2026-04-18 02:07:45','2026-04-18 02:08:12'),(24,2,1,'2026-04-18','2026-04-19',50000.00,0.00,0,'Dipinjam',1,'1776479093_2a23a73581959d3f6ac6.png','2026-04-18 02:24:53','2026-04-20 06:39:55'),(25,2,2,'2026-04-18','2026-04-19',20000.00,0.00,0,'Waiting',0,'1776479182_12085edbacc44c137105.png','2026-04-18 02:26:22','2026-04-18 02:26:22'),(26,2,10,'2026-04-18','2026-04-21',36000.00,0.00,0,'Dipinjam',0,'1776487803_0a6b7e4ded1eee5e767a.png','2026-04-18 04:50:03','2026-04-18 04:50:37'),(27,2,8,'2026-04-18','2026-04-20',100000.00,0.00,0,'Booking',1,'1776527031_85456436b72b57a53f74.png','2026-04-18 15:43:51','2026-04-18 15:44:46'),(28,4,4,'2026-04-19','2026-04-20',20000.00,0.00,0,'Booking',1,'1776585359_34ce88a0156788ca556e.png','2026-04-19 07:55:59','2026-04-19 07:56:55'),(29,4,2,'2026-04-19','2026-04-20',20000.00,0.00,0,'Waiting',0,'1776588017_60eee7898ed4a2dcb935.png','2026-04-19 08:40:17','2026-04-19 08:40:17'),(30,4,2,'2026-04-19','2026-04-24',100000.00,0.00,0,'Waiting',1,'1776588355_381c16e9c30871cd6c45.png','2026-04-19 08:45:55','2026-04-20 07:31:44');
/*!40000 ALTER TABLE `transaksi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `foto` varchar(255) DEFAULT 'default.png',
  `no_wa` varchar(20) DEFAULT NULL,
  `ktp` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Administrator','fahmi','$2y$10$FMQRdH0ecCbZZVWtN2n7/u1YZN/gr7X98Er4NG4sqDAWCXlZwmB6S','admin','1775970874_f8503dff7a155ca040c8.jpg','08123456789','1775970837_d3fddb7ccf7a26753806.png','2026-04-12 01:42:37','2026-04-15 13:28:17'),(2,'raafi','raafi','$2y$10$9mGagiqKAZX0o1B4Hc5LG.9iNw/UsJimd6jYjjlM069Zh2WxpPZgG','user','1776315231_f9df6384fd9f006afbb7.png','16','1776315231_2bc39a41324f57c293de.png','2026-04-12 01:55:09','2026-04-20 11:31:00'),(3,'ulhaqq','ulhaqq','$2y$10$ebjKlSgWoSdf2SYNsRCck.R/57Pbkfiz3lTJfz4bHBQCHFhKlvUlm','','1776052312_60339fac82c909e4a339.png','08123456789','1776052312_378c1f88c234f3735521.png','2026-04-13 10:51:52','2026-04-13 11:46:35'),(4,'ijal','ijal','$2y$10$KsN9DPbbPVFjpsfS25DY6.d4pwu/gqrtGuQ9ZYP0bX90Ej.HqxEfa','user','1776395793_48d636d2b42267cb5580.png','4044848494','1776235329_273b02958c9c3611a9c2.png','2026-04-15 13:42:09','2026-04-17 10:16:33');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wishlist`
--

DROP TABLE IF EXISTS `wishlist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wishlist` (
  `id_wishlist` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) DEFAULT NULL,
  `id_barang` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id_wishlist`),
  KEY `id_user` (`id_user`),
  KEY `id_barang` (`id_barang`),
  CONSTRAINT `wishlist_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE,
  CONSTRAINT `wishlist_ibfk_2` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wishlist`
--

LOCK TABLES `wishlist` WRITE;
/*!40000 ALTER TABLE `wishlist` DISABLE KEYS */;
INSERT INTO `wishlist` VALUES (1,2,1,'2026-04-20 17:45:17');
/*!40000 ALTER TABLE `wishlist` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-04-21  1:34:31
