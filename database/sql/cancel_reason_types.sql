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

-- Dumping data for table db_senpai.cancel_reason_types: ~5 rows (approximately)
/*!40000 ALTER TABLE `cancel_reason_types` DISABLE KEYS */;
INSERT INTO `cancel_reason_types` (`crt_id`, `crt_kind`, `crt_content`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 0, '間違えてレッスンを購入した', NULL, NULL, NULL),
	(2, 0, '急用ができた', NULL, NULL, NULL),
	(3, 0, '当日の天気が心配', NULL, NULL, NULL),
	(4, 0, '体調がすぐれない', NULL, NULL, NULL),
	(5, 0, 'その他', NULL, NULL, NULL),
	(6, 2, '間違えてレッスンを承認した', NULL, NULL, NULL),
	(7, 2, '急用ができた', NULL, NULL, NULL),
	(8, 2, '当日の天気が心配', NULL, NULL, NULL),
	(9, 2, '体調がすぐれない', NULL, NULL, NULL),
	(10, 2, 'その他', NULL, NULL, NULL),
	(11, 1, '予定が合わない', NULL, NULL, NULL),
	(12, 1, '当日の天気が心配', NULL, NULL, NULL),
	(13, 1, '提案された場所が遠い', NULL, NULL, NULL),
	(14, 1, 'その他', NULL, NULL, NULL);
/*!40000 ALTER TABLE `cancel_reason_types` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
