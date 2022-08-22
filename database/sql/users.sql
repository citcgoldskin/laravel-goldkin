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

-- Dumping data for table db_senpai.users: ~6 rows (approximately)
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `name`, `email`, `user_no`, `email_verified_at`, `password`, `remember_token`, `user_firstname`, `user_lastname`, `user_sei`, `user_mei`, `user_sex`, `user_birthday`, `user_area_id`, `user_avatar`, `user_phone`, `user_intro`, `user_state`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 'センパイテスト', 'senpai_test@senpai.inc', 'CST220412625543d3446ab', NULL, '$2y$10$Kw2Seig/i2DR1dqBzszFsOTzbOqDMZRZOPiEjrwqFzTvopmxzeMcu', NULL, '先輩', 'てすと', 'センパイ', 'テスト', 0, '1993-02-28', 1, '1.png', 123456789, NULL, 1, '2022-03-01 03:19:36', '2022-03-01 03:19:36', NULL),
	(2, 'コウハイテスト', 'kouhai_test@senpai.inc', 'CST220411625543d3446ab', NULL, '$2y$10$Kw2Seig/i2DR1dqBzszFsOTzbOqDMZRZOPiEjrwqFzTvopmxzeMcu', NULL, '後輩', 'てすと', 'コウハイ', 'テスト', 1, '1993-02-28', 2, '2.png', 234567891, NULL, 1, '2022-03-01 03:19:36', '2022-03-01 03:19:36', NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
