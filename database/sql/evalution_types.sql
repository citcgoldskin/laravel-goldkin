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

-- Dumping data for table db_senpai.evalution_types: ~0 rows (approximately)
/*!40000 ALTER TABLE `evalution_types` DISABLE KEYS */;
INSERT INTO `evalution_types` (`et_id`, `et_kind`, `et_question`, `et_sort`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 0, '遅刻などなく、開始・終了時刻がしっかり守られていましたか？', 1, '2022-03-07 19:27:01', '2022-03-07 19:27:07', NULL),
	(2, 0, 'レッスンに関係のないプライベートなことまでしつこく質問されたりはしませんでしたか？', 2, '2022-03-07 19:27:03', '2022-03-07 19:27:09', NULL),
	(3, 0, 'トークルームやレッスン当日の言葉遣いや態度は丁寧でしたか？', 3, '2022-03-07 19:27:05', '2022-03-07 19:27:10', NULL),
	(4, 0, '出品内容と異なるサービスをお願いされたりすることはありませんでしたか？', 4, '2022-03-07 19:27:28', '2022-03-07 19:27:30', NULL),
	(5, 0, '機会があればまたこのセンパイとレッスンを行ってみたいと感じましたか？', 5, '2022-03-07 19:29:20', '2022-03-07 19:29:22', NULL),
	(6, 1, '当日のレッスン内容は出品内容と同じでしたか?', 1, '2022-03-07 19:31:03', '2022-03-07 19:31:05', NULL),
	(7, 1, '遅刻などなく、正しくレッスン時間が守られてましたか?', 2, '2022-03-07 19:31:23', '2022-03-07 19:31:25', NULL),
	(8, 1, 'レッスン当日までのトークルームでの対応は丁寧で分かりやすかったですか？', 3, '2022-03-07 19:31:51', '2022-03-07 19:31:54', NULL),
	(9, 1, 'レッスンを実際に受けてみて、この価格設定に納得できましたか？', 4, '2022-03-07 19:32:13', '2022-03-07 19:32:14', NULL),
	(10, 1, 'レッスン中の言葉遣いや態度などは適切でしたか？', 5, '2022-03-07 19:32:32', '2022-03-07 19:32:34', NULL);
/*!40000 ALTER TABLE `evalution_types` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
