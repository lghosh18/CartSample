-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 03, 2020 at 07:06 AM
-- Server version: 8.0.17
-- PHP Version: 7.1.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `oms`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_items`
--

CREATE TABLE `tbl_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `selling_price` double(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tbl_items`
--

INSERT INTO `tbl_items` (`id`, `title`, `description`, `selling_price`, `created_at`, `updated_at`) VALUES
(1, 'Aarogyam C\r\n', 'Basic Screening Preventive Health Check-up Profile', 1000.00, '2018-08-02 13:30:00', '2018-08-02 13:30:00'),
(2, 'Aarogyam 1.3\r\n', 'A diagnostic profile of 90 tests for a comprehensive health evaluation.', 1400.00, '2018-08-02 13:30:00', '2018-08-02 13:30:00'),
(3, 'Basic Allergy Package\r\n', 'Find out what you are allergic to.', 480.00, '2018-08-02 13:30:00', '2018-08-02 13:30:00'),
(4, 'Diabetic Checkup', 'Get regular reports to prevent/check diabetes levels', 899.00, '2018-08-02 13:30:00', '2018-08-02 13:30:00'),
(5, 'ECHO', 'Echocardiography or ECHO', 1600.00, '2018-08-02 13:30:00', '2018-08-02 13:30:00'),
(6, 'ECG', 'Sometimes it can diagnose a heart problem', 250.00, '2018-08-02 13:30:00', '2018-08-02 13:30:00'),
(7, 'PET Scan\r\n', 'May detect the early onset of disease', 9600.00, '2018-08-02 13:30:00', '2018-08-02 13:30:00'),
(8, 'Ultrasound Whole Abdomen\r\n', 'Used to study the development of an unborn baby, abdominal and pelvic organs', 999.00, '2018-08-02 13:30:00', '2018-08-02 13:30:00'),
(9, 'MRI Scan Knee Joint\r\n', 'Detailed images of structures within the knee joint', 2600.00, '2018-08-02 13:30:00', '2018-08-02 13:30:00'),
(10, 'X ray chest PA View\r\n', 'Used to aid diagnosis of acute and chronic conditions in the lungs', 345.00, '2018-08-02 13:30:00', '2018-08-02 13:30:00'),
(11, 'Advance Heart Care\r\n', 'Provides detailed evaluation of the Cardio Vascular System', 875.00, '2018-08-02 13:30:00', '2018-08-02 13:30:00'),
(12, 'Fever Plus Profile\r\n', 'Group of tests performed to detect the reasons for fever', 950.00, '2018-08-02 13:30:00', '2018-08-02 13:30:00'),
(13, 'Liver Function Test', 'Provides detailed evaluation of liver', 240.00, '2018-08-02 13:30:00', '2018-08-02 13:30:00'),
(14, 'CT scan chest contrast\r\n', 'Diagnose, stage, and plan treatment for lung cancer', 4300.00, '2018-08-02 13:30:00', '2018-08-02 13:30:00'),
(15, 'Ultrasound Whole Abdomen\r\n', 'Study the development of an unborn baby, abdominal and pelvic organs', 999.00, '2018-08-02 13:30:00', '2018-08-02 13:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_orders`
--

CREATE TABLE `tbl_orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_amount` double(9,2) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_orders`
--

INSERT INTO `tbl_orders` (`id`, `user_id`, `total_amount`, `status`) VALUES
(1, 1, 2880.00, 1),
(2, 2, 4448.00, 1),
(3, 3, 1000.00, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_order_items`
--

CREATE TABLE `tbl_order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `qty` smallint(6) NOT NULL,
  `price` double(9,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_order_items`
--

INSERT INTO `tbl_order_items` (`id`, `order_id`, `item_id`, `qty`, `price`) VALUES
(1, 1, 2, 1, 1400.00),
(2, 1, 3, 1, 480.00),
(3, 1, 1, 1, 1000.00),
(4, 2, 5, 1, 1600.00),
(5, 2, 4, 1, 899.00),
(6, 2, 8, 1, 999.00),
(7, 2, 12, 1, 950.00),
(8, 3, 1, 1, 1000.00);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_session`
--

CREATE TABLE `tbl_session` (
  `id` varchar(40) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) NOT NULL,
  `data` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_session`
--

INSERT INTO `tbl_session` (`id`, `ip_address`, `timestamp`, `data`) VALUES
('164bdf1a7659d32219da4336d31e77588962a736', '::1', 1596438306, 0x5f5f63695f6c6173745f726567656e65726174657c693a313539363433383237343b757365725f69647c733a313a2233223b757365726e616d657c733a353a2261646d696e223b);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

CREATE TABLE `tbl_users` (
  `id` int(11) NOT NULL,
  `username` varchar(15) NOT NULL,
  `email` varchar(128) DEFAULT NULL,
  `first_name` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `second_name` varchar(40) DEFAULT NULL,
  `mobile_no` varchar(15) DEFAULT NULL,
  `password` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`id`, `username`, `email`, `first_name`, `second_name`, `mobile_no`, `password`) VALUES
(1, 'root', NULL, NULL, NULL, NULL, '$2y$10$jeyuefmXEoE1.AVSDCRmFu3pPA5T4s92ptmYgNfHk1W3kWVpL3bKu'),
(2, 'test', NULL, NULL, NULL, NULL, '$2y$10$W1UAmrRzYuJuGsHVB.xPpetVD72X3UHACCpDyKfaya2Vo2QU6.G2.'),
(3, 'admin', NULL, NULL, NULL, NULL, '$2y$10$lwvkW9cqBc87Sa1sbK2U.OY0Vkq6De98HpkTiB9XizC9JlCcO/D62');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_items`
--
ALTER TABLE `tbl_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_orders`
--
ALTER TABLE `tbl_orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_order_items`
--
ALTER TABLE `tbl_order_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_items`
--
ALTER TABLE `tbl_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `tbl_orders`
--
ALTER TABLE `tbl_orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_order_items`
--
ALTER TABLE `tbl_order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
