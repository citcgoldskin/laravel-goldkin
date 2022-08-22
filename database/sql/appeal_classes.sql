-- --------------------------------------------------------
-- Host:                         localhost
-- Server version:               10.4.11-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             9.4.0.5142
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping data for table db_senpai.appeal_classes: ~0 rows (approximately)
/*!40000 ALTER TABLE `appeal_classes` DISABLE KEYS */;
INSERT INTO `appeal_classes` (`id`, `name`, `sort`, `created_at`, `updated_at`) VALUES
	(1, '性的いやがらせ／出会い目的', 0, NULL, NULL),
	(2, '迷惑行為', 1, NULL, NULL),
	(3, 'スパム／宣伝目的', 2, NULL, NULL),
	(4, '悪質なキャンセル', 4, NULL, NULL),
	(5, 'その他', 5, NULL, NULL);
/*!40000 ALTER TABLE `appeal_classes` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
