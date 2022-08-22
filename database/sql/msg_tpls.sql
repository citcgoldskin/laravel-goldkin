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

-- Dumping structure for table db_senpai.msg_tpls
CREATE TABLE IF NOT EXISTS `msg_tpls` (
  `mt_code` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `mt_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `mt_msg_content` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `mt_sms_content` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `mt_mail_subject` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `mt_mail_content` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table db_senpai.msg_tpls: ~6 rows (approximately)
/*!40000 ALTER TABLE `msg_tpls` DISABLE KEYS */;
INSERT INTO `msg_tpls` (`mt_code`, `mt_name`, `mt_msg_content`, `mt_sms_content`, `mt_mail_subject`, `mt_mail_content`, `created_at`, `updated_at`, `deleted_at`) VALUES
	('reserve_confirm', 'コウハイ', '{$from_user_name}さんがあなたの予約を承認しました。内容を確認して期限内に購入してください。', '', '', '', NULL, NULL, NULL),
	('reserve_arrive', 'センパイ', '{$from_user_name}さんから予約リクエストが届きました。内容を確認して期限内に承認または辞退をしてください。', '', '', '', NULL, NULL, NULL),
	('reserve_cancel', 'コウハイ', '{$from_user_name}さんがあなたの予約を辞退しました。<br>内容を確認し、日時を変更するか他のセンパイにお願いしてください。', '', '', '', NULL, NULL, NULL),
	('attend_confirm', 'コウハイ', '{$from_user_name}さんがあなたの出勤リクエストを承認しました。内容を確認して期限内に購入してください。', '', '', '', NULL, NULL, NULL),
	('attend_arrive', 'センパイ', '{$from_user_name}さんから出勤リクエストが届きました。内容を確認し、承認又は辞退をしてください。', '', '', '', NULL, NULL, NULL),
	('attend_cancel', 'コウハイ', '{$from_user_name}さんがあなたの出勤リクエストを辞退しました。内容を確認し、日時を変更するか他のセンパイにお願いしてください。', '', '', '', NULL, NULL, NULL),
	('recruit_change', 'センパイ', 'あなたが提案した{$from_user_name}さんの募集が変更されました。内容を確認しもう一度提案してください。', '', '', '', NULL, NULL, NULL),
	('recruit_arrive', 'コウハイ', 'あなたの募集に対して{$from_user_name}さんから提案が届きました。内容を確認してください。', '', '', '', NULL, NULL, NULL),
	('lesson_buy', 'センパイ', '{$from_user_name}さんがあなたのレッスンを購入しました。トークルームを確認してください。', '', '', '', NULL, NULL, NULL),
	('proposal_buy', 'センパイ', '{$from_user_name}さんがあなたの提案を購入しました。<br>トークルームを確認してください。', '', '', '', NULL, NULL, NULL),
	('request_cancel', 'センパイ', '{$from_user_name}さんがリクエストを辞退しました。', '', '', '', NULL, NULL, NULL),
	('request_change', 'センパイ', '{$from_user_name}さんがリクエストを変更しました。内容を確認し、承認または辞退をしてください。', '', '', '', NULL, NULL, NULL);
/*!40000 ALTER TABLE `msg_tpls` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
