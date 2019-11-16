-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 16, 2019 at 06:58 AM
-- Server version: 8.0.17
-- PHP Version: 7.3.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mbs_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `balance_info`
--

CREATE TABLE `balance_info` (
  `id` int(11) NOT NULL,
  `customer_account_id` int(11) NOT NULL,
  `amount` int(11) NOT NULL DEFAULT '0',
  `type` tinyint(1) NOT NULL COMMENT '1: Deposite, 2: Withdrow, 3: Transfer',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0: Pending , 1: Completed'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `balance_info`
--

INSERT INTO `balance_info` (`id`, `customer_account_id`, `amount`, `type`, `status`) VALUES
(10, 2, 100, 1, 1),
(11, 2, 20, 1, 1),
(12, 2, 80, 1, 1),
(13, 2, 30, 1, 1),
(14, 2, 50, 1, 1),
(15, 2, 50, 2, 1),
(16, 2, 20, 2, 1),
(17, 9, 1222, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `customer_account_info`
--

CREATE TABLE `customer_account_info` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `account_no` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `balance` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `customer_account_info`
--

INSERT INTO `customer_account_info` (`id`, `customer_id`, `account_no`, `balance`) VALUES
(1, 1, '125.103.1110001', 2),
(2, 4, '125.101.1110002', 218),
(9, 10, '110.112.345432', 1622),
(10, 10, '110.112.345411', 11201),
(11, 10, '125.101.345433', 0);

-- --------------------------------------------------------

--
-- Table structure for table `customer_info`
--

CREATE TABLE `customer_info` (
  `id` int(11) NOT NULL,
  `customer_name` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `personal_code` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `customer_info`
--

INSERT INTO `customer_info` (`id`, `customer_name`, `email`, `personal_code`, `password`) VALUES
(1, 'Admin User', 'admin@gmail.com', '10101010', '123456'),
(4, 'momit', 'momit.litu@gmail.com', '0413413', 'e10adc3949ba59abbe56e057f20f883e'),
(5, 'amotyher', 'contact@sma.tecdiary.org', '1232', 'f0feefcdc1b86fd94cf7ec3134698e53'),
(9, 'amotyher', 'dfg@sdf.vom', '12325', '4fe1404a724a763fdc4af3ac6f7bf79a'),
(10, 'chaki', 'kajolchaki@gmail.com', '111111', 'e10adc3949ba59abbe56e057f20f883e');

-- --------------------------------------------------------

--
-- Table structure for table `nominee`
--

CREATE TABLE `nominee` (
  `id` int(11) NOT NULL,
  `n_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `nid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `customer_id` int(11) NOT NULL,
  `relation` varchar(255) NOT NULL,
  `priority` int(11) NOT NULL,
  `account_number` varchar(55) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `nominee`
--

INSERT INTO `nominee` (`id`, `n_name`, `nid`, `customer_id`, `relation`, `priority`, `account_number`) VALUES
(1, 'Sazol', '1243567890', 1, 'Brother', 1, '125.103.1110001'),
(2, 'Rokeya', '124356745890', 1, 'mother', 2, '125.103.1110001'),
(5, 'Subodh chaki', '23456788734', 10, 'Father', 3, '110.112.345432'),
(10, 'Badol', '376494516237865', 10, 'Father', 3, '125.101.345433'),
(11, 'Tipu', '12345', 10, 'Friend', 3, '110.112.345411'),
(15, 'Himel', '212345678', 10, 'friend', 3, '125.101.345433');

-- --------------------------------------------------------

--
-- Table structure for table `transection_balance`
--

CREATE TABLE `transection_balance` (
  `id` int(11) NOT NULL,
  `from_account_id` int(11) NOT NULL,
  `to_account_id` int(11) NOT NULL,
  `amount` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `transection_balance`
--

INSERT INTO `transection_balance` (`id`, `from_account_id`, `to_account_id`, `amount`) VALUES
(3, 2, 1, 2),
(4, 9, 10, 100);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `balance_info`
--
ALTER TABLE `balance_info`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_balance_info_customer_account_info` (`customer_account_id`);

--
-- Indexes for table `customer_account_info`
--
ALTER TABLE `customer_account_info`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `account_no` (`account_no`),
  ADD KEY `FK_customer_account_info_customer_info` (`customer_id`);

--
-- Indexes for table `customer_info`
--
ALTER TABLE `customer_info`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `personal_code` (`personal_code`);

--
-- Indexes for table `nominee`
--
ALTER TABLE `nominee`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transection_balance`
--
ALTER TABLE `transection_balance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_transection_history_customer_account_info` (`from_account_id`),
  ADD KEY `FK_transection_history_customer_account_info_2` (`to_account_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `balance_info`
--
ALTER TABLE `balance_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `customer_account_info`
--
ALTER TABLE `customer_account_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `customer_info`
--
ALTER TABLE `customer_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `nominee`
--
ALTER TABLE `nominee`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `transection_balance`
--
ALTER TABLE `transection_balance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `balance_info`
--
ALTER TABLE `balance_info`
  ADD CONSTRAINT `FK_balance_info_customer_account_info` FOREIGN KEY (`customer_account_id`) REFERENCES `customer_account_info` (`id`);

--
-- Constraints for table `customer_account_info`
--
ALTER TABLE `customer_account_info`
  ADD CONSTRAINT `FK_customer_account_info_customer_info` FOREIGN KEY (`customer_id`) REFERENCES `customer_info` (`id`);

--
-- Constraints for table `transection_balance`
--
ALTER TABLE `transection_balance`
  ADD CONSTRAINT `FK_transection_history_customer_account_info` FOREIGN KEY (`from_account_id`) REFERENCES `customer_account_info` (`id`),
  ADD CONSTRAINT `FK_transection_history_customer_account_info_2` FOREIGN KEY (`to_account_id`) REFERENCES `customer_account_info` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
