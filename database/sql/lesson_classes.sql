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

-- Dumping data for table db_senpai.lesson_classes: ~8 rows (approximately)
/*!40000 ALTER TABLE `lesson_classes` DISABLE KEYS */;
INSERT INTO `lesson_classes` (`class_id`, `class_name`, `class_image`, `class_icon`, `class_sort`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 'ランニング・ウォーキング', 'img_01.jpg', '01.png', 1, '2022-04-06 15:10:23', '2022-04-06 15:10:41', NULL),
	(2, 'サイクリング', 'img_02.jpg', '02.png', 2, '2022-04-06 15:10:22', '2022-04-06 15:10:40', NULL),
	(3, '水泳', 'img_03.jpg', '03.png', 3, '2022-04-06 15:10:24', '2022-04-06 15:10:39', NULL),
	(4, '筋トレ', 'img_04.jpg', '04.png', 4, '2022-04-06 15:10:25', '2022-04-06 15:10:38', NULL),
	(5, 'ゴルフ', 'img_05.jpg', '05.png', 5, '2022-04-06 15:10:26', '2022-04-06 15:10:37', NULL),
	(6, 'カラオケ・ボイトレ', 'img_06.jpg', '06.png', 6, '2022-04-06 15:10:27', '2022-04-06 15:10:36', NULL),
	(7, 'パソコンスキル', 'img_07.jpg', '07.png', 7, '2022-04-06 15:10:28', '2022-04-06 15:10:36', NULL),
	(8, 'ペット散歩', 'img_08.jpg', '08.png', 8, '2022-04-06 15:10:29', '2022-04-06 15:10:35', NULL),
	(9, 'その他・アウトドア', 'img_09.jpg', '09.png', 9, '2022-04-06 15:10:31', '2022-04-06 15:10:34', NULL),
	(10, 'その他・インドア', 'img_10.jpg', '10.png', 10, '2022-04-06 15:10:32', '2022-04-06 15:10:33', NULL);
/*!40000 ALTER TABLE `lesson_classes` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
