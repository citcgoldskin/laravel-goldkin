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

-- Dumping structure for table db_senpai.msg_classes
CREATE TABLE IF NOT EXISTS `msg_classes` (
  `mc_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mc_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `mc_sort` tinyint(3) unsigned NOT NULL DEFAULT 255,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`mc_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table db_senpai.msg_classes: ~7 rows (approximately)
/*!40000 ALTER TABLE `msg_classes` DISABLE KEYS */;
INSERT INTO `msg_classes` (`mc_id`, `mc_name`, `mc_sort`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, '取引関連', 1, NULL, NULL, NULL),
	(2, 'メッセージ', 2, NULL, NULL, NULL),
	(3, 'フォロー・お気に入り', 3, NULL, NULL, NULL),
	(4, '機能更新・メンテナンス', 4, NULL, NULL, NULL),
	(5, 'おすすめ・サービス', 5, NULL, NULL, NULL),
	(6, 'ニュース', 6, NULL, NULL, NULL),
	(7, 'あなた宛のお知らせ', 7, NULL, NULL, NULL);
/*!40000 ALTER TABLE `msg_classes` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
