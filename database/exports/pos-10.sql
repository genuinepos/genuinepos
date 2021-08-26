-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 26, 2021 at 02:05 PM
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
(19, 'Payment Account', '111112574747', 8, NULL, '200000.00', '0.00', '200000.00', '200000.00', 'Payment', 1, 2, NULL, '2021-08-16 09:27:53');

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

-- --------------------------------------------------------

--
-- Table structure for table `addons`
--

CREATE TABLE `addons` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branches` tinyint(1) NOT NULL DEFAULT 0,
  `hrm` tinyint(1) NOT NULL DEFAULT 0,
  `todo` tinyint(1) NOT NULL DEFAULT 0,
  `service` tinyint(1) NOT NULL DEFAULT 0,
  `manufacturing` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `addons`
--

INSERT INTO `addons` (`id`, `branches`, `hrm`, `todo`, `service`, `manufacturing`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 1, 1, NULL, NULL);

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
(2, 'Mr', 'Super', 'Admin', NULL, 'superadmin', 'koalasoftsolution@gmail.com', NULL, 1, NULL, 8, 1, NULL, 1, '$2y$10$rd3uLXbr7OXtcZAh5VAj1u.nHtBpy0.gZx5HYXJ1uSR/TpT/nVBai', '0.00', '0.00', NULL, NULL, NULL, NULL, NULL, 'default.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'en', NULL, NULL, '0.00', NULL, '2021-04-07 07:04:03', '2021-08-24 08:04:00');

-- --------------------------------------------------------

--
-- Table structure for table `admin_and_user_logs`
--

CREATE TABLE `admin_and_user_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mac_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_login` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(22, 'Toyota car', 9, NULL, '3.00', '1000000.00', '3000000.00', NULL, NULL);

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
(9, 'Car', 'C-1', NULL, '2021-07-18 05:01:28');

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
(8, 'SONALI BANK', 'Dhaka Branch', 'Dhaka, Bangladesh', NULL, NULL);

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
(1, '20 Barcodes Per Sheet, Page Size :8.5\'\' * 11\'\', Barcode Size :4\'\' * 0.55\'\', Barcode 20 Per Sheet', '20 Barcodes Per Sheet, Page Size :8.5\'\' * 11\'\', Barcode Size :4\'\' * 0.55\'\', Barcode 20 Per Sheet', 0, 0.1200, 0.1200, 4.0000, 0.5500, 8.5000, 11.0000, 1.0000, 1.0000, 10, 20, 0, 1, NULL, '2021-07-01 11:09:33'),
(2, 'Sticker Print, Continuous feed or rolls , Barcode Size: 3 Inc * 2 Inc', NULL, 1, 0.1000, 0.0000, 1.2000, 0.2000, 1.8000, 1.3800, 0.0000, 0.0000, 1, 1, 1, 1, NULL, '2021-07-01 09:40:09'),
(3, '40 Barcodes Per Sheet, Page Size :8.5\'\' * 11\'\', Barcode Size :2\'\' * 0.39\'\', Barcode 40 Per Sheet', NULL, 0, 0.3000, 0.1000, 2.0000, 0.3900, 8.5000, 11.0000, 0.0000, 0.0000, 10, 30, 0, 1, NULL, '2021-07-01 11:55:53'),
(4, '30 Barcodes Per Sheet, Page Size :8.5\'\' * 11\'\', Barcode Size :2.4\'\' * 0.55\'\', Barcode 30 Per Sheet', NULL, 0, 0.1000, 0.1000, 2.4000, 0.5500, 8.5000, 11.0000, 0.0000, 0.0000, 30, 30, 0, 1, NULL, '2021-07-01 12:05:57');

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
(28, 'Dell', 'default.png', 1, '2021-08-12 05:21:12', '2021-08-12 05:21:12'),
(29, 'Kaspersky', 'default.png', 1, '2021-08-12 05:26:35', '2021-08-12 05:26:35'),
(30, 'HP', 'default.png', 1, '2021-08-12 05:28:52', '2021-08-12 05:28:52'),
(31, 'Microsoft', 'default.png', 1, '2021-08-12 05:38:24', '2021-08-12 05:38:24'),
(32, 'Zebra', 'default.png', 1, '2021-08-22 09:01:16', '2021-08-22 09:01:16');

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
(16, 'Ram', '2021-08-26 08:33:10', '2021-08-26 08:33:10'),
(17, 'Storage', '2021-08-26 08:33:46', '2021-08-26 08:33:46');

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
(61, 16, '4GB', 0, '2021-08-26 08:33:10', '2021-08-26 08:33:10'),
(62, 16, '8GB', 0, '2021-08-26 08:33:10', '2021-08-26 08:33:10'),
(63, 16, '12GB', 0, '2021-08-26 08:33:10', '2021-08-26 08:33:10'),
(64, 17, '32GB', 0, '2021-08-26 08:33:46', '2021-08-26 08:34:12'),
(65, 17, '64GB', 0, '2021-08-26 08:33:46', '2021-08-26 08:34:12'),
(66, 17, '128GB', 0, '2021-08-26 08:33:46', '2021-08-26 08:34:12'),
(67, 17, '256GB', 0, '2021-08-26 08:33:46', '2021-08-26 08:34:12');

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
(4, 'Master Card', NULL, NULL, NULL),
(5, 'ATM Card', NULL, NULL, '2021-06-17 11:57:02'),
(6, 'Pioneer Card', NULL, NULL, NULL),
(7, 'Visa Card', NULL, NULL, NULL),
(8, 'Debit Card', NULL, NULL, NULL),
(9, 'Credit Card', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cash_counters`
--

CREATE TABLE `cash_counters` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `counter_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `short_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(163, 19, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '200000.00', '200000.00', 7, 2, '2021-08-16', 'August', '2021', '2021-08-15 18:00:00', 2, NULL, '2021-08-16 09:27:32', '2021-08-16 09:27:32');

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
(32, NULL, NULL, NULL, 2, '2021-08-12 07:46:00', '100.00', 0, 0, 0, NULL, '2021-07-17 12:26:45', '2021-08-12 07:46:07', NULL),
(34, NULL, NULL, NULL, 2, NULL, '0.00', NULL, NULL, 1, NULL, '2021-08-12 08:09:35', '2021-08-12 08:09:35', NULL);

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
(136, 32, NULL, 2, 1, '100.00', '2021-07-17 12:26:45', '2021-07-17 12:26:45'),
(139, 32, 341, 2, 2, NULL, '2021-07-18 12:09:36', '2021-07-18 12:09:36'),
(140, 34, NULL, 2, 1, '0.00', '2021-08-12 08:09:35', '2021-08-12 08:09:35'),
(141, 34, 347, 2, 2, NULL, '2021-08-16 08:04:10', '2021-08-16 08:04:10'),
(142, 34, 350, 2, 2, NULL, '2021-08-18 07:20:24', '2021-08-18 07:20:24'),
(143, 34, 351, 2, 2, NULL, '2021-08-18 07:23:20', '2021-08-18 07:23:20'),
(144, 34, 352, 2, 2, NULL, '2021-08-18 07:29:35', '2021-08-18 07:29:35'),
(145, 34, 353, 2, 2, NULL, '2021-08-18 07:29:44', '2021-08-18 07:29:44'),
(146, 34, 354, 2, 2, NULL, '2021-08-18 07:30:10', '2021-08-18 07:30:10'),
(147, 34, 355, 2, 2, NULL, '2021-08-18 07:35:05', '2021-08-18 07:35:05'),
(148, 34, 365, 2, 2, NULL, '2021-08-21 06:43:43', '2021-08-21 06:43:43'),
(149, 34, 366, 2, 2, NULL, '2021-08-21 07:00:52', '2021-08-21 07:00:52'),
(150, 34, 396, 2, 2, NULL, '2021-08-23 07:48:11', '2021-08-23 07:48:11'),
(151, 34, 397, 2, 2, NULL, '2021-08-23 07:48:44', '2021-08-23 07:48:44'),
(152, 34, 402, 2, 2, NULL, '2021-08-26 05:35:02', '2021-08-26 05:35:02'),
(153, 34, 405, 2, 2, NULL, '2021-08-26 05:36:45', '2021-08-26 05:36:45'),
(154, 34, 406, 2, 2, NULL, '2021-08-26 05:38:17', '2021-08-26 05:38:17');

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
(72, 'Backpack', NULL, '611de8ced1a80.png', 1, '2021-08-12 05:25:02', '2021-08-19 05:14:54'),
(73, 'Software', NULL, '611de8e34a493.png', 1, '2021-08-12 05:26:26', '2021-08-19 05:15:15'),
(82, 'Printer', NULL, 'default.png', 1, '2021-08-22 09:01:02', '2021-08-22 09:01:02'),
(83, 'Accounting', 73, 'default.png', 1, NULL, NULL);

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

--
-- Dumping data for table `combo_products`
--

INSERT INTO `combo_products` (`id`, `product_id`, `combo_product_id`, `quantity`, `product_variant_id`, `delete_in_update`, `created_at`, `updated_at`) VALUES
(7, 219, 218, '1.00', NULL, 0, '2021-08-22 04:54:26', '2021-08-22 04:54:26'),
(8, 219, 216, '1.00', NULL, 0, '2021-08-22 04:54:26', '2021-08-22 04:54:26');

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
(68, 1, '69441', NULL, 'Mr. Grims', NULL, '8801919585035', '8801919', '88', 'koalasoftsolution@gmail.com', NULL, NULL, '0.00', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1241628.40', '1247342.60', '0.00', '0.00', '35340.00', 1, 0, '2021-07-18 05:17:40', '2021-08-26 07:53:55'),
(69, NULL, '82571', NULL, 'ASBRM', NULL, '88 02 8391618', '88 02 8391618', '88 02 8391618', NULL, NULL, NULL, '0.00', 1, NULL, 'Barpa , Tarabo , Rupgonj, Narayangonj, Bangladesh', NULL, NULL, NULL, NULL, NULL, '555050.00', '746300.00', '483850.00', '0.00', '0.00', 1, 0, '2021-08-12 05:40:02', '2021-08-26 07:54:00');

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
(7, 'Premium Group', '4.00', NULL, NULL),
(8, 'Bronze Customer', '2.00', NULL, NULL);

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
(371, 68, NULL, NULL, NULL, 3, '0.00', 0, '2021-07-18 05:17:40', '2021-07-18 05:17:40'),
(372, 68, 338, NULL, NULL, 1, NULL, 0, '2021-07-18 05:33:43', '2021-07-18 05:33:43'),
(374, 68, 340, NULL, NULL, 1, NULL, 0, '2021-07-18 10:23:54', '2021-07-18 10:23:54'),
(376, 68, 341, NULL, NULL, 1, NULL, 0, '2021-07-18 12:09:36', '2021-07-18 12:09:36'),
(377, 68, 341, NULL, NULL, 1, NULL, 0, '2021-07-18 12:09:36', '2021-07-18 12:09:36'),
(378, 69, NULL, NULL, NULL, 3, '0.00', 0, '2021-08-12 05:40:02', '2021-08-12 05:40:02'),
(381, 69, 343, NULL, NULL, 1, NULL, 0, '2021-08-12 05:54:12', '2021-08-12 05:54:12'),
(383, 69, 344, NULL, NULL, 1, NULL, 0, '2021-08-12 07:27:30', '2021-08-12 07:27:30'),
(384, 69, 345, NULL, NULL, 1, NULL, 0, '2021-08-14 04:58:13', '2021-08-14 04:58:13'),
(391, 68, 348, NULL, NULL, 1, NULL, 0, '2021-08-17 09:02:15', '2021-08-17 09:02:15'),
(392, 68, 349, NULL, NULL, 1, NULL, 0, '2021-08-17 10:34:17', '2021-08-17 10:34:17'),
(393, 68, 350, NULL, NULL, 1, NULL, 0, '2021-08-18 07:20:24', '2021-08-18 07:20:24'),
(394, 68, 350, NULL, NULL, 1, NULL, 0, '2021-08-18 07:20:24', '2021-08-18 07:20:24'),
(395, 68, 351, NULL, NULL, 1, NULL, 0, '2021-08-18 07:23:20', '2021-08-18 07:23:20'),
(396, 68, 351, NULL, NULL, 1, NULL, 0, '2021-08-18 07:23:20', '2021-08-18 07:23:20'),
(397, 68, 352, NULL, NULL, 1, NULL, 0, '2021-08-18 07:29:35', '2021-08-18 07:29:35'),
(398, 68, 352, NULL, NULL, 1, NULL, 0, '2021-08-18 07:29:35', '2021-08-18 07:29:35'),
(399, 68, 353, NULL, NULL, 1, NULL, 0, '2021-08-18 07:29:44', '2021-08-18 07:29:44'),
(400, 68, 353, NULL, NULL, 1, NULL, 0, '2021-08-18 07:29:44', '2021-08-18 07:29:44'),
(401, 68, 354, NULL, NULL, 1, NULL, 0, '2021-08-18 07:30:10', '2021-08-18 07:30:10'),
(402, 68, 354, NULL, NULL, 1, NULL, 0, '2021-08-18 07:30:10', '2021-08-18 07:30:10'),
(403, 68, 355, NULL, NULL, 1, NULL, 0, '2021-08-18 07:35:05', '2021-08-18 07:35:05'),
(404, 68, 355, NULL, NULL, 1, NULL, 0, '2021-08-18 07:35:05', '2021-08-18 07:35:05'),
(405, 68, 356, NULL, NULL, 1, NULL, 0, '2021-08-18 08:45:51', '2021-08-18 08:45:51'),
(406, 68, 357, NULL, NULL, 1, NULL, 0, '2021-08-18 08:46:18', '2021-08-18 08:46:18'),
(407, 68, 358, NULL, NULL, 1, NULL, 0, '2021-08-18 08:46:56', '2021-08-18 08:46:56'),
(408, 68, 359, NULL, NULL, 1, NULL, 0, '2021-08-18 08:50:48', '2021-08-18 08:50:48'),
(409, 68, 360, NULL, NULL, 1, NULL, 0, '2021-08-18 09:01:26', '2021-08-18 09:01:26'),
(410, 68, 361, NULL, NULL, 1, NULL, 0, '2021-08-18 12:23:00', '2021-08-18 12:23:00'),
(411, 68, NULL, 320, NULL, 2, NULL, 0, '2021-08-18 12:23:00', '2021-08-18 12:23:00'),
(412, 68, NULL, 321, NULL, 2, NULL, 0, '2021-08-18 12:23:00', '2021-08-18 12:23:00'),
(413, 68, NULL, 322, NULL, 2, NULL, 0, '2021-08-18 12:23:00', '2021-08-18 12:23:00'),
(414, 68, NULL, 323, NULL, 2, NULL, 0, '2021-08-18 12:23:00', '2021-08-18 12:23:00'),
(415, 68, NULL, 324, NULL, 2, NULL, 0, '2021-08-18 12:23:00', '2021-08-18 12:23:00'),
(416, 68, NULL, 325, NULL, 2, NULL, 0, '2021-08-18 12:23:00', '2021-08-18 12:23:00'),
(417, 68, NULL, 326, NULL, 2, NULL, 0, '2021-08-18 12:23:00', '2021-08-18 12:23:00'),
(418, 68, NULL, 327, NULL, 2, NULL, 0, '2021-08-18 12:23:00', '2021-08-18 12:23:00'),
(419, 68, NULL, 328, NULL, 2, NULL, 0, '2021-08-18 12:23:00', '2021-08-18 12:23:00'),
(420, 68, NULL, 329, NULL, 2, NULL, 0, '2021-08-18 12:23:00', '2021-08-18 12:23:00'),
(421, 68, NULL, 330, NULL, 2, NULL, 0, '2021-08-18 12:23:00', '2021-08-18 12:23:00'),
(422, 68, NULL, 331, NULL, 2, NULL, 0, '2021-08-18 12:23:00', '2021-08-18 12:23:00'),
(423, 68, NULL, 332, NULL, 2, NULL, 0, '2021-08-18 12:23:00', '2021-08-18 12:23:00'),
(424, 68, NULL, 333, NULL, 2, NULL, 0, '2021-08-18 12:23:00', '2021-08-18 12:23:00'),
(425, 68, NULL, 334, NULL, 2, NULL, 0, '2021-08-18 12:23:00', '2021-08-18 12:23:00'),
(426, 68, NULL, 335, NULL, 2, NULL, 0, '2021-08-18 12:23:00', '2021-08-18 12:23:00'),
(427, 68, NULL, 336, NULL, 2, NULL, 0, '2021-08-18 12:23:00', '2021-08-18 12:23:00'),
(428, 68, 363, NULL, NULL, 1, NULL, 0, '2021-08-21 06:40:14', '2021-08-21 06:40:14'),
(429, 68, 364, NULL, NULL, 1, NULL, 0, '2021-08-21 06:40:32', '2021-08-21 06:40:32'),
(430, 68, 365, NULL, NULL, 1, NULL, 0, '2021-08-21 06:43:43', '2021-08-21 06:43:43'),
(431, 68, 365, NULL, NULL, 1, NULL, 0, '2021-08-21 06:43:43', '2021-08-21 06:43:43'),
(432, 68, 366, NULL, NULL, 1, NULL, 0, '2021-08-21 07:00:52', '2021-08-21 07:00:52'),
(433, 68, 366, NULL, NULL, 1, NULL, 0, '2021-08-21 07:00:52', '2021-08-21 07:00:52'),
(434, 69, 372, NULL, NULL, 1, NULL, 0, '2021-08-22 09:05:19', '2021-08-22 09:05:19'),
(435, 68, 373, NULL, NULL, 1, NULL, 0, '2021-08-22 09:41:44', '2021-08-22 09:41:44'),
(436, 68, NULL, 343, NULL, 2, NULL, 0, '2021-08-22 09:41:44', '2021-08-22 09:41:44'),
(437, 68, 374, NULL, NULL, 1, NULL, 0, '2021-08-22 10:00:20', '2021-08-22 10:00:20'),
(438, 68, NULL, 344, NULL, 2, NULL, 0, '2021-08-22 10:00:20', '2021-08-22 10:00:20'),
(439, 68, 375, NULL, NULL, 1, NULL, 0, '2021-08-22 10:02:05', '2021-08-22 10:02:05'),
(440, 68, NULL, 345, NULL, 2, NULL, 0, '2021-08-22 10:02:05', '2021-08-22 10:02:05'),
(441, 68, NULL, 346, NULL, 2, NULL, 0, '2021-08-22 10:02:05', '2021-08-22 10:02:05'),
(442, 68, NULL, 347, NULL, 2, NULL, 0, '2021-08-22 10:02:05', '2021-08-22 10:02:05'),
(443, 68, NULL, 348, NULL, 2, NULL, 0, '2021-08-22 10:02:05', '2021-08-22 10:02:05'),
(444, 68, NULL, 349, NULL, 2, NULL, 0, '2021-08-22 10:02:05', '2021-08-22 10:02:05'),
(445, 68, 376, NULL, NULL, 1, NULL, 0, '2021-08-22 10:03:55', '2021-08-22 10:03:55'),
(446, 68, NULL, 350, NULL, 2, NULL, 0, '2021-08-22 10:03:55', '2021-08-22 10:03:55'),
(447, 68, NULL, 351, NULL, 2, NULL, 0, '2021-08-22 10:03:55', '2021-08-22 10:03:55'),
(448, 68, 377, NULL, NULL, 1, NULL, 0, '2021-08-22 10:06:34', '2021-08-22 10:06:34'),
(449, 68, NULL, 352, NULL, 2, NULL, 0, '2021-08-22 10:06:34', '2021-08-22 10:06:34'),
(450, 68, 378, NULL, NULL, 1, NULL, 0, '2021-08-22 10:11:48', '2021-08-22 10:11:48'),
(451, 68, 379, NULL, NULL, 1, NULL, 0, '2021-08-22 11:41:33', '2021-08-22 11:41:33'),
(452, 68, 380, NULL, NULL, 1, NULL, 0, '2021-08-22 11:42:20', '2021-08-22 11:42:20'),
(453, 68, 381, NULL, NULL, 1, NULL, 0, '2021-08-22 11:42:50', '2021-08-22 11:42:50'),
(454, 68, 382, NULL, NULL, 1, NULL, 0, '2021-08-23 04:53:27', '2021-08-23 04:53:27'),
(455, 68, 383, NULL, NULL, 1, NULL, 0, '2021-08-23 06:43:24', '2021-08-23 06:43:24'),
(456, 68, 384, NULL, NULL, 1, NULL, 0, '2021-08-23 06:43:49', '2021-08-23 06:43:49'),
(457, 68, 385, NULL, NULL, 1, NULL, 0, '2021-08-23 06:44:33', '2021-08-23 06:44:33'),
(458, 68, 386, NULL, NULL, 1, NULL, 0, '2021-08-23 06:51:20', '2021-08-23 06:51:20'),
(459, 68, 387, NULL, NULL, 1, NULL, 0, '2021-08-23 06:53:15', '2021-08-23 06:53:15'),
(460, 68, 388, NULL, NULL, 1, NULL, 0, '2021-08-23 06:57:02', '2021-08-23 06:57:02'),
(461, 68, 389, NULL, NULL, 1, NULL, 0, '2021-08-23 07:00:05', '2021-08-23 07:00:05'),
(462, 68, 390, NULL, NULL, 1, NULL, 0, '2021-08-23 07:00:25', '2021-08-23 07:00:25'),
(463, 68, 391, NULL, NULL, 1, NULL, 0, '2021-08-23 07:00:59', '2021-08-23 07:00:59'),
(464, 68, 392, NULL, NULL, 1, NULL, 0, '2021-08-23 07:02:07', '2021-08-23 07:02:07'),
(465, 68, 393, NULL, NULL, 1, NULL, 0, '2021-08-23 07:17:20', '2021-08-23 07:17:20'),
(466, 68, 394, NULL, NULL, 1, NULL, 0, '2021-08-23 07:18:46', '2021-08-23 07:18:46'),
(467, 68, 395, NULL, NULL, 1, NULL, 0, '2021-08-23 07:21:01', '2021-08-23 07:21:01'),
(468, 68, 396, NULL, NULL, 1, NULL, 0, '2021-08-23 07:48:11', '2021-08-23 07:48:11'),
(469, 68, 396, NULL, NULL, 1, NULL, 0, '2021-08-23 07:48:11', '2021-08-23 07:48:11'),
(470, 68, 397, NULL, NULL, 1, NULL, 0, '2021-08-23 07:48:44', '2021-08-23 07:48:44'),
(471, 68, 397, NULL, NULL, 1, NULL, 0, '2021-08-23 07:48:44', '2021-08-23 07:48:44'),
(472, 69, 398, NULL, NULL, 1, NULL, 0, '2021-08-23 12:02:29', '2021-08-23 12:02:29'),
(473, 68, 399, NULL, NULL, 1, NULL, 0, '2021-08-24 05:48:58', '2021-08-24 05:48:58'),
(476, 68, NULL, 353, NULL, 2, NULL, 0, '2021-08-25 05:24:30', '2021-08-25 05:24:30'),
(477, 68, NULL, 354, NULL, 2, NULL, 0, '2021-08-25 05:24:30', '2021-08-25 05:24:30'),
(478, 68, NULL, 355, NULL, 2, NULL, 0, '2021-08-25 05:24:30', '2021-08-25 05:24:30'),
(479, 68, NULL, 356, NULL, 2, NULL, 0, '2021-08-25 05:24:30', '2021-08-25 05:24:30'),
(480, 68, NULL, 357, NULL, 2, NULL, 0, '2021-08-25 05:24:30', '2021-08-25 05:24:30'),
(481, 68, NULL, 358, NULL, 2, NULL, 0, '2021-08-25 05:24:30', '2021-08-25 05:24:30'),
(482, 68, NULL, 359, NULL, 2, NULL, 0, '2021-08-25 05:24:30', '2021-08-25 05:24:30'),
(483, 68, NULL, 360, NULL, 2, NULL, 0, '2021-08-25 05:24:30', '2021-08-25 05:24:30'),
(484, 68, NULL, 361, NULL, 2, NULL, 0, '2021-08-25 05:24:30', '2021-08-25 05:24:30'),
(485, 68, NULL, 362, NULL, 2, NULL, 0, '2021-08-25 05:24:30', '2021-08-25 05:24:30'),
(486, 68, NULL, 363, NULL, 2, NULL, 0, '2021-08-25 05:24:30', '2021-08-25 05:24:30'),
(487, 68, NULL, 364, NULL, 2, NULL, 0, '2021-08-25 05:24:30', '2021-08-25 05:24:30'),
(488, 68, NULL, 365, NULL, 2, NULL, 0, '2021-08-25 05:24:30', '2021-08-25 05:24:30'),
(489, 68, NULL, 366, NULL, 2, NULL, 0, '2021-08-25 05:24:30', '2021-08-25 05:24:30'),
(490, 68, NULL, 367, NULL, 2, NULL, 0, '2021-08-25 05:24:30', '2021-08-25 05:24:30'),
(491, 68, NULL, 368, NULL, 2, NULL, 0, '2021-08-25 05:24:30', '2021-08-25 05:24:30'),
(492, 68, NULL, 369, NULL, 2, NULL, 0, '2021-08-25 05:24:30', '2021-08-25 05:24:30'),
(493, 68, NULL, 370, NULL, 2, NULL, 0, '2021-08-25 05:24:30', '2021-08-25 05:24:30'),
(494, 68, NULL, 371, NULL, 2, NULL, 0, '2021-08-25 05:24:30', '2021-08-25 05:24:30'),
(495, 68, NULL, 372, NULL, 2, NULL, 0, '2021-08-25 05:24:30', '2021-08-25 05:24:30'),
(496, 68, NULL, 373, NULL, 2, NULL, 0, '2021-08-25 05:24:30', '2021-08-25 05:24:30'),
(499, 68, 402, NULL, NULL, 1, NULL, 0, '2021-08-26 05:35:02', '2021-08-26 05:35:02'),
(500, 68, 402, NULL, NULL, 1, NULL, 0, '2021-08-26 05:35:02', '2021-08-26 05:35:02'),
(501, 68, 403, NULL, NULL, 1, NULL, 0, '2021-08-26 05:35:45', '2021-08-26 05:35:45'),
(502, 68, 403, NULL, NULL, 1, NULL, 0, '2021-08-26 05:35:45', '2021-08-26 05:35:45'),
(503, 68, 404, NULL, NULL, 1, NULL, 0, '2021-08-26 05:35:56', '2021-08-26 05:35:56'),
(504, 68, 404, NULL, NULL, 1, NULL, 0, '2021-08-26 05:35:56', '2021-08-26 05:35:56'),
(505, 68, 405, NULL, NULL, 1, NULL, 0, '2021-08-26 05:36:45', '2021-08-26 05:36:45'),
(506, 68, 405, NULL, NULL, 1, NULL, 0, '2021-08-26 05:36:45', '2021-08-26 05:36:45'),
(507, 68, NULL, 376, NULL, 2, NULL, 0, '2021-08-26 05:36:45', '2021-08-26 05:36:45'),
(508, 68, NULL, 377, NULL, 2, NULL, 0, '2021-08-26 05:36:45', '2021-08-26 05:36:45');

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
(19, 'EXI210718166463', NULL, NULL, NULL, '0.00', '0.00', '100.00', '100.00', '100.00', '0.00', '2021-07-18', 'July', '2021', 2, '2021-07-18 11:07:05', '2021-07-18 05:39:25', '2021-07-18 11:07:05'),
(20, 'EXI210814837157', NULL, NULL, NULL, '0.00', '0.00', '11400.00', '11400.00', '0.00', '11400.00', '2021-08-14', 'August', '2021', 2, '2021-08-13 18:00:00', '2021-08-14 11:48:49', '2021-08-14 11:48:49'),
(21, 'EXI210817585146', NULL, NULL, NULL, '5.00', '0.00', '200.00', '210.00', '210.00', '0.00', '2021-08-17', 'August', '2021', 2, '2021-08-16 18:00:00', '2021-08-17 09:05:31', '2021-08-17 09:05:31'),
(22, 'EXI210821948599', NULL, NULL, NULL, '0.00', '0.00', '100.00', '100.00', '0.00', '100.00', '2021-08-20', 'August', '2021', NULL, '2021-08-19 18:00:00', '2021-08-21 07:08:43', '2021-08-21 07:08:43'),
(23, 'EXI210821233936', NULL, NULL, NULL, '0.00', '0.00', '2000.00', '2000.00', '0.00', '2000.00', '2021-08-21', 'August', '2021', NULL, '2021-08-20 18:00:00', '2021-08-21 07:11:33', '2021-08-21 07:11:33');

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
(18, 'Net Bill', 'NB', NULL, '2021-08-14 07:23:13'),
(19, 'Electricity Bill', 'EB', NULL, NULL),
(20, 'Home Cleaner', 'HC', NULL, NULL);

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
(42, 'EXPI21071852693', 19, NULL, 'Cash', '100.00', NULL, '2021-07-18', 'July', '2021', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, '2021-07-17 18:00:00', '2021-07-18 11:06:57', '2021-07-18 11:07:05'),
(43, 'EXPI210817585146', 21, NULL, 'Cash', '210.00', NULL, '2021-08-17', 'August', '2021', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, '2021-08-16 18:00:00', '2021-08-17 09:05:31', '2021-08-17 09:05:31');

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
(32, 19, 18, '100.00', 0, '2021-07-18 05:39:25', '2021-07-18 05:39:25'),
(33, 20, 20, '1400.00', 0, '2021-08-14 11:48:49', '2021-08-14 11:48:49'),
(34, 20, 19, '7000.00', 0, '2021-08-14 11:48:49', '2021-08-14 11:48:49'),
(35, 20, 18, '3000.00', 0, '2021-08-14 11:48:49', '2021-08-14 11:48:49'),
(36, 21, 20, '200.00', 0, '2021-08-17 09:05:31', '2021-08-17 09:05:31'),
(37, 22, 20, '100.00', 0, '2021-08-21 07:08:43', '2021-08-21 07:08:43'),
(38, 23, 19, '2000.00', 0, '2021-08-21 07:11:33', '2021-08-21 07:11:33');

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
  `send_es_settings` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'send email and sms settings',
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

INSERT INTO `general_settings` (`id`, `business`, `tax`, `product`, `sale`, `pos`, `purchase`, `dashboard`, `system`, `prefix`, `send_es_settings`, `email_setting`, `sms_setting`, `modules`, `reward_poing_settings`, `multi_branches`, `hrm`, `services`, `menufacturing`, `projects`, `essentials`, `e_commerce`, `contact_default_cr_limit`, `created_at`, `updated_at`) VALUES
(1, '{\"shop_name\":\"SpeedDigit Computers\",\"address\":\"Gausia Kasem Center 10\\/2 Arambagh (7th floor), Motijheel-1000\",\"phone\":\"01792288555\",\"email\":\"speeddigitinfo@gmail.com\",\"start_date\":\"07-04-2021\",\"default_profit\":0,\"currency\":\"\\u09f3\",\"currency_placement\":null,\"date_format\":\"d-m-Y\",\"financial_year_start\":\"Januaray\",\"time_format\":\"24\",\"business_logo\":\"61274f6ac7ab5-.png\",\"timezone\":\"Asia\\/Dhaka\"}', '{\"tax_1_name\":null,\"tax_1_no\":null,\"tax_2_name\":null,\"tax_2_no\":null,\"is_tax_en_purchase_sale\":0}', '{\"product_code_prefix\":null,\"default_unit_id\":\"null\",\"is_enable_brands\":1,\"is_enable_categories\":1,\"is_enable_sub_categories\":1,\"is_enable_price_tax\":1,\"is_enable_warranty\":1}', '{\"default_sale_discount\":\"0.00\",\"default_tax_id\":\"null\",\"sales_cmsn_agnt\":\"select_form_cmsn_list\",\"default_price_group_id\":\"7\"}', '{\"is_disable_draft\":0,\"is_disable_quotation\":0,\"is_disable_challan\":0,\"is_disable_hold_invoice\":0,\"is_disable_multiple_pay\":1,\"is_show_recent_transactions\":0,\"is_disable_discount\":0,\"is_disable_order_tax\":0,\"is_show_credit_sale_button\":1,\"is_show_partial_sale_button\":1}', '{\"is_edit_pro_price\":0,\"is_enable_status\":1,\"is_enable_lot_no\":1}', '{\"view_stock_expiry_alert_for\":\"31\"}', '[]', '{\"purchase_invoice\":\"PI\",\"sale_invoice\":\"SI\",\"purchase_return\":\"PRI\",\"stock_transfer\":\"STI\",\"stock_djustment\":\"SA\",\"sale_return\":\"SRI\",\"expenses\":\"EXI\",\"supplier_id\":\"SID\",\"customer_id\":null,\"purchase_payment\":\"PPI\",\"sale_payment\":\"SPI\",\"expanse_payment\":\"EXPI\"}', '{\"send_inv_via_email\":1,\"send_notice_via_sms\":1,\"cmr_due_rmdr_via_email\":1,\"cmr_due_rmdr_via_sms\":1}', '[]', '[]', '{\"purchases\":1,\"add_sale\":1,\"pos\":1,\"transfer_stock\":1,\"stock_adjustment\":1,\"expenses\":1,\"accounting\":1,\"contacts\":1,\"hrms\":1,\"requisite\":1}', '{\"enable_cus_point\":1,\"point_display_name\":\"Reward Point\",\"amount_for_unit_rp\":\"10\",\"min_order_total_for_rp\":\"100\",\"max_rp_per_order\":\"\",\"redeem_amount_per_unit_rp\":\"0.10\",\"min_order_total_for_redeem\":\"\",\"min_redeem_point\":\"\",\"max_redeem_point\":\"\"}', 0, 0, 0, 0, 0, 0, 0, '50000000.00', NULL, '2021-08-26 08:23:06');

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
(30, '14-08-2021', 2, '15:03', NULL, NULL, NULL, NULL, 'August', '2021', '2021-08-14 09:03:00', NULL, '2021-08-13 18:00:00', 0, '2021-08-14 09:03:40', '2021-08-14 09:03:40');

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
(3, 'fdf', 'dfd', 'fdd', NULL, NULL);

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
(2, 'fdsfd', 'fdf', NULL, NULL);

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
(3, '15th August', '2021-08-15', '2021-08-15', NULL, 0, NULL, '2021-08-14 09:23:01', '2021-08-14 09:23:01'),
(4, 'Victory day', '2021-12-16', '2021-12-16', NULL, 1, NULL, '2021-08-25 05:56:11', '2021-08-25 05:56:11');

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
(2, '73139', 3, 2, '2021-08-25', '2021-08-25', NULL, 0, NULL, NULL);

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
(3, 'Sick', 3, 2, NULL, NULL);

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
(15, 2, 'EP1408218596', '1.00', 'Monthly', '20000.00', '20000.00', '0.00', '0.00', '20000.00', '20000.00', '0.00', '2021-08-14 09:09:23', '14-08-2021', 'August', '2021', 2, '2021-08-14 09:09:23', '2021-08-14 09:09:43'),
(16, 2, 'EP1708214293', '1.00', 'Monthly', '15000.00', '15000.00', '0.00', '0.00', '15000.00', '15000.00', '0.00', '2021-08-17 08:57:52', '17-08-2021', 'September', '2021', 2, '2021-08-17 08:57:52', '2021-08-17 08:58:11'),
(17, 2, 'EP2108215937', '1.00', 'Monthly', '2000.00', '2000.00', '0.00', '0.00', '2000.00', '0.00', '2000.00', '2021-08-20 18:00:00', '21-08-2021', 'October', '2021', 2, '2021-08-21 07:16:17', '2021-08-21 07:16:17');

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
(17, 'PRP21081493611', 15, NULL, '20000.00', '0.00', 'Cash', '2021-08-14', '03:09:43 pm', 'August', '2021', '2021-08-13 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-08-14 09:09:43', '2021-08-14 09:09:43'),
(18, 'PRP21081742449', 16, NULL, '15000.00', '0.00', 'Cash', '2021-08-17', '02:58:11 pm', 'August', '2021', '2021-08-16 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-08-17 08:58:11', '2021-08-17 08:58:11');

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
(3, 'Day Shift', '18:29', NULL, '18:29', NULL, NULL);

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
(1, 'Default layout', 1, 1, NULL, 1, 4, 1, 1, 0, 1, 1, NULL, NULL, NULL, 'Invoice/Bill', 'Quotation', 'Draft', 'Challan', 0, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 1, 1, 1, 0, 1, 1, 0, 0, 'If you need any support, Feel free to contact us. email: speeddigitinfo@gmail.com.', 0, 1, NULL, 'AL-ARAFA ISLAMI BANK Ltd.', 'Nawabpur', 'Speed Digit Pvt. Ltd', '0121020028467', 1, '2021-03-02 12:24:36', '2021-08-23 12:02:46'),
(2, 'Pos Printer Layout', 2, 1, NULL, 0, NULL, 1, 1, 1, 1, 1, NULL, NULL, NULL, 'Invoice', 'Quotation', 'Draft', 'Challan', 1, 1, 1, 0, 1, 1, 1, 1, 0, 0, 0, 0, 1, 1, 0, 1, 1, 0, 0, 'Invoice Notice', 0, 1, 'Footer Text', 'ff', 'ff', 'ff', 'ff', 0, '2021-03-03 10:20:30', '2021-06-26 12:08:26'),
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
(9, 'test', '2', NULL, NULL, 1, '2021/', '2021-06-06 12:02:32', '2021-06-24 12:28:04'),
(10, 'Test-2', '1', '0', NULL, 0, 'SD', '2021-08-16 11:04:05', '2021-08-16 11:04:05'),
(11, 'Test-3', '2', '1', NULL, 0, '2021/', '2021-08-16 11:07:54', '2021-08-16 11:07:54'),
(12, 'TEST-4', '2', '12', NULL, 0, '2021/', '2021-08-16 11:08:29', '2021-08-16 11:08:29'),
(13, 'TEST-5', '2', '1', NULL, 0, '2021/', '2021-08-16 12:04:15', '2021-08-16 12:04:15');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `queue`, `payload`, `attempts`, `reserved_at`, `available_at`, `created_at`) VALUES
(5, 'default', '{\"uuid\":\"58356425-4270-4984-ad1a-4213c8774492\",\"displayName\":\"App\\\\Jobs\\\\SaleMailJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SaleMailJob\",\"command\":\"O:20:\\\"App\\\\Jobs\\\\SaleMailJob\\\":11:{s:2:\\\"to\\\";s:27:\\\"koalasoftsolution@gmail.com\\\";s:4:\\\"sale\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":4:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\Sale\\\";s:2:\\\"id\\\";i:402;s:9:\\\"relations\\\";a:7:{i:0;s:8:\\\"customer\\\";i:1;s:6:\\\"branch\\\";i:2;s:13:\\\"sale_products\\\";i:3;s:21:\\\"sale_products.product\\\";i:4;s:30:\\\"sale_products.product.warranty\\\";i:5;s:21:\\\"sale_products.variant\\\";i:6;s:5:\\\"admin\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";}s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}', 0, NULL, 1629956103, 1629956103),
(6, 'default', '{\"uuid\":\"de347da1-c97c-484e-81c1-1a137af8938b\",\"displayName\":\"App\\\\Jobs\\\\SaleMailJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SaleMailJob\",\"command\":\"O:20:\\\"App\\\\Jobs\\\\SaleMailJob\\\":11:{s:2:\\\"to\\\";s:27:\\\"koalasoftsolution@gmail.com\\\";s:4:\\\"sale\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":4:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\Sale\\\";s:2:\\\"id\\\";i:405;s:9:\\\"relations\\\";a:7:{i:0;s:8:\\\"customer\\\";i:1;s:6:\\\"branch\\\";i:2;s:13:\\\"sale_products\\\";i:3;s:21:\\\"sale_products.product\\\";i:4;s:30:\\\"sale_products.product.warranty\\\";i:5;s:21:\\\"sale_products.variant\\\";i:6;s:5:\\\"admin\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";}s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}', 0, NULL, 1629956205, 1629956205);

-- --------------------------------------------------------

--
-- Table structure for table `memos`
--

CREATE TABLE `memos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `heading` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `admin_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `memo_users`
--

CREATE TABLE `memo_users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `memo_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `is_delete_in_update` tinyint(1) NOT NULL DEFAULT 0,
  `is_author` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(169, '2021_06_22_150310_create_price_groups_table', 83),
(170, '2021_06_23_104749_create_price_group_products_table', 84),
(171, '2021_06_26_131932_add_column_branch_id_from_cash_counters_table', 85),
(172, '2020_11_23_093915_create_supplier_products_table', 86),
(173, '2021_07_04_133321_create_admin_and_user_logs_table', 87),
(174, '2021_07_05_143024_create_workspaces_table', 88),
(175, '2021_07_05_144604_create_workspace_attachments_table', 88),
(176, '2021_07_05_150048_create_workspace_users_table', 89),
(177, '2021_07_05_185346_create_workspace_tasks_table', 90),
(178, '2021_07_07_123257_create_memos_table', 91),
(179, '2021_07_07_123333_create_memo_users_table', 91),
(180, '2021_07_07_184937_create_messages_table', 92),
(181, '2021_07_08_163610_create_todos_table', 93),
(183, '2021_07_08_172517_create_todo_users_table', 94),
(185, '2021_07_10_195215_add_column_branch_id_from_warehouses_table', 95),
(186, '2021_07_11_121103_remove_column_warehouse_id_from_sales_table', 96),
(187, '2020_12_29_092109_create_stock_adjustment_products_table', 97),
(188, '2021_08_12_130532_create_addons_table', 98),
(190, '2021_08_19_115939_create_short_menus_table', 99),
(191, '2021_08_19_144935_create_short_menu_users_table', 99),
(192, '2021_08_21_134927_create_pos_short_menus_table', 100),
(193, '2021_08_21_135048_create_pos_short_menu_users_table', 100),
(194, '2021_08_22_181046_create_jobs_table', 101);

-- --------------------------------------------------------

--
-- Table structure for table `money_receipts`
--

CREATE TABLE `money_receipts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` decimal(22,2) DEFAULT NULL,
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
  `is_header_less` tinyint(1) NOT NULL DEFAULT 0,
  `gap_from_top` bigint(20) DEFAULT NULL,
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

INSERT INTO `money_receipts` (`id`, `invoice_id`, `amount`, `received_amount`, `customer_id`, `branch_id`, `note`, `payment_method`, `status`, `is_amount`, `is_date`, `is_note`, `is_invoice_id`, `is_header_less`, `gap_from_top`, `date`, `month`, `year`, `date_ts`, `created_at`, `updated_at`) VALUES
(58, '15267', '483850.00', '0.00', 69, NULL, NULL, NULL, 'Pending', 0, 1, 0, 1, 0, 0, '25-08-2021', 'August', '2021', '2021-08-24 18:00:00', '2021-08-25 05:31:00', '2021-08-25 05:31:00'),
(62, '17639', '10000.00', '0.00', 68, NULL, NULL, NULL, 'Pending', 0, 1, 0, 1, 0, NULL, '25-08-2021', 'August', '2021', '2021-08-24 18:00:00', '2021-08-25 10:36:51', '2021-08-25 10:36:51'),
(63, '93218', '10000.00', '0.00', 68, NULL, NULL, NULL, 'Pending', 0, 1, 0, 1, 1, 4, '25-08-2021', 'August', '2021', '2021-08-24 18:00:00', '2021-08-25 10:41:23', '2021-08-25 10:41:23'),
(64, '68351', NULL, '0.00', 68, NULL, NULL, NULL, 'Pending', 0, 1, 0, 1, 0, NULL, '25-08-2021', 'August', '2021', '2021-08-24 18:00:00', '2021-08-25 10:44:59', '2021-08-25 11:20:15');

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
-- Table structure for table `pos_short_menus`
--

CREATE TABLE `pos_short_menus` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pos_short_menus`
--

INSERT INTO `pos_short_menus` (`id`, `url`, `name`, `icon`, `created_at`, `updated_at`) VALUES
(1, 'product.categories.index', 'Categories', 'fas fa-th-large', '2021-08-21 09:41:00', '2021-08-21 09:41:00'),
(2, 'product.subcategories.index', 'SubCategories', 'fas fa-code-branch', '2021-08-21 09:41:00', '2021-08-21 09:41:00'),
(3, 'product.brands.index', 'Brands', 'fas fa-band-aid', '2021-08-21 09:41:00', '2021-08-21 09:41:00'),
(4, 'products.all.product', 'Product List', 'fas fa-sitemap', '2021-08-21 09:41:00', '2021-08-21 09:41:00'),
(5, 'products.add.view', 'Add Product', 'fas fa-plus-circle', '2021-08-21 09:41:00', '2021-08-21 09:41:00'),
(6, 'product.variants.index', 'Variants', 'fas fa-align-center', '2021-08-21 09:41:00', '2021-08-21 09:41:00'),
(7, 'product.import.create', 'Import Products', 'fas fa-file-import', '2021-08-21 09:41:00', '2021-08-21 09:41:00'),
(8, 'product.selling.price.groups.index', 'Price Group', 'fas fa-layer-group', '2021-08-21 09:41:00', '2021-08-21 09:41:00'),
(9, 'barcode.index', 'G.Barcode', 'fas fa-barcode', '2021-08-21 09:41:00', '2021-08-21 09:41:00'),
(10, 'product.warranties.index', 'Warranties ', 'fas fa-shield-alt', '2021-08-21 09:41:00', '2021-08-21 09:41:00'),
(11, 'contacts.supplier.index', 'Suppliers', 'fas fa-address-card', '2021-08-21 09:41:00', '2021-08-21 09:41:00'),
(12, 'contacts.suppliers.import.create', 'Import Suppliers', 'fas fa-file-import', '2021-08-21 09:41:00', '2021-08-21 09:41:00'),
(13, 'contacts.customer.index', 'Customers', 'far fa-address-card', '2021-08-21 09:41:00', '2021-08-21 09:41:00'),
(14, 'contacts.customers.import.create', 'Import Customers', 'fas fa-file-upload', '2021-08-21 09:41:00', '2021-08-21 09:41:00'),
(15, 'purchases.create', 'Add Purchase', 'fas fa-shopping-cart', '2021-08-21 09:41:00', '2021-08-21 09:41:00'),
(16, 'purchases.index_v2', 'Purchase List', 'fas fa-list', '2021-08-21 09:41:00', '2021-08-21 09:41:00'),
(17, 'purchases.returns.index', 'Purchase Return', 'fas fa-undo', '2021-08-21 09:41:00', '2021-08-21 09:41:00'),
(18, 'sales.store', 'Add Sale', 'fas fa-cart-plus', '2021-08-21 09:41:00', '2021-08-21 09:41:00'),
(19, 'sales.index2', 'Add Sale List', 'fas fa-tasks', '2021-08-21 09:41:00', '2021-08-21 09:41:00'),
(20, 'sales.pos.create', 'POS', 'fas fa-cash-register', '2021-08-21 09:41:00', '2021-08-21 09:41:00'),
(21, 'sales.pos.list', 'POS List', 'fas fa-tasks', '2021-08-21 09:41:00', '2021-08-21 09:41:00'),
(22, 'sales.drafts', 'Draft List', 'fas fa-drafting-compass', '2021-08-21 09:41:00', '2021-08-21 09:41:00'),
(23, 'sales.quotations', 'Quotation List', 'fas fa-quote-right', '2021-08-21 09:41:00', '2021-08-21 09:41:00'),
(24, 'sales.returns.index', 'Sale Returns', 'fas fa-undo', '2021-08-21 09:41:00', '2021-08-21 09:41:00'),
(25, 'sales.shipments', 'Shipments', 'fas fa-shipping-fast', '2021-08-21 09:41:00', '2021-08-21 09:41:00'),
(26, 'expanses.create', 'Add Expense', 'fas fa-plus-square', '2021-08-21 09:41:00', '2021-08-21 09:41:00'),
(27, 'expanses.index', 'Expense List', 'far fa-list-alt', '2021-08-21 09:41:00', '2021-08-21 09:41:00'),
(28, 'expanses.categories.index', 'Expense Categories Categories', 'fas fa-cubes', '2021-08-21 09:41:00', '2021-08-21 09:41:00'),
(29, 'users.create', 'Add User', 'fas fa-user-plus', '2021-08-21 09:41:00', '2021-08-21 09:41:00'),
(30, 'users.index', 'User List', 'fas fa-list-ol', '2021-08-21 09:41:00', '2021-08-21 09:41:00'),
(31, 'users.role.create', 'Add Role', 'fas fa-plus-circle', '2021-08-21 09:41:00', '2021-08-21 09:41:00'),
(32, 'users.role.index', 'Role List', 'fas fa-th-list', '2021-08-21 09:41:00', '2021-08-21 09:41:00'),
(33, 'accounting.banks.index', 'Bank', 'fas fa-university', '2021-08-21 09:41:00', '2021-08-21 09:41:00'),
(34, 'accounting.types.index', 'Account Types', 'fas fa-th', '2021-08-21 09:41:00', '2021-08-21 09:41:00'),
(35, 'accounting.accounts.index', 'Accounts', 'fas fa-th', '2021-08-21 09:41:00', '2021-08-21 09:41:00'),
(36, 'accounting.assets.index', 'Assets', 'fas fa-luggage-cart', '2021-08-21 09:41:00', '2021-08-21 09:41:00'),
(37, 'accounting.balance.sheet', 'Balance Sheet', 'fas fa-balance-scale', '2021-08-21 09:41:00', '2021-08-21 09:41:00'),
(38, 'accounting.trial.balance', 'Trial Balance', 'fas fa-balance-scale-right', '2021-08-21 09:41:00', '2021-08-21 09:41:00'),
(39, 'accounting.cash.flow', 'Cash Flow', 'fas fa-money-bill-wave', '2021-08-21 09:41:00', '2021-08-21 09:41:00'),
(40, 'settings.general.index', 'General Settings', 'fas fa-cogs', '2021-08-21 09:41:00', '2021-08-21 09:41:00'),
(41, 'settings.taxes.index', 'Taxes', 'fas fa-percentage', '2021-08-21 09:41:00', '2021-08-21 09:41:00'),
(42, 'invoices.schemas.index', 'Invoice Schemas', 'fas fa-file-invoice-dollar', '2021-08-21 09:41:00', '2021-08-21 09:41:00'),
(43, 'invoices.layouts.index', 'Invoice Layouts', 'fas fa-file-invoice', '2021-08-21 09:41:00', '2021-08-21 09:41:00'),
(44, 'settings.barcode.index', 'Barcode Settings', 'fas fa-barcode', '2021-08-21 09:41:00', '2021-08-21 09:41:00'),
(45, 'settings.cash.counter.index', 'Cash Counter', 'fas fa-store', '2021-08-21 09:41:00', '2021-08-21 09:41:00');

-- --------------------------------------------------------

--
-- Table structure for table `pos_short_menu_users`
--

CREATE TABLE `pos_short_menu_users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `short_menu_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_delete_in_update` tinyint(1) NOT NULL DEFAULT 0,
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
(6, 'Wholesale Price', 'Only For wholesale.', 'Active', NULL, '2021-08-18 10:03:35'),
(7, 'Retail Price', 'Only for retail sale.', 'Active', NULL, '2021-08-18 10:03:36'),
(8, 'Special Price', NULL, 'Active', NULL, '2021-08-18 10:03:36'),
(9, 'Special Customer Price', NULL, 'Active', NULL, '2021-08-18 10:03:38');

-- --------------------------------------------------------

--
-- Table structure for table `price_group_products`
--

CREATE TABLE `price_group_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `price_group_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `variant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `price` decimal(22,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `tax_type` tinyint(4) NOT NULL DEFAULT 1,
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
  `mb_stock` decimal(22,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `type`, `name`, `product_code`, `category_id`, `parent_category_id`, `brand_id`, `unit_id`, `tax_id`, `tax_type`, `warranty_id`, `product_cost`, `product_cost_with_tax`, `profit`, `product_price`, `offer_price`, `quantity`, `combo_price`, `alert_quantity`, `is_featured`, `is_combo`, `is_variant`, `is_show_in_ecom`, `is_show_emi_on_pos`, `is_for_sale`, `attachment`, `thumbnail_photo`, `expire_date`, `product_details`, `is_purchased`, `barcode_type`, `weight`, `product_condition`, `status`, `number_of_sale`, `total_transfered`, `total_adjusted`, `custom_field_1`, `custom_field_2`, `custom_field_3`, `mb_stock`, `created_at`, `updated_at`) VALUES
(207, 1, 'Zebra zc-300, Dual Side ID card printer', '72132193', 82, NULL, 32, 3, NULL, 2, 20, '79000.00', '79000.00', '0.00', '79000.00', '0.00', '2.00', '0.00', 0, 0, 0, 0, 0, 1, 1, NULL, 'default.png', NULL, NULL, '0', 'CODE128', NULL, 'New', 1, '8.00', '0.00', '1.00', NULL, NULL, NULL, '0.00', '2021-07-18 05:08:24', '2021-08-22 09:29:54'),
(211, 1, 'Predator Notebook Gaming Utility Backpack', '33895725', 72, NULL, NULL, 3, NULL, 1, NULL, '7000.00', '7000.00', '0.00', '7000.00', '0.00', '8.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'default.png', NULL, NULL, '1', 'CODE128', NULL, 'New', 1, '3.00', '0.00', '0.00', NULL, NULL, NULL, '7.00', '2021-08-12 05:25:51', '2021-08-23 07:48:44'),
(212, 1, 'kaspersky antivirus security', '59369213', 73, NULL, 29, 3, 5, 1, NULL, '1000.00', '1500.00', '0.00', '1000.00', '0.00', '2.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'default.png', NULL, NULL, '0', 'CODE128', NULL, 'New', 1, '8.00', '0.00', '0.00', NULL, NULL, NULL, '2.00', '2021-08-12 05:28:07', '2021-08-24 05:15:00'),
(215, 1, 'Genuine Microsoft 2020/2021 pro version - 1 EA', '85395584', 73, NULL, 31, 3, NULL, 1, NULL, '11150.00', '11150.00', '0.00', '11150.00', '0.00', '0.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'default.png', NULL, NULL, '0', 'CODE128', NULL, 'New', 1, '10.00', '0.00', '0.00', NULL, NULL, NULL, '0.00', '2021-08-12 05:38:37', '2021-08-22 11:42:20'),
(216, 1, 'HP Elitebook AMD Ryzan 3 processor, Ram -8GB, SSD -256GB', '74427982', NULL, NULL, NULL, 3, 2, 1, 19, '59500.00', '65450.00', '0.00', '59500.00', '0.00', '15.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'default.png', NULL, NULL, '0', 'CODE128', NULL, 'New', 1, '15.00', '0.00', '0.00', NULL, NULL, NULL, '15.00', '2021-08-12 07:26:19', '2021-08-26 11:27:51'),
(218, 1, 'dd', '21516427', NULL, NULL, NULL, 3, 1, 1, NULL, '100.00', '105.00', '2.00', '102.00', '0.00', '957.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'default.png', NULL, NULL, '0', 'CODE128', NULL, 'New', 1, '43.00', '0.00', '0.00', NULL, NULL, NULL, '957.00', '2021-08-18 07:01:12', '2021-08-26 05:54:31'),
(219, 2, 'Combo-1', '87819368', 73, NULL, NULL, 3, NULL, 1, NULL, '0.00', '0.00', '0.00', '55000.00', '0.00', '0.00', '55000.00', 0, 0, 1, 0, 0, 0, 1, NULL, 'default.png', NULL, NULL, '0', 'CODE128', NULL, 'New', 1, '0.00', '0.00', '0.00', NULL, NULL, NULL, '0.00', '2021-08-22 04:54:26', '2021-08-22 04:54:26'),
(220, 1, 'Zebra Card Printer Ribbons For ZC100/300 Series', '56273373', 82, NULL, 32, 3, NULL, 1, NULL, '2850.00', '2850.00', '0.00', '2850.00', '0.00', '92.00', '0.00', 0, 0, 0, 0, 0, 1, 1, NULL, 'default.png', NULL, NULL, '0', 'CODE128', NULL, 'New', 1, '8.00', '0.00', '0.00', NULL, NULL, NULL, '92.00', '2021-08-23 11:59:50', '2021-08-26 05:38:17');

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
(34, NULL, '5fb224331fd5d.png', '2020-11-16 01:03:15', '2020-11-16 01:03:15');

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
  `unit_cost_inc_tax` decimal(22,2) NOT NULL DEFAULT 0.00,
  `quantity` decimal(22,2) NOT NULL DEFAULT 0.00,
  `subtotal` decimal(22,2) NOT NULL DEFAULT 0.00,
  `lot_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_opening_stocks`
--

INSERT INTO `product_opening_stocks` (`id`, `branch_id`, `warehouse_id`, `product_id`, `product_variant_id`, `unit_cost_inc_tax`, `quantity`, `subtotal`, `lot_no`, `created_at`, `updated_at`) VALUES
(205, NULL, NULL, 215, NULL, '11150.00', '10.00', '111500.00', NULL, '2021-08-12 05:42:20', '2021-08-12 05:42:20'),
(208, NULL, NULL, 211, NULL, '7000.00', '10.00', '70000.00', NULL, '2021-08-12 05:43:11', '2021-08-12 05:43:11'),
(210, NULL, NULL, 212, NULL, '1000.00', '10.00', '10000.00', NULL, '2021-08-12 05:52:58', '2021-08-12 05:52:58'),
(211, NULL, NULL, 216, NULL, '59500.00', '20.00', '1190000.00', NULL, '2021-08-12 07:26:55', '2021-08-12 10:52:39'),
(213, NULL, NULL, 218, NULL, '100.00', '1000.00', '100000.00', NULL, '2021-08-18 07:23:11', '2021-08-18 07:23:11'),
(214, NULL, NULL, 220, NULL, '2850.00', '100.00', '285000.00', NULL, '2021-08-23 12:00:36', '2021-08-23 12:00:36');

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
  `mb_stock` decimal(22,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(88, 17, 207, '2.00', '2021-07-18 10:20:19', '2021-08-17 06:36:19'),
(90, 17, 216, '0.00', '2021-08-14 11:45:41', '2021-08-24 05:28:12'),
(92, 17, 211, '1.00', '2021-08-14 11:45:41', '2021-08-14 11:45:41');

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
(169, 'PI210718223541', NULL, NULL, 37, NULL, NULL, 1, '125714.30', '0.00', 1, '0.00', NULL, '0.00', NULL, NULL, '0.00', '6285.72', '132000.02', '132000.02', '0.00', '0.00', '0.00', NULL, 2, 1, '2021-07-18', '11:09:27 am', '2021-07-17 18:00:00', 'July', '2021', 0, 0, NULL, '2021-07-18 05:09:27', '2021-08-14 11:45:41'),
(170, 'PI210814958582', 17, NULL, 37, NULL, NULL, 4, '1116000.00', '0.00', 1, '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '1116000.00', '1116000.00', '0.00', '0.00', '0.00', NULL, 2, 1, '2021-08-14', '05:45:41 pm', '2021-08-13 18:00:00', 'August', '2021', 0, 0, NULL, '2021-08-14 11:45:41', '2021-08-17 06:36:19'),
(171, 'PI210817622324', 17, NULL, 37, NULL, NULL, 1, '12571.43', '0.00', 1, '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '12571.43', '12571.43', '0.00', '0.00', '0.00', NULL, 2, 1, '2021-08-17', '12:36:19 pm', '2021-08-16 18:00:00', 'August', '2021', 1, 0, NULL, '2021-08-17 06:36:19', '2021-08-17 06:36:19');

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
(164, 'PPI210718223541', 169, 37, NULL, 'Cash', '132000.02', 1, 1, NULL, '2021-07-18', NULL, 'July', '2021', '2021-07-17 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, '2021-07-18 05:09:27', '2021-07-18 10:37:21'),
(166, 'PPI21081786268', 170, 37, NULL, 'Cash', '1116000.00', 1, 1, NULL, '2021-08-17', '10:35:36 am', 'August', '2021', '2021-08-16 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, '2021-08-17 04:35:36', '2021-08-17 04:35:36'),
(167, 'PPI210817622324', 171, 37, NULL, 'Cash', '12571.43', 1, 1, NULL, '2021-08-17', NULL, 'August', '2021', '2021-08-16 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, '2021-08-17 06:36:19', '2021-08-17 06:36:19');

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
(367, 169, 207, NULL, '10.00', 'Piece', '12000.00', '0.00', '12000.00', '120000.00', '5.00', '600.00', '12571.43', '125714.30', '0.00', '0.00', 0, NULL, 0, '2021-07-18 05:09:27', '2021-07-18 05:09:27'),
(369, 170, 216, NULL, '10.00', 'Piece', '59500.00', '0.00', '59500.00', '595000.00', '0.00', '0.00', '59500.00', '595000.00', '0.00', '0.00', 0, NULL, 0, '2021-08-14 11:45:41', '2021-08-14 11:45:41'),
(371, 170, 211, NULL, '1.00', 'Piece', '7000.00', '0.00', '7000.00', '7000.00', '0.00', '0.00', '7000.00', '7000.00', '0.00', '0.00', 0, NULL, 0, '2021-08-14 11:45:41', '2021-08-14 11:45:41'),
(372, 171, 207, NULL, '1.00', 'Piece', '12000.00', '0.00', '12000.00', '12000.00', '5.00', '600.00', '12571.43', '12571.43', '0.00', '0.00', 0, NULL, 0, '2021-08-17 06:36:19', '2021-08-17 06:36:19');

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
(8, NULL, '{\"user_view\":1,\"user_add\":1,\"user_edit\":1,\"user_delete\":1}', '{\"role_view\":1,\"role_add\":1,\"role_edit\":1,\"role_delete\":1}', '{\"supplier_all\":1,\"supplier_add\":1,\"supplier_edit\":1,\"supplier_delete\":1}', '{\"customer_all\":1,\"customer_add\":1,\"customer_edit\":1,\"customer_delete\":1}', '{\"product_all\":1,\"product_add\":1,\"product_edit\":1,\"openingStock_add\":1,\"product_delete\":1,\"pro_unit_cost\":1}', '{\"purchase_all\":1,\"purchase_add\":1,\"purchase_edit\":1,\"purchase_delete\":1,\"purchase_payment\":1,\"purchase_return\":1,\"status_update\":1}', '{\"adjustment_all\":1,\"adjustment_add\":1,\"adjustment_delete\":1}', '{\"pos_all\":1,\"pos_add\":1,\"pos_edit\":1,\"pos_delete\":1,\"sale_access\":1,\"sale_draft\":1,\"sale_quotation\":1,\"sale_all_own\":1,\"sale_payment\":1,\"edit_price_sale_screen\":1,\"edit_price_pos_screen\":1,\"edit_discount_pos_screen\":1,\"edit_discount_sale_screen\":1,\"shipment_access\":1,\"return_access\":1}', '{\"register_view\":1,\"register_close\":1}', '{\"brand_all\":1,\"brand_add\":1,\"brand_edit\":1,\"brand_delete\":1}', '{\"category_all\":1,\"category_add\":1,\"category_edit\":1,\"category_delete\":1}', '{\"unit_all\":1,\"unit_add\":1,\"unit_edit\":1,\"unit_delete\":1}', '{\"loss_profit_report\":1,\"purchase_sale_report\":1,\"tax_report\":1,\"cus_sup_report\":1,\"stock_report\":1,\"stock_adjustment_report\":1,\"tranding_report\":1,\"item_report\":1,\"pro_purchase_report\":1,\"pro_sale_report\":1,\"purchase_payment_report\":1,\"sale_payment_report\":1,\"expanse_report\":1,\"register_report\":1,\"representative_report\":1}', '{\"tax\":1,\"branch\":1,\"warehouse\":0,\"g_settings\":1,\"p_settings\":1,\"inv_sc\":1,\"inv_lay\":1,\"barcode_settings\":1,\"cash_counters\":1}', '{\"dash_data\":1}', '{\"ac_access\":1}', '{\"leave_type\":1,\"view_own_leave\":1,\"leave_approve\":1,\"attendance_all\":1,\"view_own_attendance\":1,\"view_a_d\":1,\"department\":1,\"designation\":1}', '{\"assign_todo\":1,\"create_msg\":1,\"view_msg\":1}', '{\"menuf_view\":1,\"menuf_add\":1,\"menuf_edit\":1,\"menuf_delete\":1}', '{\"proj_view\":1,\"proj_create\":1,\"proj_edit\":1,\"proj_delete\":1}', '{\"ripe_add_invo\":1,\"ripe_edit_invo\":1,\"ripe_view_invo\":1,\"ripe_delete_invo\":1,\"change_invo_status\":1,\"ripe_jop_sheet_status\":1,\"ripe_jop_sheet_add\":1,\"ripe_jop_sheet_edit\":1,\"ripe_jop_sheet_delete\":1,\"ripe_only_assinged_job_sheet\":1,\"ripe_view_all_job_sheet\":1}', '{\"superadmin_access_pack_subscrip\":1}', '{\"e_com_sync_pro_cate\":1,\"e_com_sync_pro\":1,\"e_com_sync_order\":1,\"e_com_map_tax_rate\":1}', 1, '2021-01-26 10:45:14', '2021-01-26 10:45:14');

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_id` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
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

INSERT INTO `sales` (`id`, `invoice_id`, `branch_id`, `customer_id`, `pay_term`, `pay_term_number`, `total_item`, `net_total_amount`, `order_discount_type`, `order_discount`, `order_discount_amount`, `shipment_details`, `shipment_address`, `shipment_charge`, `shipment_status`, `delivered_to`, `sale_note`, `order_tax_percent`, `order_tax_amount`, `total_payable_amount`, `paid`, `change_amount`, `due`, `is_return_available`, `ex_status`, `sale_return_amount`, `sale_return_due`, `payment_note`, `admin_id`, `status`, `is_fixed_challen`, `date`, `time`, `report_date`, `month`, `year`, `attachment`, `created_by`, `created_at`, `updated_at`) VALUES
(337, '2021/8282', NULL, NULL, NULL, NULL, 1, '13200.00', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '13200.00', '0.00', '0.00', '13200.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '2021-07-18', '11:10:36 am', '2021-07-17 18:00:00', 'July', '2021', NULL, 1, '2021-07-18 05:10:36', '2021-08-14 06:53:16'),
(338, '2021/5136', NULL, 68, NULL, NULL, 1, '13200.00', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '13200.00', '13200.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 12, 1, 1, '2021-07-18', '11:33:43 am', '2021-07-17 18:00:00', 'July', '2021', NULL, 1, '2021-07-18 05:33:43', '2021-08-18 12:23:00'),
(339, '2021/9312', NULL, NULL, NULL, NULL, 1, '26400.00', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '26400.00', '26400.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 12, 1, 1, '18-07-2021', '11:34:39 am', '2021-07-18 05:34:39', 'July', '2021', NULL, 2, '2021-07-18 05:34:39', '2021-07-18 05:34:39'),
(340, '2021/2385', NULL, 68, NULL, NULL, 1, '13200.00', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '13200.00', '13200.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '2021-07-18', '04:23:54 pm', '2021-07-17 18:00:00', 'July', '2021', NULL, 1, '2021-07-18 10:23:54', '2021-08-18 12:23:00'),
(341, '2021/8627', NULL, 68, NULL, NULL, 1, '13200.00', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '13200.00', '13200.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '18-07-2021', '06:09:36 pm', '2021-07-18 00:09:36', 'July', '2021', NULL, 2, '2021-07-18 12:09:36', '2021-08-18 12:23:00'),
(343, '20218216', NULL, 69, NULL, NULL, 6, '278800.00', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '278800.00', '0.00', '0.00', '278800.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '2021-08-12', '11:54:12 am', '2021-08-11 18:00:00', 'August', '2021', NULL, 1, '2021-08-12 05:54:12', '2021-08-14 06:52:54'),
(344, '20216782', NULL, 69, NULL, NULL, 1, '59500.00', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '59500.00', '0.00', '0.00', '59500.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '2021-08-12', '01:27:30 pm', '2021-08-11 18:00:00', 'August', '2021', NULL, 1, '2021-08-12 07:27:30', '2021-08-14 06:52:43'),
(345, '20212131', NULL, 69, NULL, NULL, 1, '58000.00', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '58000.00', '0.00', '0.00', '58000.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '2021-08-14', '10:58:13 am', '2021-08-13 18:00:00', 'August', '2021', NULL, 1, '2021-08-14 04:58:13', '2021-08-16 09:44:08'),
(347, '20217324', NULL, NULL, NULL, NULL, 9, '364850.00', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '364850.00', '364850.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '16-08-2021', '02:04:09 pm', '2021-08-15 20:04:09', 'August', '2021', NULL, 2, '2021-08-16 08:04:09', '2021-08-16 08:04:09'),
(348, '20219596', NULL, 68, NULL, NULL, 1, '215000.00', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '215000.00', '215000.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '2021-08-17', '03:02:15 pm', '2021-08-16 18:00:00', 'August', '2021', NULL, 1, '2021-08-17 09:02:15', '2021-08-18 12:23:00'),
(349, '2021/19999', NULL, 68, NULL, NULL, 1, '215000.00', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '215000.00', '215000.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '2021-08-17', '04:34:17 pm', '2021-08-16 18:00:00', 'August', '2021', NULL, 1, '2021-08-17 10:34:17', '2021-08-18 12:23:00'),
(350, '20211741', NULL, 68, NULL, NULL, 1, '59500.00', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '59500.00', '59500.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '18-08-2021', '01:20:24 pm', '2021-08-17 19:20:24', 'August', '2021', NULL, 2, '2021-08-18 07:20:24', '2021-08-18 12:23:00'),
(351, '20211797', NULL, 68, NULL, NULL, 1, '100.00', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '100.00', '100.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '18-08-2021', '01:23:20 pm', '2021-08-17 19:23:20', 'August', '2021', NULL, 2, '2021-08-18 07:23:20', '2021-08-18 12:23:00'),
(352, '20218471', NULL, 68, NULL, NULL, 1, '100.00', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '100.00', '100.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '18-08-2021', '01:29:35 pm', '2021-08-17 19:29:35', 'August', '2021', NULL, 2, '2021-08-18 07:29:35', '2021-08-18 12:23:00'),
(353, '20217616', NULL, 68, NULL, NULL, 1, '100.00', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '100.00', '100.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '18-08-2021', '01:29:44 pm', '2021-08-17 19:29:44', 'August', '2021', NULL, 2, '2021-08-18 07:29:44', '2021-08-18 12:23:00'),
(354, '20217841', NULL, 68, NULL, NULL, 1, '100.00', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '100.00', '100.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '18-08-2021', '01:30:10 pm', '2021-08-17 19:30:10', 'August', '2021', NULL, 2, '2021-08-18 07:30:10', '2021-08-18 12:23:00'),
(355, '20217371', NULL, 68, NULL, NULL, 1, '100.00', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '100.00', '100.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '18-08-2021', '01:35:05 pm', '2021-08-17 19:35:05', 'August', '2021', NULL, 2, '2021-08-18 07:35:05', '2021-08-18 12:23:00'),
(356, '20211781', NULL, 68, NULL, NULL, 1, '59500.00', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '59500.00', '59500.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '2021-08-18', '02:45:51 pm', '2021-08-17 18:00:00', 'August', '2021', NULL, 1, '2021-08-18 08:45:51', '2021-08-18 12:23:00'),
(357, '20216387', NULL, 68, NULL, NULL, 1, '59500.00', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '59500.00', '59500.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '2021-08-18', '02:46:18 pm', '2021-08-17 18:00:00', 'August', '2021', NULL, 1, '2021-08-18 08:46:18', '2021-08-18 12:23:00'),
(358, '20212512', NULL, 68, NULL, NULL, 1, '59500.00', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '59500.00', '59500.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '2021-08-18', '02:46:56 pm', '2021-08-17 18:00:00', 'August', '2021', NULL, 1, '2021-08-18 08:46:56', '2021-08-18 12:23:00'),
(359, '20212615', NULL, 68, NULL, NULL, 1, '100.00', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '100.00', '100.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '2021-08-18', '02:50:48 pm', '2021-08-17 18:00:00', 'August', '2021', NULL, 1, '2021-08-18 08:50:48', '2021-08-18 12:23:00'),
(360, '20213813', NULL, 68, NULL, NULL, 1, '100.00', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '100.00', '100.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '2021-08-18', '03:01:26 pm', '2021-08-17 18:00:00', 'August', '2021', NULL, 1, '2021-08-18 09:01:26', '2021-08-18 12:23:00'),
(361, '20211448', NULL, 68, NULL, NULL, 1, '59500.00', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '59500.00', '59500.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '2021-08-18', '06:23:00 pm', '2021-08-17 18:00:00', 'August', '2021', NULL, 1, '2021-08-18 12:23:00', '2021-08-18 12:23:00'),
(362, '20218575', NULL, NULL, NULL, NULL, 1, '59500.00', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '59500.00', '59500.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '2021-08-19', '10:20:28 am', '2021-08-18 18:00:00', 'August', '2021', NULL, 1, '2021-08-19 04:20:28', '2021-08-19 04:20:28'),
(363, '20217169', NULL, 68, NULL, NULL, 1, '100.00', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '100.00', '100.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '2021-08-20', '12:40:14 pm', '2021-08-19 18:00:00', 'August', '2021', NULL, 1, '2021-08-21 06:40:14', '2021-08-22 10:02:05'),
(364, '20216199', NULL, 68, NULL, NULL, 1, '100.00', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '100.00', '100.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '2021-08-21', '12:40:32 pm', '2021-08-20 18:00:00', 'August', '2021', NULL, 1, '2021-08-21 06:40:32', '2021-08-22 10:02:05'),
(365, '20214514', NULL, 68, NULL, NULL, 3, '70750.00', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '70750.00', '70750.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '21-08-2021', '12:43:43 pm', '2021-08-21 06:43:43', 'August', '2021', NULL, 2, '2021-08-21 06:43:43', '2021-08-22 10:03:55'),
(366, '20216281', NULL, 68, NULL, NULL, 2, '11250.00', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '11250.00', '11250.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '21-08-2021', '01:00:52 pm', '2021-08-20 18:00:00', 'August', '2021', NULL, 2, '2021-08-21 07:00:52', '2021-08-22 10:02:05'),
(367, '20216232', NULL, NULL, NULL, NULL, 1, '100.00', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '100.00', '100.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '2021-08-22', '12:47:27 pm', '2021-08-21 18:00:00', 'August', '2021', NULL, 1, '2021-08-22 06:47:27', '2021-08-22 06:47:27'),
(368, '20215191', NULL, NULL, NULL, NULL, 1, '100.00', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '100.00', '100.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '2021-08-22', '12:50:13 pm', '2021-08-21 18:00:00', 'August', '2021', NULL, 1, '2021-08-22 06:50:13', '2021-08-22 06:50:13'),
(369, '20212967', NULL, NULL, NULL, NULL, 1, '100.00', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '100.00', '100.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '2021-08-22', '12:54:03 pm', '2021-08-21 18:00:00', 'August', '2021', NULL, 1, '2021-08-22 06:54:03', '2021-08-22 06:54:03'),
(370, '20218595', NULL, NULL, NULL, NULL, 1, '100.00', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '100.00', '100.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '2021-08-22', '12:56:49 pm', '2021-08-21 18:00:00', 'August', '2021', NULL, 1, '2021-08-22 06:56:49', '2021-08-22 06:56:49'),
(371, '20212235', NULL, NULL, NULL, NULL, 1, '100.00', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '100.00', '100.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '2021-08-22', '12:57:44 pm', '2021-08-21 18:00:00', 'August', '2021', NULL, 1, '2021-08-22 06:57:44', '2021-08-22 06:57:44'),
(372, '20219561', NULL, 69, NULL, NULL, 1, '79000.00', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '79000.00', '0.00', '0.00', '79000.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '2021-08-22', '03:05:19 pm', '2021-08-21 18:00:00', 'August', '2021', NULL, 1, '2021-08-22 09:05:19', '2021-08-22 09:05:19'),
(373, '20219259', NULL, 68, NULL, NULL, 1, '100.00', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '100.00', '100.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '2021-08-22', '03:41:44 pm', '2021-08-21 18:00:00', 'August', '2021', NULL, 1, '2021-08-22 09:41:44', '2021-08-22 09:41:44'),
(374, '20216673', NULL, 68, NULL, NULL, 4, '71750.00', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '71750.00', '71750.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '2021-08-22', '04:00:20 pm', '2021-08-21 18:00:00', 'August', '2021', NULL, 1, '2021-08-22 10:00:20', '2021-08-22 10:00:20'),
(375, '20211941', NULL, 68, NULL, NULL, 4, '71750.00', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '71750.00', '71750.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '2021-08-22', '04:02:04 pm', '2021-08-21 18:00:00', 'August', '2021', NULL, 1, '2021-08-22 10:02:04', '2021-08-22 10:02:04'),
(376, '20213972', NULL, 68, NULL, NULL, 3, '12250.00', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '12250.00', '12250.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '2021-08-22', '04:03:55 pm', '2021-08-21 18:00:00', 'August', '2021', NULL, 1, '2021-08-22 10:03:55', '2021-08-22 10:03:55'),
(377, '20218998', NULL, 68, NULL, NULL, 3, '12250.00', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '12250.00', '12250.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '2021-08-22', '04:06:34 pm', '2021-08-21 18:00:00', 'August', '2021', NULL, 1, '2021-08-22 10:06:34', '2021-08-22 10:06:34'),
(378, '20217779', NULL, 68, NULL, NULL, 3, '12250.00', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '12250.00', '12250.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '2021-08-22', '04:11:48 pm', '2021-08-21 18:00:00', 'August', '2021', NULL, 1, '2021-08-22 10:11:48', '2021-08-25 05:24:30'),
(379, '20217167', NULL, 68, NULL, NULL, 1, '100.00', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '100.00', '100.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '2021-08-22', '05:41:33 pm', '2021-08-21 18:00:00', 'August', '2021', NULL, 1, '2021-08-22 11:41:33', '2021-08-25 05:24:30'),
(380, '20211466', NULL, 68, NULL, NULL, 1, '11150.00', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '11150.00', '11150.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '2021-08-22', '05:42:19 pm', '2021-08-21 18:00:00', 'August', '2021', NULL, 1, '2021-08-22 11:42:19', '2021-08-25 05:24:30'),
(381, '20218249', NULL, 68, NULL, NULL, 1, '100.00', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '100.00', '100.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '2021-08-22', '05:42:50 pm', '2021-08-21 18:00:00', 'August', '2021', NULL, 1, '2021-08-22 11:42:50', '2021-08-25 05:24:30'),
(382, '20217456', NULL, 68, NULL, NULL, 1, '100.00', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '100.00', '100.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '2021-08-23', '10:53:27 am', '2021-08-22 18:00:00', 'August', '2021', NULL, 1, '2021-08-23 04:53:27', '2021-08-25 05:24:30'),
(383, '20219972', NULL, 68, NULL, NULL, 1, '100.00', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '100.00', '100.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '2021-08-23', '12:43:24 pm', '2021-08-22 18:00:00', 'August', '2021', NULL, 1, '2021-08-23 06:43:24', '2021-08-25 05:24:30'),
(384, '20215427', NULL, 68, NULL, NULL, 1, '100.00', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '100.00', '100.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '2021-08-23', '12:43:49 pm', '2021-08-22 18:00:00', 'August', '2021', NULL, 1, '2021-08-23 06:43:49', '2021-08-25 05:24:30'),
(385, '20212347', NULL, 68, NULL, NULL, 1, '100.00', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '100.00', '100.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '2021-08-23', '12:44:33 pm', '2021-08-22 18:00:00', 'August', '2021', NULL, 1, '2021-08-23 06:44:33', '2021-08-25 05:24:30'),
(386, '20212189', NULL, 68, NULL, NULL, 1, '100.00', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '100.00', '100.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '2021-08-23', '12:51:20 pm', '2021-08-22 18:00:00', 'August', '2021', NULL, 1, '2021-08-23 06:51:20', '2021-08-25 05:24:30'),
(387, '20211117', NULL, 68, NULL, NULL, 1, '100.00', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '100.00', '100.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '2021-08-23', '12:53:15 pm', '2021-08-22 18:00:00', 'August', '2021', NULL, 1, '2021-08-23 06:53:15', '2021-08-25 05:24:30'),
(388, '20219891', NULL, 68, NULL, NULL, 1, '100.00', 1, '0.00', '0.00', NULL, NULL, '0.00', 1, NULL, NULL, '0.00', '0.00', '100.00', '100.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '2021-08-23', '12:57:02 pm', '2021-08-22 18:00:00', 'August', '2021', NULL, 1, '2021-08-23 06:57:02', '2021-08-25 05:24:30'),
(389, '20215313', NULL, 68, NULL, NULL, 1, '100.00', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '100.00', '100.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '2021-08-23', '01:00:05 pm', '2021-08-22 18:00:00', 'August', '2021', NULL, 1, '2021-08-23 07:00:05', '2021-08-25 05:24:30'),
(390, '20215121', NULL, 68, NULL, NULL, 1, '100.00', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '100.00', '100.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '2021-08-23', '01:00:25 pm', '2021-08-22 18:00:00', 'August', '2021', NULL, 1, '2021-08-23 07:00:25', '2021-08-25 05:24:30'),
(391, '20213485', NULL, 68, NULL, NULL, 1, '100.00', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '100.00', '100.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '2021-08-23', '01:00:59 pm', '2021-08-22 18:00:00', 'August', '2021', NULL, 1, '2021-08-23 07:00:59', '2021-08-25 05:24:30'),
(392, '20213423', NULL, 68, NULL, NULL, 1, '100.00', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '100.00', '100.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '2021-08-23', '01:02:07 pm', '2021-08-22 18:00:00', 'August', '2021', NULL, 1, '2021-08-23 07:02:07', '2021-08-25 05:24:30'),
(393, '20216415', NULL, 68, NULL, NULL, 1, '100.00', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '100.00', '100.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '2021-08-23', '01:17:20 pm', '2021-08-22 18:00:00', 'August', '2021', NULL, 1, '2021-08-23 07:17:20', '2021-08-25 05:24:30'),
(394, '20213174', NULL, 68, NULL, NULL, 1, '100.00', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '100.00', '100.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '2021-08-23', '01:18:46 pm', '2021-08-22 18:00:00', 'August', '2021', NULL, 1, '2021-08-23 07:18:46', '2021-08-25 05:24:30'),
(395, '20214669', NULL, 68, NULL, NULL, 1, '100.00', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '100.00', '100.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '2021-08-23', '01:21:01 pm', '2021-08-22 18:00:00', 'August', '2021', NULL, 1, '2021-08-23 07:21:01', '2021-08-25 05:24:30'),
(396, '20219565', NULL, 68, NULL, NULL, 2, '119100.00', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '119100.00', '119100.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '23-08-2021', '01:48:11 pm', '2021-08-22 18:00:00', 'August', '2021', NULL, 2, '2021-08-23 07:48:11', '2021-08-25 05:24:30'),
(397, '20212942', NULL, 68, NULL, NULL, 4, '67600.00', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '67600.00', '67600.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '23-08-2021', '01:48:44 pm', '2021-08-22 18:00:00', 'August', '2021', NULL, 2, '2021-08-23 07:48:44', '2021-08-25 05:24:30'),
(398, '20216558', NULL, 69, NULL, NULL, 1, '8550.00', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '8550.00', '0.00', '0.00', '8550.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '2021-08-23', '06:02:29 pm', '2021-08-22 18:00:00', 'August', '2021', NULL, 1, '2021-08-23 12:02:29', '2021-08-23 12:02:29'),
(399, '20218691', NULL, 68, NULL, NULL, 1, '107.10', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '107.10', '107.10', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '2021-08-24', '11:48:58 am', '2021-08-23 18:00:00', 'August', '2021', NULL, 1, '2021-08-24 05:48:58', '2021-08-25 05:24:30'),
(402, '20216119', NULL, 68, NULL, NULL, 2, '2957.10', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '2957.10', '2957.10', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '26-08-2021', '11:35:02 am', '2021-08-25 18:00:00', 'August', '2021', NULL, 2, '2021-08-26 05:35:02', '2021-08-26 05:36:45'),
(403, '20213213', NULL, 68, NULL, NULL, 1, '2850.00', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '2850.00', '2850.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '26-08-2021', '11:35:45 am', '2021-08-25 18:00:00', 'August', '2021', NULL, 2, '2021-08-26 05:35:45', '2021-08-26 05:35:45'),
(404, '20213898', NULL, 68, NULL, NULL, 1, '2850.00', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '2850.00', '2850.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '26-08-2021', '11:35:56 am', '2021-08-25 18:00:00', 'August', '2021', NULL, 2, '2021-08-26 05:35:56', '2021-08-26 05:35:56'),
(405, '20212283', NULL, 68, NULL, NULL, 1, '2850.00', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '2850.00', '2850.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '26-08-2021', '11:36:45 am', '2021-08-25 18:00:00', 'August', '2021', NULL, 2, '2021-08-26 05:36:45', '2021-08-26 05:36:45'),
(406, '20219423', NULL, NULL, NULL, NULL, 1, '2850.00', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '2850.00', '2850.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '26-08-2021', '11:38:17 am', '2021-08-25 18:00:00', 'August', '2021', NULL, 2, '2021-08-26 05:38:17', '2021-08-26 05:38:17'),
(407, '20217948', NULL, NULL, NULL, NULL, 1, '65450.00', 1, '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '65450.00', '65450.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '2021-08-26', '05:27:51 pm', '2021-08-25 18:00:00', 'August', '2021', NULL, 1, '2021-08-26 11:27:51', '2021-08-26 11:27:51');

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
(311, 'SPI2107189312', 339, NULL, NULL, 'Cash', '26400.00', 1, 1, NULL, '18-07-2021', '11:34:39 am', 'July', '2021', '2021-07-17 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, '2021-07-18 05:34:39', '2021-07-18 05:34:39'),
(319, 'SPI2108167324', 347, NULL, NULL, 'Cash', '364850.00', 1, 1, NULL, '16-08-2021', '02:04:10 pm', 'August', '2021', '2021-08-15 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-08-16 08:04:10', '2021-08-16 08:04:10'),
(320, 'SPI2108181448', 361, 68, NULL, 'Cash', '59500.00', 1, 1, NULL, '2021-08-18', '06:23:00 pm', 'August', '2021', '2021-08-17 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-08-18 12:23:00', '2021-08-18 12:23:00'),
(321, 'SPI2108181448', 338, 68, NULL, 'Cash', '13200.00', 1, 1, NULL, '2021-08-18', '06:23:00 pm', 'August', '2021', '2021-08-17 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-08-18 12:23:00', '2021-08-18 12:23:00'),
(322, 'SPI2108181448', 340, 68, NULL, 'Cash', '13200.00', 1, 1, NULL, '2021-08-18', '06:23:00 pm', 'August', '2021', '2021-08-17 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-08-18 12:23:00', '2021-08-18 12:23:00'),
(323, 'SPI2108181448', 341, 68, NULL, 'Cash', '13200.00', 1, 1, NULL, '2021-08-18', '06:23:00 pm', 'August', '2021', '2021-08-17 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-08-18 12:23:00', '2021-08-18 12:23:00'),
(324, 'SPI2108181448', 348, 68, NULL, 'Cash', '215000.00', 1, 1, NULL, '2021-08-18', '06:23:00 pm', 'August', '2021', '2021-08-17 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-08-18 12:23:00', '2021-08-18 12:23:00'),
(325, 'SPI2108181448', 349, 68, NULL, 'Cash', '215000.00', 1, 1, NULL, '2021-08-18', '06:23:00 pm', 'August', '2021', '2021-08-17 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-08-18 12:23:00', '2021-08-18 12:23:00'),
(326, 'SPI2108181448', 350, 68, NULL, 'Cash', '59500.00', 1, 1, NULL, '2021-08-18', '06:23:00 pm', 'August', '2021', '2021-08-17 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-08-18 12:23:00', '2021-08-18 12:23:00'),
(327, 'SPI2108181448', 351, 68, NULL, 'Cash', '100.00', 1, 1, NULL, '2021-08-18', '06:23:00 pm', 'August', '2021', '2021-08-17 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-08-18 12:23:00', '2021-08-18 12:23:00'),
(328, 'SPI2108181448', 352, 68, NULL, 'Cash', '100.00', 1, 1, NULL, '2021-08-18', '06:23:00 pm', 'August', '2021', '2021-08-17 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-08-18 12:23:00', '2021-08-18 12:23:00'),
(329, 'SPI2108181448', 353, 68, NULL, 'Cash', '100.00', 1, 1, NULL, '2021-08-18', '06:23:00 pm', 'August', '2021', '2021-08-17 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-08-18 12:23:00', '2021-08-18 12:23:00'),
(330, 'SPI2108181448', 354, 68, NULL, 'Cash', '100.00', 1, 1, NULL, '2021-08-18', '06:23:00 pm', 'August', '2021', '2021-08-17 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-08-18 12:23:00', '2021-08-18 12:23:00'),
(331, 'SPI2108181448', 355, 68, NULL, 'Cash', '100.00', 1, 1, NULL, '2021-08-18', '06:23:00 pm', 'August', '2021', '2021-08-17 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-08-18 12:23:00', '2021-08-18 12:23:00'),
(332, 'SPI2108181448', 356, 68, NULL, 'Cash', '59500.00', 1, 1, NULL, '2021-08-18', '06:23:00 pm', 'August', '2021', '2021-08-17 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-08-18 12:23:00', '2021-08-18 12:23:00'),
(333, 'SPI2108181448', 357, 68, NULL, 'Cash', '59500.00', 1, 1, NULL, '2021-08-18', '06:23:00 pm', 'August', '2021', '2021-08-17 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-08-18 12:23:00', '2021-08-18 12:23:00'),
(334, 'SPI2108181448', 358, 68, NULL, 'Cash', '59500.00', 1, 1, NULL, '2021-08-18', '06:23:00 pm', 'August', '2021', '2021-08-17 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-08-18 12:23:00', '2021-08-18 12:23:00'),
(335, 'SPI2108181448', 359, 68, NULL, 'Cash', '100.00', 1, 1, NULL, '2021-08-18', '06:23:00 pm', 'August', '2021', '2021-08-17 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-08-18 12:23:00', '2021-08-18 12:23:00'),
(336, 'SPI2108181448', 360, 68, NULL, 'Cash', '100.00', 1, 1, NULL, '2021-08-18', '06:23:00 pm', 'August', '2021', '2021-08-17 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-08-18 12:23:00', '2021-08-18 12:23:00'),
(337, 'SPI2108198575', 362, NULL, NULL, 'Cash', '59500.00', 1, 1, NULL, '2021-08-19', '10:20:28 am', 'August', '2021', '2021-08-18 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-08-19 04:20:28', '2021-08-19 04:20:28'),
(338, 'SPI2108226232', 367, NULL, NULL, 'Cash', '100.00', 1, 1, NULL, '2021-08-22', '12:47:27 pm', 'August', '2021', '2021-08-21 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-08-22 06:47:27', '2021-08-22 06:47:27'),
(339, 'SPI2108225191', 368, NULL, NULL, 'Cash', '100.00', 1, 1, NULL, '2021-08-22', '12:50:13 pm', 'August', '2021', '2021-08-21 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-08-22 06:50:13', '2021-08-22 06:50:13'),
(340, 'SPI2108222967', 369, NULL, NULL, 'Cash', '100.00', 1, 1, NULL, '2021-08-22', '12:54:03 pm', 'August', '2021', '2021-08-21 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-08-22 06:54:03', '2021-08-22 06:54:03'),
(341, 'SPI2108228595', 370, NULL, NULL, 'Cash', '100.00', 1, 1, NULL, '2021-08-22', '12:56:49 pm', 'August', '2021', '2021-08-21 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-08-22 06:56:49', '2021-08-22 06:56:49'),
(342, 'SPI2108222235', 371, NULL, NULL, 'Cash', '100.00', 1, 1, NULL, '2021-08-22', '12:57:44 pm', 'August', '2021', '2021-08-21 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-08-22 06:57:44', '2021-08-22 06:57:44'),
(343, 'SPI2108229259', 373, 68, NULL, 'Cash', '100.00', 1, 1, NULL, '2021-08-22', '03:41:44 pm', 'August', '2021', '2021-08-21 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-08-22 09:41:44', '2021-08-22 09:41:44'),
(344, 'SPI2108226673', 374, 68, NULL, 'Cash', '71750.00', 1, 1, NULL, '2021-08-22', '04:00:20 pm', 'August', '2021', '2021-08-21 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-08-22 10:00:20', '2021-08-22 10:00:20'),
(345, 'SPI2108221941', 375, 68, NULL, 'Cash', '71750.00', 1, 1, NULL, '2021-08-22', '04:02:05 pm', 'August', '2021', '2021-08-21 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-08-22 10:02:05', '2021-08-22 10:02:05'),
(346, 'SPI2108221941', 363, 68, NULL, 'Cash', '100.00', 1, 1, NULL, '2021-08-22', '04:02:05 pm', 'August', '2021', '2021-08-21 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-08-22 10:02:05', '2021-08-22 10:02:05'),
(347, 'SPI2108221941', 364, 68, NULL, 'Cash', '100.00', 1, 1, NULL, '2021-08-22', '04:02:05 pm', 'August', '2021', '2021-08-21 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-08-22 10:02:05', '2021-08-22 10:02:05'),
(348, 'SPI2108221941', 365, 68, NULL, 'Cash', '28050.00', 1, 1, NULL, '2021-08-22', '04:02:05 pm', 'August', '2021', '2021-08-21 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-08-22 10:02:05', '2021-08-22 10:02:05'),
(349, 'SPI2108221941', 366, 68, NULL, 'Cash', '11250.00', 1, 1, NULL, '2021-08-22', '04:02:05 pm', 'August', '2021', '2021-08-21 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-08-22 10:02:05', '2021-08-22 10:02:05'),
(350, 'SPI2108223972', 376, 68, NULL, 'Cash', '12250.00', 1, 1, NULL, '2021-08-22', '04:03:55 pm', 'August', '2021', '2021-08-21 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-08-22 10:03:55', '2021-08-22 10:03:55'),
(351, 'SPI2108223972', 365, 68, NULL, 'Cash', '42700.00', 1, 1, NULL, '2021-08-22', '04:03:55 pm', 'August', '2021', '2021-08-21 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-08-22 10:03:55', '2021-08-22 10:03:55'),
(352, 'SPI2108228998', 377, 68, NULL, 'Cash', '12250.00', 1, 1, NULL, '2021-08-22', '04:06:34 pm', 'August', '2021', '2021-08-21 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-08-22 10:06:34', '2021-08-22 10:06:34'),
(353, 'SPI210825216462', 378, 68, NULL, 'Cash', '12250.00', 1, 1, NULL, '25-08-2021', '11:24:30 am', 'August', '2021', '2021-08-24 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-08-25 05:24:30', '2021-08-25 05:24:30'),
(354, 'SPI210825216462', 379, 68, NULL, 'Cash', '100.00', 1, 1, NULL, '25-08-2021', '11:24:30 am', 'August', '2021', '2021-08-24 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-08-25 05:24:30', '2021-08-25 05:24:30'),
(355, 'SPI210825216462', 380, 68, NULL, 'Cash', '11150.00', 1, 1, NULL, '25-08-2021', '11:24:30 am', 'August', '2021', '2021-08-24 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-08-25 05:24:30', '2021-08-25 05:24:30'),
(356, 'SPI210825216462', 381, 68, NULL, 'Cash', '100.00', 1, 1, NULL, '25-08-2021', '11:24:30 am', 'August', '2021', '2021-08-24 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-08-25 05:24:30', '2021-08-25 05:24:30'),
(357, 'SPI210825216462', 382, 68, NULL, 'Cash', '50.00', 1, 1, NULL, '25-08-2021', '11:24:30 am', 'August', '2021', '2021-08-24 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-08-25 05:24:30', '2021-08-25 05:24:30'),
(358, 'SPI210825216462', 383, 68, NULL, 'Cash', '100.00', 1, 1, NULL, '25-08-2021', '11:24:30 am', 'August', '2021', '2021-08-24 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-08-25 05:24:30', '2021-08-25 05:24:30'),
(359, 'SPI210825216462', 384, 68, NULL, 'Cash', '100.00', 1, 1, NULL, '25-08-2021', '11:24:30 am', 'August', '2021', '2021-08-24 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-08-25 05:24:30', '2021-08-25 05:24:30'),
(360, 'SPI210825216462', 385, 68, NULL, 'Cash', '100.00', 1, 1, NULL, '25-08-2021', '11:24:30 am', 'August', '2021', '2021-08-24 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-08-25 05:24:30', '2021-08-25 05:24:30'),
(361, 'SPI210825216462', 386, 68, NULL, 'Cash', '100.00', 1, 1, NULL, '25-08-2021', '11:24:30 am', 'August', '2021', '2021-08-24 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-08-25 05:24:30', '2021-08-25 05:24:30'),
(362, 'SPI210825216462', 387, 68, NULL, 'Cash', '100.00', 1, 1, NULL, '25-08-2021', '11:24:30 am', 'August', '2021', '2021-08-24 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-08-25 05:24:30', '2021-08-25 05:24:30'),
(363, 'SPI210825216462', 388, 68, NULL, 'Cash', '100.00', 1, 1, NULL, '25-08-2021', '11:24:30 am', 'August', '2021', '2021-08-24 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-08-25 05:24:30', '2021-08-25 05:24:30'),
(364, 'SPI210825216462', 389, 68, NULL, 'Cash', '100.00', 1, 1, NULL, '25-08-2021', '11:24:30 am', 'August', '2021', '2021-08-24 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-08-25 05:24:30', '2021-08-25 05:24:30'),
(365, 'SPI210825216462', 390, 68, NULL, 'Cash', '100.00', 1, 1, NULL, '25-08-2021', '11:24:30 am', 'August', '2021', '2021-08-24 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-08-25 05:24:30', '2021-08-25 05:24:30'),
(366, 'SPI210825216462', 391, 68, NULL, 'Cash', '100.00', 1, 1, NULL, '25-08-2021', '11:24:30 am', 'August', '2021', '2021-08-24 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-08-25 05:24:30', '2021-08-25 05:24:30'),
(367, 'SPI210825216462', 392, 68, NULL, 'Cash', '100.00', 1, 1, NULL, '25-08-2021', '11:24:30 am', 'August', '2021', '2021-08-24 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-08-25 05:24:30', '2021-08-25 05:24:30'),
(368, 'SPI210825216462', 393, 68, NULL, 'Cash', '100.00', 1, 1, NULL, '25-08-2021', '11:24:30 am', 'August', '2021', '2021-08-24 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-08-25 05:24:30', '2021-08-25 05:24:30'),
(369, 'SPI210825216462', 394, 68, NULL, 'Cash', '100.00', 1, 1, NULL, '25-08-2021', '11:24:30 am', 'August', '2021', '2021-08-24 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-08-25 05:24:30', '2021-08-25 05:24:30'),
(370, 'SPI210825216462', 395, 68, NULL, 'Cash', '100.00', 1, 1, NULL, '25-08-2021', '11:24:30 am', 'August', '2021', '2021-08-24 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-08-25 05:24:30', '2021-08-25 05:24:30'),
(371, 'SPI210825216462', 396, 68, NULL, 'Cash', '119100.00', 1, 1, NULL, '25-08-2021', '11:24:30 am', 'August', '2021', '2021-08-24 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-08-25 05:24:30', '2021-08-25 05:24:30'),
(372, 'SPI210825216462', 397, 68, NULL, 'Cash', '67600.00', 1, 1, NULL, '25-08-2021', '11:24:30 am', 'August', '2021', '2021-08-24 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-08-25 05:24:30', '2021-08-25 05:24:30'),
(373, 'SPI210825216462', 399, 68, NULL, 'Cash', '107.10', 1, 1, NULL, '25-08-2021', '11:24:30 am', 'August', '2021', '2021-08-24 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-08-25 05:24:30', '2021-08-25 05:24:30'),
(376, 'SPI2108262283', 405, 68, NULL, 'Cash', '2850.00', 1, 1, NULL, '26-08-2021', '11:36:45 am', 'August', '2021', '2021-08-25 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-08-26 05:36:45', '2021-08-26 05:36:45'),
(377, 'SPI2108262283', 402, 68, NULL, 'Cash', '2957.10', 2, 1, NULL, '26-08-2021', '11:36:45 am', 'August', '2021', '2021-08-25 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-08-26 05:36:45', '2021-08-26 05:36:45'),
(378, 'SPI2108269423', 406, NULL, NULL, 'Cash', '2850.00', 1, 1, NULL, '26-08-2021', '11:38:17 am', 'August', '2021', '2021-08-25 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-08-26 05:38:17', '2021-08-26 05:38:17'),
(379, 'SPI2108267948', 407, NULL, NULL, 'Cash', '65450.00', 1, 1, NULL, '2021-08-26', '05:27:51 pm', 'August', '2021', '2021-08-25 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-08-26 11:27:51', '2021-08-26 11:27:51');

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
(678, 337, 207, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '600.00', '12571.43', '12600.00', '13200.00', '13200.00', NULL, '0.00', 0, 0, '2021-07-18 05:10:36', '2021-07-18 05:10:36'),
(679, 338, 207, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '600.00', '12571.43', '12600.00', '13200.00', '13200.00', NULL, '0.00', 0, 0, '2021-07-18 05:33:43', '2021-07-18 05:33:43'),
(680, 339, 207, NULL, '2.00', 'Piece', 1, '0.00', '0.00', '5.00', '600.00', '12571.43', '12600.00', '13200.00', '26400.00', NULL, '0.00', 0, 0, '2021-07-18 05:34:39', '2021-07-18 05:34:39'),
(681, 340, 207, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '600.00', '12571.43', '12600.00', '13200.00', '13200.00', NULL, '0.00', 0, 0, '2021-07-18 10:23:54', '2021-07-18 10:23:54'),
(682, 341, 207, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '600.00', '12571.43', '12600.00', '13200.00', '13200.00', NULL, '0.00', 0, 0, '2021-07-18 12:09:36', '2021-07-18 12:09:36'),
(684, 343, 212, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '1000.00', '1000.00', '1000.00', '1000.00', NULL, '0.00', 0, 0, '2021-08-12 05:54:12', '2021-08-12 06:40:24'),
(685, 343, 215, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '11150.00', '11150.00', '11150.00', '11150.00', NULL, '0.00', 0, 0, '2021-08-12 05:54:12', '2021-08-12 06:40:24'),
(688, 343, 211, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '7000.00', '8050.00', '8050.00', '8050.00', NULL, '0.00', 0, 0, '2021-08-12 05:54:12', '2021-08-12 06:40:24'),
(690, 344, 216, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '59500.00', '59500.00', '59500.00', '59500.00', NULL, '0.00', 0, 0, '2021-08-12 07:27:30', '2021-08-12 07:27:30'),
(693, 347, 211, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '7000.00', '7000.00', '7000.00', '7000.00', NULL, '0.00', 0, 0, '2021-08-16 08:04:10', '2021-08-16 08:04:10'),
(695, 347, 207, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '600.00', '12571.43', '12600.00', '13200.00', '13200.00', NULL, '0.00', 0, 0, '2021-08-16 08:04:10', '2021-08-16 08:04:10'),
(696, 347, 212, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '1000.00', '1000.00', '1000.00', '1000.00', NULL, '0.00', 0, 0, '2021-08-16 08:04:10', '2021-08-16 08:04:10'),
(700, 347, 216, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '59500.00', '59500.00', '59500.00', '59500.00', NULL, '0.00', 0, 0, '2021-08-16 08:04:10', '2021-08-16 08:04:10'),
(701, 347, 215, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '11150.00', '11150.00', '11150.00', '11150.00', NULL, '0.00', 0, 0, '2021-08-16 08:04:10', '2021-08-16 08:04:10'),
(704, 350, 216, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '59500.00', '59500.00', '59500.00', '59500.00', NULL, '0.00', 0, 0, '2021-08-18 07:20:24', '2021-08-18 07:20:24'),
(705, 351, 218, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', '100.00', '100.00', NULL, '0.00', 0, 0, '2021-08-18 07:23:20', '2021-08-18 07:23:20'),
(706, 352, 218, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', '100.00', '100.00', NULL, '0.00', 0, 0, '2021-08-18 07:29:35', '2021-08-18 07:29:35'),
(707, 353, 218, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', '100.00', '100.00', NULL, '0.00', 0, 0, '2021-08-18 07:29:44', '2021-08-18 07:29:44'),
(708, 354, 218, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', '100.00', '100.00', NULL, '0.00', 0, 0, '2021-08-18 07:30:10', '2021-08-18 07:30:10'),
(709, 355, 218, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', '100.00', '100.00', NULL, '0.00', 0, 0, '2021-08-18 07:35:05', '2021-08-18 07:35:05'),
(710, 356, 216, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '59500.00', '59500.00', '59500.00', '59500.00', NULL, '0.00', 0, 0, '2021-08-18 08:45:51', '2021-08-18 08:45:51'),
(711, 357, 216, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '59500.00', '59500.00', '59500.00', '59500.00', NULL, '0.00', 0, 0, '2021-08-18 08:46:18', '2021-08-18 08:46:18'),
(712, 358, 216, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '59500.00', '59500.00', '59500.00', '59500.00', NULL, '0.00', 0, 0, '2021-08-18 08:46:56', '2021-08-18 08:46:56'),
(713, 359, 218, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', '100.00', '100.00', NULL, '0.00', 0, 0, '2021-08-18 08:50:48', '2021-08-18 08:50:48'),
(714, 360, 218, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', '100.00', '100.00', NULL, '0.00', 0, 0, '2021-08-18 09:01:26', '2021-08-18 09:01:26'),
(715, 361, 216, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '59500.00', '59500.00', '59500.00', '59500.00', NULL, '0.00', 0, 0, '2021-08-18 12:23:00', '2021-08-18 12:23:00'),
(716, 362, 216, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '59500.00', '59500.00', '59500.00', '59500.00', NULL, '0.00', 0, 0, '2021-08-19 04:20:28', '2021-08-19 04:20:28'),
(717, 363, 218, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', '100.00', '100.00', NULL, '0.00', 0, 0, '2021-08-21 06:40:14', '2021-08-21 06:40:14'),
(718, 364, 218, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', '100.00', '100.00', NULL, '0.00', 0, 0, '2021-08-21 06:40:32', '2021-08-21 06:40:32'),
(719, 365, 215, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '11150.00', '11150.00', '11150.00', '11150.00', NULL, '0.00', 0, 0, '2021-08-21 06:43:43', '2021-08-21 06:43:43'),
(720, 365, 216, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '59500.00', '59500.00', '59500.00', '59500.00', NULL, '0.00', 0, 0, '2021-08-21 06:43:43', '2021-08-21 06:43:43'),
(721, 365, 218, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', '100.00', '100.00', NULL, '0.00', 0, 0, '2021-08-21 06:43:43', '2021-08-21 06:43:43'),
(722, 366, 215, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '11150.00', '11150.00', '11150.00', '11150.00', NULL, '0.00', 0, 0, '2021-08-21 07:00:52', '2021-08-21 07:00:52'),
(723, 366, 218, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', '100.00', '100.00', NULL, '0.00', 0, 0, '2021-08-21 07:00:52', '2021-08-21 07:00:52'),
(724, 367, 218, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', '100.00', '100.00', NULL, '0.00', 0, 0, '2021-08-22 06:47:27', '2021-08-22 06:47:27'),
(725, 368, 218, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', '100.00', '100.00', NULL, '0.00', 0, 0, '2021-08-22 06:50:13', '2021-08-22 06:50:13'),
(726, 369, 218, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', '100.00', '100.00', NULL, '0.00', 0, 0, '2021-08-22 06:54:03', '2021-08-22 06:54:03'),
(727, 370, 218, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', '100.00', '100.00', NULL, '0.00', 0, 0, '2021-08-22 06:56:49', '2021-08-22 06:56:49'),
(728, 371, 218, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', '100.00', '100.00', NULL, '0.00', 0, 0, '2021-08-22 06:57:44', '2021-08-22 06:57:44'),
(729, 372, 207, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '79000.00', '79000.00', '79000.00', '79000.00', 'C3J201500873', '0.00', 0, 0, '2021-08-22 09:05:19', '2021-08-22 09:05:19'),
(730, 373, 218, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', '100.00', '100.00', NULL, '0.00', 0, 0, '2021-08-22 09:41:44', '2021-08-22 09:41:44'),
(731, 374, 212, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '1000.00', '1000.00', '1000.00', '1000.00', NULL, '0.00', 0, 0, '2021-08-22 10:00:20', '2021-08-22 10:00:20'),
(732, 374, 215, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '11150.00', '11150.00', '11150.00', '11150.00', NULL, '0.00', 0, 0, '2021-08-22 10:00:20', '2021-08-22 10:00:20'),
(733, 374, 216, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '59500.00', '59500.00', '59500.00', '59500.00', NULL, '0.00', 0, 0, '2021-08-22 10:00:20', '2021-08-22 10:00:20'),
(734, 374, 218, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', '100.00', '100.00', NULL, '0.00', 0, 0, '2021-08-22 10:00:20', '2021-08-22 10:00:20'),
(735, 375, 212, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '1000.00', '1000.00', '1000.00', '1000.00', NULL, '0.00', 0, 0, '2021-08-22 10:02:05', '2021-08-22 10:02:05'),
(736, 375, 215, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '11150.00', '11150.00', '11150.00', '11150.00', NULL, '0.00', 0, 0, '2021-08-22 10:02:05', '2021-08-22 10:02:05'),
(737, 375, 216, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '59500.00', '59500.00', '59500.00', '59500.00', NULL, '0.00', 0, 0, '2021-08-22 10:02:05', '2021-08-22 10:02:05'),
(738, 375, 218, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', '100.00', '100.00', NULL, '0.00', 0, 0, '2021-08-22 10:02:05', '2021-08-22 10:02:05'),
(739, 376, 215, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '11150.00', '11150.00', '11150.00', '11150.00', NULL, '0.00', 0, 0, '2021-08-22 10:03:55', '2021-08-22 10:03:55'),
(740, 376, 212, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '1000.00', '1000.00', '1000.00', '1000.00', NULL, '0.00', 0, 0, '2021-08-22 10:03:55', '2021-08-22 10:03:55'),
(741, 376, 218, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', '100.00', '100.00', NULL, '0.00', 0, 0, '2021-08-22 10:03:55', '2021-08-22 10:03:55'),
(742, 377, 215, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '11150.00', '11150.00', '11150.00', '11150.00', NULL, '0.00', 0, 0, '2021-08-22 10:06:34', '2021-08-22 10:06:34'),
(743, 377, 212, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '1000.00', '1000.00', '1000.00', '1000.00', NULL, '0.00', 0, 0, '2021-08-22 10:06:34', '2021-08-22 10:06:34'),
(744, 377, 218, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', '100.00', '100.00', NULL, '0.00', 0, 0, '2021-08-22 10:06:34', '2021-08-22 10:06:34'),
(745, 378, 215, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '11150.00', '11150.00', '11150.00', '11150.00', NULL, '0.00', 0, 0, '2021-08-22 10:11:48', '2021-08-22 10:11:48'),
(746, 378, 212, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '1000.00', '1000.00', '1000.00', '1000.00', NULL, '0.00', 0, 0, '2021-08-22 10:11:48', '2021-08-22 10:11:48'),
(747, 378, 218, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', '100.00', '100.00', NULL, '0.00', 0, 0, '2021-08-22 10:11:48', '2021-08-22 10:11:48'),
(748, 379, 218, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', '100.00', '100.00', NULL, '0.00', 0, 0, '2021-08-22 11:41:33', '2021-08-22 11:41:33'),
(749, 380, 215, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '11150.00', '11150.00', '11150.00', '11150.00', NULL, '0.00', 0, 0, '2021-08-22 11:42:20', '2021-08-22 11:42:20'),
(750, 381, 218, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', '100.00', '100.00', NULL, '0.00', 0, 0, '2021-08-22 11:42:50', '2021-08-22 11:42:50'),
(751, 382, 218, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', '100.00', '100.00', NULL, '0.00', 0, 0, '2021-08-23 04:53:27', '2021-08-23 04:53:27'),
(752, 383, 218, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', '100.00', '100.00', NULL, '0.00', 0, 0, '2021-08-23 06:43:24', '2021-08-23 06:43:24'),
(753, 384, 218, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', '100.00', '100.00', NULL, '0.00', 0, 0, '2021-08-23 06:43:49', '2021-08-23 06:43:49'),
(754, 385, 218, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', '100.00', '100.00', NULL, '0.00', 0, 0, '2021-08-23 06:44:33', '2021-08-23 06:44:33'),
(755, 386, 218, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', '100.00', '100.00', NULL, '0.00', 0, 0, '2021-08-23 06:51:20', '2021-08-23 06:51:20'),
(756, 387, 218, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', '100.00', '100.00', NULL, '0.00', 0, 0, '2021-08-23 06:53:15', '2021-08-23 06:53:15'),
(757, 388, 218, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', '100.00', '100.00', NULL, '0.00', 0, 0, '2021-08-23 06:57:02', '2021-08-23 06:57:02'),
(758, 389, 218, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', '100.00', '100.00', NULL, '0.00', 0, 0, '2021-08-23 07:00:06', '2021-08-23 07:00:06'),
(759, 390, 218, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', '100.00', '100.00', NULL, '0.00', 0, 0, '2021-08-23 07:00:25', '2021-08-23 07:00:25'),
(760, 391, 218, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', '100.00', '100.00', NULL, '0.00', 0, 0, '2021-08-23 07:00:59', '2021-08-23 07:00:59'),
(761, 392, 218, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', '100.00', '100.00', NULL, '0.00', 0, 0, '2021-08-23 07:02:07', '2021-08-23 07:02:07'),
(762, 393, 218, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', '100.00', '100.00', NULL, '0.00', 0, 0, '2021-08-23 07:17:20', '2021-08-23 07:17:20'),
(763, 394, 218, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', '100.00', '100.00', NULL, '0.00', 0, 0, '2021-08-23 07:18:46', '2021-08-23 07:18:46'),
(764, 395, 218, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', '100.00', '100.00', NULL, '0.00', 0, 0, '2021-08-23 07:21:01', '2021-08-23 07:21:01'),
(765, 396, 216, NULL, '2.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '59500.00', '59500.00', '59500.00', '119000.00', NULL, '0.00', 0, 0, '2021-08-23 07:48:11', '2021-08-23 07:48:11'),
(766, 396, 218, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', '100.00', '100.00', NULL, '0.00', 0, 0, '2021-08-23 07:48:11', '2021-08-23 07:48:11'),
(767, 397, 212, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '1000.00', '1000.00', '1000.00', '1000.00', NULL, '0.00', 0, 0, '2021-08-23 07:48:44', '2021-08-23 07:48:44'),
(768, 397, 211, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '7000.00', '7000.00', '7000.00', '7000.00', NULL, '0.00', 0, 0, '2021-08-23 07:48:44', '2021-08-23 07:48:44'),
(769, 397, 216, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '59500.00', '59500.00', '59500.00', '59500.00', NULL, '0.00', 0, 0, '2021-08-23 07:48:44', '2021-08-23 07:48:44'),
(770, 397, 218, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', '100.00', '100.00', NULL, '0.00', 0, 0, '2021-08-23 07:48:44', '2021-08-23 07:48:44'),
(771, 398, 220, NULL, '3.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '2850.00', '2850.00', '2850.00', '8550.00', '200IMAGES.P#800300-250IN', '0.00', 0, 0, '2021-08-23 12:02:29', '2021-08-23 12:02:29'),
(772, 399, 218, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '5.10', '105.00', '102.00', '107.10', '107.10', NULL, '0.00', 0, 0, '2021-08-24 05:48:58', '2021-08-24 05:48:58'),
(775, 402, 218, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '5.00', '5.10', '105.00', '102.00', '107.10', '107.10', NULL, '0.00', 0, 0, '2021-08-26 05:35:02', '2021-08-26 05:35:02'),
(776, 402, 220, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '2850.00', '2850.00', '2850.00', '2850.00', NULL, '0.00', 0, 0, '2021-08-26 05:35:02', '2021-08-26 05:35:02'),
(777, 403, 220, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '2850.00', '2850.00', '2850.00', '2850.00', NULL, '0.00', 0, 0, '2021-08-26 05:35:45', '2021-08-26 05:35:45'),
(778, 404, 220, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '2850.00', '2850.00', '2850.00', '2850.00', NULL, '0.00', 0, 0, '2021-08-26 05:35:56', '2021-08-26 05:35:56'),
(779, 405, 220, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '2850.00', '2850.00', '2850.00', '2850.00', NULL, '0.00', 0, 0, '2021-08-26 05:36:45', '2021-08-26 05:36:45'),
(780, 406, 220, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '2850.00', '2850.00', '2850.00', '2850.00', NULL, '0.00', 0, 0, '2021-08-26 05:38:17', '2021-08-26 05:38:17'),
(781, 407, 216, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '10.00', '5950.00', '65450.00', '59500.00', '65450.00', '65450.00', NULL, '0.00', 0, 0, '2021-08-26 11:27:51', '2021-08-26 11:27:51');

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

-- --------------------------------------------------------

--
-- Table structure for table `short_menus`
--

CREATE TABLE `short_menus` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `short_menus`
--

INSERT INTO `short_menus` (`id`, `url`, `name`, `icon`, `created_at`, `updated_at`) VALUES
(1, 'product.categories.index', 'Categories', 'fas fa-th-large', NULL, NULL),
(2, 'product.subcategories.index', 'SubCategories', 'fas fa-code-branch', NULL, NULL),
(3, 'product.brands.index', 'Brands', 'fas fa-band-aid', NULL, NULL),
(4, 'products.all.product', 'Product List', 'fas fa-sitemap', NULL, NULL),
(5, 'products.add.view', 'Add Product', 'fas fa-plus-circle', NULL, NULL),
(6, 'product.variants.index', 'Variants', 'fas fa-align-center', NULL, NULL),
(7, 'product.import.create', 'Import Products', 'fas fa-file-import', NULL, NULL),
(8, 'product.selling.price.groups.index', 'Price Group', 'fas fa-layer-group', NULL, NULL),
(9, 'barcode.index', 'G.Barcode', 'fas fa-barcode', NULL, NULL),
(10, 'product.warranties.index', 'Warranties ', 'fas fa-shield-alt', NULL, NULL),
(11, 'contacts.supplier.index', 'Suppliers', 'fas fa-address-card', NULL, NULL),
(12, 'contacts.suppliers.import.create', 'Import Suppliers', 'fas fa-file-import', NULL, NULL),
(13, 'contacts.customer.index', 'Customers', 'far fa-address-card', NULL, NULL),
(14, 'contacts.customers.import.create', 'Import Customers', 'fas fa-file-upload', NULL, NULL),
(15, 'purchases.create', 'Add Purchase', 'fas fa-shopping-cart', NULL, NULL),
(16, 'purchases.index_v2', 'Purchase List', 'fas fa-list', NULL, NULL),
(17, 'purchases.returns.index', 'Purchase Return', 'fas fa-undo', NULL, NULL),
(18, 'sales.store', 'Add Sale', 'fas fa-cart-plus', NULL, NULL),
(19, 'sales.index2', 'Add Sale List', 'fas fa-tasks', NULL, NULL),
(20, 'sales.pos.create', 'POS', 'fas fa-cash-register', NULL, NULL),
(21, 'sales.pos.list', 'POS List', 'fas fa-tasks', NULL, NULL),
(22, 'sales.drafts', 'Draft List', 'fas fa-drafting-compass', NULL, NULL),
(23, 'sales.quotations', 'Quotation List', 'fas fa-quote-right', NULL, NULL),
(24, 'sales.returns.index', 'Sale Returns', 'fas fa-undo', NULL, NULL),
(25, 'sales.shipments', 'Shipments', 'fas fa-shipping-fast', NULL, NULL),
(26, 'expanses.create', 'Add Expense', 'fas fa-plus-square', NULL, NULL),
(27, 'expanses.index', 'Expense List', 'far fa-list-alt', NULL, NULL),
(28, 'expanses.categories.index', 'Ex. Categories', 'fas fa-cubes', NULL, NULL),
(29, 'users.create', 'Add User', 'fas fa-user-plus', NULL, NULL),
(30, 'users.index', 'User List', 'fas fa-list-ol', NULL, NULL),
(31, 'users.role.create', 'Add Role', 'fas fa-plus-circle', NULL, NULL),
(32, 'users.role.index', 'Role List', 'fas fa-th-list', NULL, NULL),
(33, 'accounting.banks.index', 'Bank', 'fas fa-university', NULL, NULL),
(34, 'accounting.types.index', 'Account Types', 'fas fa-th', NULL, NULL),
(35, 'accounting.accounts.index', 'Accounts', 'fas fa-th', NULL, NULL),
(36, 'accounting.assets.index', 'Assets', 'fas fa-luggage-cart', NULL, NULL),
(37, 'accounting.balance.sheet', 'Balance Sheet', 'fas fa-balance-scale', NULL, NULL),
(38, 'accounting.trial.balance', 'Trial Balance', 'fas fa-balance-scale-right', NULL, NULL),
(39, 'accounting.cash.flow', 'Cash Flow', 'fas fa-money-bill-wave', NULL, NULL),
(40, 'settings.general.index', 'General Settings', 'fas fa-cogs', NULL, NULL),
(41, 'settings.taxes.index', 'Taxes', 'fas fa-percentage', NULL, NULL),
(42, 'invoices.schemas.index', 'Inv. Schemas', 'fas fa-file-invoice-dollar', NULL, NULL),
(43, 'invoices.layouts.index', 'Inv. Layouts', 'fas fa-file-invoice', NULL, NULL),
(44, 'settings.barcode.index', 'Barcode Settings', 'fas fa-barcode', NULL, NULL),
(45, 'settings.cash.counter.index', 'Cash Counter', 'fas fa-store', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `short_menu_users`
--

CREATE TABLE `short_menu_users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `short_menu_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_delete_in_update` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `time` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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

INSERT INTO `stock_adjustments` (`id`, `warehouse_id`, `branch_id`, `invoice_id`, `total_item`, `total_qty`, `net_total_amount`, `recovered_amount`, `type`, `date`, `time`, `month`, `year`, `reason`, `report_date_ts`, `admin_id`, `created_at`, `updated_at`) VALUES
(17, NULL, NULL, 'SAR1208211614', 1, '0.00', '0.00', '0.00', 1, '2021-08-12', '', 'August', '2021', NULL, '2021-08-11 18:00:00', 2, '2021-08-12 07:55:40', '2021-08-12 07:55:40'),
(18, 17, NULL, 'SAR1208214764', 1, '0.00', '12571.43', '0.00', 1, '2021-08-12', '', 'August', '2021', NULL, '2021-08-11 18:00:00', 2, '2021-08-12 07:56:00', '2021-08-12 07:56:00'),
(19, NULL, NULL, 'SAR1408219815', 1, '0.00', '50000.00', '0.00', 1, '2021-08-14', '01:11:00 pm', 'August', '2021', NULL, '2021-08-13 18:00:00', 2, '2021-08-14 07:11:00', '2021-08-14 07:11:00');

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
(8, 18, 207, NULL, '1.00', 'Piece', '12571.43', '12571.43', 0, '2021-08-12 07:56:00', '2021-08-12 07:56:00');

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
(37, NULL, 'SID87157', 'Mr. Maidel', NULL, '0154422555', '0154422555', NULL, '0154422555', NULL, NULL, NULL, '0.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1260571.45', '2244571.43', '0.00', '0.00', 1, 'M87157', '2021-07-18 05:07:04', '2021-08-17 06:36:19');

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
(114, 37, 169, NULL, 1, NULL, '2021-07-18 05:09:27', '2021-07-18 05:09:27'),
(115, 37, NULL, 164, 2, NULL, '2021-07-18 05:09:27', '2021-07-18 05:09:27'),
(116, 37, 170, NULL, 1, NULL, '2021-08-14 11:45:41', '2021-08-14 11:45:41'),
(118, 37, NULL, 166, 2, NULL, '2021-08-17 04:35:36', '2021-08-17 04:35:36'),
(119, 37, 171, NULL, 1, NULL, '2021-08-17 06:36:19', '2021-08-17 06:36:19'),
(120, 37, NULL, 167, 2, NULL, '2021-08-17 06:36:19', '2021-08-17 06:36:19');

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
(40, 37, 207, NULL, 11, '2021-07-18 05:09:27', '2021-08-17 06:36:19'),
(42, 37, 216, NULL, 10, '2021-08-14 11:45:41', '2021-08-14 11:45:41'),
(44, 37, 211, NULL, 1, '2021-08-14 11:45:41', '2021-08-14 11:45:41');

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
-- Table structure for table `todos`
--

CREATE TABLE `todos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `task` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `todo_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `priority` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `due_date` timestamp NULL DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `admin_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `todos`
--

INSERT INTO `todos` (`id`, `task`, `todo_id`, `priority`, `status`, `due_date`, `description`, `branch_id`, `admin_id`, `created_at`, `updated_at`) VALUES
(7, 'Task-2', '2021/6214', 'Urgent', 'Complated', '2021-07-07 18:00:00', 'D-S', NULL, 2, '2021-07-07 18:00:00', '2021-07-10 11:50:39'),
(8, 'Create a data base for our new project.', '2021/9211', 'Medium', 'Complated', '2021-07-07 18:00:00', 'Create a data base for our new project.', NULL, 2, '2021-07-07 18:00:00', '2021-07-18 12:06:06');

-- --------------------------------------------------------

--
-- Table structure for table `todo_users`
--

CREATE TABLE `todo_users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `todo_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `is_delete_in_update` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `todo_users`
--

INSERT INTO `todo_users` (`id`, `todo_id`, `user_id`, `is_delete_in_update`, `created_at`, `updated_at`) VALUES
(1, 7, 2, 0, NULL, NULL),
(3, 8, 2, 0, NULL, '2021-07-18 12:06:06');

-- --------------------------------------------------------

--
-- Table structure for table `transfer_stock_to_branches`
--

CREATE TABLE `transfer_stock_to_branches` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1=pending;2=partial;3=completed',
  `warehouse_id` bigint(20) UNSIGNED NOT NULL COMMENT 'form_warehouse',
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'to_branch',
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
(8, 'STI210814239248', 3, 17, NULL, '1.00', '10.00', '10.00', '595000.00', '0.00', NULL, NULL, '2021-08-14', 'August', '2021', '2021-08-13 18:00:00', '2021-08-14 11:53:55', '2021-08-24 05:28:12');

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
(8, 8, 216, NULL, '59500.00', '10.00', '10.00', 'Piece', '595000.00', 0, '2021-08-14 11:53:55', '2021-08-24 05:28:12');

-- --------------------------------------------------------

--
-- Table structure for table `transfer_stock_to_warehouses`
--

CREATE TABLE `transfer_stock_to_warehouses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1=pending;2=partial;3=completed',
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'form_branch',
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

--
-- Dumping data for table `transfer_stock_to_warehouses`
--

INSERT INTO `transfer_stock_to_warehouses` (`id`, `invoice_id`, `status`, `branch_id`, `warehouse_id`, `total_item`, `total_send_qty`, `total_received_qty`, `net_total_amount`, `shipping_charge`, `additional_note`, `receiver_note`, `date`, `month`, `year`, `admin_id`, `report_date`, `created_at`, `updated_at`) VALUES
(1, 'TSB180721681531', 3, NULL, 17, '1.00', '1.00', '1.00', '13230.00', '0.00', NULL, NULL, '2021-07-18', 'July', '2021', NULL, '2021-07-17 18:00:00', '2021-07-18 08:08:23', '2021-07-18 10:21:15'),
(2, 'TSB180721184547', 3, NULL, 17, '1.00', '1.00', '1.00', '13230.00', '0.00', NULL, NULL, '2021-07-18', 'July', '2021', NULL, '2021-07-17 18:00:00', '2021-07-18 08:11:05', '2021-07-18 10:05:12'),
(3, 'TSB170821749126', 1, NULL, 17, '1.00', '1.00', '0.00', '13230.00', '200.00', NULL, NULL, '2021-08-17', 'August', '2021', NULL, '2021-08-16 18:00:00', '2021-08-17 07:10:26', '2021-08-17 07:10:26');

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

--
-- Dumping data for table `transfer_stock_to_warehouse_products`
--

INSERT INTO `transfer_stock_to_warehouse_products` (`id`, `transfer_stock_id`, `product_id`, `product_variant_id`, `unit_price`, `quantity`, `received_qty`, `unit`, `subtotal`, `is_delete_in_update`, `created_at`, `updated_at`) VALUES
(1, 1, 207, NULL, '13230.00', '1.00', '1.00', 'Piece', '13230.00', 0, '2021-07-18 08:08:23', '2021-07-18 10:21:15'),
(2, 2, 207, NULL, '13230.00', '1.00', '1.00', 'Piece', '13230.00', 0, '2021-07-18 08:11:05', '2021-07-18 10:20:19'),
(3, 3, 207, NULL, '13230.00', '1.00', '0.00', 'Piece', '13230.00', 0, '2021-08-17 07:10:26', '2021-08-17 07:10:26');

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
(9, 'Pound', 'PND', NULL, '2021-01-19 04:29:11', '2021-01-19 04:29:11'),
(10, 'Unit', 'UT', NULL, '2021-07-15 06:08:10', '2021-07-15 06:08:10'),
(11, 'Item', 'ITM', NULL, '2021-07-15 06:53:29', '2021-07-15 06:53:29'),
(12, 'Year', 'YR', NULL, '2021-07-15 07:14:53', '2021-07-15 07:14:53');

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
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
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

INSERT INTO `warehouses` (`id`, `branch_id`, `warehouse_name`, `warehouse_code`, `phone`, `address`, `created_at`, `updated_at`) VALUES
(17, NULL, 'PMT Warehouse', 'PMTW1', '1254455888', NULL, '2021-07-18 07:01:21', '2021-07-18 07:01:21');

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
(17, 'ded', '2', 'Days', NULL, 1, NULL, NULL),
(18, '2 Years Dell Official Warranty', '2', 'Years', NULL, 1, '2021-08-12 05:22:47', '2021-08-12 06:17:30'),
(19, '1 Year warranty', '1', 'Year', NULL, 1, '2021-08-12 07:25:51', '2021-08-12 07:25:51'),
(20, '1 Year warranty without head', '1', NULL, '1 Year warranty without head', 1, '2021-08-22 09:11:02', '2021-08-24 09:57:40');

-- --------------------------------------------------------

--
-- Table structure for table `workspaces`
--

CREATE TABLE `workspaces` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ws_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `priority` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` timestamp NULL DEFAULT NULL,
  `end_date` timestamp NULL DEFAULT NULL,
  `admin_id` bigint(20) UNSIGNED DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estimated_hours` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `workspace_attachments`
--

CREATE TABLE `workspace_attachments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `workspace_id` bigint(20) UNSIGNED NOT NULL,
  `attachment` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `extension` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `workspace_tasks`
--

CREATE TABLE `workspace_tasks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `workspace_id` bigint(20) UNSIGNED NOT NULL,
  `task_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `deadline` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `priority` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `workspace_users`
--

CREATE TABLE `workspace_users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `workspace_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_delete_in_update` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Indexes for table `addons`
--
ALTER TABLE `addons`
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
-- Indexes for table `admin_and_user_logs`
--
ALTER TABLE `admin_and_user_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_and_user_logs_user_id_foreign` (`user_id`);

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
  ADD PRIMARY KEY (`id`),
  ADD KEY `cash_counters_branch_id_foreign` (`branch_id`);

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
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `memos`
--
ALTER TABLE `memos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `memos_admin_id_foreign` (`admin_id`);

--
-- Indexes for table `memo_users`
--
ALTER TABLE `memo_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `memo_users_memo_id_foreign` (`memo_id`),
  ADD KEY `memo_users_user_id_foreign` (`user_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `messages_branch_id_foreign` (`branch_id`),
  ADD KEY `messages_user_id_foreign` (`user_id`);

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
-- Indexes for table `pos_short_menus`
--
ALTER TABLE `pos_short_menus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pos_short_menu_users`
--
ALTER TABLE `pos_short_menu_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pos_short_menu_users_short_menu_id_foreign` (`short_menu_id`),
  ADD KEY `pos_short_menu_users_user_id_foreign` (`user_id`);

--
-- Indexes for table `price_groups`
--
ALTER TABLE `price_groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `price_group_products`
--
ALTER TABLE `price_group_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `price_group_products_price_group_id_foreign` (`price_group_id`),
  ADD KEY `price_group_products_product_id_foreign` (`product_id`),
  ADD KEY `price_group_products_variant_id_foreign` (`variant_id`);

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
-- Indexes for table `short_menus`
--
ALTER TABLE `short_menus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `short_menu_users`
--
ALTER TABLE `short_menu_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `short_menu_users_short_menu_id_foreign` (`short_menu_id`),
  ADD KEY `short_menu_users_user_id_foreign` (`user_id`);

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
-- Indexes for table `todos`
--
ALTER TABLE `todos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `todos_branch_id_foreign` (`branch_id`),
  ADD KEY `todos_admin_id_foreign` (`admin_id`);

--
-- Indexes for table `todo_users`
--
ALTER TABLE `todo_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `todo_users_todo_id_foreign` (`todo_id`),
  ADD KEY `todo_users_user_id_foreign` (`user_id`);

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
  ADD PRIMARY KEY (`id`),
  ADD KEY `warehouses_branch_id_foreign` (`branch_id`);

--
-- Indexes for table `warranties`
--
ALTER TABLE `warranties`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `workspaces`
--
ALTER TABLE `workspaces`
  ADD PRIMARY KEY (`id`),
  ADD KEY `workspaces_admin_id_foreign` (`admin_id`),
  ADD KEY `workspaces_branch_id_foreign` (`branch_id`);

--
-- Indexes for table `workspace_attachments`
--
ALTER TABLE `workspace_attachments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `workspace_attachments_workspace_id_foreign` (`workspace_id`);

--
-- Indexes for table `workspace_tasks`
--
ALTER TABLE `workspace_tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `workspace_tasks_workspace_id_foreign` (`workspace_id`),
  ADD KEY `workspace_tasks_user_id_foreign` (`user_id`);

--
-- Indexes for table `workspace_users`
--
ALTER TABLE `workspace_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `workspace_users_workspace_id_foreign` (`workspace_id`),
  ADD KEY `workspace_users_user_id_foreign` (`user_id`);

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `account_types`
--
ALTER TABLE `account_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `addons`
--
ALTER TABLE `addons`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `admin_and_users`
--
ALTER TABLE `admin_and_users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `admin_and_user_logs`
--
ALTER TABLE `admin_and_user_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `allowance_employees`
--
ALTER TABLE `allowance_employees`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `assets`
--
ALTER TABLE `assets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `asset_types`
--
ALTER TABLE `asset_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `banks`
--
ALTER TABLE `banks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `barcode_settings`
--
ALTER TABLE `barcode_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `branch_payment_methods`
--
ALTER TABLE `branch_payment_methods`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `bulk_variants`
--
ALTER TABLE `bulk_variants`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `bulk_variant_children`
--
ALTER TABLE `bulk_variant_children`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `card_types`
--
ALTER TABLE `card_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `cash_counters`
--
ALTER TABLE `cash_counters`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `cash_flows`
--
ALTER TABLE `cash_flows`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=164;

--
-- AUTO_INCREMENT for table `cash_registers`
--
ALTER TABLE `cash_registers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `cash_register_transactions`
--
ALTER TABLE `cash_register_transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=155;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT for table `combo_products`
--
ALTER TABLE `combo_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `currencies`
--
ALTER TABLE `currencies`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=141;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT for table `customer_groups`
--
ALTER TABLE `customer_groups`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `customer_ledgers`
--
ALTER TABLE `customer_ledgers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=509;

--
-- AUTO_INCREMENT for table `expanses`
--
ALTER TABLE `expanses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `expanse_categories`
--
ALTER TABLE `expanse_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `expanse_payments`
--
ALTER TABLE `expanse_payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `expense_descriptions`
--
ALTER TABLE `expense_descriptions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `hrm_department`
--
ALTER TABLE `hrm_department`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `hrm_designations`
--
ALTER TABLE `hrm_designations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `hrm_holidays`
--
ALTER TABLE `hrm_holidays`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `hrm_leaves`
--
ALTER TABLE `hrm_leaves`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `hrm_leavetypes`
--
ALTER TABLE `hrm_leavetypes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `hrm_payrolls`
--
ALTER TABLE `hrm_payrolls`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `hrm_shifts`
--
ALTER TABLE `hrm_shifts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `invoice_layouts`
--
ALTER TABLE `invoice_layouts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `invoice_schemas`
--
ALTER TABLE `invoice_schemas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `memos`
--
ALTER TABLE `memos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `memo_users`
--
ALTER TABLE `memo_users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=195;

--
-- AUTO_INCREMENT for table `money_receipts`
--
ALTER TABLE `money_receipts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

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
-- AUTO_INCREMENT for table `pos_short_menus`
--
ALTER TABLE `pos_short_menus`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `pos_short_menu_users`
--
ALTER TABLE `pos_short_menu_users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=107;

--
-- AUTO_INCREMENT for table `price_groups`
--
ALTER TABLE `price_groups`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `price_group_products`
--
ALTER TABLE `price_group_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=226;

--
-- AUTO_INCREMENT for table `product_branches`
--
ALTER TABLE `product_branches`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=188;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=215;

--
-- AUTO_INCREMENT for table `product_variants`
--
ALTER TABLE `product_variants`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `product_warehouses`
--
ALTER TABLE `product_warehouses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;

--
-- AUTO_INCREMENT for table `product_warehouse_variants`
--
ALTER TABLE `product_warehouse_variants`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `purchases`
--
ALTER TABLE `purchases`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=172;

--
-- AUTO_INCREMENT for table `purchase_payments`
--
ALTER TABLE `purchase_payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=168;

--
-- AUTO_INCREMENT for table `purchase_products`
--
ALTER TABLE `purchase_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=373;

--
-- AUTO_INCREMENT for table `purchase_returns`
--
ALTER TABLE `purchase_returns`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `purchase_return_products`
--
ALTER TABLE `purchase_return_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=142;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `role_permissions`
--
ALTER TABLE `role_permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=408;

--
-- AUTO_INCREMENT for table `sale_payments`
--
ALTER TABLE `sale_payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=380;

--
-- AUTO_INCREMENT for table `sale_products`
--
ALTER TABLE `sale_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=782;

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
-- AUTO_INCREMENT for table `short_menus`
--
ALTER TABLE `short_menus`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `short_menu_users`
--
ALTER TABLE `short_menu_users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=194;

--
-- AUTO_INCREMENT for table `stock_adjustments`
--
ALTER TABLE `stock_adjustments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `stock_adjustment_products`
--
ALTER TABLE `stock_adjustment_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `supplier_ledgers`
--
ALTER TABLE `supplier_ledgers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=123;

--
-- AUTO_INCREMENT for table `supplier_products`
--
ALTER TABLE `supplier_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

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
-- AUTO_INCREMENT for table `todos`
--
ALTER TABLE `todos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `todo_users`
--
ALTER TABLE `todo_users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `transfer_stock_to_branches`
--
ALTER TABLE `transfer_stock_to_branches`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `transfer_stock_to_branch_products`
--
ALTER TABLE `transfer_stock_to_branch_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `transfer_stock_to_warehouses`
--
ALTER TABLE `transfer_stock_to_warehouses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `transfer_stock_to_warehouse_products`
--
ALTER TABLE `transfer_stock_to_warehouse_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `units`
--
ALTER TABLE `units`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `warehouses`
--
ALTER TABLE `warehouses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `warranties`
--
ALTER TABLE `warranties`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `workspaces`
--
ALTER TABLE `workspaces`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `workspace_attachments`
--
ALTER TABLE `workspace_attachments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `workspace_tasks`
--
ALTER TABLE `workspace_tasks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `workspace_users`
--
ALTER TABLE `workspace_users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

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
-- Constraints for table `admin_and_user_logs`
--
ALTER TABLE `admin_and_user_logs`
  ADD CONSTRAINT `admin_and_user_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `admin_and_users` (`id`) ON DELETE CASCADE;

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
-- Constraints for table `cash_counters`
--
ALTER TABLE `cash_counters`
  ADD CONSTRAINT `cash_counters_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE;

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
-- Constraints for table `memos`
--
ALTER TABLE `memos`
  ADD CONSTRAINT `memos_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `admin_and_users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `memo_users`
--
ALTER TABLE `memo_users`
  ADD CONSTRAINT `memo_users_memo_id_foreign` FOREIGN KEY (`memo_id`) REFERENCES `memos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `memo_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `admin_and_users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `admin_and_users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `money_receipts`
--
ALTER TABLE `money_receipts`
  ADD CONSTRAINT `money_receipts_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `money_receipts_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pos_short_menu_users`
--
ALTER TABLE `pos_short_menu_users`
  ADD CONSTRAINT `pos_short_menu_users_short_menu_id_foreign` FOREIGN KEY (`short_menu_id`) REFERENCES `short_menus` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pos_short_menu_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `admin_and_users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `price_group_products`
--
ALTER TABLE `price_group_products`
  ADD CONSTRAINT `price_group_products_price_group_id_foreign` FOREIGN KEY (`price_group_id`) REFERENCES `price_groups` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `price_group_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `price_group_products_variant_id_foreign` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE;

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
  ADD CONSTRAINT `sales_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE;

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
-- Constraints for table `short_menu_users`
--
ALTER TABLE `short_menu_users`
  ADD CONSTRAINT `short_menu_users_short_menu_id_foreign` FOREIGN KEY (`short_menu_id`) REFERENCES `short_menus` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `short_menu_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `admin_and_users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stock_adjustments`
--
ALTER TABLE `stock_adjustments`
  ADD CONSTRAINT `stock_adjustments_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `admin_and_users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `stock_adjustments_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_adjustments_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stock_adjustment_products`
--
ALTER TABLE `stock_adjustment_products`
  ADD CONSTRAINT `stock_adjustment_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_adjustment_products_product_variant_id_foreign` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_adjustment_products_stock_adjustment_id_foreign` FOREIGN KEY (`stock_adjustment_id`) REFERENCES `stock_adjustments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `supplier_ledgers`
--
ALTER TABLE `supplier_ledgers`
  ADD CONSTRAINT `supplier_ledgers_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `supplier_ledgers_purchase_payment_id_foreign` FOREIGN KEY (`purchase_payment_id`) REFERENCES `purchase_payments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `supplier_ledgers_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `supplier_products`
--
ALTER TABLE `supplier_products`
  ADD CONSTRAINT `supplier_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `supplier_products_product_variant_id_foreign` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `supplier_products_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE;

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
-- Constraints for table `todos`
--
ALTER TABLE `todos`
  ADD CONSTRAINT `todos_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `admin_and_users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `todos_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `todo_users`
--
ALTER TABLE `todo_users`
  ADD CONSTRAINT `todo_users_todo_id_foreign` FOREIGN KEY (`todo_id`) REFERENCES `todos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `todo_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `admin_and_users` (`id`) ON DELETE CASCADE;

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

--
-- Constraints for table `warehouses`
--
ALTER TABLE `warehouses`
  ADD CONSTRAINT `warehouses_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `workspaces`
--
ALTER TABLE `workspaces`
  ADD CONSTRAINT `workspaces_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `admin_and_users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `workspaces_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `workspace_attachments`
--
ALTER TABLE `workspace_attachments`
  ADD CONSTRAINT `workspace_attachments_workspace_id_foreign` FOREIGN KEY (`workspace_id`) REFERENCES `workspaces` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `workspace_tasks`
--
ALTER TABLE `workspace_tasks`
  ADD CONSTRAINT `workspace_tasks_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `admin_and_users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `workspace_tasks_workspace_id_foreign` FOREIGN KEY (`workspace_id`) REFERENCES `workspaces` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `workspace_users`
--
ALTER TABLE `workspace_users`
  ADD CONSTRAINT `workspace_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `admin_and_users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `workspace_users_workspace_id_foreign` FOREIGN KEY (`workspace_id`) REFERENCES `workspaces` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
