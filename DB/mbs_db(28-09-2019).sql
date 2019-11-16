-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.1.32-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win32
-- HeidiSQL Version:             10.2.0.5599
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping structure for table mbs_db.balance_info
CREATE TABLE IF NOT EXISTS `balance_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_account_id` int(11) NOT NULL,
  `amount` int(11) NOT NULL DEFAULT '0',
  `type` tinyint(1) NOT NULL COMMENT '1: Deposite, 2: Withdrow, 3: Transfer',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0: Pending , 1: Completed',
  PRIMARY KEY (`id`),
  KEY `FK_balance_info_customer_account_info` (`customer_account_id`),
  CONSTRAINT `FK_balance_info_customer_account_info` FOREIGN KEY (`customer_account_id`) REFERENCES `customer_account_info` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table mbs_db.balance_info: ~7 rows (approximately)
/*!40000 ALTER TABLE `balance_info` DISABLE KEYS */;
INSERT INTO `balance_info` (`id`, `customer_account_id`, `amount`, `type`, `status`) VALUES
	(10, 2, 100, 1, 1),
	(11, 2, 20, 1, 1),
	(12, 2, 80, 1, 1),
	(13, 2, 30, 1, 1),
	(14, 2, 50, 1, 1),
	(15, 2, 50, 2, 1),
	(16, 2, 20, 2, 1);
/*!40000 ALTER TABLE `balance_info` ENABLE KEYS */;

-- Dumping structure for table mbs_db.customer_account_info
CREATE TABLE IF NOT EXISTS `customer_account_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL,
  `account_no` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `balance` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `account_no` (`account_no`),
  KEY `FK_customer_account_info_customer_info` (`customer_id`),
  CONSTRAINT `FK_customer_account_info_customer_info` FOREIGN KEY (`customer_id`) REFERENCES `customer_info` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table mbs_db.customer_account_info: ~2 rows (approximately)
/*!40000 ALTER TABLE `customer_account_info` DISABLE KEYS */;
INSERT INTO `customer_account_info` (`id`, `customer_id`, `account_no`, `balance`) VALUES
	(1, 1, '125.103.1110001', 2),
	(2, 4, '125.101.1110002', 218);
/*!40000 ALTER TABLE `customer_account_info` ENABLE KEYS */;

-- Dumping structure for table mbs_db.customer_info
CREATE TABLE IF NOT EXISTS `customer_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `personal_code` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `personal_code` (`personal_code`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table mbs_db.customer_info: ~2 rows (approximately)
/*!40000 ALTER TABLE `customer_info` DISABLE KEYS */;
INSERT INTO `customer_info` (`id`, `customer_name`, `email`, `personal_code`, `password`) VALUES
	(1, 'Admin User', 'admin@gmail.com', '10101010', '123456'),
	(4, 'momit', 'momit.litu@gmail.com', '0413413', 'e10adc3949ba59abbe56e057f20f883e'),
	(5, 'amotyher', 'contact@sma.tecdiary.org', '1232', 'f0feefcdc1b86fd94cf7ec3134698e53'),
	(9, 'amotyher', 'dfg@sdf.vom', '12325', '4fe1404a724a763fdc4af3ac6f7bf79a');
/*!40000 ALTER TABLE `customer_info` ENABLE KEYS */;

-- Dumping structure for table mbs_db.transection_balance
CREATE TABLE IF NOT EXISTS `transection_balance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from_account_id` int(11) NOT NULL,
  `to_account_id` int(11) NOT NULL,
  `amount` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `FK_transection_history_customer_account_info` (`from_account_id`),
  KEY `FK_transection_history_customer_account_info_2` (`to_account_id`),
  CONSTRAINT `FK_transection_history_customer_account_info` FOREIGN KEY (`from_account_id`) REFERENCES `customer_account_info` (`id`),
  CONSTRAINT `FK_transection_history_customer_account_info_2` FOREIGN KEY (`to_account_id`) REFERENCES `customer_account_info` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table mbs_db.transection_balance: ~0 rows (approximately)
/*!40000 ALTER TABLE `transection_balance` DISABLE KEYS */;
INSERT INTO `transection_balance` (`id`, `from_account_id`, `to_account_id`, `amount`) VALUES
	(3, 2, 1, 2);
/*!40000 ALTER TABLE `transection_balance` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
