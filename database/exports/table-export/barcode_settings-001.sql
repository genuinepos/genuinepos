-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 03, 2021 at 02:54 PM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 8.0.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `new_inventory`
--

-- --------------------------------------------------------

--
-- Table structure for table `barcode_settings`
--

CREATE TABLE `barcode_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_continuous` tinyint(1) NOT NULL DEFAULT 0,
  `top_margin` double(22,4) NOT NULL DEFAULT 0.0000,
  `left_margin` double(22,4) NOT NULL DEFAULT 0.0000,
  `sticker_width` double(22,4) NOT NULL DEFAULT 0.0000,
  `sticker_height` double(22,4) NOT NULL DEFAULT 0.0000,
  `paper_width` double(22,4) NOT NULL DEFAULT 0.0000,
  `paper_height` double(22,4) NOT NULL DEFAULT 0.0000,
  `row_distance` double(22,4) NOT NULL DEFAULT 0.0000,
  `column_distance` double(22,4) NOT NULL DEFAULT 0.0000,
  `stickers_in_a_row` bigint(20) NOT NULL DEFAULT 0,
  `stickers_in_one_sheet` bigint(20) NOT NULL DEFAULT 0,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `is_fixed` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `barcode_settings`
--

INSERT INTO `barcode_settings` (`id`, `name`, `description`, `is_continuous`, `top_margin`, `left_margin`, `sticker_width`, `sticker_height`, `paper_width`, `paper_height`, `row_distance`, `column_distance`, `stickers_in_a_row`, `stickers_in_one_sheet`, `is_default`, `is_fixed`, `created_at`, `updated_at`) VALUES
(1, '20 Barcodes Per Sheet, Page Size :8.5\'\' * 11\'\', Barcode Size :4\'\' * 1, Barcode 20 Per Sheet', '20 Barcodes Per Sheet, Page Size :8.5\'\' * 11\'\', Barcode Size :4\'\' * 1, Barcode 20 Per Sheet', 0, 0.1200, 0.1200, 4.0000, 1.0000, 8.5000, 11.0000, 1.0000, 1.0000, 10, 20, 0, 1, NULL, '2021-07-01 11:09:33'),
(2, 'Sticker Print, Continuous feed or rolls , Barcode Size: 3 Inc * 2 Inc', NULL, 1, 0.1000, 0.0000, 1.2000, 0.2000, 1.8000, 1.3800, 0.0000, 0.0000, 1, 1, 1, 1, NULL, '2021-07-01 09:40:09'),
(3, '40 Barcodes Per Sheet, Page Size :8.5\'\' * 11\'\', Barcode Size :2\'\' * 0.8\'\', Barcode 40 Per Sheet', NULL, 0, 0.3000, 0.1000, 2.0000, 0.8000, 8.5000, 11.0000, 0.0000, 0.0000, 10, 30, 0, 1, NULL, '2021-07-01 11:55:53'),
(4, '30 Barcodes Per Sheet, Page Size :8.5\'\' * 11\'\', Barcode Size :2.2\'\' * 1\'\', Barcode 30 Per Sheet', NULL, 0, 0.1000, 0.1000, 2.2000, 1.0000, 8.5000, 11.0000, 0.0000, 0.0000, 30, 30, 0, 1, NULL, '2021-07-01 12:05:57');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `barcode_settings`
--
ALTER TABLE `barcode_settings`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `barcode_settings`
--
ALTER TABLE `barcode_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
