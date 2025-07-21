-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for faaroti_db
CREATE DATABASE IF NOT EXISTS `faaroti_db` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `faaroti_db`;

-- Dumping structure for table faaroti_db.cache
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table faaroti_db.cache: ~8 rows (approximately)
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
	('133a60413d6f7375602ff1638994a02f', 'i:1;', 1752350419),
	('133a60413d6f7375602ff1638994a02f:timer', 'i:1752350419;', 1752350419),
	('66c67220b06bae60b35bf4e131419342', 'i:2;', 1752906533),
	('66c67220b06bae60b35bf4e131419342:timer', 'i:1752906533;', 1752906533),
	('da4b9237bacccdf19c0760cab7aec4a8359010b0', 'i:1;', 1752433637),
	('da4b9237bacccdf19c0760cab7aec4a8359010b0:timer', 'i:1752433637;', 1752433637),
	('livewire-rate-limiter:a17961fa74e9275d529f489537f179c05d50c2f3', 'i:1;', 1753027052),
	('livewire-rate-limiter:a17961fa74e9275d529f489537f179c05d50c2f3:timer', 'i:1753027052;', 1753027052);

-- Dumping structure for table faaroti_db.cache_locks
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table faaroti_db.cache_locks: ~0 rows (approximately)

-- Dumping structure for table faaroti_db.cart
CREATE TABLE IF NOT EXISTS `cart` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `quantity` int unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cart_user_id_foreign` (`user_id`),
  KEY `cart_product_id_foreign` (`product_id`),
  CONSTRAINT `cart_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cart_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table faaroti_db.cart: ~0 rows (approximately)

-- Dumping structure for table faaroti_db.categories
CREATE TABLE IF NOT EXISTS `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_kategori` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table faaroti_db.categories: ~3 rows (approximately)
INSERT INTO `categories` (`id`, `nama_kategori`, `deleted_at`, `created_at`, `updated_at`) VALUES
	(1, 'Bread', NULL, '2025-07-11 14:00:04', '2025-07-11 14:00:04'),
	(2, 'Cake', NULL, '2025-07-11 14:00:09', '2025-07-11 14:00:09'),
	(3, 'Dessert', NULL, '2025-07-11 14:00:16', '2025-07-11 14:00:16');

-- Dumping structure for table faaroti_db.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table faaroti_db.failed_jobs: ~0 rows (approximately)

-- Dumping structure for table faaroti_db.jobs
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table faaroti_db.jobs: ~0 rows (approximately)

-- Dumping structure for table faaroti_db.job_batches
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table faaroti_db.job_batches: ~0 rows (approximately)

-- Dumping structure for table faaroti_db.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table faaroti_db.migrations: ~10 rows (approximately)
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '0001_01_01_000000_create_users_table', 1),
	(2, '0001_01_01_000001_create_cache_table', 1),
	(3, '0001_01_01_000002_create_jobs_table', 1),
	(4, '2025_07_09_180302_create_categories_table', 1),
	(5, '2025_07_09_180441_create_products_table', 1),
	(6, '2025_07_10_065530_create_transactions_table', 1),
	(7, '2025_07_10_114319_create_transaction_items_table', 1),
	(8, '2025_07_10_163243_add_two_factor_columns_to_users_table', 1),
	(9, '2025_07_10_164121_create_personal_access_tokens_table', 1),
	(10, '2025_07_12_073822_cart', 2);

-- Dumping structure for table faaroti_db.password_reset_tokens
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table faaroti_db.password_reset_tokens: ~0 rows (approximately)

-- Dumping structure for table faaroti_db.personal_access_tokens
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table faaroti_db.personal_access_tokens: ~0 rows (approximately)

-- Dumping structure for table faaroti_db.products
CREATE TABLE IF NOT EXISTS `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_produk` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `foto_produk` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `harga` bigint unsigned NOT NULL,
  `stok` bigint unsigned NOT NULL,
  `is_popular` tinyint(1) NOT NULL,
  `category_id` bigint unsigned NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `products_category_id_foreign` (`category_id`),
  CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table faaroti_db.products: ~11 rows (approximately)
INSERT INTO `products` (`id`, `nama_produk`, `deskripsi`, `foto_produk`, `harga`, `stok`, `is_popular`, `category_id`, `deleted_at`, `created_at`, `updated_at`) VALUES
	(1, 'Brownis Coklat', 'Nikmati setiap gigitan brownies cokelat fudgy kami yang kaya rasa dan lezat. Dengan tekstur lembut di dalam dan toping cokelat melimpah, brownies ini sempurna untuk menemani setiap momen spesial Anda!', 'products/01JZXMR51018G3C2PSTAFJ5JQE.jpg', 15000, 2, 1, 2, NULL, '2025-07-11 14:02:36', '2025-07-18 23:32:15'),
	(2, 'Brownis Coklat fudgy', 'Nikmati kelezatan brownies cokelat fudgy kami, diperkaya dengan kombinasi unik taburan irisan almond panggang yang renyah dan parutan keju cheddar gurih. Perpaduan rasa manis, pahit cokelat, dan sedikit sentuhan asin yang sempurna!', 'products/01JZXN20K38AQ5JYA15Z2CE8JY.jpg', 10000, 15, 1, 2, NULL, '2025-07-11 14:07:59', '2025-07-11 14:07:59'),
	(3, 'Brownis Coklat Keju dan Kacang', 'Brownies cokelat fudgy kami hadir dalam berbagai pilihan topping yang menggoda selera: taburan kacang renyah dan parutan keju yang creamy. Dibuat dengan bahan-bahan terbaik untuk kelezatan maksimal di setiap potongan.', 'products/01JZXN4ZJ1G2JMVM6P37JPVTJH.jpg', 15000, 9, 0, 2, NULL, '2025-07-11 14:09:36', '2025-07-12 04:22:01'),
	(4, 'Roti Bundar', 'Nikmati kelembutan roti bundar kami yang berwarna keemasan. Teksturnya yang halus dan aromanya yang menggoda menjadikannya teman sempurna untuk kopi pagi atau camilan sore Anda.', 'products/01JZXN7FPKBD43MKXKVZEKN1TX.jpg', 5000, 6, 0, 1, NULL, '2025-07-11 14:10:59', '2025-07-12 13:13:50'),
	(5, 'Roti Sosis Keju', 'Nikmati roti sosis keju panggang dengan tampilan menggoda dan aroma harum. Setiap gigitan menawarkan perpaduan sempurna antara roti lembut, potongan sosis pilihan, dan keju lezat yang meleleh, dipercantik dengan taburan bumbu rempah.', 'products/01JZXN9D9DXC2KW3G73HJKHX3Z.jpg', 6000, 7, 1, 1, NULL, '2025-07-11 14:12:02', '2025-07-12 13:06:30'),
	(6, 'Roti Hijau', 'Nikmati keindahan dan kelezatan roti hijau yang unik ini! Dengan sentuhan warna alami dan taburan renyah, setiap gigitan adalah perpaduan sempurna antara kelembutan roti dan cita rasa yang memikat.', 'products/01JZXNC337FVSFYY6V1GMKC497.jpg', 5000, 7, 0, 1, NULL, '2025-07-11 14:13:29', '2025-07-12 13:13:50'),
	(7, 'Roti Varian Rasa', 'Aneka roti fresh kemasan individual siap memanjakan lidah Anda! Beragam varian rasa dan topping dalam setiap kemasan praktis, cocok untuk camilan kapan saja dan di mana saja.', 'products/01JZXNF1XJ5CJQ5Y3K1AE9D1KT.jpg', 5000, 20, 0, 1, NULL, '2025-07-11 14:15:07', '2025-07-11 14:15:07'),
	(8, 'Dessert Coklat Putih', 'Akhiri hidangan Anda dengan sentuhan manis dari koleksi dessert kami yang beragam. Dari kelezatan klasik hingga inovasi modern, setiap sajian dirancang untuk memanjakan lidah dan memberikan pengalaman penutup yang tak terlupakan. Pilih favorit Anda dan biarkan diri Anda larut dalam kenikmatan!', 'products/01JZXNQ7A8F6RB2BFDCBPX9WQP.jpg', 15000, 8, 1, 3, NULL, '2025-07-11 14:19:34', '2025-07-12 13:06:30'),
	(9, 'Dessert Coklat ', 'Sempurnakan momen istimewa Anda dengan pilihan dessert premium kami. Dibuat dengan bahan-bahan terbaik dan penuh ketelitian, setiap hidangan penutup kami menjanjikan kelezatan yang memuaskan dan kesan manis yang bertahan lama.', 'products/01JZXNSK0K6PEFEAMTNKJJZ4GM.jfif', 12000, 15, 1, 3, NULL, '2025-07-11 14:20:52', '2025-07-11 14:20:52'),
	(10, 'Bolu Maniss Lumer', 'Enakkk bangett', 'products/01K02JSJZ7TB07VAWBZZ05NZC4.jpg', 20000, 15, 1, 2, '2025-07-13 12:05:58', '2025-07-13 12:04:38', '2025-07-13 12:05:58'),
	(11, 'Bolu Maniss Lumer', 'Enakkk bangettt', 'products/01K02JX21ZA7FJPJ6GCMV05Q4C.jpg', 20000, 15, 0, 2, NULL, '2025-07-13 12:06:32', '2025-07-13 12:06:32');

-- Dumping structure for table faaroti_db.sessions
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table faaroti_db.sessions: ~3 rows (approximately)
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
	('ghHhb4ZHnMx8RLGhR28Thq2tQDiZ3MshxGk95kF0', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'YTo3OntzOjY6Il90b2tlbiI7czo0MDoidVRzb0FTV2g4cEJUQWZDeDNqM25rS3phNlhld1JMbEpmTWdHcHB3biI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9yZXBvcnRzIjt9czozOiJ1cmwiO2E6MDp7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjI7czoxNzoicGFzc3dvcmRfaGFzaF93ZWIiO3M6NjA6IiQyeSQxMiRnUG1veWMvOXJ4SDFxVlVXMzJXckdlZGRrODREckFGTWhWNEQxM3Nac05kaHBFR1B5VDJQSyI7czo4OiJmaWxhbWVudCI7YTowOnt9fQ==', 1753027545),
	('TidUJ3rTTWUhGx7JR6YRu4C8FuSR3qfu9fdnHSiH', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiNWdxUEI5VG52NlRZMXN3OGF1c3Q3MjJWOTRmR3dnY2dJQ0NzNFlUaSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1753026941),
	('woNGPIxO9AN5V77Jg2N6LmVPQQ0y0JbreEybdH0k', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoicW4zTGNGZkN4NldyWk1QcndHc3BNbDE4ZW1XNnFhTkMxaUo3SElJbyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1753026053);

-- Dumping structure for table faaroti_db.transactions
CREATE TABLE IF NOT EXISTS `transactions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `trx_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_quantity` bigint unsigned NOT NULL DEFAULT '0',
  `total_sub_total` bigint unsigned NOT NULL DEFAULT '0',
  `grand_total` bigint unsigned NOT NULL DEFAULT '0',
  `status` enum('pending','dikirim','selesai','dibatalkan') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `payment_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_status` enum('pending','settlement','challenge') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `payment_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `user_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `transactions_trx_id_unique` (`trx_id`),
  KEY `transactions_user_id_foreign` (`user_id`),
  CONSTRAINT `transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table faaroti_db.transactions: ~3 rows (approximately)
INSERT INTO `transactions` (`id`, `trx_id`, `name`, `email`, `phone`, `city`, `post_code`, `address`, `total_quantity`, `total_sub_total`, `grand_total`, `status`, `payment_type`, `payment_status`, `payment_url`, `user_id`, `created_at`, `updated_at`) VALUES
	(17, 'FRT-BAYUMVAGJS', 'Ridho Aziztatiko', 'ridhoaziz18@gmail.com', '082233444888', 'Cilegon', '42155', 'Jl. Kavling Blok F No.56', 5, 48000, 58000, 'pending', 'qris', 'settlement', 'bbb9ba3c-0526-457f-b60b-849f12de30ac', 3, '2025-07-12 13:06:30', '2025-07-12 13:06:31'),
	(18, 'FRT-ZEVDNSR7OP', 'Ridho Aziztatiko', 'ridhoaziz18@gmail.com', '+6287887967328', 'Cilegon', '42166', 'Jl. Kavling Cilegon', 6, 30000, 40000, 'pending', 'qris', 'settlement', '3ae71d7d-5532-46ec-ba1d-96b37c4d2055', 3, '2025-07-12 13:13:50', '2025-07-12 13:13:51'),
	(19, 'FRT-1JXLIP8YNA', 'Ridho Aziztatiko', 'ridhoaziz18@gmail.com', '+6287887967328', 'Anyer', '42166', 'Kp.Kamurang Pal\nJl. Raya Mancak', 1, 15000, 25000, 'pending', 'qris', 'settlement', 'd05ee5a6-c9d1-4362-bdfd-3e3a641dbad1', 3, '2025-07-18 23:29:52', '2025-07-18 23:29:54');

-- Dumping structure for table faaroti_db.transaction_items
CREATE TABLE IF NOT EXISTS `transaction_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `transaction_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `product_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_photo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` int unsigned NOT NULL,
  `price_at_purchase` bigint unsigned NOT NULL,
  `sub_total_item` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `transaction_items_transaction_id_foreign` (`transaction_id`),
  KEY `transaction_items_product_id_foreign` (`product_id`),
  CONSTRAINT `transaction_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `transaction_items_transaction_id_foreign` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table faaroti_db.transaction_items: ~5 rows (approximately)
INSERT INTO `transaction_items` (`id`, `transaction_id`, `product_id`, `product_name`, `product_photo`, `quantity`, `price_at_purchase`, `sub_total_item`, `created_at`, `updated_at`) VALUES
	(33, 17, 5, 'Roti Sosis Keju', 'products/01JZXN9D9DXC2KW3G73HJKHX3Z.jpg', 3, 6000, 18000, '2025-07-12 13:06:30', '2025-07-12 13:06:30'),
	(34, 17, 8, 'Dessert Coklat Putih', 'products/01JZXNQ7A8F6RB2BFDCBPX9WQP.jpg', 2, 15000, 30000, '2025-07-12 13:06:30', '2025-07-12 13:06:30'),
	(35, 18, 4, 'Roti Bundar', 'products/01JZXN7FPKBD43MKXKVZEKN1TX.jpg', 3, 5000, 15000, '2025-07-12 13:13:50', '2025-07-12 13:13:50'),
	(36, 18, 6, 'Roti Hijau', 'products/01JZXNC337FVSFYY6V1GMKC497.jpg', 3, 5000, 15000, '2025-07-12 13:13:50', '2025-07-12 13:13:50'),
	(37, 19, 1, 'Brownis Coklat', 'products/01JZXMR51018G3C2PSTAFJ5JQE.jpg', 1, 15000, 15000, '2025-07-18 23:29:52', '2025-07-18 23:29:52');

-- Dumping structure for table faaroti_db.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('user','admin') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `two_factor_secret` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `two_factor_recovery_codes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `two_factor_confirmed_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `current_team_id` bigint unsigned DEFAULT NULL,
  `profile_photo_path` varchar(2048) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table faaroti_db.users: ~3 rows (approximately)
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `two_factor_secret`, `two_factor_recovery_codes`, `two_factor_confirmed_at`, `remember_token`, `current_team_id`, `profile_photo_path`, `created_at`, `updated_at`) VALUES
	(1, 'Adimas Rizki', 'adimasrizki926@gmail.com', NULL, '$2y$12$0AXOMEOhOqgqsPKi/z6tv.yYO3cYALnVy7UzIPJhBageLfNCx7TeG', 'user', NULL, NULL, NULL, 'ecthunntvxDZYYLxdl28PO51VQZBjHaMegYPJcFnatm986MltzVxFjuOPOSo', NULL, NULL, '2025-07-10 10:06:25', '2025-07-12 04:03:47'),
	(2, 'Admin FaaRoti', 'admin@example.com', NULL, '$2y$12$gPmoyc/9rxH1qVUW32WrGeddk84DrAFMhV4D13sZsNdhpEGPyT2PK', 'admin', NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-10 11:24:29', '2025-07-10 11:24:29'),
	(3, 'Ridho Aziztatiko', 'ridhoaziz18@gmail.com', NULL, '$2y$12$zlmsdkJusUw/OidMb1ieme0o7d1IAClL7FDADzHPsLwiQ723ZEjxC', 'user', NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-11 14:49:01', '2025-07-11 14:49:01');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
