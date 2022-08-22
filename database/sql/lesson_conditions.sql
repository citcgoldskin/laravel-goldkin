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

-- Dumping data for table db_senpai_old.lesson_conditions: ~12 rows (approximately)
/*!40000 ALTER TABLE `lesson_conditions` DISABLE KEYS */;
INSERT INTO `lesson_conditions` (`lc_id`, `lc_name`, `lc_sort`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, '手ぶらOK', 255, NULL, NULL, NULL),
	(2, '顔写真あり', 255, NULL, NULL, NULL),
	(3, 'マスク着用', 255, NULL, NULL, NULL),
	(4, '複数人対応', 255, NULL, NULL, NULL),
	(5, '出勤リクエスト\r\n受付可', 255, NULL, NULL, NULL),
	(6, '本人確認必須', 255, NULL, NULL, NULL),
	(7, '相談エリア内のみ受付可', 255, NULL, NULL, NULL),
	(8, '相談エリア内\r\n出張交通費なし', 255, NULL, NULL, NULL),
	(9, '相談エリア外\r\nでも出張可', 255, NULL, NULL, NULL),
	(10, '上級者にも対応', 255, NULL, NULL, NULL),
	(11, '女性同伴で\r\n男性受付可', 255, NULL, NULL, NULL),
	(12, '割引クーポンあり', 255, NULL, NULL, NULL);
/*!40000 ALTER TABLE `lesson_conditions` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
