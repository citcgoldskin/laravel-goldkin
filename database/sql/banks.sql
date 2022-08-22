-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.4.11-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             9.4.0.5142
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping structure for table db_senpai.banks
CREATE TABLE IF NOT EXISTS `banks` (
  `bnk_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `bnk_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bnk_prefix` varchar(1) COLLATE utf8_unicode_ci NOT NULL COMMENT '50音順',
  `bnk_fav` tinyint(4) NOT NULL DEFAULT 0,
  `bnk_sort` tinyint(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`bnk_id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table db_senpai.banks: ~8 rows (approximately)
/*!40000 ALTER TABLE `banks` DISABLE KEYS */;
INSERT INTO `banks` (`bnk_id`, `bnk_name`, `bnk_prefix`, `bnk_fav`, `bnk_sort`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, '三菱UFJ銀行', 'ゆ', 1, 0, NULL, NULL, NULL),
	(2, 'みずほ銀行', 'み', 1, 0, NULL, NULL, NULL),
	(3, 'りそな銀行', 'り', 1, 0, NULL, NULL, NULL),
	(4, '埼玉りそな銀行', 'ゆ', 1, 0, NULL, NULL, NULL),
	(5, '三井住友銀行', 'ゆ', 1, 0, NULL, NULL, NULL),
	(6, 'ジャパンネット銀行', 'ゆ', 1, 0, NULL, NULL, NULL),
	(7, '楽天銀行', 'ゆ', 1, 0, NULL, NULL, NULL),
	(8, 'ゆうちょ銀行', 'ゆ', 1, 0, NULL, NULL, NULL),
	(9, '相生市農業協力組合', 'あ', 0, 0, NULL, NULL, NULL),
	(10, 'アイオー信用金庫', 'あ', 0, 0, NULL, NULL, NULL),
	(11, '愛知銀行', 'あ', 0, 0, NULL, NULL, NULL),
	(12, 'あいち海部農業協同組合', 'あ', 0, 0, NULL, NULL, NULL),
	(13, '愛知県北農業協同組合', 'あ', 0, 0, NULL, NULL, NULL),
	(14, '愛知県医師信用組合', 'あ', 0, 0, NULL, NULL, NULL),
	(15, '愛知県医療信用組合', 'あ', 0, 0, NULL, NULL, NULL),
	(16, '愛知県警察信用組合', 'あ', 0, 0, NULL, NULL, NULL),
	(17, '愛知県信用漁業協同組合連合会', 'あ', 0, 0, NULL, NULL, NULL),
	(18, '愛知県信用農業協同組合連合会', 'あ', 0, 0, NULL, NULL, NULL);
/*!40000 ALTER TABLE `banks` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
