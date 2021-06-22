-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 22, 2021 at 02:19 PM
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
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bank_id` bigint(20) UNSIGNED DEFAULT NULL,
  `account_type_id` bigint(20) UNSIGNED DEFAULT NULL,
  `opening_balance` decimal(22,2) NOT NULL DEFAULT 0.00,
  `debit` decimal(22,2) NOT NULL DEFAULT 0.00,
  `credit` decimal(22,2) NOT NULL DEFAULT 0.00,
  `balance` decimal(22,2) NOT NULL DEFAULT 0.00,
  `remark` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `admin_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `name`, `account_number`, `bank_id`, `account_type_id`, `opening_balance`, `debit`, `credit`, `balance`, `remark`, `status`, `admin_id`, `created_at`, `updated_at`) VALUES
(15, 'Payment Account', '84564574', 2, 2, '0.00', '23506.00', '60889.50', '37383.50', NULL, 1, 1, NULL, '2021-06-17 10:44:49'),
(16, 'Gregory Rowland', '501578787444', 6, 2, '67.00', '935475.50', '25712.01', '-720338.49', 'Tempore earum elige', 1, 1, NULL, '2021-06-17 09:15:14');

-- --------------------------------------------------------

--
-- Table structure for table `account_types`
--

CREATE TABLE `account_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remark` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `account_types`
--

INSERT INTO `account_types` (`id`, `name`, `remark`, `status`, `created_at`, `updated_at`) VALUES
(2, 'Payment', 'This is payment type account', 1, NULL, '2021-05-05 06:04:04'),
(3, 'Dabit', 'This is dabit type.', 1, NULL, '2021-05-02 13:05:58');

-- --------------------------------------------------------

--
-- Table structure for table `admin_and_users`
--

CREATE TABLE `admin_and_users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `prefix` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `emp_id` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `shift_id` bigint(20) UNSIGNED DEFAULT NULL,
  `role_type` int(11) DEFAULT NULL COMMENT '1=super_admin,2=admin,3=others',
  `role_id` bigint(20) UNSIGNED DEFAULT NULL,
  `role_permission_id` bigint(20) UNSIGNED DEFAULT NULL,
  `allow_login` tinyint(1) NOT NULL DEFAULT 0,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sales_commission_percent` decimal(8,2) NOT NULL DEFAULT 0.00,
  `max_sales_discount_percent` decimal(8,2) NOT NULL DEFAULT 0.00,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_of_birth` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `marital_status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `blood_group` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'default.png',
  `facebook_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `twitter_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `instagram_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `social_media_1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `social_media_2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_field_1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_field_2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `guardian_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_proof_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_proof_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `permanent_address` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `current_address` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_ac_holder_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_ac_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_identifier_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_branch` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tax_payer_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `language` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `department_id` bigint(20) UNSIGNED DEFAULT NULL,
  `designation_id` bigint(20) UNSIGNED DEFAULT NULL,
  `salary` decimal(22,2) NOT NULL DEFAULT 0.00,
  `salary_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_and_users`
--

INSERT INTO `admin_and_users` (`id`, `prefix`, `name`, `last_name`, `emp_id`, `username`, `email`, `shift_id`, `role_type`, `role_id`, `role_permission_id`, `allow_login`, `branch_id`, `status`, `password`, `sales_commission_percent`, `max_sales_discount_percent`, `phone`, `date_of_birth`, `gender`, `marital_status`, `blood_group`, `photo`, `facebook_link`, `twitter_link`, `instagram_link`, `social_media_1`, `social_media_2`, `custom_field_1`, `custom_field_2`, `guardian_name`, `id_proof_name`, `id_proof_number`, `permanent_address`, `current_address`, `bank_ac_holder_name`, `bank_ac_no`, `bank_name`, `bank_identifier_code`, `bank_branch`, `tax_payer_id`, `language`, `department_id`, `designation_id`, `salary`, `salary_type`, `created_at`, `updated_at`) VALUES
(2, 'Mr', 'Super', 'Admin', NULL, 'superadmin', 'superadmin@gamil.com', NULL, 1, NULL, 8, 1, NULL, 1, '$2y$10$rd3uLXbr7OXtcZAh5VAj1u.nHtBpy0.gZx5HYXJ1uSR/TpT/nVBai', '0.00', '0.00', NULL, NULL, NULL, NULL, NULL, 'default.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'en', NULL, NULL, '0.00', NULL, '2021-04-07 07:04:03', '2021-05-25 08:29:27'),
(3, 'Mr', 'branch', 'manager', NULL, 'BranchC', 'branchb@gmail.com', NULL, 3, 10, 7, 1, 26, 1, '$2y$10$pdWTj/XaAYZH/a/qbUdYReLZLrFJHJzDVsNR5YoWtp0NATIspEIXa', '0.00', '0.00', NULL, NULL, NULL, NULL, NULL, 'default.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, '0.00', NULL, '2021-04-07 11:26:16', '2021-04-11 06:39:41'),
(4, 'Mr', 'single', 'shop', NULL, 'singleshop', 'ss@gmail.com', NULL, 2, NULL, 8, 1, NULL, 1, '$2y$10$XRuRIDrLRD64WE67ShGyiuK7Yq7/.Wn1c4uKUqRKJwDy22aQQ4yai', '0.00', '0.00', NULL, '18-04-2021', NULL, NULL, NULL, 'default.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'FF', 'FF', 'FF', 'FF', 'FF', 'FF', NULL, 2, 1, '0.00', NULL, '2021-04-18 11:16:36', '2021-04-18 11:16:36'),
(5, 'Mr', 'Employee', 'Man', NULL, 'employee', 'me@gmail.com', NULL, 3, 10, 7, 1, 25, 1, '$2y$10$fanNVJ/9DiCisq4wbwagTuVRDqRPu1J5ktGoM4BY4KKKfDAP1hcs.', '0.00', '0.00', NULL, '18-04-2021', NULL, NULL, NULL, 'default.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, '0.00', NULL, '2021-04-18 11:21:23', '2021-04-19 11:04:40'),
(6, 'Mr', 'Employee', 'Em', NULL, NULL, 'em@gmail.com', NULL, NULL, NULL, NULL, 0, NULL, 0, '$2y$10$vTRxd0/Q7SiexdFud9jB6.7EiJVciEVlf1GdkLFerfwjKsRr3xbjS', '20.00', '25.00', '0158997744', NULL, NULL, 'Married', NULL, 'default.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'FF', 'FF', 'FF', 'FF', 'FF', 'FF', NULL, 2, 1, '15000.00', 'Monthly', '2021-04-19 10:25:19', '2021-05-02 04:50:18'),
(7, 'MD.', 'Faisal', NULL, '107', 'faisal', 'faisal@gmail.com', 2, 3, 10, 7, 1, 24, 0, '$2y$10$6LP5AtoUXHbQ8E14cBUmX.Ruzv65H.0TL8nT.7nRaf1yrOFU0NHc.', '0.00', '0.00', NULL, NULL, 'Male', 'Unmarried', NULL, 'default.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'en', 2, 1, '0.00', 'Monthly', '2021-04-28 08:27:33', '2021-06-13 12:13:09'),
(9, 'Mr', 'Employee', '2', NULL, NULL, 'me2@gmail.com', NULL, NULL, NULL, NULL, 0, 24, 0, NULL, '20.00', '5.00', NULL, NULL, NULL, 'Unmarried', NULL, 'default.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'MR', '935787454', 'JANUNA', '6878787', 'Motijheel', '5687874554', NULL, 2, 1, '15000.00', 'Yearly', '2021-05-02 03:46:07', '2021-05-02 04:46:11'),
(10, 'Mr', 'Hamish', 'Khan', 'EP-125578', 'hamish', 'h@gmail.com', 2, 3, 16, 12, 1, 24, 0, '$2y$10$.PvlXGZwh4CyGBp6iqGIKuAa7Gu2Y7wFt2t.zfgkMahfUc1ZVh2Fi', '0.00', '0.00', NULL, NULL, NULL, NULL, NULL, 'default.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, 1, '15000.00', 'Monthly', '2021-06-09 10:31:59', '2021-06-09 10:31:59');

-- --------------------------------------------------------

--
-- Table structure for table `allowance_employees`
--

CREATE TABLE `allowance_employees` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `allowance_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_delete_in_update` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `assets`
--

CREATE TABLE `assets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `asset_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type_id` bigint(20) UNSIGNED DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `quantity` decimal(22,2) NOT NULL DEFAULT 0.00,
  `per_unit_value` decimal(22,2) NOT NULL DEFAULT 0.00,
  `total_value` decimal(22,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `assets`
--

INSERT INTO `assets` (`id`, `asset_name`, `type_id`, `branch_id`, `quantity`, `per_unit_value`, `total_value`, `created_at`, `updated_at`) VALUES
(15, 'dd', 1, 24, '10.00', '10.00', '100.00', NULL, NULL),
(16, 'AC Gree', 6, 24, '2.00', '25000.00', '50000.00', NULL, NULL),
(17, 'Dell Brand Pc', 5, NULL, '6.00', '20000.00', '120000.00', NULL, NULL),
(18, 'Chair', 1, 25, '20.00', '300.00', '6000.00', NULL, NULL),
(19, 'Mouse', 4, 26, '8.00', '350.00', '2800.00', NULL, NULL),
(20, 'Monitor', 7, 24, '12.00', '8000.00', '96000.00', NULL, NULL),
(21, 'msi Monitor', 7, NULL, '2.00', '8500.00', '17000.00', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `asset_types`
--

CREATE TABLE `asset_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `asset_type_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `asset_type_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `asset_types`
--

INSERT INTO `asset_types` (`id`, `asset_type_name`, `asset_type_code`, `created_at`, `updated_at`) VALUES
(1, 'Furniture', 'F12', NULL, NULL),
(4, 'Accessories', 'ACS-5', NULL, NULL),
(5, 'Computer', 'CMP-12', NULL, NULL),
(6, 'AC', 'AC-85', NULL, NULL),
(7, 'Electronics', 'ELC-105', NULL, '2021-06-16 05:23:37');

-- --------------------------------------------------------

--
-- Table structure for table `banks`
--

CREATE TABLE `banks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `branch_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `banks`
--

INSERT INTO `banks` (`id`, `name`, `branch_name`, `address`, `created_at`, `updated_at`) VALUES
(2, 'BRAC BANK', 'Mirpur-1', 'Dhaka, Bangladesh.', NULL, '2020-12-15 04:59:22'),
(6, 'RUPALI BANK', 'Mirpur - 1', 'Dhaka, Bangladesh.', NULL, '2020-12-15 04:59:08'),
(7, 'JAMUNA BANK', 'Matijheel', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `barcode_settings`
--

CREATE TABLE `barcode_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_continuous` tinyint(1) NOT NULL DEFAULT 0,
  `top_margin` bigint(20) NOT NULL DEFAULT 0,
  `left_margin` bigint(20) NOT NULL DEFAULT 0,
  `sticker_width` bigint(20) NOT NULL DEFAULT 0,
  `sticker_height` bigint(20) NOT NULL DEFAULT 0,
  `paper_width` bigint(20) NOT NULL DEFAULT 0,
  `paper_height` bigint(20) NOT NULL DEFAULT 0,
  `row_distance` bigint(20) NOT NULL DEFAULT 0,
  `column_distance` bigint(20) NOT NULL DEFAULT 0,
  `stickers_in_a_row` bigint(20) NOT NULL DEFAULT 0,
  `stickers_in_one_sheet` bigint(20) NOT NULL DEFAULT 0,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `barcode_settings`
--

INSERT INTO `barcode_settings` (`id`, `name`, `description`, `is_continuous`, `top_margin`, `left_margin`, `sticker_width`, `sticker_height`, `paper_width`, `paper_height`, `row_distance`, `column_distance`, `stickers_in_a_row`, `stickers_in_one_sheet`, `is_default`, `created_at`, `updated_at`) VALUES
(1, 'Pearl Glover', 'Magnam fugiat aute', 0, 57, 98, 58, 68, 68, 98, 43, 61, 84, 99, 1, NULL, '2021-06-22 06:32:33');

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

CREATE TABLE `branches` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `branch_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zip_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alternate_phone_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT 'default.png',
  `invoice_schema_id` bigint(20) UNSIGNED DEFAULT NULL,
  `add_sale_invoice_layout_id` bigint(20) UNSIGNED DEFAULT NULL,
  `pos_sale_invoice_layout_id` bigint(20) UNSIGNED DEFAULT NULL,
  `default_account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `purchase_permission` tinyint(1) NOT NULL DEFAULT 0,
  `after_purchase_store` tinyint(4) DEFAULT NULL COMMENT '1=branch;2=warehouse',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `branches`
--

INSERT INTO `branches` (`id`, `name`, `branch_code`, `phone`, `city`, `state`, `zip_code`, `alternate_phone_number`, `country`, `email`, `website`, `logo`, `invoice_schema_id`, `add_sale_invoice_layout_id`, `pos_sale_invoice_layout_id`, `default_account_id`, `purchase_permission`, `after_purchase_store`, `created_at`, `updated_at`) VALUES
(24, 'SpeedDigit Computers', 'D122', '01792288555', 'Gawsia Kasem Center 10/2 Arambagh (7th floor)', 'Motijheel, Dhaka', '1000', NULL, 'Bangladesh', 'speeddigitinfo@gmail.com', NULL, '60891c09ddefb-.png', 3, 1, 1, NULL, 1, NULL, '2021-03-07 12:30:56', '2021-05-03 06:39:34'),
(25, 'Branch B', 'B1225', '0158799684', 'Dhaka', 'Dhaka', '458588', NULL, 'Bangladesh', 'b@gmail.com', 'https://branchB.com', '605efe866b64e-.png', 3, 1, 1, 15, 1, NULL, '2021-03-27 09:44:38', '2021-05-03 06:40:48'),
(26, 'Branch C', 'C12554', '0157896645', 'Dhaka', 'Dhaka', '788955', NULL, 'Bangladesh', 'c@gmail.com', NULL, '605efebfeeae8-.png', 3, 1, 1, 16, 1, NULL, '2021-03-27 09:45:35', '2021-04-12 12:47:09');

-- --------------------------------------------------------

--
-- Table structure for table `branch_payment_methods`
--

CREATE TABLE `branch_payment_methods` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED NOT NULL,
  `method_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_delete_in_update` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'default.png',
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `name`, `photo`, `status`, `created_at`, `updated_at`) VALUES
(5, 'Realme', '5fb214ee42d95.png', 1, NULL, NULL),
(6, 'Samsung', '5fb214f918f3c.png', 1, NULL, NULL),
(7, 'Transend Edited', '5fb215048a363.png', 1, NULL, '2020-11-15 23:59:01'),
(8, 'Asus', '5fb2150fbf4bb.jpg', 1, NULL, NULL),
(9, 'HP', '5fb2151a318c1.jpg', 1, NULL, NULL),
(10, 'Apple', '5fb379c0b4a2c.png', 1, NULL, NULL),
(11, 'Kama Sonic', 'default.png', 1, '2021-01-19 04:09:34', '2021-01-19 04:09:34'),
(12, 'Baby', 'default.png', 1, '2021-02-24 06:29:41', '2021-02-24 06:29:41'),
(14, 'Miako', 'default.png', 1, NULL, NULL),
(15, 'RealMax', 'default.png', 1, NULL, NULL),
(16, 'Lg', 'default.png', 1, NULL, '2021-04-20 10:22:23'),
(17, 'A4Tech', 'default.png', 1, '2021-04-28 05:19:14', '2021-04-28 05:19:14'),
(18, 'WD', 'default.png', 1, '2021-04-28 08:06:03', '2021-04-28 08:06:03'),
(19, 'Apacer', '6099fcc053834.png', 1, '2021-04-28 08:12:54', '2021-05-11 03:40:48'),
(20, 'Lenovo', '6099fcb14a6ff.png', 1, '2021-04-28 08:15:14', '2021-05-11 03:40:33'),
(21, 'Max Green', '6091000b367f9.png', 1, '2021-04-28 08:17:50', '2021-05-04 08:04:27'),
(25, 'Test Brand', 'default.png', 1, '2021-06-17 05:20:41', '2021-06-17 05:20:41'),
(26, 'Dell', 'default.png', 1, '2021-06-17 05:24:01', '2021-06-17 05:24:01');

-- --------------------------------------------------------

--
-- Table structure for table `bulk_variants`
--

CREATE TABLE `bulk_variants` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `bulk_variant_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bulk_variants`
--

INSERT INTO `bulk_variants` (`id`, `bulk_variant_name`, `created_at`, `updated_at`) VALUES
(7, 'Color', '2020-11-14 22:42:02', '2020-11-14 22:42:02'),
(8, 'Size', '2020-11-14 22:42:53', '2020-11-14 22:42:53'),
(9, 'Ram', '2020-11-14 22:44:33', '2020-11-14 22:44:33'),
(10, 'Storage', '2020-11-14 22:45:30', '2020-11-14 22:45:30'),
(12, 'Qualities', '2020-11-24 03:17:09', '2020-11-24 03:17:09'),
(13, 'Kilogram', '2020-12-30 03:08:35', '2020-12-30 03:08:35');

-- --------------------------------------------------------

--
-- Table structure for table `bulk_variant_children`
--

CREATE TABLE `bulk_variant_children` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `bulk_variant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `child_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `delete_in_update` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bulk_variant_children`
--

INSERT INTO `bulk_variant_children` (`id`, `bulk_variant_id`, `child_name`, `delete_in_update`, `created_at`, `updated_at`) VALUES
(18, 7, 'Red', 0, '2020-11-14 22:42:02', '2021-04-28 12:17:20'),
(19, 7, 'Green', 0, '2020-11-14 22:42:02', '2021-04-28 12:17:20'),
(20, 7, 'Blue', 0, '2020-11-14 22:42:02', '2021-04-28 12:17:20'),
(21, 7, 'Black', 0, '2020-11-14 22:42:02', '2021-04-28 12:17:20'),
(24, 8, 'M', 0, '2020-11-14 22:42:53', '2021-02-22 06:06:06'),
(25, 8, 'L', 0, '2020-11-14 22:42:53', '2021-02-22 06:06:06'),
(26, 8, 'XL', 0, '2020-11-14 22:42:53', '2021-02-22 06:06:06'),
(27, 8, 'XXL', 0, '2020-11-14 22:42:53', '2021-02-22 06:06:06'),
(28, 9, '2GB', 0, '2020-11-14 22:44:33', '2021-04-28 12:17:23'),
(29, 9, '3GB', 0, '2020-11-14 22:44:33', '2021-04-28 12:17:23'),
(30, 9, '4GB', 0, '2020-11-14 22:44:33', '2021-04-28 12:17:23'),
(31, 9, '6GB', 0, '2020-11-14 22:44:33', '2021-04-28 12:17:23'),
(32, 9, '8GB', 0, '2020-11-14 22:44:33', '2021-04-28 12:17:23'),
(33, 9, '12GB', 0, '2020-11-14 22:44:33', '2021-04-28 12:17:23'),
(34, 10, '16GB', 0, '2020-11-14 22:45:30', '2021-04-21 04:16:53'),
(35, 10, '32GB', 0, '2020-11-14 22:45:30', '2021-04-21 04:16:53'),
(36, 10, '64GB', 0, '2020-11-14 22:45:30', '2021-04-21 04:16:53'),
(37, 10, '128GB', 0, '2020-11-14 22:45:30', '2021-04-21 04:16:53'),
(38, 10, '256GB', 0, '2020-11-14 22:45:30', '2021-04-21 04:16:53'),
(45, 12, 'Q-1', 0, '2020-11-24 03:17:09', '2021-04-21 04:18:05'),
(46, 12, 'Q-2', 0, '2020-11-24 03:17:09', '2021-04-21 04:18:05'),
(47, 12, 'Q-3', 0, '2020-11-24 03:17:09', '2021-04-21 04:18:05'),
(48, 12, 'Q-4', 0, '2020-11-24 03:17:09', '2021-04-21 04:18:05'),
(51, 13, '250Gram', 0, '2020-12-30 03:08:35', '2021-04-28 12:17:27'),
(52, 13, '500Gram', 0, '2020-12-30 03:08:35', '2021-04-28 12:17:27'),
(53, 13, '1000Gram', 0, '2020-12-30 03:08:35', '2021-04-28 12:17:27'),
(54, 13, '2KG', 0, '2020-12-30 03:08:35', '2021-04-28 12:17:27'),
(55, 13, '750Gram', 0, '2020-12-30 03:08:35', '2021-04-28 12:17:27'),
(56, 10, '512GB', 0, '2021-02-08 10:26:16', '2021-04-21 04:16:53');

-- --------------------------------------------------------

--
-- Table structure for table `card_types`
--

CREATE TABLE `card_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `card_type_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `card_types`
--

INSERT INTO `card_types` (`id`, `card_type_name`, `account_id`, `created_at`, `updated_at`) VALUES
(4, 'Master Card', 15, NULL, NULL),
(5, 'ATM Card', 16, NULL, '2021-06-17 11:57:02'),
(6, 'Pioneer Card', 15, NULL, NULL),
(7, 'Visa Card', 15, NULL, NULL),
(8, 'Debit Card', 16, NULL, NULL),
(9, 'Credit Card', 15, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cash_counters`
--

CREATE TABLE `cash_counters` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `counter_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `short_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cash_counters`
--

INSERT INTO `cash_counters` (`id`, `counter_name`, `short_name`, `created_at`, `updated_at`) VALUES
(2, 'Cash Counter No 1', 'CCNO-1', NULL, NULL),
(3, 'Cash Counter No 2', 'CCNO-2', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cash_flows`
--

CREATE TABLE `cash_flows` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `account_id` bigint(20) UNSIGNED NOT NULL,
  `sender_account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `receiver_account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `purchase_payment_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sale_payment_id` bigint(20) UNSIGNED DEFAULT NULL,
  `expanse_payment_id` bigint(20) UNSIGNED DEFAULT NULL,
  `money_receipt_id` bigint(20) UNSIGNED DEFAULT NULL,
  `payroll_id` bigint(20) UNSIGNED DEFAULT NULL,
  `payroll_payment_id` bigint(20) UNSIGNED DEFAULT NULL,
  `debit` decimal(22,2) DEFAULT NULL,
  `credit` decimal(22,2) DEFAULT NULL,
  `balance` decimal(22,2) NOT NULL DEFAULT 0.00,
  `transaction_type` tinyint(4) NOT NULL COMMENT '1=payment;2=sale_payment;3=purchase_payment;4=fundTransfer;5=deposit;6=expansePayment;7=openingBalance;8=payroll_payment;9=money_receipt',
  `cash_type` tinyint(4) DEFAULT NULL COMMENT '1=debit;2=credit;',
  `date` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `month` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `year` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `report_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `admin_id` bigint(20) UNSIGNED DEFAULT NULL,
  `related_cash_flow_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cash_flows`
--

INSERT INTO `cash_flows` (`id`, `account_id`, `sender_account_id`, `receiver_account_id`, `purchase_payment_id`, `sale_payment_id`, `expanse_payment_id`, `money_receipt_id`, `payroll_id`, `payroll_payment_id`, `debit`, `credit`, `balance`, `transaction_type`, `cash_type`, `date`, `month`, `year`, `report_date`, `admin_id`, `related_cash_flow_id`, `created_at`, `updated_at`) VALUES
(4, 16, NULL, NULL, NULL, 4, NULL, NULL, NULL, NULL, NULL, '13348.75', '13957.00', 2, 2, '11-04-2021', 'April', '2021', '2021-04-10 18:00:00', 3, NULL, '2021-04-11 06:20:36', '2021-04-11 06:20:36'),
(5, 16, NULL, NULL, NULL, 6, NULL, NULL, NULL, NULL, NULL, '1805.00', '15762.00', 2, 2, '11-04-2021', 'April', '2021', '2021-04-10 18:00:00', 3, NULL, '2021-04-11 07:15:58', '2021-04-11 07:15:58'),
(6, 16, NULL, NULL, 102, NULL, NULL, NULL, NULL, NULL, '730.00', NULL, '15032.00', 3, 1, '13-04-2021', 'April', '2021', '2021-04-12 18:00:00', 3, NULL, '2021-04-13 06:16:42', '2021-04-13 06:16:42'),
(7, 16, NULL, NULL, NULL, 9, NULL, NULL, NULL, NULL, NULL, '1023.75', '16055.75', 2, 2, '17-04-2021', 'April', '2021', '2021-04-16 18:00:00', 2, NULL, '2021-04-17 03:47:27', '2021-04-17 03:47:27'),
(9, 15, NULL, NULL, NULL, NULL, NULL, 37, NULL, NULL, NULL, '500.00', '21675.00', 9, 2, '19-04-2021', 'April', '2021', '2021-04-18 18:00:00', 2, NULL, '2021-04-19 04:21:08', '2021-04-19 04:21:08'),
(10, 15, NULL, NULL, NULL, NULL, NULL, 40, NULL, NULL, NULL, '200.00', '21675.00', 9, 2, '19-04-2021', 'April', '2021', '2021-04-18 18:00:00', 2, NULL, '2021-04-19 05:07:19', '2021-04-19 05:07:19'),
(13, 15, NULL, NULL, NULL, 15, NULL, NULL, NULL, NULL, NULL, '315.00', '22620.00', 2, 2, '19-04-2021', 'April', '2021', '2021-04-18 18:00:00', 2, NULL, '2021-04-19 05:10:18', '2021-04-19 05:10:18'),
(15, 15, NULL, NULL, NULL, NULL, NULL, 41, NULL, NULL, NULL, '630.00', '23565.00', 9, 2, '19-04-2021', 'April', '2021', '2021-04-18 18:00:00', 2, NULL, '2021-04-19 05:10:18', '2021-04-19 05:10:18'),
(16, 15, NULL, NULL, NULL, NULL, NULL, 42, NULL, NULL, NULL, '400.00', '23415.00', 9, 2, '19-04-2021', 'April', '2021', '2021-04-18 18:00:00', 2, NULL, '2021-04-19 05:25:22', '2021-04-19 05:25:22'),
(17, 16, NULL, NULL, NULL, NULL, NULL, 43, NULL, NULL, NULL, '50.00', '16703.25', 9, 2, '19-04-2021', 'April', '2021', '2021-04-18 18:00:00', 2, NULL, '2021-04-19 05:34:12', '2021-04-19 05:34:12'),
(18, 16, NULL, NULL, NULL, NULL, NULL, 43, NULL, NULL, NULL, '150.00', '16853.25', 9, 2, '19-04-2021', 'April', '2021', '2021-04-18 18:00:00', 2, NULL, '2021-04-19 05:34:12', '2021-04-19 05:34:12'),
(20, 15, NULL, NULL, 104, NULL, NULL, NULL, NULL, NULL, '1150.00', NULL, '21115.00', 3, 1, '2021-04-21', 'April', '2021', '2021-04-20 18:00:00', 2, NULL, '2021-04-22 06:25:37', '2021-04-22 06:26:04'),
(21, 16, NULL, NULL, 111, NULL, NULL, NULL, NULL, NULL, '1150.00', NULL, '15703.25', 3, 1, '2021-04-22', 'April', '2021', '2021-04-21 18:00:00', 2, NULL, '2021-04-22 06:43:25', '2021-04-22 06:43:25'),
(22, 16, NULL, NULL, 112, NULL, NULL, NULL, NULL, NULL, '20.00', NULL, '15683.25', 3, 1, '2021-04-22', 'April', '2021', '2021-04-21 18:00:00', 2, NULL, '2021-04-22 07:15:00', '2021-04-22 07:15:00'),
(23, 16, NULL, NULL, 116, NULL, NULL, NULL, NULL, NULL, '315.00', NULL, '15368.25', 3, 1, '2021-04-24', 'April', '2021', '2021-04-23 18:00:00', 2, NULL, '2021-04-24 09:46:30', '2021-04-24 09:46:30'),
(24, 16, NULL, NULL, 119, NULL, NULL, NULL, NULL, NULL, '210.00', NULL, '15158.25', 3, 1, '2021-04-25', 'April', '2021', '2021-04-24 18:00:00', 3, NULL, '2021-04-25 07:40:51', '2021-04-25 07:40:51'),
(25, 16, NULL, NULL, NULL, 17, NULL, NULL, NULL, NULL, NULL, '2566.25', '17724.50', 2, 2, '25-04-2021', 'April', '2021', '2021-04-24 18:00:00', 3, NULL, '2021-04-25 11:46:06', '2021-04-25 11:46:06'),
(26, 16, NULL, NULL, NULL, 18, NULL, NULL, NULL, NULL, NULL, '1411.25', '19135.75', 2, 2, '25-04-2021', 'April', '2021', '2021-04-24 18:00:00', 3, NULL, '2021-04-25 11:56:13', '2021-04-25 11:56:13'),
(29, 15, NULL, NULL, NULL, 22, NULL, NULL, NULL, NULL, NULL, '1173.75', '23438.75', 2, 2, '25-04-2021', 'April', '2021', '2021-04-24 18:00:00', 2, NULL, '2021-04-25 13:43:29', '2021-04-25 13:43:29'),
(30, 16, NULL, NULL, NULL, 38, NULL, NULL, NULL, NULL, NULL, '180.06', '19315.81', 2, 2, '26-04-2021', 'April', '2021', '2021-04-25 18:00:00', 2, NULL, '2021-04-26 10:48:17', '2021-04-26 10:48:17'),
(36, 16, NULL, 15, NULL, NULL, NULL, NULL, NULL, NULL, '10.00', NULL, '19205.81', 4, 1, '2021-05-01', 'May', '2021', '2021-05-01 05:08:02', 2, 37, '2021-05-01 05:08:02', '2021-05-01 05:08:02'),
(37, 15, 16, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '10.00', '21773.75', 4, 2, '2021-05-01', 'May', '2021', '2021-04-30 18:00:00', 2, 36, '2021-05-01 05:08:02', '2021-05-01 05:08:02'),
(38, 16, NULL, 15, NULL, NULL, NULL, NULL, NULL, NULL, '20.00', NULL, '19185.81', 4, 1, '2021-05-01', 'May', '2021', '2021-05-01 05:08:20', 2, 39, '2021-05-01 05:08:20', '2021-05-01 05:08:20'),
(39, 15, 16, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '20.00', '21793.75', 4, 2, '2021-05-01', 'May', '2021', '2021-04-30 18:00:00', 2, 38, '2021-05-01 05:08:20', '2021-05-01 05:08:20'),
(40, 16, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '10.00', '19195.81', 5, 2, '2021-05-01', 'May', '2021', '2021-04-30 18:00:00', 2, NULL, '2021-05-01 05:13:55', '2021-05-01 05:13:55'),
(41, 16, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '20.00', '19215.81', 5, 2, '2021-05-01', 'May', '2021', '2021-04-30 18:00:00', 2, NULL, '2021-05-01 05:14:02', '2021-05-01 05:14:02'),
(42, 16, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '20.00', '19235.81', 5, 2, '2021-05-01', 'May', '2021', '2021-04-30 18:00:00', 2, NULL, '2021-05-01 05:14:10', '2021-05-01 05:14:10'),
(43, 16, NULL, 15, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', NULL, '19235.81', 4, 1, '2021-05-01', 'May', '2021', '2021-05-01 05:33:11', 2, 44, '2021-05-01 05:33:11', '2021-05-01 05:33:11'),
(44, 15, 16, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '21793.75', 4, 2, '2021-05-01', 'May', '2021', '2021-04-30 18:00:00', 2, 43, '2021-05-01 05:33:11', '2021-05-01 05:33:11'),
(49, 16, NULL, NULL, NULL, 41, NULL, NULL, NULL, NULL, NULL, '162.50', '19398.31', 2, 2, '2021-05-02', 'May', '2021', '2021-05-01 18:00:00', 2, NULL, '2021-05-02 10:44:58', '2021-05-02 10:44:58'),
(51, 16, NULL, NULL, 124, NULL, NULL, NULL, NULL, NULL, '9500.00', NULL, '2498.31', 3, 1, '2021-05-04', 'May', '2021', '2021-05-03 18:00:00', 2, NULL, '2021-05-04 03:58:13', '2021-05-04 03:58:13'),
(52, 15, NULL, NULL, NULL, 49, NULL, NULL, NULL, NULL, NULL, '1023.75', '22712.50', 2, 2, '08-05-2021', 'May', '2021', '2021-05-07 18:00:00', 2, NULL, '2021-05-08 12:44:59', '2021-05-08 12:44:59'),
(53, 15, NULL, NULL, NULL, 50, NULL, NULL, NULL, NULL, NULL, '300.00', '23012.50', 2, 2, '08-05-2021', 'May', '2021', '2021-05-07 18:00:00', 2, NULL, '2021-05-08 12:45:49', '2021-05-08 12:45:49'),
(54, 16, NULL, NULL, NULL, 58, NULL, NULL, NULL, NULL, NULL, '262.50', '2760.81', 2, 2, '08-05-2021', 'May', '2021', '2021-05-07 18:00:00', 2, NULL, '2021-05-08 13:07:54', '2021-05-08 13:07:54'),
(55, 16, NULL, NULL, NULL, 59, NULL, NULL, NULL, NULL, NULL, '262.50', '3023.31', 2, 2, '08-05-2021', 'May', '2021', '2021-05-07 18:00:00', 2, NULL, '2021-05-08 13:10:35', '2021-05-08 13:10:35'),
(56, 16, NULL, NULL, NULL, 60, NULL, NULL, NULL, NULL, NULL, '65.63', '3088.94', 2, 2, '08-05-2021', 'May', '2021', '2021-05-07 18:00:00', 2, NULL, '2021-05-08 13:11:24', '2021-05-08 13:11:24'),
(57, 16, NULL, NULL, NULL, 64, NULL, NULL, NULL, NULL, NULL, '262.50', '3351.44', 2, 2, '08-05-2021', 'May', '2021', '2021-05-07 18:00:00', 2, NULL, '2021-05-08 13:16:33', '2021-05-08 13:16:33'),
(58, 16, NULL, NULL, NULL, 66, NULL, NULL, NULL, NULL, NULL, '262.50', '3613.94', 2, 2, '08-05-2021', 'May', '2021', '2021-05-07 18:00:00', 2, NULL, '2021-05-08 13:18:34', '2021-05-08 13:18:34'),
(60, 16, NULL, NULL, NULL, 77, NULL, NULL, NULL, NULL, NULL, '262.50', '4138.94', 2, 2, '08-05-2021', 'May', '2021', '2021-05-07 18:00:00', 2, NULL, '2021-05-08 13:45:53', '2021-05-08 13:45:53'),
(61, 16, NULL, NULL, NULL, 78, NULL, NULL, NULL, NULL, NULL, '150.63', '4289.57', 2, 2, '08-05-2021', 'May', '2021', '2021-05-07 18:00:00', 2, NULL, '2021-05-08 13:45:53', '2021-05-08 13:45:53'),
(62, 16, NULL, NULL, NULL, 79, NULL, NULL, NULL, NULL, NULL, '262.50', '4552.07', 2, 2, '08-05-2021', 'May', '2021', '2021-05-07 18:00:00', 2, NULL, '2021-05-08 13:45:53', '2021-05-08 13:45:53'),
(63, 16, NULL, NULL, NULL, 80, NULL, NULL, NULL, NULL, NULL, '262.50', '4814.57', 2, 2, '08-05-2021', 'May', '2021', '2021-05-07 18:00:00', 2, NULL, '2021-05-08 13:45:53', '2021-05-08 13:45:53'),
(64, 16, NULL, NULL, NULL, 82, NULL, NULL, NULL, NULL, NULL, '254.63', '5069.20', 2, 2, '09-05-2021', 'May', '2021', '2021-05-08 18:00:00', 2, NULL, '2021-05-09 12:32:55', '2021-05-09 12:32:55'),
(68, 16, NULL, NULL, NULL, 109, NULL, NULL, NULL, NULL, NULL, '262.50', '5594.20', 2, 2, '10-05-2021', 'May', '2021', '2021-05-09 18:00:00', 2, NULL, '2021-05-10 05:23:23', '2021-05-10 05:23:23'),
(69, 16, NULL, NULL, NULL, 113, NULL, NULL, NULL, NULL, NULL, '262.50', '5594.20', 2, 2, '10-05-2021', 'May', '2021', '2021-05-09 18:00:00', 2, NULL, '2021-05-10 13:06:22', '2021-05-10 13:06:22'),
(71, 15, NULL, NULL, NULL, 121, NULL, NULL, NULL, NULL, NULL, '100.00', '23112.50', 2, 2, '18-05-2021', 'May', '2021', '2021-05-17 18:00:00', 2, NULL, '2021-05-18 07:26:26', '2021-05-18 07:26:26'),
(72, 15, NULL, NULL, NULL, 122, NULL, NULL, NULL, NULL, NULL, '100.00', '23212.50', 2, 2, '18-05-2021', 'May', '2021', '2021-05-17 18:00:00', 2, NULL, '2021-05-18 07:32:40', '2021-05-18 07:32:40'),
(73, 15, NULL, NULL, NULL, 123, NULL, NULL, NULL, NULL, NULL, '10.00', '23222.50', 2, 2, '18-05-2021', 'May', '2021', '2021-05-17 18:00:00', 2, NULL, '2021-05-18 09:28:34', '2021-05-18 09:28:34'),
(74, 15, NULL, NULL, NULL, 124, NULL, NULL, NULL, NULL, NULL, '1200.00', '24422.50', 2, 2, '18-05-2021', 'May', '2021', '2021-05-17 18:00:00', 2, NULL, '2021-05-18 13:23:04', '2021-05-18 13:23:04'),
(75, 15, NULL, NULL, NULL, 125, NULL, NULL, NULL, NULL, NULL, '1023.75', '25446.25', 2, 2, '18-05-2021', 'May', '2021', '2021-05-17 18:00:00', 2, NULL, '2021-05-18 13:30:41', '2021-05-18 13:30:41'),
(80, 15, NULL, NULL, NULL, 131, NULL, NULL, NULL, NULL, NULL, '400.00', '55351.75', 2, 2, '19-05-2021', 'May', '2021', '2021-05-18 18:00:00', 2, NULL, '2021-05-19 05:02:33', '2021-05-19 05:02:33'),
(81, 16, NULL, NULL, NULL, NULL, 31, NULL, NULL, NULL, '100.00', NULL, '5494.20', 6, 1, '2021-05-23', 'May', '2021', '2021-05-22 18:00:00', 2, NULL, '2021-05-23 11:27:15', '2021-05-23 11:27:15'),
(82, 15, NULL, NULL, NULL, 138, NULL, NULL, NULL, NULL, NULL, '262.50', '55614.25', 2, 2, '23-05-2021', 'May', '2021', '2021-05-22 18:00:00', 2, NULL, '2021-05-23 12:21:53', '2021-05-23 12:21:53'),
(83, 16, NULL, NULL, NULL, NULL, 32, NULL, NULL, NULL, '57.00', NULL, '5437.20', 6, 1, '2021-05-24', 'May', '2021', '2021-05-23 18:00:00', 7, NULL, '2021-05-24 04:49:47', '2021-05-24 04:49:47'),
(84, 15, NULL, NULL, NULL, NULL, 33, NULL, NULL, NULL, '15.00', NULL, '55599.25', 6, 1, '2021-05-24', 'May', '2021', '2021-05-23 18:00:00', 7, NULL, '2021-05-24 05:07:13', '2021-05-24 05:07:25'),
(85, 15, NULL, NULL, NULL, NULL, 34, NULL, NULL, NULL, '46.00', NULL, '55553.25', 6, 1, '2021-05-24', 'May', '2021', '2021-05-23 18:00:00', 7, NULL, '2021-05-24 05:07:37', '2021-05-24 05:07:37'),
(87, 15, NULL, NULL, NULL, 144, NULL, NULL, NULL, NULL, NULL, '742.50', '43295.75', 2, 2, '03-06-2021', 'June', '2021', '2021-06-02 18:00:00', 2, NULL, '2021-06-03 07:32:38', '2021-06-03 07:32:38'),
(88, 15, NULL, NULL, NULL, 145, NULL, NULL, NULL, NULL, NULL, '262.50', '43558.25', 2, 2, '03-06-2021', 'June', '2021', '2021-06-02 18:00:00', 2, NULL, '2021-06-03 07:51:44', '2021-06-03 07:51:44'),
(89, 15, NULL, NULL, NULL, 146, NULL, NULL, NULL, NULL, NULL, '886.25', '44444.50', 2, 2, '03-06-2021', 'June', '2021', '2021-06-02 18:00:00', 2, NULL, '2021-06-03 07:55:22', '2021-06-03 07:55:22'),
(90, 15, NULL, NULL, NULL, 147, NULL, NULL, NULL, NULL, NULL, '162.50', '44607.00', 2, 2, '03-06-2021', 'June', '2021', '2021-06-02 18:00:00', 2, NULL, '2021-06-03 08:00:48', '2021-06-03 08:00:48'),
(91, 15, NULL, NULL, NULL, 148, NULL, NULL, NULL, NULL, NULL, '262.50', '44869.50', 2, 2, '03-06-2021', 'June', '2021', '2021-06-02 18:00:00', 2, NULL, '2021-06-03 12:36:12', '2021-06-03 12:36:12'),
(92, 15, NULL, NULL, NULL, 149, NULL, NULL, NULL, NULL, NULL, '125.00', '44994.50', 2, 2, '03-06-2021', 'June', '2021', '2021-06-02 18:00:00', 2, NULL, '2021-06-03 12:49:39', '2021-06-03 12:49:39'),
(93, 15, NULL, NULL, NULL, 150, NULL, NULL, NULL, NULL, NULL, '262.50', '45257.00', 2, 2, '03-06-2021', 'June', '2021', '2021-06-02 18:00:00', 2, NULL, '2021-06-03 12:57:26', '2021-06-03 12:57:26'),
(94, 15, NULL, NULL, NULL, 151, NULL, NULL, NULL, NULL, NULL, '125.00', '45382.00', 2, 2, '03-06-2021', 'June', '2021', '2021-06-02 18:00:00', 2, NULL, '2021-06-03 13:03:27', '2021-06-03 13:03:27'),
(95, 15, NULL, NULL, NULL, 152, NULL, NULL, NULL, NULL, NULL, '886.25', '46268.25', 2, 2, '03-06-2021', 'June', '2021', '2021-06-02 18:00:00', 2, NULL, '2021-06-03 13:10:38', '2021-06-03 13:10:38'),
(96, 15, NULL, NULL, NULL, 153, NULL, NULL, NULL, NULL, NULL, '262.50', '46530.75', 2, 2, '03-06-2021', 'June', '2021', '2021-06-02 18:00:00', 2, NULL, '2021-06-03 13:11:16', '2021-06-03 13:11:16'),
(97, 15, NULL, NULL, NULL, 154, NULL, NULL, NULL, NULL, NULL, '761.25', '47292.00', 2, 2, '03-06-2021', 'June', '2021', '2021-06-02 18:00:00', 2, NULL, '2021-06-03 13:12:29', '2021-06-03 13:12:29'),
(98, 15, NULL, NULL, NULL, 155, NULL, NULL, NULL, NULL, NULL, '107.50', '47399.50', 2, 2, '03-06-2021', 'June', '2021', '2021-06-02 18:00:00', 2, NULL, '2021-06-03 13:14:09', '2021-06-03 13:14:09'),
(99, 15, NULL, NULL, NULL, 156, NULL, NULL, NULL, NULL, NULL, '262.50', '47662.00', 2, 2, '03-06-2021', 'June', '2021', '2021-06-02 18:00:00', 2, NULL, '2021-06-03 13:21:23', '2021-06-03 13:21:23'),
(100, 15, NULL, NULL, NULL, 157, NULL, NULL, NULL, NULL, NULL, '118.75', '47780.75', 2, 2, '03-06-2021', 'June', '2021', '2021-06-02 18:00:00', 2, NULL, '2021-06-03 13:22:00', '2021-06-03 13:22:00'),
(101, 15, NULL, NULL, NULL, 158, NULL, NULL, NULL, NULL, NULL, '250.00', '48030.75', 2, 2, '03-06-2021', 'June', '2021', '2021-06-02 18:00:00', 2, NULL, '2021-06-03 13:24:06', '2021-06-03 13:24:06'),
(102, 15, NULL, NULL, NULL, 159, NULL, NULL, NULL, NULL, NULL, '250.00', '48280.75', 2, 2, '03-06-2021', 'June', '2021', '2021-06-02 18:00:00', 2, NULL, '2021-06-03 13:25:26', '2021-06-03 13:25:26'),
(103, 15, NULL, NULL, NULL, 160, NULL, NULL, NULL, NULL, NULL, '6.25', '48287.00', 2, 2, '03-06-2021', 'June', '2021', '2021-06-02 18:00:00', 2, NULL, '2021-06-03 13:26:04', '2021-06-03 13:26:04'),
(104, 15, NULL, NULL, NULL, 161, NULL, NULL, NULL, NULL, NULL, '6.25', '48293.25', 2, 2, '03-06-2021', 'June', '2021', '2021-06-02 18:00:00', 2, NULL, '2021-06-03 13:26:45', '2021-06-03 13:26:45'),
(105, 15, NULL, NULL, NULL, 162, NULL, NULL, NULL, NULL, NULL, '887.50', '49180.75', 2, 2, '03-06-2021', 'June', '2021', '2021-06-02 18:00:00', 2, NULL, '2021-06-03 13:28:35', '2021-06-03 13:28:35'),
(106, 15, NULL, NULL, NULL, 163, NULL, NULL, NULL, NULL, NULL, '12.50', '49193.25', 2, 2, '03-06-2021', 'June', '2021', '2021-06-02 18:00:00', 2, NULL, '2021-06-03 13:29:07', '2021-06-03 13:29:07'),
(107, 15, NULL, NULL, NULL, 164, NULL, NULL, NULL, NULL, NULL, '25.00', '49218.25', 2, 2, '03-06-2021', 'June', '2021', '2021-06-02 18:00:00', 2, NULL, '2021-06-03 13:30:09', '2021-06-03 13:30:09'),
(108, 15, NULL, NULL, NULL, 165, NULL, NULL, NULL, NULL, NULL, '262.50', '49480.75', 2, 2, '03-06-2021', 'June', '2021', '2021-06-02 18:00:00', 2, NULL, '2021-06-03 13:31:57', '2021-06-03 13:31:57'),
(109, 15, NULL, NULL, NULL, 166, NULL, NULL, NULL, NULL, NULL, '118.75', '49599.50', 2, 2, '03-06-2021', 'June', '2021', '2021-06-02 18:00:00', 2, NULL, '2021-06-03 13:33:52', '2021-06-03 13:33:52'),
(110, 16, NULL, NULL, NULL, 167, NULL, NULL, NULL, NULL, NULL, '262.50', '5699.70', 2, 2, '05-06-2021', 'June', '2021', '2021-06-04 18:00:00', 2, NULL, '2021-06-05 05:36:29', '2021-06-05 05:36:29'),
(111, 15, NULL, NULL, NULL, 168, NULL, NULL, NULL, NULL, NULL, '767.25', '50366.75', 2, 2, '06-06-2021', 'June', '2021', '2021-06-05 18:00:00', 2, NULL, '2021-06-06 05:15:40', '2021-06-06 05:15:40'),
(112, 16, NULL, NULL, NULL, 140, NULL, NULL, NULL, NULL, NULL, '269.06', '5968.76', 2, 2, '2021-05-24', 'June', '2021', '2021-05-23 18:00:00', 7, NULL, '2021-06-07 09:41:46', '2021-06-07 09:41:46'),
(113, 15, NULL, NULL, NULL, 174, NULL, NULL, NULL, NULL, NULL, '300.00', '50666.75', 2, 2, '08-06-2021', 'June', '2021', '2021-06-07 18:00:00', 2, NULL, '2021-06-08 10:10:55', '2021-06-08 10:10:55'),
(114, 15, NULL, NULL, NULL, 175, NULL, NULL, NULL, NULL, NULL, '140.00', '50806.75', 2, 2, '08-06-2021', 'June', '2021', '2021-06-07 18:00:00', 2, NULL, '2021-06-08 10:18:13', '2021-06-08 10:18:13'),
(115, 15, NULL, NULL, NULL, 176, NULL, NULL, NULL, NULL, NULL, '131.25', '50938.00', 2, 2, '08-06-2021', 'June', '2021', '2021-06-07 18:00:00', 2, NULL, '2021-06-08 10:27:11', '2021-06-08 10:27:11'),
(116, 15, NULL, NULL, NULL, 177, NULL, NULL, NULL, NULL, NULL, '140.00', '51078.00', 2, 2, '08-06-2021', 'June', '2021', '2021-06-07 18:00:00', 2, NULL, '2021-06-08 10:27:24', '2021-06-08 10:27:24'),
(117, 15, NULL, NULL, NULL, 178, NULL, NULL, NULL, NULL, NULL, '140.00', '51218.00', 2, 2, '08-06-2021', 'June', '2021', '2021-06-07 18:00:00', 2, NULL, '2021-06-08 10:28:23', '2021-06-08 10:28:23'),
(118, 15, NULL, NULL, NULL, 179, NULL, NULL, NULL, NULL, NULL, '300.00', '51518.00', 2, 2, '08-06-2021', 'June', '2021', '2021-06-07 18:00:00', 2, NULL, '2021-06-08 10:31:36', '2021-06-08 10:31:36'),
(119, 15, NULL, NULL, NULL, 180, NULL, NULL, NULL, NULL, NULL, '300.00', '51818.00', 2, 2, '08-06-2021', 'June', '2021', '2021-06-07 18:00:00', 2, NULL, '2021-06-08 10:31:46', '2021-06-08 10:31:46'),
(120, 15, NULL, NULL, NULL, 181, NULL, NULL, NULL, NULL, NULL, '300.00', '52118.00', 2, 2, '08-06-2021', 'June', '2021', '2021-06-07 18:00:00', 2, NULL, '2021-06-08 10:32:26', '2021-06-08 10:32:26'),
(121, 15, NULL, NULL, NULL, 182, NULL, NULL, NULL, NULL, NULL, '300.00', '52418.00', 2, 2, '08-06-2021', 'June', '2021', '2021-06-07 18:00:00', 2, NULL, '2021-06-08 10:34:57', '2021-06-08 10:34:57'),
(122, 15, NULL, NULL, NULL, 183, NULL, NULL, NULL, NULL, NULL, '300.00', '52718.00', 2, 2, '08-06-2021', 'June', '2021', '2021-06-07 18:00:00', 2, NULL, '2021-06-08 10:35:05', '2021-06-08 10:35:05'),
(123, 15, NULL, NULL, NULL, 184, NULL, NULL, NULL, NULL, NULL, '300.00', '53018.00', 2, 2, '08-06-2021', 'June', '2021', '2021-06-07 18:00:00', 2, NULL, '2021-06-08 10:35:48', '2021-06-08 10:35:48'),
(124, 15, NULL, NULL, NULL, 185, NULL, NULL, NULL, NULL, NULL, '140.00', '53158.00', 2, 2, '08-06-2021', 'June', '2021', '2021-06-07 18:00:00', 2, NULL, '2021-06-08 10:36:05', '2021-06-08 10:36:05'),
(125, 15, NULL, NULL, NULL, 186, NULL, NULL, NULL, NULL, NULL, '140.00', '53298.00', 2, 2, '08-06-2021', 'June', '2021', '2021-06-07 18:00:00', 2, NULL, '2021-06-08 10:36:59', '2021-06-08 10:36:59'),
(126, 15, NULL, NULL, NULL, 187, NULL, NULL, NULL, NULL, NULL, '140.00', '53438.00', 2, 2, '08-06-2021', 'June', '2021', '2021-06-07 18:00:00', 2, NULL, '2021-06-08 10:39:17', '2021-06-08 10:39:17'),
(127, 15, NULL, NULL, NULL, 188, NULL, NULL, NULL, NULL, NULL, '140.00', '53578.00', 2, 2, '08-06-2021', 'June', '2021', '2021-06-07 18:00:00', 2, NULL, '2021-06-08 10:55:39', '2021-06-08 10:55:39'),
(128, 16, NULL, NULL, NULL, 201, NULL, NULL, NULL, NULL, '131.25', NULL, '5837.51', 2, 1, '2021-06-08', 'June', '2021', '2021-06-07 18:00:00', 2, NULL, '2021-06-08 12:09:44', '2021-06-08 12:09:44'),
(129, 15, NULL, NULL, NULL, 202, NULL, NULL, NULL, NULL, NULL, '140.00', '53718.00', 2, 2, '08-06-2021', 'June', '2021', '2021-06-07 18:00:00', 2, NULL, '2021-06-08 13:07:02', '2021-06-08 13:07:02'),
(130, 15, NULL, NULL, NULL, 203, NULL, NULL, NULL, NULL, NULL, '131.25', '53849.25', 2, 2, '08-06-2021', 'June', '2021', '2021-06-07 18:00:00', 2, NULL, '2021-06-08 13:10:33', '2021-06-08 13:10:33'),
(131, 15, NULL, NULL, NULL, 204, NULL, NULL, NULL, NULL, NULL, '109.25', '53958.50', 2, 2, '08-06-2021', 'June', '2021', '2021-06-07 18:00:00', 2, NULL, '2021-06-08 13:14:18', '2021-06-08 13:14:18'),
(132, 15, NULL, NULL, NULL, 205, NULL, NULL, NULL, NULL, NULL, '600.00', '54558.50', 2, 2, '08-06-2021', 'June', '2021', '2021-06-07 18:00:00', 2, NULL, '2021-06-08 13:18:17', '2021-06-08 13:18:17'),
(133, 15, NULL, NULL, NULL, 206, NULL, NULL, NULL, NULL, NULL, '125.00', '54683.50', 2, 2, '08-06-2021', 'June', '2021', '2021-06-07 18:00:00', 2, NULL, '2021-06-08 13:19:25', '2021-06-08 13:19:25'),
(134, 15, NULL, NULL, NULL, 208, NULL, NULL, NULL, NULL, NULL, '1600.00', '56283.50', 2, 2, '09-06-2021', 'June', '2021', '2021-06-08 18:00:00', 2, NULL, '2021-06-09 08:56:54', '2021-06-09 08:56:54'),
(135, 16, NULL, NULL, NULL, 209, NULL, NULL, NULL, NULL, NULL, '498.75', '5338.76', 2, 2, '09-06-2021', 'June', '2021', '2021-06-08 18:00:00', 2, NULL, '2021-06-09 09:24:42', '2021-06-09 09:24:42'),
(136, 16, NULL, NULL, NULL, 210, NULL, NULL, NULL, NULL, NULL, '31.00', '5307.76', 2, 2, '09-06-2021', 'June', '2021', '2021-06-08 18:00:00', 2, NULL, '2021-06-09 09:25:04', '2021-06-09 09:25:04'),
(139, 16, NULL, NULL, NULL, 213, NULL, NULL, NULL, NULL, NULL, '162.50', '100020.26', 2, 2, '09-06-2021', 'June', '2021', '2021-06-08 18:00:00', 2, NULL, '2021-06-09 09:27:00', '2021-06-09 09:27:00'),
(148, 15, NULL, NULL, NULL, NULL, NULL, NULL, 13, 15, '20490.00', NULL, '36843.50', 8, 1, '2021-06-10', 'June', '2021', '2021-06-09 18:00:00', 2, NULL, '2021-06-10 07:55:24', '2021-06-10 07:55:24'),
(149, 16, NULL, NULL, NULL, NULL, NULL, NULL, 14, 16, '20490.00', NULL, '79530.26', 8, 1, '2021-06-10', 'June', '2021', '2021-06-09 18:00:00', 2, NULL, '2021-06-10 08:42:26', '2021-06-10 08:42:26'),
(150, 15, NULL, NULL, NULL, 240, NULL, NULL, NULL, NULL, NULL, '140.00', '36983.50', 2, 2, '10-06-2021', 'June', '2021', '2021-06-09 18:00:00', 2, NULL, '2021-06-10 11:10:04', '2021-06-10 11:10:04'),
(151, 16, NULL, NULL, NULL, 245, NULL, NULL, NULL, NULL, NULL, '131.25', '79661.51', 2, 2, '15-06-2021', 'June', '2021', '2021-06-14 18:00:00', 2, NULL, '2021-06-15 06:18:20', '2021-06-15 06:18:20'),
(152, 16, NULL, NULL, 128, NULL, NULL, NULL, NULL, NULL, '800000.00', NULL, '-720338.49', 3, 1, '2021-06-17', 'June', '2021', '2021-06-16 18:00:00', 2, NULL, '2021-06-17 09:15:14', '2021-06-17 09:15:14'),
(153, 15, NULL, NULL, NULL, 251, NULL, NULL, NULL, NULL, NULL, '400.00', '37383.50', 2, 2, '17-06-2021', 'June', '2021', '2021-06-16 18:00:00', 2, NULL, '2021-06-17 10:44:49', '2021-06-17 10:44:49');

-- --------------------------------------------------------

--
-- Table structure for table `cash_registers`
--

CREATE TABLE `cash_registers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `warehouse_id` bigint(20) UNSIGNED DEFAULT NULL,
  `account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `admin_id` bigint(20) UNSIGNED DEFAULT NULL,
  `closed_at` timestamp NULL DEFAULT NULL,
  `closed_amount` decimal(22,2) NOT NULL DEFAULT 0.00,
  `total_card_slips` bigint(20) DEFAULT NULL,
  `total_cheques` bigint(20) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1=open;0=closed;',
  `closing_note` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `cash_counter_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cash_registers`
--

INSERT INTO `cash_registers` (`id`, `branch_id`, `warehouse_id`, `account_id`, `admin_id`, `closed_at`, `closed_amount`, `total_card_slips`, `total_cheques`, `status`, `closing_note`, `created_at`, `updated_at`, `cash_counter_id`) VALUES
(1, NULL, 7, 15, 2, '2021-05-10 13:33:00', '100.00', 1, 0, 0, NULL, '2021-05-10 11:36:56', '2021-05-10 13:33:33', NULL),
(2, NULL, 7, 15, 2, '2021-05-10 13:48:00', '0.00', 0, 0, 0, NULL, '2021-05-10 13:33:55', '2021-05-10 13:48:07', NULL),
(3, NULL, 7, 16, 2, '2021-05-10 13:49:00', '200.00', 0, 0, 0, NULL, '2021-05-10 13:48:33', '2021-05-10 13:49:20', NULL),
(4, NULL, 7, NULL, 2, '2021-05-10 13:58:00', '100.00', 0, 0, 0, NULL, '2021-05-10 13:53:19', '2021-05-10 13:58:43', NULL),
(5, NULL, 7, 15, 2, '2021-05-17 11:52:00', '100.00', 0, 0, 0, NULL, '2021-05-11 03:21:08', '2021-05-17 11:52:01', NULL),
(6, 24, NULL, NULL, 7, '2021-05-18 08:12:00', '92100.00', 1, 0, 0, NULL, '2021-05-11 03:42:03', '2021-05-18 08:12:58', NULL),
(7, NULL, 7, 15, 2, '2021-06-05 05:35:00', '12455.00', 1, 0, 0, NULL, '2021-05-17 11:52:31', '2021-06-05 05:35:38', NULL),
(8, 24, NULL, NULL, 7, '2021-05-24 04:52:00', '1000.00', 0, 0, 0, NULL, '2021-05-18 08:13:09', '2021-05-24 04:52:54', NULL),
(9, 24, NULL, NULL, 7, '2021-06-07 11:43:00', '610.00', 0, 0, 0, NULL, '2021-05-24 04:53:01', '2021-06-07 11:43:28', NULL),
(10, NULL, 7, 16, 2, '2021-06-05 07:45:00', '362.50', 0, 0, 0, NULL, '2021-06-05 05:35:52', '2021-06-05 07:45:44', NULL),
(11, NULL, 7, 15, 2, '2021-06-22 05:57:00', '7075.50', 0, 0, 0, NULL, '2021-06-05 12:36:54', '2021-06-22 05:57:57', NULL),
(12, 24, NULL, NULL, 7, NULL, '0.00', NULL, NULL, 1, NULL, '2021-06-07 11:43:34', '2021-06-07 11:43:34', NULL),
(13, 24, NULL, NULL, 10, '2021-06-09 10:34:00', '1000.00', 0, 0, 0, NULL, '2021-06-09 10:32:29', '2021-06-09 10:34:55', NULL),
(14, 24, NULL, NULL, 10, NULL, '0.00', NULL, NULL, 1, NULL, '2021-06-09 10:35:09', '2021-06-09 10:35:09', NULL),
(15, NULL, 7, NULL, 2, '2021-06-22 06:19:00', '100.00', 0, 0, 0, NULL, '2021-06-22 06:17:32', '2021-06-22 06:19:04', NULL),
(16, NULL, 7, 15, 2, '2021-06-22 06:26:00', '0.00', 0, 0, 0, NULL, '2021-06-22 06:25:55', '2021-06-22 06:26:51', NULL),
(17, NULL, 7, 15, 2, '2021-06-22 06:26:00', '100.00', 0, 0, 0, NULL, '2021-06-22 06:26:43', '2021-06-22 06:26:56', 2),
(18, NULL, 7, NULL, 2, '2021-06-22 06:54:00', '0.00', 0, 0, 0, NULL, '2021-06-22 06:27:04', '2021-06-22 06:54:07', 2);

-- --------------------------------------------------------

--
-- Table structure for table `cash_register_transactions`
--

CREATE TABLE `cash_register_transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `cash_register_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sale_id` bigint(20) UNSIGNED DEFAULT NULL,
  `cash_type` tinyint(4) NOT NULL DEFAULT 2 COMMENT '1=debit;2=credit',
  `transaction_type` tinyint(4) NOT NULL DEFAULT 2 COMMENT '1=initial;2=sale',
  `amount` decimal(22,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cash_register_transactions`
--

INSERT INTO `cash_register_transactions` (`id`, `cash_register_id`, `sale_id`, `cash_type`, `transaction_type`, `amount`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 2, 1, '100.00', '2021-05-10 11:36:56', '2021-05-10 11:36:56'),
(2, 1, 145, 2, 2, NULL, '2021-05-10 13:06:22', '2021-05-10 13:06:22'),
(3, 2, NULL, 2, 1, '0.00', '2021-05-10 13:33:55', '2021-05-10 13:33:55'),
(4, 3, NULL, 2, 1, '200.00', '2021-05-10 13:48:33', '2021-05-10 13:48:33'),
(5, 4, NULL, 2, 1, '100.00', '2021-05-10 13:53:19', '2021-05-10 13:53:19'),
(6, 5, NULL, 2, 1, '100.00', '2021-05-11 03:21:08', '2021-05-11 03:21:08'),
(7, 5, 146, 2, 2, NULL, '2021-05-11 03:25:55', '2021-05-11 03:25:55'),
(8, 5, 147, 2, 2, NULL, '2021-05-11 03:30:38', '2021-05-11 03:30:38'),
(9, 6, NULL, 2, 1, '100.00', '2021-05-11 03:42:03', '2021-05-11 03:42:03'),
(10, 6, 150, 2, 2, NULL, '2021-05-11 04:07:10', '2021-05-11 04:07:10'),
(11, 6, 151, 2, 2, NULL, '2021-05-11 04:15:35', '2021-05-11 04:15:35'),
(12, 6, 152, 2, 2, NULL, '2021-05-11 04:18:53', '2021-05-11 04:18:53'),
(13, 6, 153, 2, 2, NULL, '2021-05-11 08:42:07', '2021-05-11 08:42:07'),
(15, 7, NULL, 2, 1, '1000.00', '2021-05-17 11:52:31', '2021-05-17 11:52:31'),
(17, 7, 156, 2, 2, NULL, '2021-05-18 07:23:29', '2021-05-18 07:23:29'),
(18, 7, 157, 2, 2, NULL, '2021-05-18 07:26:26', '2021-05-18 07:26:26'),
(19, 7, 158, 2, 2, NULL, '2021-05-18 07:32:40', '2021-05-18 07:32:40'),
(20, 8, NULL, 2, 1, '1000.00', '2021-05-18 08:13:09', '2021-05-18 08:13:09'),
(21, 7, 159, 2, 2, NULL, '2021-05-18 09:28:34', '2021-05-18 09:28:34'),
(22, 7, 160, 2, 2, NULL, '2021-05-18 13:23:04', '2021-05-18 13:23:04'),
(23, 7, 161, 2, 2, NULL, '2021-05-18 13:30:41', '2021-05-18 13:30:41'),
(28, 7, 166, 2, 2, NULL, '2021-05-19 05:02:33', '2021-05-19 05:02:33'),
(29, 7, 172, 2, 2, NULL, '2021-05-22 05:17:06', '2021-05-22 05:17:06'),
(30, 7, 173, 2, 2, NULL, '2021-05-23 12:21:53', '2021-05-23 12:21:53'),
(31, 9, NULL, 2, 1, '100.00', '2021-05-24 04:53:01', '2021-05-24 04:53:01'),
(32, 7, 176, 2, 2, NULL, '2021-06-01 13:24:46', '2021-06-01 13:24:46'),
(41, 7, 189, 2, 2, NULL, '2021-06-03 05:16:40', '2021-06-03 05:16:40'),
(43, 7, 191, 2, 2, NULL, '2021-06-03 07:51:44', '2021-06-03 07:51:44'),
(44, 7, 192, 2, 2, NULL, '2021-06-03 11:09:35', '2021-06-03 11:09:35'),
(45, 7, 193, 2, 2, NULL, '2021-06-03 12:36:12', '2021-06-03 12:36:12'),
(46, 7, 194, 2, 2, NULL, '2021-06-03 12:57:26', '2021-06-03 12:57:26'),
(47, 7, 195, 2, 2, NULL, '2021-06-03 13:11:16', '2021-06-03 13:11:16'),
(48, 7, 196, 2, 2, NULL, '2021-06-03 13:21:23', '2021-06-03 13:21:23'),
(49, 7, 197, 2, 2, NULL, '2021-06-03 13:24:06', '2021-06-03 13:24:06'),
(50, 7, 198, 2, 2, NULL, '2021-06-03 13:25:26', '2021-06-03 13:25:26'),
(51, 7, 199, 2, 2, NULL, '2021-06-03 13:28:35', '2021-06-03 13:28:35'),
(52, 7, 200, 2, 2, NULL, '2021-06-03 13:31:57', '2021-06-03 13:31:57'),
(53, 10, NULL, 2, 1, '100.00', '2021-06-05 05:35:52', '2021-06-05 05:35:52'),
(54, 10, 201, 2, 2, NULL, '2021-06-05 05:36:29', '2021-06-05 05:36:29'),
(55, 11, NULL, 2, 1, '100.00', '2021-06-05 12:36:54', '2021-06-05 12:36:54'),
(56, 11, 206, 2, 2, NULL, '2021-06-06 11:22:47', '2021-06-06 11:22:47'),
(57, 9, 207, 2, 2, NULL, '2021-06-07 11:40:46', '2021-06-07 11:40:46'),
(58, 9, 208, 2, 2, NULL, '2021-06-07 11:41:46', '2021-06-07 11:41:46'),
(59, 12, NULL, 2, 1, '100.00', '2021-06-07 11:43:34', '2021-06-07 11:43:34'),
(60, 11, 209, 2, 2, NULL, '2021-06-08 10:10:55', '2021-06-08 10:10:55'),
(61, 11, 210, 2, 2, NULL, '2021-06-08 10:18:13', '2021-06-08 10:18:13'),
(62, 11, 211, 2, 2, NULL, '2021-06-08 10:27:11', '2021-06-08 10:27:11'),
(63, 11, 212, 2, 2, NULL, '2021-06-08 10:27:24', '2021-06-08 10:27:24'),
(64, 11, 213, 2, 2, NULL, '2021-06-08 10:28:23', '2021-06-08 10:28:23'),
(65, 11, 214, 2, 2, NULL, '2021-06-08 10:31:36', '2021-06-08 10:31:36'),
(66, 11, 215, 2, 2, NULL, '2021-06-08 10:31:46', '2021-06-08 10:31:46'),
(67, 11, 216, 2, 2, NULL, '2021-06-08 10:32:26', '2021-06-08 10:32:26'),
(68, 11, 217, 2, 2, NULL, '2021-06-08 10:34:57', '2021-06-08 10:34:57'),
(69, 11, 218, 2, 2, NULL, '2021-06-08 10:35:05', '2021-06-08 10:35:05'),
(70, 11, 219, 2, 2, NULL, '2021-06-08 10:35:48', '2021-06-08 10:35:48'),
(71, 11, 220, 2, 2, NULL, '2021-06-08 10:36:05', '2021-06-08 10:36:05'),
(72, 11, 221, 2, 2, NULL, '2021-06-08 10:36:59', '2021-06-08 10:36:59'),
(73, 11, 222, 2, 2, NULL, '2021-06-08 10:39:17', '2021-06-08 10:39:17'),
(74, 11, 223, 2, 2, NULL, '2021-06-08 10:55:39', '2021-06-08 10:55:39'),
(75, 11, 224, 2, 2, NULL, '2021-06-08 11:08:25', '2021-06-08 11:08:25'),
(76, 11, 225, 2, 2, NULL, '2021-06-08 11:09:27', '2021-06-08 11:09:27'),
(77, 11, 238, 2, 2, NULL, '2021-06-08 13:18:17', '2021-06-08 13:18:17'),
(78, 11, 240, 2, 2, NULL, '2021-06-09 08:56:54', '2021-06-09 08:56:54'),
(79, 13, NULL, 2, 1, '1000.00', '2021-06-09 10:32:29', '2021-06-09 10:32:29'),
(80, 14, NULL, 2, 1, '100.00', '2021-06-09 10:35:10', '2021-06-09 10:35:10'),
(82, 14, 242, 2, 2, NULL, '2021-06-09 10:51:38', '2021-06-09 10:51:38'),
(83, 14, 243, 2, 2, NULL, '2021-06-09 10:53:57', '2021-06-09 10:53:57'),
(84, 14, 244, 2, 2, NULL, '2021-06-09 11:04:20', '2021-06-09 11:04:20'),
(85, 14, 245, 2, 2, NULL, '2021-06-09 11:07:10', '2021-06-09 11:07:10'),
(86, 11, 256, 2, 2, NULL, '2021-06-10 11:10:04', '2021-06-10 11:10:04'),
(87, 12, 257, 2, 2, NULL, '2021-06-10 11:10:59', '2021-06-10 11:10:59'),
(88, 11, 266, 2, 2, NULL, '2021-06-17 10:44:49', '2021-06-17 10:44:49'),
(89, 15, NULL, 2, 1, '100.00', '2021-06-22 06:17:32', '2021-06-22 06:17:32'),
(90, 17, NULL, 2, 1, '100.00', '2021-06-22 06:26:43', '2021-06-22 06:26:43'),
(91, 18, NULL, 2, 1, '0.00', '2021-06-22 06:27:04', '2021-06-22 06:27:04');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent_category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'default.png',
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `parent_category_id`, `photo`, `status`, `created_at`, `updated_at`) VALUES
(31, 'Food', NULL, 'default.png', 1, '2021-03-07 05:49:51', '2021-03-07 05:49:51'),
(34, 'Laptop', NULL, 'default.png', 1, '2021-03-08 11:05:39', '2021-05-06 03:38:11'),
(35, 'Baby Items', NULL, 'default.png', 1, NULL, '2021-05-06 03:37:50'),
(38, 'Service', NULL, 'default.png', 1, NULL, '2021-05-06 03:35:47'),
(39, 'Matarial', NULL, 'default.png', 1, NULL, '2021-05-06 03:35:03'),
(41, 'Garments', NULL, '609363d78e259.jpg', 1, NULL, '2021-05-06 03:34:47'),
(46, 'EM', 47, '60bb686bddc76.png', 1, NULL, '2021-06-17 07:49:24'),
(47, 'Electronic', NULL, '6092360456595.jpg', 1, '2021-04-28 05:18:58', '2021-05-05 06:07:00'),
(48, 'Sam', NULL, 'default.png', 1, NULL, NULL),
(50, 'Sam-2', NULL, 'default.png', 1, NULL, NULL),
(51, 'Mobile Phone', 47, 'default.png', 1, NULL, NULL),
(52, 'Television', 47, 'default.png', 1, NULL, NULL),
(53, 'Air conditioner', 47, 'default.png', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `combo_products`
--

CREATE TABLE `combo_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `combo_product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `quantity` decimal(22,2) DEFAULT 0.00,
  `product_variant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `delete_in_update` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `currencies`
--

CREATE TABLE `currencies` (
  `id` int(10) UNSIGNED NOT NULL,
  `country` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  `symbol` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  `thousand_separator` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `decimal_separator` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `currencies`
--

INSERT INTO `currencies` (`id`, `country`, `currency`, `code`, `symbol`, `thousand_separator`, `decimal_separator`, `created_at`, `updated_at`) VALUES
(1, 'Albania', 'Leke', 'ALL', 'Lek', ',', '.', NULL, NULL),
(2, 'America', 'Dollars', 'USD', '$', ',', '.', NULL, NULL),
(3, 'Afghanistan', 'Afghanis', 'AF', '؋', ',', '.', NULL, NULL),
(4, 'Argentina', 'Pesos', 'ARS', '$', ',', '.', NULL, NULL),
(5, 'Aruba', 'Guilders', 'AWG', 'ƒ', ',', '.', NULL, NULL),
(6, 'Australia', 'Dollars', 'AUD', '$', ',', '.', NULL, NULL),
(7, 'Azerbaijan', 'New Manats', 'AZ', 'ман', ',', '.', NULL, NULL),
(8, 'Bahamas', 'Dollars', 'BSD', '$', ',', '.', NULL, NULL),
(9, 'Barbados', 'Dollars', 'BBD', '$', ',', '.', NULL, NULL),
(10, 'Belarus', 'Rubles', 'BYR', 'p.', ',', '.', NULL, NULL),
(11, 'Belgium', 'Euro', 'EUR', '€', ',', '.', NULL, NULL),
(12, 'Beliz', 'Dollars', 'BZD', 'BZ$', ',', '.', NULL, NULL),
(13, 'Bermuda', 'Dollars', 'BMD', '$', ',', '.', NULL, NULL),
(14, 'Bolivia', 'Bolivianos', 'BOB', '$b', ',', '.', NULL, NULL),
(15, 'Bosnia and Herzegovina', 'Convertible Marka', 'BAM', 'KM', ',', '.', NULL, NULL),
(16, 'Botswana', 'Pula\'s', 'BWP', 'P', ',', '.', NULL, NULL),
(17, 'Bulgaria', 'Leva', 'BG', 'лв', ',', '.', NULL, NULL),
(18, 'Brazil', 'Reais', 'BRL', 'R$', ',', '.', NULL, NULL),
(19, 'Britain [United Kingdom]', 'Pounds', 'GBP', '£', ',', '.', NULL, NULL),
(20, 'Brunei Darussalam', 'Dollars', 'BND', '$', ',', '.', NULL, NULL),
(21, 'Cambodia', 'Riels', 'KHR', '៛', ',', '.', NULL, NULL),
(22, 'Canada', 'Dollars', 'CAD', '$', ',', '.', NULL, NULL),
(23, 'Cayman Islands', 'Dollars', 'KYD', '$', ',', '.', NULL, NULL),
(24, 'Chile', 'Pesos', 'CLP', '$', ',', '.', NULL, NULL),
(25, 'China', 'Yuan Renminbi', 'CNY', '¥', ',', '.', NULL, NULL),
(26, 'Colombia', 'Pesos', 'COP', '$', ',', '.', NULL, NULL),
(27, 'Costa Rica', 'Colón', 'CRC', '₡', ',', '.', NULL, NULL),
(28, 'Croatia', 'Kuna', 'HRK', 'kn', ',', '.', NULL, NULL),
(29, 'Cuba', 'Pesos', 'CUP', '₱', ',', '.', NULL, NULL),
(30, 'Cyprus', 'Euro', 'EUR', '€', '.', ',', NULL, NULL),
(31, 'Czech Republic', 'Koruny', 'CZK', 'Kč', ',', '.', NULL, NULL),
(32, 'Denmark', 'Kroner', 'DKK', 'kr', ',', '.', NULL, NULL),
(33, 'Dominican Republic', 'Pesos', 'DOP ', 'RD$', ',', '.', NULL, NULL),
(34, 'East Caribbean', 'Dollars', 'XCD', '$', ',', '.', NULL, NULL),
(35, 'Egypt', 'Pounds', 'EGP', '£', ',', '.', NULL, NULL),
(36, 'El Salvador', 'Colones', 'SVC', '$', ',', '.', NULL, NULL),
(37, 'England [United Kingdom]', 'Pounds', 'GBP', '£', ',', '.', NULL, NULL),
(38, 'Euro', 'Euro', 'EUR', '€', '.', ',', NULL, NULL),
(39, 'Falkland Islands', 'Pounds', 'FKP', '£', ',', '.', NULL, NULL),
(40, 'Fiji', 'Dollars', 'FJD', '$', ',', '.', NULL, NULL),
(41, 'France', 'Euro', 'EUR', '€', '.', ',', NULL, NULL),
(42, 'Ghana', 'Cedis', 'GHC', '¢', ',', '.', NULL, NULL),
(43, 'Gibraltar', 'Pounds', 'GIP', '£', ',', '.', NULL, NULL),
(44, 'Greece', 'Euro', 'EUR', '€', '.', ',', NULL, NULL),
(45, 'Guatemala', 'Quetzales', 'GTQ', 'Q', ',', '.', NULL, NULL),
(46, 'Guernsey', 'Pounds', 'GGP', '£', ',', '.', NULL, NULL),
(47, 'Guyana', 'Dollars', 'GYD', '$', ',', '.', NULL, NULL),
(48, 'Holland [Netherlands]', 'Euro', 'EUR', '€', '.', ',', NULL, NULL),
(49, 'Honduras', 'Lempiras', 'HNL', 'L', ',', '.', NULL, NULL),
(50, 'Hong Kong', 'Dollars', 'HKD', '$', ',', '.', NULL, NULL),
(51, 'Hungary', 'Forint', 'HUF', 'Ft', ',', '.', NULL, NULL),
(52, 'Iceland', 'Kronur', 'ISK', 'kr', ',', '.', NULL, NULL),
(53, 'India', 'Rupees', 'INR', '₹', ',', '.', NULL, NULL),
(54, 'Indonesia', 'Rupiahs', 'IDR', 'Rp', ',', '.', NULL, NULL),
(55, 'Iran', 'Rials', 'IRR', '﷼', ',', '.', NULL, NULL),
(56, 'Ireland', 'Euro', 'EUR', '€', '.', ',', NULL, NULL),
(57, 'Isle of Man', 'Pounds', 'IMP', '£', ',', '.', NULL, NULL),
(58, 'Israel', 'New Shekels', 'ILS', '₪', ',', '.', NULL, NULL),
(59, 'Italy', 'Euro', 'EUR', '€', '.', ',', NULL, NULL),
(60, 'Jamaica', 'Dollars', 'JMD', 'J$', ',', '.', NULL, NULL),
(61, 'Japan', 'Yen', 'JPY', '¥', ',', '.', NULL, NULL),
(62, 'Jersey', 'Pounds', 'JEP', '£', ',', '.', NULL, NULL),
(63, 'Kazakhstan', 'Tenge', 'KZT', 'лв', ',', '.', NULL, NULL),
(64, 'Korea [North]', 'Won', 'KPW', '₩', ',', '.', NULL, NULL),
(65, 'Korea [South]', 'Won', 'KRW', '₩', ',', '.', NULL, NULL),
(66, 'Kyrgyzstan', 'Soms', 'KGS', 'лв', ',', '.', NULL, NULL),
(67, 'Laos', 'Kips', 'LAK', '₭', ',', '.', NULL, NULL),
(68, 'Latvia', 'Lati', 'LVL', 'Ls', ',', '.', NULL, NULL),
(69, 'Lebanon', 'Pounds', 'LBP', '£', ',', '.', NULL, NULL),
(70, 'Liberia', 'Dollars', 'LRD', '$', ',', '.', NULL, NULL),
(71, 'Liechtenstein', 'Switzerland Francs', 'CHF', 'CHF', ',', '.', NULL, NULL),
(72, 'Lithuania', 'Litai', 'LTL', 'Lt', ',', '.', NULL, NULL),
(73, 'Luxembourg', 'Euro', 'EUR', '€', '.', ',', NULL, NULL),
(74, 'Macedonia', 'Denars', 'MKD', 'ден', ',', '.', NULL, NULL),
(75, 'Malaysia', 'Ringgits', 'MYR', 'RM', ',', '.', NULL, NULL),
(76, 'Malta', 'Euro', 'EUR', '€', '.', ',', NULL, NULL),
(77, 'Mauritius', 'Rupees', 'MUR', '₨', ',', '.', NULL, NULL),
(78, 'Mexico', 'Pesos', 'MXN', '$', ',', '.', NULL, NULL),
(79, 'Mongolia', 'Tugriks', 'MNT', '₮', ',', '.', NULL, NULL),
(80, 'Mozambique', 'Meticais', 'MZ', 'MT', ',', '.', NULL, NULL),
(81, 'Namibia', 'Dollars', 'NAD', '$', ',', '.', NULL, NULL),
(82, 'Nepal', 'Rupees', 'NPR', '₨', ',', '.', NULL, NULL),
(83, 'Netherlands Antilles', 'Guilders', 'ANG', 'ƒ', ',', '.', NULL, NULL),
(84, 'Netherlands', 'Euro', 'EUR', '€', '.', ',', NULL, NULL),
(85, 'New Zealand', 'Dollars', 'NZD', '$', ',', '.', NULL, NULL),
(86, 'Nicaragua', 'Cordobas', 'NIO', 'C$', ',', '.', NULL, NULL),
(87, 'Nigeria', 'Nairas', 'NG', '₦', ',', '.', NULL, NULL),
(88, 'North Korea', 'Won', 'KPW', '₩', ',', '.', NULL, NULL),
(89, 'Norway', 'Krone', 'NOK', 'kr', ',', '.', NULL, NULL),
(90, 'Oman', 'Rials', 'OMR', '﷼', ',', '.', NULL, NULL),
(91, 'Pakistan', 'Rupees', 'PKR', '₨', ',', '.', NULL, NULL),
(92, 'Panama', 'Balboa', 'PAB', 'B/.', ',', '.', NULL, NULL),
(93, 'Paraguay', 'Guarani', 'PYG', 'Gs', ',', '.', NULL, NULL),
(94, 'Peru', 'Nuevos Soles', 'PE', 'S/.', ',', '.', NULL, NULL),
(95, 'Philippines', 'Pesos', 'PHP', 'Php', ',', '.', NULL, NULL),
(96, 'Poland', 'Zlotych', 'PL', 'zł', ',', '.', NULL, NULL),
(97, 'Qatar', 'Rials', 'QAR', '﷼', ',', '.', NULL, NULL),
(98, 'Romania', 'New Lei', 'RO', 'lei', ',', '.', NULL, NULL),
(99, 'Russia', 'Rubles', 'RUB', 'руб', ',', '.', NULL, NULL),
(100, 'Saint Helena', 'Pounds', 'SHP', '£', ',', '.', NULL, NULL),
(101, 'Saudi Arabia', 'Riyals', 'SAR', '﷼', ',', '.', NULL, NULL),
(102, 'Serbia', 'Dinars', 'RSD', 'Дин.', ',', '.', NULL, NULL),
(103, 'Seychelles', 'Rupees', 'SCR', '₨', ',', '.', NULL, NULL),
(104, 'Singapore', 'Dollars', 'SGD', '$', ',', '.', NULL, NULL),
(105, 'Slovenia', 'Euro', 'EUR', '€', '.', ',', NULL, NULL),
(106, 'Solomon Islands', 'Dollars', 'SBD', '$', ',', '.', NULL, NULL),
(107, 'Somalia', 'Shillings', 'SOS', 'S', ',', '.', NULL, NULL),
(108, 'South Africa', 'Rand', 'ZAR', 'R', ',', '.', NULL, NULL),
(109, 'South Korea', 'Won', 'KRW', '₩', ',', '.', NULL, NULL),
(110, 'Spain', 'Euro', 'EUR', '€', '.', ',', NULL, NULL),
(111, 'Sri Lanka', 'Rupees', 'LKR', '₨', ',', '.', NULL, NULL),
(112, 'Sweden', 'Kronor', 'SEK', 'kr', ',', '.', NULL, NULL),
(113, 'Switzerland', 'Francs', 'CHF', 'CHF', ',', '.', NULL, NULL),
(114, 'Suriname', 'Dollars', 'SRD', '$', ',', '.', NULL, NULL),
(115, 'Syria', 'Pounds', 'SYP', '£', ',', '.', NULL, NULL),
(116, 'Taiwan', 'New Dollars', 'TWD', 'NT$', ',', '.', NULL, NULL),
(117, 'Thailand', 'Baht', 'THB', '฿', ',', '.', NULL, NULL),
(118, 'Trinidad and Tobago', 'Dollars', 'TTD', 'TT$', ',', '.', NULL, NULL),
(119, 'Turkey', 'Lira', 'TRY', 'TL', ',', '.', NULL, NULL),
(120, 'Turkey', 'Liras', 'TRL', '£', ',', '.', NULL, NULL),
(121, 'Tuvalu', 'Dollars', 'TVD', '$', ',', '.', NULL, NULL),
(122, 'Ukraine', 'Hryvnia', 'UAH', '₴', ',', '.', NULL, NULL),
(123, 'United Kingdom', 'Pounds', 'GBP', '£', ',', '.', NULL, NULL),
(124, 'United States of America', 'Dollars', 'USD', '$', ',', '.', NULL, NULL),
(125, 'Uruguay', 'Pesos', 'UYU', '$U', ',', '.', NULL, NULL),
(126, 'Uzbekistan', 'Sums', 'UZS', 'лв', ',', '.', NULL, NULL),
(127, 'Vatican City', 'Euro', 'EUR', '€', '.', ',', NULL, NULL),
(128, 'Venezuela', 'Bolivares Fuertes', 'VEF', 'Bs', ',', '.', NULL, NULL),
(129, 'Vietnam', 'Dong', 'VND', '₫', ',', '.', NULL, NULL),
(130, 'Yemen', 'Rials', 'YER', '﷼', ',', '.', NULL, NULL),
(131, 'Zimbabwe', 'Zimbabwe Dollars', 'ZWD', 'Z$', ',', '.', NULL, NULL),
(132, 'Iraq', 'Iraqi dinar', 'IQD', 'د.ع', ',', '.', NULL, NULL),
(133, 'Kenya', 'Kenyan shilling', 'KES', 'KSh', ',', '.', NULL, NULL),
(134, 'Bangladesh', 'Taka', 'BDT', '৳', ',', '.', NULL, NULL),
(135, 'Algerie', 'Algerian dinar', 'DZD', 'د.ج', ' ', '.', NULL, NULL),
(136, 'United Arab Emirates', 'United Arab Emirates dirham', 'AED', 'د.إ', ',', '.', NULL, NULL),
(137, 'Uganda', 'Uganda shillings', 'UGX', 'USh', ',', '.', NULL, NULL),
(138, 'Tanzania', 'Tanzanian shilling', 'TZS', 'TSh', ',', '.', NULL, NULL),
(139, 'Angola', 'Kwanza', 'AOA', 'Kz', ',', '.', NULL, NULL),
(140, 'Kuwait', 'Kuwaiti dinar', 'KWD', 'KD', ',', '.', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` tinyint(4) DEFAULT NULL COMMENT '1=customer,2=supplier,3=both',
  `contact_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_group_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `business_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alternative_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `landline` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_of_birth` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tax_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `opening_balance` decimal(22,2) NOT NULL DEFAULT 0.00,
  `pay_term` tinyint(4) DEFAULT NULL COMMENT '1=months,2=days',
  `pay_term_number` int(11) DEFAULT NULL,
  `address` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_address` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zip_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_sale` decimal(22,2) NOT NULL DEFAULT 0.00,
  `total_paid` decimal(22,2) NOT NULL DEFAULT 0.00,
  `total_sale_due` decimal(22,2) NOT NULL DEFAULT 0.00,
  `total_sale_return_due` decimal(22,2) NOT NULL DEFAULT 0.00,
  `point` decimal(22,2) NOT NULL DEFAULT 0.00,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `is_walk_in_customer` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `type`, `contact_id`, `customer_group_id`, `name`, `business_name`, `phone`, `alternative_phone`, `landline`, `email`, `date_of_birth`, `tax_number`, `opening_balance`, `pay_term`, `pay_term_number`, `address`, `shipping_address`, `city`, `state`, `country`, `zip_code`, `total_sale`, `total_paid`, `total_sale_due`, `total_sale_return_due`, `point`, `status`, `is_walk_in_customer`, `created_at`, `updated_at`) VALUES
(39, 2, 'CT788', 4, 'John Miller', 'X Enterprice', '015879966655', '015879966655', '015879966655', NULL, '2021-04-04', '4455887', '0.00', 1, NULL, 'Dhaka, Bangladesh', 'Dhaka,Bangladesh', NULL, NULL, NULL, NULL, '151427.50', '-108080.25', '-38706.75', '0.00', '0.00', 1, 0, '2021-04-04 07:21:07', '2021-06-03 05:13:59'),
(40, NULL, NULL, NULL, 'Trip', NULL, '01856742357', '01856742357', '01856742357', NULL, NULL, NULL, '0.00', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2057.50', '1042.50', '0.00', '0.00', '0.00', 1, 0, '2021-04-17 03:33:36', '2021-06-09 09:24:42'),
(41, 2, 'CO75555', 4, 'Mr. Bill', 'X Company', '01853247521', '01853247521', '01853247521', NULL, NULL, NULL, '200.00', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '5013.00', '6273.62', '-742.50', '221.25', '0.00', 1, 0, '2021-04-17 04:05:36', '2021-06-10 06:37:39'),
(42, NULL, NULL, NULL, 'Jarif', NULL, '0158745212365', '0158745212365', '0158745212365', NULL, NULL, NULL, '500.00', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1972.50', '2472.50', '0.00', '0.00', '0.00', 1, 0, '2021-04-17 04:08:20', '2021-06-03 12:14:38'),
(43, 2, 'CO75558', NULL, 'Jefferson', NULL, '01856324756', '01856324756', '01856324756', NULL, NULL, NULL, '700.00', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '8846.25', '7866.25', '1680.00', '0.00', '0.00', 1, 0, '2021-04-17 04:10:35', '2021-06-03 12:33:28'),
(44, NULL, NULL, NULL, 'Nikson', NULL, '01255785544', '01255785544', '01255785544', NULL, NULL, NULL, '100.00', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2947.50', '3153.75', '-6.25', '0.00', '0.00', 1, 0, '2021-04-17 04:12:25', '2021-06-03 12:49:39'),
(45, 2, NULL, 4, 'Miller', NULL, '7555885232', '7555885232', '7555885232', NULL, NULL, NULL, '100.00', 1, NULL, NULL, 'Dhaka,Bangladesh', NULL, NULL, NULL, NULL, '190448.75', '7242.50', '-325.00', '1765.00', '0.00', 1, 0, '2021-04-17 04:13:32', '2021-06-10 06:37:50'),
(46, NULL, NULL, NULL, 'Test', NULL, '122345', '122345', '122345', NULL, NULL, NULL, '550.00', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1056.00', '1987.00', '-331.00', '0.00', '0.00', 1, 0, '2021-04-19 05:24:35', '2021-06-09 09:25:11'),
(47, NULL, NULL, NULL, 'Danial D.Flaung', 'X COMPANY', '01254788665', '01254788665', '01254788665', NULL, NULL, NULL, '0.00', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1781.85', '1781.85', '0.00', '0.00', '0.00', 1, 0, '2021-04-26 09:19:23', '2021-04-27 06:11:40'),
(48, 2, NULL, NULL, 'ASBRM', NULL, '01312644409', '01312644409', '01312644409', NULL, NULL, NULL, '0.00', 1, NULL, 'Barpa, Rupshi, Rupganj, Naraynganj, Bangladesh-1460', NULL, NULL, NULL, NULL, NULL, '94250.00', '94250.00', '0.00', '0.00', '0.00', 1, 0, '2021-04-28 08:43:03', '2021-06-09 09:25:35'),
(49, NULL, NULL, NULL, 'RT', NULL, '122588', '122588', '122588', NULL, NULL, NULL, '0.00', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '425.00', '425.00', '0.00', '0.00', '0.00', 1, 0, '2021-04-28 12:26:07', '2021-06-09 09:27:00'),
(50, NULL, NULL, NULL, 'MT', NULL, '12558577', '12558577', '12558577', NULL, NULL, NULL, '0.00', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 1, 0, '2021-04-28 12:27:36', '2021-04-28 12:27:36'),
(51, 2, NULL, NULL, 'hm', NULL, '147474', '147474', '147474', NULL, NULL, NULL, '0.00', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2817.50', '2817.50', '0.00', '0.00', '0.00', 1, 0, '2021-04-28 12:28:32', '2021-06-10 06:37:28'),
(52, NULL, NULL, NULL, 'MT', NULL, '6574578878', '6574578878', '6574578878', NULL, NULL, NULL, '0.00', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1023.75', '1023.75', '0.00', '0.00', '0.00', 1, 0, '2021-04-28 12:30:47', '2021-05-19 10:08:09'),
(53, NULL, NULL, NULL, 'Kayhel', NULL, '75558888', '75558888', '75558888', NULL, NULL, NULL, '2000.00', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '387.50', '2387.50', '0.00', '0.00', '0.00', 1, 0, '2021-04-28 12:31:11', '2021-05-06 09:56:56'),
(54, NULL, NULL, NULL, 'ou', NULL, '1000', '1000', '1000', NULL, NULL, NULL, '1000.00', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '8311.39', '16173.89', '0.00', '0.00', '0.00', 1, 0, '2021-05-02 10:27:50', '2021-06-09 08:56:54'),
(55, 2, NULL, NULL, 'Mr.Billarin', 'X Business', '01745865214', '01745865214', '01745865214', NULL, NULL, NULL, '2000.00', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2316.56', '0.00', '0.00', '0.00', '0.00', 1, 0, '2021-05-09 04:02:53', '2021-06-07 09:41:46'),
(56, 3, 'A temporibus quod ei', 5, 'Janna Huber', 'Kylan Alvarado', '+1 (942) 854-1783', '+1 (942) 854-1783', '+1 (942) 854-1783', 'xygava@mailinator.com', '09-Jul-1976', '920', '86.00', 3, 675, 'Quo fuga Eu soluta', 'Aliqua Quam aut vel', 'Aut lorem tempore a', 'Sunt proident exer', 'Consequatur Mollit', '64557', '2887.50', '4400.00', '0.00', '0.00', '0.00', 1, 0, '2021-06-03 12:21:39', '2021-06-08 11:46:24'),
(57, 2, 'Sit laborum Est vol', 4, 'Joel Mcguire', 'Hilel Chan', '+1 (531) 725-2663', '+1 (531) 725-2663', '+1 (531) 725-2663', 'vyqalepaj@mailinator.com', '11-Nov-1990', '398', '88.00', 2, 259, 'Obcaecati dolore eli', 'Atque nulla id labo', 'Cupiditate iure ipsu', 'Officiis et fugit v', 'Incidunt sit volupt', '17477', '0.00', '0.00', '88.00', '0.00', '0.00', 1, 0, '2021-06-05 05:04:04', '2021-06-05 05:04:04'),
(58, 3, 'In sint minima est', 5, 'Lawrence Atkinson', 'Steven Carney', '+1 (869) 203-1139', '+1 (869) 203-1139', '+1 (869) 203-1139', 'lydohaju@mailinator.com', '30-Oct-1986', '904', '21.00', 3, 960, 'Veniam voluptas rep', 'Vero explicabo In e', 'Architecto voluptate', 'Quos ipsum excepturi', 'Sit duis dolor quam', '36148', '0.00', '21.00', '0.00', '0.00', '0.00', 1, 0, '2021-06-05 12:33:24', '2021-06-09 09:27:15'),
(61, NULL, NULL, NULL, 'Due customer', NULL, '12255447', '12255447', '12255447', NULL, NULL, NULL, '0.00', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '151462.50', '101462.50', '50000.00', '0.00', '0.00', 1, 0, '2021-06-10 06:02:01', '2021-06-17 10:44:49');

-- --------------------------------------------------------

--
-- Table structure for table `customer_groups`
--

CREATE TABLE `customer_groups` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `group_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `calc_percentage` decimal(22,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customer_groups`
--

INSERT INTO `customer_groups` (`id`, `group_name`, `calc_percentage`, `created_at`, `updated_at`) VALUES
(4, 'Premium Customer', '10.00', NULL, NULL),
(5, 'M Group', '15.00', NULL, '2021-04-21 10:45:53'),
(6, 'Silver Group', '70.00', NULL, '2021-04-21 10:19:49');

-- --------------------------------------------------------

--
-- Table structure for table `customer_ledgers`
--

CREATE TABLE `customer_ledgers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sale_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sale_payment_id` bigint(20) UNSIGNED DEFAULT NULL,
  `money_receipt_id` bigint(20) UNSIGNED DEFAULT NULL,
  `row_type` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1=sale;2=sale_payment;3=opening_balance;3=money_receipt',
  `amount` decimal(22,2) DEFAULT NULL COMMENT 'only_for_opening_balance',
  `is_advanced` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'only_for_money_receipt',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customer_ledgers`
--

INSERT INTO `customer_ledgers` (`id`, `customer_id`, `sale_id`, `sale_payment_id`, `money_receipt_id`, `row_type`, `amount`, `is_advanced`, `created_at`, `updated_at`) VALUES
(2, 39, 14, NULL, NULL, 1, NULL, 0, '2021-04-10 11:15:05', '2021-04-10 11:15:05'),
(6, 39, 18, NULL, NULL, 1, NULL, 0, '2021-04-11 06:20:36', '2021-04-11 06:20:36'),
(7, 39, NULL, 4, NULL, 2, NULL, 0, '2021-04-11 06:20:36', '2021-04-11 06:20:36'),
(10, 39, 20, NULL, NULL, 1, NULL, 0, '2021-04-11 07:15:58', '2021-04-11 07:15:58'),
(11, 39, NULL, 6, NULL, 2, NULL, 0, '2021-04-11 07:15:58', '2021-04-11 07:15:58'),
(12, 39, 21, NULL, NULL, 1, NULL, 0, '2021-04-12 11:51:01', '2021-04-12 11:51:01'),
(13, 39, NULL, 7, NULL, 2, NULL, 0, '2021-04-12 11:51:02', '2021-04-12 11:51:02'),
(14, 39, 22, NULL, NULL, 1, NULL, 0, '2021-04-12 12:52:09', '2021-04-12 12:52:09'),
(15, 39, NULL, 8, NULL, 2, NULL, 0, '2021-04-12 12:52:09', '2021-04-12 12:52:09'),
(16, 39, 23, NULL, NULL, 1, NULL, 0, '2021-04-13 07:01:10', '2021-04-13 07:01:10'),
(17, 40, 24, NULL, NULL, 1, NULL, 0, '2021-04-17 03:47:27', '2021-04-17 03:47:27'),
(18, 40, NULL, 9, NULL, 2, NULL, 0, '2021-04-17 03:47:27', '2021-04-17 03:47:27'),
(19, 41, NULL, NULL, NULL, 3, '200.00', 0, '2021-04-17 04:05:36', '2021-04-17 04:05:36'),
(20, 42, NULL, NULL, NULL, 3, '500.00', 0, '2021-04-17 04:08:20', '2021-04-17 04:08:20'),
(21, 43, NULL, NULL, NULL, 3, '700.00', 0, '2021-04-17 04:10:35', '2021-04-17 04:10:35'),
(22, 44, NULL, NULL, NULL, 3, '100.00', 0, '2021-04-17 04:12:25', '2021-04-17 04:12:25'),
(23, 45, NULL, NULL, NULL, 3, '100.00', 0, '2021-04-17 04:13:32', '2021-04-17 04:13:32'),
(24, 45, 25, NULL, NULL, 1, NULL, 0, '2021-04-17 04:42:48', '2021-04-17 04:42:48'),
(26, 45, NULL, NULL, 35, 4, '400.00', 0, '2021-04-18 03:25:41', '2021-04-18 03:25:41'),
(31, 45, NULL, NULL, 36, 4, '100.00', 1, '2021-04-19 04:18:49', '2021-04-19 04:18:49'),
(32, 42, NULL, NULL, 37, 4, '500.00', 1, '2021-04-19 04:21:08', '2021-04-19 04:21:08'),
(33, 43, NULL, NULL, 38, 4, '700.00', 0, '2021-04-19 04:54:45', '2021-04-19 04:54:45'),
(34, 41, NULL, NULL, 40, 4, '200.00', 0, '2021-04-19 05:07:19', '2021-04-19 05:07:19'),
(35, 39, NULL, 15, NULL, 2, NULL, 0, '2021-04-19 05:10:18', '2021-04-19 05:10:18'),
(37, 39, NULL, NULL, 41, 4, '630.00', 1, '2021-04-19 05:10:18', '2021-04-19 05:10:18'),
(38, 46, NULL, NULL, NULL, 3, '550.00', 0, '2021-04-19 05:24:35', '2021-04-19 05:24:35'),
(39, 46, NULL, NULL, 42, 4, '400.00', 0, '2021-04-19 05:25:22', '2021-04-19 05:25:22'),
(40, 46, NULL, NULL, 43, 4, '50.00', 1, '2021-04-19 05:34:12', '2021-04-19 05:34:12'),
(41, 46, NULL, NULL, 43, 4, '150.00', 0, '2021-04-19 05:34:12', '2021-04-19 05:34:12'),
(42, 40, NULL, NULL, 44, 4, '100.00', 0, '2021-04-21 07:10:33', '2021-04-21 07:10:33'),
(43, 44, 29, NULL, NULL, 1, NULL, 0, '2021-04-25 11:46:06', '2021-04-25 11:46:06'),
(44, 44, NULL, 17, NULL, 2, NULL, 0, '2021-04-25 11:46:06', '2021-04-25 11:46:06'),
(45, 39, NULL, 19, NULL, 2, NULL, 0, '2021-04-25 13:16:33', '2021-04-25 13:16:33'),
(48, 45, NULL, 22, NULL, 2, NULL, 0, '2021-04-25 13:43:29', '2021-04-25 13:43:29'),
(49, 46, 31, NULL, NULL, 1, NULL, 0, '2021-04-26 07:40:51', '2021-04-26 07:40:51'),
(50, 46, NULL, 23, NULL, 2, NULL, 0, '2021-04-26 07:40:51', '2021-04-26 07:40:51'),
(51, 43, 33, NULL, NULL, 1, NULL, 0, '2021-04-26 07:45:32', '2021-04-26 07:45:32'),
(52, 43, NULL, 25, NULL, 2, NULL, 0, '2021-04-26 07:45:32', '2021-04-26 07:45:32'),
(53, 43, 34, NULL, NULL, 1, NULL, 0, '2021-04-26 07:45:39', '2021-04-26 07:45:39'),
(54, 43, NULL, 26, NULL, 2, NULL, 0, '2021-04-26 07:45:40', '2021-04-26 07:45:40'),
(55, 43, 35, NULL, NULL, 1, NULL, 0, '2021-04-26 07:45:40', '2021-04-26 07:45:40'),
(56, 43, NULL, 27, NULL, 2, NULL, 0, '2021-04-26 07:45:40', '2021-04-26 07:45:40'),
(57, 43, 36, NULL, NULL, 1, NULL, 0, '2021-04-26 07:45:41', '2021-04-26 07:45:41'),
(58, 43, NULL, 28, NULL, 2, NULL, 0, '2021-04-26 07:45:41', '2021-04-26 07:45:41'),
(59, 43, 37, NULL, NULL, 1, NULL, 0, '2021-04-26 07:45:46', '2021-04-26 07:45:46'),
(60, 43, NULL, 29, NULL, 2, NULL, 0, '2021-04-26 07:45:46', '2021-04-26 07:45:46'),
(61, 43, 38, NULL, NULL, 1, NULL, 0, '2021-04-26 07:45:48', '2021-04-26 07:45:48'),
(62, 43, NULL, 30, NULL, 2, NULL, 0, '2021-04-26 07:45:48', '2021-04-26 07:45:48'),
(63, 43, 39, NULL, NULL, 1, NULL, 0, '2021-04-26 07:46:34', '2021-04-26 07:46:34'),
(64, 43, NULL, 31, NULL, 2, NULL, 0, '2021-04-26 07:46:34', '2021-04-26 07:46:34'),
(65, 42, 40, NULL, NULL, 1, NULL, 0, '2021-04-26 08:07:38', '2021-04-26 08:07:38'),
(66, 42, NULL, 32, NULL, 2, NULL, 0, '2021-04-26 08:07:38', '2021-04-26 08:07:38'),
(67, 45, 43, NULL, NULL, 1, NULL, 0, '2021-04-26 08:20:34', '2021-04-26 08:20:34'),
(68, 45, NULL, 35, NULL, 2, NULL, 0, '2021-04-26 08:20:34', '2021-04-26 08:20:34'),
(69, 41, 44, NULL, NULL, 1, NULL, 0, '2021-04-26 08:23:00', '2021-04-26 08:23:00'),
(70, 41, NULL, 36, NULL, 2, NULL, 0, '2021-04-26 08:23:00', '2021-04-26 08:23:00'),
(71, 45, 45, NULL, NULL, 1, NULL, 0, '2021-04-26 09:39:31', '2021-04-26 09:39:31'),
(72, 45, NULL, 37, NULL, 2, NULL, 0, '2021-04-26 09:39:31', '2021-04-26 09:39:31'),
(73, 45, NULL, 38, NULL, 2, NULL, 0, '2021-04-26 10:48:17', '2021-04-26 10:48:17'),
(74, 47, 46, NULL, NULL, 1, NULL, 0, '2021-04-27 06:10:37', '2021-04-27 06:10:37'),
(75, 47, NULL, 39, NULL, 2, NULL, 0, '2021-04-27 06:10:37', '2021-04-27 06:10:37'),
(76, 41, 48, NULL, NULL, 1, NULL, 0, '2021-04-28 05:44:22', '2021-04-28 05:44:22'),
(77, 41, NULL, 40, NULL, 2, NULL, 0, '2021-04-28 05:44:22', '2021-04-28 05:44:22'),
(78, 48, 49, NULL, NULL, 1, NULL, 0, '2021-04-28 08:53:06', '2021-04-28 08:53:06'),
(79, 53, NULL, NULL, NULL, 3, '2000.00', 0, '2021-04-28 12:31:11', '2021-04-28 12:31:11'),
(80, 54, NULL, NULL, NULL, 3, '1000.00', 0, '2021-05-02 10:27:50', '2021-05-02 10:27:50'),
(81, 40, 50, NULL, NULL, 1, NULL, 0, '2021-05-02 10:44:58', '2021-05-02 10:44:58'),
(82, 40, NULL, 41, NULL, 2, NULL, 0, '2021-05-02 10:44:58', '2021-05-02 10:44:58'),
(83, 53, 51, NULL, NULL, 1, NULL, 0, '2021-05-04 04:56:24', '2021-05-04 04:56:24'),
(84, 53, NULL, 42, NULL, 2, NULL, 0, '2021-05-04 04:56:25', '2021-05-04 04:56:25'),
(85, 51, 58, NULL, NULL, 1, NULL, 0, '2021-05-04 05:09:15', '2021-05-04 05:09:15'),
(86, 51, NULL, 43, NULL, 2, NULL, 0, '2021-05-04 05:09:15', '2021-05-04 05:09:15'),
(87, 45, 64, NULL, NULL, 1, NULL, 0, '2021-05-05 08:48:37', '2021-05-05 08:48:37'),
(88, 45, 64, NULL, NULL, 1, NULL, 0, '2021-05-05 08:48:38', '2021-05-05 08:48:38'),
(89, 54, 74, NULL, NULL, 1, NULL, 0, '2021-05-08 11:18:04', '2021-05-08 11:18:04'),
(90, 54, 74, NULL, NULL, 1, NULL, 0, '2021-05-08 11:18:04', '2021-05-08 11:18:04'),
(91, 54, 85, NULL, NULL, 1, NULL, 0, '2021-05-08 12:50:50', '2021-05-08 12:50:50'),
(92, 54, 85, NULL, NULL, 1, NULL, 0, '2021-05-08 12:50:50', '2021-05-08 12:50:50'),
(93, 54, 86, NULL, NULL, 1, NULL, 0, '2021-05-08 12:50:58', '2021-05-08 12:50:58'),
(94, 54, 86, NULL, NULL, 1, NULL, 0, '2021-05-08 12:50:58', '2021-05-08 12:50:58'),
(95, 54, 87, NULL, NULL, 1, NULL, 0, '2021-05-08 12:53:18', '2021-05-08 12:53:18'),
(96, 54, 87, NULL, NULL, 1, NULL, 0, '2021-05-08 12:53:18', '2021-05-08 12:53:18'),
(97, 54, 88, NULL, NULL, 1, NULL, 0, '2021-05-08 12:53:33', '2021-05-08 12:53:33'),
(98, 54, 88, NULL, NULL, 1, NULL, 0, '2021-05-08 12:53:33', '2021-05-08 12:53:33'),
(99, 54, 89, NULL, NULL, 1, NULL, 0, '2021-05-08 12:54:44', '2021-05-08 12:54:44'),
(100, 54, 89, NULL, NULL, 1, NULL, 0, '2021-05-08 12:54:44', '2021-05-08 12:54:44'),
(101, 54, 90, NULL, NULL, 1, NULL, 0, '2021-05-08 12:54:46', '2021-05-08 12:54:46'),
(102, 54, 90, NULL, NULL, 1, NULL, 0, '2021-05-08 12:54:46', '2021-05-08 12:54:46'),
(103, 54, 92, NULL, NULL, 1, NULL, 0, '2021-05-08 12:58:02', '2021-05-08 12:58:02'),
(104, 54, 92, NULL, NULL, 1, NULL, 0, '2021-05-08 12:58:02', '2021-05-08 12:58:02'),
(105, 54, NULL, 53, NULL, 2, NULL, 0, '2021-05-08 12:58:02', '2021-05-08 12:58:02'),
(106, 54, 93, NULL, NULL, 1, NULL, 0, '2021-05-08 12:58:16', '2021-05-08 12:58:16'),
(107, 54, 93, NULL, NULL, 1, NULL, 0, '2021-05-08 12:58:16', '2021-05-08 12:58:16'),
(108, 54, 94, NULL, NULL, 1, NULL, 0, '2021-05-08 12:58:34', '2021-05-08 12:58:34'),
(109, 54, 94, NULL, NULL, 1, NULL, 0, '2021-05-08 12:58:34', '2021-05-08 12:58:34'),
(110, 54, 95, NULL, NULL, 1, NULL, 0, '2021-05-08 12:58:39', '2021-05-08 12:58:39'),
(111, 54, 95, NULL, NULL, 1, NULL, 0, '2021-05-08 12:58:39', '2021-05-08 12:58:39'),
(112, 54, 96, NULL, NULL, 1, NULL, 0, '2021-05-08 12:58:53', '2021-05-08 12:58:53'),
(113, 54, 96, NULL, NULL, 1, NULL, 0, '2021-05-08 12:58:53', '2021-05-08 12:58:53'),
(114, 54, NULL, 54, NULL, 2, NULL, 0, '2021-05-08 12:58:53', '2021-05-08 12:58:53'),
(115, 54, 97, NULL, NULL, 1, NULL, 0, '2021-05-08 12:59:05', '2021-05-08 12:59:05'),
(116, 54, 97, NULL, NULL, 1, NULL, 0, '2021-05-08 12:59:05', '2021-05-08 12:59:05'),
(117, 54, 98, NULL, NULL, 1, NULL, 0, '2021-05-08 12:59:15', '2021-05-08 12:59:15'),
(118, 54, 98, NULL, NULL, 1, NULL, 0, '2021-05-08 12:59:15', '2021-05-08 12:59:15'),
(119, 54, 99, NULL, NULL, 1, NULL, 0, '2021-05-08 13:04:43', '2021-05-08 13:04:43'),
(120, 54, 99, NULL, NULL, 1, NULL, 0, '2021-05-08 13:04:43', '2021-05-08 13:04:43'),
(121, 54, NULL, 55, NULL, 2, NULL, 0, '2021-05-08 13:04:43', '2021-05-08 13:04:43'),
(122, 54, NULL, 56, NULL, 2, NULL, 0, '2021-05-08 13:04:43', '2021-05-08 13:04:43'),
(123, 54, NULL, 57, NULL, 2, NULL, 0, '2021-05-08 13:04:43', '2021-05-08 13:04:43'),
(124, 54, 102, NULL, NULL, 1, NULL, 0, '2021-05-08 13:11:24', '2021-05-08 13:11:24'),
(125, 54, 102, NULL, NULL, 1, NULL, 0, '2021-05-08 13:11:24', '2021-05-08 13:11:24'),
(126, 54, NULL, 60, NULL, 2, NULL, 0, '2021-05-08 13:11:24', '2021-05-08 13:11:24'),
(127, 54, 103, NULL, NULL, 1, NULL, 0, '2021-05-08 13:12:46', '2021-05-08 13:12:46'),
(128, 54, 103, NULL, NULL, 1, NULL, 0, '2021-05-08 13:12:46', '2021-05-08 13:12:46'),
(129, 54, NULL, 61, NULL, 2, NULL, 0, '2021-05-08 13:12:46', '2021-05-08 13:12:46'),
(130, 54, 118, NULL, NULL, 1, NULL, 0, '2021-05-08 13:44:55', '2021-05-08 13:44:55'),
(131, 54, 118, NULL, NULL, 1, NULL, 0, '2021-05-08 13:44:55', '2021-05-08 13:44:55'),
(132, 54, 119, NULL, NULL, 1, NULL, 0, '2021-05-08 13:45:24', '2021-05-08 13:45:24'),
(133, 54, 119, NULL, NULL, 1, NULL, 0, '2021-05-08 13:45:24', '2021-05-08 13:45:24'),
(137, 54, NULL, 77, NULL, 2, NULL, 0, '2021-05-08 13:45:53', '2021-05-08 13:45:53'),
(138, 54, NULL, 78, NULL, 2, NULL, 0, '2021-05-08 13:45:53', '2021-05-08 13:45:53'),
(139, 54, NULL, 79, NULL, 2, NULL, 0, '2021-05-08 13:45:53', '2021-05-08 13:45:53'),
(140, 54, NULL, 80, NULL, 2, NULL, 0, '2021-05-08 13:45:53', '2021-05-08 13:45:53'),
(142, 55, NULL, NULL, NULL, 3, '2000.00', 0, '2021-05-09 04:02:53', '2021-05-09 04:02:53'),
(143, 54, 133, NULL, NULL, 1, NULL, 0, '2021-05-09 12:32:55', '2021-05-09 12:32:55'),
(144, 54, 133, NULL, NULL, 1, NULL, 0, '2021-05-09 12:32:55', '2021-05-09 12:32:55'),
(145, 54, NULL, 82, NULL, 2, NULL, 0, '2021-05-09 12:32:55', '2021-05-09 12:32:55'),
(146, 54, 134, NULL, NULL, 1, NULL, 0, '2021-05-09 12:51:45', '2021-05-09 12:51:45'),
(147, 55, 136, NULL, NULL, 1, NULL, 0, '2021-05-09 13:02:56', '2021-05-09 13:02:56'),
(148, 55, 136, NULL, NULL, 1, NULL, 0, '2021-05-09 13:02:56', '2021-05-09 13:02:56'),
(149, 55, NULL, 84, NULL, 2, NULL, 0, '2021-05-09 13:02:56', '2021-05-09 13:02:56'),
(150, 39, 146, NULL, NULL, 1, NULL, 0, '2021-05-11 03:25:55', '2021-05-11 03:25:55'),
(151, 39, 146, NULL, NULL, 1, NULL, 0, '2021-05-11 03:25:55', '2021-05-11 03:25:55'),
(152, 39, 147, NULL, NULL, 1, NULL, 0, '2021-05-11 03:30:38', '2021-05-11 03:30:38'),
(153, 39, 147, NULL, NULL, 1, NULL, 0, '2021-05-11 03:30:38', '2021-05-11 03:30:38'),
(154, 43, 156, NULL, NULL, 1, NULL, 0, '2021-05-18 07:23:29', '2021-05-18 07:23:29'),
(155, 43, 156, NULL, NULL, 1, NULL, 0, '2021-05-18 07:23:29', '2021-05-18 07:23:29'),
(156, 45, 157, NULL, NULL, 1, NULL, 0, '2021-05-18 07:26:26', '2021-05-18 07:26:26'),
(157, 45, 157, NULL, NULL, 1, NULL, 0, '2021-05-18 07:26:26', '2021-05-18 07:26:26'),
(158, 45, NULL, 121, NULL, 2, NULL, 0, '2021-05-18 07:26:26', '2021-05-18 07:26:26'),
(159, 49, 158, NULL, NULL, 1, NULL, 0, '2021-05-18 07:32:40', '2021-05-18 07:32:40'),
(160, 49, 158, NULL, NULL, 1, NULL, 0, '2021-05-18 07:32:40', '2021-05-18 07:32:40'),
(161, 49, NULL, 122, NULL, 2, NULL, 0, '2021-05-18 07:32:40', '2021-05-18 07:32:40'),
(162, 40, 159, NULL, NULL, 1, NULL, 0, '2021-05-18 09:28:34', '2021-05-18 09:28:34'),
(163, 40, 159, NULL, NULL, 1, NULL, 0, '2021-05-18 09:28:34', '2021-05-18 09:28:34'),
(164, 40, NULL, 123, NULL, 2, NULL, 0, '2021-05-18 09:28:34', '2021-05-18 09:28:34'),
(165, 52, 167, NULL, NULL, 1, NULL, 0, '2021-05-19 10:08:09', '2021-05-19 10:08:09'),
(166, 52, NULL, 132, NULL, 2, NULL, 0, '2021-05-19 10:08:09', '2021-05-19 10:08:09'),
(167, 54, 168, NULL, NULL, 1, NULL, 0, '2021-05-19 10:13:18', '2021-05-19 10:13:18'),
(168, 54, NULL, 133, NULL, 2, NULL, 0, '2021-05-19 10:13:18', '2021-05-19 10:13:18'),
(169, 54, 169, NULL, NULL, 1, NULL, 0, '2021-05-19 10:13:43', '2021-05-19 10:13:43'),
(170, 54, NULL, 134, NULL, 2, NULL, 0, '2021-05-19 10:13:43', '2021-05-19 10:13:43'),
(171, 51, 170, NULL, NULL, 1, NULL, 0, '2021-05-19 10:15:19', '2021-05-19 10:15:19'),
(172, 51, NULL, 135, NULL, 2, NULL, 0, '2021-05-19 10:15:19', '2021-05-19 10:15:19'),
(173, 55, 174, NULL, NULL, 1, NULL, 0, '2021-05-24 05:50:13', '2021-05-24 05:50:13'),
(174, 55, NULL, 139, NULL, 2, NULL, 0, '2021-05-24 05:50:13', '2021-05-24 05:50:13'),
(175, 55, 175, NULL, NULL, 1, NULL, 0, '2021-05-24 05:53:25', '2021-05-24 05:53:25'),
(176, 55, NULL, 140, NULL, 2, NULL, 0, '2021-05-24 05:53:25', '2021-05-24 05:53:25'),
(177, 41, 176, NULL, NULL, 1, NULL, 0, '2021-06-01 13:24:46', '2021-06-01 13:24:46'),
(178, 41, 176, NULL, NULL, 1, NULL, 0, '2021-06-01 13:24:46', '2021-06-01 13:24:46'),
(184, 39, 180, NULL, NULL, 1, NULL, 0, '2021-06-02 12:27:21', '2021-06-02 12:27:21'),
(191, 39, 184, NULL, NULL, 1, NULL, 0, '2021-06-02 12:34:22', '2021-06-02 12:34:22'),
(192, 39, 185, NULL, NULL, 1, NULL, 0, '2021-06-02 12:35:27', '2021-06-02 12:35:27'),
(193, 39, 186, NULL, NULL, 1, NULL, 0, '2021-06-02 12:37:47', '2021-06-02 12:37:47'),
(198, 41, NULL, 144, NULL, 2, NULL, 0, '2021-06-03 07:32:38', '2021-06-03 07:32:38'),
(199, 42, 191, NULL, NULL, 1, NULL, 0, '2021-06-03 07:51:43', '2021-06-03 07:51:43'),
(200, 42, 191, NULL, NULL, 1, NULL, 0, '2021-06-03 07:51:43', '2021-06-03 07:51:43'),
(201, 42, NULL, 145, NULL, 2, NULL, 0, '2021-06-03 07:51:44', '2021-06-03 07:51:44'),
(202, 42, NULL, 146, NULL, 2, NULL, 0, '2021-06-03 07:55:22', '2021-06-03 07:55:22'),
(203, 49, NULL, 147, NULL, 2, NULL, 0, '2021-06-03 08:00:48', '2021-06-03 08:00:48'),
(204, 56, NULL, NULL, NULL, 3, '86.00', 0, '2021-06-03 12:21:39', '2021-06-03 12:21:39'),
(205, 44, 193, NULL, NULL, 1, NULL, 0, '2021-06-03 12:36:11', '2021-06-03 12:36:11'),
(206, 44, 193, NULL, NULL, 1, NULL, 0, '2021-06-03 12:36:12', '2021-06-03 12:36:12'),
(207, 44, NULL, 148, NULL, 2, NULL, 0, '2021-06-03 12:36:12', '2021-06-03 12:36:12'),
(208, 44, NULL, 149, NULL, 2, NULL, 0, '2021-06-03 12:49:39', '2021-06-03 12:49:39'),
(209, 51, 195, NULL, NULL, 1, NULL, 0, '2021-06-03 13:11:16', '2021-06-03 13:11:16'),
(210, 51, 195, NULL, NULL, 1, NULL, 0, '2021-06-03 13:11:16', '2021-06-03 13:11:16'),
(211, 51, NULL, 153, NULL, 2, NULL, 0, '2021-06-03 13:11:16', '2021-06-03 13:11:16'),
(212, 51, NULL, 154, NULL, 2, NULL, 0, '2021-06-03 13:12:29', '2021-06-03 13:12:29'),
(213, 51, NULL, 155, NULL, 2, NULL, 0, '2021-06-03 13:14:09', '2021-06-03 13:14:09'),
(214, 54, 196, NULL, NULL, 1, NULL, 0, '2021-06-03 13:21:23', '2021-06-03 13:21:23'),
(215, 54, 196, NULL, NULL, 1, NULL, 0, '2021-06-03 13:21:23', '2021-06-03 13:21:23'),
(216, 54, NULL, 156, NULL, 2, NULL, 0, '2021-06-03 13:21:23', '2021-06-03 13:21:23'),
(217, 54, NULL, 157, NULL, 2, NULL, 0, '2021-06-03 13:22:00', '2021-06-03 13:22:00'),
(218, 46, 198, NULL, NULL, 1, NULL, 0, '2021-06-03 13:25:26', '2021-06-03 13:25:26'),
(219, 46, 198, NULL, NULL, 1, NULL, 0, '2021-06-03 13:25:26', '2021-06-03 13:25:26'),
(220, 46, NULL, 159, NULL, 2, NULL, 0, '2021-06-03 13:25:26', '2021-06-03 13:25:26'),
(221, 46, NULL, 160, NULL, 2, NULL, 0, '2021-06-03 13:26:04', '2021-06-03 13:26:04'),
(222, 46, NULL, 161, NULL, 2, NULL, 0, '2021-06-03 13:26:45', '2021-06-03 13:26:45'),
(223, 57, NULL, NULL, NULL, 3, '88.00', 0, '2021-06-05 05:04:04', '2021-06-05 05:04:04'),
(224, 58, NULL, NULL, NULL, 3, '21.00', 0, '2021-06-05 12:33:24', '2021-06-05 12:33:24'),
(225, 55, 203, NULL, NULL, 1, NULL, 0, '2021-06-06 07:23:19', '2021-06-06 07:23:19'),
(226, 55, NULL, 170, NULL, 2, NULL, 0, '2021-06-06 07:23:19', '2021-06-06 07:23:19'),
(227, 46, 205, NULL, NULL, 1, NULL, 0, '2021-06-06 08:29:28', '2021-06-06 08:29:28'),
(228, 46, NULL, 172, NULL, 2, NULL, 0, '2021-06-06 08:29:28', '2021-06-06 08:29:28'),
(229, 51, 206, NULL, NULL, 1, NULL, 0, '2021-06-06 11:22:47', '2021-06-06 11:22:47'),
(230, 51, 206, NULL, NULL, 1, NULL, 0, '2021-06-06 11:22:47', '2021-06-06 11:22:47'),
(231, 46, 208, NULL, NULL, 1, NULL, 0, '2021-06-07 11:41:46', '2021-06-07 11:41:46'),
(232, 46, 208, NULL, NULL, 1, NULL, 0, '2021-06-07 11:41:46', '2021-06-07 11:41:46'),
(233, 41, 224, NULL, NULL, 1, NULL, 0, '2021-06-08 11:08:25', '2021-06-08 11:08:25'),
(234, 41, 224, NULL, NULL, 1, NULL, 0, '2021-06-08 11:08:25', '2021-06-08 11:08:25'),
(235, 40, 225, NULL, NULL, 1, NULL, 0, '2021-06-08 11:09:27', '2021-06-08 11:09:27'),
(236, 40, 225, NULL, NULL, 1, NULL, 0, '2021-06-08 11:09:27', '2021-06-08 11:09:27'),
(237, 56, 226, NULL, NULL, 1, NULL, 0, '2021-06-08 11:27:11', '2021-06-08 11:27:11'),
(238, 56, NULL, 189, NULL, 2, NULL, 0, '2021-06-08 11:27:11', '2021-06-08 11:27:11'),
(239, 56, 227, NULL, NULL, 1, NULL, 0, '2021-06-08 11:27:17', '2021-06-08 11:27:17'),
(240, 56, NULL, 190, NULL, 2, NULL, 0, '2021-06-08 11:27:17', '2021-06-08 11:27:17'),
(241, 56, 228, NULL, NULL, 1, NULL, 0, '2021-06-08 11:28:08', '2021-06-08 11:28:08'),
(242, 56, NULL, 191, NULL, 2, NULL, 0, '2021-06-08 11:28:08', '2021-06-08 11:28:08'),
(243, 56, 229, NULL, NULL, 1, NULL, 0, '2021-06-08 11:29:18', '2021-06-08 11:29:18'),
(244, 56, NULL, 192, NULL, 2, NULL, 0, '2021-06-08 11:29:18', '2021-06-08 11:29:18'),
(245, 56, 230, NULL, NULL, 1, NULL, 0, '2021-06-08 11:31:29', '2021-06-08 11:31:29'),
(246, 56, NULL, 193, NULL, 2, NULL, 0, '2021-06-08 11:31:29', '2021-06-08 11:31:29'),
(247, 56, 231, NULL, NULL, 1, NULL, 0, '2021-06-08 11:33:15', '2021-06-08 11:33:15'),
(248, 56, NULL, 194, NULL, 2, NULL, 0, '2021-06-08 11:33:15', '2021-06-08 11:33:15'),
(249, 56, 232, NULL, NULL, 1, NULL, 0, '2021-06-08 11:39:49', '2021-06-08 11:39:49'),
(250, 56, NULL, 195, NULL, 2, NULL, 0, '2021-06-08 11:39:49', '2021-06-08 11:39:49'),
(251, 56, 233, NULL, NULL, 1, NULL, 0, '2021-06-08 11:39:49', '2021-06-08 11:39:49'),
(252, 56, NULL, 196, NULL, 2, NULL, 0, '2021-06-08 11:39:49', '2021-06-08 11:39:49'),
(253, 56, 234, NULL, NULL, 1, NULL, 0, '2021-06-08 11:39:50', '2021-06-08 11:39:50'),
(254, 56, NULL, 197, NULL, 2, NULL, 0, '2021-06-08 11:39:50', '2021-06-08 11:39:50'),
(255, 56, 235, NULL, NULL, 1, NULL, 0, '2021-06-08 11:45:13', '2021-06-08 11:45:13'),
(256, 56, NULL, 198, NULL, 2, NULL, 0, '2021-06-08 11:45:13', '2021-06-08 11:45:13'),
(257, 56, 236, NULL, NULL, 1, NULL, 0, '2021-06-08 11:46:24', '2021-06-08 11:46:24'),
(258, 56, NULL, 199, NULL, 2, NULL, 0, '2021-06-08 11:46:24', '2021-06-08 11:46:24'),
(259, 40, NULL, 202, NULL, 2, NULL, 0, '2021-06-08 13:07:02', '2021-06-08 13:07:02'),
(260, 41, NULL, 203, NULL, 2, NULL, 0, '2021-06-08 13:10:33', '2021-06-08 13:10:33'),
(261, 54, 240, NULL, NULL, 1, NULL, 0, '2021-06-09 08:56:54', '2021-06-09 08:56:54'),
(262, 54, 240, NULL, NULL, 1, NULL, 0, '2021-06-09 08:56:54', '2021-06-09 08:56:54'),
(263, 54, NULL, 208, NULL, 2, NULL, 0, '2021-06-09 08:56:54', '2021-06-09 08:56:54'),
(264, 40, NULL, 209, NULL, 2, NULL, 0, '2021-06-09 09:24:42', '2021-06-09 09:24:42'),
(265, 46, NULL, 210, NULL, 2, NULL, 0, '2021-06-09 09:25:04', '2021-06-09 09:25:04'),
(266, 49, NULL, 213, NULL, 2, NULL, 0, '2021-06-09 09:27:00', '2021-06-09 09:27:00'),
(283, 61, NULL, NULL, NULL, 3, '0.00', 0, '2021-06-10 06:02:01', '2021-06-10 06:02:01'),
(284, 61, 253, NULL, NULL, 1, NULL, 0, '2021-06-10 06:06:46', '2021-06-10 06:06:46'),
(285, 61, 254, NULL, NULL, 1, NULL, 0, '2021-06-10 06:09:43', '2021-06-10 06:09:43'),
(286, 61, NULL, 233, NULL, 2, NULL, 0, '2021-06-10 06:09:43', '2021-06-10 06:09:43'),
(287, 61, NULL, 234, NULL, 2, NULL, 0, '2021-06-10 06:09:43', '2021-06-10 06:09:43'),
(288, 51, NULL, 235, NULL, 2, NULL, 0, '2021-06-10 06:37:20', '2021-06-10 06:37:20'),
(289, 51, NULL, 236, NULL, 2, NULL, 0, '2021-06-10 06:37:28', '2021-06-10 06:37:28'),
(290, 41, NULL, 237, NULL, 2, NULL, 0, '2021-06-10 06:37:39', '2021-06-10 06:37:39'),
(291, 45, NULL, 238, NULL, 2, NULL, 0, '2021-06-10 06:37:50', '2021-06-10 06:37:50'),
(292, 61, 264, NULL, NULL, 1, NULL, 0, '2021-06-17 10:39:58', '2021-06-17 10:39:58'),
(293, 61, NULL, 249, NULL, 2, NULL, 0, '2021-06-17 10:39:58', '2021-06-17 10:39:58'),
(294, 61, 265, NULL, NULL, 1, NULL, 0, '2021-06-17 10:41:59', '2021-06-17 10:41:59'),
(295, 61, NULL, 250, NULL, 2, NULL, 0, '2021-06-17 10:41:59', '2021-06-17 10:41:59'),
(296, 61, 266, NULL, NULL, 1, NULL, 0, '2021-06-17 10:44:49', '2021-06-17 10:44:49'),
(297, 61, 266, NULL, NULL, 1, NULL, 0, '2021-06-17 10:44:49', '2021-06-17 10:44:49'),
(298, 61, NULL, 251, NULL, 2, NULL, 0, '2021-06-17 10:44:49', '2021-06-17 10:44:49');

-- --------------------------------------------------------

--
-- Table structure for table `expanses`
--

CREATE TABLE `expanses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `attachment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `note` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tax_percent` decimal(22,2) NOT NULL DEFAULT 0.00,
  `tax_amount` decimal(22,2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(22,2) NOT NULL DEFAULT 0.00,
  `net_total_amount` decimal(22,2) NOT NULL DEFAULT 0.00,
  `paid` decimal(22,2) NOT NULL DEFAULT 0.00,
  `due` decimal(22,2) NOT NULL DEFAULT 0.00,
  `date` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `month` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `year` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `admin_id` bigint(20) UNSIGNED DEFAULT NULL,
  `report_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `expanses`
--

INSERT INTO `expanses` (`id`, `invoice_id`, `branch_id`, `attachment`, `note`, `tax_percent`, `tax_amount`, `total_amount`, `net_total_amount`, `paid`, `due`, `date`, `month`, `year`, `admin_id`, `report_date`, `created_at`, `updated_at`) VALUES
(3, 'EXI210523823418', NULL, NULL, NULL, '0.00', '0.00', '5800.00', '5800.00', '5800.00', '0.00', '2021-05-23', 'May', '2021', NULL, '2021-05-22 18:00:00', '2021-05-23 11:24:41', '2021-05-23 11:24:41'),
(4, 'EXI210523333182', NULL, NULL, NULL, '0.00', '0.00', '5800.00', '5800.00', '5800.00', '0.00', '2021-05-23', 'May', '2021', NULL, '2021-05-22 18:00:00', '2021-05-23 11:25:07', '2021-05-23 11:25:07'),
(5, 'EXI210523117473', NULL, NULL, NULL, '5.00', '0.00', '300.00', '315.00', '100.00', '215.00', '2021-05-23', 'May', '2021', 2, '2021-05-22 18:00:00', '2021-05-23 11:27:15', '2021-05-25 07:54:12'),
(6, 'EXI210524244484', 24, NULL, NULL, '0.00', '0.00', '57.00', '57.00', '57.00', '0.00', '2021-05-24', 'May', '2021', 9, '2021-05-23 18:00:00', '2021-05-24 04:49:47', '2021-05-24 04:50:10'),
(7, 'EXI210524274735', 24, NULL, NULL, '0.00', '0.00', '61.00', '61.00', '61.00', '0.00', '2021-05-24', 'May', '2021', 7, '2021-05-23 18:00:00', '2021-05-24 05:06:51', '2021-05-25 06:25:04'),
(8, 'EXI210609978496', NULL, NULL, NULL, '0.00', '0.00', '600.00', '600.00', '600.00', '0.00', '2021-06-09', 'June', '2021', NULL, '2021-06-08 18:00:00', '2021-06-09 08:46:09', '2021-06-09 08:46:09'),
(9, 'EXI210613941147', NULL, NULL, NULL, '0.00', '0.00', '100.00', '100.00', '0.00', '100.00', '2021-06-13', 'June', '2021', 2, '2021-06-12 18:00:00', '2021-06-13 11:35:53', '2021-06-13 11:35:53'),
(10, 'EXI210613377896', NULL, NULL, NULL, '0.00', '0.00', '100.00', '100.00', '0.00', '100.00', '2021-06-13', 'June', '2021', 2, '2021-06-12 18:00:00', '2021-06-13 11:36:24', '2021-06-13 11:36:24'),
(11, 'Ex-14552', NULL, NULL, NULL, '5.00', '0.00', '1000.00', '1050.00', '1000.00', '50.00', '2021-06-13', 'June', '2021', 7, '2021-06-12 18:00:00', '2021-06-13 11:38:01', '2021-06-13 11:38:01'),
(12, 'Ex-45522', NULL, NULL, NULL, '5.00', '0.00', '800.00', '840.00', '840.00', '0.00', '2021-06-13', 'June', '2021', 7, '2021-06-12 18:00:00', '2021-06-13 11:38:57', '2021-06-13 11:38:57'),
(13, 'EXI210613776419', NULL, NULL, NULL, '0.00', '0.00', '100.00', '100.00', '0.00', '100.00', '2021-06-13', 'June', '2021', 2, '2021-06-12 18:00:00', '2021-06-13 11:39:42', '2021-06-13 11:39:42'),
(14, 'EXI210613927436', NULL, NULL, NULL, '0.00', '0.00', '100.00', '100.00', '0.00', '100.00', '2021-06-13', 'June', '2021', NULL, '2021-06-12 18:00:00', '2021-06-13 11:40:59', '2021-06-13 11:40:59'),
(15, 'EXI210613734998', NULL, NULL, NULL, '0.00', '0.00', '100.00', '100.00', '100.00', '0.00', '2021-06-13', 'June', '2021', 5, '2021-06-12 18:00:00', '2021-06-13 11:54:41', '2021-06-13 11:54:41'),
(16, 'EXI210613835418', NULL, NULL, NULL, '0.00', '0.00', '100.00', '100.00', '100.00', '0.00', '2021-06-13', 'June', '2021', 4, '2021-06-13 12:00:55', '2021-06-13 11:55:28', '2021-06-13 12:00:55'),
(17, 'EXI210614561229', NULL, NULL, NULL, '0.00', '0.00', '120.00', '120.00', '100.00', '20.00', '2021-06-14', 'June', '2021', 2, '2021-06-13 18:00:00', '2021-06-14 05:06:15', '2021-06-14 05:06:15');

-- --------------------------------------------------------

--
-- Table structure for table `expanse_categories`
--

CREATE TABLE `expanse_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `expanse_categories`
--

INSERT INTO `expanse_categories` (`id`, `name`, `code`, `created_at`, `updated_at`) VALUES
(12, 'Net Bill', 'NB1', NULL, NULL),
(13, 'Others', 'OT2', NULL, NULL),
(14, 'Clener', 'C3', NULL, NULL),
(15, 'Help', 'H123', NULL, NULL),
(16, 'Bus Fare', 'BF-56', NULL, NULL),
(17, 'Snacks', 'S-122', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `expanse_payments`
--

CREATE TABLE `expanse_payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expanse_id` bigint(20) UNSIGNED NOT NULL,
  `account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `pay_mode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paid_amount` decimal(22,2) NOT NULL DEFAULT 0.00,
  `payment_status` tinyint(4) DEFAULT NULL COMMENT '1=due;2=partial;3=paid',
  `date` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `month` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `year` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `card_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_holder` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_transaction_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_month` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_year` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_secure_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cheque_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transaction_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attachment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `admin_id` bigint(20) UNSIGNED DEFAULT NULL,
  `note` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `report_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `expanse_payments`
--

INSERT INTO `expanse_payments` (`id`, `invoice_id`, `expanse_id`, `account_id`, `pay_mode`, `paid_amount`, `payment_status`, `date`, `month`, `year`, `card_no`, `card_holder`, `card_type`, `card_transaction_no`, `card_month`, `card_year`, `card_secure_code`, `account_no`, `cheque_no`, `transaction_no`, `attachment`, `admin_id`, `note`, `report_date`, `created_at`, `updated_at`) VALUES
(31, 'EXPI210523117473', 5, 16, 'Card', '100.00', NULL, '2021-05-23', 'May', '2021', NULL, NULL, 'Credit-Card', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, '2021-05-22 18:00:00', '2021-05-23 11:27:15', '2021-05-23 11:27:15'),
(32, 'EXPI210524244484', 6, 16, 'Advanced', '57.00', NULL, '2021-05-24', 'May', '2021', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 7, NULL, '2021-05-23 18:00:00', '2021-05-24 04:49:47', '2021-05-24 04:49:47'),
(33, 'EXPI21052499189', 7, 15, 'Card', '15.00', NULL, '2021-05-24', 'May', '2021', 'FF', 'FF', 'Credit-Card', 'FF', 'FF', 'FF', 'FF', NULL, NULL, NULL, NULL, 7, NULL, '2021-05-23 18:00:00', '2021-05-24 05:07:13', '2021-05-24 05:07:25'),
(34, 'EXPI21052418122', 7, 15, 'Bank-Transfer', '46.00', NULL, '2021-05-24', 'May', '2021', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 7, NULL, '2021-05-23 18:00:00', '2021-05-24 05:07:37', '2021-05-24 05:07:37'),
(35, 'EXPI210609978496', 8, NULL, 'Cash', '600.00', NULL, '2021-06-09', 'June', '2021', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, '2021-06-08 18:00:00', '2021-06-09 08:46:09', '2021-06-09 08:46:09'),
(36, 'EXPI210613543778', 11, NULL, 'Cash', '1000.00', NULL, '2021-06-13', 'June', '2021', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, '2021-06-12 18:00:00', '2021-06-13 11:38:01', '2021-06-13 11:38:01'),
(37, 'EXPI210613232681', 12, NULL, 'Cash', '840.00', NULL, '2021-06-13', 'June', '2021', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, '2021-06-12 18:00:00', '2021-06-13 11:38:57', '2021-06-13 11:38:57'),
(38, 'EXPI210613734998', 15, NULL, 'Cash', '100.00', NULL, '2021-06-13', 'June', '2021', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, '2021-06-12 18:00:00', '2021-06-13 11:54:41', '2021-06-13 11:54:41'),
(39, 'EXPI210613835418', 16, NULL, 'Cash', '100.00', NULL, '2021-06-13', 'June', '2021', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, '2021-06-12 18:00:00', '2021-06-13 11:55:28', '2021-06-13 12:00:55'),
(40, 'EXPI210614561229', 17, NULL, 'Cash', '100.00', NULL, '2021-06-14', 'June', '2021', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, '2021-06-13 18:00:00', '2021-06-14 05:06:15', '2021-06-14 05:06:15');

-- --------------------------------------------------------

--
-- Table structure for table `expense_descriptions`
--

CREATE TABLE `expense_descriptions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `expense_id` bigint(20) UNSIGNED DEFAULT NULL,
  `expense_category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `amount` decimal(22,2) NOT NULL DEFAULT 0.00,
  `is_delete_in_update` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `expense_descriptions`
--

INSERT INTO `expense_descriptions` (`id`, `expense_id`, `expense_category_id`, `amount`, `is_delete_in_update`, `created_at`, `updated_at`) VALUES
(9, 5, 14, '100.00', 0, '2021-05-23 11:27:15', '2021-05-25 07:54:12'),
(10, 5, 13, '200.00', 0, '2021-05-23 11:27:15', '2021-05-25 07:54:12'),
(11, 6, 14, '45.00', 0, '2021-05-24 04:49:47', '2021-05-24 04:50:10'),
(12, 6, 14, '12.00', 0, '2021-05-24 04:49:47', '2021-05-24 04:50:10'),
(13, 7, 14, '10.00', 0, '2021-05-24 05:06:51', '2021-05-25 06:25:04'),
(14, 7, 14, '12.00', 0, '2021-05-24 05:06:51', '2021-05-25 06:25:04'),
(15, 7, 13, '12.00', 0, '2021-05-24 05:06:51', '2021-05-25 06:25:04'),
(16, 7, 14, '12.00', 0, '2021-05-24 05:06:51', '2021-05-25 06:25:04'),
(17, 7, 14, '15.00', 0, '2021-05-24 05:06:51', '2021-05-25 06:25:04'),
(18, 8, 14, '100.00', 0, '2021-06-09 08:46:09', '2021-06-09 08:46:09'),
(19, 8, 13, '500.00', 0, '2021-06-09 08:46:09', '2021-06-09 08:46:09'),
(20, 9, 14, '100.00', 0, '2021-06-13 11:35:53', '2021-06-13 11:35:53'),
(21, 10, 14, '100.00', 0, '2021-06-13 11:36:24', '2021-06-13 11:36:24'),
(22, 11, 14, '1000.00', 0, '2021-06-13 11:38:01', '2021-06-13 11:38:01'),
(23, 12, 14, '100.00', 0, '2021-06-13 11:38:57', '2021-06-13 11:38:57'),
(24, 12, 13, '200.00', 0, '2021-06-13 11:38:57', '2021-06-13 11:38:57'),
(25, 12, 12, '500.00', 0, '2021-06-13 11:38:57', '2021-06-13 11:38:57'),
(26, 13, 13, '100.00', 0, '2021-06-13 11:39:42', '2021-06-13 11:39:42'),
(27, 14, 14, '100.00', 0, '2021-06-13 11:40:59', '2021-06-13 11:40:59'),
(28, 15, 14, '100.00', 0, '2021-06-13 11:54:41', '2021-06-13 11:54:41'),
(29, 16, 14, '100.00', 0, '2021-06-13 11:55:28', '2021-06-13 11:55:28'),
(30, 17, 17, '120.00', 0, '2021-06-14 05:06:15', '2021-06-14 05:06:15');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `general_settings`
--

CREATE TABLE `general_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `business` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tax` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sale` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pos` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `purchase` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dashboard` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `system` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `prefix` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_setting` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sms_setting` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `modules` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reward_poing_settings` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `multi_branches` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'is_activated',
  `hrm` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'is_activated',
  `services` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'is_activated',
  `menufacturing` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'is_activated',
  `projects` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'is_activated',
  `essentials` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'is_activated',
  `e_commerce` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'is_activated',
  `contact_default_cr_limit` decimal(22,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `general_settings`
--

INSERT INTO `general_settings` (`id`, `business`, `tax`, `product`, `sale`, `pos`, `purchase`, `dashboard`, `system`, `prefix`, `email_setting`, `sms_setting`, `modules`, `reward_poing_settings`, `multi_branches`, `hrm`, `services`, `menufacturing`, `projects`, `essentials`, `e_commerce`, `contact_default_cr_limit`, `created_at`, `updated_at`) VALUES
(1, '{\"shop_name\":\"SpeedDigit Pvt. Ltd.\",\"address\":\"Dhaka, Bangladesh\",\"phone\":\"015577886545\",\"email\":\"tg@gmail.com\",\"start_date\":\"07-04-2021\",\"default_profit\":0,\"currency\":\"\\u09f3\",\"currency_placement\":null,\"date_format\":\"dd-mm-yyyy\",\"financial_year_start\":\"Januaray\",\"time_format\":\"12\",\"business_logo\":\"60a9049c6c777-.png\",\"timezone\":\"Asia\\/Kalkata\"}', '{\"tax_1_name\":\"Tax\",\"tax_1_no\":\"1\",\"tax_2_name\":\"GST\",\"tax_2_no\":\"2\",\"is_tax_en_purchase_sale\":1}', '{\"product_code_prefix\":null,\"default_unit_id\":\"null\",\"is_enable_brands\":1,\"is_enable_categories\":1,\"is_enable_sub_categories\":1,\"is_enable_price_tax\":1,\"is_enable_warranty\":1}', '{\"default_sale_discount\":\"0.00\",\"default_tax_id\":\"null\",\"sales_cmsn_agnt\":\"select_form_cmsn_list\"}', '{\"is_disable_draft\":0,\"is_disable_quotation\":0,\"is_disable_challan\":0,\"is_disable_hold_invoice\":0,\"is_disable_multiple_pay\":1,\"is_show_recent_transactions\":0,\"is_disable_discount\":0,\"is_disable_order_tax\":0,\"is_show_credit_sale_button\":1,\"is_show_partial_sale_button\":1}', '{\"is_edit_pro_price\":0,\"is_enable_status\":1,\"is_enable_lot_no\":1}', '{\"view_stock_expiry_alert_for\":\"31\"}', '[]', '{\"purchase_invoice\":\"PI\",\"sale_invoice\":\"SI\",\"purchase_return\":\"PRI\",\"stock_transfer\":\"STI\",\"stock_djustment\":\"SA\",\"sale_return\":\"SRI\",\"expenses\":\"EXI\",\"supplier_id\":\"SID\",\"customer_id\":null,\"purchase_payment\":\"PPI\",\"sale_payment\":\"SPI\",\"expanse_payment\":\"EXPI\"}', '[]', '[]', '{\"purchases\":1,\"add_sale\":1,\"pos\":1,\"transfer_stock\":1,\"stock_adjustment\":1,\"expenses\":1,\"accounting\":1,\"contacts\":1,\"hrms\":1,\"damage_product\":1}', '{\"enable_cus_point\":1,\"point_display_name\":\"Reward Point\",\"amount_for_unit_rp\":\"10\",\"min_order_total_for_rp\":\"10\",\"max_rp_per_order\":\"\",\"redeem_amount_per_unit_rp\":\"10\",\"min_order_total_for_redeem\":\"\",\"min_redeem_point\":\"\",\"max_redeem_point\":\"\"}', 0, 0, 0, 0, 0, 0, 0, '50000.00', NULL, '2021-06-22 07:56:17');

-- --------------------------------------------------------

--
-- Table structure for table `hrm_allowance`
--

CREATE TABLE `hrm_allowance` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `employee_id` bigint(20) UNSIGNED DEFAULT NULL,
  `amount_type` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1=fixed;2=percentage',
  `amount` decimal(22,2) NOT NULL DEFAULT 0.00,
  `applicable_date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hrm_allowance`
--

INSERT INTO `hrm_allowance` (`id`, `description`, `type`, `employee_id`, `amount_type`, `amount`, `applicable_date`, `created_at`, `updated_at`) VALUES
(13, 'Allowance', 'Allowance', NULL, 1, '500.00', NULL, NULL, '2021-02-17 11:50:46'),
(14, 'Deduction', 'Deduction', NULL, 1, '10.00', NULL, NULL, '2021-06-06 05:37:11');

-- --------------------------------------------------------

--
-- Table structure for table `hrm_attendances`
--

CREATE TABLE `hrm_attendances` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `at_date` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `clock_in` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `clock_out` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `work_duration` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `clock_in_note` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `clock_out_note` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `month` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `year` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `clock_in_ts` timestamp NULL DEFAULT NULL,
  `clock_out_ts` timestamp NULL DEFAULT NULL,
  `at_date_ts` timestamp NULL DEFAULT NULL,
  `is_completed` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hrm_attendances`
--

INSERT INTO `hrm_attendances` (`id`, `at_date`, `user_id`, `clock_in`, `clock_out`, `work_duration`, `clock_in_note`, `clock_out_note`, `month`, `year`, `clock_in_ts`, `clock_out_ts`, `at_date_ts`, `is_completed`, `created_at`, `updated_at`) VALUES
(24, '01-06-2021', 4, '08:35', '20:13', NULL, NULL, NULL, 'June', '2021', '2021-06-01 02:35:00', '2021-06-13 14:13:00', '2021-06-01 02:35:00', 1, '2021-06-01 11:35:14', '2021-06-13 07:13:08'),
(25, '01-06-2021', 2, '06:35', '19:33', NULL, NULL, NULL, 'June', '2021', '2021-06-01 00:35:00', '2021-06-13 13:33:00', '2021-06-01 00:35:00', 1, '2021-06-01 11:35:14', '2021-06-13 07:33:19'),
(26, '01-06-2021', 6, '20:35', '06:12', NULL, NULL, NULL, 'June', '2021', '2021-06-01 14:35:00', '2021-06-13 00:12:00', '2021-06-01 14:35:00', 1, '2021-06-01 11:35:14', '2021-06-13 07:37:42'),
(27, '12-06-2021', 2, '10:00', NULL, NULL, NULL, NULL, 'June', '2021', '2021-06-12 04:00:00', NULL, '2021-06-11 18:00:00', 0, '2021-06-12 07:10:31', '2021-06-12 07:10:31'),
(28, '12-06-2021', 4, '10:00', '19:00', NULL, NULL, NULL, 'June', '2021', '2021-06-12 04:00:00', '2021-06-13 13:00:00', '2021-06-12 04:00:00', 1, '2021-06-12 07:10:31', '2021-06-13 07:32:57'),
(29, '12-06-2021', 6, '08:00', '20:12', NULL, NULL, NULL, 'June', '2021', '2021-06-12 02:00:00', '2021-06-13 14:12:00', '2021-06-12 02:00:00', 1, '2021-06-12 07:10:31', '2021-06-13 07:12:52');

-- --------------------------------------------------------

--
-- Table structure for table `hrm_department`
--

CREATE TABLE `hrm_department` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `department_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `department_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hrm_department`
--

INSERT INTO `hrm_department` (`id`, `department_name`, `department_id`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Accountings', '14', NULL, NULL, NULL),
(2, 'IT Department', '123', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `hrm_designations`
--

CREATE TABLE `hrm_designations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `designation_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hrm_designations`
--

INSERT INTO `hrm_designations` (`id`, `designation_name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Manager', 'This is a Designation.', NULL, '2021-06-12 08:51:30');

-- --------------------------------------------------------

--
-- Table structure for table `hrm_holidays`
--

CREATE TABLE `hrm_holidays` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `holiday_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `end_date` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_all` tinyint(1) NOT NULL DEFAULT 0,
  `notes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hrm_holidays`
--

INSERT INTO `hrm_holidays` (`id`, `holiday_name`, `start_date`, `end_date`, `branch_id`, `is_all`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'Weekend', '2021-06-05', '2021-06-05', NULL, 0, NULL, '2021-06-05 07:16:01', '2021-06-05 07:16:01'),
(2, 'Week', '2021-06-05', '2021-06-05', 26, 0, NULL, '2021-06-05 11:56:50', '2021-06-05 11:56:50');

-- --------------------------------------------------------

--
-- Table structure for table `hrm_leaves`
--

CREATE TABLE `hrm_leaves` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `reference_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `leave_id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `start_date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `end_date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reason` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hrm_leaves`
--

INSERT INTO `hrm_leaves` (`id`, `reference_number`, `leave_id`, `employee_id`, `start_date`, `end_date`, `reason`, `status`, `created_at`, `updated_at`) VALUES
(1, '760781', 1, 2, '2021-06-09', '2021-06-09', NULL, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `hrm_leavetypes`
--

CREATE TABLE `hrm_leavetypes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `leave_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `max_leave_count` int(11) NOT NULL,
  `leave_count_interval` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hrm_leavetypes`
--

INSERT INTO `hrm_leavetypes` (`id`, `leave_type`, `max_leave_count`, `leave_count_interval`, `created_at`, `updated_at`) VALUES
(1, 'Medical', 3, 2, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `hrm_payrolls`
--

CREATE TABLE `hrm_payrolls` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `reference_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `duration_time` decimal(22,2) NOT NULL DEFAULT 0.00,
  `duration_unit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount_per_unit` decimal(22,2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(22,2) NOT NULL DEFAULT 0.00,
  `total_allowance_amount` decimal(22,2) NOT NULL DEFAULT 0.00,
  `total_deduction_amount` decimal(22,2) NOT NULL DEFAULT 0.00,
  `gross_amount` decimal(22,2) NOT NULL DEFAULT 0.00,
  `paid` decimal(22,2) NOT NULL DEFAULT 0.00,
  `due` decimal(22,2) NOT NULL DEFAULT 0.00,
  `report_date_ts` timestamp NULL DEFAULT NULL,
  `date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `month` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `year` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `admin_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hrm_payrolls`
--

INSERT INTO `hrm_payrolls` (`id`, `user_id`, `reference_no`, `duration_time`, `duration_unit`, `amount_per_unit`, `total_amount`, `total_allowance_amount`, `total_deduction_amount`, `gross_amount`, `paid`, `due`, `report_date_ts`, `date`, `month`, `year`, `admin_id`, `created_at`, `updated_at`) VALUES
(11, 3, 'EP0421621454', '120.00', 'Hour', '10.00', '1200.00', '100.00', '100.00', '1200.00', '0.00', '1200.00', '2021-04-17 07:15:39', '17-04-2021', 'April', '2021', 2, '2021-04-17 07:15:39', '2021-04-17 07:15:39'),
(12, 6, 'EP0306219534', '100.00', 'Month', '200.00', '20000.00', '0.00', '0.00', '20000.00', '3900.00', '0.00', '2021-06-03 04:55:31', '03-06-2021', 'June', '2021', 2, '2021-06-03 04:55:31', '2021-06-06 10:28:56'),
(13, 2, 'EP1006212446', '100.00', 'Hourly', '200.00', '20000.00', '500.00', '10.00', '20490.00', '20490.00', '0.00', '2021-06-10 07:55:14', '10-06-2021', 'November', '2021', 2, '2021-06-10 07:55:14', '2021-06-10 07:55:24'),
(14, 4, 'EP1006216298', '20000.00', 'Monthly', '1.00', '20000.00', '500.00', '10.00', '20490.00', '20490.00', '0.00', '2021-06-10 08:42:00', '10-06-2021', 'September', '2021', 2, '2021-06-10 08:42:00', '2021-06-10 08:42:26');

-- --------------------------------------------------------

--
-- Table structure for table `hrm_payroll_allowances`
--

CREATE TABLE `hrm_payroll_allowances` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `payroll_id` bigint(20) UNSIGNED DEFAULT NULL,
  `allowance_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `allowance_percent` decimal(22,2) NOT NULL DEFAULT 0.00,
  `allowance_amount` decimal(22,2) NOT NULL DEFAULT 0.00,
  `date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `month` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `year` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `report_date_ts` timestamp NULL DEFAULT NULL,
  `is_delete_in_update` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hrm_payroll_allowances`
--

INSERT INTO `hrm_payroll_allowances` (`id`, `payroll_id`, `allowance_name`, `amount_type`, `allowance_percent`, `allowance_amount`, `date`, `month`, `year`, `report_date_ts`, `is_delete_in_update`, `created_at`, `updated_at`) VALUES
(12, 11, 'Home Rent', '1', '0.00', '100.00', NULL, NULL, NULL, NULL, 0, '2021-04-17 07:15:39', '2021-04-17 07:15:39'),
(14, 13, 'Allowance', '1', '0.00', '500.00', NULL, NULL, NULL, NULL, 0, '2021-06-10 07:55:14', '2021-06-10 07:55:14'),
(15, 14, 'Allowance', '1', '0.00', '500.00', NULL, NULL, NULL, NULL, 0, '2021-06-10 08:42:00', '2021-06-10 08:42:00');

-- --------------------------------------------------------

--
-- Table structure for table `hrm_payroll_deductions`
--

CREATE TABLE `hrm_payroll_deductions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `payroll_id` bigint(20) UNSIGNED DEFAULT NULL,
  `deduction_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount_type` tinyint(4) NOT NULL DEFAULT 1,
  `deduction_percent` decimal(8,2) NOT NULL DEFAULT 0.00,
  `deduction_amount` decimal(22,2) NOT NULL DEFAULT 0.00,
  `report_date_ts` timestamp NULL DEFAULT NULL,
  `is_delete_in_update` tinyint(1) NOT NULL DEFAULT 0,
  `date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `month` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `year` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hrm_payroll_deductions`
--

INSERT INTO `hrm_payroll_deductions` (`id`, `payroll_id`, `deduction_name`, `amount_type`, `deduction_percent`, `deduction_amount`, `report_date_ts`, `is_delete_in_update`, `date`, `month`, `year`, `created_at`, `updated_at`) VALUES
(9, 11, 'ff', 1, '0.00', '100.00', NULL, 0, NULL, NULL, NULL, '2021-04-17 07:15:39', '2021-04-17 07:15:39'),
(13, 13, 'Deduction', 1, '0.00', '10.00', NULL, 0, NULL, NULL, NULL, '2021-06-10 07:55:14', '2021-06-10 07:55:14'),
(14, 14, 'Deduction', 1, '0.00', '10.00', NULL, 0, NULL, NULL, NULL, '2021-06-10 08:42:00', '2021-06-10 08:42:00');

-- --------------------------------------------------------

--
-- Table structure for table `hrm_payroll_payments`
--

CREATE TABLE `hrm_payroll_payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `reference_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payroll_id` bigint(20) UNSIGNED DEFAULT NULL,
  `account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `paid` decimal(22,2) NOT NULL DEFAULT 0.00,
  `due` decimal(22,2) NOT NULL DEFAULT 0.00,
  `pay_mode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `time` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `month` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `year` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `report_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `card_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_holder` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_transaction_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_month` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_year` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_secure_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cheque_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transaction_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attachment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `note` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `admin_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hrm_payroll_payments`
--

INSERT INTO `hrm_payroll_payments` (`id`, `reference_no`, `payroll_id`, `account_id`, `paid`, `due`, `pay_mode`, `date`, `time`, `month`, `year`, `report_date`, `card_no`, `card_holder`, `card_type`, `card_transaction_no`, `card_month`, `card_year`, `card_secure_code`, `account_no`, `cheque_no`, `transaction_no`, `attachment`, `note`, `admin_id`, `created_at`, `updated_at`) VALUES
(13, 'PRP21060321132', 12, NULL, '16100.00', '0.00', 'Cash', '2021-06-03', '10:58:03 am', 'June', '2021', '2021-06-02 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-06-03 04:58:03', '2021-06-03 04:58:03'),
(14, 'PRP21060671649', 12, NULL, '3900.00', '0.00', 'Cash', '2021-06-06', '04:28:56 pm', 'June', '2021', '2021-06-05 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-06-06 10:28:56', '2021-06-06 10:28:56'),
(15, 'PRP21061021183', 13, 15, '20490.00', '0.00', 'Cash', '2021-06-10', '01:55:24 pm', 'June', '2021', '2021-06-09 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-06-10 07:55:24', '2021-06-10 07:55:24'),
(16, 'PRP21061049387', 14, 16, '20490.00', '0.00', 'Cheque', '2021-06-10', '02:42:26 pm', 'June', '2021', '2021-06-09 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'C:756665888555544', NULL, NULL, NULL, 2, '2021-06-10 08:42:26', '2021-06-10 08:42:26');

-- --------------------------------------------------------

--
-- Table structure for table `hrm_shifts`
--

CREATE TABLE `hrm_shifts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `shift_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_time` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `late_count` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `endtime` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hrm_shifts`
--

INSERT INTO `hrm_shifts` (`id`, `shift_name`, `start_time`, `late_count`, `endtime`, `created_at`, `updated_at`) VALUES
(1, 'Morning', '19:30', NULL, '19:30', NULL, NULL),
(2, 'tt', '17:12', NULL, '17:18', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `invoice_layouts`
--

CREATE TABLE `invoice_layouts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `layout_design` tinyint(4) NOT NULL COMMENT '1=normal_printer;2=pos_printer',
  `show_shop_logo` tinyint(1) NOT NULL DEFAULT 0,
  `header_text` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_header_less` tinyint(1) NOT NULL DEFAULT 0,
  `gap_from_top` bigint(20) DEFAULT NULL,
  `show_seller_info` tinyint(1) NOT NULL DEFAULT 0,
  `customer_name` tinyint(1) NOT NULL DEFAULT 1,
  `customer_tax_no` tinyint(1) NOT NULL DEFAULT 0,
  `customer_address` tinyint(1) NOT NULL DEFAULT 0,
  `customer_phone` tinyint(1) NOT NULL DEFAULT 0,
  `sub_heading_1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sub_heading_2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sub_heading_3` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `invoice_heading` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quotation_heading` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `draft_heading` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `challan_heading` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `branch_landmark` tinyint(1) NOT NULL DEFAULT 0,
  `branch_city` tinyint(1) NOT NULL DEFAULT 0,
  `branch_state` tinyint(1) NOT NULL DEFAULT 0,
  `branch_country` tinyint(1) NOT NULL DEFAULT 0,
  `branch_zipcode` tinyint(1) NOT NULL DEFAULT 0,
  `branch_phone` tinyint(1) NOT NULL DEFAULT 0,
  `branch_alternate_number` tinyint(1) NOT NULL DEFAULT 0,
  `branch_email` tinyint(1) NOT NULL DEFAULT 0,
  `product_img` tinyint(1) NOT NULL DEFAULT 0,
  `product_cate` tinyint(1) NOT NULL DEFAULT 0,
  `product_brand` tinyint(1) NOT NULL DEFAULT 0,
  `product_imei` tinyint(1) NOT NULL DEFAULT 0,
  `product_w_type` tinyint(1) NOT NULL DEFAULT 0,
  `product_w_duration` tinyint(1) NOT NULL DEFAULT 0,
  `product_w_discription` tinyint(1) NOT NULL DEFAULT 0,
  `product_discount` tinyint(1) NOT NULL DEFAULT 0,
  `product_tax` tinyint(1) NOT NULL DEFAULT 0,
  `product_price_inc_tax` tinyint(1) NOT NULL DEFAULT 0,
  `product_price_exc_tax` tinyint(1) NOT NULL DEFAULT 0,
  `invoice_notice` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sale_note` tinyint(1) NOT NULL DEFAULT 0,
  `show_total_in_word` tinyint(1) NOT NULL DEFAULT 0,
  `footer_text` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_branch` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoice_layouts`
--

INSERT INTO `invoice_layouts` (`id`, `name`, `layout_design`, `show_shop_logo`, `header_text`, `is_header_less`, `gap_from_top`, `show_seller_info`, `customer_name`, `customer_tax_no`, `customer_address`, `customer_phone`, `sub_heading_1`, `sub_heading_2`, `sub_heading_3`, `invoice_heading`, `quotation_heading`, `draft_heading`, `challan_heading`, `branch_landmark`, `branch_city`, `branch_state`, `branch_country`, `branch_zipcode`, `branch_phone`, `branch_alternate_number`, `branch_email`, `product_img`, `product_cate`, `product_brand`, `product_imei`, `product_w_type`, `product_w_duration`, `product_w_discription`, `product_discount`, `product_tax`, `product_price_inc_tax`, `product_price_exc_tax`, `invoice_notice`, `sale_note`, `show_total_in_word`, `footer_text`, `bank_name`, `bank_branch`, `account_name`, `account_no`, `is_default`, `created_at`, `updated_at`) VALUES
(1, 'Default layout', 2, 1, NULL, 1, 3, 1, 1, 1, 1, 1, NULL, NULL, NULL, 'Invoice/Bill', 'Quotation', 'Draft', 'Challan', 0, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 1, 1, 1, 0, 1, 1, 0, 0, 'Warranty Void: Any damage, malfunction and/or defect (s) arising from and/or due to mishandling, Improper use, \r\nunauthorized repair, physical damage, wetting, burning, electrical short circuit is not under warranty.', 0, 1, NULL, 'AL-ARAFA ISLAMI BANK Ltd.', 'Nawabpur', 'Speed Digit Pvt. Ltd', '0121020028467', 1, '2021-03-02 12:24:36', '2021-06-03 07:55:56'),
(2, 'Pos Printer Layout', 2, 1, NULL, 0, NULL, 1, 1, 1, 1, 1, 'Sub Heading Line 1', 'Sub Heading Line 2', NULL, 'Invoice', 'Quotation', 'Draft', 'Challan', 1, 1, 1, 0, 1, 1, 1, 1, 0, 0, 0, 0, 1, 1, 0, 1, 1, 0, 0, 'Invoice Notice', 0, 1, 'Footer Text', 'ff', 'ff', 'ff', 'ff', 0, '2021-03-03 10:20:30', '2021-05-11 05:04:15'),
(3, 'Header Less', 1, 1, NULL, 1, 2, 1, 1, 1, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 1, 0, 1, 1, 1, 1, 0, 0, 0, 1, 1, 1, 1, 1, 1, 1, 1, NULL, 0, 1, NULL, NULL, NULL, NULL, NULL, 0, '2021-03-03 10:22:42', '2021-05-04 07:53:03');

-- --------------------------------------------------------

--
-- Table structure for table `invoice_schemas`
--

CREATE TABLE `invoice_schemas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `format` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `start_from` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `number_of_digit` tinyint(4) DEFAULT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `prefix` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoice_schemas`
--

INSERT INTO `invoice_schemas` (`id`, `name`, `format`, `start_from`, `number_of_digit`, `is_default`, `prefix`, `created_at`, `updated_at`) VALUES
(3, 'yyyy', '1', '11', NULL, 0, 'SDC0', '2021-03-02 08:07:36', '2021-06-06 12:05:25'),
(6, 'sss', '1', '00', NULL, 0, 'SD', '2021-03-02 08:56:49', '2021-06-06 12:01:53'),
(9, 'test', '1', '78', NULL, 1, 'TEST', '2021-06-06 12:02:32', '2021-06-06 12:02:32');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(6, '2020_11_02_100600_create_units_table', 2),
(7, '2020_11_02_100636_create_taxes_table', 2),
(8, '2020_11_03_043450_create_categories_table', 3),
(9, '2020_11_03_050021_create_brands_table', 4),
(11, '2020_11_03_074719_create_product_variants_table', 5),
(12, '2020_11_03_081651_create_combo_products_table', 5),
(13, '2020_11_03_114308_create_product_images_table', 5),
(15, '2020_11_04_121711_create_bulk_variants_table', 7),
(16, '2020_11_04_121816_create_bulk_variant_children_table', 7),
(17, '2020_11_15_053541_create_general_settings_table', 8),
(18, '2020_11_15_082449_create_months_table', 9),
(20, '2020_11_16_091136_create_product_branches_table', 11),
(21, '2020_11_16_091328_create_product_branch_variants_table', 11),
(22, '2020_11_18_062546_create_suppliers_table', 12),
(23, '2020_11_18_104651_create_warehouses_table', 13),
(30, '2020_11_23_093915_create_supplier_products_table', 15),
(33, '2020_11_21_121749_create_purchases_table', 16),
(34, '2020_11_21_123246_create_purchase_products_table', 16),
(35, '2020_11_18_104742_create_product_warehouses_table', 17),
(36, '2020_11_18_104801_create_product_warehouse_variants_table', 17),
(47, '2020_12_07_043444_create_purchase_return_products_table', 21),
(60, '2020_12_14_122349_create_banks_table', 26),
(61, '2020_12_15_054045_create_account_types_table', 27),
(62, '2020_12_15_075538_create_accounts_table', 28),
(63, '2020_12_17_051129_create_expanse_categories_table', 29),
(70, '2020_12_17_072305_create_purchase_payments_table', 31),
(72, '2020_12_17_072328_create_expanse_payments_table', 33),
(73, '2020_12_28_084947_create_currencies_table', 34),
(79, '2020_11_23_093916_create_customer_groups_table', 36),
(80, '2020_11_28_081503_create_customers_table', 36),
(83, '2020_11_03_050022_create_warranties_table', 37),
(84, '2020_11_03_074127_create_products_table', 37),
(85, '2020_10_31_075426_create_branches_table', 38),
(86, '2020_12_31_045651_create_payment_methods_table', 38),
(88, '2020_12_31_073536_create_branch_payment_methods_table', 39),
(94, '2020_12_29_092109_create_stock_adjustment_products_table', 43),
(95, '2020_11_01_074931_create_roles_table', 44),
(96, '2020_11_01_074932_create_role_permissions_table', 44),
(97, '2020_10_31_075427_create_departments_table', 45),
(98, '2020_10_31_075428_create_designations_table', 45),
(100, '2021_02_01_162056_create_hrm_designations_table', 47),
(101, '2021_02_02_104702_create_hrm_department_table', 47),
(102, '2021_02_02_112758_create_hrm_leavetypes_table', 47),
(104, '2021_02_02_164845_create_hrm_allowance_table', 47),
(105, '2021_02_03_113338_create_hrm_leaves_table', 47),
(106, '2021_02_06_104136_create_hrm_shifts_table', 47),
(107, '2021_02_08_143446_create_hrm_attendances_table', 47),
(109, '2021_02_16_130523_create_allowance_employees_table', 48),
(117, '2021_02_17_175850_create_hrm_payrolls_table', 49),
(118, '2021_02_17_180827_create_hrm_payroll_allowances_table', 49),
(119, '2021_02_17_181252_create_hrm_payroll_deductions_table', 49),
(122, '2021_02_18_151938_create_hrm_payroll_payments_table', 50),
(123, '2021_02_27_175944_create_timezones_table', 51),
(125, '2021_03_02_114435_create_invoice_schemas_table', 52),
(126, '2021_03_02_160327_create_invoice_layouts_table', 53),
(128, '2021_03_25_114719_create_money_receipts_table', 54),
(129, '2020_11_28_095207_create_sales_table', 55),
(130, '2021_01_16_114746_create_product_opening_stocks_table', 56),
(131, '2020_11_01_074933_create_admin_and_users_table', 57),
(132, '2020_12_29_085907_create_stock_adjustments_table', 58),
(134, '2020_12_17_083221_create_cash_flows_table', 60),
(135, '2020_12_17_072250_create_sale_payments_table', 61),
(136, '2020_12_29_055123_create_customer_ledgers_table', 62),
(137, '2020_11_28_095232_create_sale_products_table', 63),
(138, '2021_04_14_104001_create_sv_devices_table', 64),
(139, '2021_04_14_104219_create_sv_device_models_table', 65),
(140, '2021_04_14_144159_create_sv_status_table', 65),
(141, '2021_04_15_124601_create_sv_job_sheets_table', 66),
(142, '2021_04_15_135702_create_sv_job_sheets_parts_table', 66),
(143, '2020_12_29_055052_create_supplier_ledgers_table', 67),
(144, '2020_12_07_043342_create_purchase_returns_table', 68),
(145, '2020_12_05_050553_create_sale_returns_table', 69),
(146, '2020_12_05_052157_create_sale_return_products_table', 69),
(147, '2021_01_20_094145_create_cash_registers_table', 70),
(148, '2021_01_20_094227_create_cash_register_transactions_table', 70),
(151, '2020_12_17_055604_create_expanses_table', 71),
(152, '2021_05_23_140809_create_expense_descriptions_table', 71),
(153, '2021_06_03_114200_add_shift_id_to_admin_and_users_table', 72),
(154, '2021_02_02_134638_create_hrm_holidays_table', 73),
(155, '2021_06_03_114704_remove_column_shift_id_from_hrm_attendances_table', 74),
(156, '2021_06_07_141127_create_xyz_table', 75),
(158, '2020_12_12_064712_create_transfer_stock_to_branches_table', 76),
(159, '2020_12_12_065407_create_transfer_stock_to_branch_products_table', 76),
(160, '2020_12_13_060916_create_transfer_stock_to_warehouses_table', 76),
(161, '2020_12_13_060924_create_transfer_stock_to_warehouse_products_table', 76),
(162, '2021_06_15_144538_create_asset_types_table', 77),
(163, '2021_06_15_162732_create_assets_table', 78),
(164, '2021_06_17_171914_create_card_types_table', 79),
(165, '2021_06_17_180811_add_column_card_type_id_from_sale_payments_table', 80),
(166, '2021_06_19_011217_create_barcode_settings_table', 81),
(167, '2021_06_19_212930_create_cash_counters_table', 81),
(168, '2021_06_19_213532_add_column_cash_counter_id_from_cash_registers_table', 82),
(169, '2021_06_22_150310_create_price_groups_table', 83);

-- --------------------------------------------------------

--
-- Table structure for table `money_receipts`
--

CREATE TABLE `money_receipts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` decimal(22,2) NOT NULL DEFAULT 0.00,
  `received_amount` decimal(22,2) NOT NULL DEFAULT 0.00,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `note` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_method` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_amount` tinyint(1) NOT NULL DEFAULT 0,
  `is_date` tinyint(1) NOT NULL DEFAULT 0,
  `is_note` tinyint(1) NOT NULL DEFAULT 0,
  `is_invoice_id` tinyint(1) NOT NULL DEFAULT 0,
  `date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `month` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `year` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_ts` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `money_receipts`
--

INSERT INTO `money_receipts` (`id`, `invoice_id`, `amount`, `received_amount`, `customer_id`, `branch_id`, `note`, `payment_method`, `status`, `is_amount`, `is_date`, `is_note`, `is_invoice_id`, `date`, `month`, `year`, `date_ts`, `created_at`, `updated_at`) VALUES
(34, '72317', '100000.00', '0.00', 39, 26, NULL, NULL, 'Pending', 0, 1, 1, 1, '13-04-2021', 'April', '2021', '2021-04-12 18:00:00', '2021-04-13 07:16:31', '2021-04-13 07:16:31'),
(35, '28786', '400.00', '400.00', 45, NULL, 'Thanks for paying us.', 'Cash', 'Completed', 0, 1, 1, 1, '17-04-2021', 'April', '2021', '2021-04-16 18:00:00', '2021-04-17 05:16:49', '2021-04-18 03:25:41'),
(36, '28152', '100.00', '100.00', 45, NULL, NULL, 'Cash', 'Completed', 0, 1, 1, 1, '18-04-2021', 'April', '2021', '2021-04-17 18:00:00', '2021-04-18 07:10:51', '2021-04-19 04:18:49'),
(37, '14595', '500.00', '500.00', 42, NULL, NULL, 'Cash', 'Completed', 0, 0, 0, 0, '19-04-2021', 'April', '2021', '2021-04-18 18:00:00', '2021-04-19 04:20:30', '2021-04-19 04:21:08'),
(38, '51163', '700.00', '700.00', 43, NULL, NULL, 'Cash', 'Completed', 0, 1, 1, 1, '19-04-2021', 'April', '2021', '2021-04-18 18:00:00', '2021-04-19 04:54:04', '2021-04-19 04:54:45'),
(39, '51958', '100.00', '100.00', 44, NULL, NULL, 'Cash', 'Completed', 0, 1, 1, 1, '19-04-2021', 'April', '2021', '2021-04-18 18:00:00', '2021-04-19 04:56:53', '2021-04-19 04:57:17'),
(40, '11511', '200.00', '200.00', 41, NULL, NULL, 'Cash', 'Completed', 0, 1, 1, 1, '19-04-2021', 'April', '2021', '2021-04-18 18:00:00', '2021-04-19 05:06:55', '2021-04-19 05:07:19'),
(41, '28591', '315.00', '315.00', 39, NULL, NULL, 'Cash', 'Completed', 0, 1, 1, 1, '19-04-2021', 'April', '2021', '2021-04-18 18:00:00', '2021-04-19 05:08:23', '2021-04-19 05:10:18'),
(42, '99137', '550.00', '400.00', 46, NULL, NULL, 'Cash', 'Completed', 0, 1, 1, 1, '19-04-2021', 'April', '2021', '2021-04-18 18:00:00', '2021-04-19 05:25:03', '2021-04-19 05:25:22'),
(43, '56914', '200.00', '200.00', 46, NULL, NULL, 'Bank-Transfer', 'Completed', 0, 1, 1, 1, '19-04-2021', 'April', '2021', '2021-04-18 18:00:00', '2021-04-19 05:33:57', '2021-04-19 05:34:12'),
(44, '98927', '1000.00', '100.00', 40, NULL, NULL, 'Cash', 'Completed', 0, 1, 1, 1, '21-04-2021', 'April', '2021', '2021-04-20 18:00:00', '2021-04-21 07:07:25', '2021-04-21 07:10:33'),
(45, '43425', '8000.00', '0.00', 43, NULL, NULL, NULL, 'Pending', 0, 1, 1, 1, '21-04-2021', 'April', '2021', '2021-04-20 18:00:00', '2021-04-21 08:18:07', '2021-04-21 08:18:07'),
(46, '59732', '1000.00', '0.00', 46, 26, NULL, NULL, 'Pending', 0, 1, 1, 1, '25-04-2021', 'April', '2021', '2021-04-24 18:00:00', '2021-04-25 09:22:43', '2021-04-25 09:22:43'),
(47, '94985', '1000.00', '0.00', 49, NULL, NULL, NULL, 'Pending', 0, 1, 1, 1, '05-05-2021', 'May', '2021', '2021-05-04 18:00:00', '2021-05-05 12:37:46', '2021-05-05 12:37:46');

-- --------------------------------------------------------

--
-- Table structure for table `months`
--

CREATE TABLE `months` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `month` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `months`
--

INSERT INTO `months` (`id`, `month`, `created_at`, `updated_at`) VALUES
(1, 'Januaray', NULL, NULL),
(2, 'February', NULL, NULL),
(3, 'March', NULL, NULL),
(4, 'April', NULL, NULL),
(5, 'May', NULL, NULL),
(6, 'June', NULL, NULL),
(7, 'July', NULL, NULL),
(8, 'August', NULL, NULL),
(9, 'September', NULL, NULL),
(10, 'October', NULL, NULL),
(11, 'November', NULL, NULL),
(12, 'December', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`email`, `token`, `created_at`) VALUES
('superadmin@gamil.com', '$2y$10$lHAmNtk5ndgUBbE67mnVBOiQMKSzXdi5t2eBQ0WhjhBKRKqX2MLta', '2021-05-25 08:27:05');

-- --------------------------------------------------------

--
-- Table structure for table `payment_methods`
--

CREATE TABLE `payment_methods` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `price_groups`
--

CREATE TABLE `price_groups` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `price_groups`
--

INSERT INTO `price_groups` (`id`, `name`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Whole sale Price', 'Whole sale Price description.', 'Active', '2021-06-22 11:12:08', '2021-06-22 11:36:46');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` tinyint(4) NOT NULL COMMENT '1=general,2=combo,3=digital',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `parent_category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `brand_id` bigint(20) UNSIGNED DEFAULT NULL,
  `unit_id` bigint(20) UNSIGNED DEFAULT NULL,
  `tax_id` bigint(20) UNSIGNED DEFAULT NULL,
  `warranty_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_cost` decimal(22,2) NOT NULL DEFAULT 0.00,
  `product_cost_with_tax` decimal(22,2) NOT NULL DEFAULT 0.00,
  `profit` decimal(22,2) NOT NULL DEFAULT 0.00,
  `product_price` decimal(22,2) NOT NULL DEFAULT 0.00,
  `offer_price` decimal(22,2) NOT NULL DEFAULT 0.00,
  `quantity` decimal(22,2) NOT NULL DEFAULT 0.00,
  `combo_price` decimal(22,2) NOT NULL DEFAULT 0.00,
  `alert_quantity` int(11) NOT NULL DEFAULT 0,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `is_combo` tinyint(1) NOT NULL DEFAULT 0,
  `is_variant` tinyint(1) NOT NULL DEFAULT 0,
  `is_show_in_ecom` tinyint(1) NOT NULL DEFAULT 0,
  `is_show_emi_on_pos` tinyint(1) NOT NULL DEFAULT 0,
  `is_for_sale` tinyint(1) NOT NULL DEFAULT 1,
  `attachment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `thumbnail_photo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'default.png',
  `expire_date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_details` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_purchased` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `barcode_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `weight` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_condition` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `number_of_sale` decimal(22,2) NOT NULL DEFAULT 0.00,
  `total_transfered` decimal(22,2) NOT NULL DEFAULT 0.00,
  `total_adjusted` decimal(22,2) NOT NULL DEFAULT 0.00,
  `custom_field_1` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_field_2` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_field_3` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `type`, `name`, `product_code`, `category_id`, `parent_category_id`, `brand_id`, `unit_id`, `tax_id`, `warranty_id`, `product_cost`, `product_cost_with_tax`, `profit`, `product_price`, `offer_price`, `quantity`, `combo_price`, `alert_quantity`, `is_featured`, `is_combo`, `is_variant`, `is_show_in_ecom`, `is_show_emi_on_pos`, `is_for_sale`, `attachment`, `thumbnail_photo`, `expire_date`, `product_details`, `is_purchased`, `barcode_type`, `weight`, `product_condition`, `status`, `number_of_sale`, `total_transfered`, `total_adjusted`, `custom_field_1`, `custom_field_2`, `custom_field_3`, `created_at`, `updated_at`) VALUES
(92, 1, 'Test', '1093156', 31, NULL, NULL, 3, NULL, NULL, '100.00', '100.00', '25.00', '125.00', '0.00', '95.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, '609776e6edadd.jpg', NULL, NULL, '0', 'CODE128', NULL, 'New', 1, '105.00', '0.00', '0.00', NULL, NULL, NULL, '2021-03-07 12:33:30', '2021-06-06 11:22:47'),
(93, 1, 'Imported Product', '121255', 34, NULL, 5, 4, 5, NULL, '100.00', '150.00', '10.00', '165.00', '0.00', '385.00', '0.00', 10, 0, 0, 0, 1, 1, 1, NULL, '609776955a46d.jpg', NULL, NULL, '0', 'CODE128', NULL, 'New', 1, '15.00', '0.00', '0.00', NULL, NULL, NULL, '2021-03-08 11:05:39', '2021-06-09 10:53:57'),
(94, 1, 'Imported Product', '121255', 34, NULL, 5, 4, 5, NULL, '200.00', '300.00', '10.00', '165.00', '0.00', '197.00', '0.00', 10, 0, 0, 0, 0, 0, 1, NULL, '609776a66a342.png', NULL, NULL, '0', 'CODE128', NULL, 'New', 1, '3.00', '0.00', '0.00', NULL, NULL, NULL, '2021-03-08 11:11:18', '2021-05-09 05:44:06'),
(95, 1, 'Samsung Galaxy A30', 'PO76699939', 34, NULL, 6, 3, 1, 2, '100.00', '105.00', '25.00', '125.00', '0.00', '550.00', '0.00', 620, 0, 0, 1, 0, 0, 1, NULL, '6097762fc053a.jpg', NULL, NULL, '0', 'CODE128', NULL, 'New', 1, '266.00', '0.00', '25.00', NULL, NULL, NULL, '2021-03-09 04:35:35', '2021-06-16 10:32:56'),
(96, 1, 'Ales', 'PO65696188', 35, NULL, 5, 3, 1, 2, '100.00', '105.00', '25.00', '125.00', '0.00', '328.00', '0.00', 0, 0, 0, 0, 0, 1, 1, NULL, '60928152875ef.jpg', NULL, NULL, '1', 'CODE128', '50KG', 'New', 1, '113.00', '0.00', '1.00', NULL, NULL, NULL, '2021-03-24 04:43:06', '2021-06-16 05:49:23'),
(97, 1, 'Test_product_1', 'PO39446545', 35, NULL, 5, 3, NULL, NULL, '100.00', '115.00', '25.00', '125.00', '0.00', '300.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'default.png', NULL, NULL, '1', 'CODE128', NULL, 'Used', 1, '0.00', '0.00', '0.00', NULL, NULL, NULL, '2021-04-01 09:43:26', '2021-04-03 07:57:46'),
(98, 1, 'Purchase-1', '75555', 35, NULL, 5, 3, NULL, 2, '100.00', '100.00', '25.00', '125.00', '0.00', '0.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'default.png', NULL, NULL, '1', 'CODE128', NULL, NULL, 1, '1.00', '0.00', '0.00', NULL, NULL, NULL, '2021-04-01 11:15:21', '2021-05-09 13:00:03'),
(99, 1, 'Variant Products', 'PO73361812', 35, NULL, 5, 3, NULL, NULL, '100.00', '100.00', '25.00', '125.00', '0.00', '0.00', '0.00', 0, 0, 0, 1, 1, 1, 0, NULL, 'default.png', NULL, NULL, '0', 'CODE128', NULL, 'New', 1, '0.00', '0.00', '0.00', NULL, NULL, NULL, '2021-04-01 11:53:45', '2021-04-27 07:49:07'),
(100, 1, 'Opening_product_1', '45587755', 35, NULL, 5, 3, NULL, 2, '1000.00', '1000.00', '25.00', '1250.00', '0.00', '102.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'default.png', NULL, NULL, '1', 'CODE128', NULL, NULL, 1, '-2.00', '0.00', '0.00', NULL, NULL, NULL, '2021-04-05 05:54:24', '2021-04-10 11:32:04'),
(101, 1, 'Opening_product_1', '45587755', 35, NULL, 5, 3, NULL, 2, '1000.00', '1000.00', '25.00', '1250.00', '0.00', '0.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'default.png', NULL, NULL, '1', 'CODE128', NULL, NULL, 1, '0.00', '0.00', '0.00', NULL, NULL, NULL, '2021-04-05 05:54:32', '2021-04-05 05:54:32'),
(102, 1, 'Opening_product_1', '45587755', 35, NULL, 5, 3, NULL, 2, '1000.00', '1000.00', '25.00', '1250.00', '0.00', '0.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'default.png', NULL, NULL, '1', 'CODE128', NULL, NULL, 1, '0.00', '0.00', '0.00', NULL, NULL, NULL, '2021-04-05 05:55:27', '2021-04-05 05:55:27'),
(103, 1, 'Opening_product_1', '45587755', 35, NULL, 5, 3, NULL, 2, '1000.00', '1000.00', '25.00', '1250.00', '0.00', '100.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'default.png', NULL, NULL, '1', 'CODE128', NULL, NULL, 1, '0.00', '0.00', '0.00', NULL, NULL, NULL, '2021-04-05 05:56:17', '2021-05-09 13:00:03'),
(104, 1, 'Opening_product_1', '45587755', 35, NULL, 5, 3, NULL, 2, '1000.00', '1000.00', '25.00', '1250.00', '0.00', '-2.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'default.png', NULL, NULL, '1', 'CODE128', NULL, NULL, 1, '2.00', '0.00', '0.00', NULL, NULL, NULL, '2021-04-05 05:58:38', '2021-06-03 05:22:08'),
(106, 1, 'Dell Brand PC', '7885555', 35, NULL, 5, 3, 1, 2, '100.00', '105.00', '25.00', '125.00', '0.00', '193.00', '0.00', 0, 0, 0, 0, 0, 1, 1, NULL, '609775917673f.jpg', NULL, 'This is a Brand PC.', '0', 'CODE128', NULL, 'New', 1, '10.00', '0.00', '0.00', NULL, NULL, NULL, '2021-04-05 06:03:29', '2021-06-03 05:22:10'),
(107, 1, 'Dell Brand PC', '7885555', 35, NULL, 5, 3, 1, 2, '100.00', '105.00', '25.00', '125.00', '0.00', '99.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, '609775a2bd344.png', NULL, 'This is a Brand PC.', '0', 'CODE128', NULL, 'New', 1, '2.00', '0.00', '0.00', NULL, NULL, NULL, '2021-04-05 06:03:40', '2021-06-03 05:22:08'),
(108, 1, '60/80 Mash', '788554555', 35, NULL, 5, 3, 1, 2, '100.00', '105.00', '25.00', '125.00', '0.00', '1047.00', '0.00', 0, 0, 0, 0, 0, 1, 1, NULL, '609280fc422fd.jpg', NULL, NULL, '0', 'CODE128', NULL, 'New', 1, '23.00', '0.00', '20.00', NULL, NULL, NULL, '2021-04-05 06:05:35', '2021-06-09 08:56:54'),
(109, 1, '60/80 Mash', '788554555', 46, NULL, 5, 3, 1, 2, '100.00', '105.00', '25.00', '125.00', '0.00', '-1.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, '609281217b756.png', NULL, NULL, '0', 'CODE128', NULL, 'New', 1, '1.00', '0.00', '0.00', NULL, NULL, NULL, '2021-04-05 06:05:41', '2021-06-05 12:28:27'),
(110, 1, '60/80 Mash', '788554555', 35, NULL, 5, 3, 1, 2, '100.00', '105.00', '25.00', '125.00', '0.00', '100.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, '6092812e1de39.jpg', NULL, NULL, '0', 'CODE128', NULL, 'New', 1, '3.00', '0.00', '0.00', NULL, NULL, NULL, '2021-04-05 06:06:39', '2021-06-03 05:22:08'),
(112, 1, '60/80 Mash', '788554555', 35, NULL, 5, 3, 1, 2, '100.00', '105.00', '25.00', '125.00', '0.00', '100.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, '60928138827b9.jpg', NULL, NULL, '0', 'CODE128', NULL, 'New', 1, '0.00', '0.00', '0.00', NULL, NULL, NULL, '2021-04-05 06:09:55', '2021-05-09 13:00:03'),
(113, 1, '60/80 Mash', '788554555', 35, NULL, 5, 3, 1, 2, '100.00', '105.00', '25.00', '125.00', '0.00', '39.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, '609281449ee8f.png', NULL, NULL, '0', 'CODE128', NULL, 'New', 1, '61.00', '0.00', '0.00', NULL, NULL, NULL, '2021-04-05 06:13:35', '2021-06-06 11:22:47'),
(114, 1, 'JBL Ear Phone', '788554', 35, NULL, 5, 3, 1, 2, '100.00', '105.00', '25.00', '125.00', '0.00', '92.00', '0.00', 0, 0, 0, 0, 1, 1, 1, NULL, '609777725f78d.jpg', NULL, NULL, '0', 'CODE128', NULL, 'New', 1, '8.00', '0.00', '0.00', NULL, NULL, NULL, '2021-04-05 06:36:03', '2021-06-03 05:22:08'),
(115, 1, 'Realme Ear Phone', 'PO83743439', 34, NULL, 5, 3, NULL, 2, '100.00', '100.00', '25.00', '125.00', '0.00', '398.00', '0.00', 0, 0, 0, 0, 1, 1, 1, NULL, '6097774db2660.jpg', NULL, NULL, '0', 'CODE128', NULL, 'New', 1, '15.00', '0.00', '1.00', NULL, NULL, NULL, '2021-04-05 06:41:05', '2021-06-16 10:32:56'),
(116, 1, 'Sony Bravia TV', '755545269', 34, NULL, 5, 3, 5, 6, '40000.00', '60000.00', '25.00', '50000.00', '0.00', '98.00', '0.00', 0, 0, 0, 0, 1, 1, 1, NULL, 'default.png', NULL, 'This is a TV.', '1', 'CODE128', NULL, NULL, 1, '2.00', '0.00', '0.00', NULL, NULL, NULL, '2021-04-05 07:44:45', '2021-05-09 13:00:03'),
(117, 1, 'Test-2', '6857', 35, NULL, 5, 3, 1, NULL, '100.00', '105.00', '25.00', '125.00', '0.00', '0.00', '0.00', 0, 0, 0, 0, 1, 1, 1, NULL, 'default.png', NULL, NULL, '1', 'CODE128', NULL, NULL, 1, '0.00', '0.00', '0.00', NULL, NULL, NULL, '2021-04-05 07:47:17', '2021-04-05 07:47:17'),
(119, 1, 'Test-4', '878998', 35, NULL, 5, 3, 1, NULL, '10000.00', '10500.00', '10.00', '11000.00', '0.00', '85.00', '0.00', 0, 0, 0, 0, 1, 1, 1, NULL, '6097771fcaf5e.png', NULL, NULL, '0', 'CODE128', NULL, 'New', 1, '15.00', '0.00', '0.00', NULL, NULL, NULL, '2021-04-05 07:53:59', '2021-06-03 05:22:10'),
(122, 1, 'Branch B Product 3', '88321458556', 34, NULL, 5, 3, NULL, 2, '182000.00', '182000.00', '25.00', '227500.00', '0.00', '100.00', '0.00', 0, 0, 0, 0, 1, 1, 1, NULL, '6097754d0be79.jpg', NULL, NULL, '0', 'CODE128', NULL, 'New', 1, '0.00', '0.00', '0.00', NULL, NULL, NULL, '2021-04-05 08:12:57', '2021-05-09 05:38:21'),
(123, 1, 'JBL Head Phone', 'JBL687', 35, NULL, NULL, 3, NULL, NULL, '100.00', '210.00', '0.00', '150.00', '0.00', '100.00', '0.00', 0, 0, 0, 0, 0, 1, 1, NULL, 'default.png', NULL, NULL, '1', 'CODE128', NULL, NULL, 1, '1.00', '0.00', '0.00', NULL, NULL, NULL, '2021-04-11 04:20:41', '2021-04-12 12:52:09'),
(124, 1, 'RealMax Ear Phone', 'RMX867687', 35, NULL, 5, 3, 1, 3, '700.00', '735.00', '0.00', '850.00', '0.00', '124.00', '0.00', 0, 0, 0, 0, 0, 1, 1, NULL, '609776cd05715.jpg', NULL, 'This is an era phone.', '0', 'CODE128', NULL, 'New', 1, '77.00', '0.00', '1.00', NULL, NULL, NULL, '2021-04-11 04:31:27', '2021-06-09 07:22:00'),
(125, 1, 'Food', 'F35786787', 35, NULL, 9, 3, 1, 2, '100.00', '105.00', '0.00', '150.00', '0.00', '3.00', '0.00', 0, 0, 0, 0, 0, 1, 1, NULL, '6097766c7d48f.jpg', NULL, NULL, '0', 'CODE128', NULL, 'New', 1, '0.00', '0.00', '0.00', NULL, NULL, NULL, '2021-04-11 04:42:08', '2021-05-09 05:43:08'),
(126, 1, 'Foot Ball', 'FB687887', 35, NULL, 9, 3, NULL, 2, '100.00', '100.00', '0.00', '150.00', '0.00', '1.00', '0.00', 0, 0, 0, 0, 1, 1, 1, NULL, '609776857eb52.jpg', NULL, NULL, '0', 'CODE128', NULL, 'New', 1, '0.00', '0.00', '0.00', NULL, NULL, NULL, '2021-04-11 04:45:50', '2021-05-09 05:43:33'),
(127, 1, 'MUM Mineral Water.', '755588', 34, NULL, NULL, 3, 1, NULL, '12.00', '12.60', '0.00', '15.00', '0.00', '-20.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, '60977702d499c.png', NULL, NULL, '0', 'CODE128', NULL, 'New', 1, '20.00', '0.00', '0.00', NULL, NULL, NULL, '2021-04-13 07:00:34', '2021-05-09 05:45:38'),
(129, 1, 'Milk', '8787', 35, NULL, NULL, 4, NULL, NULL, '40.00', '40.00', '0.00', '45.00', '0.00', '-1.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'default.png', NULL, NULL, '1', 'CODE128', NULL, NULL, 1, '1.00', '0.00', '0.00', NULL, NULL, NULL, '2021-04-17 04:09:48', '2021-04-17 04:42:48'),
(130, 1, 'Baby Locon', '7885554', 39, 46, NULL, 3, 1, NULL, '100.00', '105.00', '0.00', '150.00', '0.00', '99.00', '0.00', 0, 0, 0, 0, 0, 1, 1, NULL, '60977537bb02c.jpg', NULL, NULL, '0', 'CODE128', NULL, 'New', 1, '1.00', '0.00', '0.00', NULL, NULL, NULL, '2021-04-24 06:32:58', '2021-06-03 05:22:08'),
(131, 1, 'Samsung Product', '78855444', 39, 46, 5, 3, 1, 2, '100.00', '105.00', '10.00', '110.00', '0.00', '0.00', '0.00', 0, 0, 0, 0, 0, 1, 1, NULL, '6097765890aed.jpg', NULL, NULL, '0', 'CODE128', NULL, 'New', 1, '1.00', '0.00', '0.00', NULL, NULL, NULL, '2021-04-25 03:44:49', '2021-05-09 05:42:48'),
(132, 1, 'Test_product_1', '68876565654', 39, 46, 5, 3, 1, 2, '100.00', '105.00', '12.00', '112.00', '0.00', '-1.00', '0.00', 0, 0, 0, 0, 0, 1, 1, NULL, 'default.png', NULL, NULL, '1', 'CODE128', NULL, NULL, 1, '1.00', '0.00', '0.00', NULL, NULL, NULL, '2021-04-26 09:28:54', '2021-04-27 06:11:40'),
(133, 1, 'Carter Salazar', 'Laboris dolore fugia', 35, NULL, NULL, 4, 1, NULL, '100.00', '105.00', '10.00', '110.00', '0.00', '-3.00', '0.00', 188, 0, 0, 0, 1, 1, 1, NULL, '60977572d2c59.jpg', NULL, 'Ea quisquam dignissi', '0', 'CODE39', NULL, 'New', 1, '3.00', '0.00', '0.00', NULL, NULL, NULL, '2021-04-26 09:30:27', '2021-06-06 08:29:28'),
(134, 1, 'Carter Salazar', 'Laboris dolore fugia', 35, NULL, NULL, 4, 1, NULL, '100.00', '105.00', '10.00', '110.00', '0.00', '-3.00', '0.00', 188, 0, 0, 0, 1, 1, 1, NULL, '6097757f500ae.jpg', NULL, 'Ea quisquam dignissi', '0', 'CODE39', NULL, 'New', 1, '3.00', '0.00', '0.00', NULL, NULL, NULL, '2021-04-26 09:32:07', '2021-06-06 08:29:28'),
(135, 1, 'Mouse A4Tech OP-620D 2X Click USB', 'OP-620D', 47, NULL, 17, 3, NULL, NULL, '280.00', '280.00', '0.00', '400.00', '0.00', '16.00', '0.00', 0, 0, 0, 0, 0, 1, 1, NULL, 'default.png', NULL, NULL, '1', 'CODE128', NULL, 'New', 1, '2.00', '0.00', '0.00', NULL, NULL, NULL, '2021-04-28 05:20:53', '2021-05-11 03:53:30'),
(136, 1, 'Keyboard A4Tech KRS-83 Multimedia With Bangala Black Color', 'KRS-83', 47, NULL, 17, 3, NULL, NULL, '570.00', '570.00', '22.81', '700.00', '0.00', '16.00', '0.00', 0, 0, 0, 0, 0, 1, 1, NULL, 'default.png', NULL, NULL, '1', 'CODE128', NULL, 'New', 1, '2.00', '0.00', '0.00', NULL, NULL, NULL, '2021-04-28 05:25:22', '2021-05-11 03:53:30'),
(137, 1, 'Cable Power OEM 3 Pin 1.5', 'OEM704', 47, NULL, NULL, 3, NULL, NULL, '130.00', '130.00', '130.77', '300.00', '0.00', '33.00', '0.00', 0, 0, 0, 0, 0, 1, 1, NULL, '6097756198761.jpg', NULL, NULL, '0', 'CODE128', NULL, 'New', 1, '3.00', '0.00', '0.00', NULL, NULL, NULL, '2021-04-28 05:34:40', '2021-06-07 11:41:46'),
(138, 1, 'WD 120GB SSD', 'PO62146496', 47, NULL, 18, 3, NULL, 11, '1650.00', '1650.00', '69.70', '2800.00', '0.00', '14.00', '0.00', 0, 0, 0, 0, 0, 1, 1, NULL, 'default.png', NULL, NULL, '1', 'CODE128', NULL, 'New', 1, '2.00', '0.00', '0.00', NULL, NULL, NULL, '2021-04-28 08:11:54', '2021-05-11 03:53:30'),
(139, 1, 'Apacer PNATHER 240GB', 'PO72224685', 47, NULL, 19, 3, NULL, 11, '2850.00', '2850.00', '36.84', '3900.00', '0.00', '2.00', '0.00', 0, 0, 0, 0, 0, 1, 1, NULL, '6092815da83ff.jpg', NULL, NULL, '0', 'CODE128', NULL, 'New', 1, '1.00', '0.00', '0.00', NULL, NULL, NULL, '2021-04-28 08:13:33', '2021-05-11 03:53:30'),
(140, 1, 'Lenovo M91P Brand PC', 'M91P', 47, NULL, 20, 3, NULL, NULL, '18500.00', '18500.00', '21.62', '22500.00', '0.00', '104.00', '0.00', 0, 0, 0, 0, 0, 1, 1, NULL, 'default.png', NULL, NULL, '1', 'CODE128', NULL, 'New', 1, '2.00', '0.00', '0.00', NULL, NULL, NULL, '2021-04-28 08:16:20', '2021-05-11 03:53:30'),
(141, 1, 'Max Green MGO-PX1k 1KVA Standard Backup Online UPS', 'MGO-PX1k', 47, NULL, 21, 3, NULL, 8, '13000.00', '13000.00', '42.31', '18500.00', '0.00', '104.00', '0.00', 0, 0, 0, 0, 0, 1, 1, NULL, 'default.png', NULL, NULL, '1', 'CODE128', NULL, 'New', 1, '2.00', '0.00', '0.00', NULL, NULL, NULL, '2021-04-28 08:18:33', '2021-05-11 03:53:30'),
(142, 1, 'Pos Product-1', 'POS788555', 39, 46, 5, 3, 1, 2, '500.00', '525.00', '20.00', '600.00', '0.00', '-2.00', '0.00', 20, 0, 0, 0, 0, 1, 1, NULL, 'default.png', NULL, NULL, '1', 'CODE128', NULL, NULL, 1, '2.00', '0.00', '0.00', NULL, NULL, NULL, '2021-05-09 06:59:18', '2021-06-03 05:22:08'),
(143, 1, 'Elaine Winters', 'Pos-6565545', 39, 46, 5, 3, 1, 9, '100.00', '105.00', '100.00', '200.00', '0.00', '-1.00', '0.00', 10, 0, 0, 0, 0, 0, 1, NULL, 'default.png', NULL, 'Vitae repellendus A', '1', 'CODE128', NULL, NULL, 1, '1.00', '0.00', '0.00', NULL, NULL, NULL, '2021-05-09 07:00:24', '2021-06-03 05:22:08'),
(144, 1, 'Elaine Winters', 'Pos-6565545', 39, 46, 5, 3, 1, 9, '100.00', '105.00', '100.00', '200.00', '0.00', '0.00', '0.00', 10, 0, 0, 0, 0, 0, 1, NULL, 'default.png', NULL, 'Vitae repellendus A', '1', 'CODE128', NULL, NULL, 1, '0.00', '0.00', '0.00', NULL, NULL, NULL, '2021-05-09 07:07:02', '2021-05-09 07:07:02'),
(145, 1, 'POS Product-2', 'POS78554485', 39, 46, 5, 3, 1, 2, '500.00', '525.00', '15.00', '575.00', '0.00', '-1.00', '0.00', 0, 0, 0, 0, 0, 1, 1, NULL, 'default.png', NULL, NULL, '1', 'CODE128', NULL, NULL, 1, '1.00', '0.00', '0.00', NULL, NULL, NULL, '2021-05-09 07:19:34', '2021-05-09 13:00:03'),
(146, 1, 'Pos Product-4', 'POS755544', 47, NULL, 5, 3, NULL, 2, '100.00', '100.00', '0.00', '100.00', '0.00', '-1.00', '0.00', 0, 0, 0, 0, 0, 1, 1, NULL, 'default.png', NULL, NULL, '1', 'CODE128', NULL, NULL, 1, '1.00', '0.00', '0.00', NULL, NULL, NULL, '2021-05-09 07:28:29', '2021-06-03 05:22:08'),
(147, 1, 'Pos product-5', 'POS7521126', 47, NULL, NULL, 3, 1, NULL, '100.00', '105.00', '0.00', '100.00', '0.00', '-1.00', '0.00', 0, 0, 0, 0, 0, 1, 1, NULL, 'default.png', NULL, NULL, '1', 'CODE128', NULL, NULL, 1, '1.00', '0.00', '0.00', NULL, NULL, NULL, '2021-05-09 07:32:22', '2021-05-09 13:00:03'),
(148, 1, 'SpeedDigit Computer', 'SDC7884445', 47, NULL, 16, 3, 1, 2, '50000.00', '52500.00', '15.00', '57500.00', '0.00', '0.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'default.png', NULL, NULL, '1', 'CODE128', NULL, NULL, 1, '0.00', '0.00', '0.00', NULL, NULL, NULL, '2021-05-11 04:00:09', '2021-05-11 04:00:09'),
(149, 1, 'SD Computer', 'SDC1254878', 47, NULL, 17, 3, NULL, 2, '40000.00', '42000.00', '15.00', '46000.00', '0.00', '1.00', '0.00', 0, 0, 0, 0, 0, 1, 1, NULL, 'default.png', '1970-01-01', NULL, '0', 'CODE128', NULL, 'New', 1, '-1.00', '0.00', '0.00', NULL, NULL, NULL, '2021-05-11 04:07:06', '2021-06-17 09:13:35'),
(150, 1, 'MOG', '57153775', 48, NULL, NULL, 3, NULL, NULL, '100.00', '100.00', '10.00', '110.00', '0.00', '0.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'default.png', NULL, NULL, '0', 'CODE128', NULL, 'New', 1, '0.00', '0.00', '0.00', NULL, NULL, NULL, '2021-06-07 05:09:18', '2021-06-07 05:09:18'),
(151, 1, 'Mog-2', '83313428', 48, NULL, 5, 3, 1, NULL, '100.00', '105.00', '20.00', '120.00', '0.00', '0.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'default.png', NULL, NULL, '0', 'CODE128', NULL, 'New', 1, '0.00', '0.00', '0.00', NULL, NULL, NULL, '2021-06-07 05:22:03', '2021-06-07 05:22:03'),
(152, 1, 'Return product', '67238314', 41, NULL, 5, 3, 1, 2, '100.00', '105.00', '20.00', '120.00', '0.00', '0.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'default.png', NULL, NULL, '0', 'CODE128', NULL, 'New', 1, '0.00', '0.00', '0.00', NULL, NULL, NULL, '2021-06-09 12:27:34', '2021-06-09 12:27:34'),
(153, 1, 'Samsung A32', 'SMA32', 47, NULL, 6, 3, NULL, 2, '2000.00', '2000.00', '40.00', '2800.00', '0.00', '0.00', '0.00', 0, 0, 0, 1, 0, 1, 1, NULL, 'default.png', '1970-01-01', 'This samsung A32 Mobile Phone.', '0', 'CODE128', NULL, 'New', 1, '0.00', '0.00', '0.00', NULL, NULL, NULL, '2021-06-17 04:50:21', '2021-06-17 08:48:42'),
(154, 1, 'Dell Vestro Laptop Core i3 15\'6 Inc.', '75372737', 47, NULL, 26, 3, 1, 11, '40000.00', '42000.00', '20.00', '48000.00', '0.00', '96.00', '0.00', 0, 0, 0, 0, 1, 1, 1, NULL, 'default.png', '1970-01-01', '<h1><span style=\"background-color: rgb(255, 204, 51); color: rgb(0, 0, 102);\">This is a Laptop.</span></h1>', '1', 'CODE128', '100 kg', 'New', 1, '4.00', '0.00', '0.00', 'This is a hit product.', NULL, NULL, '2021-06-17 05:24:47', '2021-06-17 10:44:49');

-- --------------------------------------------------------

--
-- Table structure for table `product_branches`
--

CREATE TABLE `product_branches` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_quantity` decimal(22,2) DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_branches`
--

INSERT INTO `product_branches` (`id`, `branch_id`, `product_id`, `product_quantity`, `created_at`, `updated_at`) VALUES
(144, 24, 92, '0.00', '2021-03-07 12:33:38', '2021-03-10 07:34:20'),
(145, 24, 93, '187.00', '2021-03-08 11:05:39', '2021-06-09 11:07:47'),
(146, 24, 94, '196.00', '2021-03-08 11:11:18', '2021-06-09 10:54:40'),
(147, 24, 95, '148.00', '2021-03-09 04:35:48', '2021-06-09 11:07:47'),
(148, 24, 96, '32.00', '2021-03-24 04:43:18', '2021-06-10 11:12:10'),
(149, 25, 96, '7.00', '2021-03-28 05:17:55', '2021-04-06 10:15:48'),
(150, 25, 95, '197.00', '2021-03-28 09:54:02', '2021-04-06 10:15:48'),
(151, 25, 93, '99.00', '2021-03-28 09:54:10', '2021-04-06 10:15:48'),
(152, 26, 96, '193.00', '2021-03-29 07:04:52', '2021-06-07 12:50:53'),
(153, 26, 93, '98.00', '2021-03-29 07:09:15', '2021-04-03 07:10:20'),
(154, 26, 95, '475.00', '2021-03-29 07:19:02', '2021-06-09 07:03:59'),
(155, 24, 97, '100.00', '2021-04-03 07:57:46', '2021-04-03 07:57:46'),
(156, 25, 97, '100.00', '2021-04-03 07:57:46', '2021-04-03 07:57:46'),
(157, 26, 97, '100.00', '2021-04-03 07:57:46', '2021-04-03 07:57:46'),
(158, 24, 115, '99.00', '2021-04-05 06:42:10', '2021-06-07 13:15:30'),
(159, 25, 115, '100.00', '2021-04-05 06:42:10', '2021-04-05 06:42:38'),
(160, 26, 115, '106.00', '2021-04-05 06:42:10', '2021-04-25 11:58:23'),
(161, 25, 116, '20.00', '2021-04-05 07:44:45', '2021-04-05 07:44:45'),
(162, 25, 117, '100.00', '2021-04-05 07:47:17', '2021-04-05 07:47:17'),
(166, 25, 122, '1.00', '2021-04-05 08:12:57', '2021-04-05 08:12:57'),
(167, 26, 114, '96.00', '2021-04-11 06:18:27', '2021-05-05 08:48:37'),
(168, 26, 124, '47.00', '2021-04-11 06:18:27', '2021-06-07 12:50:41'),
(170, 26, 106, '90.00', '2021-04-11 06:18:27', '2021-05-05 08:48:38'),
(171, 26, 119, '86.00', '2021-04-11 06:18:27', '2021-05-05 08:48:38'),
(172, 26, 127, '180.00', '2021-04-13 07:00:34', '2021-04-13 07:01:10'),
(173, 24, 141, '99.00', '2021-04-28 08:37:51', '2021-06-09 10:54:40'),
(174, 24, 137, '8.00', '2021-04-28 08:37:51', '2021-06-09 11:05:21'),
(175, 24, 139, '0.00', '2021-04-28 08:37:51', '2021-05-11 03:53:30'),
(176, 24, 138, '10.00', '2021-04-28 08:37:51', '2021-05-11 03:53:30'),
(177, 24, 140, '100.00', '2021-04-28 08:37:51', '2021-05-11 03:53:30'),
(178, 24, 135, '4.00', '2021-04-28 08:37:51', '2021-05-11 03:53:30'),
(179, 24, 136, '4.00', '2021-04-28 08:37:51', '2021-05-11 03:53:30'),
(180, 24, 148, '100.00', '2021-05-11 04:00:09', '2021-05-11 04:00:09'),
(181, 24, 149, '101.00', '2021-05-11 04:07:06', '2021-05-11 04:19:34'),
(182, 24, 124, '0.00', '2021-06-07 13:15:00', '2021-06-09 10:53:25');

-- --------------------------------------------------------

--
-- Table structure for table `product_branch_variants`
--

CREATE TABLE `product_branch_variants` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_variant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `variant_quantity` decimal(22,2) DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_branch_variants`
--

INSERT INTO `product_branch_variants` (`id`, `product_branch_id`, `product_id`, `product_variant_id`, `variant_quantity`, `created_at`, `updated_at`) VALUES
(41, 147, 95, 40, '53.00', '2021-03-09 04:35:48', '2021-06-09 11:07:10'),
(42, 147, 95, 41, '95.00', '2021-03-09 04:35:48', '2021-06-09 11:07:47'),
(43, 150, 95, 40, '197.00', '2021-03-28 09:54:10', '2021-04-06 10:15:48'),
(44, 150, 95, 41, '-1.00', '2021-03-28 09:54:10', '2021-04-06 10:15:48'),
(45, 154, 95, 40, '272.00', '2021-03-29 07:19:02', '2021-06-07 12:50:53'),
(46, 154, 95, 41, '174.00', '2021-03-29 07:19:02', '2021-06-09 07:03:59');

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `image`, `created_at`, `updated_at`) VALUES
(7, NULL, '5fb20b4de8c7c.jpg', '2020-11-15 23:17:02', '2020-11-15 23:17:02'),
(8, NULL, '5fb20b4e6ece3.png', '2020-11-15 23:17:02', '2020-11-15 23:17:02'),
(9, NULL, '5fb20b56cf6c4.jpg', '2020-11-15 23:17:10', '2020-11-15 23:17:10'),
(10, NULL, '5fb20b56da651.png', '2020-11-15 23:17:10', '2020-11-15 23:17:10'),
(11, NULL, '5fb20b9d217d1.jpg', '2020-11-15 23:18:21', '2020-11-15 23:18:21'),
(12, NULL, '5fb20b9d316c5.png', '2020-11-15 23:18:21', '2020-11-15 23:18:21'),
(33, NULL, '5fb224330c206.jpg', '2020-11-16 01:03:15', '2020-11-16 01:03:15'),
(34, NULL, '5fb224331fd5d.png', '2020-11-16 01:03:15', '2020-11-16 01:03:15'),
(38, 109, '6092810680be4.png', '2021-05-05 11:27:02', '2021-05-05 11:27:02');

-- --------------------------------------------------------

--
-- Table structure for table `product_opening_stocks`
--

CREATE TABLE `product_opening_stocks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `warehouse_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_variant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `unit_cost_exc_tax` decimal(22,2) NOT NULL DEFAULT 0.00,
  `quantity` decimal(22,2) NOT NULL DEFAULT 0.00,
  `subtotal` decimal(22,2) NOT NULL DEFAULT 0.00,
  `lot_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_opening_stocks`
--

INSERT INTO `product_opening_stocks` (`id`, `branch_id`, `warehouse_id`, `product_id`, `product_variant_id`, `unit_cost_exc_tax`, `quantity`, `subtotal`, `lot_no`, `created_at`, `updated_at`) VALUES
(26, NULL, 7, 95, 40, '200.00', '120.00', '24000.00', NULL, '2021-04-07 08:02:56', '2021-04-07 08:06:21'),
(27, NULL, 7, 95, 41, '100.00', '120.00', '12000.00', NULL, '2021-04-07 08:05:33', '2021-04-07 08:06:21'),
(28, NULL, 7, 108, NULL, '100.00', '1000.00', '100000.00', NULL, '2021-04-10 11:12:34', '2021-04-20 11:34:54'),
(29, NULL, 7, 110, NULL, '100.00', '100.00', '10000.00', NULL, '2021-04-10 11:12:40', '2021-04-10 11:12:40'),
(30, NULL, 7, 112, NULL, '100.00', '100.00', '10000.00', NULL, '2021-04-10 11:12:45', '2021-04-10 11:12:45'),
(31, NULL, 7, 113, NULL, '100.00', '100.00', '10000.00', NULL, '2021-04-10 11:12:50', '2021-04-10 11:12:50'),
(34, NULL, 7, 122, NULL, '182000.00', '100.00', '18200000.00', NULL, '2021-04-10 11:13:10', '2021-04-10 11:13:10'),
(35, NULL, 7, 106, NULL, '100.00', '100.00', '10000.00', NULL, '2021-04-10 11:13:15', '2021-04-10 11:13:15'),
(36, NULL, 7, 100, NULL, '1000.00', '100.00', '100000.00', NULL, '2021-04-10 11:13:25', '2021-04-10 11:13:25'),
(37, NULL, 7, 103, NULL, '1000.00', '100.00', '100000.00', NULL, '2021-04-10 11:13:34', '2021-04-10 11:13:34'),
(38, 24, NULL, 108, NULL, '100.00', '0.00', '0.00', NULL, '2021-04-10 11:26:16', '2021-04-10 11:26:16'),
(39, 25, NULL, 108, NULL, '100.00', '0.00', '0.00', NULL, '2021-04-10 11:26:16', '2021-04-10 11:26:16'),
(40, 26, NULL, 108, NULL, '100.00', '0.00', '0.00', NULL, '2021-04-10 11:26:16', '2021-04-10 11:26:16'),
(41, NULL, 7, 96, NULL, '100.00', '100.00', '10000.00', NULL, '2021-04-11 05:17:43', '2021-04-11 05:17:43'),
(42, NULL, 7, 107, NULL, '100.00', '100.00', '10000.00', NULL, '2021-04-11 05:18:01', '2021-04-11 05:18:01'),
(43, NULL, 7, 123, NULL, '100.00', '100.00', '10000.00', NULL, '2021-04-11 05:18:16', '2021-04-11 05:18:16'),
(45, NULL, 7, 124, NULL, '700.00', '100.00', '70000.00', NULL, '2021-04-11 05:18:42', '2021-04-11 05:18:42'),
(46, NULL, 7, 116, NULL, '40000.00', '100.00', '4000000.00', NULL, '2021-04-11 05:18:54', '2021-04-11 05:18:54'),
(48, 26, NULL, 127, NULL, '12.00', '200.00', '2400.00', NULL, '2021-04-13 07:00:34', '2021-04-13 07:00:34'),
(50, NULL, 7, 129, NULL, '40.00', '10000.00', '400000.00', NULL, '2021-04-17 04:09:48', '2021-04-17 04:09:48'),
(51, NULL, 7, 132, NULL, '100.00', '10.00', '1000.00', NULL, '2021-04-26 09:28:54', '2021-04-26 09:28:54'),
(52, NULL, 7, 133, NULL, '100.00', '6.00', '186.00', NULL, '2021-04-26 09:30:27', '2021-04-26 09:30:27'),
(53, NULL, 7, 134, NULL, '100.00', '6.00', '186.00', NULL, '2021-04-26 09:32:07', '2021-04-26 09:32:07'),
(54, 24, NULL, 135, NULL, '280.00', '0.00', '0.00', NULL, '2021-04-28 05:21:58', '2021-04-28 05:21:58'),
(55, 25, NULL, 135, NULL, '280.00', '0.00', '0.00', NULL, '2021-04-28 05:21:58', '2021-04-28 05:21:58'),
(56, 26, NULL, 135, NULL, '280.00', '0.00', '0.00', NULL, '2021-04-28 05:21:58', '2021-04-28 05:21:58'),
(60, 24, NULL, 106, NULL, '100.00', '0.00', '0.00', NULL, '2021-04-28 05:39:40', '2021-04-28 05:39:40'),
(61, 25, NULL, 106, NULL, '100.00', '0.00', '0.00', NULL, '2021-04-28 05:39:40', '2021-04-28 05:39:40'),
(62, 26, NULL, 106, NULL, '100.00', '0.00', '0.00', NULL, '2021-04-28 05:39:40', '2021-04-28 05:39:40'),
(63, 24, NULL, 130, NULL, '100.00', '0.00', '0.00', NULL, '2021-04-28 05:39:57', '2021-04-28 05:39:57'),
(64, 25, NULL, 130, NULL, '100.00', '0.00', '0.00', NULL, '2021-04-28 05:39:57', '2021-04-28 05:39:57'),
(65, 26, NULL, 130, NULL, '100.00', '0.00', '0.00', NULL, '2021-04-28 05:39:57', '2021-04-28 05:39:57'),
(66, NULL, 7, 130, NULL, '100.00', '100.00', '10000.00', NULL, '2021-04-28 05:40:18', '2021-04-28 05:40:18'),
(67, NULL, 7, 92, NULL, '100.00', '100.00', '10000.00', NULL, '2021-04-29 12:22:48', '2021-04-29 12:23:22'),
(68, 24, NULL, 109, NULL, '100.00', '0.00', '0.00', NULL, '2021-05-05 11:27:02', '2021-05-05 11:27:02'),
(69, 25, NULL, 109, NULL, '100.00', '0.00', '0.00', NULL, '2021-05-05 11:27:02', '2021-05-05 11:27:02'),
(70, 26, NULL, 109, NULL, '100.00', '0.00', '0.00', NULL, '2021-05-05 11:27:02', '2021-05-05 11:27:02'),
(71, 24, NULL, 110, NULL, '100.00', '0.00', '0.00', NULL, '2021-05-05 11:27:42', '2021-05-05 11:27:42'),
(72, 25, NULL, 110, NULL, '100.00', '0.00', '0.00', NULL, '2021-05-05 11:27:42', '2021-05-05 11:27:42'),
(73, 26, NULL, 110, NULL, '100.00', '0.00', '0.00', NULL, '2021-05-05 11:27:42', '2021-05-05 11:27:42'),
(74, 24, NULL, 112, NULL, '100.00', '0.00', '0.00', NULL, '2021-05-05 11:27:52', '2021-05-05 11:27:52'),
(75, 25, NULL, 112, NULL, '100.00', '0.00', '0.00', NULL, '2021-05-05 11:27:52', '2021-05-05 11:27:52'),
(76, 26, NULL, 112, NULL, '100.00', '0.00', '0.00', NULL, '2021-05-05 11:27:52', '2021-05-05 11:27:52'),
(77, 24, NULL, 113, NULL, '100.00', '0.00', '0.00', NULL, '2021-05-05 11:28:04', '2021-05-05 11:28:04'),
(78, 25, NULL, 113, NULL, '100.00', '0.00', '0.00', NULL, '2021-05-05 11:28:04', '2021-05-05 11:28:04'),
(79, 26, NULL, 113, NULL, '100.00', '0.00', '0.00', NULL, '2021-05-05 11:28:04', '2021-05-05 11:28:04'),
(80, 24, NULL, 96, NULL, '100.00', '0.00', '0.00', NULL, '2021-05-05 11:28:18', '2021-05-05 11:28:18'),
(81, 25, NULL, 96, NULL, '100.00', '0.00', '0.00', NULL, '2021-05-05 11:28:18', '2021-05-05 11:28:18'),
(82, 26, NULL, 96, NULL, '100.00', '0.00', '0.00', NULL, '2021-05-05 11:28:18', '2021-05-05 11:28:18'),
(83, 24, NULL, 139, NULL, '2850.00', '0.00', '0.00', NULL, '2021-05-05 11:28:29', '2021-05-05 11:28:29'),
(84, 25, NULL, 139, NULL, '2850.00', '0.00', '0.00', NULL, '2021-05-05 11:28:29', '2021-05-05 11:28:29'),
(85, 26, NULL, 139, NULL, '2850.00', '0.00', '0.00', NULL, '2021-05-05 11:28:29', '2021-05-05 11:28:29'),
(86, 24, NULL, 122, NULL, '182000.00', '0.00', '0.00', NULL, '2021-05-09 05:38:21', '2021-05-09 05:38:21'),
(87, 25, NULL, 122, NULL, '182000.00', '0.00', '0.00', NULL, '2021-05-09 05:38:21', '2021-05-09 05:38:21'),
(88, 26, NULL, 122, NULL, '182000.00', '0.00', '0.00', NULL, '2021-05-09 05:38:21', '2021-05-09 05:38:21'),
(89, 24, NULL, 137, NULL, '130.00', '0.00', '0.00', NULL, '2021-05-09 05:38:41', '2021-05-09 05:38:41'),
(90, 25, NULL, 137, NULL, '130.00', '0.00', '0.00', NULL, '2021-05-09 05:38:41', '2021-05-09 05:38:41'),
(91, 26, NULL, 137, NULL, '130.00', '0.00', '0.00', NULL, '2021-05-09 05:38:41', '2021-05-09 05:38:41'),
(92, 24, NULL, 133, NULL, '100.00', '0.00', '0.00', NULL, '2021-05-09 05:38:58', '2021-05-09 05:38:58'),
(93, 25, NULL, 133, NULL, '100.00', '0.00', '0.00', NULL, '2021-05-09 05:38:58', '2021-05-09 05:38:58'),
(94, 26, NULL, 133, NULL, '100.00', '0.00', '0.00', NULL, '2021-05-09 05:38:58', '2021-05-09 05:38:58'),
(95, 24, NULL, 134, NULL, '100.00', '0.00', '0.00', NULL, '2021-05-09 05:39:11', '2021-05-09 05:39:11'),
(96, 25, NULL, 134, NULL, '100.00', '0.00', '0.00', NULL, '2021-05-09 05:39:11', '2021-05-09 05:39:11'),
(97, 26, NULL, 134, NULL, '100.00', '0.00', '0.00', NULL, '2021-05-09 05:39:11', '2021-05-09 05:39:11'),
(98, 24, NULL, 107, NULL, '100.00', '0.00', '0.00', NULL, '2021-05-09 05:39:46', '2021-05-09 05:39:46'),
(99, 25, NULL, 107, NULL, '100.00', '0.00', '0.00', NULL, '2021-05-09 05:39:46', '2021-05-09 05:39:46'),
(100, 26, NULL, 107, NULL, '100.00', '0.00', '0.00', NULL, '2021-05-09 05:39:46', '2021-05-09 05:39:46'),
(101, 24, NULL, 95, 40, '200.00', '0.00', '0.00', NULL, '2021-05-09 05:41:18', '2021-05-09 05:41:18'),
(102, 24, NULL, 95, 41, '100.00', '0.00', '0.00', NULL, '2021-05-09 05:41:18', '2021-05-09 05:41:18'),
(103, 25, NULL, 95, 40, '200.00', '0.00', '0.00', NULL, '2021-05-09 05:41:18', '2021-05-09 05:41:18'),
(104, 25, NULL, 95, 41, '100.00', '0.00', '0.00', NULL, '2021-05-09 05:41:18', '2021-05-09 05:41:18'),
(105, 26, NULL, 95, 40, '200.00', '0.00', '0.00', NULL, '2021-05-09 05:41:18', '2021-05-09 05:41:18'),
(106, 26, NULL, 95, 41, '100.00', '0.00', '0.00', NULL, '2021-05-09 05:41:18', '2021-05-09 05:41:18'),
(107, 24, NULL, 131, NULL, '100.00', '0.00', '0.00', NULL, '2021-05-09 05:42:48', '2021-05-09 05:42:48'),
(108, 25, NULL, 131, NULL, '100.00', '0.00', '0.00', NULL, '2021-05-09 05:42:48', '2021-05-09 05:42:48'),
(109, 26, NULL, 131, NULL, '100.00', '0.00', '0.00', NULL, '2021-05-09 05:42:48', '2021-05-09 05:42:48'),
(110, 24, NULL, 125, NULL, '100.00', '0.00', '0.00', NULL, '2021-05-09 05:43:08', '2021-05-09 05:43:08'),
(111, 25, NULL, 125, NULL, '100.00', '0.00', '0.00', NULL, '2021-05-09 05:43:08', '2021-05-09 05:43:08'),
(112, 26, NULL, 125, NULL, '100.00', '0.00', '0.00', NULL, '2021-05-09 05:43:08', '2021-05-09 05:43:08'),
(113, 24, NULL, 126, NULL, '100.00', '0.00', '0.00', NULL, '2021-05-09 05:43:33', '2021-05-09 05:43:33'),
(114, 25, NULL, 126, NULL, '100.00', '0.00', '0.00', NULL, '2021-05-09 05:43:33', '2021-05-09 05:43:33'),
(115, 26, NULL, 126, NULL, '100.00', '0.00', '0.00', NULL, '2021-05-09 05:43:33', '2021-05-09 05:43:33'),
(116, 24, NULL, 93, NULL, '100.00', '0.00', '0.00', NULL, '2021-05-09 05:43:49', '2021-05-09 05:43:49'),
(117, 25, NULL, 93, NULL, '100.00', '0.00', '0.00', NULL, '2021-05-09 05:43:49', '2021-05-09 05:43:49'),
(118, 26, NULL, 93, NULL, '100.00', '0.00', '0.00', NULL, '2021-05-09 05:43:49', '2021-05-09 05:43:49'),
(119, 24, NULL, 94, NULL, '200.00', '0.00', '0.00', NULL, '2021-05-09 05:44:06', '2021-05-09 05:44:06'),
(120, 25, NULL, 94, NULL, '200.00', '0.00', '0.00', NULL, '2021-05-09 05:44:06', '2021-05-09 05:44:06'),
(121, 26, NULL, 94, NULL, '200.00', '0.00', '0.00', NULL, '2021-05-09 05:44:06', '2021-05-09 05:44:06'),
(122, 24, NULL, 124, NULL, '700.00', '0.00', '0.00', NULL, '2021-05-09 05:44:45', '2021-05-09 05:44:45'),
(123, 25, NULL, 124, NULL, '700.00', '0.00', '0.00', NULL, '2021-05-09 05:44:45', '2021-05-09 05:44:45'),
(124, 26, NULL, 124, NULL, '700.00', '0.00', '0.00', NULL, '2021-05-09 05:44:45', '2021-05-09 05:44:45'),
(125, 24, NULL, 92, NULL, '100.00', '0.00', '0.00', NULL, '2021-05-09 05:45:11', '2021-05-09 05:45:11'),
(126, 25, NULL, 92, NULL, '100.00', '0.00', '0.00', NULL, '2021-05-09 05:45:11', '2021-05-09 05:45:11'),
(127, 26, NULL, 92, NULL, '100.00', '0.00', '0.00', NULL, '2021-05-09 05:45:11', '2021-05-09 05:45:11'),
(128, 24, NULL, 127, NULL, '12.00', '0.00', '0.00', NULL, '2021-05-09 05:45:38', '2021-05-09 05:45:38'),
(129, 25, NULL, 127, NULL, '12.00', '0.00', '0.00', NULL, '2021-05-09 05:45:38', '2021-05-09 05:45:38'),
(130, 24, NULL, 119, NULL, '10000.00', '0.00', '0.00', NULL, '2021-05-09 05:46:07', '2021-05-09 05:46:07'),
(131, 25, NULL, 119, NULL, '10000.00', '0.00', '0.00', NULL, '2021-05-09 05:46:07', '2021-05-09 05:46:07'),
(132, 26, NULL, 119, NULL, '10000.00', '0.00', '0.00', NULL, '2021-05-09 05:46:07', '2021-05-09 05:46:07'),
(133, 24, NULL, 115, NULL, '100.00', '0.00', '0.00', NULL, '2021-05-09 05:46:53', '2021-05-09 05:46:53'),
(134, 25, NULL, 115, NULL, '100.00', '0.00', '0.00', NULL, '2021-05-09 05:46:53', '2021-05-09 05:46:53'),
(135, 26, NULL, 115, NULL, '100.00', '0.00', '0.00', NULL, '2021-05-09 05:46:53', '2021-05-09 05:46:53'),
(136, 24, NULL, 114, NULL, '100.00', '0.00', '0.00', NULL, '2021-05-09 05:47:30', '2021-05-09 05:47:30'),
(137, 25, NULL, 114, NULL, '100.00', '0.00', '0.00', NULL, '2021-05-09 05:47:30', '2021-05-09 05:47:30'),
(138, 26, NULL, 114, NULL, '100.00', '0.00', '0.00', NULL, '2021-05-09 05:47:30', '2021-05-09 05:47:30'),
(139, NULL, 7, 142, NULL, '500.00', '100.00', '50000.00', NULL, '2021-05-09 06:59:18', '2021-05-09 06:59:18'),
(140, NULL, 7, 143, NULL, '100.00', '100.00', '10000.00', NULL, '2021-05-09 07:00:24', '2021-05-09 07:00:24'),
(141, NULL, 7, 144, NULL, '100.00', '100.00', '10000.00', NULL, '2021-05-09 07:07:02', '2021-05-09 07:07:02'),
(142, NULL, 7, 145, NULL, '500.00', '100.00', '50000.00', NULL, '2021-05-09 07:19:34', '2021-05-09 07:19:34'),
(143, NULL, 7, 146, NULL, '100.00', '100.00', '10000.00', NULL, '2021-05-09 07:28:29', '2021-05-09 07:28:29'),
(144, NULL, 7, 147, NULL, '100.00', '100.00', '10000.00', NULL, '2021-05-09 07:32:22', '2021-05-09 07:32:22'),
(145, 24, NULL, 148, NULL, '50000.00', '100.00', '5000000.00', NULL, '2021-05-11 04:00:09', '2021-05-11 04:00:09'),
(146, 24, NULL, 149, NULL, '40000.00', '100.00', '4000000.00', NULL, '2021-05-11 04:07:06', '2021-05-11 04:07:06'),
(147, 24, NULL, 153, 43, '2000.00', '0.00', '0.00', NULL, '2021-06-17 08:48:42', '2021-06-17 08:48:42'),
(148, 24, NULL, 153, 44, '2000.00', '0.00', '0.00', NULL, '2021-06-17 08:48:42', '2021-06-17 08:48:42'),
(150, 24, NULL, 153, 46, '2000.00', '0.00', '0.00', NULL, '2021-06-17 08:48:42', '2021-06-17 08:48:42'),
(151, 25, NULL, 153, 43, '2000.00', '0.00', '0.00', NULL, '2021-06-17 08:48:42', '2021-06-17 08:48:42'),
(152, 25, NULL, 153, 44, '2000.00', '0.00', '0.00', NULL, '2021-06-17 08:48:42', '2021-06-17 08:48:42'),
(154, 25, NULL, 153, 46, '2000.00', '0.00', '0.00', NULL, '2021-06-17 08:48:42', '2021-06-17 08:48:42'),
(155, 26, NULL, 153, 43, '2000.00', '0.00', '0.00', NULL, '2021-06-17 08:48:42', '2021-06-17 08:48:42'),
(156, 26, NULL, 153, 44, '2000.00', '0.00', '0.00', NULL, '2021-06-17 08:48:42', '2021-06-17 08:48:42'),
(158, 26, NULL, 153, 46, '2000.00', '0.00', '0.00', NULL, '2021-06-17 08:48:42', '2021-06-17 08:48:42'),
(159, 24, NULL, 153, 48, '2000.00', '0.00', '0.00', NULL, '2021-06-17 08:55:43', '2021-06-17 08:55:43'),
(160, 25, NULL, 153, 48, '2000.00', '0.00', '0.00', NULL, '2021-06-17 08:55:43', '2021-06-17 08:55:43'),
(161, 26, NULL, 153, 48, '2000.00', '0.00', '0.00', NULL, '2021-06-17 08:55:43', '2021-06-17 08:55:43'),
(162, 24, NULL, 154, NULL, '40000.00', '0.00', '0.00', NULL, '2021-06-17 09:08:31', '2021-06-17 09:08:31'),
(163, 25, NULL, 154, NULL, '40000.00', '0.00', '0.00', NULL, '2021-06-17 09:08:31', '2021-06-17 09:08:31'),
(164, 26, NULL, 154, NULL, '40000.00', '0.00', '0.00', NULL, '2021-06-17 09:08:31', '2021-06-17 09:08:31'),
(165, 25, NULL, 149, NULL, '40000.00', '0.00', '0.00', NULL, '2021-06-17 09:13:35', '2021-06-17 09:13:35'),
(166, 26, NULL, 149, NULL, '40000.00', '0.00', '0.00', NULL, '2021-06-17 09:13:35', '2021-06-17 09:13:35');

-- --------------------------------------------------------

--
-- Table structure for table `product_variants`
--

CREATE TABLE `product_variants` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `variant_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `variant_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `variant_quantity` decimal(22,2) NOT NULL DEFAULT 0.00,
  `number_of_sale` decimal(22,2) NOT NULL DEFAULT 0.00,
  `total_transfered` decimal(22,2) NOT NULL DEFAULT 0.00,
  `total_adjusted` decimal(22,2) NOT NULL DEFAULT 0.00,
  `variant_cost` decimal(22,2) NOT NULL,
  `variant_cost_with_tax` decimal(22,2) NOT NULL DEFAULT 0.00,
  `variant_profit` decimal(22,2) NOT NULL DEFAULT 0.00,
  `variant_price` decimal(22,2) NOT NULL,
  `variant_image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_purchased` tinyint(1) NOT NULL DEFAULT 0,
  `delete_in_update` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_variants`
--

INSERT INTO `product_variants` (`id`, `product_id`, `variant_name`, `variant_code`, `variant_quantity`, `number_of_sale`, `total_transfered`, `total_adjusted`, `variant_cost`, `variant_cost_with_tax`, `variant_profit`, `variant_price`, `variant_image`, `is_purchased`, `delete_in_update`, `created_at`, `updated_at`) VALUES
(40, 95, '6GB 128GB', '6gb128gb-PO76699939', '1543.00', '155.00', '0.00', '23.00', '200.00', '210.00', '25.00', '125.00', '609775fe64b8c.jpg', 1, 0, '2021-03-09 04:35:35', '2021-06-09 11:07:10'),
(41, 95, '4GB 128GB', '4gb128gb-PO76699939', '296.00', '111.00', '0.00', '2.00', '100.00', '105.00', '25.00', '125.00', '609775fe5c6b6.jpg', 1, 0, '2021-03-09 04:35:35', '2021-06-16 10:32:56'),
(42, 99, 'Red,L', 'red,l-PO73361812', '1.00', '0.00', '0.00', '0.00', '100.00', '100.00', '25.00', '125.00', NULL, 1, 0, '2021-04-01 11:53:45', '2021-04-27 07:49:07'),
(43, 153, 'Black,6GB,128GB', 'black,6gb,128gb-SMA32', '100.00', '0.00', '0.00', '0.00', '2000.00', '2000.00', '40.00', '2800.00', NULL, 1, 0, '2021-06-17 04:50:21', '2021-06-17 09:15:13'),
(44, 153, 'Blue,6GB,128GB', 'blue,6gb,128gb-SMA32', '100.00', '0.00', '0.00', '0.00', '2000.00', '2000.00', '40.00', '2800.00', NULL, 1, 0, '2021-06-17 04:50:21', '2021-06-17 09:15:13'),
(46, 153, 'Blue,4GB,64GB', 'blue,4gb,64gb-black-SMA32', '100.00', '0.00', '0.00', '0.00', '2000.00', '2000.00', '40.00', '2800.00', NULL, 1, 0, '2021-06-17 04:50:21', '2021-06-17 09:15:13'),
(48, 153, 'Black-4GB-64GB', 'black-4gb-64gb-SMA32', '100.00', '0.00', '0.00', '0.00', '2000.00', '2000.00', '40.00', '2800.00', NULL, 1, 0, '2021-06-17 08:55:43', '2021-06-17 09:15:13');

-- --------------------------------------------------------

--
-- Table structure for table `product_warehouses`
--

CREATE TABLE `product_warehouses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `warehouse_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_quantity` decimal(22,2) DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_warehouses`
--

INSERT INTO `product_warehouses` (`id`, `warehouse_id`, `product_id`, `product_quantity`, `created_at`, `updated_at`) VALUES
(31, 7, 95, '206.00', '2021-03-09 08:49:11', '2021-06-16 10:32:56'),
(32, 7, 96, '81.00', '2021-03-28 11:24:08', '2021-06-16 05:49:23'),
(33, 7, 94, '0.00', '2021-03-29 05:17:53', '2021-04-06 10:19:36'),
(34, 7, 103, '120.00', '2021-04-05 05:56:17', '2021-05-09 13:00:03'),
(35, 7, 104, '20.00', '2021-04-05 05:58:38', '2021-06-03 05:22:08'),
(37, 7, 106, '1082.00', '2021-04-05 06:03:29', '2021-06-03 05:22:10'),
(38, 7, 107, '1098.00', '2021-04-05 06:03:40', '2021-06-08 13:19:25'),
(39, 7, 108, '1057.00', '2021-04-05 06:05:35', '2021-06-09 08:56:54'),
(40, 7, 109, '9.00', '2021-04-05 06:05:41', '2021-05-09 13:00:03'),
(41, 7, 110, '107.00', '2021-04-05 06:06:39', '2021-06-03 05:22:08'),
(43, 7, 112, '110.00', '2021-04-05 06:09:55', '2021-05-09 13:00:03'),
(44, 7, 113, '44.00', '2021-04-05 06:13:35', '2021-06-08 13:07:02'),
(45, 7, 114, '93.00', '2021-04-05 06:36:03', '2021-06-03 13:15:18'),
(46, 7, 119, '9.00', '2021-04-05 07:53:59', '2021-06-03 05:22:10'),
(49, 7, 122, '100.00', '2021-04-10 11:13:10', '2021-04-10 11:13:10'),
(50, 7, 100, '102.00', '2021-04-10 11:13:25', '2021-04-10 11:32:04'),
(51, 7, 126, '0.00', '2021-04-11 04:46:30', '2021-04-24 11:52:03'),
(52, 7, 123, '99.00', '2021-04-11 05:18:16', '2021-04-12 12:52:09'),
(53, 7, 124, '71.00', '2021-04-11 05:18:42', '2021-06-09 07:22:00'),
(54, 7, 116, '98.00', '2021-04-11 05:18:54', '2021-05-09 13:00:03'),
(57, 7, 129, '9999.00', '2021-04-17 04:09:48', '2021-04-17 04:42:48'),
(58, 7, 99, '1.00', '2021-04-24 07:27:48', '2021-04-27 07:49:07'),
(59, 7, 98, '0.00', '2021-04-24 07:27:48', '2021-05-09 13:00:03'),
(60, 7, 131, '0.00', '2021-04-25 03:50:24', '2021-04-27 06:11:40'),
(61, 7, 132, '9.00', '2021-04-26 09:28:54', '2021-04-27 06:11:40'),
(62, 7, 133, '2.00', '2021-04-26 09:30:27', '2021-06-08 13:14:18'),
(63, 7, 134, '3.00', '2021-04-26 09:32:07', '2021-06-06 08:29:28'),
(64, 7, 130, '99.00', '2021-04-28 05:40:18', '2021-06-03 05:22:08'),
(65, 7, 92, '86.00', '2021-04-29 12:22:48', '2021-06-06 11:22:47'),
(66, 7, 115, '81.00', '2021-05-04 03:58:13', '2021-06-16 10:32:56'),
(67, 7, 142, '98.00', '2021-05-09 06:59:18', '2021-06-03 05:22:08'),
(68, 7, 143, '99.00', '2021-05-09 07:00:24', '2021-06-03 05:22:08'),
(69, 7, 144, '100.00', '2021-05-09 07:07:02', '2021-05-09 07:07:02'),
(70, 7, 145, '99.00', '2021-05-09 07:19:34', '2021-05-09 13:00:03'),
(71, 7, 146, '99.00', '2021-05-09 07:28:29', '2021-06-03 05:22:08'),
(72, 7, 147, '99.00', '2021-05-09 07:32:22', '2021-05-09 13:00:03'),
(73, 7, 153, '400.00', '2021-06-17 09:15:13', '2021-06-17 09:15:14'),
(74, 7, 154, '96.00', '2021-06-17 09:16:01', '2021-06-17 10:44:49');

-- --------------------------------------------------------

--
-- Table structure for table `product_warehouse_variants`
--

CREATE TABLE `product_warehouse_variants` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_warehouse_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_variant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `variant_quantity` decimal(22,2) DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_warehouse_variants`
--

INSERT INTO `product_warehouse_variants` (`id`, `product_warehouse_id`, `product_id`, `product_variant_id`, `variant_quantity`, `created_at`, `updated_at`) VALUES
(16, 31, 95, 40, '0.00', '2021-03-09 08:49:11', '2021-06-09 07:22:00'),
(17, 31, 95, 41, '22.00', '2021-04-01 10:47:38', '2021-06-16 10:32:56'),
(18, 58, 99, 42, '1.00', '2021-04-24 07:27:48', '2021-04-27 07:49:07'),
(19, 73, 153, 48, '100.00', '2021-06-17 09:15:14', '2021-06-17 09:15:14'),
(20, 73, 153, 46, '100.00', '2021-06-17 09:15:14', '2021-06-17 09:15:14'),
(21, 73, 153, 43, '100.00', '2021-06-17 09:15:14', '2021-06-17 09:15:14'),
(22, 73, 153, 44, '100.00', '2021-06-17 09:15:14', '2021-06-17 09:15:14');

-- --------------------------------------------------------

--
-- Table structure for table `purchases`
--

CREATE TABLE `purchases` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `warehouse_id` bigint(20) UNSIGNED DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `supplier_id` bigint(20) UNSIGNED DEFAULT NULL,
  `pay_term` tinyint(4) DEFAULT NULL,
  `pay_term_number` bigint(20) DEFAULT NULL,
  `total_item` bigint(20) NOT NULL,
  `net_total_amount` decimal(22,2) NOT NULL DEFAULT 0.00,
  `order_discount` decimal(22,2) NOT NULL DEFAULT 0.00,
  `order_discount_type` tinyint(4) NOT NULL DEFAULT 1,
  `order_discount_amount` decimal(22,2) NOT NULL DEFAULT 0.00,
  `shipment_details` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipment_charge` decimal(22,2) NOT NULL DEFAULT 0.00,
  `purchase_note` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `purchase_tax_id` bigint(20) UNSIGNED DEFAULT NULL,
  `purchase_tax_percent` decimal(22,2) NOT NULL DEFAULT 0.00,
  `purchase_tax_amount` decimal(22,2) NOT NULL DEFAULT 0.00,
  `total_purchase_amount` decimal(22,2) NOT NULL DEFAULT 0.00,
  `paid` decimal(22,2) NOT NULL DEFAULT 0.00,
  `due` decimal(22,2) NOT NULL DEFAULT 0.00,
  `purchase_return_amount` decimal(22,2) NOT NULL DEFAULT 0.00,
  `purchase_return_due` decimal(22,2) NOT NULL DEFAULT 0.00,
  `payment_note` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `admin_id` bigint(20) UNSIGNED DEFAULT NULL,
  `purchase_status` tinyint(4) NOT NULL DEFAULT 1,
  `date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `time` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `report_date` timestamp NULL DEFAULT NULL,
  `month` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `year` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_last_created` tinyint(1) NOT NULL DEFAULT 0,
  `is_return_available` tinyint(1) NOT NULL DEFAULT 0,
  `attachment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchases`
--

INSERT INTO `purchases` (`id`, `invoice_id`, `warehouse_id`, `branch_id`, `supplier_id`, `pay_term`, `pay_term_number`, `total_item`, `net_total_amount`, `order_discount`, `order_discount_type`, `order_discount_amount`, `shipment_details`, `shipment_charge`, `purchase_note`, `purchase_tax_id`, `purchase_tax_percent`, `purchase_tax_amount`, `total_purchase_amount`, `paid`, `due`, `purchase_return_amount`, `purchase_return_due`, `payment_note`, `admin_id`, `purchase_status`, `date`, `time`, `report_date`, `month`, `year`, `is_last_created`, `is_return_available`, `attachment`, `created_at`, `updated_at`) VALUES
(105, 'PI210410468754', 7, NULL, 26, NULL, NULL, 1, '1050.00', '0.00', 1, '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '1050.00', '1050.00', '100.00', '100.00', '100.00', NULL, 2, 1, '10-04-2021', '04:13:37 pm', '2021-04-09 18:00:00', 'April', '2021', 0, 0, NULL, '2021-04-10 10:13:37', '2021-04-11 04:31:58'),
(106, 'PI210411765699', 7, NULL, 26, NULL, NULL, 2, '840.00', '0.00', 1, '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '840.00', '940.00', '0.00', '100.00', '35.00', NULL, 2, 1, '11-04-2021', '10:31:58 am', '2021-04-10 18:00:00', 'April', '2021', 0, 0, NULL, '2021-04-11 04:31:58', '2021-05-25 07:01:12'),
(107, 'PI210411482947', 7, NULL, 26, NULL, NULL, 2, '840.00', '0.00', 1, '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '840.00', '840.00', '0.00', '0.00', '0.00', NULL, 2, 1, '11-04-2021', '10:32:07 am', '2021-04-10 18:00:00', 'April', '2021', 0, 0, NULL, '2021-04-11 04:32:07', '2021-04-11 04:42:40'),
(108, 'PI210411123628', 7, NULL, 26, NULL, NULL, 4, '1150.00', '0.00', 1, '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '1150.00', '1150.00', '0.00', '0.00', '0.00', NULL, 2, 1, '11-04-2021', '10:42:40 am', '2021-04-10 18:00:00', 'April', '2021', 0, 0, NULL, '2021-04-11 04:42:40', '2021-04-21 05:41:40'),
(109, 'PI210411891192', 7, NULL, 26, NULL, NULL, 4, '1150.00', '0.00', 1, '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '1150.00', '1150.00', '0.00', '0.00', '0.00', NULL, 2, 1, '11-04-2021', '10:42:46 am', '2021-04-10 18:00:00', 'April', '2021', 0, 0, NULL, '2021-04-11 04:42:46', '2021-04-21 05:41:40'),
(110, 'PI210411149945', 7, NULL, 26, NULL, NULL, 4, '1150.00', '0.00', 1, '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '1150.00', '1150.00', '0.00', '0.00', '0.00', NULL, 2, 1, '11-04-2021', '10:44:42 am', '2021-04-10 18:00:00', 'April', '2021', 0, 0, NULL, '2021-04-11 04:44:42', '2021-04-22 06:43:25'),
(111, 'PI210411879669', 7, NULL, 26, NULL, NULL, 3, '415.00', '0.00', 1, '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '415.00', '415.00', '-310.00', '310.00', '310.00', NULL, 2, 1, '11-04-2021', '10:46:30 am', '2021-04-10 18:00:00', 'April', '2021', 0, 0, NULL, '2021-04-11 04:46:30', '2021-04-24 11:52:03'),
(112, 'PI210411859721', NULL, 26, 26, NULL, NULL, 8, '1196500.00', '0.00', 1, '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '1196500.00', '1196500.00', '0.00', '0.00', '0.00', NULL, 3, 1, '11-04-2021', '12:18:27 pm', '2021-04-10 18:00:00', 'April', '2021', 0, 0, NULL, '2021-04-11 06:18:27', '2021-04-25 07:39:38'),
(113, 'PI210413482612', NULL, 26, 26, NULL, NULL, 6, '730.00', '0.00', 1, '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '730.00', '730.00', '-210.00', '210.00', '0.00', NULL, 3, 1, '13-04-2021', '12:16:42 pm', '2021-04-12 18:00:00', 'April', '2021', 0, 1, NULL, '2021-04-13 06:16:42', '2021-04-25 07:40:37'),
(114, 'PI210424169257', 7, NULL, 26, NULL, NULL, 9, '1775.00', '0.00', 1, '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '1775.00', '1670.00', '-105.00', '210.00', '105.00', NULL, 2, 1, '2021-04-24', '01:27:48 pm', '2021-04-23 18:00:00', 'April', '2021', 0, 1, NULL, '2021-04-24 07:27:48', '2021-04-27 07:49:07'),
(115, 'PI210424725657', 7, NULL, 27, 1, 884, 2, '630.00', '0.00', 1, '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '630.00', '630.00', '-210.00', '210.00', '210.00', NULL, 2, 1, '2021-04-24', '01:43:18 pm', '2021-04-23 18:00:00', 'April', '2021', 0, 1, NULL, '2021-04-24 07:43:18', '2021-04-25 13:50:54'),
(116, 'PI210425333624', 7, NULL, 27, 1, 10, 1, '105.00', '10.00', 1, '10.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '95.00', '95.00', '0.00', '0.00', '0.00', NULL, 2, 1, '2021-04-25', '09:50:24 am', '2021-04-24 18:00:00', 'April', '2021', 0, 0, NULL, '2021-04-25 03:50:24', '2021-04-28 08:35:26'),
(117, 'PI210428155415', NULL, 24, 30, NULL, NULL, 7, '75810.00', '0.00', 1, '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '75810.00', '75810.00', '0.00', '0.00', '0.00', NULL, 7, 1, '2021-04-28', '02:35:26 pm', '2021-04-27 18:00:00', 'April', '2021', 0, 0, NULL, '2021-04-28 08:35:26', '2021-04-28 08:35:38'),
(118, 'PI210428535329', NULL, 24, 30, NULL, NULL, 7, '75810.00', '0.00', 1, '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '75810.00', '75810.00', '0.00', '0.00', '0.00', NULL, 7, 1, '2021-04-28', '02:35:38 pm', '2021-04-27 18:00:00', 'April', '2021', 0, 0, NULL, '2021-04-28 08:35:38', '2021-04-28 08:37:51'),
(119, 'PI210428819133', NULL, 24, 30, NULL, NULL, 7, '75810.00', '0.00', 1, '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '75810.00', '75810.00', '0.00', '0.00', '0.00', NULL, 7, 1, '2021-04-28', '02:37:51 pm', '2021-04-27 18:00:00', 'April', '2021', 0, 0, NULL, '2021-04-28 08:37:51', '2021-04-28 09:13:31'),
(120, 'PI210428516848', NULL, 24, 30, NULL, NULL, 1, '1850000.00', '0.00', 1, '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '1850000.00', '1850000.00', '0.00', '0.00', '0.00', NULL, 7, 1, '2021-04-28', '03:13:31 pm', '2021-04-27 18:00:00', 'April', '2021', 0, 0, NULL, '2021-04-28 09:13:31', '2021-04-28 09:14:24'),
(121, 'PI210428442259', NULL, 24, 30, NULL, NULL, 1, '1300000.00', '0.00', 1, '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '1300000.00', '1300000.00', '0.00', '0.00', '0.00', NULL, 7, 1, '2021-04-28', '03:14:24 pm', '2021-04-27 18:00:00', 'April', '2021', 0, 0, NULL, '2021-04-28 09:14:24', '2021-04-28 09:18:38'),
(122, 'PI210428972884', NULL, 24, 27, 1, 884, 1, '16500.00', '0.00', 1, '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '16500.00', '16500.00', '0.00', '0.00', '0.00', NULL, 7, 1, '2021-04-28', '03:18:38 pm', '2021-04-27 18:00:00', 'April', '2021', 0, 0, NULL, '2021-04-28 09:18:38', '2021-05-04 03:58:13'),
(123, 'PI210504848376', 7, NULL, 26, NULL, NULL, 1, '10000.00', '1000.00', 1, '1000.00', NULL, '0.00', NULL, NULL, '5.00', '500.00', '9500.00', '9500.00', '0.00', '0.00', '0.00', NULL, 2, 1, '2021-05-04', '09:58:13 am', '2021-05-03 18:00:00', 'May', '2021', 0, 0, NULL, '2021-05-04 03:58:13', '2021-06-06 10:00:30'),
(124, 'PI210606295452', 7, NULL, 26, NULL, NULL, 1, '840.00', '0.00', 1, '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '840.00', '840.00', '0.00', '0.00', '0.00', NULL, 2, 1, '2021-06-06', '04:00:30 pm', '2021-06-05 18:00:00', 'June', '2021', 0, 0, NULL, '2021-06-06 10:00:30', '2021-06-06 10:01:15'),
(125, 'PI210606645459', 7, NULL, 26, NULL, NULL, 1, '840.00', '0.00', 1, '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '840.00', '0.00', '840.00', '0.00', '0.00', NULL, 2, 1, '2021-06-06', '04:01:15 pm', '2021-06-05 18:00:00', 'June', '2021', 0, 0, NULL, '2021-06-06 10:01:15', '2021-06-10 06:47:33'),
(126, 'PI210610897489', 7, NULL, 31, NULL, NULL, 1, '210.00', '0.00', 1, '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '210.00', '210.00', '-105.00', '105.00', '105.00', NULL, 2, 1, '2021-06-10', '12:47:33 pm', '2021-06-09 18:00:00', 'June', '2021', 0, 1, NULL, '2021-06-10 06:47:33', '2021-06-10 06:49:00'),
(127, 'PI210610657434', 7, NULL, 31, NULL, NULL, 1, '105.00', '0.00', 1, '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '105.00', '0.00', '105.00', '0.00', '0.00', NULL, 2, 1, '2021-06-10', '12:49:00 pm', '2021-06-09 18:00:00', 'June', '2021', 0, 0, NULL, '2021-06-10 06:49:00', '2021-06-17 09:15:13'),
(128, 'PI210617978157', 7, NULL, 26, NULL, NULL, 4, '800000.00', '0.00', 1, '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '800000.00', '800000.00', '0.00', '0.00', '0.00', NULL, 2, 1, '2021-06-17', '03:15:13 pm', '2021-06-16 18:00:00', 'June', '2021', 0, 0, NULL, '2021-06-17 09:15:13', '2021-06-17 09:16:01'),
(129, 'PI210617268916', 7, NULL, 29, NULL, NULL, 1, '4200000.00', '0.00', 1, '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '4200000.00', '4200000.00', '0.00', '0.00', '0.00', NULL, 2, 1, '2021-06-17', '03:16:01 pm', '2021-06-16 18:00:00', 'June', '2021', 1, 0, NULL, '2021-06-17 09:16:01', '2021-06-17 09:16:01');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_payments`
--

CREATE TABLE `purchase_payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `purchase_id` bigint(20) UNSIGNED NOT NULL,
  `supplier_id` bigint(20) UNSIGNED DEFAULT NULL,
  `account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `pay_mode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paid_amount` decimal(22,2) NOT NULL DEFAULT 0.00,
  `payment_on` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1=purchase_invoice_due;2=supplier_due',
  `payment_type` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1=purchase_due;2=return_due',
  `payment_status` tinyint(4) DEFAULT NULL COMMENT '1=due;2=partial;3=paid',
  `date` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `time` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `month` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `year` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `report_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `card_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_holder` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_transaction_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_month` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_year` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_secure_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cheque_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transaction_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `admin_id` bigint(20) UNSIGNED DEFAULT NULL,
  `note` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attachment` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_payments`
--

INSERT INTO `purchase_payments` (`id`, `invoice_id`, `purchase_id`, `supplier_id`, `account_id`, `pay_mode`, `paid_amount`, `payment_on`, `payment_type`, `payment_status`, `date`, `time`, `month`, `year`, `report_date`, `card_no`, `card_holder`, `card_type`, `card_transaction_no`, `card_month`, `card_year`, `card_secure_code`, `account_no`, `cheque_no`, `transaction_no`, `admin_id`, `note`, `attachment`, `created_at`, `updated_at`) VALUES
(101, 'PPI210410468754', 105, 26, NULL, 'Cash', '1050.00', 1, 1, NULL, '10-04-2021', NULL, 'April', '2021', '2021-04-09 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, '2021-04-10 10:13:37', '2021-04-10 10:13:37'),
(102, 'PPI210413482612', 113, 26, 16, 'Cash', '730.00', 1, 1, NULL, '13-04-2021', NULL, 'April', '2021', '2021-04-12 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, NULL, NULL, '2021-04-13 06:16:42', '2021-04-13 06:16:42'),
(103, 'PPI210421438332', 108, 26, NULL, 'Cash', '1150.00', 1, 1, NULL, '21-04-2021', NULL, 'April', '2021', '2021-04-20 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, '2021-04-21 05:41:40', '2021-04-21 05:41:40'),
(104, 'PPI210421438332', 109, 26, 15, 'Cash', '1150.00', 1, 1, NULL, '2021-04-21', NULL, 'April', '2021', '2021-04-20 18:00:00', 'FF', NULL, 'Credit-Card', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, '2021-04-21 05:41:40', '2021-04-22 06:26:04'),
(106, 'PPI210421438332', 111, 26, NULL, 'Cash', '415.00', 1, 1, NULL, '21-04-2021', NULL, 'April', '2021', '2021-04-20 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, '2021-04-21 05:41:40', '2021-04-21 05:41:40'),
(107, 'PPI210421438332', 112, 26, NULL, 'Cash', '4135.00', 1, 1, NULL, '21-04-2021', NULL, 'April', '2021', '2021-04-20 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, '2021-04-21 05:41:40', '2021-04-21 05:41:40'),
(108, 'PPI210421987366', 112, 26, NULL, 'Cash', '1192360.00', 1, 1, NULL, '21-04-2021', NULL, 'April', '2021', '2021-04-20 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, '2021-04-21 05:42:54', '2021-04-21 05:42:54'),
(109, 'PPI21042275827', 106, 26, NULL, 'Cash', '20.00', 1, 1, NULL, '2021-04-22', '12:10:59 pm', 'April', '2021', '2021-04-21 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, '2021-04-22 06:10:59', '2021-04-22 06:10:59'),
(111, 'PPI21042262132', 110, 26, 16, 'Card', '1150.00', 1, 1, NULL, '2021-04-22', '12:43:25 pm', 'April', '2021', '2021-04-21 18:00:00', 'FF', 'FF', 'Credit-Card', 'FF', 'FF', 'FF', 'FF', NULL, NULL, NULL, 2, 'Payment is complated.', NULL, '2021-04-22 06:43:25', '2021-04-22 06:43:25'),
(112, 'PRPR22042115355', 106, 26, 16, 'Cash', '20.00', 1, 2, NULL, '2021-04-22', '01:14:10 pm', 'April', '2021', '2021-04-21 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, '2021-04-22 07:14:10', '2021-04-22 07:15:00'),
(113, 'PRPR22042122697', 106, 26, NULL, 'Cash', '5.00', 1, 2, NULL, '2021-04-22', '01:53:37 pm', 'April', '2021', '2021-04-21 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, '2021-04-22 07:53:37', '2021-04-22 07:53:37'),
(114, 'PPI210424169257', 114, 26, NULL, 'Cash', '1670.00', 1, 1, NULL, '2021-04-24', NULL, 'April', '2021', '2021-04-23 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, '2021-04-24 07:27:48', '2021-04-24 07:27:48'),
(115, 'PPI210424725657', 115, 27, NULL, 'Cash', '630.00', 1, 1, NULL, '2021-04-24', NULL, 'April', '2021', '2021-04-23 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, '2021-04-24 07:43:18', '2021-04-24 07:43:18'),
(116, 'PRPR24042198415', 115, 27, 16, 'Cash', '315.00', 1, 2, NULL, '2021-04-24', '03:46:16 pm', 'April', '2021', '2021-04-23 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, '2021-04-24 09:46:16', '2021-04-24 09:46:30'),
(117, 'PPI210425333624', 116, 27, NULL, 'Cash', '95.00', 1, 1, NULL, '2021-04-25', NULL, 'April', '2021', '2021-04-24 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, '2021-04-25 03:50:24', '2021-04-25 03:50:24'),
(118, 'PPI21042519327', 112, 26, NULL, 'Cash', '5.00', 1, 1, NULL, '2021-04-25', '01:39:38 pm', 'April', '2021', '2021-04-24 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, NULL, NULL, '2021-04-25 07:39:38', '2021-04-25 07:39:38'),
(119, 'PRPR25042137131', 113, 26, 16, 'Cash', '210.00', 1, 2, NULL, '2021-04-25', '01:40:37 pm', 'April', '2021', '2021-04-24 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, NULL, NULL, '2021-04-25 07:40:37', '2021-04-25 07:40:51'),
(120, 'PPI210428819133', 119, 30, NULL, 'Cash', '75810.00', 1, 1, NULL, '2021-04-28', NULL, 'April', '2021', '2021-04-27 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 7, NULL, NULL, '2021-04-28 08:37:51', '2021-04-28 08:37:51'),
(121, 'PPI210428516848', 120, 30, NULL, 'Cash', '1850000.00', 1, 1, NULL, '2021-04-28', NULL, 'April', '2021', '2021-04-27 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 7, NULL, NULL, '2021-04-28 09:13:31', '2021-04-28 09:13:31'),
(122, 'PPI210428442259', 121, 30, NULL, 'Cash', '1300000.00', 1, 1, NULL, '2021-04-28', NULL, 'April', '2021', '2021-04-27 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 7, NULL, NULL, '2021-04-28 09:14:24', '2021-04-28 09:14:24'),
(123, 'PPI210428972884', 122, 27, NULL, 'Cash', '16500.00', 1, 1, NULL, '2021-04-28', NULL, 'April', '2021', '2021-04-27 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 7, NULL, NULL, '2021-04-28 09:18:38', '2021-04-28 09:18:38'),
(124, 'PPI210504848376', 123, 26, 16, 'Cash', '9500.00', 1, 1, NULL, '2021-05-04', NULL, 'May', '2021', '2021-05-03 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, '2021-05-04 03:58:13', '2021-05-04 03:58:13'),
(125, 'PPI21052532529', 106, 26, NULL, 'Cash', '80.00', 1, 1, NULL, '2021-05-25', '01:01:12 pm', 'May', '2021', '2021-05-24 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, '2021-05-25 07:01:12', '2021-05-25 07:01:12'),
(126, 'PPI210606295452', 124, 26, NULL, 'Cash', '840.00', 1, 1, NULL, '2021-06-06', NULL, 'June', '2021', '2021-06-05 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, '2021-06-06 10:00:30', '2021-06-06 10:00:30'),
(127, 'PPI210610897489', 126, 31, NULL, 'Cash', '210.00', 1, 1, NULL, '2021-06-10', NULL, 'June', '2021', '2021-06-09 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, '2021-06-10 06:47:33', '2021-06-10 06:47:33'),
(128, 'PPI210617978157', 128, 26, 16, 'Cash', '800000.00', 1, 1, NULL, '2021-06-17', NULL, 'June', '2021', '2021-06-16 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, '2021-06-17 09:15:14', '2021-06-17 09:15:14'),
(129, 'PPI210617268916', 129, 29, NULL, 'Cash', '4200000.00', 1, 1, NULL, '2021-06-17', NULL, 'June', '2021', '2021-06-16 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, '2021-06-17 09:16:01', '2021-06-17 09:16:01');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_products`
--

CREATE TABLE `purchase_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `purchase_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `product_variant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `quantity` decimal(22,2) DEFAULT 0.00,
  `unit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unit_cost` decimal(22,2) NOT NULL DEFAULT 0.00,
  `unit_discount` decimal(22,2) NOT NULL DEFAULT 0.00,
  `unit_cost_with_discount` decimal(22,2) NOT NULL DEFAULT 0.00,
  `subtotal` decimal(22,2) NOT NULL DEFAULT 0.00 COMMENT 'Without_tax',
  `unit_tax_percent` decimal(22,2) NOT NULL DEFAULT 0.00,
  `unit_tax` decimal(22,2) NOT NULL DEFAULT 0.00,
  `net_unit_cost` decimal(22,2) NOT NULL DEFAULT 0.00 COMMENT 'With_tax',
  `line_total` decimal(22,2) NOT NULL DEFAULT 0.00,
  `profit_margin` decimal(22,2) NOT NULL DEFAULT 0.00,
  `selling_price` decimal(22,2) NOT NULL DEFAULT 0.00,
  `is_received` tinyint(1) NOT NULL DEFAULT 0,
  `lot_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `delete_in_update` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_products`
--

INSERT INTO `purchase_products` (`id`, `purchase_id`, `product_id`, `product_variant_id`, `quantity`, `unit`, `unit_cost`, `unit_discount`, `unit_cost_with_discount`, `subtotal`, `unit_tax_percent`, `unit_tax`, `net_unit_cost`, `line_total`, `profit_margin`, `selling_price`, `is_received`, `lot_no`, `delete_in_update`, `created_at`, `updated_at`) VALUES
(237, 105, 96, NULL, '10.00', 'Kilogram', '100.00', '0.00', '100.00', '1000.00', '5.00', '5.00', '105.00', '1050.00', '0.00', '0.00', 0, '20', 0, '2021-04-10 10:13:37', '2021-04-10 10:13:37'),
(238, 106, 124, NULL, '1.00', 'Piece', '700.00', '0.00', '700.00', '700.00', '5.00', '5.00', '735.00', '735.00', '0.00', '0.00', 0, NULL, 0, '2021-04-11 04:31:58', '2021-04-11 04:31:58'),
(239, 107, 124, NULL, '1.00', 'Piece', '700.00', '0.00', '700.00', '700.00', '5.00', '5.00', '735.00', '735.00', '0.00', '0.00', 0, NULL, 0, '2021-04-11 04:32:07', '2021-04-11 04:32:07'),
(240, 108, 125, NULL, '1.00', 'Piece', '100.00', '0.00', '100.00', '100.00', '5.00', '5.00', '105.00', '105.00', '0.00', '0.00', 0, NULL, 0, '2021-04-11 04:42:40', '2021-04-11 04:42:40'),
(241, 108, 115, NULL, '1.00', 'Piece', '100.00', '0.00', '100.00', '100.00', '0.00', '0.00', '100.00', '100.00', '0.00', '0.00', 0, NULL, 0, '2021-04-11 04:42:40', '2021-04-11 04:42:40'),
(242, 108, 124, NULL, '1.00', 'Piece', '700.00', '0.00', '700.00', '700.00', '5.00', '35.00', '735.00', '735.00', '0.00', '0.00', 0, NULL, 0, '2021-04-11 04:42:40', '2021-04-11 04:42:40'),
(243, 109, 125, NULL, '1.00', 'Piece', '100.00', '0.00', '100.00', '100.00', '5.00', '5.00', '105.00', '105.00', '0.00', '0.00', 0, NULL, 0, '2021-04-11 04:42:46', '2021-04-11 04:42:46'),
(244, 109, 115, NULL, '1.00', 'Piece', '100.00', '0.00', '100.00', '100.00', '0.00', '0.00', '100.00', '100.00', '0.00', '0.00', 0, NULL, 0, '2021-04-11 04:42:46', '2021-04-11 04:42:46'),
(245, 109, 124, NULL, '1.00', 'Piece', '700.00', '0.00', '700.00', '700.00', '5.00', '35.00', '735.00', '735.00', '0.00', '0.00', 0, NULL, 0, '2021-04-11 04:42:46', '2021-04-11 04:42:46'),
(246, 110, 125, NULL, '1.00', 'Piece', '100.00', '0.00', '100.00', '100.00', '5.00', '5.00', '105.00', '105.00', '0.00', '0.00', 0, NULL, 0, '2021-04-11 04:44:42', '2021-04-11 04:44:42'),
(247, 110, 115, NULL, '1.00', 'Piece', '100.00', '0.00', '100.00', '100.00', '0.00', '0.00', '100.00', '100.00', '0.00', '0.00', 0, NULL, 0, '2021-04-11 04:44:42', '2021-04-11 04:44:42'),
(248, 110, 124, NULL, '1.00', 'Piece', '700.00', '0.00', '700.00', '700.00', '5.00', '35.00', '735.00', '735.00', '0.00', '0.00', 0, NULL, 0, '2021-04-11 04:44:42', '2021-04-11 04:44:42'),
(249, 111, 126, NULL, '1.00', 'Piece', '100.00', '0.00', '100.00', '100.00', '0.00', '0.00', '100.00', '100.00', '0.00', '0.00', 0, NULL, 0, '2021-04-11 04:46:30', '2021-04-11 04:46:30'),
(250, 111, 95, 40, '1.00', 'Piece', '200.00', '0.00', '200.00', '200.00', '5.00', '10.00', '210.00', '210.00', '0.00', '0.00', 0, NULL, 0, '2021-04-11 04:46:30', '2021-04-11 04:46:30'),
(252, 112, 114, NULL, '100.00', 'Piece', '100.00', '0.00', '100.00', '10000.00', '5.00', '5.00', '105.00', '10500.00', '0.00', '0.00', 0, NULL, 0, '2021-04-11 06:18:27', '2021-04-11 06:18:27'),
(253, 112, 115, NULL, '100.00', 'Piece', '100.00', '0.00', '100.00', '10000.00', '0.00', '0.00', '100.00', '10000.00', '0.00', '0.00', 0, NULL, 0, '2021-04-11 06:18:27', '2021-04-11 06:18:27'),
(254, 112, 124, NULL, '100.00', 'Piece', '700.00', '0.00', '700.00', '70000.00', '5.00', '35.00', '735.00', '73500.00', '0.00', '0.00', 0, NULL, 0, '2021-04-11 06:18:27', '2021-04-11 06:18:27'),
(256, 112, 106, NULL, '100.00', 'Piece', '100.00', '0.00', '100.00', '10000.00', '5.00', '5.00', '105.00', '10500.00', '0.00', '0.00', 0, NULL, 0, '2021-04-11 06:18:27', '2021-04-11 06:18:27'),
(257, 112, 95, 40, '100.00', 'Piece', '200.00', '0.00', '200.00', '20000.00', '5.00', '10.00', '210.00', '21000.00', '0.00', '0.00', 0, NULL, 0, '2021-04-11 06:18:27', '2021-04-11 06:18:27'),
(258, 112, 96, NULL, '100.00', 'Piece', '100.00', '0.00', '100.00', '10000.00', '5.00', '5.00', '105.00', '10500.00', '0.00', '0.00', 0, NULL, 0, '2021-04-11 06:18:27', '2021-04-11 06:18:27'),
(259, 112, 119, NULL, '100.00', 'Piece', '10000.00', '0.00', '10000.00', '1000000.00', '5.00', '500.00', '10500.00', '1050000.00', '0.00', '0.00', 0, NULL, 0, '2021-04-11 06:18:27', '2021-04-11 06:18:27'),
(261, 113, 106, NULL, '1.00', 'Piece', '100.00', '0.00', '100.00', '100.00', '5.00', '5.00', '105.00', '105.00', '0.00', '0.00', 0, NULL, 0, '2021-04-13 06:16:42', '2021-04-13 06:16:42'),
(262, 113, 96, NULL, '1.00', 'Piece', '100.00', '0.00', '100.00', '100.00', '5.00', '5.00', '105.00', '105.00', '0.00', '0.00', 0, 'BQI855', 0, '2021-04-13 06:16:42', '2021-04-13 06:16:42'),
(263, 113, 95, 41, '1.00', 'Piece', '100.00', '0.00', '100.00', '100.00', '5.00', '5.00', '105.00', '105.00', '0.00', '0.00', 0, NULL, 0, '2021-04-13 06:16:42', '2021-04-13 06:16:42'),
(264, 113, 115, NULL, '1.00', 'Piece', '100.00', '0.00', '100.00', '100.00', '0.00', '0.00', '100.00', '100.00', '0.00', '0.00', 0, 'MP7555', 0, '2021-04-13 06:16:42', '2021-04-13 06:16:42'),
(265, 113, 95, 40, '1.00', 'Piece', '200.00', '0.00', '200.00', '200.00', '5.00', '10.00', '210.00', '210.00', '0.00', '0.00', 0, NULL, 0, '2021-04-13 06:16:42', '2021-04-13 06:16:42'),
(266, 114, 110, NULL, '1.00', 'Piece', '100.00', '0.00', '100.00', '100.00', '5.00', '5.00', '105.00', '105.00', '0.00', '0.00', 0, NULL, 0, '2021-04-24 07:27:48', '2021-04-24 08:10:36'),
(267, 114, 106, NULL, '1.00', 'Piece', '100.00', '0.00', '100.00', '100.00', '5.00', '5.00', '105.00', '105.00', '0.00', '0.00', 0, NULL, 0, '2021-04-24 07:27:48', '2021-04-24 08:10:36'),
(268, 114, 107, NULL, '1.00', 'Piece', '100.00', '0.00', '100.00', '100.00', '5.00', '5.00', '105.00', '105.00', '0.00', '0.00', 0, NULL, 0, '2021-04-24 07:27:48', '2021-04-24 08:10:36'),
(269, 114, 99, 42, '1.00', 'Piece', '100.00', '0.00', '100.00', '100.00', '0.00', '0.00', '100.00', '100.00', '0.00', '0.00', 0, NULL, 0, '2021-04-24 07:27:48', '2021-04-24 08:10:36'),
(270, 114, 98, NULL, '1.00', 'Piece', '100.00', '0.00', '100.00', '100.00', '0.00', '0.00', '100.00', '100.00', '0.00', '0.00', 0, NULL, 0, '2021-04-24 07:27:48', '2021-04-24 08:10:36'),
(271, 114, 96, NULL, '2.00', 'Piece', '100.00', '0.00', '100.00', '200.00', '5.00', '5.00', '105.00', '210.00', '0.00', '0.00', 0, NULL, 0, '2021-04-24 07:27:48', '2021-04-24 08:10:36'),
(272, 114, 124, NULL, '1.00', 'Piece', '700.00', '0.00', '700.00', '700.00', '5.00', '35.00', '735.00', '735.00', '0.00', '0.00', 0, NULL, 0, '2021-04-24 07:27:48', '2021-04-24 08:10:36'),
(273, 114, 95, 40, '1.00', 'Piece', '200.00', '0.00', '200.00', '200.00', '5.00', '10.00', '210.00', '210.00', '0.00', '0.00', 0, NULL, 0, '2021-04-24 07:27:48', '2021-04-24 08:10:36'),
(274, 115, 95, 40, '2.00', 'Piece', '200.00', '0.00', '200.00', '400.00', '5.00', '10.00', '210.00', '420.00', '0.00', '0.00', 0, NULL, 0, '2021-04-24 07:43:18', '2021-04-24 07:43:18'),
(275, 115, 95, 41, '2.00', 'Piece', '100.00', '0.00', '100.00', '200.00', '5.00', '5.00', '105.00', '210.00', '0.00', '0.00', 0, NULL, 0, '2021-04-24 07:43:18', '2021-04-24 07:43:18'),
(277, 116, 131, NULL, '1.00', 'Piece', '100.00', '0.00', '100.00', '100.00', '5.00', '5.00', '105.00', '105.00', '0.00', '0.00', 0, NULL, 0, '2021-04-25 03:50:24', '2021-04-25 03:50:24'),
(278, 119, 141, NULL, '2.00', 'Piece', '13000.00', '0.00', '13000.00', '26000.00', '0.00', '0.00', '13000.00', '26000.00', '0.00', '0.00', 0, NULL, 0, '2021-04-28 08:37:51', '2021-04-28 08:37:51'),
(279, 119, 137, NULL, '12.00', 'Piece', '130.00', '0.00', '130.00', '1560.00', '0.00', '0.00', '130.00', '1560.00', '0.00', '0.00', 0, NULL, 0, '2021-04-28 08:37:51', '2021-04-28 08:37:51'),
(280, 119, 139, NULL, '1.00', 'Piece', '2850.00', '0.00', '2850.00', '2850.00', '0.00', '0.00', '2850.00', '2850.00', '0.00', '0.00', 0, NULL, 0, '2021-04-28 08:37:51', '2021-04-28 08:37:51'),
(281, 119, 138, NULL, '2.00', 'Piece', '1650.00', '0.00', '1650.00', '3300.00', '0.00', '0.00', '1650.00', '3300.00', '0.00', '0.00', 0, NULL, 0, '2021-04-28 08:37:51', '2021-04-28 08:37:51'),
(282, 119, 140, NULL, '2.00', 'Piece', '18500.00', '0.00', '18500.00', '37000.00', '0.00', '0.00', '18500.00', '37000.00', '0.00', '0.00', 0, NULL, 0, '2021-04-28 08:37:51', '2021-04-28 08:37:51'),
(283, 119, 135, NULL, '6.00', 'Piece', '280.00', '0.00', '280.00', '1680.00', '0.00', '0.00', '280.00', '1680.00', '0.00', '0.00', 0, NULL, 0, '2021-04-28 08:37:51', '2021-04-28 08:37:51'),
(284, 119, 136, NULL, '6.00', 'Piece', '570.00', '0.00', '570.00', '3420.00', '0.00', '0.00', '570.00', '3420.00', '0.00', '0.00', 0, NULL, 0, '2021-04-28 08:37:51', '2021-04-28 08:37:51'),
(285, 120, 140, NULL, '100.00', 'Piece', '18500.00', '0.00', '18500.00', '1850000.00', '0.00', '0.00', '18500.00', '1850000.00', '0.00', '0.00', 0, NULL, 0, '2021-04-28 09:13:31', '2021-04-28 09:13:31'),
(286, 121, 141, NULL, '100.00', 'Piece', '13000.00', '0.00', '13000.00', '1300000.00', '0.00', '0.00', '13000.00', '1300000.00', '0.00', '0.00', 0, NULL, 0, '2021-04-28 09:14:24', '2021-04-28 09:14:24'),
(287, 122, 138, NULL, '10.00', 'Piece', '1650.00', '0.00', '1650.00', '16500.00', '0.00', '0.00', '1650.00', '16500.00', '0.00', '0.00', 0, NULL, 0, '2021-04-28 09:18:38', '2021-04-28 09:18:38'),
(288, 123, 115, NULL, '100.00', 'Piece', '100.00', '0.00', '100.00', '10000.00', '0.00', '0.00', '100.00', '10000.00', '0.00', '0.00', 0, 'MPH785544', 0, '2021-05-04 03:58:13', '2021-05-04 04:00:28'),
(289, 124, 96, NULL, '8.00', 'Piece', '100.00', '0.00', '100.00', '800.00', '5.00', '5.00', '105.00', '840.00', '0.00', '0.00', 0, NULL, 0, '2021-06-06 10:00:30', '2021-06-06 10:00:30'),
(290, 125, 96, NULL, '8.00', 'Piece', '100.00', '0.00', '100.00', '800.00', '5.00', '5.00', '105.00', '840.00', '0.00', '0.00', 0, NULL, 0, '2021-06-06 10:01:15', '2021-06-06 10:01:15'),
(291, 126, 96, NULL, '2.00', 'Piece', '100.00', '0.00', '100.00', '200.00', '5.00', '5.00', '105.00', '210.00', '0.00', '0.00', 0, NULL, 0, '2021-06-10 06:47:33', '2021-06-10 06:47:33'),
(292, 127, 96, NULL, '1.00', 'Piece', '100.00', '0.00', '100.00', '100.00', '5.00', '5.00', '105.00', '105.00', '0.00', '0.00', 0, NULL, 0, '2021-06-10 06:49:00', '2021-06-10 06:49:00'),
(293, 128, 153, 48, '100.00', 'Piece', '2000.00', '0.00', '2000.00', '200000.00', '0.00', '0.00', '2000.00', '200000.00', '0.00', '0.00', 0, NULL, 0, '2021-06-17 09:15:13', '2021-06-17 09:15:13'),
(294, 128, 153, 46, '100.00', 'Piece', '2000.00', '0.00', '2000.00', '200000.00', '0.00', '0.00', '2000.00', '200000.00', '0.00', '0.00', 0, NULL, 0, '2021-06-17 09:15:13', '2021-06-17 09:15:13'),
(295, 128, 153, 43, '100.00', 'Piece', '2000.00', '0.00', '2000.00', '200000.00', '0.00', '0.00', '2000.00', '200000.00', '0.00', '0.00', 0, NULL, 0, '2021-06-17 09:15:13', '2021-06-17 09:15:13'),
(296, 128, 153, 44, '100.00', 'Piece', '2000.00', '0.00', '2000.00', '200000.00', '0.00', '0.00', '2000.00', '200000.00', '0.00', '0.00', 0, NULL, 0, '2021-06-17 09:15:13', '2021-06-17 09:15:13'),
(297, 129, 154, NULL, '100.00', 'Piece', '40000.00', '0.00', '40000.00', '4000000.00', '5.00', '2000.00', '42000.00', '4200000.00', '0.00', '0.00', 0, NULL, 0, '2021-06-17 09:16:01', '2021-06-17 09:16:01');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_returns`
--

CREATE TABLE `purchase_returns` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `purchase_id` bigint(20) UNSIGNED DEFAULT NULL,
  `admin_id` bigint(20) UNSIGNED DEFAULT NULL,
  `warehouse_id` bigint(20) UNSIGNED DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `supplier_id` bigint(20) UNSIGNED DEFAULT NULL,
  `return_type` tinyint(4) DEFAULT NULL COMMENT '1=purchase_invoice_return;2=supplier_purchase_return',
  `total_return_amount` decimal(22,2) NOT NULL DEFAULT 0.00,
  `total_return_due` decimal(22,2) NOT NULL DEFAULT 0.00,
  `total_return_due_received` decimal(22,2) NOT NULL DEFAULT 0.00,
  `purchase_tax_percent` decimal(22,2) NOT NULL DEFAULT 0.00,
  `purchase_tax_amount` decimal(22,2) NOT NULL DEFAULT 0.00,
  `date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `month` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `year` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `report_date` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_returns`
--

INSERT INTO `purchase_returns` (`id`, `invoice_id`, `purchase_id`, `admin_id`, `warehouse_id`, `branch_id`, `supplier_id`, `return_type`, `total_return_amount`, `total_return_due`, `total_return_due_received`, `purchase_tax_percent`, `purchase_tax_amount`, `date`, `month`, `year`, `report_date`, `created_at`, `updated_at`) VALUES
(8, 'PRI21042549619', 114, 2, 7, NULL, 26, 1, '210.00', '105.00', '0.00', '0.00', '0.00', '2021-04-27', 'April', '2021', '2021-04-26 18:00:00', '2021-04-25 04:32:37', '2021-04-27 07:49:07'),
(9, 'PRI21042523275', NULL, 2, 7, NULL, 27, 2, '105.00', '105.00', '0.00', '0.00', '0.00', '2021-04-25', 'April', '2021', '2021-04-24 18:00:00', '2021-04-25 05:33:17', '2021-04-25 13:54:34'),
(13, 'PRI21042543944', NULL, 3, NULL, 26, 26, 2, '1863.75', '1863.75', '0.00', '5.00', '78.75', '2021-04-25', 'April', '2021', '2021-04-24 18:00:00', '2021-04-25 07:16:52', '2021-04-25 09:15:19'),
(14, 'PRI21042544228', 113, 3, NULL, 26, 26, 1, '210.00', '0.00', '210.00', '0.00', '0.00', '2021-04-25', 'April', '2021', '2021-04-24 18:00:00', '2021-04-25 07:40:00', '2021-04-25 07:40:37'),
(17, 'PRI21042568314', 115, 2, 7, NULL, 27, 1, '210.00', '210.00', '0.00', '0.00', '0.00', '2021-04-25', 'April', '2021', '2021-04-24 18:00:00', '2021-04-25 13:50:54', '2021-04-25 13:50:54'),
(18, 'PRI21061068636', 126, 2, 7, NULL, 31, 1, '105.00', '105.00', '0.00', '0.00', '0.00', '2021-06-10', 'June', '2021', '2021-06-09 18:00:00', '2021-06-10 06:48:02', '2021-06-10 06:48:02');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_return_products`
--

CREATE TABLE `purchase_return_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `purchase_return_id` bigint(20) UNSIGNED NOT NULL,
  `purchase_product_id` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'this_field_only_for_purchase_invoice_return.',
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_variant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `return_qty` decimal(22,2) NOT NULL DEFAULT 0.00,
  `unit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `return_subtotal` decimal(22,2) NOT NULL DEFAULT 0.00,
  `is_delete_in_update` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_return_products`
--

INSERT INTO `purchase_return_products` (`id`, `purchase_return_id`, `purchase_product_id`, `product_id`, `product_variant_id`, `return_qty`, `unit`, `return_subtotal`, `is_delete_in_update`, `created_at`, `updated_at`) VALUES
(109, 8, 266, 110, NULL, '1.00', 'Piece', '105.00', 0, '2021-04-25 04:32:37', '2021-04-25 04:32:37'),
(110, 8, 267, 106, NULL, '1.00', 'Piece', '105.00', 0, '2021-04-25 04:32:37', '2021-04-25 04:32:37'),
(111, 8, 268, 107, NULL, '0.00', 'Piece', '0.00', 0, '2021-04-25 04:32:37', '2021-04-25 04:32:37'),
(112, 8, 269, 99, 42, '0.00', 'Piece', '0.00', 0, '2021-04-25 04:32:37', '2021-04-25 04:32:37'),
(113, 8, 270, 98, NULL, '0.00', 'Piece', '0.00', 0, '2021-04-25 04:32:37', '2021-04-25 04:32:37'),
(114, 8, 271, 96, NULL, '0.00', 'Piece', '0.00', 0, '2021-04-25 04:32:37', '2021-04-25 04:32:37'),
(115, 8, 272, 124, NULL, '0.00', 'Piece', '0.00', 0, '2021-04-25 04:32:37', '2021-04-25 04:32:37'),
(116, 8, 273, 95, 40, '0.00', 'Piece', '0.00', 0, '2021-04-25 04:32:37', '2021-04-25 04:32:37'),
(122, 14, 261, 106, NULL, '1.00', 'Piece', '105.00', 0, '2021-04-25 07:40:00', '2021-04-25 07:40:00'),
(123, 14, 262, 96, NULL, '0.00', 'Piece', '0.00', 0, '2021-04-25 07:40:00', '2021-04-25 07:40:00'),
(124, 14, 263, 95, 41, '0.00', 'Piece', '0.00', 0, '2021-04-25 07:40:00', '2021-04-25 07:40:00'),
(125, 14, 264, 115, NULL, '0.00', 'Piece', '0.00', 0, '2021-04-25 07:40:00', '2021-04-25 07:40:00'),
(126, 14, 265, 95, 40, '0.00', 'Piece', '0.00', 0, '2021-04-25 07:40:01', '2021-04-25 07:40:01'),
(131, 13, NULL, 95, 40, '5.00', 'Piece', '1050.00', 0, '2021-04-25 09:15:19', '2021-04-25 09:15:19'),
(132, 13, NULL, 95, 41, '5.00', 'Piece', '525.00', 0, '2021-04-25 09:15:19', '2021-04-25 09:15:19'),
(133, 13, NULL, 106, NULL, '2.00', 'Piece', '210.00', 0, '2021-04-25 09:15:19', '2021-04-25 09:15:19'),
(134, 17, 274, 95, 40, '1.00', 'Piece', '210.00', 0, '2021-04-25 13:50:54', '2021-04-25 13:50:54'),
(135, 17, 275, 95, 41, '0.00', 'Piece', '0.00', 0, '2021-04-25 13:50:54', '2021-04-25 13:50:54'),
(137, 9, NULL, 96, NULL, '1.00', 'Piece', '105.00', 0, '2021-04-25 13:54:34', '2021-04-25 13:54:34'),
(138, 18, 291, 96, NULL, '1.00', 'Piece', '105.00', 0, '2021-06-10 06:48:02', '2021-06-10 06:48:02');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `created_at`, `updated_at`) VALUES
(10, 'Branch Manager', '2021-01-26 10:15:04', '2021-01-26 10:37:48'),
(12, 'Cahier', '2021-01-28 06:26:55', '2021-01-28 06:26:55'),
(14, 'Test_role', '2021-02-15 08:19:34', '2021-02-15 08:19:34'),
(16, 'Test Role Name', '2021-05-02 13:02:18', '2021-05-02 13:02:18');

-- --------------------------------------------------------

--
-- Table structure for table `role_permissions`
--

CREATE TABLE `role_permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `roles` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `supplier` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customers` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `purchase` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `s_adjust` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sale` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `register` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `brand` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unit` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `report` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `setup` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dashboard` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `accounting` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hrms` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `essential` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `manufacturing` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `project` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `repair` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `superadmin` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `e_commerce` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_super_admin_role` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_permissions`
--

INSERT INTO `role_permissions` (`id`, `role_id`, `user`, `roles`, `supplier`, `customers`, `product`, `purchase`, `s_adjust`, `sale`, `register`, `brand`, `category`, `unit`, `report`, `setup`, `dashboard`, `accounting`, `hrms`, `essential`, `manufacturing`, `project`, `repair`, `superadmin`, `e_commerce`, `is_super_admin_role`, `created_at`, `updated_at`) VALUES
(7, 10, '{\"user_view\":1,\"user_add\":1,\"user_edit\":1,\"user_delete\":1}', '{\"role_view\":1,\"role_add\":1,\"role_edit\":1,\"role_delete\":1}', '{\"supplier_all\":1,\"supplier_add\":1,\"supplier_edit\":1,\"supplier_delete\":1}', '{\"customer_all\":1,\"customer_add\":1,\"customer_edit\":1,\"customer_delete\":1}', '{\"product_all\":1,\"product_add\":1,\"product_edit\":1,\"openingStock_add\":1,\"product_delete\":0,\"pro_unit_cost\":1}', '{\"purchase_all\":1,\"purchase_add\":1,\"purchase_edit\":1,\"purchase_delete\":1,\"purchase_payment\":1,\"purchase_return\":1,\"status_update\":1}', '{\"adjustment_all\":1,\"adjustment_add\":1,\"adjustment_delete\":1}', '{\"pos_all\":1,\"pos_add\":1,\"pos_edit\":1,\"pos_delete\":1,\"sale_access\":1,\"sale_draft\":1,\"sale_quotation\":1,\"sale_all_own\":1,\"sale_payment\":1,\"edit_price_sale_screen\":0,\"edit_price_pos_screen\":1,\"edit_discount_pos_screen\":1,\"edit_discount_sale_screen\":1,\"shipment_access\":1,\"return_access\":1}', '{\"register_view\":1,\"register_close\":1}', '{\"brand_all\":1,\"brand_add\":1,\"brand_edit\":1,\"brand_delete\":1}', '{\"category_all\":1,\"category_add\":1,\"category_edit\":1,\"category_delete\":1}', '{\"unit_all\":1,\"unit_add\":1,\"unit_edit\":1,\"unit_delete\":1}', '{\"loss_profit_report\":1,\"purchase_sale_report\":1,\"tax_report\":1,\"cus_sup_report\":1,\"stock_report\":1,\"stock_adjustment_report\":1,\"tranding_report\":1,\"item_report\":1,\"pro_purchase_report\":1,\"pro_sale_report\":1,\"purchase_payment_report\":1,\"sale_payment_report\":1,\"expanse_report\":1,\"register_report\":1,\"representative_report\":1}', '{\"tax\":0,\"branch\":0,\"warehouse\":0,\"g_settings\":0}', '{\"dash_data\":1}', '{\"ac_access\":1}', '{\"leave_type\":1,\"view_own_leave\":1,\"leave_approve\":1,\"attendance_all\":1,\"view_own_attendance\":1,\"view_a_d\":1,\"department\":1,\"designation\":1}', '{\"assign_todo\":1,\"create_msg\":1,\"view_msg\":1}', '{\"menuf_view\":1,\"menuf_add\":1,\"menuf_edit\":1,\"menuf_delete\":1}', '{\"proj_view\":1,\"proj_create\":1,\"proj_edit\":1,\"proj_delete\":1}', '{\"ripe_add_invo\":1,\"ripe_edit_invo\":1,\"ripe_view_invo\":1,\"ripe_delete_invo\":1,\"change_invo_status\":1,\"ripe_jop_sheet_status\":1,\"ripe_jop_sheet_add\":1,\"ripe_jop_sheet_edit\":1,\"ripe_jop_sheet_delete\":1,\"ripe_only_assinged_job_sheet\":1,\"ripe_view_all_job_sheet\":1}', '{\"superadmin_access_pack_subscrip\":1}', '{\"e_com_sync_pro_cate\":1,\"e_com_sync_pro\":1,\"e_com_sync_order\":1,\"e_com_map_tax_rate\":1}', 0, '2021-01-26 10:15:04', '2021-05-11 07:19:17'),
(8, NULL, '{\"user_view\":1,\"user_add\":1,\"user_edit\":1,\"user_delete\":1}', '{\"role_view\":1,\"role_add\":1,\"role_edit\":1,\"role_delete\":1}', '{\"supplier_all\":1,\"supplier_add\":1,\"supplier_edit\":1,\"supplier_delete\":1}', '{\"customer_all\":1,\"customer_add\":1,\"customer_edit\":1,\"customer_delete\":1}', '{\"product_all\":1,\"product_add\":1,\"product_edit\":1,\"openingStock_add\":1,\"product_delete\":1,\"pro_unit_cost\":1}', '{\"purchase_all\":1,\"purchase_add\":1,\"purchase_edit\":1,\"purchase_delete\":1,\"purchase_payment\":1,\"purchase_return\":1,\"status_update\":1}', '{\"adjustment_all\":1,\"adjustment_add\":1,\"adjustment_delete\":1}', '{\"pos_all\":1,\"pos_add\":1,\"pos_edit\":1,\"pos_delete\":1,\"sale_access\":1,\"sale_draft\":1,\"sale_quotation\":1,\"sale_all_own\":1,\"sale_payment\":1,\"edit_price_sale_screen\":1,\"edit_price_pos_screen\":1,\"edit_discount_pos_screen\":1,\"edit_discount_sale_screen\":1,\"shipment_access\":1,\"return_access\":1}', '{\"register_view\":1,\"register_close\":1}', '{\"brand_all\":1,\"brand_add\":1,\"brand_edit\":1,\"brand_delete\":1}', '{\"category_all\":1,\"category_add\":1,\"category_edit\":1,\"category_delete\":1}', '{\"unit_all\":1,\"unit_add\":1,\"unit_edit\":1,\"unit_delete\":1}', '{\"loss_profit_report\":1,\"purchase_sale_report\":1,\"tax_report\":1,\"cus_sup_report\":1,\"stock_report\":1,\"stock_adjustment_report\":1,\"tranding_report\":1,\"item_report\":1,\"pro_purchase_report\":1,\"pro_sale_report\":1,\"purchase_payment_report\":1,\"sale_payment_report\":1,\"expanse_report\":1,\"register_report\":1,\"representative_report\":1}', '{\"tax\":1,\"branch\":1,\"warehouse\":1,\"g_settings\":1}', '{\"dash_data\":1}', '{\"ac_access\":1}', '{\"leave_type\":1,\"view_own_leave\":1,\"leave_approve\":1,\"attendance_all\":1,\"view_own_attendance\":1,\"view_a_d\":1,\"department\":1,\"designation\":1}', '{\"assign_todo\":1,\"create_msg\":1,\"view_msg\":1}', '{\"menuf_view\":1,\"menuf_add\":1,\"menuf_edit\":1,\"menuf_delete\":1}', '{\"proj_view\":1,\"proj_create\":1,\"proj_edit\":1,\"proj_delete\":1}', '{\"ripe_add_invo\":1,\"ripe_edit_invo\":1,\"ripe_view_invo\":1,\"ripe_delete_invo\":1,\"change_invo_status\":1,\"ripe_jop_sheet_status\":1,\"ripe_jop_sheet_add\":1,\"ripe_jop_sheet_edit\":1,\"ripe_jop_sheet_delete\":1,\"ripe_only_assinged_job_sheet\":1,\"ripe_view_all_job_sheet\":1}', '{\"superadmin_access_pack_subscrip\":1}', '{\"e_com_sync_pro_cate\":1,\"e_com_sync_pro\":1,\"e_com_sync_order\":1,\"e_com_map_tax_rate\":1}', 1, '2021-01-26 10:45:14', '2021-01-26 10:45:14'),
(9, 12, '{\"user_view\":1,\"user_add\":0,\"user_edit\":0,\"user_delete\":0}', '{\"role_view\":0,\"role_add\":0,\"role_edit\":0,\"role_delete\":0}', '{\"supplier_all\":0,\"supplier_add\":0,\"supplier_edit\":0,\"supplier_delete\":0}', '{\"customer_all\":0,\"customer_add\":0,\"customer_edit\":0,\"customer_delete\":0}', '{\"product_all\":0,\"product_add\":0,\"product_edit\":0,\"openingStock_add\":0,\"product_delete\":0,\"pro_unit_cost\":0}', '{\"purchase_all\":0,\"purchase_add\":0,\"purchase_edit\":0,\"purchase_delete\":0,\"purchase_payment\":0,\"purchase_return\":1,\"status_update\":0}', '{\"adjustment_all\":0,\"adjustment_add\":0,\"adjustment_delete\":0}', '{\"pos_all\":1,\"pos_add\":1,\"pos_edit\":1,\"pos_delete\":0,\"sale_access\":1,\"sale_draft\":1,\"sale_quotation\":1,\"sale_all_own\":1,\"sale_payment\":1,\"edit_price_sale_screen\":0,\"edit_price_pos_screen\":1,\"edit_discount_pos_screen\":1,\"edit_discount_sale_screen\":1,\"shipment_access\":1,\"return_access\":1}', '{\"register_view\":1,\"register_close\":1}', '{\"brand_all\":0,\"brand_add\":0,\"brand_edit\":0,\"brand_delete\":0}', '{\"category_all\":1,\"category_add\":1,\"category_edit\":1,\"category_delete\":1}', '{\"unit_all\":0,\"unit_add\":0,\"unit_edit\":0,\"unit_delete\":0}', '{\"loss_profit_report\":1,\"purchase_sale_report\":1,\"tax_report\":1,\"cus_sup_report\":1,\"stock_report\":1,\"stock_adjustment_report\":1,\"tranding_report\":1,\"item_report\":1,\"pro_purchase_report\":1,\"pro_sale_report\":1,\"purchase_payment_report\":1,\"sale_payment_report\":1,\"expanse_report\":1,\"register_report\":1,\"representative_report\":1}', '{\"tax\":0,\"branch\":0,\"warehouse\":0,\"g_settings\":0}', '{\"dash_data\":1}', '{\"ac_access\":0}', '{\"leave_type\":0,\"view_own_leave\":0,\"leave_approve\":0,\"attendance_all\":0,\"view_own_attendance\":0,\"view_a_d\":0,\"department\":0,\"designation\":0}', '{\"assign_todo\":0,\"create_msg\":0,\"view_msg\":0}', '{\"menuf_view\":0,\"menuf_add\":0,\"menuf_edit\":0,\"menuf_delete\":0}', '{\"proj_view\":0,\"proj_create\":0,\"proj_edit\":0,\"proj_delete\":0}', '{\"ripe_add_invo\":0,\"ripe_edit_invo\":0,\"ripe_view_invo\":0,\"ripe_delete_invo\":0,\"change_invo_status\":0,\"ripe_jop_sheet_status\":0,\"ripe_jop_sheet_add\":0,\"ripe_jop_sheet_edit\":0,\"ripe_jop_sheet_delete\":0,\"ripe_only_assinged_job_sheet\":0,\"ripe_view_all_job_sheet\":0}', '{\"superadmin_access_pack_subscrip\":0}', '{\"e_com_sync_pro_cate\":0,\"e_com_sync_pro\":0,\"e_com_sync_order\":0,\"e_com_map_tax_rate\":0}', 0, '2021-01-28 06:26:55', '2021-05-03 04:37:51'),
(10, 14, '{\"user_view\":0,\"user_add\":0,\"user_edit\":0,\"user_delete\":0}', '{\"role_view\":0,\"role_add\":0,\"role_edit\":0,\"role_delete\":0}', '{\"supplier_all\":0,\"supplier_add\":0,\"supplier_edit\":0,\"supplier_delete\":0}', '{\"customer_all\":0,\"customer_add\":0,\"customer_edit\":0,\"customer_delete\":0}', '{\"product_all\":0,\"product_add\":0,\"product_edit\":0,\"openingStock_add\":0,\"product_delete\":0,\"pro_unit_cost\":0}', '{\"purchase_all\":0,\"purchase_add\":0,\"purchase_edit\":0,\"purchase_delete\":0,\"purchase_payment\":0,\"purchase_return\":0,\"status_update\":0}', '{\"adjustment_all\":0,\"adjustment_add\":0,\"adjustment_delete\":0}', '{\"pos_all\":0,\"pos_add\":0,\"pos_edit\":0,\"pos_delete\":0,\"sale_access\":0,\"sale_draft\":0,\"sale_quotation\":0,\"sale_all_own\":0,\"sale_payment\":0,\"edit_price_sale_screen\":0,\"edit_price_pos_screen\":0,\"edit_discount_pos_screen\":0,\"edit_discount_sale_screen\":0,\"shipment_access\":0,\"return_access\":0}', '{\"register_view\":0,\"register_close\":0}', '{\"brand_all\":0,\"brand_add\":0,\"brand_edit\":0,\"brand_delete\":0}', '{\"category_all\":0,\"category_add\":0,\"category_edit\":0,\"category_delete\":0}', '{\"unit_all\":0,\"unit_add\":0,\"unit_edit\":0,\"unit_delete\":0}', '{\"loss_profit_report\":1,\"purchase_sale_report\":1,\"tax_report\":1,\"cus_sup_report\":1,\"stock_report\":1,\"stock_adjustment_report\":1,\"tranding_report\":1,\"item_report\":1,\"pro_purchase_report\":1,\"pro_sale_report\":1,\"purchase_payment_report\":1,\"sale_payment_report\":1,\"expanse_report\":1,\"register_report\":1,\"representative_report\":1}', '{\"tax\":0,\"branch\":0,\"warehouse\":0,\"g_settings\":0}', '{\"dash_data\":0}', '{\"ac_access\":0}', '{\"leave_type\":0,\"view_own_leave\":0,\"leave_approve\":0,\"attendance_all\":0,\"view_own_attendance\":0,\"view_a_d\":0,\"department\":0,\"designation\":0}', '{\"assign_todo\":0,\"create_msg\":0,\"view_msg\":0}', '{\"menuf_view\":0,\"menuf_add\":0,\"menuf_edit\":0,\"menuf_delete\":0}', '{\"proj_view\":0,\"proj_create\":0,\"proj_edit\":0,\"proj_delete\":0}', '{\"ripe_add_invo\":0,\"ripe_edit_invo\":0,\"ripe_view_invo\":0,\"ripe_delete_invo\":0,\"change_invo_status\":0,\"ripe_jop_sheet_status\":0,\"ripe_jop_sheet_add\":0,\"ripe_jop_sheet_edit\":0,\"ripe_jop_sheet_delete\":0,\"ripe_only_assinged_job_sheet\":0,\"ripe_view_all_job_sheet\":0}', '{\"superadmin_access_pack_subscrip\":0}', '{\"e_com_sync_pro_cate\":0,\"e_com_sync_pro\":0,\"e_com_sync_order\":0,\"e_com_map_tax_rate\":0}', 0, '2021-02-15 08:19:34', '2021-02-15 08:19:34'),
(12, 16, '{\"user_view\":0,\"user_add\":0,\"user_edit\":0,\"user_delete\":0}', '{\"role_view\":0,\"role_add\":0,\"role_edit\":0,\"role_delete\":0}', '{\"supplier_all\":0,\"supplier_add\":0,\"supplier_edit\":0,\"supplier_delete\":0}', '{\"customer_all\":0,\"customer_add\":0,\"customer_edit\":0,\"customer_delete\":0}', '{\"product_all\":0,\"product_add\":0,\"product_edit\":0,\"openingStock_add\":0,\"product_delete\":0,\"pro_unit_cost\":0}', '{\"purchase_all\":0,\"purchase_add\":0,\"purchase_edit\":0,\"purchase_delete\":0,\"purchase_payment\":0,\"purchase_return\":0,\"status_update\":0}', '{\"adjustment_all\":0,\"adjustment_add\":0,\"adjustment_delete\":0}', '{\"pos_all\":1,\"pos_add\":1,\"pos_edit\":1,\"pos_delete\":1,\"sale_access\":1,\"sale_draft\":1,\"sale_quotation\":1,\"sale_all_own\":1,\"sale_payment\":1,\"edit_price_sale_screen\":0,\"edit_price_pos_screen\":1,\"edit_discount_pos_screen\":1,\"edit_discount_sale_screen\":1,\"shipment_access\":1,\"return_access\":1}', '{\"register_view\":1,\"register_close\":1}', '{\"brand_all\":0,\"brand_add\":0,\"brand_edit\":0,\"brand_delete\":0}', '{\"category_all\":1,\"category_add\":1,\"category_edit\":1,\"category_delete\":1}', '{\"unit_all\":0,\"unit_add\":0,\"unit_edit\":0,\"unit_delete\":0}', '{\"loss_profit_report\":0,\"purchase_sale_report\":0,\"tax_report\":0,\"cus_sup_report\":0,\"stock_report\":0,\"stock_adjustment_report\":0,\"tranding_report\":0,\"item_report\":0,\"pro_purchase_report\":0,\"pro_sale_report\":0,\"purchase_payment_report\":0,\"sale_payment_report\":0,\"expanse_report\":0,\"register_report\":0,\"representative_report\":0}', '{\"tax\":0,\"branch\":0,\"warehouse\":0,\"g_settings\":0}', '{\"dash_data\":0}', '{\"ac_access\":0}', '{\"leave_type\":0,\"view_own_leave\":0,\"leave_approve\":0,\"attendance_all\":0,\"view_own_attendance\":0,\"view_a_d\":0,\"department\":0,\"designation\":0}', '{\"assign_todo\":0,\"create_msg\":0,\"view_msg\":0}', '{\"menuf_view\":0,\"menuf_add\":0,\"menuf_edit\":0,\"menuf_delete\":0}', '{\"proj_view\":0,\"proj_create\":0,\"proj_edit\":0,\"proj_delete\":0}', '{\"ripe_add_invo\":0,\"ripe_edit_invo\":0,\"ripe_view_invo\":0,\"ripe_delete_invo\":0,\"change_invo_status\":0,\"ripe_jop_sheet_status\":0,\"ripe_jop_sheet_add\":0,\"ripe_jop_sheet_edit\":0,\"ripe_jop_sheet_delete\":0,\"ripe_only_assinged_job_sheet\":0,\"ripe_view_all_job_sheet\":0}', '{\"superadmin_access_pack_subscrip\":0}', '{\"e_com_sync_pro_cate\":0,\"e_com_sync_pro\":0,\"e_com_sync_order\":0,\"e_com_map_tax_rate\":0}', 0, '2021-05-02 13:02:18', '2021-06-09 10:34:39');

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_id` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `warehouse_id` bigint(20) UNSIGNED DEFAULT NULL,
  `customer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `pay_term` tinyint(4) DEFAULT NULL,
  `pay_term_number` bigint(20) DEFAULT NULL,
  `total_item` bigint(20) NOT NULL,
  `net_total_amount` decimal(22,2) NOT NULL DEFAULT 0.00,
  `order_discount_type` tinyint(4) NOT NULL DEFAULT 1,
  `order_discount` decimal(22,2) NOT NULL DEFAULT 0.00,
  `order_discount_amount` decimal(22,2) NOT NULL DEFAULT 0.00,
  `shipment_details` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipment_address` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipment_charge` decimal(22,2) NOT NULL DEFAULT 0.00,
  `shipment_status` tinyint(4) DEFAULT NULL,
  `delivered_to` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sale_note` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_tax_percent` decimal(22,2) NOT NULL DEFAULT 0.00,
  `order_tax_amount` decimal(22,2) NOT NULL DEFAULT 0.00,
  `total_payable_amount` decimal(22,2) NOT NULL DEFAULT 0.00,
  `paid` decimal(22,2) NOT NULL DEFAULT 0.00,
  `change_amount` decimal(22,2) NOT NULL DEFAULT 0.00,
  `due` decimal(22,2) NOT NULL DEFAULT 0.00,
  `is_return_available` tinyint(1) NOT NULL DEFAULT 0,
  `ex_status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0=exchangeed,1=exchanged',
  `sale_return_amount` decimal(22,2) NOT NULL DEFAULT 0.00,
  `sale_return_due` decimal(22,2) NOT NULL DEFAULT 0.00,
  `payment_note` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `admin_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1=final;2=draft;3=challan;4=quatation;5=hold;6=suspended',
  `is_fixed_challen` tinyint(1) NOT NULL DEFAULT 0,
  `date` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `time` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `report_date` timestamp NULL DEFAULT NULL,
  `month` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `year` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attachment` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1=add_sale;2=pos',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `invoice_id`, `branch_id`, `warehouse_id`, `customer_id`, `pay_term`, `pay_term_number`, `total_item`, `net_total_amount`, `order_discount_type`, `order_discount`, `order_discount_amount`, `shipment_details`, `shipment_address`, `shipment_charge`, `shipment_status`, `delivered_to`, `sale_note`, `order_tax_percent`, `order_tax_amount`, `total_payable_amount`, `paid`, `change_amount`, `due`, `is_return_available`, `ex_status`, `sale_return_amount`, `sale_return_due`, `payment_note`, `admin_id`, `status`, `is_fixed_challen`, `date`, `time`, `report_date`, `month`, `year`, `attachment`, `created_by`, `created_at`, `updated_at`) VALUES
(11, 'PMT002104107937', NULL, 7, NULL, NULL, NULL, 2, '262.50', 2, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '262.50', '262.50', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 0, '10-04-2021', '04:14:47 pm', '2021-04-09 18:00:00', 'April', '2021', NULL, 1, '2021-04-10 10:14:47', '2021-04-10 10:14:47'),
(12, 'PMT002104104362', NULL, 7, NULL, NULL, NULL, 2, '262.50', 2, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '262.50', '262.50', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 0, '10-04-2021', '04:14:56 pm', '2021-04-09 18:00:00', 'April', '2021', NULL, 1, '2021-04-10 10:14:56', '2021-04-10 10:14:56'),
(14, 'PMT002104107345', NULL, 7, 39, NULL, NULL, 9, '5918.75', 2, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '5918.75', '5919.75', '14191.25', '-1.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 0, '10-04-2021', '05:15:05 pm', '2021-04-09 18:00:00', 'April', '2021', NULL, 1, '2021-04-10 11:15:05', '2021-04-25 13:16:33'),
(17, 'PMT002104115489', 26, NULL, NULL, NULL, NULL, 2, '262.50', 2, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '262.50', '262.50', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 3, 1, 0, '11-04-2021', '12:07:23 pm', '2021-04-10 18:00:00', 'April', '2021', NULL, 1, '2021-04-11 06:07:23', '2021-04-11 06:07:23'),
(18, 'PMT002104115587', 26, NULL, 39, NULL, NULL, 8, '13348.75', 2, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '13348.75', '13348.75', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 3, 1, 0, '11-04-2021', '12:20:36 pm', '2021-04-10 18:00:00', 'April', '2021', NULL, 1, '2021-04-11 06:20:36', '2021-04-11 06:22:48'),
(20, 'PMT002104114744', 26, NULL, 39, NULL, NULL, 8, '1805.00', 2, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '1805.00', '1805.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 3, 1, 0, '11-04-2021', '01:15:58 pm', '2021-04-10 18:00:00', 'April', '2021', NULL, 1, '2021-04-11 07:15:58', '2021-04-11 07:15:58'),
(21, 'PMT002104129477', NULL, 7, 39, NULL, NULL, 3, '1155.00', 2, '0.00', '0.00', 'Shipment.', 'Arambagh, Motijheel, Dhaka, Bangladesh.', '0.00', 5, 'Rails', NULL, '0.00', '0.00', '1155.00', '1155.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 0, '12-04-2021', '05:51:01 pm', '2021-04-11 18:00:00', 'April', '2021', NULL, 1, '2021-04-12 11:51:01', '2021-04-27 07:47:56'),
(22, 'PMT002104123975', NULL, 7, 39, NULL, NULL, 2, '281.25', 2, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '281.25', '281.25', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 0, '12-04-2021', '06:52:09 pm', '2021-04-11 18:00:00', 'April', '2021', NULL, 1, '2021-04-12 12:52:09', '2021-04-12 12:52:09'),
(23, 'PMT002104138111', 26, NULL, 39, NULL, NULL, 1, '315.00', 2, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '315.00', '0.00', '0.00', '315.00', 0, 0, '0.00', '0.00', NULL, 3, 1, 0, '13-04-2021', '01:01:10 pm', '2021-04-12 18:00:00', 'April', '2021', NULL, 1, '2021-04-13 07:01:10', '2021-04-13 07:01:10'),
(24, 'PMT002104179815', NULL, 7, 40, NULL, NULL, 2, '1023.75', 2, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '1023.75', '1023.75', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 0, '17-04-2021', '09:47:27 am', '2021-04-16 18:00:00', 'April', '2021', NULL, 1, '2021-04-17 03:47:27', '2021-04-17 03:47:27'),
(25, 'PMT002104179832', NULL, 7, 45, NULL, NULL, 3, '1173.75', 2, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '1173.75', '1173.75', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 0, '17-04-2021', '10:42:48 am', '2021-04-16 18:00:00', 'April', '2021', NULL, 1, '2021-04-17 04:42:48', '2021-04-25 13:43:29'),
(26, 'PMT002104178759', NULL, 7, 43, NULL, NULL, 3, '1155.00', 2, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '1155.00', '0.00', '0.00', '1155.00', 0, 0, '0.00', '0.00', NULL, 2, 4, 0, '2021-04-17', '01:22:32 pm', '2021-04-16 18:00:00', 'April', '2021', NULL, 1, '2021-04-17 07:22:32', '2021-05-04 05:47:48'),
(29, 'PMT002104251647', 26, NULL, 44, NULL, NULL, 7, '2566.25', 2, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '2566.25', '2566.25', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 3, 1, 0, '25-04-2021', '05:46:06 pm', '2021-04-24 18:00:00', 'April', '2021', NULL, 1, '2021-04-25 11:46:06', '2021-04-25 11:46:06'),
(30, 'PMT002104254894', 26, NULL, NULL, NULL, NULL, 5, '1411.25', 2, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '1411.25', '1411.25', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 3, 1, 0, '25-04-2021', '05:56:13 pm', '2021-04-24 18:00:00', 'April', '2021', NULL, 1, '2021-04-25 11:56:13', '2021-04-25 11:58:23'),
(31, 'PMT002104263127', NULL, 7, 46, NULL, NULL, 2, '262.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '262.50', '262.50', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 0, '2021-04-26', '01:40:51 pm', '2021-04-25 18:00:00', 'April', '2021', NULL, 1, '2021-04-26 07:40:51', '2021-04-26 07:40:51'),
(32, 'PMT002104265536', NULL, 7, NULL, NULL, NULL, 3, '1155.00', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '1155.00', '1155.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 0, '2021-04-26', '01:43:34 pm', '2021-04-25 18:00:00', 'April', '2021', NULL, 1, '2021-04-26 07:43:34', '2021-04-26 07:43:34'),
(33, 'PMT002104265323', NULL, 7, 43, NULL, NULL, 2, '1023.75', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '1023.75', '1023.75', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 0, '2021-04-26', '01:45:32 pm', '2021-04-25 18:00:00', 'April', '2021', NULL, 1, '2021-04-26 07:45:32', '2021-04-26 07:45:32'),
(34, 'PMT002104262744', NULL, 7, 43, NULL, NULL, 2, '1023.75', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '1023.75', '1023.75', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 0, '2021-04-26', '01:45:39 pm', '2021-04-25 18:00:00', 'April', '2021', NULL, 1, '2021-04-26 07:45:39', '2021-04-26 07:45:39'),
(35, 'PMT002104266457', NULL, 7, 43, NULL, NULL, 2, '1023.75', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '1023.75', '1023.75', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 0, '2021-04-26', '01:45:40 pm', '2021-04-25 18:00:00', 'April', '2021', NULL, 1, '2021-04-26 07:45:40', '2021-04-26 07:45:40'),
(36, 'PMT002104267258', NULL, 7, 43, NULL, NULL, 2, '1023.75', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '1023.75', '1023.75', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 0, '2021-04-26', '01:45:41 pm', '2021-04-25 18:00:00', 'April', '2021', NULL, 1, '2021-04-26 07:45:41', '2021-04-26 07:45:41'),
(37, 'PMT002104264867', NULL, 7, 43, NULL, NULL, 2, '1023.75', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '1023.75', '1023.75', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 0, '2021-04-26', '01:45:46 pm', '2021-04-25 18:00:00', 'April', '2021', NULL, 1, '2021-04-26 07:45:46', '2021-04-26 07:45:46'),
(38, 'PMT002104262315', NULL, 7, 43, NULL, NULL, 2, '1023.75', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '1023.75', '1023.75', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 0, '2021-04-26', '01:45:48 pm', '2021-04-25 18:00:00', 'April', '2021', NULL, 1, '2021-04-26 07:45:48', '2021-04-26 07:45:48'),
(39, 'PMT002104267965', NULL, 7, 43, NULL, NULL, 2, '1023.75', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '1023.75', '1023.75', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 0, '2021-04-26', '01:46:34 pm', '2021-04-25 18:00:00', 'April', '2021', NULL, 1, '2021-04-26 07:46:34', '2021-04-26 07:46:34'),
(40, 'PMT002104262426', NULL, 7, 42, NULL, NULL, 2, '1023.75', 1, '200.00', '200.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '823.75', '823.75', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 0, '2021-04-26', '02:07:38 pm', '2021-04-25 18:00:00', 'April', '2021', NULL, 1, '2021-04-26 08:07:38', '2021-04-26 08:07:38'),
(41, 'PMT002104268423', NULL, 7, NULL, NULL, NULL, 2, '1023.75', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '1023.75', '1023.75', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 0, '2021-04-26', '02:09:27 pm', '2021-04-25 18:00:00', 'April', '2021', NULL, 1, '2021-04-26 08:09:27', '2021-04-26 08:09:27'),
(42, 'PMT002104269136', NULL, 7, NULL, NULL, NULL, 2, '1023.75', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '1023.75', '1023.75', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 0, '2021-04-26', '02:14:50 pm', '2021-04-25 18:00:00', 'April', '2021', NULL, 1, '2021-04-26 08:14:50', '2021-04-26 08:14:50'),
(43, 'PMT002104267153', NULL, 7, 45, NULL, NULL, 2, '1023.75', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '923.75', '923.75', '0.00', '892.50', 0, 0, '0.00', '0.00', NULL, 2, 1, 0, '2021-04-26', '02:20:34 pm', '2021-04-25 18:00:00', 'April', '2021', NULL, 1, '2021-04-26 08:20:34', '2021-06-09 07:22:00'),
(44, 'PMT002104269563', NULL, 7, 41, NULL, NULL, 1, '131.25', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '131.25', '131.25', '0.00', '118.12', 0, 0, '0.00', '0.00', NULL, 2, 1, 0, '2021-04-26', '02:23:00 pm', '2021-04-25 18:00:00', 'April', '2021', NULL, 1, '2021-04-26 08:23:00', '2021-06-09 07:21:39'),
(45, 'INV123', NULL, 7, 45, NULL, NULL, 3, '1155.00', 2, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '1155.00', '1155.00', '0.00', '0.00', 1, 0, '882.50', '882.50', NULL, 2, 1, 0, '2021-04-26', '03:39:31 pm', '2021-04-25 18:00:00', 'April', '2021', NULL, 1, '2021-04-26 09:39:31', '2021-04-27 04:44:07'),
(46, 'PMT002104279222', NULL, 7, 47, NULL, NULL, 7, '1781.85', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '1781.85', '1781.85', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 0, '2021-04-27', '12:10:37 pm', '2021-04-26 18:00:00', 'April', '2021', NULL, 1, '2021-04-27 06:10:37', '2021-04-27 06:11:40'),
(48, 'PMT002104281531', NULL, 7, 41, NULL, NULL, 6, '1559.25', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '1559.25', '1559.25', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 0, '2021-04-28', '11:44:22 am', '2021-04-27 18:00:00', 'April', '2021', NULL, 1, '2021-04-28 05:44:22', '2021-04-28 05:44:22'),
(49, 'PMT002104289348', 24, NULL, 48, NULL, NULL, 7, '94250.00', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '94250.00', '94250.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 7, 1, 0, '2021-04-28', '02:53:06 pm', '2021-04-27 18:00:00', 'April', '2021', NULL, 1, '2021-04-28 08:53:06', '2021-06-09 09:25:35'),
(50, 'PMT002105025517', NULL, 7, 40, NULL, NULL, 2, '262.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '162.50', '162.50', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 0, '2021-05-02', '04:44:58 pm', '2021-05-01 18:00:00', 'May', '2021', NULL, 1, '2021-05-02 10:44:58', '2021-05-02 10:44:58'),
(51, 'PMT002105041545', NULL, 7, 53, NULL, NULL, 3, '387.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '387.50', '387.50', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 0, '2021-05-04', '10:56:24 am', '2021-05-03 18:00:00', 'May', '2021', NULL, 1, '2021-05-04 04:56:24', '2021-05-04 04:56:24'),
(52, 'PMT002105041956', NULL, 7, 53, NULL, NULL, 2, '262.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '262.50', '0.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 4, 0, '2021-05-04', '10:58:56 am', '2021-05-03 18:00:00', 'May', '2021', NULL, 1, '2021-05-04 04:58:56', '2021-05-04 04:58:56'),
(53, 'PMT002105046145', NULL, 7, 53, NULL, NULL, 2, '262.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '262.50', '0.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 4, 0, '2021-05-04', '10:59:06 am', '2021-05-03 18:00:00', 'May', '2021', NULL, 1, '2021-05-04 04:59:06', '2021-05-04 04:59:06'),
(54, 'PMT002105046435', NULL, 7, 54, NULL, NULL, 1, '131.25', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '131.25', '0.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 4, 0, '2021-05-04', '11:01:16 am', '2021-05-03 18:00:00', 'May', '2021', NULL, 1, '2021-05-04 05:01:16', '2021-05-04 05:01:16'),
(55, 'PMT002105043489', NULL, 7, 54, NULL, NULL, 1, '131.25', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '131.25', '0.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 4, 0, '2021-05-04', '11:01:23 am', '2021-05-03 18:00:00', 'May', '2021', NULL, 1, '2021-05-04 05:01:23', '2021-05-04 05:01:23'),
(56, 'PMT002105046136', NULL, 7, 54, NULL, NULL, 1, '131.25', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '131.25', '0.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 4, 0, '2021-05-04', '11:02:55 am', '2021-05-03 18:00:00', 'May', '2021', NULL, 1, '2021-05-04 05:02:55', '2021-05-04 05:02:55'),
(57, 'PMT002105045546', NULL, 7, 54, NULL, NULL, 1, '131.25', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '131.25', '0.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 4, 0, '2021-05-04', '11:04:56 am', '2021-05-03 18:00:00', 'May', '2021', NULL, 1, '2021-05-04 05:04:56', '2021-05-04 05:04:56'),
(58, 'PMT002105041377', NULL, 7, 51, NULL, NULL, 1, '131.25', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '131.25', '131.25', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 0, '2021-05-04', '11:09:15 am', '2021-05-03 18:00:00', 'May', '2021', NULL, 1, '2021-05-04 05:09:15', '2021-05-04 05:09:15'),
(59, 'PMT002105048364', NULL, 7, 54, NULL, NULL, 2, '1017.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '1017.50', '0.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 4, 0, '2021-05-04', '11:09:39 am', '2021-05-03 18:00:00', 'May', '2021', NULL, 1, '2021-05-04 05:09:39', '2021-05-04 05:09:39'),
(60, 'PMT002105045321', NULL, 7, 50, NULL, NULL, 2, '1023.75', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '1023.75', '0.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 4, 0, '2021-05-04', '11:10:20 am', '2021-05-03 18:00:00', 'May', '2021', NULL, 1, '2021-05-04 05:10:20', '2021-05-04 05:10:20'),
(61, 'PMT002105041966', NULL, 7, 42, NULL, NULL, 1, '131.25', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '131.25', '0.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 2, 0, '2021-05-04', '11:27:41 am', '2021-05-03 18:00:00', 'May', '2021', NULL, 1, '2021-05-04 05:27:41', '2021-05-04 05:27:41'),
(62, 'PMT002105043114', NULL, 7, 42, NULL, NULL, 1, '131.25', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '131.25', '0.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 2, 0, '2021-05-04', '11:27:47 am', '2021-05-03 18:00:00', 'May', '2021', NULL, 1, '2021-05-04 05:27:47', '2021-05-04 05:27:47'),
(63, 'PMT002105049118', NULL, 7, 42, NULL, NULL, 1, '131.25', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '131.25', '0.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 2, 0, '2021-05-04', '11:30:16 am', '2021-05-03 18:00:00', 'May', '2021', NULL, 1, '2021-05-04 05:30:16', '2021-05-04 05:30:16'),
(64, 'SI210505253416', 26, NULL, 45, NULL, NULL, 4, '184550.00', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '184550.00', '184550.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 0, '05-05-2021', NULL, '2021-05-04 20:48:37', 'May', '2021', NULL, 2, '2021-05-05 08:48:37', '2021-05-05 08:48:37'),
(65, 'SDC0210508789956', NULL, 7, NULL, NULL, NULL, 2, '262.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '262.50', '0.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 4, 0, '08-05-2021', '05:10:18 pm', '2021-05-07 23:10:18', 'May', '2021', NULL, 1, '2021-05-08 11:10:18', '2021-05-08 11:10:18'),
(66, 'SDC0210508118568', NULL, 7, NULL, NULL, NULL, 2, '262.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '262.50', '0.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 4, 0, '08-05-2021', '05:11:47 pm', '2021-05-07 23:11:47', 'May', '2021', NULL, 1, '2021-05-08 11:11:47', '2021-05-08 11:11:47'),
(67, 'SDC0210508238628', NULL, 7, NULL, NULL, NULL, 2, '262.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '262.50', '0.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 4, 0, '08-05-2021', '05:11:58 pm', '2021-05-07 23:11:58', 'May', '2021', NULL, 1, '2021-05-08 11:11:58', '2021-05-08 11:11:58'),
(68, 'SDC0210508784159', NULL, 7, NULL, NULL, NULL, 2, '262.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '262.50', '0.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 4, 0, '08-05-2021', '05:12:27 pm', '2021-05-07 23:12:27', 'May', '2021', NULL, 1, '2021-05-08 11:12:27', '2021-05-08 11:12:27'),
(69, 'SDC0210508931522', NULL, 7, NULL, NULL, NULL, 2, '262.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '262.50', '0.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 2, 0, '08-05-2021', '05:12:38 pm', '2021-05-07 23:12:38', 'May', '2021', NULL, 1, '2021-05-08 11:12:38', '2021-05-08 11:12:38'),
(70, 'SDC0210508919361', NULL, 7, NULL, NULL, NULL, 2, '262.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '262.50', '262.50', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '08-05-2021', '05:13:44 pm', '2021-05-07 23:13:44', 'May', '2021', NULL, 2, '2021-05-08 11:13:44', '2021-05-08 11:13:44'),
(71, 'SDC0210508366953', NULL, 7, NULL, NULL, NULL, 2, '262.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '262.50', '262.50', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '08-05-2021', '05:13:57 pm', '2021-05-07 23:13:57', 'May', '2021', NULL, 2, '2021-05-08 11:13:57', '2021-05-08 11:13:57'),
(72, 'SDC0210508999448', NULL, 7, NULL, NULL, NULL, 2, '262.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '262.50', '262.50', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '08-05-2021', '05:14:47 pm', '2021-05-07 23:14:47', 'May', '2021', NULL, 2, '2021-05-08 11:14:47', '2021-05-08 11:14:47'),
(73, 'SDC0210508661767', NULL, 7, NULL, NULL, NULL, 2, '262.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '262.50', '262.50', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '08-05-2021', '05:16:53 pm', '2021-05-07 23:16:53', 'May', '2021', NULL, 2, '2021-05-08 11:16:53', '2021-05-08 11:16:53'),
(74, 'SDC0210508311244', NULL, 7, 54, NULL, NULL, 2, '262.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '262.50', '262.50', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '08-05-2021', '05:18:04 pm', '2021-05-07 23:18:04', 'May', '2021', NULL, 2, '2021-05-08 11:18:04', '2021-05-08 13:04:43'),
(75, 'SDC0210508392154', NULL, 7, NULL, NULL, NULL, 2, '262.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '262.50', '262.50', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '08-05-2021', '05:30:27 pm', '2021-05-07 23:30:27', 'May', '2021', NULL, 2, '2021-05-08 11:30:27', '2021-05-08 11:30:27'),
(76, 'SDC0210508584297', NULL, 7, NULL, NULL, NULL, 2, '262.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '262.50', '0.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 2, 0, '08-05-2021', '05:31:20 pm', '2021-05-07 23:31:20', 'May', '2021', NULL, 1, '2021-05-08 11:31:20', '2021-05-08 11:31:20'),
(77, 'SDC0210508528793', NULL, 7, NULL, NULL, NULL, 3, '11812.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '11812.50', '0.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 4, 0, '08-05-2021', '05:32:10 pm', '2021-05-07 23:32:10', 'May', '2021', NULL, 1, '2021-05-08 11:32:10', '2021-05-08 11:32:10'),
(78, 'SDC0210508958751', NULL, 7, NULL, NULL, NULL, 2, '262.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '262.50', '262.50', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 0, '10-05-2021', '05:37:46 pm', '2021-05-10 05:05:23', 'May', '2021', NULL, 1, '2021-05-08 11:37:46', '2021-05-10 05:23:23'),
(81, 'SDC0210508378221', NULL, 7, NULL, NULL, NULL, 4, '1286.25', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '1286.25', '0.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 4, 0, '08-05-2021', '05:45:52 pm', '2021-05-07 23:45:52', 'May', '2021', NULL, 1, '2021-05-08 11:45:52', '2021-05-08 11:45:52'),
(82, 'SDC0210508921513', NULL, 7, NULL, NULL, NULL, 2, '1023.75', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '1023.75', '1023.75', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '08-05-2021', '06:44:59 pm', '2021-05-08 00:44:59', 'May', '2021', NULL, 2, '2021-05-08 12:44:59', '2021-05-08 12:44:59'),
(83, 'SDC0210508223735', NULL, 7, NULL, NULL, NULL, 2, '262.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '262.50', '300.00', '37.50', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '08-05-2021', '06:45:49 pm', '2021-05-08 00:45:49', 'May', '2021', NULL, 2, '2021-05-08 12:45:49', '2021-05-08 12:45:49'),
(84, 'SDC0210508855231', NULL, 7, NULL, NULL, NULL, 2, '262.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '262.50', '300.00', '37.50', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '08-05-2021', '06:48:44 pm', '2021-05-08 00:48:44', 'May', '2021', NULL, 2, '2021-05-08 12:48:44', '2021-05-08 12:48:44'),
(85, 'SDC0210508184486', NULL, 7, 54, NULL, NULL, 2, '262.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '262.50', '262.50', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '08-05-2021', '06:50:50 pm', '2021-05-08 00:50:50', 'May', '2021', NULL, 2, '2021-05-08 12:50:50', '2021-05-08 12:50:50'),
(86, 'SDC0210508382784', NULL, 7, 54, NULL, NULL, 2, '262.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '262.50', '262.50', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '08-05-2021', '06:50:58 pm', '2021-05-08 00:50:58', 'May', '2021', NULL, 2, '2021-05-08 12:50:58', '2021-05-08 12:50:58'),
(87, 'SDC0210508945225', NULL, 7, 54, NULL, NULL, 2, '262.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '262.50', '262.50', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '08-05-2021', '06:53:18 pm', '2021-05-08 00:53:18', 'May', '2021', NULL, 2, '2021-05-08 12:53:18', '2021-05-08 12:53:18'),
(88, 'SDC0210508924725', NULL, 7, 54, NULL, NULL, 2, '262.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '262.50', '262.50', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '08-05-2021', '06:53:33 pm', '2021-05-08 00:53:33', 'May', '2021', NULL, 2, '2021-05-08 12:53:33', '2021-05-08 12:53:33'),
(89, 'SDC0210508274488', NULL, 7, 54, NULL, NULL, 2, '262.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '262.50', '262.50', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '08-05-2021', '06:54:44 pm', '2021-05-08 00:54:44', 'May', '2021', NULL, 2, '2021-05-08 12:54:44', '2021-05-08 12:54:44'),
(90, 'SDC0210508523381', NULL, 7, 54, NULL, NULL, 2, '262.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '262.50', '262.50', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '08-05-2021', '06:54:46 pm', '2021-05-08 00:54:46', 'May', '2021', NULL, 2, '2021-05-08 12:54:46', '2021-05-08 12:54:46'),
(91, 'SDC0210508782182', NULL, 7, NULL, NULL, NULL, 2, '262.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '262.50', '262.50', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '08-05-2021', '06:57:45 pm', '2021-05-08 00:57:45', 'May', '2021', NULL, 2, '2021-05-08 12:57:45', '2021-05-08 12:57:45'),
(92, 'SDC0210508124598', NULL, 7, 54, NULL, NULL, 2, '262.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '262.50', '262.50', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '08-05-2021', '06:58:02 pm', '2021-05-08 00:58:02', 'May', '2021', NULL, 2, '2021-05-08 12:58:02', '2021-05-08 12:58:02'),
(93, 'SDC0210508776662', NULL, 7, 54, NULL, NULL, 2, '262.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '262.50', '262.50', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '08-05-2021', '06:58:16 pm', '2021-05-08 00:58:16', 'May', '2021', NULL, 2, '2021-05-08 12:58:16', '2021-05-08 13:04:43'),
(94, 'SDC0210508418171', NULL, 7, 54, NULL, NULL, 2, '262.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '262.50', '262.50', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '08-05-2021', '06:58:34 pm', '2021-05-08 00:58:34', 'May', '2021', NULL, 2, '2021-05-08 12:58:34', '2021-05-08 12:58:34'),
(95, 'SDC0210508223156', NULL, 7, 54, NULL, NULL, 2, '262.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '262.50', '262.50', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '08-05-2021', '06:58:39 pm', '2021-05-08 00:58:39', 'May', '2021', NULL, 2, '2021-05-08 12:58:39', '2021-05-08 12:58:39'),
(96, 'SDC0210508225892', NULL, 7, 54, NULL, NULL, 2, '262.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '262.50', '262.50', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '08-05-2021', '06:58:53 pm', '2021-05-08 00:58:53', 'May', '2021', NULL, 2, '2021-05-08 12:58:53', '2021-05-08 12:58:53'),
(97, 'SDC0210508171214', NULL, 7, 54, NULL, NULL, 2, '262.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '262.50', '262.50', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '08-05-2021', '06:59:05 pm', '2021-05-08 00:59:05', 'May', '2021', NULL, 2, '2021-05-08 12:59:05', '2021-05-08 13:45:53'),
(98, 'SDC0210508972656', NULL, 7, 54, NULL, NULL, 2, '262.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '262.50', '262.50', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '08-05-2021', '06:59:15 pm', '2021-05-08 00:59:15', 'May', '2021', NULL, 2, '2021-05-08 12:59:15', '2021-05-08 12:59:15'),
(99, 'SDC0210508321897', NULL, 7, 54, NULL, NULL, 2, '262.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '262.50', '262.50', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '08-05-2021', '07:04:43 pm', '2021-05-08 01:04:43', 'May', '2021', NULL, 2, '2021-05-08 13:04:43', '2021-05-08 13:04:43'),
(100, 'SDC0210508948334', NULL, 7, NULL, NULL, NULL, 2, '262.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '262.50', '262.50', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '08-05-2021', '07:07:54 pm', '2021-05-08 01:07:54', 'May', '2021', NULL, 2, '2021-05-08 13:07:54', '2021-05-08 13:07:54'),
(101, 'SDC0210508523531', NULL, 7, NULL, NULL, NULL, 2, '262.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '262.50', '262.50', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '08-05-2021', '07:10:35 pm', '2021-05-08 01:10:35', 'May', '2021', NULL, 2, '2021-05-08 13:10:35', '2021-05-08 13:10:35'),
(102, 'SDC0210508736789', NULL, 7, 54, NULL, NULL, 2, '262.50', 1, '200.00', '200.00', NULL, NULL, '0.00', NULL, NULL, NULL, '5.00', '3.13', '65.63', '65.63', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '08-05-2021', '07:11:24 pm', '2021-05-08 01:11:24', 'May', '2021', NULL, 2, '2021-05-08 13:11:24', '2021-05-08 13:11:24'),
(103, 'SDC0210508161995', NULL, 7, 54, NULL, NULL, 2, '262.50', 1, '100.00', '100.00', NULL, NULL, '0.00', NULL, NULL, NULL, '5.00', '8.13', '170.63', '170.63', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '08-05-2021', '07:12:46 pm', '2021-05-08 01:12:46', 'May', '2021', NULL, 2, '2021-05-08 13:12:46', '2021-05-08 13:45:53'),
(104, 'SDC0210508143332', NULL, 7, NULL, NULL, NULL, 2, '262.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '262.50', '262.50', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '08-05-2021', '07:14:02 pm', '2021-05-08 01:14:02', 'May', '2021', NULL, 2, '2021-05-08 13:14:02', '2021-05-08 13:14:02'),
(105, 'SDC0210508617218', NULL, 7, NULL, NULL, NULL, 2, '262.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '262.50', '262.50', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '08-05-2021', '07:14:35 pm', '2021-05-08 01:14:35', 'May', '2021', NULL, 2, '2021-05-08 13:14:35', '2021-05-08 13:14:35'),
(106, 'SDC0210508421976', NULL, 7, NULL, NULL, NULL, 2, '262.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '262.50', '262.50', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '08-05-2021', '07:16:33 pm', '2021-05-08 01:16:33', 'May', '2021', NULL, 2, '2021-05-08 13:16:33', '2021-05-08 13:16:33'),
(107, 'SDC0210508487258', NULL, 7, NULL, NULL, NULL, 1, '131.25', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '131.25', '131.25', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '08-05-2021', '07:18:18 pm', '2021-05-08 01:18:18', 'May', '2021', NULL, 2, '2021-05-08 13:18:18', '2021-05-08 13:18:18'),
(108, 'SDC0210508118541', NULL, 7, NULL, NULL, NULL, 2, '262.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '262.50', '262.50', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '08-05-2021', '07:18:34 pm', '2021-05-08 01:18:34', 'May', '2021', NULL, 2, '2021-05-08 13:18:34', '2021-05-08 13:18:34'),
(109, 'SDC0210508549349', NULL, 7, NULL, NULL, NULL, 2, '262.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '262.50', '262.50', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '08-05-2021', '07:19:13 pm', '2021-05-08 01:19:13', 'May', '2021', NULL, 2, '2021-05-08 13:19:13', '2021-05-08 13:19:13'),
(110, 'SDC0210508953199', NULL, 7, NULL, NULL, NULL, 2, '262.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '262.50', '262.50', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '08-05-2021', '07:19:29 pm', '2021-05-08 01:19:29', 'May', '2021', NULL, 2, '2021-05-08 13:19:29', '2021-05-08 13:19:29'),
(111, 'SDC0210508743843', NULL, 7, NULL, NULL, NULL, 2, '262.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '262.50', '262.50', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '08-05-2021', '07:20:01 pm', '2021-05-08 01:20:01', 'May', '2021', NULL, 2, '2021-05-08 13:20:01', '2021-05-08 13:20:01'),
(112, 'SDC0210508476788', NULL, 7, NULL, NULL, NULL, 2, '262.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '262.50', '262.50', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '08-05-2021', '07:20:24 pm', '2021-05-08 01:20:24', 'May', '2021', NULL, 2, '2021-05-08 13:20:24', '2021-05-08 13:20:24'),
(113, 'SDC0210508367814', NULL, 7, NULL, NULL, NULL, 2, '262.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '262.50', '262.50', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '08-05-2021', '07:20:58 pm', '2021-05-08 01:20:58', 'May', '2021', NULL, 2, '2021-05-08 13:20:58', '2021-05-08 13:20:58'),
(114, 'SDC0210508111574', NULL, 7, NULL, NULL, NULL, 2, '262.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '262.50', '262.50', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '08-05-2021', '07:21:07 pm', '2021-05-08 01:21:07', 'May', '2021', NULL, 2, '2021-05-08 13:21:07', '2021-05-08 13:21:07'),
(115, 'SDC0210508132856', NULL, 7, NULL, NULL, NULL, 1, '131.25', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '131.25', '131.25', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '08-05-2021', '07:21:23 pm', '2021-05-08 01:21:23', 'May', '2021', NULL, 2, '2021-05-08 13:21:23', '2021-05-08 13:21:23'),
(116, 'SDC0210508992648', NULL, 7, NULL, NULL, NULL, 2, '262.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '262.50', '262.50', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '08-05-2021', '07:22:02 pm', '2021-05-08 01:22:02', 'May', '2021', NULL, 2, '2021-05-08 13:22:02', '2021-05-08 13:22:02'),
(117, 'SDC0210508787488', NULL, 7, NULL, NULL, NULL, 2, '262.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '262.50', '262.50', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '08-05-2021', '07:22:16 pm', '2021-05-08 01:22:16', 'May', '2021', NULL, 2, '2021-05-08 13:22:16', '2021-05-08 13:22:16'),
(118, 'SDC0210508543747', NULL, 7, 54, NULL, NULL, 2, '262.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '262.50', '262.50', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '08-05-2021', '07:44:55 pm', '2021-05-08 01:44:55', 'May', '2021', NULL, 2, '2021-05-08 13:44:55', '2021-05-08 13:45:53'),
(119, 'SDC0210508296251', NULL, 7, 54, NULL, NULL, 2, '262.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '262.50', '262.50', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '08-05-2021', '07:45:24 pm', '2021-05-08 01:45:24', 'May', '2021', NULL, 2, '2021-05-08 13:45:24', '2021-05-08 13:45:53'),
(121, 'SDC0210508276274', NULL, 7, NULL, NULL, NULL, 2, '262.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '262.50', '0.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 4, 0, '08-05-2021', '08:46:07 pm', '2021-05-08 02:46:07', 'May', '2021', NULL, 1, '2021-05-08 14:46:07', '2021-05-08 14:46:07'),
(122, 'SDC0210508758833', NULL, 7, NULL, NULL, NULL, 2, '262.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '262.50', '0.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 4, 0, '08-05-2021', '08:48:49 pm', '2021-05-08 02:48:49', 'May', '2021', NULL, 1, '2021-05-08 14:48:49', '2021-05-08 14:48:49'),
(123, 'SDC0210508337854', NULL, 7, NULL, NULL, NULL, 2, '262.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '262.50', '0.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 4, 0, '08-05-2021', '08:50:54 pm', '2021-05-08 02:50:54', 'May', '2021', NULL, 2, '2021-05-08 14:50:54', '2021-05-08 14:50:54'),
(124, 'SDC0210508644592', NULL, 7, NULL, NULL, NULL, 2, '262.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '262.50', '262.50', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 0, '09-05-2021', '08:59:03 pm', '2021-05-09 03:05:14', 'May', '2021', NULL, 2, '2021-05-08 14:59:03', '2021-05-09 15:14:33'),
(132, 'SDC0210509611621', NULL, 7, NULL, NULL, NULL, 2, '262.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '5.00', '13.13', '275.63', '275.63', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '09-05-2021', '05:49:47 pm', '2021-05-08 23:49:47', 'May', '2021', NULL, 2, '2021-05-09 11:49:47', '2021-05-09 11:49:47'),
(133, 'SDC0210509792784', NULL, 7, 54, NULL, NULL, 2, '262.50', 1, '20.00', '20.00', NULL, NULL, '0.00', NULL, NULL, NULL, '5.00', '12.13', '254.63', '254.63', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '09-05-2021', '06:32:55 pm', '2021-05-09 00:32:55', 'May', '2021', NULL, 2, '2021-05-09 12:32:55', '2021-05-09 12:32:55'),
(134, 'SDC0210509347688', NULL, 7, 54, NULL, NULL, 2, '262.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '262.50', '262.50', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 0, '10-05-2021', '06:51:45 pm', '2021-05-09 22:05:02', 'May', '2021', NULL, 2, '2021-05-09 12:51:45', '2021-05-10 10:02:10'),
(135, 'SDC0210509762396', NULL, 7, NULL, NULL, NULL, 15, '52937.50', 1, '500.00', '500.00', NULL, NULL, '0.00', NULL, NULL, NULL, '5.00', '2621.88', '55059.38', '55059.38', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '09-05-2021', '07:00:03 pm', '2021-05-09 01:00:03', 'May', '2021', NULL, 2, '2021-05-09 13:00:03', '2021-05-09 13:00:03'),
(136, 'SDC0210509565849', NULL, 7, 55, NULL, NULL, 1, '892.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '892.50', '892.50', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '09-05-2021', '07:02:56 pm', '2021-05-09 01:02:56', 'May', '2021', NULL, 2, '2021-05-09 13:02:56', '2021-06-05 05:59:31'),
(137, 'SDC02105097464', NULL, 7, NULL, NULL, NULL, 1, '131.25', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '131.25', '131.25', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '2021-05-09', '07:04:10 pm', '2021-05-08 18:00:00', 'May', '2021', NULL, 1, '2021-05-09 13:04:10', '2021-05-09 13:04:10'),
(138, 'SDC0210509559382', NULL, 7, NULL, NULL, NULL, 2, '1023.75', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '1023.75', '1023.75', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '09-05-2021', '07:15:40 pm', '2021-05-09 01:15:40', 'May', '2021', NULL, 2, '2021-05-09 13:15:40', '2021-05-09 13:15:40'),
(139, 'SDC0210509665635', NULL, 7, NULL, NULL, NULL, 2, '1023.75', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '1023.75', '1023.75', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '09-05-2021', '08:42:59 pm', '2021-05-09 02:05:53', 'May', '2021', NULL, 2, '2021-05-09 14:42:59', '2021-05-09 14:53:02'),
(140, 'SDC0210509248953', NULL, 7, NULL, NULL, NULL, 6, '13860.00', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '13860.00', '13860.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '09-05-2021', '08:44:15 pm', '2021-05-09 02:44:15', 'May', '2021', NULL, 2, '2021-05-09 14:44:15', '2021-05-09 14:44:15'),
(141, 'SDC0210509786499', NULL, 7, NULL, NULL, NULL, 2, '262.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '262.50', '262.50', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '09-05-2021', '08:54:15 pm', '2021-05-09 03:05:11', 'May', '2021', NULL, 2, '2021-05-09 14:54:15', '2021-05-09 15:11:58'),
(142, 'SDC0210510144484', NULL, 7, NULL, NULL, NULL, 2, '262.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '262.50', '262.50', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 0, '10-05-2021', '10:16:46 am', '2021-05-09 22:05:21', 'May', '2021', NULL, 2, '2021-05-10 04:16:46', '2021-05-10 10:21:01'),
(143, 'SDC0210510239276', NULL, 7, NULL, NULL, NULL, 2, '262.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '262.50', '262.50', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '10-05-2021', '10:49:56 am', '2021-05-09 22:05:19', 'May', '2021', NULL, 2, '2021-05-10 04:49:56', '2021-05-10 10:19:37'),
(144, 'SDC0210510653321', NULL, 7, NULL, NULL, NULL, 3, '1155.00', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '1155.00', '0.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 2, 0, '10-05-2021', '11:15:42 am', '2021-05-10 05:15:42', 'May', '2021', NULL, 2, '2021-05-10 05:15:42', '2021-05-10 05:15:42'),
(145, 'SDC0210510931293', NULL, 7, NULL, NULL, NULL, 2, '262.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '262.50', '262.50', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '10-05-2021', '07:06:22 pm', '2021-05-10 01:06:22', 'May', '2021', NULL, 2, '2021-05-10 13:06:22', '2021-05-10 13:06:22'),
(146, 'SDC0210511629673', NULL, 7, 39, NULL, NULL, 6, '2572.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '-17039.75', '-17039.75', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '11-05-2021', '09:25:54 am', '2021-05-11 03:25:54', 'May', '2021', NULL, 2, '2021-05-11 03:25:54', '2021-05-11 03:25:54'),
(147, 'SDC0210511536832', NULL, 7, 39, NULL, NULL, 2, '262.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '-16777.25', '-16777.25', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '11-05-2021', '09:30:38 am', '2021-05-11 03:30:38', 'May', '2021', NULL, 2, '2021-05-11 03:30:38', '2021-05-11 03:30:38'),
(150, 'SDC0210511187586', 24, NULL, NULL, NULL, NULL, 2, '46133.25', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '46133.25', '46133.25', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 7, 1, 0, '11-05-2021', '10:07:10 am', '2021-05-11 04:05:10', 'May', '2021', NULL, 2, '2021-05-11 04:07:10', '2021-05-11 04:10:13'),
(151, 'SDC0210511951498', 24, NULL, NULL, NULL, NULL, 1, '46000.00', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '46000.00', '46000.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 7, 1, 1, '11-05-2021', '10:15:35 am', '2021-05-11 04:05:18', 'May', '2021', NULL, 2, '2021-05-11 04:15:35', '2021-05-11 04:18:26'),
(152, 'SDC0210511224372', 24, NULL, NULL, NULL, NULL, 1, '46000.00', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '46000.00', '46000.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 7, 1, 1, '11-05-2021', '10:18:53 am', '2021-05-11 04:05:19', 'May', '2021', NULL, 2, '2021-05-11 04:18:53', '2021-05-11 04:19:34'),
(153, 'SDC0210511138837', 24, NULL, NULL, NULL, NULL, 2, '262.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '262.50', '0.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 7, 6, 0, '11-05-2021', '02:42:07 pm', '2021-05-10 20:42:07', 'May', '2021', NULL, 2, '2021-05-11 08:42:07', '2021-05-11 08:42:07'),
(156, 'SDC0210518796724', NULL, 7, 43, NULL, NULL, 2, '262.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '1680.00', '0.00', '0.00', '1680.00', 0, 1, '0.00', '0.00', NULL, 2, 1, 1, '18-05-2021', '01:23:29 pm', '2021-05-17 19:23:29', 'May', '2021', NULL, 2, '2021-05-18 07:23:29', '2021-06-03 12:33:28'),
(157, 'SDC0210518371179', NULL, 7, 45, NULL, NULL, 2, '262.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '425.00', '425.00', '0.00', '0.00', 0, 1, '0.00', '0.00', NULL, 2, 1, 1, '18-05-2021', '01:26:26 pm', '2021-05-17 19:26:26', 'May', '2021', NULL, 2, '2021-05-18 07:26:26', '2021-06-10 06:37:50'),
(158, 'SDC0210518726884', NULL, 7, 49, NULL, NULL, 2, '262.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '425.00', '425.00', '0.00', '0.00', 0, 1, '0.00', '0.00', NULL, 2, 1, 1, '18-05-2021', '01:32:40 pm', '2021-05-17 19:32:40', 'May', '2021', NULL, 2, '2021-05-18 07:32:40', '2021-06-09 09:27:00'),
(159, 'SDC0210518381777', NULL, 7, 40, NULL, NULL, 2, '262.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '508.75', '508.75', '0.00', '0.00', 0, 1, '0.00', '0.00', NULL, 2, 1, 1, '18-05-2021', '03:28:34 pm', '2021-05-17 21:28:34', 'May', '2021', NULL, 2, '2021-05-18 09:28:34', '2021-06-09 09:24:42'),
(160, 'SDC0210518732761', NULL, 7, NULL, NULL, NULL, 3, '1155.00', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '1155.00', '1200.00', '45.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '18-05-2021', '07:23:04 pm', '2021-05-18 01:23:04', 'May', '2021', NULL, 2, '2021-05-18 13:23:04', '2021-05-18 13:23:04'),
(161, 'SDC0210518887152', NULL, 7, NULL, NULL, NULL, 2, '1023.75', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '1023.75', '1023.75', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '18-05-2021', '07:30:41 pm', '2021-05-18 01:30:41', 'May', '2021', NULL, 2, '2021-05-18 13:30:41', '2021-05-18 13:30:41'),
(166, 'SDC0210519283423', NULL, 7, NULL, NULL, NULL, 2, '262.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '262.50', '400.00', '137.50', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '19-05-2021', '11:02:32 am', '2021-05-19 05:02:32', 'May', '2021', NULL, 2, '2021-05-19 05:02:32', '2021-05-19 05:02:32'),
(167, 'SDC02105193912', NULL, 7, 52, NULL, NULL, 2, '1023.75', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '1023.75', '1023.75', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '2021-05-19', '04:08:09 pm', '2021-05-18 18:00:00', 'May', '2021', NULL, 1, '2021-05-19 10:08:09', '2021-05-19 10:08:09'),
(168, 'SDC02105194453', NULL, 7, 54, NULL, NULL, 2, '1023.75', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '1023.75', '1023.75', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '2021-05-19', '04:13:18 pm', '2021-05-18 18:00:00', 'May', '2021', NULL, 1, '2021-05-19 10:13:18', '2021-05-19 10:13:18'),
(169, 'SDC02105192727', NULL, 7, 54, NULL, NULL, 1, '115.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '115.50', '115.50', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '2021-05-19', '04:13:43 pm', '2021-05-18 18:00:00', 'May', '2021', NULL, 1, '2021-05-19 10:13:43', '2021-05-19 10:13:43'),
(170, 'SDC02105195932', NULL, 7, 51, NULL, NULL, 2, '1023.75', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '1023.75', '1023.75', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '2021-05-19', '04:15:19 pm', '2021-05-18 18:00:00', 'May', '2021', NULL, 1, '2021-05-19 10:15:19', '2021-05-19 10:15:19'),
(171, 'SDC02105195794', NULL, 7, NULL, NULL, NULL, 1, '125.00', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '125.00', '125.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '2021-05-19', '04:15:35 pm', '2021-05-18 18:00:00', 'May', '2021', NULL, 1, '2021-05-19 10:15:35', '2021-05-19 10:15:35'),
(172, 'SDC0210522138826', NULL, 7, NULL, NULL, NULL, 3, '393.75', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '393.75', '0.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 2, 0, '22-05-2021', '11:17:06 am', '2021-05-22 05:17:06', 'May', '2021', NULL, 2, '2021-05-22 05:17:06', '2021-05-22 05:17:06'),
(173, 'SDC0210523392161', NULL, 7, NULL, NULL, NULL, 2, '262.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '262.50', '262.50', '0.00', '0.00', 0, 1, '0.00', '0.00', NULL, 2, 1, 1, '23-05-2021', '06:21:53 pm', '2021-05-23 00:21:53', 'May', '2021', NULL, 2, '2021-05-23 12:21:53', '2021-06-03 12:31:09'),
(174, 'SDC02105243881', 24, NULL, 55, NULL, NULL, 1, '131.25', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '131.25', '131.25', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 7, 1, 1, '2021-05-24', '11:50:13 am', '2021-05-23 18:00:00', 'May', '2021', NULL, 1, '2021-05-24 05:50:13', '2021-05-24 05:50:13'),
(175, 'SDC02105245994', 24, NULL, 55, NULL, NULL, 2, '256.25', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '5.00', '12.81', '269.06', '269.06', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 7, 1, 1, '2021-05-24', '11:53:25 am', '2021-05-23 18:00:00', 'May', '2021', NULL, 1, '2021-05-24 05:53:25', '2021-06-07 09:41:46'),
(176, 'SDC0210601688559', NULL, 7, 41, NULL, NULL, 1, '1575.00', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '3060.00', '3060.00', '0.00', '0.00', 0, 1, '0.00', '0.00', NULL, 2, 1, 1, '02-06-2021', '07:24:46 pm', '2021-06-01 20:06:57', 'June', '2021', NULL, 2, '2021-06-01 13:24:46', '2021-06-10 06:37:39'),
(180, 'SDC02106026532', NULL, 7, 39, NULL, NULL, 2, '256.25', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '-16127.25', '-16127.25', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '2021-06-02', '06:27:21 pm', '2021-06-01 18:00:00', 'June', '2021', NULL, 1, '2021-06-02 12:27:21', '2021-06-02 12:27:21'),
(184, 'SDC02106024945', NULL, 7, 39, NULL, NULL, 2, '256.25', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '-15477.25', '-15477.25', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '2021-06-02', '06:34:22 pm', '2021-06-01 18:00:00', 'June', '2021', NULL, 1, '2021-06-02 12:34:22', '2021-06-02 12:34:22'),
(185, 'SDC02106026587', NULL, 7, 39, NULL, NULL, 1, '7612.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '-7864.75', '-7864.75', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '2021-06-02', '06:35:27 pm', '2021-06-01 18:00:00', 'June', '2021', NULL, 1, '2021-06-02 12:35:27', '2021-06-02 12:35:27'),
(186, 'SDC02106028329', NULL, 7, 39, NULL, NULL, 2, '768.75', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '768.75', '-7096.00', '0.00', '7864.75', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '2021-06-02', '06:37:47 pm', '2021-06-01 18:00:00', 'June', '2021', NULL, 1, '2021-06-02 12:37:47', '2021-06-02 12:37:47'),
(189, 'SDC0210603292949', NULL, 7, NULL, NULL, NULL, 2, '262.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '262.50', '0.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 5, 0, '03-06-2021', '11:16:40 am', '2021-06-03 05:16:40', 'June', '2021', NULL, 2, '2021-06-03 05:16:40', '2021-06-03 05:16:40'),
(191, 'SDC0210603946577', NULL, 7, 42, NULL, NULL, 2, '262.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '1148.75', '1148.75', '0.00', '0.00', 0, 1, '0.00', '0.00', NULL, 2, 1, 1, '03-06-2021', '01:51:43 pm', '2021-06-02 19:51:43', 'June', '2021', NULL, 2, '2021-06-03 07:51:43', '2021-06-03 12:14:38'),
(192, 'SDC0210603466947', NULL, 7, NULL, NULL, NULL, 2, '262.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '262.50', '0.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 6, 0, '03-06-2021', '05:09:35 pm', '2021-06-02 23:09:35', 'June', '2021', NULL, 2, '2021-06-03 11:09:35', '2021-06-03 11:09:35');
INSERT INTO `sales` (`id`, `invoice_id`, `branch_id`, `warehouse_id`, `customer_id`, `pay_term`, `pay_term_number`, `total_item`, `net_total_amount`, `order_discount_type`, `order_discount`, `order_discount_amount`, `shipment_details`, `shipment_address`, `shipment_charge`, `shipment_status`, `delivered_to`, `sale_note`, `order_tax_percent`, `order_tax_amount`, `total_payable_amount`, `paid`, `change_amount`, `due`, `is_return_available`, `ex_status`, `sale_return_amount`, `sale_return_due`, `payment_note`, `admin_id`, `status`, `is_fixed_challen`, `date`, `time`, `report_date`, `month`, `year`, `attachment`, `created_by`, `created_at`, `updated_at`) VALUES
(193, 'SDC0210603211588', NULL, 7, 44, NULL, NULL, 2, '262.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '381.25', '387.50', '0.00', '0.00', 0, 1, '0.00', '0.00', NULL, 2, 1, 1, '03-06-2021', '06:36:11 pm', '2021-06-03 00:36:11', 'June', '2021', NULL, 2, '2021-06-03 12:36:11', '2021-06-03 12:49:39'),
(194, 'SDC0210603232651', NULL, 7, NULL, NULL, NULL, 2, '1280.00', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '1142.50', '1273.75', '0.00', '0.00', 0, 1, '0.00', '0.00', NULL, 2, 1, 1, '03-06-2021', '06:57:26 pm', '2021-06-03 00:57:26', 'June', '2021', NULL, 2, '2021-06-03 12:57:26', '2021-06-03 13:10:38'),
(195, 'SDC0210603481195', NULL, 7, 51, NULL, NULL, 2, '1143.75', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '1143.75', '1143.75', '0.00', '0.00', 0, 1, '0.00', '0.00', NULL, 2, 1, 1, '03-06-2021', '07:11:16 pm', '2021-06-03 01:11:16', 'June', '2021', NULL, 2, '2021-06-03 13:11:16', '2021-06-10 06:37:28'),
(196, 'SDC0210603994881', NULL, 7, 54, NULL, NULL, 2, '381.25', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '381.25', '381.25', '0.00', '0.00', 0, 1, '0.00', '0.00', NULL, 2, 1, 1, '03-06-2021', '07:21:23 pm', '2021-06-03 01:21:23', 'June', '2021', NULL, 2, '2021-06-03 13:21:23', '2021-06-03 13:22:00'),
(197, 'SDC0210603661727', NULL, 7, NULL, NULL, NULL, 1, '250.00', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '250.00', '250.00', '0.00', '0.00', 0, 1, '0.00', '0.00', NULL, 2, 1, 1, '03-06-2021', '07:24:06 pm', '2021-06-03 01:24:06', 'June', '2021', NULL, 2, '2021-06-03 13:24:06', '2021-06-03 13:24:32'),
(198, 'SDC0210603171674', NULL, 7, 46, NULL, NULL, 1, '262.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '262.50', '262.50', '0.00', '0.00', 0, 1, '0.00', '0.00', NULL, 2, 1, 1, '03-06-2021', '07:25:26 pm', '2021-06-03 01:25:26', 'June', '2021', NULL, 2, '2021-06-03 13:25:26', '2021-06-05 05:48:40'),
(199, 'SDC0210603232319', NULL, 7, NULL, NULL, NULL, 1, '1025.00', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '825.00', '925.00', '100.00', '0.00', 0, 1, '0.00', '0.00', NULL, 2, 1, 1, '03-06-2021', '07:28:35 pm', '2021-06-03 01:28:35', 'June', '2021', NULL, 2, '2021-06-03 13:28:35', '2021-06-03 13:30:09'),
(200, 'SDC0210603932962', NULL, 7, NULL, NULL, NULL, 2, '381.25', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '381.25', '381.25', '0.00', '0.00', 0, 1, '0.00', '0.00', NULL, 2, 1, 1, '03-06-2021', '07:31:57 pm', '2021-06-03 01:31:57', 'June', '2021', NULL, 2, '2021-06-03 13:31:57', '2021-06-03 13:33:52'),
(201, 'SDC0210605464191', NULL, 7, NULL, NULL, NULL, 2, '1023.75', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '1023.75', '1029.75', '0.00', '-6.00', 0, 1, '0.00', '0.00', NULL, 2, 1, 1, '05-06-2021', '11:36:29 am', '2021-06-05 05:36:29', 'June', '2021', NULL, 2, '2021-06-05 05:36:29', '2021-06-06 05:15:40'),
(202, 'SDC02106068564', NULL, 7, NULL, NULL, NULL, 1, '131.25', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '131.25', '140.00', '8.75', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '2021-06-06', '12:28:17 pm', '2021-06-05 18:00:00', 'June', '2021', NULL, 1, '2021-06-06 06:28:17', '2021-06-06 06:28:17'),
(203, 'SDC02106062677', NULL, 7, 55, NULL, NULL, 2, '1023.75', 1, '0.00', '0.00', NULL, NULL, '0.00', 1, NULL, NULL, '0.00', '0.00', '1023.75', '1023.75', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '2021-06-06', '01:23:19 pm', '2021-06-05 18:00:00', 'June', '2021', NULL, 1, '2021-06-06 07:23:19', '2021-06-06 07:23:19'),
(204, 'SDC02106065985', NULL, 7, NULL, NULL, NULL, 2, '231.00', 1, '0.00', '0.00', NULL, NULL, '0.00', 1, NULL, NULL, '0.00', '0.00', '231.00', '231.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '2021-06-06', '01:35:31 pm', '2021-06-05 18:00:00', 'June', '2021', NULL, 1, '2021-06-06 07:35:31', '2021-06-06 07:35:31'),
(205, 'INV-DUE', NULL, 7, 46, NULL, NULL, 2, '231.00', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '231.00', '231.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '2021-06-06', '02:29:28 pm', '2021-06-05 18:00:00', 'June', '2021', NULL, 1, '2021-06-06 08:29:28', '2021-06-09 09:25:04'),
(206, 'SDC0210606861367', NULL, 7, 51, NULL, NULL, 4, '518.75', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '518.75', '518.75', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '06-06-2021', '05:22:47 pm', '2021-06-05 23:22:47', 'June', '2021', NULL, 2, '2021-06-06 11:22:47', '2021-06-10 06:37:20'),
(207, 'SDC011210607392582', 24, NULL, NULL, NULL, NULL, 3, '510.00', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '510.00', '510.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 7, 1, 1, '07-06-2021', '05:40:46 pm', '2021-06-06 23:40:46', 'June', '2021', NULL, 2, '2021-06-07 11:40:46', '2021-06-07 11:40:46'),
(208, 'SDC011210607911424', 24, NULL, 46, NULL, NULL, 1, '300.00', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '300.00', '300.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 7, 1, 1, '07-06-2021', '05:41:46 pm', '2021-06-06 23:41:46', 'June', '2021', NULL, 2, '2021-06-07 11:41:46', '2021-06-09 09:25:04'),
(209, 'TEST78210608633422', NULL, 7, NULL, NULL, NULL, 2, '262.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '262.50', '300.00', '37.50', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '08-06-2021', '04:10:55 pm', '2021-06-07 22:10:55', 'June', '2021', NULL, 2, '2021-06-08 10:10:55', '2021-06-08 10:10:55'),
(210, 'TEST78210608344845', NULL, 7, NULL, NULL, NULL, 1, '131.25', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '131.25', '140.00', '8.75', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '08-06-2021', '04:18:13 pm', '2021-06-07 22:18:13', 'June', '2021', NULL, 2, '2021-06-08 10:18:13', '2021-06-08 10:18:13'),
(211, 'TEST78210608542837', NULL, 7, NULL, NULL, NULL, 1, '131.25', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '131.25', '131.25', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '08-06-2021', '04:27:11 pm', '2021-06-07 22:27:11', 'June', '2021', NULL, 2, '2021-06-08 10:27:11', '2021-06-08 10:27:11'),
(212, 'TEST78210608617895', NULL, 7, NULL, NULL, NULL, 1, '131.25', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '131.25', '131.25', '8.75', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '08-06-2021', '04:27:24 pm', '2021-06-07 22:27:24', 'June', '2021', NULL, 2, '2021-06-08 10:27:24', '2021-06-08 10:27:24'),
(213, 'TEST78210608286687', NULL, 7, NULL, NULL, NULL, 1, '131.25', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '131.25', '131.25', '8.75', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '08-06-2021', '04:28:23 pm', '2021-06-07 22:28:23', 'June', '2021', NULL, 2, '2021-06-08 10:28:23', '2021-06-08 10:28:23'),
(214, 'TEST78210608776137', NULL, 7, NULL, NULL, NULL, 2, '262.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '262.50', '262.50', '37.50', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '08-06-2021', '04:31:36 pm', '2021-06-07 22:31:36', 'June', '2021', NULL, 2, '2021-06-08 10:31:36', '2021-06-08 10:31:36'),
(215, 'TEST78210608118487', NULL, 7, NULL, NULL, NULL, 2, '262.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '262.50', '262.50', '37.50', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '08-06-2021', '04:31:46 pm', '2021-06-07 22:31:46', 'June', '2021', NULL, 2, '2021-06-08 10:31:46', '2021-06-08 10:31:46'),
(216, 'TEST78210608615179', NULL, 7, NULL, NULL, NULL, 2, '262.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '262.50', '262.50', '37.50', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '08-06-2021', '04:32:26 pm', '2021-06-07 22:32:26', 'June', '2021', NULL, 2, '2021-06-08 10:32:26', '2021-06-08 10:32:26'),
(217, 'TEST78210608156528', NULL, 7, NULL, NULL, NULL, 2, '262.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '262.50', '262.50', '37.50', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '08-06-2021', '04:34:57 pm', '2021-06-07 22:34:57', 'June', '2021', NULL, 2, '2021-06-08 10:34:57', '2021-06-08 10:34:57'),
(218, 'TEST78210608892998', NULL, 7, NULL, NULL, NULL, 2, '262.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '262.50', '262.50', '37.50', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '08-06-2021', '04:35:05 pm', '2021-06-07 22:35:05', 'June', '2021', NULL, 2, '2021-06-08 10:35:05', '2021-06-08 10:35:05'),
(219, 'TEST78210608773886', NULL, 7, NULL, NULL, NULL, 2, '262.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '262.50', '262.50', '37.50', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '08-06-2021', '04:35:48 pm', '2021-06-07 22:35:48', 'June', '2021', NULL, 2, '2021-06-08 10:35:48', '2021-06-08 10:35:48'),
(220, 'TEST78210608876148', NULL, 7, NULL, NULL, NULL, 1, '131.25', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '131.25', '131.25', '8.75', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '08-06-2021', '04:36:05 pm', '2021-06-07 22:36:05', 'June', '2021', NULL, 2, '2021-06-08 10:36:05', '2021-06-08 10:36:05'),
(221, 'TEST78210608815192', NULL, 7, NULL, NULL, NULL, 1, '131.25', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '131.25', '131.25', '8.75', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '08-06-2021', '04:36:59 pm', '2021-06-07 22:36:59', 'June', '2021', NULL, 2, '2021-06-08 10:36:59', '2021-06-08 10:36:59'),
(222, 'TEST78210608281669', NULL, 7, NULL, NULL, NULL, 1, '131.25', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '131.25', '131.25', '8.75', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '08-06-2021', '04:39:17 pm', '2021-06-07 22:39:17', 'June', '2021', NULL, 2, '2021-06-08 10:39:17', '2021-06-08 10:39:17'),
(223, 'TEST78210608418939', NULL, 7, NULL, NULL, NULL, 1, '240.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '240.50', '240.50', '8.75', '0.00', 0, 1, '0.00', '0.00', NULL, 2, 1, 1, '08-06-2021', '04:55:39 pm', '2021-06-07 22:55:39', 'June', '2021', NULL, 2, '2021-06-08 10:55:39', '2021-06-08 13:14:18'),
(224, 'TEST78210608924314', NULL, 7, 41, NULL, NULL, 1, '262.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '262.50', '131.25', '0.00', '0.00', 0, 1, '0.00', '0.00', NULL, 2, 1, 1, '08-06-2021', '05:08:25 pm', '2021-06-07 23:08:25', 'June', '2021', NULL, 2, '2021-06-08 11:08:25', '2021-06-08 13:10:33'),
(225, 'TEST78210608167324', NULL, 7, 40, NULL, NULL, 1, '262.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '262.50', '140.00', '0.00', '0.00', 0, 1, '0.00', '0.00', NULL, 2, 1, 1, '08-06-2021', '05:09:27 pm', '2021-06-07 23:09:27', 'June', '2021', NULL, 2, '2021-06-08 11:09:27', '2021-06-08 13:07:02'),
(226, 'TEST782106086168', NULL, 7, 56, NULL, NULL, 1, '262.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '262.50', '262.50', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '2021-06-08', '05:27:11 pm', '2021-06-07 18:00:00', 'June', '2021', NULL, 1, '2021-06-08 11:27:11', '2021-06-08 11:27:11'),
(227, 'TEST782106085628', NULL, 7, 56, NULL, NULL, 1, '262.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '262.50', '262.50', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '2021-06-08', '05:27:17 pm', '2021-06-07 18:00:00', 'June', '2021', NULL, 1, '2021-06-08 11:27:17', '2021-06-08 11:27:17'),
(228, 'TEST782106088121', NULL, 7, 56, NULL, NULL, 1, '262.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '262.50', '262.50', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '2021-06-08', '05:28:08 pm', '2021-06-07 18:00:00', 'June', '2021', NULL, 1, '2021-06-08 11:28:08', '2021-06-08 11:28:08'),
(229, 'TEST782106083395', NULL, 7, 56, NULL, NULL, 1, '262.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '262.50', '262.50', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '2021-06-08', '05:29:18 pm', '2021-06-07 18:00:00', 'June', '2021', NULL, 1, '2021-06-08 11:29:18', '2021-06-08 11:29:18'),
(230, 'TEST782106087276', NULL, 7, 56, NULL, NULL, 1, '262.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '262.50', '262.50', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '2021-06-08', '05:31:29 pm', '2021-06-07 18:00:00', 'June', '2021', NULL, 1, '2021-06-08 11:31:29', '2021-06-08 11:31:29'),
(231, 'TEST782106085654', NULL, 7, 56, NULL, NULL, 1, '262.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '262.50', '262.50', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '2021-06-08', '05:33:15 pm', '2021-06-07 18:00:00', 'June', '2021', NULL, 1, '2021-06-08 11:33:15', '2021-06-08 11:33:15'),
(232, 'TEST782106089227', NULL, 7, 56, NULL, NULL, 1, '262.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '262.50', '262.50', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '2021-06-08', '05:39:49 pm', '2021-06-07 18:00:00', 'June', '2021', NULL, 1, '2021-06-08 11:39:49', '2021-06-08 11:39:49'),
(233, 'TEST782106087374', NULL, 7, 56, NULL, NULL, 1, '262.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '262.50', '262.50', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '2021-06-08', '05:39:49 pm', '2021-06-07 18:00:00', 'June', '2021', NULL, 1, '2021-06-08 11:39:49', '2021-06-08 11:39:49'),
(234, 'TEST782106086338', NULL, 7, 56, NULL, NULL, 1, '262.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '262.50', '262.50', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '2021-06-08', '05:39:50 pm', '2021-06-07 18:00:00', 'June', '2021', NULL, 1, '2021-06-08 11:39:50', '2021-06-08 11:39:50'),
(235, 'TEST782106085998', NULL, 7, 56, NULL, NULL, 1, '262.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '262.50', '262.50', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '2021-06-08', '05:45:13 pm', '2021-06-07 18:00:00', 'June', '2021', NULL, 1, '2021-06-08 11:45:13', '2021-06-08 11:45:13'),
(236, 'TEST782106084898', NULL, 7, 56, NULL, NULL, 1, '262.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '262.50', '262.50', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '2021-06-08', '05:46:24 pm', '2021-06-07 18:00:00', 'June', '2021', NULL, 1, '2021-06-08 11:46:24', '2021-06-08 11:46:24'),
(237, 'TEST782106081139', NULL, 7, NULL, NULL, NULL, 1, '131.25', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '131.25', '140.00', '8.75', '0.00', 1, 0, '131.25', '0.00', NULL, 2, 1, 1, '2021-06-08', '05:47:35 pm', '2021-06-07 18:00:00', 'June', '2021', NULL, 1, '2021-06-08 11:47:35', '2021-06-08 12:09:44'),
(238, 'TEST78210608575675', NULL, 7, NULL, NULL, NULL, 4, '643.75', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '643.75', '643.75', '81.25', '0.00', 0, 1, '0.00', '0.00', NULL, 2, 1, 1, '08-06-2021', '07:18:17 pm', '2021-06-08 01:18:17', 'June', '2021', NULL, 2, '2021-06-08 13:18:17', '2021-06-08 13:19:25'),
(239, 'TEST782106092465', NULL, 7, NULL, NULL, NULL, 1, '131.25', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '131.25', '131.25', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '2021-06-09', '01:16:33 pm', '2021-06-08 18:00:00', 'June', '2021', NULL, 1, '2021-06-09 07:16:33', '2021-06-09 07:16:33'),
(240, 'TEST78210609568893', NULL, 7, 54, NULL, NULL, 2, '1575.00', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '1575.00', '1575.00', '25.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '09-06-2021', '02:56:54 pm', '2021-06-08 20:56:54', 'June', '2021', NULL, 2, '2021-06-09 08:56:54', '2021-06-09 08:56:54'),
(242, 'SDC011210609475382', 24, NULL, NULL, NULL, NULL, 2, '2546.25', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '2546.25', '2662.50', '153.75', '0.00', 0, 1, '0.00', '0.00', NULL, 10, 1, 1, '09-06-2021', '04:51:38 pm', '2021-06-08 22:51:38', 'June', '2021', NULL, 2, '2021-06-09 10:51:38', '2021-06-09 10:53:25'),
(243, 'SDC011210609839827', 24, NULL, NULL, NULL, NULL, 2, '18878.75', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '18878.75', '20378.75', '1521.25', '0.00', 0, 1, '0.00', '0.00', NULL, 10, 1, 1, '09-06-2021', '04:53:57 pm', '2021-06-08 22:53:57', 'June', '2021', NULL, 2, '2021-06-09 10:53:57', '2021-06-09 10:54:40'),
(244, 'SDC011210609461829', 24, NULL, NULL, NULL, NULL, 2, '562.50', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '562.50', '762.50', '237.50', '-200.00', 0, 1, '0.00', '0.00', NULL, 10, 1, 1, '09-06-2021', '05:04:19 pm', '2021-06-08 23:04:19', 'June', '2021', NULL, 2, '2021-06-09 11:04:19', '2021-06-09 11:05:21'),
(245, 'SDC011210609445698', 24, NULL, NULL, NULL, NULL, 2, '378.75', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '378.75', '378.75', '121.25', '0.00', 0, 1, '0.00', '0.00', NULL, 10, 1, 1, '09-06-2021', '05:07:10 pm', '2021-06-08 23:07:10', 'June', '2021', NULL, 2, '2021-06-09 11:07:10', '2021-06-09 11:07:47'),
(253, 'TEST782106104146', NULL, 7, 61, NULL, NULL, 1, '131.25', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '131.25', '131.25', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '2021-06-10', '12:06:46 pm', '2021-06-09 18:00:00', 'June', '2021', NULL, 1, '2021-06-10 06:06:46', '2021-06-10 06:09:43'),
(254, 'TEST782106101651', NULL, 7, 61, NULL, NULL, 1, '131.25', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '131.25', '131.25', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '2021-06-10', '12:09:43 pm', '2021-06-09 18:00:00', 'June', '2021', NULL, 1, '2021-06-10 06:09:43', '2021-06-10 06:09:43'),
(255, 'TEST782106104343', NULL, 7, NULL, NULL, NULL, 1, '131.25', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '131.25', '131.25', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '2021-06-10', '05:09:36 pm', '2021-06-09 18:00:00', 'June', '2021', NULL, 1, '2021-06-10 11:09:36', '2021-06-10 11:09:36'),
(256, 'TEST78210610415536', NULL, 7, NULL, NULL, NULL, 1, '131.25', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '131.25', '131.25', '8.75', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '10-06-2021', '05:10:04 pm', '2021-06-09 23:10:04', 'June', '2021', NULL, 2, '2021-06-10 11:10:04', '2021-06-10 11:10:04'),
(257, 'SDC011210610225392', 24, NULL, NULL, NULL, NULL, 1, '131.25', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '131.25', '131.25', '8.75', '0.00', 0, 0, '0.00', '0.00', NULL, 7, 1, 1, '10-06-2021', '05:10:59 pm', '2021-06-09 23:10:59', 'June', '2021', NULL, 2, '2021-06-10 11:10:59', '2021-06-10 11:10:59'),
(258, 'SDC0112106109453', 24, NULL, NULL, NULL, NULL, 1, '131.25', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '131.25', '131.25', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 7, 1, 1, '2021-06-10', '05:12:10 pm', '2021-06-09 18:00:00', 'June', '2021', NULL, 1, '2021-06-10 11:12:10', '2021-06-10 11:12:10'),
(259, 'TEST782106138424', NULL, 7, NULL, NULL, NULL, 1, '131.25', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '131.25', '131.25', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '2021-06-13', '01:59:14 pm', '2021-06-12 18:00:00', 'June', '2021', NULL, 1, '2021-06-13 07:59:14', '2021-06-13 07:59:14'),
(260, 'TEST782106134188', NULL, 7, NULL, NULL, NULL, 2, '262.50', 1, '0.00', '0.00', 'Shipment Details', NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '262.50', '262.50', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '2021-06-13', '02:03:31 pm', '2021-06-12 18:00:00', 'June', '2021', NULL, 1, '2021-06-13 08:03:31', '2021-06-15 06:22:46'),
(261, 'TEST782106162828', NULL, 7, NULL, NULL, NULL, 1, '131.25', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '131.25', '131.25', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '2021-06-16', '11:49:23 am', '2021-06-15 18:00:00', 'June', '2021', NULL, 1, '2021-06-16 05:49:23', '2021-06-16 05:49:23'),
(262, 'TEST782106169284', NULL, 7, NULL, NULL, NULL, 2, '256.25', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '256.25', '256.25', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '2021-06-16', '04:32:56 pm', '2021-06-15 18:00:00', 'June', '2021', NULL, 1, '2021-06-16 10:32:56', '2021-06-16 10:32:56'),
(263, 'TEST782106177369', NULL, 7, NULL, NULL, NULL, 1, '50400.00', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '50400.00', '50400.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '2021-06-17', '03:16:40 pm', '2021-06-16 18:00:00', 'June', '2021', NULL, 1, '2021-06-17 09:16:40', '2021-06-17 09:16:40'),
(264, 'TEST782106177649', NULL, 7, 61, NULL, NULL, 1, '50400.00', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '50400.00', '50400.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '2021-06-17', '04:39:58 pm', '2021-06-16 18:00:00', 'June', '2021', NULL, 1, '2021-06-17 10:39:58', '2021-06-17 10:39:58'),
(265, 'TEST782106174497', NULL, 7, 61, NULL, NULL, 1, '50400.00', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '50400.00', '50400.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '2021-06-17', '04:41:59 pm', '2021-06-16 18:00:00', 'June', '2021', NULL, 1, '2021-06-17 10:41:59', '2021-06-17 10:41:59'),
(266, 'TEST78210617154594', NULL, 7, 61, NULL, NULL, 1, '50400.00', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '50400.00', '400.00', '0.00', '50000.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '17-06-2021', '04:44:49 pm', '2021-06-16 22:44:49', 'June', '2021', NULL, 2, '2021-06-17 10:44:49', '2021-06-17 10:44:49');

-- --------------------------------------------------------

--
-- Table structure for table `sale_payments`
--

CREATE TABLE `sale_payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sale_id` bigint(20) UNSIGNED DEFAULT NULL,
  `customer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `pay_mode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paid_amount` decimal(22,2) NOT NULL DEFAULT 0.00,
  `payment_on` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1=sale_invoice_due;2=customer_due',
  `payment_type` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1=sale_due;2=return_due',
  `payment_status` tinyint(4) DEFAULT NULL COMMENT '1=due;2=partial;3=paid',
  `date` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `time` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `month` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `year` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `report_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `card_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_holder` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_type_id` bigint(20) UNSIGNED DEFAULT NULL,
  `card_transaction_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_month` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_year` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_secure_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cheque_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transaction_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attachment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `note` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `admin_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sale_payments`
--

INSERT INTO `sale_payments` (`id`, `invoice_id`, `sale_id`, `customer_id`, `account_id`, `pay_mode`, `paid_amount`, `payment_on`, `payment_type`, `payment_status`, `date`, `time`, `month`, `year`, `report_date`, `card_no`, `card_holder`, `card_type`, `card_type_id`, `card_transaction_no`, `card_month`, `card_year`, `card_secure_code`, `account_no`, `cheque_no`, `transaction_no`, `attachment`, `note`, `admin_id`, `created_at`, `updated_at`) VALUES
(3, 'SPI2104115489', 17, NULL, NULL, 'Advanced', '262.50', 1, 1, NULL, '11-04-2021', '12:07:23 pm', 'April', '2021', '2021-04-10 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, '2021-04-11 06:07:23', '2021-04-11 06:07:23'),
(4, 'SPI2104115587', 18, 39, 16, 'Advanced', '13348.75', 1, 1, NULL, '11-04-2021', '12:20:36 pm', 'April', '2021', '2021-04-10 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, '2021-04-11 06:20:36', '2021-04-11 06:20:36'),
(6, 'SPI2104114744', 20, 39, 16, 'Advanced', '1805.00', 1, 1, NULL, '11-04-2021', '01:15:58 pm', 'April', '2021', '2021-04-10 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, '2021-04-11 07:15:58', '2021-04-11 07:15:58'),
(7, 'SPI2104129477', 21, 39, NULL, 'Advanced', '1155.00', 1, 1, NULL, '12-04-2021', '05:51:02 pm', 'April', '2021', '2021-04-11 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-04-12 11:51:02', '2021-04-12 11:51:02'),
(8, 'SPI2104123975', 22, 39, NULL, 'Advanced', '281.25', 1, 1, NULL, '12-04-2021', '06:52:09 pm', 'April', '2021', '2021-04-11 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-04-12 12:52:09', '2021-04-12 12:52:09'),
(9, 'SPI2104179815', 24, 40, 16, 'Cash', '1023.75', 1, 1, NULL, '17-04-2021', '09:47:27 am', 'April', '2021', '2021-04-16 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-04-17 03:47:27', '2021-04-17 03:47:27'),
(13, 'SPI210419431824', 14, 39, 15, 'Cash', '315.00', 1, 1, NULL, '19-04-2021', '11:08:42 am', 'April', '2021', '2021-04-18 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-04-19 05:08:42', '2021-04-19 05:08:42'),
(14, 'SPI210419623632', 14, 39, 15, 'Cash', '315.00', 1, 1, NULL, '19-04-2021', '11:08:54 am', 'April', '2021', '2021-04-18 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-04-19 05:08:54', '2021-04-19 05:08:54'),
(15, 'SPI210419279662', 14, 39, 15, 'Cash', '315.00', 1, 1, NULL, '19-04-2021', '11:10:18 am', 'April', '2021', '2021-04-18 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-04-19 05:10:18', '2021-04-19 05:10:18'),
(17, 'SPI2104251647', 29, 44, 16, 'Cash', '2566.25', 1, 1, NULL, '25-04-2021', '05:46:06 pm', 'April', '2021', '2021-04-24 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, '2021-04-25 11:46:06', '2021-04-25 11:46:06'),
(18, 'SPI2104254894', 30, NULL, 16, 'Cash', '1411.25', 1, 1, NULL, '25-04-2021', '05:56:13 pm', 'April', '2021', '2021-04-24 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, '2021-04-25 11:56:13', '2021-04-25 11:56:13'),
(19, 'SPI21042515146', 14, 39, NULL, 'Cash', '4974.75', 1, 1, NULL, '25-04-2021', '07:16:33 pm', 'April', '2021', '2021-04-24 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-04-25 13:16:33', '2021-04-25 13:16:33'),
(22, 'SPI21042567576', 25, 45, 15, 'Cash', '1173.75', 1, 1, NULL, '25-04-2021', '07:43:29 pm', 'April', '2021', '2021-04-24 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-04-25 13:43:29', '2021-04-25 13:43:29'),
(23, 'SPI2104263127', 31, 46, NULL, 'Cash', '262.50', 1, 1, NULL, '2021-04-26', '01:40:51 pm', 'April', '2021', '2021-04-25 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-04-26 07:40:51', '2021-04-26 07:40:51'),
(24, 'SPI2104265536', 32, NULL, NULL, 'Cash', '1155.00', 1, 1, NULL, '2021-04-26', '01:43:34 pm', 'April', '2021', '2021-04-25 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-04-26 07:43:34', '2021-04-26 07:43:34'),
(25, 'SPI2104265323', 33, 43, NULL, 'Cash', '1023.75', 1, 1, NULL, '2021-04-26', '01:45:32 pm', 'April', '2021', '2021-04-25 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-04-26 07:45:32', '2021-04-26 07:45:32'),
(26, 'SPI2104262744', 34, 43, NULL, 'Cash', '1023.75', 1, 1, NULL, '2021-04-26', '01:45:40 pm', 'April', '2021', '2021-04-25 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-04-26 07:45:40', '2021-04-26 07:45:40'),
(27, 'SPI2104266457', 35, 43, NULL, 'Cash', '1023.75', 1, 1, NULL, '2021-04-26', '01:45:40 pm', 'April', '2021', '2021-04-25 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-04-26 07:45:40', '2021-04-26 07:45:40'),
(28, 'SPI2104267258', 36, 43, NULL, 'Cash', '1023.75', 1, 1, NULL, '2021-04-26', '01:45:41 pm', 'April', '2021', '2021-04-25 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-04-26 07:45:41', '2021-04-26 07:45:41'),
(29, 'SPI2104264867', 37, 43, NULL, 'Cash', '1023.75', 1, 1, NULL, '2021-04-26', '01:45:46 pm', 'April', '2021', '2021-04-25 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-04-26 07:45:46', '2021-04-26 07:45:46'),
(30, 'SPI2104262315', 38, 43, NULL, 'Cash', '1023.75', 1, 1, NULL, '2021-04-26', '01:45:48 pm', 'April', '2021', '2021-04-25 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-04-26 07:45:48', '2021-04-26 07:45:48'),
(31, 'SPI2104267965', 39, 43, NULL, 'Cash', '1023.75', 1, 1, NULL, '2021-04-26', '01:46:34 pm', 'April', '2021', '2021-04-25 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-04-26 07:46:34', '2021-04-26 07:46:34'),
(32, 'SPI2104262426', 40, 42, NULL, 'Cash', '823.75', 1, 1, NULL, '2021-04-26', '02:07:38 pm', 'April', '2021', '2021-04-25 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-04-26 08:07:38', '2021-04-26 08:07:38'),
(33, 'SPI2104268423', 41, NULL, NULL, 'Cash', '1023.75', 1, 1, NULL, '2021-04-26', '02:09:27 pm', 'April', '2021', '2021-04-25 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-04-26 08:09:27', '2021-04-26 08:09:27'),
(34, 'SPI2104269136', 42, NULL, NULL, 'Cash', '1023.75', 1, 1, NULL, '2021-04-26', '02:14:50 pm', 'April', '2021', '2021-04-25 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-04-26 08:14:50', '2021-04-26 08:14:50'),
(35, 'SPI2104267153', 43, 45, NULL, 'Cash', '923.75', 1, 1, NULL, '2021-04-26', '02:20:34 pm', 'April', '2021', '2021-04-25 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-04-26 08:20:34', '2021-04-26 08:20:34'),
(36, 'SPI2104269563', 44, 41, NULL, 'Cash', '131.25', 1, 1, NULL, '2021-04-26', '02:23:00 pm', 'April', '2021', '2021-04-25 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-04-26 08:23:00', '2021-04-26 08:23:00'),
(37, 'SPI2104269314', 45, 45, NULL, 'Cash', '974.94', 1, 1, NULL, '26-04-2021', '03:39:31 pm', 'April', '2021', '2021-04-25 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-04-26 09:39:31', '2021-04-26 09:39:31'),
(38, 'SPI21042644846', 45, 45, 16, 'Card', '180.06', 1, 1, NULL, '26-04-2021', '04:48:17 pm', 'April', '2021', '2021-04-25 18:00:00', NULL, NULL, 'Credit-Card', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-04-26 10:48:17', '2021-04-26 10:48:17'),
(39, 'SPI2104279222', 46, 47, NULL, 'Cash', '1781.85', 1, 1, NULL, '2021-04-27', '12:10:37 pm', 'April', '2021', '2021-04-26 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-04-27 06:10:37', '2021-04-27 06:10:37'),
(40, 'SPI2104281531', 48, 41, NULL, 'Cash', '1559.25', 1, 1, NULL, '2021-04-28', '11:44:22 am', 'April', '2021', '2021-04-27 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-04-28 05:44:22', '2021-04-28 05:44:22'),
(41, 'SPI2105025517', 50, 40, 16, 'Cash', '162.50', 1, 1, NULL, '2021-05-02', '04:44:58 pm', 'May', '2021', '2021-05-01 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-05-02 10:44:58', '2021-05-02 10:44:58'),
(42, 'SPI2105041545', 51, 53, NULL, 'Cash', '387.50', 1, 1, NULL, '2021-05-04', '10:56:25 am', 'May', '2021', '2021-05-03 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-05-04 04:56:25', '2021-05-04 04:56:25'),
(43, 'SPI2105041377', 58, 51, NULL, 'Cash', '131.25', 1, 1, NULL, '2021-05-04', '11:09:15 am', 'May', '2021', '2021-05-03 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-05-04 05:09:15', '2021-05-04 05:09:15'),
(44, 'SPI210508919361', 70, NULL, NULL, NULL, '262.50', 1, 1, NULL, '08-05-2021', '05:13:44 pm', 'May', '2021', '2021-05-07 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-05-08 11:13:44', '2021-05-08 11:13:44'),
(45, 'SPI210508366953', 71, NULL, NULL, NULL, '262.50', 1, 1, NULL, '08-05-2021', '05:13:57 pm', 'May', '2021', '2021-05-07 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-05-08 11:13:57', '2021-05-08 11:13:57'),
(46, 'SPI210508999448', 72, NULL, NULL, NULL, '262.50', 1, 1, NULL, '08-05-2021', '05:14:47 pm', 'May', '2021', '2021-05-07 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-05-08 11:14:47', '2021-05-08 11:14:47'),
(47, 'SPI210508661767', 73, NULL, NULL, NULL, '262.50', 1, 1, NULL, '08-05-2021', '05:16:53 pm', 'May', '2021', '2021-05-07 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-05-08 11:16:53', '2021-05-08 11:16:53'),
(48, 'SPI210508392154', 75, NULL, NULL, NULL, '262.50', 1, 1, NULL, '08-05-2021', '05:30:27 pm', 'May', '2021', '2021-05-07 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-05-08 11:30:27', '2021-05-08 11:30:27'),
(49, 'SPI210508921513', 82, NULL, 15, NULL, '1023.75', 1, 1, NULL, '08-05-2021', '06:44:59 pm', 'May', '2021', '2021-05-07 18:00:00', 'FF', 'FF', 'Credit-Card', NULL, 'FF', 'FF', 'FF', 'FF', NULL, NULL, NULL, NULL, NULL, 2, '2021-05-08 12:44:59', '2021-05-08 12:44:59'),
(50, 'SPI210508223735', 83, NULL, 15, NULL, '300.00', 1, 1, NULL, '08-05-2021', '06:45:49 pm', 'May', '2021', '2021-05-07 18:00:00', NULL, NULL, 'Credit-Card', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-05-08 12:45:49', '2021-05-08 12:45:49'),
(51, 'SPI210508855231', 84, NULL, NULL, NULL, '300.00', 1, 1, NULL, '08-05-2021', '06:48:44 pm', 'May', '2021', '2021-05-07 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-05-08 12:48:44', '2021-05-08 12:48:44'),
(52, 'SPI210508782182', 91, NULL, NULL, NULL, '262.50', 1, 1, NULL, '08-05-2021', '06:57:45 pm', 'May', '2021', '2021-05-07 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-05-08 12:57:45', '2021-05-08 12:57:45'),
(53, 'SPI210508124598', 92, 54, NULL, NULL, '262.50', 1, 1, NULL, '08-05-2021', '06:58:02 pm', 'May', '2021', '2021-05-07 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-05-08 12:58:02', '2021-05-08 12:58:02'),
(54, 'SPI210508225892', 96, 54, NULL, NULL, '262.50', 1, 1, NULL, '08-05-2021', '06:58:53 pm', 'May', '2021', '2021-05-07 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-05-08 12:58:53', '2021-05-08 12:58:53'),
(55, 'SPI210508321897', 99, 54, NULL, NULL, '262.50', 1, 1, NULL, '08-05-2021', '07:04:43 pm', 'May', '2021', '2021-05-07 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-05-08 13:04:43', '2021-05-08 13:04:43'),
(56, 'SPI210508321897', 74, 54, NULL, NULL, '262.50', 2, 1, NULL, '08-05-2021', '07:04:43 pm', 'May', '2021', '2021-05-07 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-05-08 13:04:43', '2021-05-08 13:04:43'),
(57, 'SPI210508321897', 93, 54, NULL, NULL, '262.50', 2, 1, NULL, '08-05-2021', '07:04:43 pm', 'May', '2021', '2021-05-07 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-05-08 13:04:43', '2021-05-08 13:04:43'),
(58, 'SPI210508948334', 100, NULL, 16, NULL, '262.50', 1, 1, NULL, '08-05-2021', '07:07:54 pm', 'May', '2021', '2021-05-07 18:00:00', NULL, NULL, 'Credit-Card', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-05-08 13:07:54', '2021-05-08 13:07:54'),
(59, 'SPI210508523531', 101, NULL, 16, NULL, '262.50', 1, 1, NULL, '08-05-2021', '07:10:35 pm', 'May', '2021', '2021-05-07 18:00:00', NULL, NULL, 'Credit-Card', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-05-08 13:10:35', '2021-05-08 13:10:35'),
(60, 'SPI210508736789', 102, 54, 16, NULL, '65.63', 1, 1, NULL, '08-05-2021', '07:11:24 pm', 'May', '2021', '2021-05-07 18:00:00', NULL, NULL, 'Credit-Card', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-05-08 13:11:24', '2021-05-08 13:11:24'),
(61, 'SPI210508161995', 103, 54, NULL, NULL, '20.00', 1, 1, NULL, '08-05-2021', '07:12:46 pm', 'May', '2021', '2021-05-07 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-05-08 13:12:46', '2021-05-08 13:12:46'),
(62, 'SPI210508143332', 104, NULL, NULL, NULL, '262.50', 1, 1, NULL, '08-05-2021', '07:14:02 pm', 'May', '2021', '2021-05-07 18:00:00', NULL, NULL, 'Credit-Card', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-05-08 13:14:02', '2021-05-08 13:14:02'),
(63, 'SPI210508617218', 105, NULL, NULL, NULL, '262.50', 1, 1, NULL, '08-05-2021', '07:14:35 pm', 'May', '2021', '2021-05-07 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-05-08 13:14:35', '2021-05-08 13:14:35'),
(64, 'SPI210508421976', 106, NULL, 16, NULL, '262.50', 1, 1, NULL, '08-05-2021', '07:16:33 pm', 'May', '2021', '2021-05-07 18:00:00', NULL, NULL, 'Credit-Card', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-05-08 13:16:33', '2021-05-08 13:16:33'),
(65, 'SPI210508487258', 107, NULL, NULL, NULL, '131.25', 1, 1, NULL, '08-05-2021', '07:18:18 pm', 'May', '2021', '2021-05-07 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-05-08 13:18:18', '2021-05-08 13:18:18'),
(66, 'SPI210508118541', 108, NULL, 16, NULL, '262.50', 1, 1, NULL, '08-05-2021', '07:18:34 pm', 'May', '2021', '2021-05-07 18:00:00', NULL, NULL, 'Credit-Card', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-05-08 13:18:34', '2021-05-08 13:18:34'),
(67, 'SPI210508549349', 109, NULL, NULL, NULL, '262.50', 1, 1, NULL, '08-05-2021', '07:19:13 pm', 'May', '2021', '2021-05-07 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-05-08 13:19:13', '2021-05-08 13:19:13'),
(68, 'SPI210508953199', 110, NULL, NULL, NULL, '262.50', 1, 1, NULL, '08-05-2021', '07:19:29 pm', 'May', '2021', '2021-05-07 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-05-08 13:19:29', '2021-05-08 13:19:29'),
(69, 'SPI210508743843', 111, NULL, NULL, NULL, '262.50', 1, 1, NULL, '08-05-2021', '07:20:01 pm', 'May', '2021', '2021-05-07 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-05-08 13:20:01', '2021-05-08 13:20:01'),
(70, 'SPI210508476788', 112, NULL, NULL, NULL, '262.50', 1, 1, NULL, '08-05-2021', '07:20:24 pm', 'May', '2021', '2021-05-07 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-05-08 13:20:24', '2021-05-08 13:20:24'),
(71, 'SPI210508367814', 113, NULL, NULL, NULL, '262.50', 1, 1, NULL, '08-05-2021', '07:20:58 pm', 'May', '2021', '2021-05-07 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-05-08 13:20:58', '2021-05-08 13:20:58'),
(72, 'SPI210508111574', 114, NULL, NULL, NULL, '262.50', 1, 1, NULL, '08-05-2021', '07:21:07 pm', 'May', '2021', '2021-05-07 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-05-08 13:21:07', '2021-05-08 13:21:07'),
(73, 'SPI210508132856', 115, NULL, NULL, NULL, '131.25', 1, 1, NULL, '08-05-2021', '07:21:23 pm', 'May', '2021', '2021-05-07 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-05-08 13:21:23', '2021-05-08 13:21:23'),
(74, 'SPI210508992648', 116, NULL, NULL, NULL, '262.50', 1, 1, NULL, '08-05-2021', '07:22:02 pm', 'May', '2021', '2021-05-07 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-05-08 13:22:02', '2021-05-08 13:22:02'),
(75, 'SPI210508787488', 117, NULL, NULL, NULL, '262.50', 1, 1, NULL, '08-05-2021', '07:22:16 pm', 'May', '2021', '2021-05-07 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-05-08 13:22:16', '2021-05-08 13:22:16'),
(77, 'SPI210508237968', 97, 54, 16, NULL, '262.50', 2, 1, NULL, '08-05-2021', '07:45:53 pm', 'May', '2021', '2021-05-07 18:00:00', 'FF', 'FF', 'Credit-Card', NULL, 'GG', 'FF', 'FF', 'FF', NULL, NULL, NULL, NULL, NULL, 2, '2021-05-08 13:45:53', '2021-05-08 13:45:53'),
(78, 'SPI210508237968', 103, 54, 16, NULL, '150.63', 2, 1, NULL, '08-05-2021', '07:45:53 pm', 'May', '2021', '2021-05-07 18:00:00', 'FF', 'FF', 'Credit-Card', NULL, 'GG', 'FF', 'FF', 'FF', NULL, NULL, NULL, NULL, NULL, 2, '2021-05-08 13:45:53', '2021-05-08 13:45:53'),
(79, 'SPI210508237968', 118, 54, 16, NULL, '262.50', 2, 1, NULL, '08-05-2021', '07:45:53 pm', 'May', '2021', '2021-05-07 18:00:00', 'FF', 'FF', 'Credit-Card', NULL, 'GG', 'FF', 'FF', 'FF', NULL, NULL, NULL, NULL, NULL, 2, '2021-05-08 13:45:53', '2021-05-08 13:45:53'),
(80, 'SPI210508237968', 119, 54, 16, NULL, '262.50', 2, 1, NULL, '08-05-2021', '07:45:53 pm', 'May', '2021', '2021-05-07 18:00:00', 'FF', 'FF', 'Credit-Card', NULL, 'GG', 'FF', 'FF', 'FF', NULL, NULL, NULL, NULL, NULL, 2, '2021-05-08 13:45:53', '2021-05-08 13:45:53'),
(81, 'SPI210509611621', 132, NULL, NULL, NULL, '275.63', 1, 1, NULL, '09-05-2021', '05:49:47 pm', 'May', '2021', '2021-05-08 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-05-09 11:49:47', '2021-05-09 11:49:47'),
(82, 'SPI210509792784', 133, 54, 16, NULL, '254.63', 1, 1, NULL, '09-05-2021', '06:32:55 pm', 'May', '2021', '2021-05-08 18:00:00', NULL, NULL, 'Credit-Card', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-05-09 12:32:55', '2021-05-09 12:32:55'),
(83, 'SPI210509762396', 135, NULL, NULL, NULL, '55059.38', 1, 1, NULL, '09-05-2021', '07:00:03 pm', 'May', '2021', '2021-05-08 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-05-09 13:00:03', '2021-05-09 13:00:03'),
(84, 'SPI210509565849', 136, 55, NULL, 'Cash', '892.50', 1, 1, NULL, '2021-05-09', '07:02:56 pm', 'June', '2021', '2021-05-08 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-05-09 13:02:56', '2021-06-05 05:59:31'),
(85, 'SPI2105097464', 137, NULL, NULL, 'Cash', '131.25', 1, 1, NULL, '2021-05-09', '07:04:10 pm', 'May', '2021', '2021-05-08 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-05-09 13:04:10', '2021-05-09 13:04:10'),
(86, 'SPI210509559382', 138, NULL, NULL, NULL, '1023.75', 1, 1, NULL, '09-05-2021', '07:15:40 pm', 'May', '2021', '2021-05-08 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-05-09 13:15:40', '2021-05-09 13:15:40'),
(88, 'SPI210509248953', 140, NULL, NULL, 'Card', '13860.00', 1, 1, NULL, '09-05-2021', '08:44:15 pm', 'May', '2021', '2021-05-08 18:00:00', 'FF', 'FF', 'Credit-Card', NULL, 'FF', 'FF', 'FF', 'FF', NULL, NULL, NULL, NULL, NULL, 2, '2021-05-09 14:44:15', '2021-05-09 14:44:15'),
(92, 'SPI210509712314', 139, NULL, NULL, 'Cash', '1023.75', 1, 1, NULL, '09-05-2021', '08:53:02', 'May', '2021', '2021-05-08 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-05-09 14:53:02', '2021-05-09 14:53:02'),
(104, 'SPI210509451967', 141, NULL, NULL, 'Cash', '262.50', 1, 1, NULL, '09-05-2021', '09:11:58', 'May', '2021', '2021-05-08 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-05-09 15:11:58', '2021-05-09 15:11:58'),
(107, 'SPI210509668526', 124, NULL, NULL, 'Cash', '262.50', 1, 1, NULL, '09-05-2021', '09:14:33', 'May', '2021', '2021-05-08 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-05-09 15:14:33', '2021-05-09 15:14:33'),
(109, 'SPI210510224248', 78, NULL, 16, 'Bank-Transfer', '262.50', 1, 1, NULL, '10-05-2021', '11:23:23', 'May', '2021', '2021-05-09 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'A/C:7445588522224', NULL, NULL, NULL, NULL, 2, '2021-05-10 05:23:23', '2021-05-10 05:23:23'),
(110, 'SPI210510695437', 134, NULL, NULL, 'Cash', '262.50', 1, 1, NULL, '10-05-2021', '04:02:10', 'May', '2021', '2021-05-09 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-05-10 10:02:10', '2021-05-10 10:02:10'),
(111, 'SPI210510261156', 143, NULL, NULL, 'Cash', '262.50', 1, 1, NULL, '10-05-2021', '04:19:37', 'May', '2021', '2021-05-09 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-05-10 10:19:37', '2021-05-10 10:19:37'),
(112, 'SPI210510449636', 142, NULL, NULL, 'Cash', '262.50', 1, 1, NULL, '10-05-2021', '04:21:02', 'May', '2021', '2021-05-09 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-05-10 10:21:02', '2021-05-10 10:21:02'),
(113, 'SPI210510931293', 145, NULL, 16, 'Card', '262.50', 1, 1, NULL, '10-05-2021', '07:06:22 pm', 'May', '2021', '2021-05-09 18:00:00', NULL, NULL, 'Credit-Card', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-05-10 13:06:22', '2021-05-10 13:06:22'),
(115, 'SPI210511361457', 150, NULL, NULL, 'Card', '46133.25', 1, 1, NULL, '11-05-2021', '10:10:13', 'May', '2021', '2021-05-10 18:00:00', 'FF', 'FF', 'Credit-Card', NULL, 'FF', 'FF', 'FF', 'FF', NULL, NULL, NULL, NULL, NULL, 7, '2021-05-11 04:10:13', '2021-05-11 04:10:13'),
(118, 'SPI210511853519', 151, NULL, NULL, 'Cash', '46000.00', 1, 1, NULL, '11-05-2021', '10:18:26', 'May', '2021', '2021-05-10 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 7, '2021-05-11 04:18:26', '2021-05-11 04:18:26'),
(120, 'SPI210511326647', 152, NULL, NULL, 'Cash', '46000.00', 1, 1, NULL, '11-05-2021', '10:19:34', 'May', '2021', '2021-05-10 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 7, '2021-05-11 04:19:34', '2021-05-11 04:19:34'),
(121, 'SPI210518371179', 157, 45, 15, 'Cash', '100.00', 1, 1, NULL, '18-05-2021', '01:26:26 pm', 'May', '2021', '2021-05-17 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-05-18 07:26:26', '2021-05-18 07:26:26'),
(122, 'SPI210518726884', 158, 49, 15, 'Cash', '100.00', 1, 1, NULL, '18-05-2021', '01:32:40 pm', 'May', '2021', '2021-05-17 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-05-18 07:32:40', '2021-05-18 07:32:40'),
(123, 'SPI210518381777', 159, 40, 15, 'Cash', '10.00', 1, 1, NULL, '18-05-2021', '03:28:34 pm', 'May', '2021', '2021-05-17 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-05-18 09:28:34', '2021-05-18 09:28:34'),
(124, 'SPI210518732761', 160, NULL, 15, 'Cash', '1200.00', 1, 1, NULL, '18-05-2021', '07:23:04 pm', 'May', '2021', '2021-05-17 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-05-18 13:23:04', '2021-05-18 13:23:04'),
(125, 'SPI210518887152', 161, NULL, 15, 'Cash', '1023.75', 1, 1, NULL, '18-05-2021', '07:30:41 pm', 'May', '2021', '2021-05-17 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-05-18 13:30:41', '2021-05-18 13:30:41'),
(131, 'SPI210519283423', 166, NULL, 15, 'Cash', '400.00', 1, 1, NULL, '19-05-2021', '11:02:33 am', 'May', '2021', '2021-05-18 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-05-19 05:02:33', '2021-05-19 05:02:33'),
(132, 'SPI2105193912', 167, 52, NULL, 'Cash', '1023.75', 1, 1, NULL, '2021-05-19', '04:08:09 pm', 'May', '2021', '2021-05-18 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-05-19 10:08:09', '2021-05-19 10:08:09'),
(133, 'SPI2105194453', 168, 54, NULL, 'Cash', '1023.75', 1, 1, NULL, '2021-05-19', '04:13:18 pm', 'May', '2021', '2021-05-18 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-05-19 10:13:18', '2021-05-19 10:13:18'),
(134, 'SPI2105192727', 169, 54, NULL, 'Cash', '115.50', 1, 1, NULL, '2021-05-19', '04:13:43 pm', 'May', '2021', '2021-05-18 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-05-19 10:13:43', '2021-05-19 10:13:43'),
(135, 'SPI2105195932', 170, 51, NULL, 'Cash', '1023.75', 1, 1, NULL, '2021-05-19', '04:15:19 pm', 'May', '2021', '2021-05-18 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-05-19 10:15:19', '2021-05-19 10:15:19'),
(136, 'SPI2105195794', 171, NULL, NULL, 'Cash', '125.00', 1, 1, NULL, '2021-05-19', '04:15:35 pm', 'May', '2021', '2021-05-18 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-05-19 10:15:35', '2021-05-19 10:15:35'),
(138, 'SPI210523392161', 173, NULL, 15, 'Card', '262.50', 1, 1, NULL, '23-05-2021', '06:21:53 pm', 'May', '2021', '2021-05-22 18:00:00', NULL, NULL, 'Credit-Card', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-05-23 12:21:53', '2021-05-23 12:21:53'),
(139, 'SPI2105243881', 174, 55, NULL, 'Cash', '131.25', 1, 1, NULL, '2021-05-24', '11:50:13 am', 'May', '2021', '2021-05-23 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 7, '2021-05-24 05:50:13', '2021-05-24 05:50:13'),
(140, 'SPI2105245994', 175, 55, 16, 'Cash', '269.06', 1, 1, NULL, '2021-05-24', '11:53:25 am', 'June', '2021', '2021-05-23 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 7, '2021-05-24 05:53:25', '2021-06-07 09:41:46'),
(142, 'SPI210602963899', 176, NULL, NULL, 'Cash', '1575.00', 1, 1, NULL, '02-06-2021', '02:57:23', 'June', '2021', '2021-06-01 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-06-02 08:57:23', '2021-06-02 08:57:23'),
(144, 'SPI21060399326', 176, 41, 15, 'Cash', '742.50', 1, 1, NULL, '03-06-2021', '01:32:38', 'June', '2021', '2021-06-02 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-06-03 07:32:38', '2021-06-03 07:32:38'),
(145, 'SPI210603946577', 191, 42, 15, 'Cash', '262.50', 1, 1, NULL, '03-06-2021', '01:51:43 pm', 'June', '2021', '2021-06-02 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-06-03 07:51:43', '2021-06-03 07:51:43'),
(146, 'SPI21060345951', 191, 42, 15, 'Cash', '886.25', 1, 1, NULL, '03-06-2021', '01:55:22', 'June', '2021', '2021-06-02 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-06-03 07:55:22', '2021-06-03 07:55:22'),
(147, 'SPI21060369627', 158, 49, 15, 'Cash', '162.50', 1, 1, NULL, '03-06-2021', '02:00:48', 'June', '2021', '2021-06-02 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-06-03 08:00:48', '2021-06-03 08:00:48'),
(148, 'SPI210603211588', 193, 44, 15, 'Cash', '262.50', 1, 1, NULL, '03-06-2021', '06:36:12 pm', 'June', '2021', '2021-06-02 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-06-03 12:36:12', '2021-06-03 12:36:12'),
(149, 'SPI21060321755', 193, 44, 15, 'Cash', '125.00', 1, 1, NULL, '03-06-2021', '06:49:39', 'June', '2021', '2021-06-02 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-06-03 12:49:39', '2021-06-03 12:49:39'),
(150, 'SPI210603232651', 194, NULL, 15, 'Cash', '262.50', 1, 1, NULL, '03-06-2021', '06:57:26 pm', 'June', '2021', '2021-06-02 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-06-03 12:57:26', '2021-06-03 12:57:26'),
(151, 'SPI21060395115', 194, NULL, 15, 'Cash', '125.00', 1, 1, NULL, '03-06-2021', '07:03:27', 'June', '2021', '2021-06-02 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-06-03 13:03:27', '2021-06-03 13:03:27'),
(152, 'SPI21060386138', 194, NULL, 15, 'Cash', '886.25', 1, 1, NULL, '03-06-2021', '07:10:38', 'June', '2021', '2021-06-02 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-06-03 13:10:38', '2021-06-03 13:10:38'),
(153, 'SPI210603481195', 195, 51, 15, 'Cash', '262.50', 1, 1, NULL, '03-06-2021', '07:11:16 pm', 'June', '2021', '2021-06-02 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-06-03 13:11:16', '2021-06-03 13:11:16'),
(154, 'SPI21060369877', 195, 51, 15, 'Cash', '761.25', 1, 1, NULL, '03-06-2021', '07:12:29', 'June', '2021', '2021-06-02 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-06-03 13:12:29', '2021-06-03 13:12:29'),
(155, 'SPI21060315152', 195, 51, 15, 'Cash', '107.50', 1, 1, NULL, '03-06-2021', '07:14:09', 'June', '2021', '2021-06-02 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-06-03 13:14:09', '2021-06-03 13:14:09'),
(156, 'SPI210603994881', 196, 54, 15, 'Cash', '262.50', 1, 1, NULL, '03-06-2021', '07:21:23 pm', 'June', '2021', '2021-06-02 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-06-03 13:21:23', '2021-06-03 13:21:23'),
(157, 'SPI21060393587', 196, 54, 15, 'Cash', '118.75', 1, 1, NULL, '03-06-2021', '07:22:00', 'June', '2021', '2021-06-02 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-06-03 13:22:00', '2021-06-03 13:22:00'),
(158, 'SPI210603661727', 197, NULL, 15, 'Cash', '250.00', 1, 1, NULL, '03-06-2021', '07:24:06 pm', 'June', '2021', '2021-06-02 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-06-03 13:24:06', '2021-06-03 13:24:06'),
(159, 'SPI210603171674', 198, 46, 15, 'Cash', '250.00', 1, 1, NULL, '03-06-2021', '07:25:26 pm', 'June', '2021', '2021-06-02 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-06-03 13:25:26', '2021-06-03 13:25:26'),
(160, 'SPI21060347922', 198, 46, 15, 'Cash', '6.25', 1, 1, NULL, '03-06-2021', '07:26:04', 'June', '2021', '2021-06-02 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-06-03 13:26:04', '2021-06-03 13:26:04'),
(161, 'SPI21060342544', 198, 46, 15, 'Cash', '6.25', 1, 1, NULL, '03-06-2021', '07:26:45', 'June', '2021', '2021-06-02 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-06-03 13:26:45', '2021-06-03 13:26:45'),
(162, 'SPI210603232319', 199, NULL, 15, 'Cash', '887.50', 1, 1, NULL, '03-06-2021', '07:28:35 pm', 'June', '2021', '2021-06-02 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-06-03 13:28:35', '2021-06-03 13:28:35'),
(163, 'SPI21060374939', 199, NULL, 15, 'Cash', '12.50', 1, 1, NULL, '03-06-2021', '07:29:07', 'June', '2021', '2021-06-02 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-06-03 13:29:07', '2021-06-03 13:29:07'),
(164, 'SPI21060393562', 199, NULL, 15, 'Cash', '25.00', 1, 1, NULL, '03-06-2021', '07:30:09', 'June', '2021', '2021-06-02 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-06-03 13:30:09', '2021-06-03 13:30:09'),
(165, 'SPI210603932962', 200, NULL, 15, 'Cash', '262.50', 1, 1, NULL, '03-06-2021', '07:31:57 pm', 'June', '2021', '2021-06-02 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-06-03 13:31:57', '2021-06-03 13:31:57'),
(166, 'SPI21060327928', 200, NULL, 15, 'Cash', '118.75', 1, 1, NULL, '03-06-2021', '07:33:52', 'June', '2021', '2021-06-02 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-06-03 13:33:52', '2021-06-03 13:33:52'),
(167, 'SPI210605464191', 201, NULL, 16, 'Cash', '262.50', 1, 1, NULL, '05-06-2021', '11:36:29 am', 'June', '2021', '2021-06-04 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-06-05 05:36:29', '2021-06-05 05:36:29'),
(168, 'SPI21060638863', 201, NULL, 15, 'Cash', '767.25', 1, 1, NULL, '06-06-2021', '11:15:40', 'June', '2021', '2021-06-05 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-06-06 05:15:40', '2021-06-06 05:15:40'),
(169, 'SPI2106068564', 202, NULL, NULL, 'Cash', '140.00', 1, 1, NULL, '2021-06-06', '12:28:17 pm', 'June', '2021', '2021-06-05 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-06-06 06:28:17', '2021-06-06 06:28:17'),
(170, 'SPI2106062677', 203, 55, NULL, 'Cash', '1023.75', 1, 1, NULL, '2021-06-06', '01:23:19 pm', 'June', '2021', '2021-06-05 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-06-06 07:23:19', '2021-06-06 07:23:19'),
(171, 'SPI2106065985', 204, NULL, NULL, 'Cash', '231.00', 1, 1, NULL, '2021-06-06', '01:35:32 pm', 'June', '2021', '2021-06-05 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-06-06 07:35:32', '2021-06-06 07:35:32'),
(172, 'SPI2106066489', 205, 46, NULL, 'Cash', '200.00', 1, 1, NULL, '2021-06-06', '02:29:28 pm', 'June', '2021', '2021-06-05 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-06-06 08:29:28', '2021-06-06 08:29:28'),
(173, 'SPI210607392582', 207, NULL, NULL, 'Cash', '510.00', 1, 1, NULL, '07-06-2021', '05:40:46 pm', 'June', '2021', '2021-06-06 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 7, '2021-06-07 11:40:46', '2021-06-07 11:40:46'),
(174, 'SPI210608633422', 209, NULL, 15, 'Cash', '300.00', 1, 1, NULL, '08-06-2021', '04:10:55 pm', 'June', '2021', '2021-06-07 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-06-08 10:10:55', '2021-06-08 10:10:55'),
(175, 'SPI210608344845', 210, NULL, 15, 'Cash', '140.00', 1, 1, NULL, '08-06-2021', '04:18:13 pm', 'June', '2021', '2021-06-07 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-06-08 10:18:13', '2021-06-08 10:18:13'),
(176, 'SPI210608542837', 211, NULL, 15, 'Cash', '131.25', 1, 1, NULL, '08-06-2021', '04:27:11 pm', 'June', '2021', '2021-06-07 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-06-08 10:27:11', '2021-06-08 10:27:11'),
(177, 'SPI210608617895', 212, NULL, 15, 'Cash', '140.00', 1, 1, NULL, '08-06-2021', '04:27:24 pm', 'June', '2021', '2021-06-07 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-06-08 10:27:24', '2021-06-08 10:27:24'),
(178, 'SPI210608286687', 213, NULL, 15, 'Cash', '140.00', 1, 1, NULL, '08-06-2021', '04:28:23 pm', 'June', '2021', '2021-06-07 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-06-08 10:28:23', '2021-06-08 10:28:23'),
(179, 'SPI210608776137', 214, NULL, 15, 'Cash', '300.00', 1, 1, NULL, '08-06-2021', '04:31:36 pm', 'June', '2021', '2021-06-07 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-06-08 10:31:36', '2021-06-08 10:31:36'),
(180, 'SPI210608118487', 215, NULL, 15, 'Cash', '300.00', 1, 1, NULL, '08-06-2021', '04:31:46 pm', 'June', '2021', '2021-06-07 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-06-08 10:31:46', '2021-06-08 10:31:46'),
(181, 'SPI210608615179', 216, NULL, 15, 'Cash', '300.00', 1, 1, NULL, '08-06-2021', '04:32:26 pm', 'June', '2021', '2021-06-07 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-06-08 10:32:26', '2021-06-08 10:32:26'),
(182, 'SPI210608156528', 217, NULL, 15, 'Cash', '300.00', 1, 1, NULL, '08-06-2021', '04:34:57 pm', 'June', '2021', '2021-06-07 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-06-08 10:34:57', '2021-06-08 10:34:57'),
(183, 'SPI210608892998', 218, NULL, 15, 'Cash', '300.00', 1, 1, NULL, '08-06-2021', '04:35:05 pm', 'June', '2021', '2021-06-07 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-06-08 10:35:05', '2021-06-08 10:35:05'),
(184, 'SPI210608773886', 219, NULL, 15, 'Cash', '300.00', 1, 1, NULL, '08-06-2021', '04:35:48 pm', 'June', '2021', '2021-06-07 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-06-08 10:35:48', '2021-06-08 10:35:48'),
(185, 'SPI210608876148', 220, NULL, 15, 'Cash', '140.00', 1, 1, NULL, '08-06-2021', '04:36:05 pm', 'June', '2021', '2021-06-07 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-06-08 10:36:05', '2021-06-08 10:36:05'),
(186, 'SPI210608815192', 221, NULL, 15, 'Cash', '140.00', 1, 1, NULL, '08-06-2021', '04:36:59 pm', 'June', '2021', '2021-06-07 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-06-08 10:36:59', '2021-06-08 10:36:59'),
(187, 'SPI210608281669', 222, NULL, 15, 'Cash', '140.00', 1, 1, NULL, '08-06-2021', '04:39:17 pm', 'June', '2021', '2021-06-07 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-06-08 10:39:17', '2021-06-08 10:39:17'),
(188, 'SPI210608418939', 223, NULL, 15, 'Cash', '140.00', 1, 1, NULL, '08-06-2021', '04:55:39 pm', 'June', '2021', '2021-06-07 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-06-08 10:55:39', '2021-06-08 10:55:39'),
(189, 'SPI2106086168', 226, 56, NULL, 'Cash', '262.50', 1, 1, NULL, '2021-06-08', '05:27:11 pm', 'June', '2021', '2021-06-07 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-06-08 11:27:11', '2021-06-08 11:27:11'),
(190, 'SPI2106085628', 227, 56, NULL, 'Cash', '262.50', 1, 1, NULL, '2021-06-08', '05:27:17 pm', 'June', '2021', '2021-06-07 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-06-08 11:27:17', '2021-06-08 11:27:17'),
(191, 'SPI2106088121', 228, 56, NULL, 'Cash', '262.50', 1, 1, NULL, '2021-06-08', '05:28:08 pm', 'June', '2021', '2021-06-07 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-06-08 11:28:08', '2021-06-08 11:28:08'),
(192, 'SPI2106083395', 229, 56, NULL, 'Cash', '262.50', 1, 1, NULL, '2021-06-08', '05:29:18 pm', 'June', '2021', '2021-06-07 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-06-08 11:29:18', '2021-06-08 11:29:18'),
(193, 'SPI2106087276', 230, 56, NULL, 'Cash', '262.50', 1, 1, NULL, '2021-06-08', '05:31:29 pm', 'June', '2021', '2021-06-07 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-06-08 11:31:29', '2021-06-08 11:31:29'),
(194, 'SPI2106085654', 231, 56, NULL, 'Cash', '262.50', 1, 1, NULL, '2021-06-08', '05:33:15 pm', 'June', '2021', '2021-06-07 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-06-08 11:33:15', '2021-06-08 11:33:15'),
(195, 'SPI2106089227', 232, 56, NULL, 'Cash', '262.50', 1, 1, NULL, '2021-06-08', '05:39:49 pm', 'June', '2021', '2021-06-07 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-06-08 11:39:49', '2021-06-08 11:39:49'),
(196, 'SPI2106087374', 233, 56, NULL, 'Cash', '262.50', 1, 1, NULL, '2021-06-08', '05:39:49 pm', 'June', '2021', '2021-06-07 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-06-08 11:39:49', '2021-06-08 11:39:49'),
(197, 'SPI2106086338', 234, 56, NULL, 'Cash', '262.50', 1, 1, NULL, '2021-06-08', '05:39:50 pm', 'June', '2021', '2021-06-07 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-06-08 11:39:50', '2021-06-08 11:39:50'),
(198, 'SPI2106085998', 235, 56, NULL, 'Cash', '262.50', 1, 1, NULL, '2021-06-08', '05:45:13 pm', 'June', '2021', '2021-06-07 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-06-08 11:45:13', '2021-06-08 11:45:13'),
(199, 'SPI2106084898', 236, 56, NULL, 'Cash', '262.50', 1, 1, NULL, '2021-06-08', '05:46:24 pm', 'June', '2021', '2021-06-07 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-06-08 11:46:24', '2021-06-08 11:46:24'),
(200, 'SPI2106081139', 237, NULL, NULL, 'Cash', '140.00', 1, 1, NULL, '2021-06-08', '05:47:35 pm', 'June', '2021', '2021-06-07 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-06-08 11:47:35', '2021-06-08 11:47:35'),
(201, 'SRPI08062181315', 237, NULL, 16, 'Cash', '131.25', 1, 2, NULL, '2021-06-08', '06:09:44 pm', 'June', '2021', '2021-06-07 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-06-08 12:09:44', '2021-06-08 12:09:44'),
(202, 'SPI21060829629', 225, 40, 15, 'Cash', '140.00', 1, 1, NULL, '08-06-2021', '07:07:02', 'June', '2021', '2021-06-07 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-06-08 13:07:02', '2021-06-08 13:07:02'),
(203, 'SPI21060897464', 224, 41, 15, 'Cash', '131.25', 1, 1, NULL, '08-06-2021', '07:10:33', 'June', '2021', '2021-06-07 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-06-08 13:10:33', '2021-06-08 13:10:33'),
(204, 'SPI21060813553', 223, NULL, 15, 'Cash', '109.25', 1, 1, NULL, '08-06-2021', '07:14:18', 'June', '2021', '2021-06-07 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-06-08 13:14:18', '2021-06-08 13:14:18'),
(205, 'SPI210608575675', 238, NULL, 15, 'Cash', '600.00', 1, 1, NULL, '08-06-2021', '07:18:17 pm', 'June', '2021', '2021-06-07 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-06-08 13:18:17', '2021-06-08 13:18:17'),
(206, 'SPI21060864478', 238, NULL, 15, 'Cash', '125.00', 1, 1, NULL, '08-06-2021', '07:19:25', 'June', '2021', '2021-06-07 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-06-08 13:19:25', '2021-06-08 13:19:25'),
(207, 'SPI2106092465', 239, NULL, NULL, 'Cash', '131.25', 1, 1, NULL, '2021-06-09', '01:16:33 pm', 'June', '2021', '2021-06-08 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-06-09 07:16:33', '2021-06-09 07:16:33'),
(208, 'SPI210609568893', 240, 54, 15, 'Cash', '1600.00', 1, 1, NULL, '09-06-2021', '02:56:54 pm', 'June', '2021', '2021-06-08 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-06-09 08:56:54', '2021-06-09 08:56:54'),
(209, 'SPI210609732774', 159, 40, 16, 'Cash', '498.75', 1, 1, NULL, '09-06-2021', '03:24:42 pm', 'June', '2021', '2021-06-08 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-06-09 09:24:42', '2021-06-09 09:24:42'),
(210, 'SPI210609148781', 205, 46, 16, 'Cheque', '31.00', 1, 1, NULL, '09-06-2021', '03:25:04 pm', 'June', '2021', '2021-06-08 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-06-09 09:25:04', '2021-06-09 09:25:04'),
(211, 'SPI210609148781', 208, 46, 16, 'Cheque', '300.00', 1, 1, NULL, '09-06-2021', '03:25:04 pm', 'June', '2021', '2021-06-08 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-06-09 09:25:04', '2021-06-09 09:25:04'),
(212, 'SPI210609597195', 49, 48, 16, 'Cash', '94250.00', 1, 1, NULL, '09-06-2021', '03:25:35 pm', 'June', '2021', '2021-06-08 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-06-09 09:25:35', '2021-06-09 09:25:35'),
(213, 'SPI210609894988', 158, 49, 16, 'Cash', '162.50', 1, 1, NULL, '09-06-2021', '03:27:00 pm', 'June', '2021', '2021-06-08 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-06-09 09:27:00', '2021-06-09 09:27:00'),
(215, 'SPI210609475382', 242, NULL, NULL, 'Cash', '300.00', 1, 1, NULL, '09-06-2021', '04:51:38 pm', 'June', '2021', '2021-06-08 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 10, '2021-06-09 10:51:38', '2021-06-09 10:51:38'),
(216, 'SPI21060938548', 242, NULL, NULL, 'Cash', '800.00', 1, 1, NULL, '09-06-2021', '04:53:25', 'June', '2021', '2021-06-08 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 10, '2021-06-09 10:53:25', '2021-06-09 10:53:25'),
(217, 'SPI210609839827', 243, NULL, NULL, 'Cash', '400.00', 1, 1, NULL, '09-06-2021', '04:53:57 pm', 'June', '2021', '2021-06-08 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 10, '2021-06-09 10:53:57', '2021-06-09 10:53:57'),
(218, 'SPI21060999663', 243, NULL, NULL, 'Cash', '20000.00', 1, 1, NULL, '09-06-2021', '04:54:40', 'June', '2021', '2021-06-08 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 10, '2021-06-09 10:54:40', '2021-06-09 10:54:40'),
(219, 'SPI210609461829', 244, NULL, NULL, 'Cash', '300.00', 1, 1, NULL, '09-06-2021', '05:04:20 pm', 'June', '2021', '2021-06-08 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 10, '2021-06-09 11:04:20', '2021-06-09 11:04:20'),
(220, 'SPI21060913954', 244, NULL, NULL, 'Cash', '500.00', 1, 1, NULL, '09-06-2021', '05:05:21', 'June', '2021', '2021-06-08 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 10, '2021-06-09 11:05:21', '2021-06-09 11:05:21'),
(221, 'SPI210609445698', 245, NULL, NULL, 'Cash', '300.00', 1, 1, NULL, '09-06-2021', '05:07:10 pm', 'June', '2021', '2021-06-08 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 10, '2021-06-09 11:07:10', '2021-06-09 11:07:10'),
(222, 'SPI21060936276', 245, NULL, NULL, 'Cash', '200.00', 1, 1, NULL, '09-06-2021', '05:07:47', 'June', '2021', '2021-06-08 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 10, '2021-06-09 11:07:47', '2021-06-09 11:07:47'),
(233, 'SPI2106101651', 254, 61, NULL, 'Cash', '131.25', 1, 1, NULL, '2021-06-10', '12:09:43 pm', 'June', '2021', '2021-06-09 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-06-10 06:09:43', '2021-06-10 06:09:43'),
(234, 'SPI2106101651', 253, 61, NULL, 'Cash', '131.25', 1, 1, NULL, '10-06-2021', '12:09:43 pm', 'June', '2021', '2021-06-09 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-06-10 06:09:43', '2021-06-10 06:09:43'),
(235, 'SPI21061018282', 206, 51, NULL, 'Cash', '518.75', 1, 1, NULL, '10-06-2021', '12:37:20 pm', 'June', '2021', '2021-06-09 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-06-10 06:37:20', '2021-06-10 06:37:20'),
(236, 'SPI21061078642', 195, 51, NULL, 'Cash', '12.50', 1, 1, NULL, '10-06-2021', '12:37:28 pm', 'June', '2021', '2021-06-09 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-06-10 06:37:28', '2021-06-10 06:37:28'),
(237, 'SPI21061065152', 176, 41, NULL, 'Cash', '742.50', 1, 1, NULL, '10-06-2021', '12:37:39 pm', 'June', '2021', '2021-06-09 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-06-10 06:37:39', '2021-06-10 06:37:39'),
(238, 'SPI21061045948', 157, 45, NULL, 'Cash', '325.00', 1, 1, NULL, '10-06-2021', '12:37:50 pm', 'June', '2021', '2021-06-09 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-06-10 06:37:50', '2021-06-10 06:37:50'),
(239, 'SPI2106104343', 255, NULL, NULL, 'Cash', '131.25', 1, 1, NULL, '2021-06-10', '05:09:36 pm', 'June', '2021', '2021-06-09 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-06-10 11:09:36', '2021-06-10 11:09:36');
INSERT INTO `sale_payments` (`id`, `invoice_id`, `sale_id`, `customer_id`, `account_id`, `pay_mode`, `paid_amount`, `payment_on`, `payment_type`, `payment_status`, `date`, `time`, `month`, `year`, `report_date`, `card_no`, `card_holder`, `card_type`, `card_type_id`, `card_transaction_no`, `card_month`, `card_year`, `card_secure_code`, `account_no`, `cheque_no`, `transaction_no`, `attachment`, `note`, `admin_id`, `created_at`, `updated_at`) VALUES
(240, 'SPI210610415536', 256, NULL, 15, 'Cash', '140.00', 1, 1, NULL, '10-06-2021', '05:10:04 pm', 'June', '2021', '2021-06-09 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-06-10 11:10:04', '2021-06-10 11:10:04'),
(241, 'SPI210610225392', 257, NULL, NULL, 'Cash', '140.00', 1, 1, NULL, '10-06-2021', '05:10:59 pm', 'June', '2021', '2021-06-09 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 7, '2021-06-10 11:10:59', '2021-06-10 11:10:59'),
(242, 'SPI2106109453', 258, NULL, NULL, 'Cash', '131.25', 1, 1, NULL, '2021-06-10', '05:12:10 pm', 'June', '2021', '2021-06-09 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 7, '2021-06-10 11:12:10', '2021-06-10 11:12:10'),
(243, 'SPI2106138424', 259, NULL, NULL, 'Cash', '131.25', 1, 1, NULL, '2021-06-13', '01:59:14 pm', 'June', '2021', '2021-06-12 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-06-13 07:59:14', '2021-06-13 07:59:14'),
(244, 'SPI2106134186', 260, NULL, NULL, 'Cash', '131.25', 1, 1, NULL, '2021-06-13', '02:03:31 pm', 'June', '2021', '2021-06-12 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-06-13 08:03:31', '2021-06-13 08:03:31'),
(245, 'SPI21061524611', 260, NULL, 16, 'Cash', '131.25', 1, 1, NULL, '15-06-2021', '12:18:20 pm', 'June', '2021', '2021-06-14 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-06-15 06:18:20', '2021-06-15 06:18:20'),
(246, 'SPI2106162828', 261, NULL, NULL, 'Cash', '131.25', 1, 1, NULL, '2021-06-16', '11:49:23 am', 'June', '2021', '2021-06-15 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-06-16 05:49:23', '2021-06-16 05:49:23'),
(247, 'SPI2106169284', 262, NULL, NULL, 'Cash', '256.25', 1, 1, NULL, '2021-06-16', '04:32:56 pm', 'June', '2021', '2021-06-15 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-06-16 10:32:56', '2021-06-16 10:32:56'),
(248, 'SPI2106177369', 263, NULL, NULL, 'Cash', '50400.00', 1, 1, NULL, '2021-06-17', '03:16:40 pm', 'June', '2021', '2021-06-16 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-06-17 09:16:40', '2021-06-17 09:16:40'),
(249, 'SPI2106177649', 264, 61, NULL, 'Cash', '50400.00', 1, 1, NULL, '2021-06-17', '04:39:58 pm', 'June', '2021', '2021-06-16 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-06-17 10:39:58', '2021-06-17 10:39:58'),
(250, 'SPI2106174497', 265, 61, NULL, 'Cash', '50400.00', 1, 1, NULL, '2021-06-17', '04:41:59 pm', 'June', '2021', '2021-06-16 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-06-17 10:41:59', '2021-06-17 10:41:59'),
(251, 'SPI210617154594', 266, 61, 15, 'Cash', '400.00', 1, 1, NULL, '17-06-2021', '04:44:49 pm', 'June', '2021', '2021-06-16 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-06-17 10:44:49', '2021-06-17 10:44:49');

-- --------------------------------------------------------

--
-- Table structure for table `sale_products`
--

CREATE TABLE `sale_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sale_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `product_variant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `quantity` decimal(22,2) NOT NULL DEFAULT 0.00,
  `unit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unit_discount_type` tinyint(4) NOT NULL DEFAULT 1,
  `unit_discount` decimal(22,2) NOT NULL DEFAULT 0.00,
  `unit_discount_amount` decimal(22,2) NOT NULL DEFAULT 0.00,
  `unit_tax_percent` decimal(22,2) NOT NULL DEFAULT 0.00,
  `unit_tax_amount` decimal(22,2) NOT NULL DEFAULT 0.00,
  `unit_cost_inc_tax` decimal(22,2) NOT NULL DEFAULT 0.00 COMMENT 'this_col_for_invoice_profit_report',
  `unit_price_exc_tax` decimal(22,2) NOT NULL DEFAULT 0.00,
  `unit_price_inc_tax` decimal(22,2) NOT NULL DEFAULT 0.00,
  `subtotal` decimal(22,2) NOT NULL DEFAULT 0.00,
  `description` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ex_quantity` decimal(22,2) NOT NULL DEFAULT 0.00,
  `ex_status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0=no_exchanged,1=prepare_to_exchange,2=exchanged',
  `delete_in_update` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sale_products`
--

INSERT INTO `sale_products` (`id`, `sale_id`, `product_id`, `product_variant_id`, `quantity`, `unit`, `unit_discount_type`, `unit_discount`, `unit_discount_amount`, `unit_tax_percent`, `unit_tax_amount`, `unit_cost_inc_tax`, `unit_price_exc_tax`, `unit_price_inc_tax`, `subtotal`, `description`, `ex_quantity`, `ex_status`, `delete_in_update`, `created_at`, `updated_at`) VALUES
(1, 21, 124, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '42.50', '735.00', '850.00', '892.50', '892.50', NULL, '0.00', 0, 0, '2021-04-12 11:51:01', '2021-04-12 11:51:01'),
(2, 21, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-04-12 11:51:01', '2021-04-12 11:51:01'),
(3, 21, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-04-12 11:51:02', '2021-04-12 11:51:02'),
(4, 22, 123, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '210.00', '150.00', '150.00', '150.00', NULL, '0.00', 0, 0, '2021-04-12 12:52:09', '2021-04-12 12:52:09'),
(5, 22, 114, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-04-12 12:52:09', '2021-04-12 12:52:09'),
(6, 23, 127, NULL, '20.00', 'Piece', 1, '0.00', '0.00', '5.00', '0.75', '12.60', '15.00', '15.75', '315.00', NULL, '0.00', 0, 0, '2021-04-13 07:01:10', '2021-04-13 07:01:10'),
(7, 24, 124, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '42.50', '735.00', '850.00', '892.50', '892.50', 'SL:5645757874', '0.00', 0, 0, '2021-04-17 03:47:27', '2021-04-17 03:47:27'),
(8, 24, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-04-17 03:47:27', '2021-04-17 03:47:27'),
(9, 25, 96, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-04-17 04:42:48', '2021-04-17 04:42:48'),
(11, 25, 129, NULL, '1.00', 'Kilogram', 1, '0.00', '0.00', '0.00', '0.00', '40.00', '45.00', '45.00', '45.00', NULL, '0.00', 0, 0, '2021-04-17 04:42:48', '2021-04-17 04:42:48'),
(13, 26, 96, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-04-17 07:22:32', '2021-05-04 05:47:48'),
(14, 26, 124, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '42.50', '735.00', '850.00', '892.50', '892.50', NULL, '0.00', 0, 0, '2021-04-17 07:22:32', '2021-05-04 05:47:48'),
(15, 26, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-04-17 07:22:32', '2021-05-04 05:47:48'),
(19, 29, 96, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-04-25 11:46:06', '2021-04-25 11:46:06'),
(20, 29, 114, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-04-25 11:46:06', '2021-04-25 11:46:06'),
(22, 29, 106, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-04-25 11:46:06', '2021-04-25 11:46:06'),
(23, 29, 124, NULL, '2.00', 'Piece', 1, '0.00', '0.00', '5.00', '42.50', '735.00', '850.00', '892.50', '1785.00', NULL, '0.00', 0, 0, '2021-04-25 11:46:06', '2021-04-25 11:46:06'),
(24, 29, 115, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '100.00', '125.00', '125.00', '125.00', NULL, '0.00', 0, 0, '2021-04-25 11:46:06', '2021-04-25 11:46:06'),
(25, 29, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-04-25 11:46:06', '2021-04-25 11:46:06'),
(26, 30, 96, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', '654451465454', '0.00', 0, 0, '2021-04-25 11:56:13', '2021-04-25 11:58:23'),
(27, 30, 124, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '42.50', '735.00', '850.00', '892.50', '892.50', '6546454', '0.00', 0, 0, '2021-04-25 11:56:13', '2021-04-25 11:58:23'),
(28, 30, 115, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '100.00', '125.00', '125.00', '125.00', '6547878787, sadfasdf657485', '0.00', 0, 0, '2021-04-25 11:56:13', '2021-04-25 11:58:23'),
(29, 30, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-04-25 11:56:13', '2021-04-25 11:58:23'),
(30, 30, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-04-25 11:56:13', '2021-04-25 11:58:23'),
(31, 31, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-04-26 07:40:51', '2021-04-26 07:40:51'),
(32, 31, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-04-26 07:40:51', '2021-04-26 07:40:51'),
(33, 32, 124, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '42.50', '735.00', '850.00', '892.50', '892.50', NULL, '0.00', 0, 0, '2021-04-26 07:43:34', '2021-04-26 07:43:34'),
(34, 32, 96, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-04-26 07:43:34', '2021-04-26 07:43:34'),
(35, 32, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-04-26 07:43:34', '2021-04-26 07:43:34'),
(36, 33, 124, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '42.50', '735.00', '850.00', '892.50', '892.50', NULL, '0.00', 0, 0, '2021-04-26 07:45:32', '2021-04-26 07:45:32'),
(37, 33, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-04-26 07:45:32', '2021-04-26 07:45:32'),
(38, 34, 124, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '42.50', '735.00', '850.00', '892.50', '892.50', NULL, '0.00', 0, 0, '2021-04-26 07:45:39', '2021-04-26 07:45:39'),
(39, 34, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-04-26 07:45:39', '2021-04-26 07:45:39'),
(40, 35, 124, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '42.50', '735.00', '850.00', '892.50', '892.50', NULL, '0.00', 0, 0, '2021-04-26 07:45:40', '2021-04-26 07:45:40'),
(41, 35, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-04-26 07:45:40', '2021-04-26 07:45:40'),
(42, 36, 124, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '42.50', '735.00', '850.00', '892.50', '892.50', NULL, '0.00', 0, 0, '2021-04-26 07:45:41', '2021-04-26 07:45:41'),
(43, 36, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-04-26 07:45:41', '2021-04-26 07:45:41'),
(44, 37, 124, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '42.50', '735.00', '850.00', '892.50', '892.50', NULL, '0.00', 0, 0, '2021-04-26 07:45:46', '2021-04-26 07:45:46'),
(45, 37, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-04-26 07:45:46', '2021-04-26 07:45:46'),
(46, 38, 124, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '42.50', '735.00', '850.00', '892.50', '892.50', NULL, '0.00', 0, 0, '2021-04-26 07:45:48', '2021-04-26 07:45:48'),
(47, 38, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-04-26 07:45:48', '2021-04-26 07:45:48'),
(48, 39, 124, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '42.50', '735.00', '850.00', '892.50', '892.50', NULL, '0.00', 0, 0, '2021-04-26 07:46:34', '2021-04-26 07:46:34'),
(49, 39, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-04-26 07:46:34', '2021-04-26 07:46:34'),
(50, 40, 124, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '42.50', '735.00', '850.00', '892.50', '892.50', '7888556696988, 78899996668, 78566665856, 755588755', '0.00', 0, 0, '2021-04-26 08:07:38', '2021-04-26 08:07:38'),
(51, 40, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-04-26 08:07:38', '2021-04-26 08:07:38'),
(52, 41, 124, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '42.50', '735.00', '850.00', '892.50', '892.50', '775555457877, 6878755878', '0.00', 0, 0, '2021-04-26 08:09:27', '2021-04-26 08:09:27'),
(53, 41, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-04-26 08:09:27', '2021-04-26 08:09:27'),
(54, 42, 124, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '42.50', '735.00', '850.00', '892.50', '892.50', NULL, '0.00', 0, 0, '2021-04-26 08:14:50', '2021-04-26 08:14:50'),
(55, 42, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-04-26 08:14:50', '2021-04-26 08:14:50'),
(56, 43, 124, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '42.50', '735.00', '850.00', '892.50', '892.50', '68778765485745, 5678789465657', '0.00', 0, 0, '2021-04-26 08:20:34', '2021-04-26 08:20:34'),
(57, 43, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-04-26 08:20:34', '2021-04-26 08:20:34'),
(58, 44, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-04-26 08:23:00', '2021-04-26 08:23:00'),
(59, 45, 124, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '42.50', '735.00', '850.00', '892.50', '892.50', NULL, '0.00', 0, 0, '2021-04-26 09:39:31', '2021-04-26 10:49:01'),
(60, 45, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-04-26 09:39:31', '2021-04-26 10:49:01'),
(61, 45, 96, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-04-26 10:47:53', '2021-04-26 10:49:01'),
(63, 46, 107, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-04-27 06:10:37', '2021-04-27 06:11:40'),
(64, 46, 131, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '5.50', '105.00', '110.00', '115.50', '115.50', 'SL:745558588', '0.00', 0, 0, '2021-04-27 06:10:37', '2021-04-27 06:11:40'),
(65, 46, 132, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '5.60', '105.00', '112.00', '117.60', '117.60', 'SL:77855558', '0.00', 0, 0, '2021-04-27 06:10:37', '2021-04-27 06:11:40'),
(66, 46, 96, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', 'SL:7855544', '0.00', 0, 0, '2021-04-27 06:10:37', '2021-04-27 06:11:41'),
(67, 46, 124, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '42.50', '735.00', '850.00', '892.50', '892.50', 'SL:75558855', '0.00', 0, 0, '2021-04-27 06:10:37', '2021-04-27 06:11:41'),
(68, 46, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-04-27 06:10:37', '2021-04-27 06:11:41'),
(71, 48, 96, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', 'SN: 5576-5345654, 57857-564564564, 6545785754, 57457857587-5645, 6578578575464556-544, 68787854-6545, 6578578558-47587, 5878877454', '0.00', 0, 0, '2021-04-28 05:44:22', '2021-04-28 05:44:22'),
(72, 48, 124, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '42.50', '735.00', '850.00', '892.50', '892.50', 'SN: 5576-5345654, 57857-564564564, 6545785754, 57457857587-5645, 6578578575464556-544, 68787854-6545, 6578578558-47587, 5878877454', '0.00', 0, 0, '2021-04-28 05:44:22', '2021-04-28 05:44:22'),
(73, 48, 106, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', 'SN: 5576-5345654, 57857-564564564, 6545785754, 57457857587-5645, 6578578575464556-544, 68787854-6545, 6578578558-47587, 5878877454', '0.00', 0, 0, '2021-04-28 05:44:22', '2021-04-28 05:44:22'),
(74, 48, 133, NULL, '1.00', 'Kilogram', 1, '0.00', '0.00', '5.00', '5.50', '105.00', '110.00', '115.50', '115.50', 'SN: 5576-5345654, 57857-564564564, 6545785754, 57457857587-5645, 6578578575464556-544, 68787854-6545, 6578578558-47587, 5878877454', '0.00', 0, 0, '2021-04-28 05:44:22', '2021-04-28 05:44:22'),
(76, 48, 130, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '7.50', '105.00', '150.00', '157.50', '157.50', 'SN: 658785-56467487, 68787467-564, 787787487-54, 8578778787, 5785454498-544, 65748797778', '0.00', 0, 0, '2021-04-28 05:44:22', '2021-04-28 05:44:22'),
(77, 49, 141, NULL, '2.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '13000.00', '18500.00', '18500.00', '37000.00', 'S/N: 12000719304259, 12000719304202', '0.00', 0, 0, '2021-04-28 08:53:06', '2021-05-11 03:53:30'),
(78, 49, 138, NULL, '2.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '1650.00', '2800.00', '2800.00', '5600.00', 'S/N: 46485070204, 46485070193', '0.00', 0, 0, '2021-04-28 08:53:06', '2021-05-11 03:53:30'),
(79, 49, 139, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '2850.00', '3900.00', '3900.00', '3900.00', 'S/N:142033502832', '0.00', 0, 0, '2021-04-28 08:53:06', '2021-05-11 03:53:30'),
(80, 49, 140, NULL, '2.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '18500.00', '22500.00', '22500.00', '45000.00', NULL, '0.00', 0, 0, '2021-04-28 08:53:06', '2021-05-11 03:53:30'),
(81, 49, 137, NULL, '2.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '130.00', '300.00', '300.00', '600.00', 'S/N: 704-07012188, 704-07012190', '0.00', 0, 0, '2021-04-28 08:53:06', '2021-05-11 03:53:30'),
(82, 49, 136, NULL, '2.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '570.00', '700.00', '700.00', '1400.00', 'S/N: 1604-271220846, 1604-271220844', '0.00', 0, 0, '2021-04-28 08:53:06', '2021-05-11 03:53:30'),
(83, 49, 135, NULL, '2.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '280.00', '375.00', '375.00', '750.00', 'S/N; 169-2712209078, 169-2712209076', '0.00', 0, 0, '2021-04-28 08:53:06', '2021-05-11 03:53:30'),
(84, 50, 96, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-02 10:44:58', '2021-05-02 10:44:58'),
(85, 50, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-02 10:44:58', '2021-05-02 10:44:58'),
(86, 51, 115, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '100.00', '125.00', '125.00', '125.00', '68787', '0.00', 0, 0, '2021-05-04 04:56:25', '2021-05-04 04:56:25'),
(87, 51, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-04 04:56:25', '2021-05-04 04:56:25'),
(88, 51, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-04 04:56:25', '2021-05-04 04:56:25'),
(89, 52, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-04 04:58:56', '2021-05-04 04:58:56'),
(90, 52, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-04 04:58:56', '2021-05-04 04:58:56'),
(91, 53, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-04 04:59:06', '2021-05-04 04:59:06'),
(92, 53, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-04 04:59:06', '2021-05-04 04:59:06'),
(93, 54, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-04 05:01:16', '2021-05-04 05:01:16'),
(94, 55, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-04 05:01:23', '2021-05-04 05:01:23'),
(95, 56, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-04 05:02:55', '2021-05-04 05:02:55'),
(96, 57, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-04 05:04:56', '2021-05-04 05:04:56'),
(97, 58, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-04 05:09:15', '2021-05-04 05:09:15'),
(98, 59, 124, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '42.50', '735.00', '850.00', '892.50', '892.50', NULL, '0.00', 0, 0, '2021-05-04 05:09:39', '2021-05-04 05:09:39'),
(99, 59, 115, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '100.00', '125.00', '125.00', '125.00', NULL, '0.00', 0, 0, '2021-05-04 05:09:39', '2021-05-04 05:09:39'),
(100, 60, 124, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '42.50', '735.00', '850.00', '892.50', '892.50', NULL, '0.00', 0, 0, '2021-05-04 05:10:20', '2021-05-04 05:10:20'),
(101, 60, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-04 05:10:20', '2021-05-04 05:10:20'),
(102, 61, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-04 05:27:41', '2021-05-04 05:27:41'),
(103, 62, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-04 05:27:47', '2021-05-04 05:27:47'),
(104, 63, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-04 05:30:16', '2021-05-04 05:30:16'),
(105, 64, 114, NULL, '2.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '125.00', '250.00', NULL, '0.00', 0, 0, '2021-05-05 08:48:38', '2021-05-05 08:48:38'),
(106, 64, 124, NULL, '48.00', 'Piece', 1, '0.00', '0.00', '5.00', '42.50', '735.00', '850.00', '850.00', '40800.00', NULL, '0.00', 0, 0, '2021-05-05 08:48:38', '2021-05-05 08:48:38'),
(107, 64, 119, NULL, '13.00', 'Piece', 1, '0.00', '0.00', '5.00', '550.00', '10500.00', '11000.00', '11000.00', '143000.00', NULL, '0.00', 0, 0, '2021-05-05 08:48:38', '2021-05-05 08:48:38'),
(108, 64, 106, NULL, '4.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '125.00', '500.00', NULL, '0.00', 0, 0, '2021-05-05 08:48:38', '2021-05-05 08:48:38'),
(109, 65, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 11:10:18', '2021-05-08 11:10:18'),
(110, 65, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 11:10:18', '2021-05-08 11:10:18'),
(111, 66, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 11:11:47', '2021-05-08 11:11:47'),
(112, 66, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 11:11:47', '2021-05-08 11:11:47'),
(113, 67, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 11:11:58', '2021-05-08 11:11:58'),
(114, 67, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 11:11:58', '2021-05-08 11:11:58'),
(115, 68, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 11:12:27', '2021-05-08 11:12:27'),
(116, 68, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 11:12:27', '2021-05-08 11:12:27'),
(117, 69, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 11:12:38', '2021-05-08 11:12:38'),
(118, 69, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 11:12:38', '2021-05-08 11:12:38'),
(119, 70, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 11:13:44', '2021-05-08 11:13:44'),
(120, 70, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 11:13:44', '2021-05-08 11:13:44'),
(121, 71, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 11:13:57', '2021-05-08 11:13:57'),
(122, 71, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 11:13:57', '2021-05-08 11:13:57'),
(123, 72, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 11:14:47', '2021-05-08 11:14:47'),
(124, 72, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 11:14:47', '2021-05-08 11:14:47'),
(125, 73, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 11:16:53', '2021-05-08 11:16:53'),
(126, 73, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 11:16:53', '2021-05-08 11:16:53'),
(127, 74, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 11:18:04', '2021-05-08 11:18:04'),
(128, 74, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 11:18:04', '2021-05-08 11:18:04'),
(129, 75, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 11:30:27', '2021-05-08 11:30:27'),
(130, 75, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 11:30:27', '2021-05-08 11:30:27'),
(131, 76, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 11:31:20', '2021-05-08 11:31:20'),
(132, 76, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 11:31:20', '2021-05-08 11:31:20'),
(133, 77, 119, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '550.00', '10500.00', '11000.00', '11550.00', '11550.00', NULL, '0.00', 0, 0, '2021-05-08 11:32:10', '2021-05-08 11:32:10'),
(134, 77, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 11:32:10', '2021-05-08 11:32:10'),
(135, 77, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 11:32:10', '2021-05-08 11:32:10'),
(136, 78, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 11:37:46', '2021-05-10 05:23:23'),
(137, 78, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 11:37:46', '2021-05-10 05:23:23'),
(144, 81, 124, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '42.50', '735.00', '850.00', '892.50', '892.50', NULL, '0.00', 0, 0, '2021-05-08 11:45:52', '2021-05-08 11:45:52'),
(145, 81, 96, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 11:45:52', '2021-05-08 11:45:52'),
(146, 81, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 11:45:52', '2021-05-08 11:45:52'),
(147, 81, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 11:45:52', '2021-05-08 11:45:52'),
(148, 82, 124, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '42.50', '735.00', '850.00', '892.50', '892.50', NULL, '0.00', 0, 0, '2021-05-08 12:44:59', '2021-05-08 12:44:59'),
(149, 82, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 12:44:59', '2021-05-08 12:44:59'),
(150, 83, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 12:45:49', '2021-05-08 12:45:49'),
(151, 83, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 12:45:49', '2021-05-08 12:45:49'),
(152, 84, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 12:48:44', '2021-05-08 12:48:44'),
(153, 84, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 12:48:44', '2021-05-08 12:48:44'),
(154, 85, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 12:50:50', '2021-05-08 12:50:50'),
(155, 85, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 12:50:50', '2021-05-08 12:50:50'),
(156, 86, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 12:50:58', '2021-05-08 12:50:58'),
(157, 86, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 12:50:58', '2021-05-08 12:50:58'),
(158, 87, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 12:53:18', '2021-05-08 12:53:18'),
(159, 87, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 12:53:18', '2021-05-08 12:53:18'),
(160, 88, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 12:53:33', '2021-05-08 12:53:33'),
(161, 88, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 12:53:33', '2021-05-08 12:53:33'),
(162, 89, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 12:54:44', '2021-05-08 12:54:44'),
(163, 89, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 12:54:44', '2021-05-08 12:54:44'),
(164, 90, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 12:54:46', '2021-05-08 12:54:46'),
(165, 90, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 12:54:46', '2021-05-08 12:54:46'),
(166, 91, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 12:57:45', '2021-05-08 12:57:45'),
(167, 91, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 12:57:45', '2021-05-08 12:57:45'),
(168, 92, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 12:58:02', '2021-05-08 12:58:02'),
(169, 92, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 12:58:02', '2021-05-08 12:58:02'),
(170, 93, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 12:58:16', '2021-05-08 12:58:16'),
(171, 93, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 12:58:16', '2021-05-08 12:58:16'),
(172, 94, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 12:58:34', '2021-05-08 12:58:34'),
(173, 94, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 12:58:34', '2021-05-08 12:58:34'),
(174, 95, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 12:58:39', '2021-05-08 12:58:39'),
(175, 95, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 12:58:39', '2021-05-08 12:58:39'),
(176, 96, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 12:58:53', '2021-05-08 12:58:53'),
(177, 96, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 12:58:53', '2021-05-08 12:58:53'),
(178, 97, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 12:59:05', '2021-05-08 12:59:05'),
(179, 97, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 12:59:05', '2021-05-08 12:59:05'),
(180, 98, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 12:59:15', '2021-05-08 12:59:15'),
(181, 98, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 12:59:15', '2021-05-08 12:59:15'),
(182, 99, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 13:04:43', '2021-05-08 13:04:43'),
(183, 99, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 13:04:43', '2021-05-08 13:04:43'),
(184, 100, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 13:07:54', '2021-05-08 13:07:54'),
(185, 100, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 13:07:54', '2021-05-08 13:07:54'),
(186, 101, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 13:10:35', '2021-05-08 13:10:35'),
(187, 101, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 13:10:35', '2021-05-08 13:10:35'),
(188, 102, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 13:11:24', '2021-05-08 13:11:24'),
(189, 102, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 13:11:24', '2021-05-08 13:11:24'),
(190, 103, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 13:12:46', '2021-05-08 13:12:46'),
(191, 103, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 13:12:46', '2021-05-08 13:12:46'),
(192, 104, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 13:14:02', '2021-05-08 13:14:02'),
(193, 104, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 13:14:02', '2021-05-08 13:14:02'),
(194, 105, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 13:14:35', '2021-05-08 13:14:35'),
(195, 105, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 13:14:35', '2021-05-08 13:14:35'),
(196, 106, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 13:16:33', '2021-05-08 13:16:33'),
(197, 106, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 13:16:33', '2021-05-08 13:16:33'),
(198, 107, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 13:18:18', '2021-05-08 13:18:18'),
(199, 108, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 13:18:34', '2021-05-08 13:18:34'),
(200, 108, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 13:18:34', '2021-05-08 13:18:34'),
(201, 109, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 13:19:13', '2021-05-08 13:19:13'),
(202, 109, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 13:19:13', '2021-05-08 13:19:13'),
(203, 110, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 13:19:29', '2021-05-08 13:19:29'),
(204, 110, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 13:19:29', '2021-05-08 13:19:29'),
(205, 111, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 13:20:01', '2021-05-08 13:20:01'),
(206, 111, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 13:20:01', '2021-05-08 13:20:01'),
(207, 112, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 13:20:24', '2021-05-08 13:20:24'),
(208, 112, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 13:20:24', '2021-05-08 13:20:24'),
(209, 113, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 13:20:58', '2021-05-08 13:20:58'),
(210, 113, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 13:20:58', '2021-05-08 13:20:58'),
(211, 114, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 13:21:07', '2021-05-08 13:21:07'),
(212, 114, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 13:21:07', '2021-05-08 13:21:07'),
(213, 115, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 13:21:23', '2021-05-08 13:21:23'),
(214, 116, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 13:22:02', '2021-05-08 13:22:02'),
(215, 116, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 13:22:02', '2021-05-08 13:22:02'),
(216, 117, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 13:22:16', '2021-05-08 13:22:16'),
(217, 117, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 13:22:16', '2021-05-08 13:22:16'),
(218, 118, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 13:44:55', '2021-05-08 13:44:55'),
(219, 118, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 13:44:55', '2021-05-08 13:44:55'),
(220, 119, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 13:45:24', '2021-05-08 13:45:24'),
(221, 119, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 13:45:24', '2021-05-08 13:45:24'),
(224, 121, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 14:46:07', '2021-05-08 14:46:07'),
(225, 121, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 14:46:07', '2021-05-08 14:46:07'),
(226, 122, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 14:48:49', '2021-05-08 14:48:49'),
(227, 122, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 14:48:49', '2021-05-08 14:48:49'),
(228, 123, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 14:50:54', '2021-05-08 14:50:54'),
(229, 123, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 14:50:54', '2021-05-08 14:50:54'),
(230, 124, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 14:59:03', '2021-05-09 15:14:33'),
(231, 124, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-08 14:59:03', '2021-05-09 15:14:33'),
(250, 132, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-09 11:49:47', '2021-05-09 11:49:47'),
(251, 132, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-09 11:49:47', '2021-05-09 11:49:47'),
(252, 133, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-09 12:32:55', '2021-05-09 12:32:55'),
(253, 133, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-09 12:32:55', '2021-05-09 12:32:55'),
(254, 134, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-09 12:51:45', '2021-05-10 10:02:10'),
(255, 134, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-09 12:51:45', '2021-05-10 10:02:10'),
(256, 135, 108, NULL, '2.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '262.50', 'S/L:657787487487,857875474', '0.00', 0, 0, '2021-05-09 13:00:03', '2021-05-09 13:00:03'),
(257, 135, 110, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-09 13:00:03', '2021-05-09 13:00:03'),
(258, 135, 107, NULL, '1.00', 'Piece', 1, '10.00', '10.00', '0.00', '0.00', '105.00', '125.00', '115.00', '115.00', NULL, '0.00', 0, 0, '2021-05-09 13:00:03', '2021-05-09 13:00:03'),
(259, 135, 116, NULL, '1.00', 'Piece', 1, '3000.00', '3000.00', '0.00', '0.00', '60000.00', '50000.00', '47000.00', '47000.00', 'S/L:657787487487,857875474', '0.00', 0, 0, '2021-05-09 13:00:03', '2021-05-09 13:00:03'),
(260, 135, 104, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '1000.00', '1250.00', '1250.00', '1250.00', NULL, '0.00', 0, 0, '2021-05-09 13:00:03', '2021-05-09 13:00:03'),
(261, 135, 98, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '100.00', '125.00', '125.00', '125.00', NULL, '0.00', 0, 0, '2021-05-09 13:00:03', '2021-05-09 13:00:03'),
(262, 135, 142, NULL, '2.00', 'Piece', 1, '0.00', '0.00', '5.00', '30.00', '525.00', '600.00', '630.00', '1260.00', 'S/L:657787487487,857875474', '0.00', 0, 0, '2021-05-09 13:00:03', '2021-05-09 13:00:03'),
(263, 135, 145, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '28.75', '525.00', '575.00', '603.75', '603.75', 'S/L:657787487487,857875474', '0.00', 0, 0, '2021-05-09 13:00:03', '2021-05-09 13:00:03'),
(264, 135, 146, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', '100.00', '100.00', 'SL:7555447778558,88787878, 87898564754, 78578578,78577,', '0.00', 0, 0, '2021-05-09 13:00:03', '2021-05-09 13:00:03'),
(265, 135, 143, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '10.00', '105.00', '200.00', '210.00', '210.00', NULL, '0.00', 0, 0, '2021-05-09 13:00:03', '2021-05-09 13:00:03'),
(266, 135, 109, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-09 13:00:03', '2021-05-09 13:00:03'),
(267, 135, 147, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '5.00', '105.00', '100.00', '105.00', '105.00', 'S/L:657787487487,857875474', '0.00', 0, 0, '2021-05-09 13:00:03', '2021-05-09 13:00:03'),
(268, 135, 103, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '1000.00', '1250.00', '1250.00', '1250.00', NULL, '0.00', 0, 0, '2021-05-09 13:00:03', '2021-05-09 13:00:03'),
(269, 135, 112, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-09 13:00:03', '2021-05-09 13:00:03'),
(270, 135, 113, NULL, '2.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '262.50', NULL, '0.00', 0, 0, '2021-05-09 13:00:03', '2021-05-09 13:00:03'),
(271, 136, 124, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '42.50', '735.00', '850.00', '892.50', '892.50', 'cfasdfdsaf, dfas', '0.00', 0, 0, '2021-05-09 13:02:56', '2021-05-09 13:02:56'),
(272, 137, 96, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', 'SL:657487874544', '0.00', 0, 0, '2021-05-09 13:04:10', '2021-05-09 13:04:10'),
(273, 138, 124, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '42.50', '735.00', '850.00', '892.50', '892.50', 'S/L:67745664, 678787454', '0.00', 0, 0, '2021-05-09 13:15:40', '2021-05-09 13:15:40'),
(274, 138, 96, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', 'S/L:67745664, 678787454', '0.00', 0, 0, '2021-05-09 13:15:40', '2021-05-09 13:15:40'),
(275, 139, 124, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '42.50', '735.00', '850.00', '892.50', '892.50', 'S/L:67745664, 678787454', '0.00', 0, 0, '2021-05-09 14:42:59', '2021-05-09 14:53:02'),
(276, 139, 96, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', 'S/L:67745664, 678787454', '0.00', 0, 0, '2021-05-09 14:42:59', '2021-05-09 14:53:02'),
(277, 140, 114, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-09 14:44:15', '2021-05-09 14:44:15'),
(278, 140, 119, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '550.00', '10500.00', '11000.00', '11550.00', '11550.00', NULL, '0.00', 0, 0, '2021-05-09 14:44:15', '2021-05-09 14:44:15'),
(279, 140, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-09 14:44:15', '2021-05-09 14:44:15'),
(280, 140, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-09 14:44:15', '2021-05-09 14:44:15'),
(281, 140, 124, NULL, '2.00', 'Piece', 1, '0.00', '0.00', '5.00', '42.50', '735.00', '850.00', '892.50', '1785.00', 'S/L:67745664, 678787454', '0.00', 0, 0, '2021-05-09 14:44:15', '2021-05-09 14:44:15'),
(282, 140, 96, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', 'S/L:67745664, 678787454', '0.00', 0, 0, '2021-05-09 14:44:15', '2021-05-09 14:44:15'),
(283, 141, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-09 14:54:15', '2021-05-09 15:11:58'),
(284, 141, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-09 14:54:15', '2021-05-09 15:11:58'),
(285, 142, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-10 04:16:46', '2021-05-10 10:21:02'),
(286, 142, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-10 04:16:46', '2021-05-10 10:21:02'),
(287, 143, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-10 04:49:56', '2021-05-10 10:19:37'),
(288, 143, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-10 04:49:56', '2021-05-10 10:19:37'),
(289, 144, 124, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '42.50', '735.00', '850.00', '892.50', '892.50', NULL, '0.00', 0, 0, '2021-05-10 05:15:42', '2021-05-10 05:15:42'),
(290, 144, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-10 05:15:42', '2021-05-10 05:15:42'),
(291, 144, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-10 05:15:42', '2021-05-10 05:15:42'),
(292, 145, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-10 13:06:22', '2021-05-10 13:06:22'),
(293, 145, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-10 13:06:22', '2021-05-10 13:06:22'),
(294, 146, 110, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-11 03:25:55', '2021-05-11 03:25:55'),
(295, 146, 108, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-11 03:25:55', '2021-05-11 03:25:55'),
(296, 146, 114, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-11 03:25:55', '2021-05-11 03:25:55'),
(297, 146, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-11 03:25:55', '2021-05-11 03:25:55'),
(298, 146, 96, NULL, '2.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '262.50', NULL, '0.00', 0, 0, '2021-05-11 03:25:55', '2021-05-11 03:25:55'),
(299, 146, 124, NULL, '2.00', 'Piece', 1, '0.00', '0.00', '5.00', '42.50', '735.00', '850.00', '892.50', '1785.00', NULL, '0.00', 0, 0, '2021-05-11 03:25:55', '2021-05-11 03:25:55'),
(300, 147, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-11 03:30:38', '2021-05-11 03:30:38'),
(301, 147, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-11 03:30:38', '2021-05-11 03:30:38'),
(302, 150, 149, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '42000.00', '46000.00', '46002.00', '46002.00', 'S/N:6877845-141444,', '0.00', 0, 0, '2021-05-11 04:07:10', '2021-05-11 04:10:13'),
(303, 150, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-11 04:07:10', '2021-05-11 04:10:13'),
(304, 151, 149, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '42000.00', '46000.00', '46000.00', '46000.00', NULL, '0.00', 0, 0, '2021-05-11 04:15:35', '2021-05-11 04:18:26'),
(305, 152, 149, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '42000.00', '46000.00', '46000.00', '46000.00', NULL, '0.00', 0, 0, '2021-05-11 04:18:53', '2021-05-11 04:19:34'),
(306, 153, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-11 08:42:07', '2021-05-11 08:42:07'),
(307, 153, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-11 08:42:07', '2021-05-11 08:42:07'),
(311, 156, 95, 41, '0.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '0.00', NULL, '0.00', 0, 0, '2021-05-18 07:23:29', '2021-06-03 12:33:20'),
(312, 156, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-18 07:23:29', '2021-06-03 12:33:20');
INSERT INTO `sale_products` (`id`, `sale_id`, `product_id`, `product_variant_id`, `quantity`, `unit`, `unit_discount_type`, `unit_discount`, `unit_discount_amount`, `unit_tax_percent`, `unit_tax_amount`, `unit_cost_inc_tax`, `unit_price_exc_tax`, `unit_price_inc_tax`, `subtotal`, `description`, `ex_quantity`, `ex_status`, `delete_in_update`, `created_at`, `updated_at`) VALUES
(313, 157, 95, 41, '2.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '262.50', NULL, '1.00', 2, 0, '2021-05-18 07:26:26', '2021-06-03 12:16:52'),
(314, 157, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-18 07:26:26', '2021-06-03 12:15:23'),
(315, 158, 95, 41, '0.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '0.00', NULL, '0.00', 0, 0, '2021-05-18 07:32:40', '2021-06-03 08:26:38'),
(316, 158, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '-1.00', 1, 0, '2021-05-18 07:32:40', '2021-06-03 08:26:38'),
(317, 159, 95, 41, '0.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '0.00', NULL, '-1.00', 2, 0, '2021-05-18 09:28:34', '2021-06-03 12:34:33'),
(318, 159, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-18 09:28:34', '2021-06-03 12:34:03'),
(319, 160, 124, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '42.50', '735.00', '850.00', '892.50', '892.50', NULL, '0.00', 0, 0, '2021-05-18 13:23:04', '2021-05-18 13:23:04'),
(320, 160, 96, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-18 13:23:04', '2021-05-18 13:23:04'),
(321, 160, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-18 13:23:04', '2021-05-18 13:23:04'),
(322, 161, 124, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '42.50', '735.00', '850.00', '892.50', '892.50', NULL, '0.00', 0, 0, '2021-05-18 13:30:41', '2021-05-18 13:30:41'),
(323, 161, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-18 13:30:41', '2021-05-18 13:30:41'),
(357, 166, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-19 05:02:32', '2021-06-03 07:48:12'),
(358, 166, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '-1.00', 1, 0, '2021-05-19 05:02:33', '2021-06-03 07:48:12'),
(359, 167, 124, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '42.50', '735.00', '850.00', '892.50', '892.50', NULL, '0.00', 0, 0, '2021-05-19 10:08:09', '2021-05-19 10:08:09'),
(360, 167, 96, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-19 10:08:09', '2021-05-19 10:08:09'),
(361, 168, 124, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '42.50', '735.00', '850.00', '892.50', '892.50', NULL, '0.00', 0, 0, '2021-05-19 10:13:18', '2021-05-19 10:13:18'),
(362, 168, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-19 10:13:18', '2021-05-19 10:13:18'),
(363, 169, 134, NULL, '1.00', 'Kilogram', 1, '0.00', '0.00', '5.00', '5.50', '105.00', '110.00', '115.50', '115.50', NULL, '0.00', 0, 0, '2021-05-19 10:13:43', '2021-05-19 10:13:43'),
(364, 170, 124, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '42.50', '735.00', '850.00', '892.50', '892.50', NULL, '0.00', 0, 0, '2021-05-19 10:15:19', '2021-05-19 10:15:19'),
(365, 170, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-19 10:15:19', '2021-05-19 10:15:19'),
(366, 171, 115, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '100.00', '125.00', '125.00', '125.00', NULL, '0.00', 0, 0, '2021-05-19 10:15:35', '2021-05-19 10:15:35'),
(367, 172, 96, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-22 05:17:06', '2021-05-22 05:17:06'),
(368, 172, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-22 05:17:06', '2021-05-22 05:17:06'),
(369, 172, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-22 05:17:06', '2021-05-22 05:17:06'),
(370, 173, 95, 40, '0.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '0.00', NULL, '0.00', 0, 0, '2021-05-23 12:21:53', '2021-06-03 12:28:40'),
(371, 173, 95, 41, '0.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '0.00', NULL, '-1.00', 2, 0, '2021-05-23 12:21:53', '2021-06-03 12:31:09'),
(372, 174, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-24 05:50:13', '2021-05-24 05:50:13'),
(373, 175, 115, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '100.00', '125.00', '125.00', '125.00', NULL, '0.00', 0, 0, '2021-05-24 05:53:25', '2021-05-24 05:53:25'),
(374, 175, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-05-24 05:53:25', '2021-05-24 05:53:25'),
(375, 176, 108, NULL, '5.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '656.25', 'S/N:1,2,3,4,5,6,7,8,9,10,11,12', '-1.00', 1, 0, '2021-06-01 13:24:46', '2021-06-03 11:40:24'),
(381, 180, 115, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '100.00', '125.00', '125.00', '125.00', NULL, '0.00', 0, 0, '2021-06-02 12:27:21', '2021-06-02 12:27:21'),
(382, 180, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-06-02 12:27:21', '2021-06-02 12:27:21'),
(386, 184, 115, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '100.00', '125.00', '125.00', '125.00', NULL, '0.00', 0, 0, '2021-06-02 12:34:22', '2021-06-02 12:34:22'),
(387, 184, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-06-02 12:34:22', '2021-06-02 12:34:22'),
(388, 185, 113, NULL, '58.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '7612.50', NULL, '0.00', 0, 0, '2021-06-02 12:35:27', '2021-06-02 12:35:27'),
(389, 186, 115, NULL, '3.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '100.00', '125.00', '125.00', '375.00', NULL, '0.00', 0, 0, '2021-06-02 12:37:47', '2021-06-02 12:37:47'),
(390, 186, 95, 40, '3.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '393.75', NULL, '0.00', 0, 0, '2021-06-02 12:37:47', '2021-06-02 12:37:47'),
(395, 189, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-06-03 05:16:40', '2021-06-03 05:16:40'),
(396, 189, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-06-03 05:16:40', '2021-06-03 05:16:40'),
(398, 176, 96, NULL, '3.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '0.00', '131.25', '131.25', NULL, '-1.00', 1, 0, '2021-06-03 07:15:30', '2021-06-03 11:40:24'),
(399, 176, 124, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '42.50', '735.00', '0.00', '892.50', '0.00', NULL, '0.00', 0, 0, '2021-06-03 07:19:24', '2021-06-03 11:40:24'),
(400, 176, 115, NULL, '3.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '100.00', '0.00', '125.00', '0.00', NULL, '0.00', 0, 0, '2021-06-03 07:19:24', '2021-06-03 11:40:24'),
(401, 173, 96, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '0.00', '131.25', '0.00', NULL, '0.00', 0, 0, '2021-06-03 07:20:09', '2021-06-03 12:28:40'),
(402, 176, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '0.00', '131.25', '0.00', NULL, '0.00', 0, 0, '2021-06-03 07:32:38', '2021-06-03 11:40:24'),
(403, 176, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '0.00', '131.25', '0.00', NULL, '0.00', 0, 0, '2021-06-03 07:32:38', '2021-06-03 11:40:24'),
(404, 191, 95, 41, '0.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '0.00', NULL, '0.00', 0, 0, '2021-06-03 07:51:43', '2021-06-03 12:03:43'),
(405, 191, 95, 40, '0.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '0.00', NULL, '0.00', 0, 0, '2021-06-03 07:51:43', '2021-06-03 12:03:43'),
(406, 191, 96, NULL, '2.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '0.00', '131.25', '131.25', NULL, '1.00', 2, 0, '2021-06-03 07:52:11', '2021-06-03 12:14:38'),
(407, 191, 124, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '42.50', '735.00', '0.00', '892.50', '0.00', NULL, '0.00', 0, 0, '2021-06-03 07:55:22', '2021-06-03 12:03:43'),
(408, 191, 115, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '100.00', '0.00', '125.00', '0.00', NULL, '0.00', 0, 0, '2021-06-03 07:55:22', '2021-06-03 12:03:43'),
(409, 158, 96, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '0.00', '131.25', '0.00', NULL, '0.00', 0, 0, '2021-06-03 08:00:48', '2021-06-03 08:26:38'),
(410, 192, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-06-03 11:09:35', '2021-06-03 11:09:35'),
(411, 192, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-06-03 11:09:35', '2021-06-03 11:09:35'),
(412, 173, 113, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '0.00', '131.25', '0.00', NULL, '0.00', 0, 0, '2021-06-03 12:31:09', '2021-06-03 12:31:09'),
(413, 156, 124, NULL, '0.00', 'Piece', 1, '0.00', '0.00', '5.00', '42.50', '735.00', '0.00', '892.50', '-892.50', NULL, '-1.00', 2, 0, '2021-06-03 12:32:17', '2021-06-03 12:33:28'),
(414, 159, 92, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '100.00', '0.00', '125.00', '0.00', NULL, '0.00', 0, 0, '2021-06-03 12:34:33', '2021-06-03 12:34:33'),
(415, 193, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-06-03 12:36:12', '2021-06-03 12:55:45'),
(416, 193, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-06-03 12:36:12', '2021-06-03 12:55:45'),
(417, 193, 92, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '100.00', '0.00', '125.00', '0.00', NULL, '-1.00', 1, 0, '2021-06-03 12:47:42', '2021-06-03 12:55:45'),
(418, 193, 96, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '0.00', '131.25', '0.00', NULL, '0.00', 0, 0, '2021-06-03 12:49:39', '2021-06-03 12:55:45'),
(419, 194, 95, 41, '0.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '0.00', NULL, '0.00', 0, 0, '2021-06-03 12:57:26', '2021-06-03 13:10:10'),
(420, 194, 95, 40, '0.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '0.00', NULL, '-1.00', 2, 0, '2021-06-03 12:57:26', '2021-06-03 13:10:38'),
(421, 194, 96, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '0.00', '131.25', '0.00', NULL, '0.00', 0, 0, '2021-06-03 13:03:27', '2021-06-03 13:10:10'),
(422, 194, 92, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '100.00', '0.00', '125.00', '0.00', NULL, '0.00', 0, 0, '2021-06-03 13:03:27', '2021-06-03 13:10:10'),
(423, 194, 124, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '42.50', '735.00', '0.00', '892.50', '0.00', NULL, '0.00', 0, 0, '2021-06-03 13:10:38', '2021-06-03 13:10:38'),
(424, 195, 95, 40, '0.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '0.00', NULL, '0.00', 0, 0, '2021-06-03 13:11:16', '2021-06-03 13:14:55'),
(425, 195, 95, 41, '0.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '0.00', NULL, '-1.00', 2, 0, '2021-06-03 13:11:16', '2021-06-03 13:15:18'),
(426, 195, 124, NULL, '0.00', 'Piece', 1, '0.00', '0.00', '5.00', '42.50', '735.00', '0.00', '892.50', '-892.50', NULL, '0.00', 0, 0, '2021-06-03 13:12:29', '2021-06-03 13:14:55'),
(427, 195, 115, NULL, '6.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '100.00', '0.00', '125.00', '-250.00', NULL, '-2.00', 2, 0, '2021-06-03 13:14:09', '2021-06-03 13:15:18'),
(428, 195, 114, NULL, '3.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '0.00', '131.25', '0.00', NULL, '0.00', 0, 0, '2021-06-03 13:15:18', '2021-06-03 13:15:18'),
(429, 196, 95, 40, '0.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '0.00', NULL, '0.00', 0, 0, '2021-06-03 13:21:23', '2021-06-03 13:22:36'),
(430, 196, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-06-03 13:21:23', '2021-06-03 13:22:36'),
(431, 196, 92, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '100.00', '0.00', '125.00', '-125.00', NULL, '-1.00', 2, 0, '2021-06-03 13:22:00', '2021-06-03 13:22:55'),
(432, 196, 115, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '100.00', '0.00', '125.00', '0.00', NULL, '0.00', 0, 0, '2021-06-03 13:22:55', '2021-06-03 13:22:55'),
(433, 197, 92, NULL, '3.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '100.00', '125.00', '125.00', '375.00', NULL, '1.00', 2, 0, '2021-06-03 13:24:06', '2021-06-03 13:24:32'),
(434, 198, 92, NULL, '0.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '100.00', '125.00', '125.00', '0.00', NULL, '0.00', 0, 0, '2021-06-03 13:25:26', '2021-06-03 13:27:46'),
(435, 198, 95, 41, '0.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '0.00', '131.25', '-131.25', NULL, '-1.00', 2, 0, '2021-06-03 13:26:04', '2021-06-05 05:48:40'),
(436, 198, 113, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '0.00', '131.25', '0.00', NULL, '0.00', 0, 0, '2021-06-03 13:26:45', '2021-06-03 13:27:46'),
(437, 199, 96, NULL, '4.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '525.00', NULL, '-1.00', 1, 0, '2021-06-03 13:28:35', '2021-06-05 05:13:39'),
(438, 199, 92, NULL, '5.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '100.00', '0.00', '125.00', '250.00', NULL, '0.00', 0, 0, '2021-06-03 13:29:07', '2021-06-05 05:13:39'),
(439, 200, 95, 40, '0.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '0.00', NULL, '-1.00', 2, 0, '2021-06-03 13:31:57', '2021-06-03 13:33:52'),
(440, 200, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-06-03 13:31:57', '2021-06-03 13:32:12'),
(441, 200, 92, NULL, '2.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '100.00', '0.00', '125.00', '250.00', NULL, '0.00', 0, 0, '2021-06-03 13:33:52', '2021-06-03 13:33:52'),
(442, 201, 95, 40, '0.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '0.00', NULL, '-1.00', 2, 0, '2021-06-05 05:36:29', '2021-06-06 05:15:40'),
(443, 201, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-06-05 05:36:29', '2021-06-05 05:36:29'),
(444, 198, 96, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '0.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-06-05 05:48:40', '2021-06-05 05:48:40'),
(445, 201, 124, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '42.50', '735.00', '0.00', '892.50', '892.50', NULL, '0.00', 0, 0, '2021-06-06 05:15:40', '2021-06-06 05:15:40'),
(446, 202, 96, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-06-06 06:28:17', '2021-06-06 06:28:17'),
(447, 203, 124, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '42.50', '735.00', '850.00', '892.50', '892.50', NULL, '0.00', 0, 0, '2021-06-06 07:23:19', '2021-06-06 07:23:19'),
(448, 203, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-06-06 07:23:19', '2021-06-06 07:23:19'),
(449, 204, 134, NULL, '1.00', 'Kilogram', 1, '0.00', '0.00', '5.00', '5.50', '105.00', '110.00', '115.50', '115.50', NULL, '0.00', 0, 0, '2021-06-06 07:35:32', '2021-06-06 07:35:32'),
(450, 204, 133, NULL, '1.00', 'Kilogram', 1, '0.00', '0.00', '5.00', '5.50', '105.00', '110.00', '115.50', '115.50', NULL, '0.00', 0, 0, '2021-06-06 07:35:32', '2021-06-06 07:35:32'),
(451, 205, 134, NULL, '1.00', 'Kilogram', 1, '0.00', '0.00', '5.00', '5.50', '105.00', '110.00', '115.50', '115.50', NULL, '0.00', 0, 0, '2021-06-06 08:29:28', '2021-06-06 08:29:28'),
(452, 205, 133, NULL, '1.00', 'Kilogram', 1, '0.00', '0.00', '5.00', '5.50', '105.00', '110.00', '115.50', '115.50', NULL, '0.00', 0, 0, '2021-06-06 08:29:28', '2021-06-06 08:29:28'),
(453, 206, 113, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-06-06 11:22:47', '2021-06-06 11:22:47'),
(454, 206, 92, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '100.00', '125.00', '125.00', '125.00', NULL, '0.00', 0, 0, '2021-06-06 11:22:47', '2021-06-06 11:22:47'),
(455, 206, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-06-06 11:22:47', '2021-06-06 11:22:47'),
(456, 206, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-06-06 11:22:47', '2021-06-06 11:22:47'),
(457, 207, 93, NULL, '1.00', 'Kilogram', 1, '0.00', '0.00', '50.00', '82.50', '150.00', '165.00', '247.50', '247.50', NULL, '0.00', 0, 0, '2021-06-07 11:40:46', '2021-06-07 11:40:46'),
(458, 207, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-06-07 11:40:46', '2021-06-07 11:40:46'),
(459, 207, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-06-07 11:40:46', '2021-06-07 11:40:46'),
(460, 208, 137, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '130.00', '300.00', '300.00', '300.00', NULL, '0.00', 0, 0, '2021-06-07 11:41:46', '2021-06-07 11:41:46'),
(461, 209, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-06-08 10:10:55', '2021-06-08 10:10:55'),
(462, 209, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-06-08 10:10:55', '2021-06-08 10:10:55'),
(463, 210, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-06-08 10:18:13', '2021-06-08 10:18:13'),
(464, 211, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-06-08 10:27:11', '2021-06-08 10:27:11'),
(465, 212, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-06-08 10:27:24', '2021-06-08 10:27:24'),
(466, 213, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-06-08 10:28:23', '2021-06-08 10:28:23'),
(467, 214, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-06-08 10:31:36', '2021-06-08 10:31:36'),
(468, 214, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-06-08 10:31:36', '2021-06-08 10:31:36'),
(469, 215, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-06-08 10:31:46', '2021-06-08 10:31:46'),
(470, 215, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-06-08 10:31:46', '2021-06-08 10:31:46'),
(471, 216, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-06-08 10:32:26', '2021-06-08 10:32:26'),
(472, 216, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-06-08 10:32:26', '2021-06-08 10:32:26'),
(473, 217, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-06-08 10:34:57', '2021-06-08 10:34:57'),
(474, 217, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-06-08 10:34:57', '2021-06-08 10:34:57'),
(475, 218, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-06-08 10:35:05', '2021-06-08 10:35:05'),
(476, 218, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-06-08 10:35:05', '2021-06-08 10:35:05'),
(477, 219, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-06-08 10:35:48', '2021-06-08 10:35:48'),
(478, 219, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-06-08 10:35:48', '2021-06-08 10:35:48'),
(479, 220, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-06-08 10:36:05', '2021-06-08 10:36:05'),
(480, 221, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-06-08 10:36:59', '2021-06-08 10:36:59'),
(481, 222, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-06-08 10:39:17', '2021-06-08 10:39:17'),
(482, 223, 95, 41, '0.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '0.00', NULL, '-1.00', 2, 0, '2021-06-08 10:55:39', '2021-06-08 13:14:18'),
(483, 224, 95, 41, '0.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '0.00', NULL, '-1.00', 2, 0, '2021-06-08 11:08:25', '2021-06-08 13:10:33'),
(484, 225, 95, 41, '0.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '0.00', NULL, '-1.00', 2, 0, '2021-06-08 11:09:27', '2021-06-08 13:07:02'),
(485, 226, 95, 41, '2.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '262.50', NULL, '0.00', 0, 0, '2021-06-08 11:27:11', '2021-06-08 11:27:11'),
(486, 227, 95, 41, '2.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '262.50', NULL, '0.00', 0, 0, '2021-06-08 11:27:17', '2021-06-08 11:27:17'),
(487, 228, 95, 41, '2.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '262.50', NULL, '0.00', 0, 0, '2021-06-08 11:28:08', '2021-06-08 11:28:08'),
(488, 229, 95, 41, '2.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '262.50', NULL, '0.00', 0, 0, '2021-06-08 11:29:18', '2021-06-08 11:29:18'),
(489, 230, 95, 41, '2.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '262.50', NULL, '0.00', 0, 0, '2021-06-08 11:31:29', '2021-06-08 11:31:29'),
(490, 231, 95, 41, '2.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '262.50', NULL, '0.00', 0, 0, '2021-06-08 11:33:15', '2021-06-08 11:33:15'),
(491, 232, 95, 41, '2.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '262.50', NULL, '0.00', 0, 0, '2021-06-08 11:39:49', '2021-06-08 11:39:49'),
(492, 233, 95, 41, '2.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '262.50', NULL, '0.00', 0, 0, '2021-06-08 11:39:49', '2021-06-08 11:39:49'),
(493, 234, 95, 41, '2.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '262.50', NULL, '0.00', 0, 0, '2021-06-08 11:39:50', '2021-06-08 11:39:50'),
(494, 235, 95, 41, '2.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '262.50', NULL, '0.00', 0, 0, '2021-06-08 11:45:13', '2021-06-08 11:45:13'),
(495, 236, 95, 41, '2.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '262.50', NULL, '0.00', 0, 0, '2021-06-08 11:46:24', '2021-06-08 11:46:24'),
(496, 237, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-06-08 11:47:35', '2021-06-08 11:47:35'),
(497, 225, 113, NULL, '2.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '0.00', '131.25', '262.50', NULL, '0.00', 0, 0, '2021-06-08 13:07:02', '2021-06-08 13:07:02'),
(498, 224, 96, NULL, '2.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '0.00', '131.25', '262.50', NULL, '0.00', 0, 0, '2021-06-08 13:10:33', '2021-06-08 13:10:33'),
(499, 223, 133, NULL, '1.00', 'Kilogram', 1, '0.00', '0.00', '5.00', '5.50', '105.00', '0.00', '115.50', '115.50', NULL, '0.00', 0, 0, '2021-06-08 13:14:18', '2021-06-08 13:14:18'),
(500, 223, 115, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '100.00', '0.00', '125.00', '125.00', NULL, '0.00', 0, 0, '2021-06-08 13:14:18', '2021-06-08 13:14:18'),
(501, 238, 115, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '100.00', '125.00', '125.00', '125.00', NULL, '0.00', 0, 0, '2021-06-08 13:18:17', '2021-06-08 13:19:25'),
(502, 238, 108, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-06-08 13:18:17', '2021-06-08 13:18:17'),
(503, 238, 96, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-06-08 13:18:17', '2021-06-08 13:18:17'),
(504, 238, 95, 41, '0.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '0.00', NULL, '-1.00', 2, 0, '2021-06-08 13:18:17', '2021-06-08 13:19:25'),
(505, 238, 107, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '0.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-06-08 13:19:25', '2021-06-08 13:19:25'),
(506, 239, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-06-09 07:16:33', '2021-06-09 07:16:33'),
(507, 240, 108, NULL, '6.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '787.50', NULL, '0.00', 0, 0, '2021-06-09 08:56:54', '2021-06-09 08:56:54'),
(508, 240, 96, NULL, '6.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '787.50', NULL, '0.00', 0, 0, '2021-06-09 08:56:54', '2021-06-09 08:56:54'),
(511, 242, 95, 40, '0.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '0.00', NULL, '-1.00', 2, 0, '2021-06-09 10:51:38', '2021-06-09 10:53:25'),
(512, 242, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-06-09 10:51:38', '2021-06-09 10:51:38'),
(513, 242, 124, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '42.50', '735.00', '0.00', '892.50', '892.50', NULL, '0.00', 0, 0, '2021-06-09 10:53:25', '2021-06-09 10:53:25'),
(514, 243, 93, NULL, '0.00', 'Kilogram', 1, '0.00', '0.00', '50.00', '82.50', '150.00', '165.00', '247.50', '0.00', NULL, '-1.00', 2, 0, '2021-06-09 10:53:57', '2021-06-09 10:54:40'),
(515, 243, 96, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-06-09 10:53:57', '2021-06-09 10:53:57'),
(516, 243, 141, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '13000.00', '0.00', '18500.00', '18500.00', NULL, '0.00', 0, 0, '2021-06-09 10:54:40', '2021-06-09 10:54:40'),
(517, 243, 94, NULL, '1.00', 'Kilogram', 1, '0.00', '0.00', '50.00', '82.50', '300.00', '0.00', '247.50', '247.50', NULL, '0.00', 0, 0, '2021-06-09 10:54:40', '2021-06-09 10:54:40'),
(518, 244, 95, 41, '0.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '0.00', NULL, '-1.00', 2, 0, '2021-06-09 11:04:19', '2021-06-09 11:05:21'),
(519, 244, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-06-09 11:04:20', '2021-06-09 11:04:20'),
(520, 244, 137, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '130.00', '0.00', '300.00', '300.00', NULL, '0.00', 0, 0, '2021-06-09 11:05:21', '2021-06-09 11:05:21'),
(521, 244, 96, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '0.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-06-09 11:05:21', '2021-06-09 11:05:21'),
(522, 245, 95, 41, '0.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '0.00', NULL, '-1.00', 2, 0, '2021-06-09 11:07:10', '2021-06-09 11:07:47'),
(523, 245, 95, 40, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '210.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-06-09 11:07:10', '2021-06-09 11:07:10'),
(524, 245, 93, NULL, '1.00', 'Kilogram', 1, '0.00', '0.00', '50.00', '82.50', '150.00', '0.00', '247.50', '247.50', NULL, '0.00', 0, 0, '2021-06-09 11:07:47', '2021-06-09 11:07:47'),
(532, 253, 96, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-06-10 06:06:46', '2021-06-10 06:06:46'),
(533, 254, 96, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-06-10 06:09:43', '2021-06-10 06:09:43'),
(534, 255, 96, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-06-10 11:09:36', '2021-06-10 11:09:36'),
(535, 256, 96, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-06-10 11:10:04', '2021-06-10 11:10:04'),
(536, 257, 96, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-06-10 11:10:59', '2021-06-10 11:10:59'),
(537, 258, 96, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-06-10 11:12:10', '2021-06-10 11:12:10'),
(538, 259, 96, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-06-13 07:59:14', '2021-06-13 07:59:14'),
(539, 260, 96, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-06-13 08:03:31', '2021-06-15 06:22:46'),
(540, 260, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-06-15 06:18:07', '2021-06-15 06:22:46'),
(541, 261, 96, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-06-16 05:49:23', '2021-06-16 05:49:23'),
(542, 262, 115, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '100.00', '125.00', '125.00', '125.00', NULL, '0.00', 0, 0, '2021-06-16 10:32:56', '2021-06-16 10:32:56'),
(543, 262, 95, 41, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '6.25', '105.00', '125.00', '131.25', '131.25', NULL, '0.00', 0, 0, '2021-06-16 10:32:56', '2021-06-16 10:32:56'),
(544, 263, 154, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '2400.00', '42000.00', '48000.00', '50400.00', '50400.00', 'S/L:75555224445', '0.00', 0, 0, '2021-06-17 09:16:40', '2021-06-17 09:16:40'),
(545, 264, 154, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '2400.00', '42000.00', '48000.00', '50400.00', '50400.00', NULL, '0.00', 0, 0, '2021-06-17 10:39:58', '2021-06-17 10:39:58'),
(546, 265, 154, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '2400.00', '42000.00', '48000.00', '50400.00', '50400.00', NULL, '0.00', 0, 0, '2021-06-17 10:41:59', '2021-06-17 10:41:59'),
(547, 266, 154, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '2400.00', '42000.00', '48000.00', '50400.00', '50400.00', NULL, '0.00', 0, 0, '2021-06-17 10:44:49', '2021-06-17 10:44:49');

-- --------------------------------------------------------

--
-- Table structure for table `sale_returns`
--

CREATE TABLE `sale_returns` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sale_id` bigint(20) UNSIGNED NOT NULL,
  `admin_id` bigint(20) UNSIGNED NOT NULL,
  `warehouse_id` bigint(20) UNSIGNED DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `return_discount_type` tinyint(4) NOT NULL DEFAULT 1,
  `return_discount` decimal(22,2) NOT NULL DEFAULT 0.00,
  `return_discount_amount` decimal(22,2) NOT NULL DEFAULT 0.00,
  `net_total_amount` decimal(22,2) NOT NULL DEFAULT 0.00,
  `total_return_amount` decimal(22,2) NOT NULL DEFAULT 0.00,
  `total_return_due` decimal(22,2) NOT NULL DEFAULT 0.00,
  `total_return_due_pay` decimal(22,2) NOT NULL DEFAULT 0.00,
  `date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `month` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `year` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `report_date` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sale_returns`
--

INSERT INTO `sale_returns` (`id`, `invoice_id`, `sale_id`, `admin_id`, `warehouse_id`, `branch_id`, `return_discount_type`, `return_discount`, `return_discount_amount`, `net_total_amount`, `total_return_amount`, `total_return_due`, `total_return_due_pay`, `date`, `month`, `year`, `report_date`, `created_at`, `updated_at`) VALUES
(3, 'SRI21060848629', 237, 2, 7, NULL, 1, '0.00', '0.00', '131.25', '131.25', '0.00', '131.25', '2021-06-08', 'June', '2021', '2021-06-07 18:00:00', '2021-06-08 12:09:16', '2021-06-08 12:09:44');

-- --------------------------------------------------------

--
-- Table structure for table `sale_return_products`
--

CREATE TABLE `sale_return_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sale_return_id` bigint(20) UNSIGNED NOT NULL,
  `sale_product_id` bigint(20) UNSIGNED NOT NULL,
  `return_qty` decimal(22,2) NOT NULL DEFAULT 0.00,
  `unit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `return_subtotal` decimal(22,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sale_return_products`
--

INSERT INTO `sale_return_products` (`id`, `sale_return_id`, `sale_product_id`, `return_qty`, `unit`, `return_subtotal`, `created_at`, `updated_at`) VALUES
(4, 3, 496, '1.00', 'Piece', '131.25', '2021-06-08 12:09:16', '2021-06-08 12:09:16');

-- --------------------------------------------------------

--
-- Table structure for table `stock_adjustments`
--

CREATE TABLE `stock_adjustments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `warehouse_id` bigint(20) UNSIGNED DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `invoice_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_item` bigint(20) NOT NULL DEFAULT 0,
  `total_qty` decimal(22,2) NOT NULL DEFAULT 0.00,
  `net_total_amount` decimal(22,2) NOT NULL DEFAULT 0.00,
  `recovered_amount` decimal(22,2) NOT NULL DEFAULT 0.00,
  `type` tinyint(4) NOT NULL DEFAULT 0,
  `date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `month` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `year` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `report_date_ts` timestamp NULL DEFAULT NULL,
  `admin_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stock_adjustments`
--

INSERT INTO `stock_adjustments` (`id`, `warehouse_id`, `branch_id`, `invoice_id`, `total_item`, `total_qty`, `net_total_amount`, `recovered_amount`, `type`, `date`, `month`, `year`, `reason`, `report_date_ts`, `admin_id`, `created_at`, `updated_at`) VALUES
(3, 7, NULL, 'SAI080421598161', 1, '0.00', '2100.00', '0.00', 1, '08-04-2021', 'April', '2021', NULL, '2021-04-07 18:00:00', 2, '2021-04-08 07:13:57', '2021-04-08 07:13:57'),
(4, 7, NULL, 'SAI080421917963', 1, '0.00', '2100.00', '0.00', 1, '08-04-2021', 'April', '2021', NULL, '2021-04-07 18:00:00', 2, '2021-04-08 07:14:04', '2021-04-08 07:14:04'),
(5, 7, NULL, 'SAI280421491394', 1, '0.00', '210.00', '0.00', 1, '2021-04-28', 'April', '2021', NULL, '2021-04-27 18:00:00', 2, '2021-04-28 12:10:39', '2021-04-28 12:10:39'),
(6, 7, NULL, 'SAI220521152254', 2, '0.00', '945.00', '0.00', 2, '2021-05-22', 'May', '2021', NULL, '2021-05-21 18:00:00', 2, '2021-05-22 07:46:43', '2021-05-22 07:46:43'),
(7, 7, NULL, 'SAI220521738948', 1, '0.00', '105.00', '50.00', 2, '2021-05-22', 'May', '2021', NULL, '2021-05-21 18:00:00', 2, '2021-05-22 09:15:07', '2021-05-22 09:15:07'),
(8, NULL, 24, 'SAI240521268149', 2, '0.00', '205.00', '0.00', 1, '2021-05-24', 'May', '2021', NULL, '2021-05-23 18:00:00', 7, '2021-05-24 04:39:55', '2021-05-24 04:39:55'),
(9, 7, NULL, 'SAI090621821594', 1, '0.00', '105.00', '20.00', 1, '2021-06-09', 'June', '2021', NULL, '2021-06-08 18:00:00', 2, '2021-06-09 08:50:14', '2021-06-09 08:50:14');

-- --------------------------------------------------------

--
-- Table structure for table `stock_adjustment_products`
--

CREATE TABLE `stock_adjustment_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `stock_adjustment_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `product_variant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `quantity` decimal(22,2) NOT NULL DEFAULT 0.00,
  `unit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unit_cost_inc_tax` decimal(22,2) NOT NULL DEFAULT 0.00,
  `subtotal` decimal(22,2) NOT NULL DEFAULT 0.00,
  `is_delete_in_update` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stock_adjustment_products`
--

INSERT INTO `stock_adjustment_products` (`id`, `stock_adjustment_id`, `product_id`, `product_variant_id`, `quantity`, `unit`, `unit_cost_inc_tax`, `subtotal`, `is_delete_in_update`, `created_at`, `updated_at`) VALUES
(27, 24, 95, 40, '1.00', 'Piece', '210.00', '210.00', 0, '2021-04-07 10:26:41', '2021-04-07 10:26:41'),
(28, 1, 106, NULL, '15.00', 'Piece', '105.00', '1575.00', 0, '2021-04-07 12:00:57', '2021-04-07 12:00:57'),
(29, 2, 108, NULL, '20.00', 'Piece', '105.00', '2100.00', 0, '2021-04-07 12:05:20', '2021-04-07 12:05:20'),
(30, 3, 95, 40, '10.00', 'Piece', '210.00', '2100.00', 0, '2021-04-08 07:13:57', '2021-04-08 07:13:57'),
(31, 4, 95, 40, '10.00', 'Piece', '210.00', '2100.00', 0, '2021-04-08 07:14:04', '2021-04-08 07:14:04'),
(32, 5, 95, 40, '1.00', 'Piece', '210.00', '210.00', 0, '2021-04-28 12:10:39', '2021-04-28 12:10:39'),
(33, 6, 124, NULL, '1.00', 'Piece', '735.00', '735.00', 0, '2021-05-22 07:46:43', '2021-05-22 07:46:43'),
(34, 6, 95, 40, '1.00', 'Piece', '210.00', '210.00', 0, '2021-05-22 07:46:43', '2021-05-22 07:46:43'),
(35, 7, 95, 41, '1.00', 'Piece', '105.00', '105.00', 0, '2021-05-22 09:15:07', '2021-05-22 09:15:07'),
(36, 8, 115, NULL, '1.00', 'Piece', '100.00', '100.00', 0, '2021-05-24 04:39:55', '2021-05-24 04:39:55'),
(37, 8, 96, NULL, '1.00', 'Piece', '105.00', '105.00', 0, '2021-05-24 04:39:55', '2021-05-24 04:39:55'),
(38, 9, 95, 41, '1.00', 'Piece', '105.00', '105.00', 0, '2021-06-09 08:50:14', '2021-06-09 08:50:14');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` tinyint(4) DEFAULT NULL,
  `contact_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `business_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alternative_phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alternate_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `landline` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_of_birth` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tax_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `opening_balance` decimal(22,2) NOT NULL DEFAULT 0.00,
  `pay_term` tinyint(4) DEFAULT NULL COMMENT '1=months,2=days',
  `pay_term_number` int(11) DEFAULT NULL,
  `address` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_address` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zip_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_purchase` decimal(22,2) NOT NULL DEFAULT 0.00,
  `total_paid` decimal(22,2) NOT NULL DEFAULT 0.00,
  `total_purchase_due` decimal(22,2) NOT NULL DEFAULT 0.00,
  `total_purchase_return_due` decimal(22,2) NOT NULL DEFAULT 0.00,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `prefix` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `type`, `contact_id`, `name`, `business_name`, `phone`, `alternative_phone`, `alternate_phone`, `landline`, `email`, `date_of_birth`, `tax_number`, `opening_balance`, `pay_term`, `pay_term_number`, `address`, `shipping_address`, `city`, `state`, `country`, `zip_code`, `total_purchase`, `total_paid`, `total_purchase_due`, `total_purchase_return_due`, `status`, `prefix`, `created_at`, `updated_at`) VALUES
(26, NULL, NULL, 'dd', NULL, 'ddd', 'ddd', NULL, 'ddd', NULL, NULL, NULL, '0.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2082780.50', '810420.00', '3365.00', '3263.75', 1, 'd932432', '2021-03-09 06:46:55', '2021-06-17 09:15:13'),
(27, 2, 'CO45555', 'Coleman', 'Chester Byrd', '+1 (456) 444-9615', '+1 (456) 444-9615', NULL, '+1 (456) 444-9615', 'lufejawy@mailinator.com', NULL, '201', '94.00', 1, 884, 'Quae sint nulla offi', 'Animi asperiores qu', 'Lorem ipsum repudian', 'Incidunt non eius v', 'Explicabo Qui id po', '50575', '17225.00', '16595.00', '5355.00', '1050.00', 1, 'E8545', '2021-03-16 10:15:28', '2021-06-22 08:28:03'),
(28, NULL, NULL, 'a', NULL, '411', '411', NULL, '411', NULL, NULL, NULL, '0.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', '0.00', 1, 'a34', '2021-03-24 04:39:27', '2021-04-21 05:49:10'),
(29, 1, NULL, 'M.Flug', NULL, '12255877', '12255877', NULL, '12255877', NULL, NULL, NULL, '0.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '4200000.00', '4200000.00', '0.00', '0.00', 1, NULL, '2021-04-24 07:07:46', '2021-06-17 09:16:01'),
(30, 1, 'CO123', 'General supplier', NULL, '123456789', '123456789', NULL, '123456789', NULL, NULL, NULL, '0.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '3377430.00', '3377430.00', '0.00', '0.00', 1, 'G476747', '2021-04-28 08:31:05', '2021-06-05 12:32:11'),
(31, NULL, '15455', 'Supplier-1', NULL, '100022454', '100022454', NULL, '100022454', NULL, NULL, NULL, '0.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '315.00', '210.00', '105.00', '105.00', 1, 'S228324', '2021-06-10 06:47:14', '2021-06-22 06:09:39'),
(32, NULL, 'SID37159', 'Ali', NULL, '858555444', '858555444', NULL, '858555444', NULL, NULL, NULL, '0.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', '0.00', 1, 'A37159', '2021-06-22 08:04:35', '2021-06-22 08:06:11');

-- --------------------------------------------------------

--
-- Table structure for table `supplier_ledgers`
--

CREATE TABLE `supplier_ledgers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `supplier_id` bigint(20) UNSIGNED DEFAULT NULL,
  `purchase_id` bigint(20) UNSIGNED DEFAULT NULL,
  `purchase_payment_id` bigint(20) UNSIGNED DEFAULT NULL,
  `row_type` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1=purchase;2=purchase_payment3=opening_balance',
  `amount` decimal(22,2) DEFAULT NULL COMMENT 'only_for_opening_balance',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `supplier_ledgers`
--

INSERT INTO `supplier_ledgers` (`id`, `supplier_id`, `purchase_id`, `purchase_payment_id`, `row_type`, `amount`, `created_at`, `updated_at`) VALUES
(1, 26, NULL, 103, 2, NULL, '2021-04-21 05:41:40', '2021-04-21 05:41:40'),
(2, 26, NULL, 104, 2, NULL, '2021-04-21 05:41:40', '2021-04-21 05:41:40'),
(4, 26, NULL, 106, 2, NULL, '2021-04-21 05:41:40', '2021-04-21 05:41:40'),
(5, 26, NULL, 107, 2, NULL, '2021-04-21 05:41:40', '2021-04-21 05:41:40'),
(6, 26, NULL, 108, 2, NULL, '2021-04-21 05:42:54', '2021-04-21 05:42:54'),
(7, 26, NULL, 109, 2, NULL, '2021-04-22 06:10:59', '2021-04-22 06:10:59'),
(9, 26, NULL, 111, 2, NULL, '2021-04-22 06:43:25', '2021-04-22 06:43:25'),
(10, 26, NULL, 112, 2, NULL, '2021-04-22 07:14:10', '2021-04-22 07:14:10'),
(11, 26, NULL, 113, 2, NULL, '2021-04-22 07:53:37', '2021-04-22 07:53:37'),
(12, 26, 114, NULL, 1, NULL, '2021-04-24 07:27:48', '2021-04-24 07:27:48'),
(13, 26, NULL, 114, 2, NULL, '2021-04-24 07:27:48', '2021-04-24 07:27:48'),
(14, 27, 115, NULL, 1, NULL, '2021-04-24 07:43:18', '2021-04-24 07:43:18'),
(15, 27, NULL, 115, 2, NULL, '2021-04-24 07:43:18', '2021-04-24 07:43:18'),
(16, 27, NULL, 116, 2, NULL, '2021-04-24 09:46:16', '2021-04-24 09:46:16'),
(17, 27, 116, NULL, 1, NULL, '2021-04-25 03:50:24', '2021-04-25 03:50:24'),
(18, 27, NULL, 117, 2, NULL, '2021-04-25 03:50:24', '2021-04-25 03:50:24'),
(19, 26, NULL, 118, 2, NULL, '2021-04-25 07:39:38', '2021-04-25 07:39:38'),
(20, 26, NULL, 119, 2, NULL, '2021-04-25 07:40:37', '2021-04-25 07:40:37'),
(21, 30, 119, NULL, 1, NULL, '2021-04-28 08:37:51', '2021-04-28 08:37:51'),
(22, 30, NULL, 120, 2, NULL, '2021-04-28 08:37:51', '2021-04-28 08:37:51'),
(23, 30, 120, NULL, 1, NULL, '2021-04-28 09:13:31', '2021-04-28 09:13:31'),
(24, 30, NULL, 121, 2, NULL, '2021-04-28 09:13:31', '2021-04-28 09:13:31'),
(25, 30, 121, NULL, 1, NULL, '2021-04-28 09:14:24', '2021-04-28 09:14:24'),
(26, 30, NULL, 122, 2, NULL, '2021-04-28 09:14:24', '2021-04-28 09:14:24'),
(27, 27, 122, NULL, 1, NULL, '2021-04-28 09:18:38', '2021-04-28 09:18:38'),
(28, 27, NULL, 123, 2, NULL, '2021-04-28 09:18:38', '2021-04-28 09:18:38'),
(29, 26, 123, NULL, 1, NULL, '2021-05-04 03:58:13', '2021-05-04 03:58:13'),
(30, 26, NULL, 124, 2, NULL, '2021-05-04 03:58:13', '2021-05-04 03:58:13'),
(31, 26, NULL, 125, 2, NULL, '2021-05-25 07:01:12', '2021-05-25 07:01:12'),
(32, 26, 124, NULL, 1, NULL, '2021-06-06 10:00:30', '2021-06-06 10:00:30'),
(33, 26, NULL, 126, 2, NULL, '2021-06-06 10:00:30', '2021-06-06 10:00:30'),
(34, 26, 125, NULL, 1, NULL, '2021-06-06 10:01:15', '2021-06-06 10:01:15'),
(35, 31, 126, NULL, 1, NULL, '2021-06-10 06:47:33', '2021-06-10 06:47:33'),
(36, 31, NULL, 127, 2, NULL, '2021-06-10 06:47:33', '2021-06-10 06:47:33'),
(37, 31, 127, NULL, 1, NULL, '2021-06-10 06:49:00', '2021-06-10 06:49:00'),
(38, 26, 128, NULL, 1, NULL, '2021-06-17 09:15:14', '2021-06-17 09:15:14'),
(39, 26, NULL, 128, 2, NULL, '2021-06-17 09:15:14', '2021-06-17 09:15:14'),
(40, 29, 129, NULL, 1, NULL, '2021-06-17 09:16:01', '2021-06-17 09:16:01'),
(41, 29, NULL, 129, 2, NULL, '2021-06-17 09:16:01', '2021-06-17 09:16:01');

-- --------------------------------------------------------

--
-- Table structure for table `supplier_products`
--

CREATE TABLE `supplier_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `supplier_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `product_variant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `label_qty` bigint(20) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `supplier_products`
--

INSERT INTO `supplier_products` (`id`, `supplier_id`, `product_id`, `product_variant_id`, `label_qty`, `created_at`, `updated_at`) VALUES
(59, 26, 95, 40, 912, '2021-03-09 08:44:52', '2021-04-24 08:10:36'),
(60, 26, 96, NULL, 131, '2021-03-28 09:54:02', '2021-06-06 10:01:15'),
(61, 26, 95, 41, 1, '2021-03-28 09:54:02', '2021-04-13 06:16:42'),
(62, 26, 93, NULL, 0, '2021-03-28 09:54:02', '2021-04-06 10:15:48'),
(63, 26, 94, NULL, 0, '2021-03-29 05:17:53', '2021-04-06 10:19:36'),
(64, 26, 123, NULL, 1, '2021-04-11 04:21:09', '2021-04-11 04:21:09'),
(65, 26, 124, NULL, 106, '2021-04-11 04:31:58', '2021-04-24 08:10:36'),
(66, 26, 125, NULL, 3, '2021-04-11 04:42:40', '2021-04-11 04:44:42'),
(67, 26, 115, NULL, 204, '2021-04-11 04:42:40', '2021-05-04 04:00:28'),
(68, 26, 126, NULL, 1, '2021-04-11 04:46:30', '2021-04-11 04:46:30'),
(69, 26, 105, NULL, 103, '2021-04-11 04:46:30', '2021-04-24 08:10:36'),
(70, 26, 114, NULL, 100, '2021-04-11 06:18:27', '2021-04-11 06:18:27'),
(71, 26, 106, NULL, 102, '2021-04-11 06:18:27', '2021-04-24 08:10:36'),
(72, 26, 119, NULL, 100, '2021-04-11 06:18:27', '2021-04-11 06:18:27'),
(73, 26, 110, NULL, 1, '2021-04-24 07:27:48', '2021-04-24 08:10:36'),
(74, 26, 107, NULL, 1, '2021-04-24 07:27:48', '2021-04-24 08:10:36'),
(75, 26, 99, 42, 1, '2021-04-24 07:27:48', '2021-04-24 08:10:36'),
(76, 26, 98, NULL, 1, '2021-04-24 07:27:48', '2021-04-24 08:10:36'),
(77, 27, 95, 40, 2, '2021-04-24 07:43:18', '2021-04-24 07:43:18'),
(78, 27, 95, 41, 2, '2021-04-24 07:43:18', '2021-04-24 07:43:18'),
(79, 27, 131, NULL, 1, '2021-04-25 03:50:24', '2021-04-25 03:50:24'),
(80, 30, 140, NULL, 106, '2021-04-28 08:35:26', '2021-04-28 09:13:31'),
(81, 30, 137, NULL, 36, '2021-04-28 08:35:26', '2021-04-28 08:37:50'),
(82, 30, 135, NULL, 18, '2021-04-28 08:35:26', '2021-04-28 08:37:50'),
(83, 30, 136, NULL, 18, '2021-04-28 08:35:26', '2021-04-28 08:37:50'),
(84, 30, 139, NULL, 3, '2021-04-28 08:35:26', '2021-04-28 08:37:50'),
(85, 30, 141, NULL, 106, '2021-04-28 08:35:26', '2021-04-28 09:14:24'),
(86, 30, 138, NULL, 6, '2021-04-28 08:35:26', '2021-04-28 08:37:50'),
(87, 27, 138, NULL, 10, '2021-04-28 09:18:38', '2021-04-28 09:18:38'),
(88, 31, 96, NULL, 3, '2021-06-10 06:47:33', '2021-06-10 06:49:00'),
(89, 26, 153, 48, 100, '2021-06-17 09:15:13', '2021-06-17 09:15:13'),
(90, 26, 153, 46, 100, '2021-06-17 09:15:13', '2021-06-17 09:15:13'),
(91, 26, 153, 43, 100, '2021-06-17 09:15:13', '2021-06-17 09:15:13'),
(92, 26, 153, 44, 100, '2021-06-17 09:15:13', '2021-06-17 09:15:13'),
(93, 29, 154, NULL, 100, '2021-06-17 09:16:01', '2021-06-17 09:16:01');

-- --------------------------------------------------------

--
-- Table structure for table `sv_devices`
--

CREATE TABLE `sv_devices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sv_devices`
--

INSERT INTO `sv_devices` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Mobile', 'Mobile servicing', '2021-04-15 04:30:14', '2021-04-15 04:31:46');

-- --------------------------------------------------------

--
-- Table structure for table `sv_device_models`
--

CREATE TABLE `sv_device_models` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `brand_id` bigint(20) UNSIGNED DEFAULT NULL,
  `device_id` bigint(20) UNSIGNED DEFAULT NULL,
  `model_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `checklist` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sv_device_models`
--

INSERT INTO `sv_device_models` (`id`, `brand_id`, `device_id`, `model_name`, `checklist`, `created_at`, `updated_at`) VALUES
(1, 6, 1, 'S10', 'Camera|Buttery|Display|Software', '2021-04-15 06:04:39', '2021-04-15 06:17:43');

-- --------------------------------------------------------

--
-- Table structure for table `sv_job_sheets`
--

CREATE TABLE `sv_job_sheets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `brand_id` bigint(20) UNSIGNED DEFAULT NULL,
  `device_id` bigint(20) UNSIGNED DEFAULT NULL,
  `model_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_completed` tinyint(1) NOT NULL DEFAULT 0,
  `service_type` tinyint(4) DEFAULT NULL,
  `address` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cost` decimal(22,2) NOT NULL DEFAULT 0.00,
  `checklist` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `serial_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `configuration` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Product Configuration',
  `Condition` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Condition Of The Product',
  `customer_report` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Problem Reported By The Customer',
  `technician_comment` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `delivery_date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `send_notification` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `time` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `month` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `year` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sv_job_sheets_parts`
--

CREATE TABLE `sv_job_sheets_parts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `job_sheet_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_variant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sv_status`
--

CREATE TABLE `sv_status` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `color` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#262b26',
  `sort_order` bigint(20) DEFAULT NULL,
  `is_completed` tinyint(1) NOT NULL DEFAULT 0,
  `sms_template` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mail_subject` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mail_body` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sv_status`
--

INSERT INTO `sv_status` (`id`, `name`, `color`, `sort_order`, `is_completed`, `sms_template`, `mail_subject`, `mail_body`, `created_at`, `updated_at`) VALUES
(1, 'Completed', '#40e25b', 0, 1, 'SMS Template', 'Eamil Subject', 'Eamil Body', '2021-04-14 09:44:33', '2021-04-15 04:08:21');

-- --------------------------------------------------------

--
-- Table structure for table `taxes`
--

CREATE TABLE `taxes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tax_percent` decimal(10,2) NOT NULL DEFAULT 0.00,
  `tax_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `taxes`
--

INSERT INTO `taxes` (`id`, `tax_percent`, `tax_name`, `created_at`, `updated_at`) VALUES
(1, '5.00', 'Tax@5%', '2020-11-02 05:21:19', '2020-11-02 05:24:07'),
(2, '10.00', 'Tax@10%', '2020-11-02 05:24:42', '2020-11-02 05:24:42'),
(3, '15.00', 'Tax@15%', '2020-11-02 05:24:55', '2020-11-02 05:29:34'),
(5, '50.00', 'Tax@50%', '2021-01-31 10:01:08', '2021-01-31 10:01:08');

-- --------------------------------------------------------

--
-- Table structure for table `timezones`
--

CREATE TABLE `timezones` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `timezones`
--

INSERT INTO `timezones` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Asia/Dhaka', NULL, NULL),
(2, 'Asia/Kalkata', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `transfer_stock_to_branches`
--

CREATE TABLE `transfer_stock_to_branches` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1=pending;2=partial;3=completed',
  `warehouse_id` bigint(20) UNSIGNED NOT NULL COMMENT 'form_warehouse',
  `branch_id` bigint(20) UNSIGNED NOT NULL COMMENT 'to_branch',
  `total_item` decimal(8,2) NOT NULL,
  `total_send_qty` decimal(22,2) NOT NULL DEFAULT 0.00,
  `total_received_qty` decimal(22,2) NOT NULL DEFAULT 0.00,
  `net_total_amount` decimal(22,2) NOT NULL DEFAULT 0.00,
  `shipping_charge` decimal(22,2) NOT NULL DEFAULT 0.00,
  `additional_note` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `receiver_note` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `month` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `year` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `report_date` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transfer_stock_to_branches`
--

INSERT INTO `transfer_stock_to_branches` (`id`, `invoice_id`, `status`, `warehouse_id`, `branch_id`, `total_item`, `total_send_qty`, `total_received_qty`, `net_total_amount`, `shipping_charge`, `additional_note`, `receiver_note`, `date`, `month`, `year`, `report_date`, `created_at`, `updated_at`) VALUES
(2, 'STI210609546967', 1, 7, 26, '1.00', '1.00', '0.00', '131.25', '0.00', NULL, NULL, '2021-06-09', 'June', '2021', '2021-06-08 18:00:00', '2021-06-09 12:33:49', '2021-06-09 12:33:49'),
(3, 'STI210609933756', 1, 7, 26, '1.00', '1.00', '0.00', '227500.00', '0.00', NULL, NULL, '2021-06-09', 'June', '2021', '2021-06-08 18:00:00', '2021-06-09 12:34:09', '2021-06-09 12:34:09');

-- --------------------------------------------------------

--
-- Table structure for table `transfer_stock_to_branch_products`
--

CREATE TABLE `transfer_stock_to_branch_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `transfer_stock_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `product_variant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `unit_price` decimal(22,2) NOT NULL,
  `quantity` decimal(22,2) NOT NULL,
  `received_qty` decimal(22,2) NOT NULL DEFAULT 0.00,
  `unit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subtotal` decimal(22,2) NOT NULL,
  `is_delete_in_update` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transfer_stock_to_branch_products`
--

INSERT INTO `transfer_stock_to_branch_products` (`id`, `transfer_stock_id`, `product_id`, `product_variant_id`, `unit_price`, `quantity`, `received_qty`, `unit`, `subtotal`, `is_delete_in_update`, `created_at`, `updated_at`) VALUES
(2, 2, 96, NULL, '131.25', '1.00', '0.00', 'Piece', '131.25', 0, '2021-06-09 12:33:49', '2021-06-09 12:33:49'),
(3, 3, 122, NULL, '227500.00', '1.00', '0.00', 'Piece', '227500.00', 0, '2021-06-09 12:34:09', '2021-06-09 12:34:09');

-- --------------------------------------------------------

--
-- Table structure for table `transfer_stock_to_warehouses`
--

CREATE TABLE `transfer_stock_to_warehouses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1=pending;2=partial;3=completed',
  `branch_id` bigint(20) UNSIGNED NOT NULL COMMENT 'form_branch',
  `warehouse_id` bigint(20) UNSIGNED NOT NULL COMMENT 'to_warehouse',
  `total_item` decimal(8,2) NOT NULL,
  `total_send_qty` decimal(22,2) NOT NULL DEFAULT 0.00,
  `total_received_qty` decimal(22,2) NOT NULL DEFAULT 0.00,
  `net_total_amount` decimal(22,2) NOT NULL DEFAULT 0.00,
  `shipping_charge` decimal(22,2) NOT NULL DEFAULT 0.00,
  `additional_note` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `receiver_note` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `month` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `year` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `admin_id` bigint(20) UNSIGNED DEFAULT NULL,
  `report_date` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transfer_stock_to_warehouse_products`
--

CREATE TABLE `transfer_stock_to_warehouse_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `transfer_stock_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `product_variant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `unit_price` decimal(22,2) NOT NULL,
  `quantity` decimal(22,2) NOT NULL,
  `received_qty` decimal(22,2) NOT NULL DEFAULT 0.00,
  `unit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subtotal` decimal(22,2) NOT NULL,
  `is_delete_in_update` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `units`
--

CREATE TABLE `units` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dimension` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `units`
--

INSERT INTO `units` (`id`, `name`, `code_name`, `dimension`, `created_at`, `updated_at`) VALUES
(3, 'Piece', 'PC', '1 Per piece', '2020-11-02 04:57:56', '2020-11-02 04:57:56'),
(4, 'Kilogram', 'KG', '100 Kilogram = 1 KG', '2020-11-03 00:41:16', '2020-11-03 00:41:16'),
(5, 'Dozon', 'DZ', '12 Pieces = 1 DZ', '2020-11-03 00:42:06', '2020-12-30 00:26:39'),
(7, 'Gram', 'GM', '1', '2020-12-30 03:13:06', '2020-12-30 03:13:18'),
(8, 'Ton', 'TN', NULL, '2021-01-19 04:27:58', '2021-01-19 04:27:58'),
(9, 'Pound', 'PND', NULL, '2021-01-19 04:29:11', '2021-01-19 04:29:11');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `warehouses`
--

CREATE TABLE `warehouses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `warehouse_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `warehouse_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `warehouses`
--

INSERT INTO `warehouses` (`id`, `warehouse_name`, `warehouse_code`, `phone`, `address`, `created_at`, `updated_at`) VALUES
(7, 'Motijheel', 'BLOCK/55', '+1 (653) 114-1069', 'Dhaka, Bangladesh.', '2021-03-09 08:44:03', '2021-04-04 07:22:36');

-- --------------------------------------------------------

--
-- Table structure for table `warranties`
--

CREATE TABLE `warranties` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `duration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `duration_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1=warranty;2=guaranty ',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `warranties`
--

INSERT INTO `warranties` (`id`, `name`, `duration`, `duration_type`, `description`, `type`, `created_at`, `updated_at`) VALUES
(2, 'Mobile Warranty', '1', 'Year', 'This warranty would be duration of 3 month.', 2, NULL, '2021-01-02 01:51:28'),
(3, 'Head phone Warranty', '6', 'Months', 'This warranty would be duration of 6 month.', 2, NULL, '2020-12-31 05:06:36'),
(4, 'Refrigerator Guaranty', '5', 'Year', 'This warranty would be duration of 5 Year.', 2, NULL, '2020-12-31 05:37:52'),
(6, 'Washing Machine', '2', 'Year', 'This warranty would be duration of 2 years.', 1, NULL, '2021-01-02 01:51:56'),
(7, 'General Warranty', '6', 'Months', 'This warranty would be a duration of 6 months.', 1, NULL, '2021-04-21 10:52:51'),
(8, '365 Days Warranty', '365', 'Days', NULL, 1, NULL, NULL),
(9, '1 Year Warranty', '1', 'Year', NULL, 1, NULL, NULL),
(10, '2 Year Warranty', '2', 'Year', NULL, 1, NULL, NULL),
(11, '3 Years Warranty', '3', 'Year', NULL, 1, NULL, NULL),
(12, 'w', '10', 'Months', 'fdsfs', 1, '2021-04-28 10:22:47', '2021-04-28 10:22:47'),
(13, 'wa', '10', 'Year', 'fedf', 1, '2021-04-28 10:23:37', '2021-04-28 10:23:37'),
(14, 'wa4', '3', 'Days', NULL, 1, '2021-04-28 10:24:38', '2021-04-28 10:24:38');

-- --------------------------------------------------------

--
-- Table structure for table `xyz`
--

CREATE TABLE `xyz` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `accounts_bank_id_foreign` (`bank_id`),
  ADD KEY `accounts_account_type_id_foreign` (`account_type_id`);

--
-- Indexes for table `account_types`
--
ALTER TABLE `account_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin_and_users`
--
ALTER TABLE `admin_and_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admin_and_users_email_unique` (`email`),
  ADD KEY `admin_and_users_role_id_foreign` (`role_id`),
  ADD KEY `admin_and_users_role_permission_id_foreign` (`role_permission_id`),
  ADD KEY `admin_and_users_branch_id_foreign` (`branch_id`),
  ADD KEY `admin_and_users_department_id_foreign` (`department_id`),
  ADD KEY `admin_and_users_designation_id_foreign` (`designation_id`),
  ADD KEY `admin_and_users_shift_id_foreign` (`shift_id`);

--
-- Indexes for table `allowance_employees`
--
ALTER TABLE `allowance_employees`
  ADD PRIMARY KEY (`id`),
  ADD KEY `allowance_employees_user_id_foreign` (`user_id`),
  ADD KEY `allowance_employees_allowance_id_foreign` (`allowance_id`);

--
-- Indexes for table `assets`
--
ALTER TABLE `assets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `assets_type_id_foreign` (`type_id`),
  ADD KEY `assets_branch_id_foreign` (`branch_id`);

--
-- Indexes for table `asset_types`
--
ALTER TABLE `asset_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `banks`
--
ALTER TABLE `banks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `barcode_settings`
--
ALTER TABLE `barcode_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `branches`
--
ALTER TABLE `branches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `branch_payment_methods`
--
ALTER TABLE `branch_payment_methods`
  ADD PRIMARY KEY (`id`),
  ADD KEY `branch_payment_methods_branch_id_foreign` (`branch_id`),
  ADD KEY `branch_payment_methods_account_id_foreign` (`account_id`);

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bulk_variants`
--
ALTER TABLE `bulk_variants`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bulk_variant_children`
--
ALTER TABLE `bulk_variant_children`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bulk_variant_children_bulk_variant_id_foreign` (`bulk_variant_id`);

--
-- Indexes for table `card_types`
--
ALTER TABLE `card_types`
  ADD PRIMARY KEY (`id`),
  ADD KEY `card_types_account_id_foreign` (`account_id`);

--
-- Indexes for table `cash_counters`
--
ALTER TABLE `cash_counters`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cash_flows`
--
ALTER TABLE `cash_flows`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cash_flows_account_id_foreign` (`account_id`),
  ADD KEY `cash_flows_sender_account_id_foreign` (`sender_account_id`),
  ADD KEY `cash_flows_receiver_account_id_foreign` (`receiver_account_id`),
  ADD KEY `cash_flows_purchase_payment_id_foreign` (`purchase_payment_id`),
  ADD KEY `cash_flows_sale_payment_id_foreign` (`sale_payment_id`),
  ADD KEY `cash_flows_expanse_payment_id_foreign` (`expanse_payment_id`),
  ADD KEY `cash_flows_money_receipt_id_foreign` (`money_receipt_id`),
  ADD KEY `cash_flows_payroll_id_foreign` (`payroll_id`),
  ADD KEY `cash_flows_payroll_payment_id_foreign` (`payroll_payment_id`);

--
-- Indexes for table `cash_registers`
--
ALTER TABLE `cash_registers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cash_registers_branch_id_foreign` (`branch_id`),
  ADD KEY `cash_registers_warehouse_id_foreign` (`warehouse_id`),
  ADD KEY `cash_registers_account_id_foreign` (`account_id`),
  ADD KEY `cash_registers_admin_id_foreign` (`admin_id`),
  ADD KEY `cash_registers_cash_counter_id_foreign` (`cash_counter_id`);

--
-- Indexes for table `cash_register_transactions`
--
ALTER TABLE `cash_register_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cash_register_transactions_cash_register_id_foreign` (`cash_register_id`),
  ADD KEY `cash_register_transactions_sale_id_foreign` (`sale_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categories_parent_category_id_foreign` (`parent_category_id`);

--
-- Indexes for table `combo_products`
--
ALTER TABLE `combo_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `combo_products_product_id_foreign` (`product_id`),
  ADD KEY `combo_products_combo_product_id_foreign` (`combo_product_id`),
  ADD KEY `combo_products_product_variant_id_foreign` (`product_variant_id`);

--
-- Indexes for table `currencies`
--
ALTER TABLE `currencies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customers_customer_group_id_foreign` (`customer_group_id`);

--
-- Indexes for table `customer_groups`
--
ALTER TABLE `customer_groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_ledgers`
--
ALTER TABLE `customer_ledgers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_ledgers_customer_id_foreign` (`customer_id`),
  ADD KEY `customer_ledgers_sale_id_foreign` (`sale_id`),
  ADD KEY `customer_ledgers_sale_payment_id_foreign` (`sale_payment_id`),
  ADD KEY `customer_ledgers_money_receipt_id_foreign` (`money_receipt_id`);

--
-- Indexes for table `expanses`
--
ALTER TABLE `expanses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `expanses_branch_id_foreign` (`branch_id`);

--
-- Indexes for table `expanse_categories`
--
ALTER TABLE `expanse_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expanse_payments`
--
ALTER TABLE `expanse_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `expanse_payments_expanse_id_foreign` (`expanse_id`),
  ADD KEY `expanse_payments_account_id_foreign` (`account_id`);

--
-- Indexes for table `expense_descriptions`
--
ALTER TABLE `expense_descriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `expense_descriptions_expense_category_id_foreign` (`expense_category_id`),
  ADD KEY `expense_descriptions_expense_id_foreign` (`expense_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `general_settings`
--
ALTER TABLE `general_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hrm_allowance`
--
ALTER TABLE `hrm_allowance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hrm_allowance_employee_id_foreign` (`employee_id`);

--
-- Indexes for table `hrm_attendances`
--
ALTER TABLE `hrm_attendances`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hrm_attendances_user_id_foreign` (`user_id`);

--
-- Indexes for table `hrm_department`
--
ALTER TABLE `hrm_department`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hrm_designations`
--
ALTER TABLE `hrm_designations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hrm_holidays`
--
ALTER TABLE `hrm_holidays`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hrm_holidays_branch_id_foreign` (`branch_id`);

--
-- Indexes for table `hrm_leaves`
--
ALTER TABLE `hrm_leaves`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hrm_leaves_employee_id_foreign` (`employee_id`),
  ADD KEY `hrm_leaves_leave_id_foreign` (`leave_id`);

--
-- Indexes for table `hrm_leavetypes`
--
ALTER TABLE `hrm_leavetypes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hrm_payrolls`
--
ALTER TABLE `hrm_payrolls`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hrm_payrolls_user_id_foreign` (`user_id`),
  ADD KEY `hrm_payrolls_admin_id_foreign` (`admin_id`);

--
-- Indexes for table `hrm_payroll_allowances`
--
ALTER TABLE `hrm_payroll_allowances`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hrm_payroll_allowances_payroll_id_foreign` (`payroll_id`);

--
-- Indexes for table `hrm_payroll_deductions`
--
ALTER TABLE `hrm_payroll_deductions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hrm_payroll_deductions_payroll_id_foreign` (`payroll_id`);

--
-- Indexes for table `hrm_payroll_payments`
--
ALTER TABLE `hrm_payroll_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hrm_payroll_payments_payroll_id_foreign` (`payroll_id`),
  ADD KEY `hrm_payroll_payments_account_id_foreign` (`account_id`),
  ADD KEY `hrm_payroll_payments_admin_id_foreign` (`admin_id`);

--
-- Indexes for table `hrm_shifts`
--
ALTER TABLE `hrm_shifts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoice_layouts`
--
ALTER TABLE `invoice_layouts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoice_schemas`
--
ALTER TABLE `invoice_schemas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `money_receipts`
--
ALTER TABLE `money_receipts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `money_receipts_customer_id_foreign` (`customer_id`),
  ADD KEY `money_receipts_branch_id_foreign` (`branch_id`);

--
-- Indexes for table `months`
--
ALTER TABLE `months`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `payment_methods`
--
ALTER TABLE `payment_methods`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `price_groups`
--
ALTER TABLE `price_groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `products_category_id_foreign` (`category_id`),
  ADD KEY `products_parent_category_id_foreign` (`parent_category_id`),
  ADD KEY `products_brand_id_foreign` (`brand_id`),
  ADD KEY `products_unit_id_foreign` (`unit_id`),
  ADD KEY `products_tax_id_foreign` (`tax_id`),
  ADD KEY `products_warranty_id_foreign` (`warranty_id`);

--
-- Indexes for table `product_branches`
--
ALTER TABLE `product_branches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_branches_product_id_foreign` (`product_id`),
  ADD KEY `product_branches_branch_id_foreign` (`branch_id`);

--
-- Indexes for table `product_branch_variants`
--
ALTER TABLE `product_branch_variants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_branch_variants_product_branch_id_foreign` (`product_branch_id`),
  ADD KEY `product_branch_variants_product_id_foreign` (`product_id`),
  ADD KEY `product_branch_variants_product_variant_id_foreign` (`product_variant_id`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_images_product_id_foreign` (`product_id`);

--
-- Indexes for table `product_opening_stocks`
--
ALTER TABLE `product_opening_stocks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_opening_stocks_branch_id_foreign` (`branch_id`),
  ADD KEY `product_opening_stocks_warehouse_id_foreign` (`warehouse_id`),
  ADD KEY `product_opening_stocks_product_id_foreign` (`product_id`),
  ADD KEY `product_opening_stocks_product_variant_id_foreign` (`product_variant_id`);

--
-- Indexes for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_variants_product_id_foreign` (`product_id`);

--
-- Indexes for table `product_warehouses`
--
ALTER TABLE `product_warehouses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_warehouses_warehouse_id_foreign` (`warehouse_id`),
  ADD KEY `product_warehouses_product_id_foreign` (`product_id`);

--
-- Indexes for table `product_warehouse_variants`
--
ALTER TABLE `product_warehouse_variants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_warehouse_variants_product_warehouse_id_foreign` (`product_warehouse_id`),
  ADD KEY `product_warehouse_variants_product_id_foreign` (`product_id`),
  ADD KEY `product_warehouse_variants_product_variant_id_foreign` (`product_variant_id`);

--
-- Indexes for table `purchases`
--
ALTER TABLE `purchases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchases_warehouse_id_foreign` (`warehouse_id`),
  ADD KEY `purchases_branch_id_foreign` (`branch_id`),
  ADD KEY `purchases_supplier_id_foreign` (`supplier_id`);

--
-- Indexes for table `purchase_payments`
--
ALTER TABLE `purchase_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_payments_purchase_id_foreign` (`purchase_id`),
  ADD KEY `purchase_payments_supplier_id_foreign` (`supplier_id`),
  ADD KEY `purchase_payments_account_id_foreign` (`account_id`);

--
-- Indexes for table `purchase_products`
--
ALTER TABLE `purchase_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_products_purchase_id_foreign` (`purchase_id`),
  ADD KEY `purchase_products_product_id_foreign` (`product_id`),
  ADD KEY `purchase_products_product_variant_id_foreign` (`product_variant_id`);

--
-- Indexes for table `purchase_returns`
--
ALTER TABLE `purchase_returns`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_returns_purchase_id_foreign` (`purchase_id`),
  ADD KEY `purchase_returns_warehouse_id_foreign` (`warehouse_id`),
  ADD KEY `purchase_returns_branch_id_foreign` (`branch_id`),
  ADD KEY `purchase_returns_supplier_id_foreign` (`supplier_id`);

--
-- Indexes for table `purchase_return_products`
--
ALTER TABLE `purchase_return_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_return_products_purchase_return_id_foreign` (`purchase_return_id`),
  ADD KEY `purchase_return_products_purchase_product_id_foreign` (`purchase_product_id`),
  ADD KEY `purchase_return_products_product_id_foreign` (`product_id`),
  ADD KEY `purchase_return_products_product_variant_id_foreign` (`product_variant_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sales_branch_id_foreign` (`branch_id`),
  ADD KEY `sales_warehouse_id_foreign` (`warehouse_id`),
  ADD KEY `sales_customer_id_foreign` (`customer_id`);

--
-- Indexes for table `sale_payments`
--
ALTER TABLE `sale_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sale_payments_sale_id_foreign` (`sale_id`),
  ADD KEY `sale_payments_customer_id_foreign` (`customer_id`),
  ADD KEY `sale_payments_account_id_foreign` (`account_id`),
  ADD KEY `sale_payments_card_type_id_foreign` (`card_type_id`);

--
-- Indexes for table `sale_products`
--
ALTER TABLE `sale_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sale_products_sale_id_foreign` (`sale_id`),
  ADD KEY `sale_products_product_id_foreign` (`product_id`),
  ADD KEY `sale_products_product_variant_id_foreign` (`product_variant_id`);

--
-- Indexes for table `sale_returns`
--
ALTER TABLE `sale_returns`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sale_returns_sale_id_foreign` (`sale_id`),
  ADD KEY `sale_returns_warehouse_id_foreign` (`warehouse_id`),
  ADD KEY `sale_returns_branch_id_foreign` (`branch_id`);

--
-- Indexes for table `sale_return_products`
--
ALTER TABLE `sale_return_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sale_return_products_sale_return_id_foreign` (`sale_return_id`),
  ADD KEY `sale_return_products_sale_product_id_foreign` (`sale_product_id`);

--
-- Indexes for table `stock_adjustments`
--
ALTER TABLE `stock_adjustments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_adjustments_warehouse_id_foreign` (`warehouse_id`),
  ADD KEY `stock_adjustments_branch_id_foreign` (`branch_id`),
  ADD KEY `stock_adjustments_admin_id_foreign` (`admin_id`);

--
-- Indexes for table `stock_adjustment_products`
--
ALTER TABLE `stock_adjustment_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_adjustment_products_stock_adjustment_id_foreign` (`stock_adjustment_id`),
  ADD KEY `stock_adjustment_products_product_id_foreign` (`product_id`),
  ADD KEY `stock_adjustment_products_product_variant_id_foreign` (`product_variant_id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `supplier_ledgers`
--
ALTER TABLE `supplier_ledgers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `supplier_ledgers_supplier_id_foreign` (`supplier_id`),
  ADD KEY `supplier_ledgers_purchase_id_foreign` (`purchase_id`),
  ADD KEY `supplier_ledgers_purchase_payment_id_foreign` (`purchase_payment_id`);

--
-- Indexes for table `supplier_products`
--
ALTER TABLE `supplier_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `supplier_products_supplier_id_foreign` (`supplier_id`),
  ADD KEY `supplier_products_product_id_foreign` (`product_id`),
  ADD KEY `supplier_products_product_variant_id_foreign` (`product_variant_id`);

--
-- Indexes for table `sv_devices`
--
ALTER TABLE `sv_devices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sv_device_models`
--
ALTER TABLE `sv_device_models`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sv_device_models_brand_id_foreign` (`brand_id`),
  ADD KEY `sv_device_models_device_id_foreign` (`device_id`);

--
-- Indexes for table `sv_job_sheets`
--
ALTER TABLE `sv_job_sheets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sv_job_sheets_customer_id_foreign` (`customer_id`),
  ADD KEY `sv_job_sheets_branch_id_foreign` (`branch_id`),
  ADD KEY `sv_job_sheets_user_id_foreign` (`user_id`),
  ADD KEY `sv_job_sheets_brand_id_foreign` (`brand_id`),
  ADD KEY `sv_job_sheets_device_id_foreign` (`device_id`),
  ADD KEY `sv_job_sheets_model_id_foreign` (`model_id`),
  ADD KEY `sv_job_sheets_status_id_foreign` (`status_id`);

--
-- Indexes for table `sv_job_sheets_parts`
--
ALTER TABLE `sv_job_sheets_parts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sv_job_sheets_parts_job_sheet_id_foreign` (`job_sheet_id`),
  ADD KEY `sv_job_sheets_parts_product_id_foreign` (`product_id`),
  ADD KEY `sv_job_sheets_parts_product_variant_id_foreign` (`product_variant_id`);

--
-- Indexes for table `sv_status`
--
ALTER TABLE `sv_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `taxes`
--
ALTER TABLE `taxes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `timezones`
--
ALTER TABLE `timezones`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transfer_stock_to_branches`
--
ALTER TABLE `transfer_stock_to_branches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transfer_stock_to_branches_warehouse_id_foreign` (`warehouse_id`),
  ADD KEY `transfer_stock_to_branches_branch_id_foreign` (`branch_id`);

--
-- Indexes for table `transfer_stock_to_branch_products`
--
ALTER TABLE `transfer_stock_to_branch_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transfer_stock_to_branch_products_transfer_stock_id_foreign` (`transfer_stock_id`),
  ADD KEY `transfer_stock_to_branch_products_product_id_foreign` (`product_id`),
  ADD KEY `transfer_stock_to_branch_products_product_variant_id_foreign` (`product_variant_id`);

--
-- Indexes for table `transfer_stock_to_warehouses`
--
ALTER TABLE `transfer_stock_to_warehouses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transfer_stock_to_warehouses_branch_id_foreign` (`branch_id`),
  ADD KEY `transfer_stock_to_warehouses_warehouse_id_foreign` (`warehouse_id`);

--
-- Indexes for table `transfer_stock_to_warehouse_products`
--
ALTER TABLE `transfer_stock_to_warehouse_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transfer_stock_to_warehouse_products_transfer_stock_id_foreign` (`transfer_stock_id`),
  ADD KEY `transfer_stock_to_warehouse_products_product_id_foreign` (`product_id`),
  ADD KEY `transfer_stock_to_warehouse_products_product_variant_id_foreign` (`product_variant_id`);

--
-- Indexes for table `units`
--
ALTER TABLE `units`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `warehouses`
--
ALTER TABLE `warehouses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `warranties`
--
ALTER TABLE `warranties`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `xyz`
--
ALTER TABLE `xyz`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `account_types`
--
ALTER TABLE `account_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `admin_and_users`
--
ALTER TABLE `admin_and_users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `allowance_employees`
--
ALTER TABLE `allowance_employees`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `assets`
--
ALTER TABLE `assets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `asset_types`
--
ALTER TABLE `asset_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `banks`
--
ALTER TABLE `banks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `barcode_settings`
--
ALTER TABLE `barcode_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `branch_payment_methods`
--
ALTER TABLE `branch_payment_methods`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `bulk_variants`
--
ALTER TABLE `bulk_variants`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `bulk_variant_children`
--
ALTER TABLE `bulk_variant_children`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `card_types`
--
ALTER TABLE `card_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `cash_counters`
--
ALTER TABLE `cash_counters`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `cash_flows`
--
ALTER TABLE `cash_flows`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=154;

--
-- AUTO_INCREMENT for table `cash_registers`
--
ALTER TABLE `cash_registers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `cash_register_transactions`
--
ALTER TABLE `cash_register_transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `combo_products`
--
ALTER TABLE `combo_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `currencies`
--
ALTER TABLE `currencies`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=141;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT for table `customer_groups`
--
ALTER TABLE `customer_groups`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `customer_ledgers`
--
ALTER TABLE `customer_ledgers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=304;

--
-- AUTO_INCREMENT for table `expanses`
--
ALTER TABLE `expanses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `expanse_categories`
--
ALTER TABLE `expanse_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `expanse_payments`
--
ALTER TABLE `expanse_payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `expense_descriptions`
--
ALTER TABLE `expense_descriptions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `general_settings`
--
ALTER TABLE `general_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `hrm_allowance`
--
ALTER TABLE `hrm_allowance`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `hrm_attendances`
--
ALTER TABLE `hrm_attendances`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `hrm_department`
--
ALTER TABLE `hrm_department`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `hrm_designations`
--
ALTER TABLE `hrm_designations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `hrm_holidays`
--
ALTER TABLE `hrm_holidays`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `hrm_leaves`
--
ALTER TABLE `hrm_leaves`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `hrm_leavetypes`
--
ALTER TABLE `hrm_leavetypes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `hrm_payrolls`
--
ALTER TABLE `hrm_payrolls`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `hrm_payroll_allowances`
--
ALTER TABLE `hrm_payroll_allowances`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `hrm_payroll_deductions`
--
ALTER TABLE `hrm_payroll_deductions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `hrm_payroll_payments`
--
ALTER TABLE `hrm_payroll_payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `hrm_shifts`
--
ALTER TABLE `hrm_shifts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `invoice_layouts`
--
ALTER TABLE `invoice_layouts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `invoice_schemas`
--
ALTER TABLE `invoice_schemas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=170;

--
-- AUTO_INCREMENT for table `money_receipts`
--
ALTER TABLE `money_receipts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `months`
--
ALTER TABLE `months`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `payment_methods`
--
ALTER TABLE `payment_methods`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `price_groups`
--
ALTER TABLE `price_groups`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=185;

--
-- AUTO_INCREMENT for table `product_branches`
--
ALTER TABLE `product_branches`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=183;

--
-- AUTO_INCREMENT for table `product_branch_variants`
--
ALTER TABLE `product_branch_variants`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `product_opening_stocks`
--
ALTER TABLE `product_opening_stocks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=167;

--
-- AUTO_INCREMENT for table `product_variants`
--
ALTER TABLE `product_variants`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `product_warehouses`
--
ALTER TABLE `product_warehouses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT for table `product_warehouse_variants`
--
ALTER TABLE `product_warehouse_variants`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `purchases`
--
ALTER TABLE `purchases`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=130;

--
-- AUTO_INCREMENT for table `purchase_payments`
--
ALTER TABLE `purchase_payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=130;

--
-- AUTO_INCREMENT for table `purchase_products`
--
ALTER TABLE `purchase_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=298;

--
-- AUTO_INCREMENT for table `purchase_returns`
--
ALTER TABLE `purchase_returns`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `purchase_return_products`
--
ALTER TABLE `purchase_return_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=139;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `role_permissions`
--
ALTER TABLE `role_permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=267;

--
-- AUTO_INCREMENT for table `sale_payments`
--
ALTER TABLE `sale_payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=252;

--
-- AUTO_INCREMENT for table `sale_products`
--
ALTER TABLE `sale_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=548;

--
-- AUTO_INCREMENT for table `sale_returns`
--
ALTER TABLE `sale_returns`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `sale_return_products`
--
ALTER TABLE `sale_return_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `stock_adjustments`
--
ALTER TABLE `stock_adjustments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `stock_adjustment_products`
--
ALTER TABLE `stock_adjustment_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `supplier_ledgers`
--
ALTER TABLE `supplier_ledgers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `supplier_products`
--
ALTER TABLE `supplier_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94;

--
-- AUTO_INCREMENT for table `sv_devices`
--
ALTER TABLE `sv_devices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sv_device_models`
--
ALTER TABLE `sv_device_models`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sv_job_sheets`
--
ALTER TABLE `sv_job_sheets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sv_job_sheets_parts`
--
ALTER TABLE `sv_job_sheets_parts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sv_status`
--
ALTER TABLE `sv_status`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `taxes`
--
ALTER TABLE `taxes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `timezones`
--
ALTER TABLE `timezones`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `transfer_stock_to_branches`
--
ALTER TABLE `transfer_stock_to_branches`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `transfer_stock_to_branch_products`
--
ALTER TABLE `transfer_stock_to_branch_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `transfer_stock_to_warehouses`
--
ALTER TABLE `transfer_stock_to_warehouses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transfer_stock_to_warehouse_products`
--
ALTER TABLE `transfer_stock_to_warehouse_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `units`
--
ALTER TABLE `units`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `warehouses`
--
ALTER TABLE `warehouses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `warranties`
--
ALTER TABLE `warranties`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `xyz`
--
ALTER TABLE `xyz`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `accounts`
--
ALTER TABLE `accounts`
  ADD CONSTRAINT `accounts_account_type_id_foreign` FOREIGN KEY (`account_type_id`) REFERENCES `account_types` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `accounts_bank_id_foreign` FOREIGN KEY (`bank_id`) REFERENCES `banks` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `admin_and_users`
--
ALTER TABLE `admin_and_users`
  ADD CONSTRAINT `admin_and_users_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `admin_and_users_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `hrm_department` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `admin_and_users_designation_id_foreign` FOREIGN KEY (`designation_id`) REFERENCES `hrm_designations` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `admin_and_users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `admin_and_users_role_permission_id_foreign` FOREIGN KEY (`role_permission_id`) REFERENCES `role_permissions` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `admin_and_users_shift_id_foreign` FOREIGN KEY (`shift_id`) REFERENCES `hrm_shifts` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `allowance_employees`
--
ALTER TABLE `allowance_employees`
  ADD CONSTRAINT `allowance_employees_allowance_id_foreign` FOREIGN KEY (`allowance_id`) REFERENCES `hrm_allowance` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `allowance_employees_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `admin_and_users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `assets`
--
ALTER TABLE `assets`
  ADD CONSTRAINT `assets_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `assets_type_id_foreign` FOREIGN KEY (`type_id`) REFERENCES `asset_types` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `branch_payment_methods`
--
ALTER TABLE `branch_payment_methods`
  ADD CONSTRAINT `branch_payment_methods_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `branch_payment_methods_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `bulk_variant_children`
--
ALTER TABLE `bulk_variant_children`
  ADD CONSTRAINT `bulk_variant_children_bulk_variant_id_foreign` FOREIGN KEY (`bulk_variant_id`) REFERENCES `bulk_variants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `card_types`
--
ALTER TABLE `card_types`
  ADD CONSTRAINT `card_types_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `cash_flows`
--
ALTER TABLE `cash_flows`
  ADD CONSTRAINT `cash_flows_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cash_flows_expanse_payment_id_foreign` FOREIGN KEY (`expanse_payment_id`) REFERENCES `expanse_payments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cash_flows_money_receipt_id_foreign` FOREIGN KEY (`money_receipt_id`) REFERENCES `money_receipts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cash_flows_payroll_id_foreign` FOREIGN KEY (`payroll_id`) REFERENCES `hrm_payrolls` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cash_flows_payroll_payment_id_foreign` FOREIGN KEY (`payroll_payment_id`) REFERENCES `hrm_payroll_payments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cash_flows_purchase_payment_id_foreign` FOREIGN KEY (`purchase_payment_id`) REFERENCES `purchase_payments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cash_flows_receiver_account_id_foreign` FOREIGN KEY (`receiver_account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cash_flows_sale_payment_id_foreign` FOREIGN KEY (`sale_payment_id`) REFERENCES `sale_payments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cash_flows_sender_account_id_foreign` FOREIGN KEY (`sender_account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cash_registers`
--
ALTER TABLE `cash_registers`
  ADD CONSTRAINT `cash_registers_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `cash_registers_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `admin_and_users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cash_registers_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cash_registers_cash_counter_id_foreign` FOREIGN KEY (`cash_counter_id`) REFERENCES `cash_counters` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `cash_registers_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cash_register_transactions`
--
ALTER TABLE `cash_register_transactions`
  ADD CONSTRAINT `cash_register_transactions_cash_register_id_foreign` FOREIGN KEY (`cash_register_id`) REFERENCES `cash_registers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cash_register_transactions_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_parent_category_id_foreign` FOREIGN KEY (`parent_category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `combo_products`
--
ALTER TABLE `combo_products`
  ADD CONSTRAINT `combo_products_combo_product_id_foreign` FOREIGN KEY (`combo_product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `combo_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `combo_products_product_variant_id_foreign` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `customers`
--
ALTER TABLE `customers`
  ADD CONSTRAINT `customers_customer_group_id_foreign` FOREIGN KEY (`customer_group_id`) REFERENCES `customer_groups` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `customer_ledgers`
--
ALTER TABLE `customer_ledgers`
  ADD CONSTRAINT `customer_ledgers_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `customer_ledgers_money_receipt_id_foreign` FOREIGN KEY (`money_receipt_id`) REFERENCES `money_receipts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `customer_ledgers_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `customer_ledgers_sale_payment_id_foreign` FOREIGN KEY (`sale_payment_id`) REFERENCES `sale_payments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `expanses`
--
ALTER TABLE `expanses`
  ADD CONSTRAINT `expanses_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `expanse_payments`
--
ALTER TABLE `expanse_payments`
  ADD CONSTRAINT `expanse_payments_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `expanse_payments_expanse_id_foreign` FOREIGN KEY (`expanse_id`) REFERENCES `expanses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `expense_descriptions`
--
ALTER TABLE `expense_descriptions`
  ADD CONSTRAINT `expense_descriptions_expense_category_id_foreign` FOREIGN KEY (`expense_category_id`) REFERENCES `expanse_categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `expense_descriptions_expense_id_foreign` FOREIGN KEY (`expense_id`) REFERENCES `expanses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hrm_allowance`
--
ALTER TABLE `hrm_allowance`
  ADD CONSTRAINT `hrm_allowance_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `admin_and_users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hrm_attendances`
--
ALTER TABLE `hrm_attendances`
  ADD CONSTRAINT `hrm_attendances_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `admin_and_users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hrm_holidays`
--
ALTER TABLE `hrm_holidays`
  ADD CONSTRAINT `hrm_holidays_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hrm_leaves`
--
ALTER TABLE `hrm_leaves`
  ADD CONSTRAINT `hrm_leaves_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `admin_and_users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hrm_leaves_leave_id_foreign` FOREIGN KEY (`leave_id`) REFERENCES `hrm_leavetypes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hrm_payrolls`
--
ALTER TABLE `hrm_payrolls`
  ADD CONSTRAINT `hrm_payrolls_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `admin_and_users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `hrm_payrolls_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `admin_and_users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hrm_payroll_allowances`
--
ALTER TABLE `hrm_payroll_allowances`
  ADD CONSTRAINT `hrm_payroll_allowances_payroll_id_foreign` FOREIGN KEY (`payroll_id`) REFERENCES `hrm_payrolls` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hrm_payroll_deductions`
--
ALTER TABLE `hrm_payroll_deductions`
  ADD CONSTRAINT `hrm_payroll_deductions_payroll_id_foreign` FOREIGN KEY (`payroll_id`) REFERENCES `hrm_payrolls` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hrm_payroll_payments`
--
ALTER TABLE `hrm_payroll_payments`
  ADD CONSTRAINT `hrm_payroll_payments_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hrm_payroll_payments_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `admin_and_users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `hrm_payroll_payments_payroll_id_foreign` FOREIGN KEY (`payroll_id`) REFERENCES `hrm_payrolls` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `money_receipts`
--
ALTER TABLE `money_receipts`
  ADD CONSTRAINT `money_receipts_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `money_receipts_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `products_parent_category_id_foreign` FOREIGN KEY (`parent_category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `products_tax_id_foreign` FOREIGN KEY (`tax_id`) REFERENCES `taxes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `products_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `products_warranty_id_foreign` FOREIGN KEY (`warranty_id`) REFERENCES `warranties` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `product_branches`
--
ALTER TABLE `product_branches`
  ADD CONSTRAINT `product_branches_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_branches_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_branch_variants`
--
ALTER TABLE `product_branch_variants`
  ADD CONSTRAINT `product_branch_variants_product_branch_id_foreign` FOREIGN KEY (`product_branch_id`) REFERENCES `product_branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_branch_variants_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_branch_variants_product_variant_id_foreign` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_opening_stocks`
--
ALTER TABLE `product_opening_stocks`
  ADD CONSTRAINT `product_opening_stocks_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_opening_stocks_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_opening_stocks_product_variant_id_foreign` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_opening_stocks_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD CONSTRAINT `product_variants_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_warehouses`
--
ALTER TABLE `product_warehouses`
  ADD CONSTRAINT `product_warehouses_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_warehouses_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_warehouse_variants`
--
ALTER TABLE `product_warehouse_variants`
  ADD CONSTRAINT `product_warehouse_variants_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_warehouse_variants_product_variant_id_foreign` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_warehouse_variants_product_warehouse_id_foreign` FOREIGN KEY (`product_warehouse_id`) REFERENCES `product_warehouses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `purchases`
--
ALTER TABLE `purchases`
  ADD CONSTRAINT `purchases_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchases_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchases_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `purchase_payments`
--
ALTER TABLE `purchase_payments`
  ADD CONSTRAINT `purchase_payments_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_payments_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_payments_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `purchase_products`
--
ALTER TABLE `purchase_products`
  ADD CONSTRAINT `purchase_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_products_product_variant_id_foreign` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_products_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `purchase_returns`
--
ALTER TABLE `purchase_returns`
  ADD CONSTRAINT `purchase_returns_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_returns_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_returns_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_returns_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `purchase_return_products`
--
ALTER TABLE `purchase_return_products`
  ADD CONSTRAINT `purchase_return_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_return_products_product_variant_id_foreign` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_return_products_purchase_product_id_foreign` FOREIGN KEY (`purchase_product_id`) REFERENCES `purchase_products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_return_products_purchase_return_id_foreign` FOREIGN KEY (`purchase_return_id`) REFERENCES `purchase_returns` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD CONSTRAINT `role_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sales_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sales_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sale_payments`
--
ALTER TABLE `sale_payments`
  ADD CONSTRAINT `sale_payments_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sale_payments_card_type_id_foreign` FOREIGN KEY (`card_type_id`) REFERENCES `card_types` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `sale_payments_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sale_payments_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sale_products`
--
ALTER TABLE `sale_products`
  ADD CONSTRAINT `sale_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sale_products_product_variant_id_foreign` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sale_products_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sale_returns`
--
ALTER TABLE `sale_returns`
  ADD CONSTRAINT `sale_returns_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sale_returns_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sale_returns_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sale_return_products`
--
ALTER TABLE `sale_return_products`
  ADD CONSTRAINT `sale_return_products_sale_product_id_foreign` FOREIGN KEY (`sale_product_id`) REFERENCES `sale_products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sale_return_products_sale_return_id_foreign` FOREIGN KEY (`sale_return_id`) REFERENCES `sale_returns` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stock_adjustments`
--
ALTER TABLE `stock_adjustments`
  ADD CONSTRAINT `stock_adjustments_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `admin_and_users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `stock_adjustments_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_adjustments_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `supplier_ledgers`
--
ALTER TABLE `supplier_ledgers`
  ADD CONSTRAINT `supplier_ledgers_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `supplier_ledgers_purchase_payment_id_foreign` FOREIGN KEY (`purchase_payment_id`) REFERENCES `purchase_payments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `supplier_ledgers_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sv_device_models`
--
ALTER TABLE `sv_device_models`
  ADD CONSTRAINT `sv_device_models_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sv_device_models_device_id_foreign` FOREIGN KEY (`device_id`) REFERENCES `sv_devices` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sv_job_sheets`
--
ALTER TABLE `sv_job_sheets`
  ADD CONSTRAINT `sv_job_sheets_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sv_job_sheets_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sv_job_sheets_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sv_job_sheets_device_id_foreign` FOREIGN KEY (`device_id`) REFERENCES `sv_devices` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sv_job_sheets_model_id_foreign` FOREIGN KEY (`model_id`) REFERENCES `sv_device_models` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sv_job_sheets_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `sv_status` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `sv_job_sheets_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `admin_and_users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `sv_job_sheets_parts`
--
ALTER TABLE `sv_job_sheets_parts`
  ADD CONSTRAINT `sv_job_sheets_parts_job_sheet_id_foreign` FOREIGN KEY (`job_sheet_id`) REFERENCES `sv_job_sheets` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sv_job_sheets_parts_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sv_job_sheets_parts_product_variant_id_foreign` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transfer_stock_to_branches`
--
ALTER TABLE `transfer_stock_to_branches`
  ADD CONSTRAINT `transfer_stock_to_branches_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transfer_stock_to_branches_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transfer_stock_to_branch_products`
--
ALTER TABLE `transfer_stock_to_branch_products`
  ADD CONSTRAINT `transfer_stock_to_branch_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transfer_stock_to_branch_products_product_variant_id_foreign` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transfer_stock_to_branch_products_transfer_stock_id_foreign` FOREIGN KEY (`transfer_stock_id`) REFERENCES `transfer_stock_to_branches` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transfer_stock_to_warehouses`
--
ALTER TABLE `transfer_stock_to_warehouses`
  ADD CONSTRAINT `transfer_stock_to_warehouses_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transfer_stock_to_warehouses_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transfer_stock_to_warehouse_products`
--
ALTER TABLE `transfer_stock_to_warehouse_products`
  ADD CONSTRAINT `transfer_stock_to_warehouse_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transfer_stock_to_warehouse_products_product_variant_id_foreign` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transfer_stock_to_warehouse_products_transfer_stock_id_foreign` FOREIGN KEY (`transfer_stock_id`) REFERENCES `transfer_stock_to_warehouses` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
