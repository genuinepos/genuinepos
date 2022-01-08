-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 08, 2022 at 06:49 AM
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

INSERT INTO `accounts` (`id`, `name`, `account_number`, `bank_id`, `opening_balance`, `debit`, `credit`, `balance`, `remark`, `status`, `admin_id`, `created_at`, `updated_at`) VALUES
(28, 'General Account', '012555578785', 8, '0.00', '4632558.40', '1892635.20', '-2739923.20', NULL, 1, 2, NULL, '2022-01-06 11:06:27');

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
  `e_commerce` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `addons`
--

INSERT INTO `addons` (`id`, `branches`, `hrm`, `todo`, `service`, `manufacturing`, `e_commerce`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 1, 1, 1, NULL, NULL);

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
(2, 'Mr', 'Super', 'Admin', NULL, 'superadmin', 'koalasoftsolution@gmail.com', NULL, 1, NULL, 8, 1, NULL, 1, '$2y$10$rd3uLXbr7OXtcZAh5VAj1u.nHtBpy0.gZx5HYXJ1uSR/TpT/nVBai', '0.00', '0.00', NULL, NULL, NULL, NULL, NULL, 'default.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'en', NULL, NULL, '0.00', NULL, '2021-04-07 07:04:03', '2021-08-24 08:04:00'),
(16, 'Mr', 'Sales', 'Man', NULL, 'salesman', 'example@gmail.com', NULL, 3, 19, NULL, 1, 33, 0, '$2y$10$0Gi8zVRnthOigLApghPypOdtWTk3W.Sz1mo78clia4SkosiC3F4Z2', '0.00', '0.00', '+88017000000', NULL, NULL, NULL, NULL, 'default.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Chandpur, Hazigong, Chattagram.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', NULL, '2021-10-25 11:20:51', '2021-10-28 04:37:10'),
(18, 'Mr.', 'Seller', NULL, '1008', 'seller', 'seller@gmail.com', 4, 3, 38, 29, 1, NULL, 0, '$2y$10$nrwhmIvjwwjZEkdPknpH.eg1c1vBB4IoLZ5VFIqstt4jA5QkwARiy', '0.00', '0.00', NULL, NULL, NULL, NULL, NULL, 'default.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, NULL, '15500.00', 'Monthly', '2022-01-04 12:18:38', '2022-01-04 12:18:38');

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
(22, 'Toyota car', 9, NULL, '3.00', '1000000.00', '3000000.00', NULL, NULL),
(23, 'Office Advance', 10, NULL, '1.00', '40000.00', '40000.00', NULL, NULL);

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
(9, 'Car', 'C-1', NULL, '2021-07-18 05:01:28'),
(10, 'Refundable', '11', NULL, NULL);

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
(2, 'Sticker Print, Continuous feed or rolls , Barcode Size: 38mm X 25mm', NULL, 1, 0.0000, 0.0000, 2.0000, 0.5000, 1.8000, 0.9843, 0.0000, 0.0000, 1, 1, 1, 1, NULL, '2021-07-01 09:40:09'),
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

--
-- Dumping data for table `branches`
--

INSERT INTO `branches` (`id`, `name`, `branch_code`, `phone`, `city`, `state`, `zip_code`, `alternate_phone_number`, `country`, `email`, `website`, `logo`, `invoice_schema_id`, `add_sale_invoice_layout_id`, `pos_sale_invoice_layout_id`, `default_account_id`, `purchase_permission`, `after_purchase_store`, `created_at`, `updated_at`) VALUES
(33, 'Computer Market Motijheel Branch', 'CMMB', '08801225444', 'Dhaka', 'Dhaka', '9000', NULL, 'Bangaldesh', NULL, NULL, 'default.png', 3, 1, 1, 28, 1, NULL, '2021-10-28 04:35:38', '2021-10-28 04:35:38');

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
(16, 'Ram', '2021-08-26 08:33:10', '2021-08-26 08:33:10');

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
(61, 16, '4GB', 0, '2021-08-26 08:33:10', '2021-10-27 07:43:44'),
(62, 16, '8GB', 0, '2021-08-26 08:33:10', '2021-10-27 07:43:44'),
(63, 16, '12GB', 0, '2021-08-26 08:33:10', '2021-10-27 07:43:44');

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
  `supplier_payment_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sale_payment_id` bigint(20) UNSIGNED DEFAULT NULL,
  `customer_payment_id` bigint(20) UNSIGNED DEFAULT NULL,
  `expanse_payment_id` bigint(20) UNSIGNED DEFAULT NULL,
  `money_receipt_id` bigint(20) UNSIGNED DEFAULT NULL,
  `payroll_id` bigint(20) UNSIGNED DEFAULT NULL,
  `payroll_payment_id` bigint(20) UNSIGNED DEFAULT NULL,
  `loan_id` bigint(20) UNSIGNED DEFAULT NULL,
  `debit` decimal(22,2) DEFAULT NULL,
  `credit` decimal(22,2) DEFAULT NULL,
  `balance` decimal(22,2) NOT NULL DEFAULT 0.00,
  `transaction_type` tinyint(4) NOT NULL COMMENT '1=payment;2=sale_payment;3=purchase_payment;4=fundTransfer;5=deposit;6=expansePayment;7=openingBalance;8=payroll_payment;9=money_receipt;10=loan-get/pay;11=loan_ins_payment/receive;12=supplier_payment;13=customer_payment',
  `cash_type` tinyint(4) DEFAULT NULL COMMENT '1=debit;2=credit;',
  `date` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `month` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `year` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `report_date` timestamp NULL DEFAULT NULL,
  `admin_id` bigint(20) UNSIGNED DEFAULT NULL,
  `related_cash_flow_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `loan_payment_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cash_flows`
--

INSERT INTO `cash_flows` (`id`, `account_id`, `sender_account_id`, `receiver_account_id`, `purchase_payment_id`, `supplier_payment_id`, `sale_payment_id`, `customer_payment_id`, `expanse_payment_id`, `money_receipt_id`, `payroll_id`, `payroll_payment_id`, `loan_id`, `debit`, `credit`, `balance`, `transaction_type`, `cash_type`, `date`, `month`, `year`, `report_date`, `admin_id`, `related_cash_flow_id`, `created_at`, `updated_at`, `loan_payment_id`) VALUES
(469, 28, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', 7, 2, '2021-10-27', 'October', '2021', '2021-10-26 18:00:00', 2, NULL, '2021-10-27 07:44:21', '2021-10-27 07:44:21', NULL),
(475, 28, NULL, NULL, NULL, NULL, 536, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '600.00', '600.00', 2, 2, '27-10-2021', 'October', '2021', '2021-10-26 18:00:00', 2, NULL, '2021-10-27 07:48:30', '2021-10-27 07:48:30', NULL),
(476, 28, NULL, NULL, NULL, NULL, 551, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '20300.00', '20900.00', 2, 2, '08-11-2021', 'November', '2021', '2021-11-07 18:00:00', 2, NULL, '2021-11-08 10:58:41', '2021-11-08 10:58:41', NULL),
(477, 28, NULL, NULL, NULL, 124, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '36500.00', NULL, '-15600.00', 12, 1, '13-11-2021', 'November', '2021', '2021-11-12 18:00:00', 2, NULL, '2021-11-13 04:29:39', '2021-11-13 04:29:39', NULL),
(478, 28, NULL, NULL, NULL, 125, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1000.00', '-14600.00', 12, 2, '13-11-21', 'November', '2021', '2021-11-12 18:00:00', 2, NULL, '2021-11-13 04:36:38', '2021-11-13 04:36:38', NULL),
(479, 28, NULL, NULL, 419, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '18500.00', '3900.00', 3, 2, '13-11-2021', 'November', '2021', '2021-11-12 18:00:00', 2, NULL, '2021-11-13 05:19:48', '2021-11-13 05:19:48', NULL),
(480, 28, NULL, NULL, NULL, NULL, 572, NULL, NULL, NULL, NULL, NULL, NULL, '33749.70', NULL, '-29849.70', 2, 1, '13-11-2021', 'November', '2021', '2021-11-12 18:00:00', 2, NULL, '2021-11-13 05:21:08', '2021-11-13 05:21:08', NULL),
(481, 28, NULL, NULL, 420, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '4510000.00', NULL, '-4539849.70', 3, 1, '14-11-2021', 'November', '2021', '2021-11-13 18:00:00', 2, NULL, '2021-11-14 08:46:58', '2021-11-14 08:46:58', NULL),
(482, 28, NULL, NULL, 422, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '44558.70', NULL, '-4584408.40', 3, 1, '15-11-2021', 'November', '2021', '2021-11-14 18:00:00', 2, NULL, '2021-11-15 08:32:55', '2021-11-15 08:32:55', NULL),
(483, 28, NULL, NULL, NULL, NULL, 567, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '33749.70', '-4550658.70', 2, 2, '11-11-2021', 'November', '2021', '2021-11-10 18:00:00', 2, NULL, '2021-11-16 07:27:43', '2021-11-16 07:27:43', NULL),
(484, 28, NULL, NULL, NULL, NULL, NULL, 59, NULL, NULL, NULL, NULL, NULL, NULL, '1687500.00', '-2863158.70', 13, 2, '20-11-2021', 'November', '2021', '2021-11-19 18:00:00', 2, NULL, '2021-11-20 05:34:47', '2021-11-20 05:34:47', NULL),
(485, 28, NULL, NULL, 423, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '500.00', NULL, '-2863658.70', 3, 1, '22-11-2021', 'November', '2021', '2021-11-21 18:00:00', 2, NULL, '2021-11-22 08:14:06', '2021-11-22 08:14:06', NULL),
(486, 28, NULL, NULL, NULL, NULL, 581, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '100.00', '-2863558.70', 2, 2, '19-12-2021', 'December', '2021', '2021-12-18 18:00:00', 2, NULL, '2021-12-19 08:08:44', '2021-12-19 08:08:44', NULL),
(487, 28, NULL, NULL, NULL, 126, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '6000.00', NULL, '-2869558.70', 12, 1, '27-12-2021', 'December', '2021', '2021-12-26 18:00:00', 2, NULL, '2021-12-27 06:30:45', '2021-12-27 06:30:45', NULL),
(488, 28, NULL, NULL, NULL, NULL, 582, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '800.00', '-2868758.70', 2, 2, '28-12-2021', 'December', '2021', '2021-12-27 18:00:00', 2, NULL, '2021-12-28 06:11:07', '2021-12-28 06:11:07', NULL),
(489, 28, NULL, NULL, NULL, NULL, 583, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '120.00', '-2868638.70', 2, 2, '28-12-2021', 'December', '2021', '2021-12-27 18:00:00', 2, NULL, '2021-12-28 06:13:47', '2021-12-28 06:13:47', NULL),
(490, 28, NULL, NULL, NULL, NULL, 584, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '750.00', '-2867888.70', 2, 2, '28-12-2021', 'December', '2021', '2021-12-27 18:00:00', 2, NULL, '2021-12-28 06:27:58', '2021-12-28 06:27:58', NULL),
(491, 28, NULL, NULL, NULL, NULL, 585, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '770.00', '-2867118.70', 2, 2, '28-12-2021', 'December', '2021', '2021-12-27 18:00:00', 2, NULL, '2021-12-28 06:33:23', '2021-12-28 06:33:23', NULL),
(492, 28, NULL, NULL, NULL, NULL, 586, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '190.00', '-2866928.70', 2, 2, '28-12-2021', 'December', '2021', '2021-12-27 18:00:00', 2, NULL, '2021-12-28 06:55:07', '2021-12-28 06:55:07', NULL),
(493, 28, NULL, NULL, NULL, NULL, 587, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '400.00', '-2866528.70', 2, 2, '02-01-2022', 'January', '2022', '2022-01-01 18:00:00', 2, NULL, '2022-01-02 08:10:54', '2022-01-02 08:10:54', NULL),
(494, 28, NULL, NULL, NULL, NULL, 588, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '10.00', '-2866518.70', 2, 2, '02-01-2022', 'January', '2022', '2022-01-01 18:00:00', 2, NULL, '2022-01-02 08:22:35', '2022-01-02 08:22:35', NULL),
(495, 28, NULL, NULL, NULL, NULL, 589, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '146.00', '-2866372.70', 2, 2, '02-01-2022', 'January', '2022', '2022-01-01 18:00:00', 2, NULL, '2022-01-02 08:43:09', '2022-01-02 08:43:09', NULL),
(496, 28, NULL, NULL, NULL, NULL, 590, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '190.00', '-2866182.70', 2, 2, '02-01-2022', 'January', '2022', '2022-01-01 18:00:00', 2, NULL, '2022-01-02 08:43:55', '2022-01-02 08:43:55', NULL),
(497, 28, NULL, NULL, NULL, NULL, 591, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '150.00', '-2866032.70', 2, 2, '02-01-2022', 'January', '2022', '2022-01-01 18:00:00', 2, NULL, '2022-01-02 08:51:40', '2022-01-02 08:51:40', NULL),
(498, 28, NULL, NULL, NULL, NULL, 592, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '136.00', '-2865896.70', 2, 2, '02-01-2022', 'January', '2022', '2022-01-01 18:00:00', 2, NULL, '2022-01-02 10:24:47', '2022-01-02 10:24:47', NULL),
(499, 28, NULL, NULL, NULL, NULL, 593, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '30802.50', '-2835094.20', 2, 2, '02-01-2022', 'January', '2022', '2022-01-01 18:00:00', 2, NULL, '2022-01-02 11:26:08', '2022-01-02 11:26:08', NULL),
(500, 28, NULL, NULL, NULL, NULL, 594, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '197.50', '-2834896.70', 2, 2, '02-01-2022', 'January', '2022', '2022-01-01 18:00:00', 2, NULL, '2022-01-02 11:26:08', '2022-01-02 11:26:08', NULL),
(501, 28, NULL, NULL, NULL, NULL, 595, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '30802.50', '-2804094.20', 2, 2, '02-01-2022', 'January', '2022', '2022-01-01 18:00:00', 2, NULL, '2022-01-02 11:32:00', '2022-01-02 11:32:00', NULL),
(502, 28, NULL, NULL, NULL, NULL, 596, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '30802.50', '-2773291.70', 2, 2, '02-01-2022', 'January', '2022', '2022-01-01 18:00:00', 2, NULL, '2022-01-02 11:39:38', '2022-01-02 11:39:38', NULL),
(504, 28, NULL, NULL, NULL, NULL, 599, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '30802.50', '-2742489.20', 2, 2, '03-01-2022', 'January', '2022', '2022-01-02 18:00:00', 2, NULL, '2022-01-03 04:50:52', '2022-01-03 04:50:52', NULL),
(506, 28, NULL, NULL, NULL, NULL, 601, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '136.00', '-2742303.20', 2, 2, '03-01-2022', 'January', '2022', '2022-01-02 18:00:00', 2, NULL, '2022-01-03 05:35:58', '2022-01-03 05:35:58', NULL),
(507, 28, NULL, NULL, NULL, NULL, 602, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '136.00', '-2742167.20', 2, 2, '03-01-2022', 'January', '2022', '2022-01-02 18:00:00', 2, NULL, '2022-01-03 05:37:21', '2022-01-03 05:37:21', NULL),
(508, 28, NULL, NULL, NULL, NULL, 603, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '200.00', '-2741967.20', 2, 2, '03-01-2022', 'January', '2022', '2022-01-02 18:00:00', 2, NULL, '2022-01-03 07:28:22', '2022-01-03 07:28:22', NULL),
(509, 28, NULL, NULL, NULL, NULL, 604, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '300.00', '-2741667.20', 2, 2, '03-01-2022', 'January', '2022', '2022-01-02 18:00:00', 2, NULL, '2022-01-03 07:28:46', '2022-01-03 07:28:46', NULL),
(510, 28, NULL, NULL, NULL, NULL, 605, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '200.00', '-2741467.20', 2, 2, '03-01-2022', 'January', '2022', '2022-01-02 18:00:00', 2, NULL, '2022-01-03 07:37:47', '2022-01-03 07:37:48', NULL),
(513, 28, NULL, NULL, NULL, NULL, NULL, 62, NULL, NULL, NULL, NULL, NULL, '1150.00', NULL, '-2744967.20', 13, 1, '04-01-22', 'January', '2022', '2022-01-03 18:00:00', 2, NULL, '2022-01-04 12:08:31', '2022-01-04 12:08:31', NULL),
(514, 28, NULL, NULL, NULL, NULL, 612, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '640.00', '-2742027.20', 2, 2, '05-01-2022', 'January', '2022', '2022-01-04 18:00:00', 2, NULL, '2022-01-05 05:30:19', '2022-01-05 05:30:19', NULL),
(515, 28, NULL, NULL, NULL, NULL, 614, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '200.00', '-2741827.20', 2, 2, '05-01-2022', 'January', '2022', '2022-01-04 18:00:00', 2, NULL, '2022-01-05 10:00:04', '2022-01-05 10:00:04', NULL),
(516, 28, NULL, NULL, NULL, NULL, 615, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '100.00', '-2741727.20', 2, 2, '05-01-2022', 'January', '2022', '2022-01-04 18:00:00', 2, NULL, '2022-01-05 11:34:21', '2022-01-05 11:34:21', NULL),
(517, 28, NULL, NULL, NULL, NULL, NULL, NULL, 79, NULL, NULL, NULL, NULL, '100.00', NULL, '-2741827.20', 6, 1, '06-01-2022', 'January', '2022', '2022-01-05 18:00:00', 2, NULL, '2022-01-06 05:31:09', '2022-01-06 05:31:09', NULL),
(518, 28, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1000.00', '-2740827.20', 5, 2, '06-01-2022', 'January', '2022', '2022-01-05 18:00:00', 2, NULL, '2022-01-06 08:11:46', '2022-01-06 08:11:46', NULL),
(519, 28, NULL, NULL, NULL, NULL, 620, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '904.00', '-2739923.20', 2, 2, '06-01-2022', 'January', '2022', '2022-01-05 18:00:00', 2, NULL, '2022-01-06 11:06:27', '2022-01-06 11:06:27', NULL);

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
(34, NULL, NULL, NULL, 2, '2021-12-19 07:33:00', '363857.50', 0, 0, 0, NULL, '2021-08-12 08:09:35', '2021-12-19 07:33:48', NULL),
(37, NULL, NULL, 28, 2, '2022-01-06 11:04:00', '161084.00', 0, 0, 0, NULL, '2021-12-19 07:33:54', '2022-01-06 11:04:44', NULL),
(38, NULL, NULL, NULL, 18, NULL, '0.00', NULL, NULL, 1, NULL, '2022-01-05 08:47:14', '2022-01-05 08:47:14', NULL),
(39, NULL, NULL, 28, 2, NULL, '0.00', NULL, NULL, 1, NULL, '2022-01-06 11:06:09', '2022-01-06 11:06:09', NULL);

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
(140, 34, NULL, 2, 1, '0.00', '2021-08-12 08:09:35', '2021-08-12 08:09:35'),
(171, 34, 582, 2, 2, NULL, '2021-10-26 05:29:02', '2021-10-26 05:29:02'),
(172, 34, 593, 2, 2, NULL, '2021-10-27 08:39:03', '2021-10-27 08:39:03'),
(173, 34, 596, 2, 2, NULL, '2021-10-30 05:34:34', '2021-10-30 05:34:34'),
(174, 34, 597, 2, 2, NULL, '2021-10-30 05:35:34', '2021-10-30 05:35:34'),
(175, 34, 598, 2, 2, NULL, '2021-10-30 05:36:35', '2021-10-30 05:36:35'),
(176, 34, 599, 2, 2, NULL, '2021-10-30 05:37:21', '2021-10-30 05:37:21'),
(177, 34, 600, 2, 2, NULL, '2021-10-30 05:38:44', '2021-10-30 05:38:44'),
(178, 34, 601, 2, 2, NULL, '2021-10-30 05:50:20', '2021-10-30 05:50:20'),
(179, 34, 602, 2, 2, NULL, '2021-10-30 05:50:53', '2021-10-30 05:50:53'),
(180, 34, 603, 2, 2, NULL, '2021-10-30 05:51:31', '2021-10-30 05:51:31'),
(181, 34, 604, 2, 2, NULL, '2021-10-30 05:59:37', '2021-10-30 05:59:37'),
(182, 34, 605, 2, 2, NULL, '2021-10-30 06:00:24', '2021-10-30 06:00:24'),
(183, 34, 606, 2, 2, NULL, '2021-11-08 11:03:30', '2021-11-08 11:03:30'),
(184, 34, 609, 2, 2, NULL, '2021-11-09 04:41:35', '2021-11-09 04:41:35'),
(185, 34, 610, 2, 2, NULL, '2021-11-09 04:43:03', '2021-11-09 04:43:03'),
(186, 34, 611, 2, 2, NULL, '2021-11-09 04:44:10', '2021-11-09 04:44:10'),
(187, 34, 612, 2, 2, NULL, '2021-11-09 04:45:13', '2021-11-09 04:45:13'),
(188, 34, 613, 2, 2, NULL, '2021-11-09 04:46:17', '2021-11-09 04:46:17'),
(189, 34, 614, 2, 2, NULL, '2021-11-09 04:47:56', '2021-11-09 04:47:56'),
(190, 34, 615, 2, 2, NULL, '2021-11-09 04:48:59', '2021-11-09 04:48:59'),
(191, 34, 616, 2, 2, NULL, '2021-11-09 04:49:54', '2021-11-09 04:49:54'),
(192, 34, 621, 2, 2, NULL, '2021-11-10 13:16:27', '2021-11-10 13:16:27'),
(193, 34, 623, 2, 2, NULL, '2021-11-11 10:42:21', '2021-11-11 10:42:21'),
(194, 34, 624, 2, 2, NULL, '2021-11-11 11:02:54', '2021-11-11 11:02:54'),
(195, 34, 626, 2, 2, NULL, '2021-11-13 08:35:17', '2021-11-13 08:35:17'),
(196, 34, 627, 2, 2, NULL, '2021-11-17 04:40:55', '2021-11-17 04:40:55'),
(197, 34, 628, 2, 2, NULL, '2021-11-17 04:56:38', '2021-11-17 04:56:38'),
(198, 34, 630, 2, 2, NULL, '2021-11-18 12:24:46', '2021-11-18 12:24:46'),
(199, 34, 632, 2, 2, NULL, '2021-11-20 05:19:11', '2021-11-20 05:19:11'),
(200, 34, 635, 2, 2, NULL, '2021-12-14 04:41:19', '2021-12-14 04:41:19'),
(201, 34, 636, 2, 2, NULL, '2021-12-14 04:45:07', '2021-12-14 04:45:07'),
(202, 37, NULL, 2, 1, '0.00', '2021-12-19 07:33:54', '2021-12-19 07:33:54'),
(203, 37, 637, 2, 2, NULL, '2021-12-19 08:08:44', '2021-12-19 08:08:44'),
(204, 37, 638, 2, 2, NULL, '2021-12-28 06:11:07', '2021-12-28 06:11:07'),
(205, 37, 639, 2, 2, NULL, '2021-12-28 06:13:47', '2021-12-28 06:13:47'),
(206, 37, 640, 2, 2, NULL, '2021-12-28 06:19:26', '2021-12-28 06:19:26'),
(207, 37, 641, 2, 2, NULL, '2021-12-28 06:27:58', '2021-12-28 06:27:58'),
(208, 37, 642, 2, 2, NULL, '2021-12-28 06:33:23', '2021-12-28 06:33:23'),
(209, 37, 643, 2, 2, NULL, '2021-12-28 06:55:07', '2021-12-28 06:55:07'),
(210, 37, 644, 2, 2, NULL, '2022-01-02 08:10:54', '2022-01-02 08:10:54'),
(211, 37, 645, 2, 2, NULL, '2022-01-02 08:22:35', '2022-01-02 08:22:35'),
(212, 37, 646, 2, 2, NULL, '2022-01-02 08:43:09', '2022-01-02 08:43:09'),
(213, 37, 647, 2, 2, NULL, '2022-01-02 10:24:47', '2022-01-02 10:24:47'),
(214, 37, 648, 2, 2, NULL, '2022-01-02 11:26:08', '2022-01-02 11:26:08'),
(215, 37, 649, 2, 2, NULL, '2022-01-02 11:32:00', '2022-01-02 11:32:00'),
(216, 37, 650, 2, 2, NULL, '2022-01-02 11:39:38', '2022-01-02 11:39:38'),
(217, 37, 651, 2, 2, NULL, '2022-01-02 11:58:13', '2022-01-02 11:58:13'),
(218, 37, 652, 2, 2, NULL, '2022-01-03 04:53:17', '2022-01-03 04:53:17'),
(219, 37, 653, 2, 2, NULL, '2022-01-03 05:35:58', '2022-01-03 05:35:58'),
(220, 37, 654, 2, 2, NULL, '2022-01-03 05:37:21', '2022-01-03 05:37:21'),
(221, 37, 655, 2, 2, NULL, '2022-01-03 07:28:22', '2022-01-03 07:28:22'),
(222, 37, 656, 2, 2, NULL, '2022-01-03 07:28:46', '2022-01-03 07:28:46'),
(223, 37, 657, 2, 2, NULL, '2022-01-03 07:37:48', '2022-01-03 07:37:48'),
(224, 37, 658, 2, 2, NULL, '2022-01-05 05:30:19', '2022-01-05 05:30:19'),
(225, 38, NULL, 2, 1, '1000.00', '2022-01-05 08:47:14', '2022-01-05 08:47:14'),
(226, 38, 659, 2, 2, NULL, '2022-01-05 08:47:33', '2022-01-05 08:47:33'),
(227, 37, 660, 2, 2, NULL, '2022-01-05 10:00:04', '2022-01-05 10:00:04'),
(228, 37, 664, 2, 2, NULL, '2022-01-05 11:34:21', '2022-01-05 11:34:21'),
(229, 39, NULL, 2, 1, '100.00', '2022-01-06 11:06:09', '2022-01-06 11:06:09'),
(230, 39, 668, 2, 2, NULL, '2022-01-06 11:06:27', '2022-01-06 11:06:27');

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
(113, 'Electronics', NULL, 'default.png', 1, NULL, NULL),
(114, 'Glossary', NULL, 'default.png', 1, NULL, NULL),
(115, 'Food', NULL, 'default.png', 1, NULL, NULL),
(116, 'Mobile', 113, 'default.png', 1, NULL, NULL),
(117, 'Laptop', 113, 'default.png', 1, NULL, NULL),
(118, 'Computer accessories', 113, 'default.png', 1, NULL, NULL),
(119, 'Build Materials', NULL, 'default.png', 1, '2021-11-13 07:34:42', '2021-11-13 07:34:42');

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
(134, 'Bangladesh', 'Taka', 'BDT', 'TK.', ',', '.', NULL, NULL),
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
  `credit_limit` decimal(22,2) DEFAULT NULL,
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
  `total_return` decimal(8,2) NOT NULL DEFAULT 0.00,
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

INSERT INTO `customers` (`id`, `contact_id`, `customer_group_id`, `name`, `business_name`, `phone`, `alternative_phone`, `landline`, `email`, `date_of_birth`, `tax_number`, `opening_balance`, `credit_limit`, `pay_term`, `pay_term_number`, `address`, `shipping_address`, `city`, `state`, `country`, `zip_code`, `total_sale`, `total_paid`, `total_sale_due`, `total_return`, `total_sale_return_due`, `point`, `status`, `is_walk_in_customer`, `created_at`, `updated_at`) VALUES
(91, '23768', NULL, 'Mr. Grims', 'X Company', '1002525', NULL, NULL, NULL, NULL, NULL, '1000.00', NULL, 1, NULL, 'Dhaka, Bangladesh', NULL, NULL, NULL, NULL, NULL, '369750.00', '371900.00', '0.00', '0.00', '0.00', '2327.00', 1, 0, '2021-09-16 07:12:20', '2022-01-04 12:09:16'),
(102, '0092', NULL, 'Mr. Joo', NULL, '08801700000', NULL, NULL, NULL, NULL, NULL, '0.00', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '200.00', '200.00', '0.00', '0.00', '0.00', '0.00', 1, 0, '2021-11-09 07:56:29', '2022-01-03 07:28:22'),
(103, '0103', NULL, 'Andres', NULL, '0185200000', NULL, NULL, NULL, NULL, NULL, '0.00', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1707650.00', '1707500.00', '150.00', '0.00', '0.00', '15.00', 1, 0, '2021-11-10 06:06:29', '2021-12-14 04:45:07'),
(104, '0104', NULL, 'Mr. Customer', NULL, '08801225444', NULL, NULL, NULL, NULL, NULL, '2000.00', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '10100.00', '0.00', '12100.00', '0.00', '0.00', '0.00', 1, 0, '2021-11-14 06:48:16', '2021-12-14 04:40:07'),
(105, '0105', NULL, 'Build Materials', NULL, '455522', NULL, NULL, NULL, NULL, NULL, '0.00', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 1, 0, '2021-11-14 06:55:13', '2021-11-14 06:55:13'),
(106, '0106', NULL, 'Test Customer', NULL, '01256665787', NULL, NULL, NULL, NULL, NULL, '0.00', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '92507.50', '92507.50', '0.00', '0.00', '0.00', '0.00', 1, 0, '2021-11-18 11:57:15', '2021-12-12 08:35:06'),
(107, '0107', NULL, 'Vutta', NULL, '0125254444774', NULL, NULL, NULL, NULL, NULL, '0.00', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 1, 0, '2021-12-20 12:46:04', '2021-12-20 12:46:04'),
(108, '0108', NULL, 'Khoil', NULL, '0125254444774', NULL, NULL, NULL, NULL, NULL, '0.00', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 1, 0, '2021-12-20 12:47:32', '2021-12-20 12:47:32'),
(109, '0109', NULL, 'Khoil', NULL, '0125254444774', NULL, NULL, NULL, NULL, NULL, '0.00', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 1, 0, '2021-12-20 12:47:32', '2021-12-20 12:47:32'),
(110, '0110', NULL, 'Khoil', NULL, '0125254444774', NULL, NULL, NULL, NULL, NULL, '0.00', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 1, 0, '2021-12-20 12:47:57', '2021-12-20 12:47:57'),
(111, '0111', NULL, 'Khoil', NULL, '0125254444774', NULL, NULL, NULL, NULL, NULL, '1000.00', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '1000.00', '0.00', '0.00', '0.00', 1, 0, '2021-12-20 12:48:52', '2021-12-20 12:48:52'),
(112, '0112', NULL, 'Khoil', NULL, '0125254444774', NULL, NULL, NULL, NULL, NULL, '1000.00', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '1000.00', '0.00', '0.00', '0.00', 1, 0, '2021-12-20 12:48:52', '2021-12-20 12:48:52'),
(113, '0113', NULL, 'Khoil', NULL, '0125254444774', NULL, NULL, NULL, NULL, NULL, '1000.00', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '1000.00', '0.00', '0.00', '0.00', 1, 0, '2021-12-20 12:49:54', '2021-12-20 12:49:54'),
(114, '0114', NULL, 'Exchange Customer', NULL, '01258844', NULL, NULL, NULL, NULL, NULL, '0.00', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '31522.50', '31522.50', '0.00', '0.00', '0.00', '80.00', 1, 0, '2022-01-02 08:07:22', '2022-01-06 06:19:04');

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
(8, 'Bronze Customer', '2.00', NULL, NULL),
(9, 'Silver Group', '1.00', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `customer_ledgers`
--

CREATE TABLE `customer_ledgers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sale_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sale_payment_id` bigint(20) UNSIGNED DEFAULT NULL,
  `customer_payment_id` bigint(20) UNSIGNED DEFAULT NULL,
  `money_receipt_id` bigint(20) UNSIGNED DEFAULT NULL,
  `row_type` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1=sale;2=sale_payment;3=opening_balance;4=money_receipt;5=supplier_payment',
  `amount` decimal(22,2) DEFAULT NULL COMMENT 'only_for_opening_balance',
  `report_date` timestamp NULL DEFAULT NULL,
  `is_advanced` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'only_for_money_receipt',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customer_ledgers`
--

INSERT INTO `customer_ledgers` (`id`, `customer_id`, `sale_id`, `sale_payment_id`, `customer_payment_id`, `money_receipt_id`, `row_type`, `amount`, `report_date`, `is_advanced`, `created_at`, `updated_at`) VALUES
(730, 91, NULL, NULL, NULL, NULL, 3, '1000.00', NULL, 0, '2021-09-16 07:12:20', '2021-09-16 07:12:20'),
(857, 91, 582, NULL, NULL, NULL, 1, NULL, '2021-10-25 18:00:00', 0, '2021-10-26 05:29:02', '2021-10-26 05:29:02'),
(858, 91, NULL, 534, NULL, NULL, 2, NULL, '2021-10-25 18:00:00', 0, '2021-10-26 05:29:02', '2021-10-26 05:29:02'),
(859, 91, NULL, NULL, 58, NULL, 5, NULL, '2021-10-25 18:00:00', 0, '2021-10-26 05:29:02', '2021-10-26 05:29:02'),
(869, 91, 592, NULL, NULL, NULL, 1, NULL, '2021-10-26 18:00:00', 0, '2021-10-27 07:21:05', '2021-10-27 07:21:05'),
(870, 91, NULL, 536, NULL, NULL, 2, NULL, '2021-10-26 18:00:00', 0, '2021-10-27 07:48:30', '2021-10-27 07:48:30'),
(871, 91, 593, NULL, NULL, NULL, 1, NULL, '2021-10-26 18:00:00', 0, '2021-10-27 08:39:03', '2021-10-27 08:39:03'),
(872, 91, 594, NULL, NULL, NULL, 1, NULL, '2021-10-26 18:00:00', 0, '2021-10-27 10:10:53', '2021-10-27 10:10:53'),
(873, 91, NULL, 537, NULL, NULL, 2, NULL, '2021-10-26 18:00:00', 0, '2021-10-27 10:10:53', '2021-10-27 10:10:53'),
(874, 91, NULL, 538, NULL, NULL, 2, NULL, '2021-10-26 18:00:00', 0, '2021-10-27 10:10:53', '2021-10-27 10:10:53'),
(875, 91, 596, NULL, NULL, NULL, 1, NULL, '2021-10-29 18:00:00', 0, '2021-10-30 05:34:34', '2021-10-30 05:34:34'),
(876, 91, NULL, 540, NULL, NULL, 2, NULL, '2021-10-29 18:00:00', 0, '2021-10-30 05:34:34', '2021-10-30 05:34:34'),
(877, 91, NULL, 541, NULL, NULL, 2, NULL, '2021-10-29 18:00:00', 0, '2021-10-30 05:34:34', '2021-10-30 05:34:34'),
(878, 91, 597, NULL, NULL, NULL, 1, NULL, '2021-10-29 18:00:00', 0, '2021-10-30 05:35:34', '2021-10-30 05:35:34'),
(879, 91, NULL, 542, NULL, NULL, 2, NULL, '2021-10-29 18:00:00', 0, '2021-10-30 05:35:34', '2021-10-30 05:35:34'),
(880, 91, 599, NULL, NULL, NULL, 1, NULL, '2021-10-29 18:00:00', 0, '2021-10-30 05:37:20', '2021-10-30 05:37:20'),
(881, 91, NULL, 544, NULL, NULL, 2, NULL, '2021-10-29 18:00:00', 0, '2021-10-30 05:37:21', '2021-10-30 05:37:21'),
(882, 91, 603, NULL, NULL, NULL, 1, NULL, '2021-10-29 18:00:00', 0, '2021-10-30 05:51:30', '2021-10-30 05:51:30'),
(883, 91, NULL, 548, NULL, NULL, 2, NULL, '2021-10-29 18:00:00', 0, '2021-10-30 05:51:31', '2021-10-30 05:51:31'),
(884, 91, 607, NULL, NULL, NULL, 1, NULL, '2021-11-08 18:00:00', 0, '2021-11-09 04:36:54', '2021-11-09 04:36:54'),
(885, 91, 608, NULL, NULL, NULL, 1, NULL, '2021-11-08 18:00:00', 0, '2021-11-09 04:37:48', '2021-11-09 04:37:48'),
(886, 91, 609, NULL, NULL, NULL, 1, NULL, '2021-11-08 18:00:00', 0, '2021-11-09 04:41:35', '2021-11-09 04:41:35'),
(887, 91, NULL, 553, NULL, NULL, 2, NULL, '2021-11-08 18:00:00', 0, '2021-11-09 04:41:35', '2021-11-09 04:41:35'),
(888, 91, NULL, 554, NULL, NULL, 2, NULL, '2021-11-08 18:00:00', 0, '2021-11-09 04:41:35', '2021-11-09 04:41:35'),
(889, 91, NULL, 555, NULL, NULL, 2, NULL, '2021-11-08 18:00:00', 0, '2021-11-09 04:41:35', '2021-11-09 04:41:35'),
(890, 91, 610, NULL, NULL, NULL, 1, NULL, '2021-11-08 18:00:00', 0, '2021-11-09 04:43:02', '2021-11-09 04:43:02'),
(891, 91, NULL, 556, NULL, NULL, 2, NULL, '2021-11-08 18:00:00', 0, '2021-11-09 04:43:03', '2021-11-09 04:43:03'),
(892, 102, NULL, NULL, NULL, NULL, 3, '0.00', '2021-11-08 18:00:00', 0, '2021-11-09 07:56:29', '2021-11-09 07:56:29'),
(893, 103, NULL, NULL, NULL, NULL, 3, '0.00', '2021-11-09 18:00:00', 0, '2021-11-10 06:06:29', '2021-11-10 06:06:29'),
(894, 103, 620, NULL, NULL, NULL, 1, NULL, '2021-11-09 18:00:00', 0, '2021-11-10 10:53:52', '2021-11-10 10:53:52'),
(895, 103, NULL, 565, NULL, NULL, 2, NULL, '2021-11-09 18:00:00', 0, '2021-11-10 10:53:52', '2021-11-10 10:53:52'),
(896, 91, 623, NULL, NULL, NULL, 1, NULL, '2021-11-10 18:00:00', 0, '2021-11-11 10:42:20', '2021-11-11 10:42:20'),
(897, 91, NULL, 568, NULL, NULL, 2, NULL, '2021-11-10 18:00:00', 0, '2021-11-11 10:42:20', '2021-11-11 10:42:20'),
(898, 91, NULL, 569, NULL, NULL, 2, NULL, '2021-11-10 18:00:00', 0, '2021-11-11 10:42:21', '2021-11-11 10:42:21'),
(899, 103, 625, NULL, NULL, NULL, 1, NULL, '2021-11-10 18:00:00', 0, '2021-11-11 12:56:32', '2021-11-11 12:56:32'),
(900, 104, NULL, NULL, NULL, NULL, 3, '2000.00', '2021-11-13 18:00:00', 0, '2021-11-14 06:48:16', '2021-11-14 06:48:16'),
(901, 105, NULL, NULL, NULL, NULL, 3, '0.00', '2021-11-13 18:00:00', 0, '2021-11-14 06:55:13', '2021-11-14 06:55:13'),
(902, 106, NULL, NULL, NULL, NULL, 3, '0.00', '2021-11-17 18:00:00', 0, '2021-11-18 11:57:15', '2021-11-18 11:57:15'),
(903, 106, 629, NULL, NULL, NULL, 1, NULL, '2021-11-17 18:00:00', 0, '2021-11-18 11:59:41', '2021-11-18 11:59:41'),
(904, 106, 630, NULL, NULL, NULL, 1, NULL, '2021-11-17 18:00:00', 0, '2021-11-18 12:25:12', '2021-11-18 12:25:12'),
(905, 106, NULL, 575, NULL, NULL, 2, NULL, '2021-11-17 18:00:00', 0, '2021-11-18 12:25:12', '2021-11-18 12:25:12'),
(906, 106, 631, NULL, NULL, NULL, 1, NULL, '2021-11-19 18:00:00', 0, '2021-11-20 04:47:13', '2021-11-20 04:47:13'),
(907, 103, NULL, NULL, 59, NULL, 5, NULL, '2021-11-19 18:00:00', 0, '2021-11-20 05:34:47', '2021-11-20 05:34:47'),
(908, 106, 633, NULL, NULL, NULL, 1, NULL, '2021-12-11 18:00:00', 0, '2021-12-12 08:35:06', '2021-12-12 08:35:06'),
(909, 106, NULL, 579, NULL, NULL, 2, NULL, '2021-12-11 18:00:00', 0, '2021-12-12 08:35:06', '2021-12-12 08:35:06'),
(910, 106, NULL, 580, NULL, NULL, 2, NULL, '2021-12-11 18:00:00', 0, '2021-12-12 08:35:06', '2021-12-12 08:35:06'),
(911, 104, 634, NULL, NULL, NULL, 1, NULL, '2021-12-13 18:00:00', 0, '2021-12-14 04:40:07', '2021-12-14 04:40:07'),
(912, 103, 636, NULL, NULL, NULL, 1, NULL, '2021-12-13 18:00:00', 0, '2021-12-14 04:45:07', '2021-12-14 04:45:07'),
(913, 107, NULL, NULL, NULL, NULL, 3, '0.00', '2021-12-19 18:00:00', 0, '2021-12-20 12:46:04', '2021-12-20 12:46:04'),
(914, 108, NULL, NULL, NULL, NULL, 3, '0.00', '2021-12-19 18:00:00', 0, '2021-12-20 12:47:32', '2021-12-20 12:47:32'),
(915, 109, NULL, NULL, NULL, NULL, 3, '0.00', '2021-12-19 18:00:00', 0, '2021-12-20 12:47:32', '2021-12-20 12:47:32'),
(916, 110, NULL, NULL, NULL, NULL, 3, '0.00', '2021-12-19 18:00:00', 0, '2021-12-20 12:47:57', '2021-12-20 12:47:57'),
(917, 111, NULL, NULL, NULL, NULL, 3, '1000.00', '2021-12-19 18:00:00', 0, '2021-12-20 12:48:52', '2021-12-20 12:48:52'),
(918, 112, NULL, NULL, NULL, NULL, 3, '1000.00', '2021-12-19 18:00:00', 0, '2021-12-20 12:48:52', '2021-12-20 12:48:52'),
(919, 113, NULL, NULL, NULL, NULL, 3, '1000.00', '2021-12-19 18:00:00', 0, '2021-12-20 12:49:54', '2021-12-20 12:49:54'),
(920, 91, 640, NULL, NULL, NULL, 1, NULL, '2021-12-27 18:00:00', 0, '2021-12-28 06:19:26', '2021-12-28 06:19:26'),
(921, 114, NULL, NULL, NULL, NULL, 3, '0.00', '2022-01-01 18:00:00', 0, '2022-01-02 08:07:23', '2022-01-02 08:07:23'),
(922, 114, 644, NULL, NULL, NULL, 1, NULL, '2022-01-01 18:00:00', 0, '2022-01-02 08:10:54', '2022-01-02 08:10:54'),
(923, 114, NULL, 587, NULL, NULL, 2, NULL, '2022-01-01 18:00:00', 0, '2022-01-02 08:10:54', '2022-01-02 08:10:54'),
(924, 91, NULL, 591, NULL, NULL, 2, NULL, '2022-01-01 18:00:00', 0, '2022-01-02 08:51:40', '2022-01-02 08:51:40'),
(925, 91, 648, NULL, NULL, NULL, 1, NULL, '2022-01-01 18:00:00', 0, '2022-01-02 11:26:08', '2022-01-02 11:26:08'),
(926, 91, NULL, 593, NULL, NULL, 2, NULL, '2022-01-01 18:00:00', 0, '2022-01-02 11:26:08', '2022-01-02 11:26:08'),
(927, 91, NULL, 594, NULL, NULL, 2, NULL, '2022-01-01 18:00:00', 0, '2022-01-02 11:26:08', '2022-01-02 11:26:08'),
(928, 102, 655, NULL, NULL, NULL, 1, NULL, '2022-01-02 18:00:00', 0, '2022-01-03 07:28:22', '2022-01-03 07:28:22'),
(929, 102, NULL, 603, NULL, NULL, 2, NULL, '2022-01-02 18:00:00', 0, '2022-01-03 07:28:22', '2022-01-03 07:28:22'),
(932, 91, NULL, NULL, 62, NULL, 5, NULL, '2022-01-03 18:00:00', 0, '2022-01-04 12:08:31', '2022-01-04 12:08:31'),
(933, 114, 667, NULL, NULL, NULL, 1, NULL, '2022-01-05 18:00:00', 0, '2022-01-06 06:19:04', '2022-01-06 06:19:04'),
(934, 114, NULL, 618, NULL, NULL, 2, NULL, '2022-01-05 18:00:00', 0, '2022-01-06 06:19:04', '2022-01-06 06:19:04'),
(935, 114, NULL, 619, NULL, NULL, 2, NULL, '2022-01-05 18:00:00', 0, '2022-01-06 06:19:04', '2022-01-06 06:19:04');

-- --------------------------------------------------------

--
-- Table structure for table `customer_payments`
--

CREATE TABLE `customer_payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `voucher_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `customer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `paid_amount` decimal(22,2) NOT NULL DEFAULT 0.00,
  `report_date` timestamp NULL DEFAULT NULL,
  `type` tinyint(4) NOT NULL DEFAULT 1,
  `pay_mode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `time` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
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
  `note` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `admin_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customer_payments`
--

INSERT INTO `customer_payments` (`id`, `voucher_no`, `branch_id`, `customer_id`, `account_id`, `paid_amount`, `report_date`, `type`, `pay_mode`, `date`, `time`, `month`, `year`, `card_no`, `card_holder`, `card_type`, `card_transaction_no`, `card_month`, `card_year`, `card_secure_code`, `account_no`, `cheque_no`, `transaction_no`, `attachment`, `note`, `admin_id`, `created_at`, `updated_at`) VALUES
(58, 'CPV1', NULL, 91, NULL, '1000.00', NULL, 1, 'Cash', '26-10-2021', '11:29:02 am', 'October', '2021', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-10-26 05:29:02', '2021-10-26 05:29:02'),
(59, 'CPV112159', NULL, 103, 28, '1687500.00', '2021-11-19 18:00:00', 1, 'Cash', '20-11-2021', '11:34:47 am', 'November', '2021', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-11-20 05:34:47', '2021-11-20 05:34:47'),
(62, 'RPV012262', NULL, 91, 28, '1150.00', '2022-01-03 18:00:00', 2, 'Cash', '04-01-2022', '06:08:31 pm', 'January', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-01-04 12:08:31', '2022-01-04 12:08:31');

-- --------------------------------------------------------

--
-- Table structure for table `customer_payment_invoices`
--

CREATE TABLE `customer_payment_invoices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_payment_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sale_id` bigint(20) UNSIGNED DEFAULT NULL,
  `paid_amount` decimal(22,2) NOT NULL DEFAULT 0.00,
  `type` tinyint(4) DEFAULT NULL COMMENT '1=sale_payment;2=sale_return_payment',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customer_payment_invoices`
--

INSERT INTO `customer_payment_invoices` (`id`, `customer_payment_id`, `sale_id`, `paid_amount`, `type`, `created_at`, `updated_at`) VALUES
(53, 59, 625, '1687500.00', NULL, '2021-11-20 05:34:47', '2021-11-20 05:34:47'),
(55, 62, 641, '1150.00', 2, '2022-01-04 12:08:31', '2022-01-04 12:08:31');

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
(56, 'ER01221', NULL, NULL, NULL, '0.00', '0.00', '100.00', '100.00', '100.00', '0.00', '06-01-2022', 'January', '2022', NULL, '2022-01-05 18:00:00', '2022-01-06 05:31:09', '2022-01-06 05:31:09');

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
(27, 'Office Rent', '23', NULL, NULL),
(28, 'Electricity Bill', '28', NULL, NULL),
(29, 'Net Bill', '29', NULL, NULL);

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
(79, '01221', 56, 28, 'Cash', '100.00', NULL, '06-01-2022', 'January', '2022', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, '2022-01-05 18:00:00', '2022-01-06 05:31:09', '2022-01-06 05:31:09');

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
(73, 56, 29, '100.00', 0, '2022-01-06 05:31:09', '2022-01-06 05:31:09');

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
  `send_es_settings` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_setting` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sms_setting` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `modules` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reward_poing_settings` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mf_settings` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'manufacturing_settings',
  `multi_branches` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'is_activated',
  `hrm` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'is_activated',
  `services` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'is_activated',
  `manufacturing` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'is_activated',
  `projects` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'is_activated',
  `essentials` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'is_activated',
  `e_commerce` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'is_activated',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `general_settings`
--

INSERT INTO `general_settings` (`id`, `business`, `tax`, `product`, `sale`, `pos`, `purchase`, `dashboard`, `system`, `prefix`, `send_es_settings`, `email_setting`, `sms_setting`, `modules`, `reward_poing_settings`, `mf_settings`, `multi_branches`, `hrm`, `services`, `manufacturing`, `projects`, `essentials`, `e_commerce`, `created_at`, `updated_at`) VALUES
(1, '{\"shop_name\":\"Computer Market\",\"address\":\"Dhaka, Bangladesh\",\"phone\":\"+88017000000\",\"email\":\"example@gmail.com\",\"start_date\":\"07-04-2021\",\"default_profit\":0,\"currency\":\"TK.\",\"currency_placement\":null,\"date_format\":\"d-m-Y\",\"financial_year_start\":\"Januaray\",\"time_format\":\"12\",\"business_logo\":\"61c857f6a2fd6-.png\",\"timezone\":\"Asia\\/Dhaka\"}', '{\"tax_1_name\":null,\"tax_1_no\":null,\"tax_2_name\":null,\"tax_2_no\":null,\"is_tax_en_purchase_sale\":0}', '{\"product_code_prefix\":\"SCD\",\"default_unit_id\":\"3\",\"is_enable_brands\":1,\"is_enable_categories\":1,\"is_enable_sub_categories\":1,\"is_enable_price_tax\":1,\"is_enable_warranty\":1}', '{\"default_sale_discount\":\"0.00\",\"default_tax_id\":\"null\",\"sales_cmsn_agnt\":\"select_form_cmsn_list\",\"default_price_group_id\":\"10\"}', '{\"is_enabled_multiple_pay\":1,\"is_enabled_draft\":1,\"is_enabled_quotation\":1,\"is_enabled_suspend\":1,\"is_enabled_discount\":1,\"is_enabled_order_tax\":1,\"is_show_recent_transactions\":1,\"is_enabled_credit_full_sale\":1,\"is_enabled_hold_invoice\":1}', '{\"is_edit_pro_price\":1,\"is_enable_status\":1,\"is_enable_lot_no\":1}', '{\"view_stock_expiry_alert_for\":\"31\"}', '{\"theme_color\":\"dark-theme\",\"datatable_page_entry\":\"10\"}', '{\"purchase_invoice\":\"PI\",\"sale_invoice\":null,\"purchase_return\":null,\"stock_transfer\":null,\"stock_djustment\":\"SA\",\"sale_return\":\"SRI\",\"expenses\":\"ER\",\"supplier_id\":\"S\",\"customer_id\":null,\"purchase_payment\":\"PPV\",\"sale_payment\":\"SPV\",\"expanse_payment\":null}', '{\"send_inv_via_email\":0,\"send_notice_via_sms\":0,\"cmr_due_rmdr_via_email\":0,\"cmr_due_rmdr_via_sms\":0}', '[]', '[]', '{\"purchases\":1,\"add_sale\":1,\"pos\":1,\"transfer_stock\":1,\"stock_adjustment\":1,\"expenses\":1,\"accounting\":1,\"contacts\":1,\"hrms\":1,\"requisite\":1,\"manufacturing\":1,\"service\":1}', '{\"enable_cus_point\":0,\"point_display_name\":\"Reward Point\",\"amount_for_unit_rp\":\"10\",\"min_order_total_for_rp\":\"100\",\"max_rp_per_order\":\"\",\"redeem_amount_per_unit_rp\":\"0.10\",\"min_order_total_for_redeem\":\"\",\"min_redeem_point\":\"\",\"max_redeem_point\":\"\"}', '{\"production_ref_prefix\":\"MF\",\"enable_editing_ingredient_qty\":1,\"enable_updating_product_price\":1}', 0, 0, 0, 0, 0, 0, 0, NULL, '2022-01-06 11:37:25');

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
(4, 'Sales Department', 'SD1', NULL, NULL, NULL);

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
(4, 'Day Shift', '10:05', NULL, '22:00', NULL, NULL);

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
(1, 'Default layout', 2, 1, NULL, 1, 4, 1, 1, 0, 1, 1, NULL, NULL, NULL, 'Invoice/Bill', 'Quotation', 'Draft', 'Challan', 0, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 1, 1, 1, 0, 1, 1, 0, 0, 'If you need any support, Feel free to contact us. email: speeddigitinfo@gmail.com.', 0, 1, NULL, 'AL-ARAFA ISLAMI BANK Ltd.', 'Nawabpur', 'Speed Digit Pvt. Ltd', '0121020028467', 1, '2021-03-02 12:24:36', '2021-10-30 05:34:20'),
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
(12, 'TEST-4', '2', '12', NULL, 0, '2021/', '2021-08-16 11:08:29', '2021-08-16 11:08:29');

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
(6, 'default', '{\"uuid\":\"de347da1-c97c-484e-81c1-1a137af8938b\",\"displayName\":\"App\\\\Jobs\\\\SaleMailJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SaleMailJob\",\"command\":\"O:20:\\\"App\\\\Jobs\\\\SaleMailJob\\\":11:{s:2:\\\"to\\\";s:27:\\\"koalasoftsolution@gmail.com\\\";s:4:\\\"sale\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":4:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\Sale\\\";s:2:\\\"id\\\";i:405;s:9:\\\"relations\\\";a:7:{i:0;s:8:\\\"customer\\\";i:1;s:6:\\\"branch\\\";i:2;s:13:\\\"sale_products\\\";i:3;s:21:\\\"sale_products.product\\\";i:4;s:30:\\\"sale_products.product.warranty\\\";i:5;s:21:\\\"sale_products.variant\\\";i:6;s:5:\\\"admin\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";}s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}', 0, NULL, 1629956205, 1629956205),
(7, 'default', '{\"uuid\":\"ec27010d-80bd-4f3d-9f3c-de4f7802cec7\",\"displayName\":\"App\\\\Jobs\\\\SaleMailJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SaleMailJob\",\"command\":\"O:20:\\\"App\\\\Jobs\\\\SaleMailJob\\\":11:{s:2:\\\"to\\\";s:27:\\\"koalasoftsolution@gmail.com\\\";s:4:\\\"sale\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":4:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\Sale\\\";s:2:\\\"id\\\";i:408;s:9:\\\"relations\\\";a:7:{i:0;s:8:\\\"customer\\\";i:1;s:6:\\\"branch\\\";i:2;s:13:\\\"sale_products\\\";i:3;s:21:\\\"sale_products.product\\\";i:4;s:30:\\\"sale_products.product.warranty\\\";i:5;s:21:\\\"sale_products.variant\\\";i:6;s:5:\\\"admin\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";}s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";O:25:\\\"Illuminate\\\\Support\\\\Carbon\\\":3:{s:4:\\\"date\\\";s:26:\\\"2021-08-30 12:56:57.073984\\\";s:13:\\\"timezone_type\\\";i:3;s:8:\\\"timezone\\\";s:10:\\\"Asia\\/Dhaka\\\";}s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}', 0, NULL, 1630306617, 1630306612),
(8, 'default', '{\"uuid\":\"b7a5e735-01b9-462f-a661-72709e8e2218\",\"displayName\":\"App\\\\Jobs\\\\SaleMailJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SaleMailJob\",\"command\":\"O:20:\\\"App\\\\Jobs\\\\SaleMailJob\\\":11:{s:2:\\\"to\\\";s:27:\\\"koalasoftsolution@gmail.com\\\";s:4:\\\"sale\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":4:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\Sale\\\";s:2:\\\"id\\\";i:409;s:9:\\\"relations\\\";a:7:{i:0;s:8:\\\"customer\\\";i:1;s:6:\\\"branch\\\";i:2;s:13:\\\"sale_products\\\";i:3;s:21:\\\"sale_products.product\\\";i:4;s:30:\\\"sale_products.product.warranty\\\";i:5;s:21:\\\"sale_products.variant\\\";i:6;s:5:\\\"admin\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";}s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";O:25:\\\"Illuminate\\\\Support\\\\Carbon\\\":3:{s:4:\\\"date\\\";s:26:\\\"2021-08-31 13:43:54.503682\\\";s:13:\\\"timezone_type\\\";i:3;s:8:\\\"timezone\\\";s:10:\\\"Asia\\/Dhaka\\\";}s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}', 0, NULL, 1630395834, 1630395830),
(9, 'default', '{\"uuid\":\"499a5820-b2e7-426b-ac17-20c5883a9a4c\",\"displayName\":\"App\\\\Jobs\\\\SaleMailJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SaleMailJob\",\"command\":\"O:20:\\\"App\\\\Jobs\\\\SaleMailJob\\\":11:{s:2:\\\"to\\\";s:27:\\\"koalasoftsolution@gmail.com\\\";s:4:\\\"sale\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":4:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\Sale\\\";s:2:\\\"id\\\";i:410;s:9:\\\"relations\\\";a:7:{i:0;s:8:\\\"customer\\\";i:1;s:6:\\\"branch\\\";i:2;s:13:\\\"sale_products\\\";i:3;s:21:\\\"sale_products.product\\\";i:4;s:30:\\\"sale_products.product.warranty\\\";i:5;s:21:\\\"sale_products.variant\\\";i:6;s:5:\\\"admin\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";}s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";O:25:\\\"Illuminate\\\\Support\\\\Carbon\\\":3:{s:4:\\\"date\\\";s:26:\\\"2021-08-31 15:14:26.839294\\\";s:13:\\\"timezone_type\\\";i:3;s:8:\\\"timezone\\\";s:10:\\\"Asia\\/Dhaka\\\";}s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"}}', 0, NULL, 1630401266, 1630401261);

-- --------------------------------------------------------

--
-- Table structure for table `loans`
--

CREATE TABLE `loans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `expense_id` bigint(20) UNSIGNED DEFAULT NULL,
  `purchase_id` bigint(20) UNSIGNED DEFAULT NULL,
  `reference_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `loan_company_id` bigint(20) UNSIGNED DEFAULT NULL,
  `account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `type` tinyint(4) DEFAULT NULL,
  `loan_amount` decimal(22,2) NOT NULL DEFAULT 0.00,
  `due` decimal(22,2) NOT NULL DEFAULT 0.00,
  `total_paid` decimal(22,2) NOT NULL DEFAULT 0.00,
  `total_receive` decimal(22,2) NOT NULL DEFAULT 0.00,
  `report_date` timestamp NULL DEFAULT NULL,
  `loan_reason` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `loan_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `loan_companies`
--

CREATE TABLE `loan_companies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pay_loan_amount` decimal(22,2) NOT NULL DEFAULT 0.00,
  `pay_loan_due` decimal(22,2) NOT NULL DEFAULT 0.00,
  `get_loan_amount` decimal(22,2) NOT NULL DEFAULT 0.00,
  `get_loan_due` decimal(22,2) NOT NULL DEFAULT 0.00,
  `total_pay` decimal(22,2) NOT NULL DEFAULT 0.00,
  `total_receive` decimal(22,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `loan_payments`
--

CREATE TABLE `loan_payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `voucher_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_id` bigint(20) UNSIGNED DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `paid_amount` decimal(22,2) NOT NULL DEFAULT 0.00,
  `pay_mode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `report_date` timestamp NULL DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `payment_type` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1=pay_loan_payment;2=get_loan_payment',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `loan_payment_distributions`
--

CREATE TABLE `loan_payment_distributions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `loan_payment_id` bigint(20) UNSIGNED DEFAULT NULL,
  `loan_id` bigint(20) UNSIGNED DEFAULT NULL,
  `paid_amount` decimal(22,2) NOT NULL DEFAULT 0.00,
  `payment_type` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1=pay_loan_payment;2=get_loan_payment',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(194, '2021_08_22_181046_create_jobs_table', 101),
(197, '2021_08_28_163833_create_processes_table', 102),
(198, '2021_08_28_174915_create_process_ingredients_table', 102),
(202, '2021_08_31_181734_add_e_commerce_to_addons_table', 104),
(203, '2021_08_30_135333_create_productions_table', 105),
(204, '2021_09_02_103707_create_production_ingredients_table', 105),
(205, '2021_09_02_120021_update_general_setting_table_add_column_send_es_settings', 106),
(206, '2021_09_04_120655_create_loan_companies_table', 107),
(207, '2021_09_04_144845_create_loans_table', 108),
(208, '2021_09_04_160952_add_branch_id_to_loans_table', 109),
(209, '2021_09_05_102218_add_loan_id_to_cash_flows_table', 110),
(210, '2021_09_05_143236_add_columns_to_loans_table', 111),
(211, '2021_09_09_111055_create_supplier_payments_table', 112),
(212, '2021_09_09_111115_create_supplier_payment_invoices_table', 113),
(213, '2021_09_09_165137_add_columns_to_supplier_ledgers_table', 114),
(214, '2021_09_11_105818_create_customer_payments_table', 115),
(215, '2021_09_11_105829_create_customer_payment_invoices_table', 116),
(216, '2021_09_11_113228_add_columns_to_customer_ledgers_table', 116),
(217, '2021_09_11_113553_add_columns_to_sales_table', 117),
(218, '2021_09_11_150200_add_columns_to_cash_flows_table', 117),
(219, '2021_09_11_160156_add_columns_to_purchase_payments_table', 118),
(220, '2021_09_11_160529_add_columns_to_sale_payments_table', 119),
(221, '2021_09_18_120519_add_column_to_suppliers', 120),
(222, '2021_09_19_102537_add_column_to_sale_return_products', 121),
(223, '2021_09_19_171336_add_column_to_supplier_payment_invoices', 122),
(224, '2021_09_20_131524_change_column_purchase_id_made_nullable', 123),
(225, '2021_09_20_172436_add_column_to_purchase_payments', 124),
(227, '2021_09_25_132922_add_column_to_customers', 125),
(229, '2021_09_22_125158_add_column_to_cash_flows', 126),
(230, '2021_10_06_140926_add_column_to_customer_payment_invoices_table', 127),
(231, '2021_10_07_132114_add_column_to_loans_table', 128),
(234, '2021_10_13_173736_add_column_to_cash_flows_table', 130),
(237, '2021_10_14_135021_add_columns_loan_companies_table', 132),
(238, '2021_10_14_141415_add_new_columns_loan_companies_table', 133),
(247, '2021_10_13_154417_create_loan_payments_table', 134),
(248, '2021_10_13_155648_create_loan_payment_distributions_table', 134),
(249, '2021_10_17_130418_add_new_columns_product_branch_variants_table', 134),
(250, '2021_10_17_130830_add_new_columns_products_table', 134),
(251, '2021_10_17_130922_add_new_columns_product_variants_table', 134),
(256, '2021_10_18_120146_add_or_edit_columns_product_branch_variants_table', 135),
(257, '2021_10_18_171227_add_columns_to_purchases_table', 136),
(258, '2021_10_18_181422_add_new_columns_to_purchases_table', 137),
(259, '2021_10_18_181742_create_purchase_order_products_table', 137),
(260, '2021_10_19_102549_add_new_columns_purchase_payments_table', 137),
(261, '2021_10_19_161118_add_new_columns_purchase_order_products_table', 138),
(262, '2021_10_19_174424_add_and_edit_columns_purchases_table', 139),
(263, '2021_10_19_174919_edit_columns_purchase_order_products_table', 140),
(264, '2021_10_20_105414_edit_columns_purchase_products_table', 141),
(265, '2021_10_20_134119_edit_columns_purchases_table', 142),
(266, '2021_10_21_134631_edit_columns_products_table', 143),
(267, '2021_10_21_134714_edit_columns_product_variants_table', 143),
(268, '2021_10_25_123637_add_columns_purchases_table', 144),
(269, '2021_10_27_105018_add_columns_products_table', 145),
(270, '2021_11_09_110242_add_new_cols_product_branches_table', 146),
(271, '2021_11_09_110304_add_new_cols_product_branch_variants_table', 146),
(272, '2021_11_10_113023_remove_columns_customers_table', 147),
(273, '2021_11_10_113047_remove_columns_suppliers_table', 147),
(274, '2021_11_10_114610_add_column_customers_table', 147),
(275, '2021_11_10_121208_remove_column_form_general_settings_table', 148),
(276, '2021_11_11_115245_remove_columns_form_products_table', 149),
(277, '2021_11_11_115302_remove_columns_form_product_variants_table', 149),
(278, '2021_11_11_182630_add_columns_form_product_warehouses_table', 150),
(279, '2021_11_11_182652_add_columns_form_product_warehouse_variants_table', 150),
(280, '2021_11_11_182928_add_columns_form_product_branches_table', 150),
(281, '2021_11_11_183022_add_columns_form_product_branch_variants_table', 150),
(282, '2021_11_14_110149_add_columns_to_product_branches_table', 151),
(283, '2021_11_14_110207_add_columns_to_product_branch_variants_table', 151),
(284, '2021_11_14_110231_add_columns_to_product_warehouses_table', 152),
(285, '2021_11_14_110252_add_columns_to_product_warehouse_variants_table', 152),
(286, '2021_11_14_133556_add_columns_to_productions_table', 153),
(287, '2021_11_14_135251_add_columns_to_production_ingredients_table', 154),
(288, '2021_11_14_135516_add_column_to_productions_table', 154),
(289, '2021_11_14_143142_add_new_column_to_productions_table', 155),
(290, '2021_11_15_112700_add_a_new_column_to_productions_table', 156),
(291, '2021_11_15_123637_edit_column_to_production_ingredients_table', 156),
(292, '2021_11_15_132518_edit_production_id_forign_key_from_production_ingredients_table', 157),
(293, '2021_11_15_133354_add_a_new_column_called_time_to_productions_table', 158),
(294, '2021_11_15_164602_add_2_columns_to_productions_table', 159),
(295, '2021_11_20_111115_edit_column_called_alert_quantity_form_products_table', 160),
(296, '2021_12_26_161252_add_new_column_to_purchase_products_table', 161),
(297, '2021_12_26_162232_add_new_column_to_purchase_order_products_table', 162),
(298, '2022_01_03_134629_add_column_to_role_permissions_table', 163),
(299, '2022_01_03_185118_drop_some_columns_to_role_permissions_table', 164),
(300, '2022_01_04_142457_drop_a_column_to_role_permissions_table', 165),
(301, '2022_01_04_172855_add_new_column_to_role_permissions_table', 166),
(302, '2022_01_04_182835_add_a_new_column_to_role_permissions_table', 167),
(303, '2022_01_04_183526_remove_2_columns_to_role_permissions_table', 168),
(305, '2022_01_05_131102_drop_column_from_sale_payments_table', 169),
(307, '2022_01_05_132959_drop_card_types_table', 170),
(308, '2022_01_05_140526_create_payment_methods_table', 171),
(311, '2022_01_06_171103_drop_column_from_accounts_table', 172),
(312, '2022_01_06_172058_add_more_one_column_from_sale_payments_table', 172),
(313, '2022_01_06_172439_drop_account_types_table', 173),
(314, '2022_01_08_114250_add_more_one_column_to_accounts_table', 174);

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
(69, '92148', '9900.00', '0.00', 91, NULL, NULL, NULL, 'Pending', 1, 1, 0, 1, 1, 1, '05-10-2021', 'October', '2021', '2021-10-04 18:00:00', '2021-10-05 06:11:18', '2021-10-05 06:11:18');

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
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payment_methods`
--

INSERT INTO `payment_methods` (`id`, `name`, `account_id`, `created_at`, `updated_at`) VALUES
(3, 'Cash', NULL, NULL, '2022-01-06 08:11:04'),
(4, 'Debit-Card', 28, NULL, NULL),
(5, 'Credit-Card', 28, NULL, NULL),
(6, 'American Express Card', 28, NULL, NULL);

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
(18, 'sales.create', 'Add Sale', 'fas fa-cart-plus', '2021-08-21 09:41:00', '2021-08-21 09:41:00'),
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

--
-- Dumping data for table `pos_short_menu_users`
--

INSERT INTO `pos_short_menu_users` (`id`, `short_menu_id`, `user_id`, `is_delete_in_update`, `created_at`, `updated_at`) VALUES
(107, 8, 2, 0, '2021-09-04 08:47:02', '2021-09-04 08:47:13'),
(108, 7, 2, 0, '2021-09-04 08:47:03', '2021-09-04 08:47:13'),
(109, 18, 2, 0, '2021-09-04 08:47:11', '2021-09-04 08:47:13'),
(110, 5, 2, 0, '2021-09-04 08:47:13', '2021-09-04 08:47:13');

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
(10, 'whole sale', NULL, 'Active', NULL, NULL);

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

--
-- Dumping data for table `price_group_products`
--

INSERT INTO `price_group_products` (`id`, `price_group_id`, `product_id`, `variant_id`, `price`, `created_at`, `updated_at`) VALUES
(52, 10, 288, NULL, '80.00', '2021-10-27 12:32:23', '2021-10-27 12:32:23');

-- --------------------------------------------------------

--
-- Table structure for table `processes`
--

CREATE TABLE `processes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `variant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `total_ingredient_cost` decimal(22,2) NOT NULL DEFAULT 0.00,
  `wastage_percent` decimal(8,2) NOT NULL DEFAULT 0.00,
  `wastage_amount` decimal(8,2) NOT NULL DEFAULT 0.00,
  `total_output_qty` decimal(22,2) NOT NULL DEFAULT 0.00,
  `unit_id` bigint(20) UNSIGNED DEFAULT NULL,
  `production_cost` decimal(22,2) NOT NULL DEFAULT 0.00,
  `total_cost` decimal(22,2) NOT NULL DEFAULT 0.00,
  `process_instruction` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `processes`
--

INSERT INTO `processes` (`id`, `product_id`, `variant_id`, `total_ingredient_cost`, `wastage_percent`, `wastage_amount`, `total_output_qty`, `unit_id`, `production_cost`, `total_cost`, `process_instruction`, `created_at`, `updated_at`) VALUES
(11, 308, NULL, '54540.00', '0.00', '0.00', '1000.00', 4, '100.00', '54640.00', NULL, '2021-11-15 05:07:28', '2021-11-15 13:24:34'),
(12, 275, NULL, '17500.00', '0.00', '0.00', '1.00', 3, '0.00', '17500.00', NULL, '2021-11-17 06:04:48', '2021-11-17 06:04:48'),
(13, 312, NULL, '87775.00', '0.00', '0.00', '1000.00', 3, '0.00', '87775.00', NULL, '2021-11-18 04:38:49', '2021-11-18 04:46:29');

-- --------------------------------------------------------

--
-- Table structure for table `process_ingredients`
--

CREATE TABLE `process_ingredients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `process_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `variant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `wastage_percent` decimal(22,2) NOT NULL DEFAULT 0.00,
  `wastage_amount` decimal(22,2) NOT NULL DEFAULT 0.00,
  `final_qty` decimal(22,2) NOT NULL DEFAULT 0.00,
  `unit_id` bigint(20) UNSIGNED DEFAULT NULL,
  `unit_cost_inc_tax` decimal(8,2) NOT NULL DEFAULT 0.00,
  `subtotal` decimal(22,2) NOT NULL DEFAULT 0.00,
  `is_delete_in_update` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `process_ingredients`
--

INSERT INTO `process_ingredients` (`id`, `process_id`, `product_id`, `variant_id`, `wastage_percent`, `wastage_amount`, `final_qty`, `unit_id`, `unit_cost_inc_tax`, `subtotal`, `is_delete_in_update`, `created_at`, `updated_at`) VALUES
(11, 11, 309, NULL, '0.00', '0.00', '1000.00', 4, '3.50', '3500.00', 0, '2021-11-15 05:07:28', '2021-11-15 13:24:34'),
(12, 11, 311, NULL, '0.00', '0.00', '1000.00', 4, '50.00', '50000.00', 0, '2021-11-15 05:19:53', '2021-11-15 13:24:34'),
(13, 11, 310, NULL, '0.00', '0.00', '200.00', 4, '5.20', '1040.00', 0, '2021-11-15 05:19:53', '2021-11-15 13:24:34'),
(14, 12, 278, NULL, '0.00', '0.00', '1.00', 3, '17500.00', '17500.00', 0, '2021-11-17 06:04:48', '2021-11-17 06:04:48'),
(15, 12, 298, NULL, '0.00', '0.00', '1.00', 3, '0.00', '0.00', 0, '2021-11-17 06:04:48', '2021-11-17 06:04:48'),
(16, 13, 311, NULL, '0.00', '0.00', '500.00', 4, '50.00', '25000.00', 0, '2021-11-18 04:38:49', '2021-11-18 04:46:29'),
(17, 13, 310, NULL, '0.00', '0.00', '500.00', 4, '5.20', '2600.00', 0, '2021-11-18 04:38:49', '2021-11-18 04:46:30'),
(18, 13, 309, NULL, '0.00', '0.00', '50.00', 4, '3.50', '175.00', 0, '2021-11-18 04:38:49', '2021-11-18 04:46:30'),
(19, 13, 314, NULL, '0.00', '0.00', '30.00', 3, '2000.00', '60000.00', 0, '2021-11-18 04:38:49', '2021-11-18 04:46:30');

-- --------------------------------------------------------

--
-- Table structure for table `productions`
--

CREATE TABLE `productions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `unit_id` bigint(20) UNSIGNED DEFAULT NULL,
  `tax_id` bigint(20) UNSIGNED DEFAULT NULL,
  `tax_type` tinyint(4) DEFAULT NULL,
  `reference_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `time` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `report_date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `warehouse_id` bigint(20) UNSIGNED DEFAULT NULL,
  `stock_warehouse_id` bigint(20) UNSIGNED DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `stock_branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `variant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `total_ingredient_cost` decimal(22,2) DEFAULT NULL,
  `quantity` decimal(22,2) DEFAULT NULL,
  `parameter_quantity` decimal(22,2) NOT NULL DEFAULT 0.00,
  `wasted_quantity` decimal(22,2) DEFAULT NULL,
  `total_final_quantity` decimal(22,2) NOT NULL DEFAULT 0.00,
  `unit_cost_exc_tax` decimal(22,2) NOT NULL DEFAULT 0.00,
  `unit_cost_inc_tax` decimal(22,2) NOT NULL DEFAULT 0.00,
  `x_margin` decimal(22,2) NOT NULL DEFAULT 0.00,
  `price_exc_tax` decimal(22,2) NOT NULL DEFAULT 0.00,
  `production_cost` decimal(22,2) DEFAULT NULL,
  `total_cost` decimal(22,2) DEFAULT NULL,
  `is_final` tinyint(1) NOT NULL DEFAULT 0,
  `is_last_entry` tinyint(1) NOT NULL DEFAULT 0,
  `is_default_price` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `productions`
--

INSERT INTO `productions` (`id`, `unit_id`, `tax_id`, `tax_type`, `reference_no`, `date`, `time`, `report_date`, `warehouse_id`, `stock_warehouse_id`, `branch_id`, `stock_branch_id`, `product_id`, `variant_id`, `total_ingredient_cost`, `quantity`, `parameter_quantity`, `wasted_quantity`, `total_final_quantity`, `unit_cost_exc_tax`, `unit_cost_inc_tax`, `x_margin`, `price_exc_tax`, `production_cost`, `total_cost`, `is_final`, `is_last_entry`, `is_default_price`, `created_at`, `updated_at`) VALUES
(80, 4, 1, 1, 'MF00001', '20-11-2021', '04:21:03 pm', '2021-11-20', NULL, NULL, NULL, NULL, 308, NULL, '54540.00', '1000.00', '1000.00', '0.00', '1000.00', '54.64', '57.37', '0.00', '0.00', '100.00', '54640.00', 1, 1, 0, '2021-11-20 10:21:03', '2021-11-20 10:21:03');

-- --------------------------------------------------------

--
-- Table structure for table `production_ingredients`
--

CREATE TABLE `production_ingredients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `production_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `variant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `parameter_quantity` decimal(22,2) NOT NULL DEFAULT 0.00,
  `input_qty` decimal(22,2) NOT NULL DEFAULT 0.00,
  `wastage_percent` decimal(22,2) NOT NULL DEFAULT 0.00,
  `final_qty` decimal(22,2) NOT NULL DEFAULT 0.00,
  `unit_id` bigint(20) UNSIGNED DEFAULT NULL,
  `unit_cost_inc_tax` decimal(22,2) NOT NULL DEFAULT 0.00,
  `subtotal` decimal(22,2) NOT NULL DEFAULT 0.00,
  `is_delete_in_update` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `production_ingredients`
--

INSERT INTO `production_ingredients` (`id`, `production_id`, `product_id`, `variant_id`, `parameter_quantity`, `input_qty`, `wastage_percent`, `final_qty`, `unit_id`, `unit_cost_inc_tax`, `subtotal`, `is_delete_in_update`, `created_at`, `updated_at`) VALUES
(237, 80, 309, NULL, '1000.00', '1000.00', '0.00', '0.00', 4, '3.50', '3500.00', 0, '2021-11-20 10:21:03', '2021-11-20 10:21:03'),
(238, 80, 311, NULL, '1000.00', '1000.00', '0.00', '0.00', 4, '50.00', '50000.00', 0, '2021-11-20 10:21:03', '2021-11-20 10:21:03'),
(239, 80, 310, NULL, '200.00', '200.00', '0.00', '0.00', 4, '5.20', '1040.00', 0, '2021-11-20 10:21:03', '2021-11-20 10:21:03');

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
  `is_manage_stock` tinyint(1) NOT NULL DEFAULT 1,
  `quantity` decimal(22,2) NOT NULL DEFAULT 0.00,
  `combo_price` decimal(22,2) NOT NULL DEFAULT 0.00,
  `alert_quantity` bigint(20) NOT NULL DEFAULT 0,
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

INSERT INTO `products` (`id`, `type`, `name`, `product_code`, `category_id`, `parent_category_id`, `brand_id`, `unit_id`, `tax_id`, `tax_type`, `warranty_id`, `product_cost`, `product_cost_with_tax`, `profit`, `product_price`, `offer_price`, `is_manage_stock`, `quantity`, `combo_price`, `alert_quantity`, `is_featured`, `is_combo`, `is_variant`, `is_show_in_ecom`, `is_show_emi_on_pos`, `is_for_sale`, `attachment`, `thumbnail_photo`, `expire_date`, `product_details`, `is_purchased`, `barcode_type`, `weight`, `product_condition`, `status`, `number_of_sale`, `total_transfered`, `total_adjusted`, `custom_field_1`, `custom_field_2`, `custom_field_3`, `created_at`, `updated_at`) VALUES
(273, 1, 'Variant', 'BS6595381', NULL, NULL, NULL, 3, NULL, 1, NULL, '0.00', '0.00', '100.00', '100.00', '0.00', 1, '-10.00', '0.00', 0, 0, 0, 1, 0, 0, 1, NULL, 'default.png', NULL, NULL, '1', 'CODE128', NULL, 'New', 1, '13.00', '0.00', '0.00', NULL, NULL, NULL, '2021-10-27 04:26:28', '2021-11-14 10:36:01'),
(274, 1, 'Service-1', 'BS7668317', NULL, NULL, NULL, 3, NULL, 1, NULL, '150.00', '150.00', '100.00', '300.00', '0.00', 0, '0.00', '0.00', 0, 0, 0, 0, 0, 1, 1, NULL, 'default.png', NULL, NULL, '1', 'CODE128', NULL, 'New', 1, '0.00', '0.00', '0.00', NULL, NULL, NULL, '2021-10-27 05:54:23', '2021-11-08 09:07:40'),
(275, 1, 'AC Repairing', 'BS3954374', NULL, NULL, NULL, 3, NULL, 1, NULL, '0.00', '0.00', '100.00', '500.00', '0.00', 0, '0.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'default.png', NULL, NULL, '1', 'CODE128', NULL, 'New', 1, '0.00', '0.00', '0.00', NULL, NULL, NULL, '2021-10-27 09:49:22', '2021-11-14 10:36:01'),
(276, 1, 'Mobile Reparing', 'BS4296397', NULL, NULL, NULL, 3, NULL, 1, NULL, '0.00', '0.00', '100.00', '500.00', '0.00', 0, '0.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'default.png', NULL, NULL, '1', 'CODE128', NULL, 'New', 1, '0.00', '0.00', '0.00', NULL, NULL, NULL, '2021-10-27 09:54:28', '2021-11-14 10:36:00'),
(277, 1, 'Samsung A22', 'BS3923779', NULL, NULL, NULL, 3, 5, 1, NULL, '18500.00', '18500.00', '11.00', '20535.00', '0.00', 1, '50.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'default.png', NULL, NULL, '1', 'CODE128', NULL, 'New', 1, '79.00', '0.00', '0.00', NULL, NULL, NULL, '2021-10-27 09:55:05', '2022-01-06 06:19:04'),
(278, 1, 'Real Phone', 'BS3934871', NULL, NULL, NULL, 3, NULL, 1, NULL, '17500.00', '17500.00', '11.43', '19500.00', '0.00', 1, '102.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'default.png', NULL, NULL, '1', 'CODE128', NULL, 'New', 1, '0.00', '0.00', '0.00', NULL, NULL, NULL, '2021-10-27 09:56:00', '2021-11-14 10:36:01'),
(279, 1, 'Comic PDF', 'BS4336581', NULL, NULL, NULL, 3, NULL, 1, NULL, '0.00', '0.00', '100.00', '150.00', '0.00', 0, '0.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'default.png', NULL, NULL, '1', 'CODE128', NULL, 'New', 1, '0.00', '0.00', '0.00', NULL, NULL, NULL, '2021-10-27 09:56:43', '2021-11-14 10:36:00'),
(280, 1, 'Dell Brand Pc', 'BS2643262', NULL, NULL, NULL, 3, NULL, 1, NULL, '80000.00', '80000.00', '2.50', '82000.00', '0.00', 1, '94.00', '0.00', 0, 0, 0, 0, 0, 1, 1, NULL, 'default.png', NULL, NULL, '0', 'CODE128', NULL, 'New', 1, '6.00', '0.00', '0.00', NULL, NULL, NULL, '2021-10-27 09:57:27', '2021-11-11 10:28:42'),
(281, 1, 'Lenovo Brand PC', 'BS3955939', NULL, NULL, NULL, 3, NULL, 1, NULL, '18500.00', '18500.00', '8.11', '20000.00', '0.00', 1, '104.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'default.png', NULL, NULL, '1', 'CODE128', NULL, 'Used', 1, '3.00', '0.00', '0.00', NULL, NULL, NULL, '2021-10-27 09:59:17', '2021-11-13 04:36:15'),
(282, 1, 'Mouse', 'BS5486837', NULL, NULL, NULL, 3, NULL, 1, NULL, '250.00', '250.00', '40.00', '350.00', '0.00', 1, '95.00', '0.00', 0, 0, 0, 0, 0, 1, 1, NULL, 'default.png', NULL, NULL, '1', 'CODE128', NULL, 'New', 1, '6.00', '0.00', '0.00', NULL, NULL, NULL, '2021-10-27 09:59:50', '2021-11-14 10:36:01'),
(283, 1, 'Acer Laptop', 'BS1516259', NULL, NULL, NULL, 3, NULL, 1, NULL, '3500.00', '3500.00', '42.86', '5000.00', '0.00', 1, '99.00', '0.00', 0, 0, 0, 0, 0, 1, 1, NULL, 'default.png', NULL, NULL, '1', 'CODE128', NULL, 'New', 1, '6.00', '0.00', '0.00', NULL, NULL, NULL, '2021-10-27 10:00:33', '2021-11-14 10:36:01'),
(284, 1, 'Computer Servicing', 'BS8363181', NULL, NULL, NULL, 3, NULL, 1, NULL, '0.00', '0.00', '0.00', '250.00', '0.00', 1, '5.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'default.png', NULL, NULL, '1', 'CODE128', NULL, NULL, 1, '5.00', '0.00', '0.00', NULL, NULL, NULL, '2021-10-27 10:06:44', '2021-11-11 10:28:42'),
(285, 1, 'Service-2', 'BS1958433', NULL, NULL, NULL, 3, NULL, 1, NULL, '0.00', '0.00', '0.00', '100.00', '0.00', 1, '6.00', '0.00', 0, 0, 0, 0, 0, 1, 1, NULL, 'default.png', NULL, NULL, '1', 'CODE128', NULL, 'New', 1, '7.00', '0.00', '0.00', NULL, NULL, NULL, '2021-10-27 10:30:00', '2021-11-14 10:36:01'),
(286, 1, 'Test1', 'BS7439682', NULL, NULL, NULL, 3, NULL, 1, NULL, '0.00', '0.00', '100.00', '100.00', '0.00', 1, '10.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'default.png', NULL, NULL, '1', 'CODE128', NULL, NULL, 1, '1.00', '0.00', '0.00', NULL, NULL, NULL, '2021-10-27 11:59:38', '2021-11-14 10:36:01'),
(287, 1, 'Test2', 'BS1246853', NULL, NULL, NULL, 3, NULL, 1, NULL, '0.00', '0.00', '0.00', '100.00', '0.00', 1, '3.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'default.png', NULL, NULL, '1', 'CODE128', NULL, NULL, 1, '8.00', '0.00', '0.00', NULL, NULL, NULL, '2021-10-27 12:00:20', '2021-11-14 10:36:01'),
(288, 1, 'Testte', 'BS1792717', NULL, NULL, NULL, 3, NULL, 1, NULL, '0.00', '0.00', '0.00', '100.00', '0.00', 1, '1.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'default.png', NULL, NULL, '1', 'CODE128', NULL, NULL, 1, '10.00', '0.00', '0.00', NULL, NULL, NULL, '2021-10-27 12:00:39', '2021-11-14 10:36:01'),
(289, 1, 'Service-2', 'BS9355544', NULL, NULL, NULL, 3, NULL, 1, NULL, '0.00', '0.00', '100.00', '100.00', '0.00', 1, '0.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'default.png', NULL, NULL, '0', 'CODE128', NULL, 'New', 1, '0.00', '0.00', '0.00', NULL, NULL, NULL, '2021-10-30 08:00:23', '2021-11-08 09:07:40'),
(290, 1, 'Service-2', 'BS8363435', NULL, NULL, NULL, 3, NULL, 1, NULL, '0.00', '0.00', '100.00', '100.00', '0.00', 1, '1.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'default.png', NULL, NULL, '1', 'CODE128', NULL, 'New', 1, '0.00', '0.00', '0.00', NULL, NULL, NULL, '2021-10-30 08:00:32', '2021-11-14 10:36:01'),
(291, 1, 'Service-3', 'BS4483593', NULL, NULL, NULL, 3, NULL, 1, NULL, '0.00', '0.00', '100.00', '100.00', '0.00', 1, '4.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'default.png', NULL, NULL, '1', 'CODE128', NULL, 'New', 1, '0.00', '0.00', '0.00', NULL, NULL, NULL, '2021-10-30 08:00:48', '2021-12-27 05:33:42'),
(292, 1, 'sa', 'BS7676397', NULL, NULL, NULL, 3, NULL, 1, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 1, '1.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'default.png', NULL, NULL, '1', 'CODE128', NULL, 'New', 1, '0.00', '0.00', '0.00', NULL, NULL, NULL, '2021-10-30 08:01:01', '2021-11-14 10:36:01'),
(293, 1, 'Service-1', 'BS3467581', NULL, NULL, NULL, 3, NULL, 1, NULL, '0.00', '0.00', '100.00', '100.00', '0.00', 0, '0.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'default.png', NULL, NULL, '1', 'CODE128', NULL, 'New', 1, '0.00', '0.00', '0.00', NULL, NULL, NULL, '2021-10-30 08:02:22', '2021-12-26 10:40:42'),
(294, 1, 'sa', 'BS8475113', NULL, NULL, NULL, 3, NULL, 1, NULL, '50.00', '50.00', '100.00', '100.00', '0.00', 1, '13.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'default.png', NULL, NULL, '1', 'CODE128', NULL, 'New', 1, '1.00', '0.00', '0.00', NULL, NULL, NULL, '2021-10-30 08:02:35', '2021-11-22 08:13:12'),
(295, 1, 'Test', 'BS9787524', NULL, NULL, NULL, 3, NULL, 1, NULL, '0.00', '0.00', '100.00', '100.00', '0.00', 1, '1.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'default.png', NULL, NULL, '1', 'CODE128', NULL, 'New', 1, '0.00', '0.00', '0.00', NULL, NULL, NULL, '2021-10-30 08:02:47', '2021-11-14 10:36:01'),
(296, 1, 'ojhhhjjhj', 'BS2713653', NULL, NULL, NULL, 3, NULL, 1, NULL, '0.00', '0.00', '100.00', '100.00', '0.00', 1, '1.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'default.png', NULL, NULL, '1', 'CODE128', NULL, 'New', 1, '0.00', '0.00', '0.00', NULL, NULL, NULL, '2021-10-30 08:02:59', '2021-11-14 10:36:01'),
(297, 1, 'asdfasdf', 'BS9416724', NULL, NULL, NULL, 3, NULL, 1, NULL, '0.00', '0.00', '100.00', '1000.00', '0.00', 1, '2.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'default.png', NULL, NULL, '1', 'CODE128', NULL, 'New', 1, '0.00', '0.00', '1.00', NULL, NULL, NULL, '2021-10-30 08:09:03', '2022-01-02 12:51:14'),
(298, 1, 'sadf', 'BS3127596', NULL, NULL, NULL, 3, NULL, 1, NULL, '0.00', '0.00', '100.00', '10100.00', '0.00', 1, '0.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'default.png', NULL, NULL, '1', 'CODE128', NULL, 'New', 1, '2.00', '0.00', '0.00', NULL, NULL, NULL, '2021-10-30 08:10:08', '2021-12-14 04:40:07'),
(299, 1, 'asdfa', 'BS8113933', NULL, NULL, NULL, 3, NULL, 1, NULL, '0.00', '0.00', '100.00', '100.00', '0.00', 1, '1.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'default.png', NULL, NULL, '1', 'CODE128', NULL, 'New', 1, '1.00', '0.00', '0.00', NULL, NULL, NULL, '2021-10-30 08:54:52', '2021-12-19 08:08:44'),
(300, 1, 'Test_products', 'BS1684727', NULL, NULL, NULL, 3, NULL, 1, NULL, '0.00', '0.00', '100.00', '100.00', '0.00', 1, '2.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'default.png', NULL, NULL, '1', 'CODE128', NULL, 'New', 1, '0.00', '0.00', '0.00', NULL, NULL, NULL, '2021-10-30 09:01:43', '2021-11-14 10:36:01'),
(301, 1, 'code1', 'BS1666321', NULL, NULL, NULL, 3, NULL, 1, NULL, '0.00', '0.00', '100.00', '100.00', '0.00', 1, '3.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'default.png', NULL, NULL, '1', 'CODE128', NULL, 'New', 1, '0.00', '0.00', '0.00', NULL, NULL, NULL, '2021-11-08 08:58:10', '2021-11-14 10:36:01'),
(302, 1, 'code2', 'BS2288198', NULL, NULL, NULL, 3, NULL, 1, NULL, '0.00', '0.00', '100.00', '100.00', '0.00', 1, '1.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'default.png', NULL, NULL, '1', 'CODE128', NULL, 'New', 1, '0.00', '0.00', '0.00', NULL, NULL, NULL, '2021-11-08 08:58:25', '2021-11-14 10:36:01'),
(303, 1, 'code3', 'BS7561564', NULL, NULL, NULL, 3, NULL, 1, NULL, '0.00', '0.00', '100.00', '100.00', '0.00', 1, '2.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'default.png', NULL, NULL, '1', 'CODE128', NULL, 'New', 1, '0.00', '0.00', '0.00', NULL, NULL, NULL, '2021-11-08 09:00:53', '2021-11-14 10:36:01'),
(304, 1, 'code4', 'BS7396821', NULL, NULL, NULL, 3, NULL, 1, NULL, '0.00', '0.00', '100.00', '100.00', '0.00', 1, '2.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'default.png', NULL, NULL, '1', 'CODE128', NULL, 'New', 1, '0.00', '0.00', '0.00', NULL, NULL, NULL, '2021-11-08 09:01:08', '2021-11-14 10:36:01'),
(305, 1, 'Qty TEST', 'SCD9448436', NULL, NULL, NULL, 3, NULL, 1, NULL, '0.00', '0.00', '100.00', '95.00', '0.00', 1, '1.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'default.png', NULL, NULL, '1', 'CODE128', NULL, 'New', 1, '0.00', '0.00', '0.00', NULL, NULL, NULL, '2021-11-08 12:16:24', '2021-11-14 10:36:01'),
(306, 1, 'Corsair PC RAM kit Vengeance ® RGB PRO DDR4 RAM 3200 MHz CL16', 'SCD1366965', NULL, NULL, NULL, 3, NULL, 1, NULL, '0.00', '0.00', '100.00', '200.00', '0.00', 1, '8.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'default.png', NULL, NULL, '1', 'CODE128', NULL, 'New', 1, '3.00', '0.00', '0.00', NULL, NULL, NULL, '2021-11-10 12:26:48', '2022-01-02 08:51:40'),
(307, 1, 'ggg', 'SCD1125162', NULL, NULL, NULL, 3, NULL, 1, NULL, '500.00', '500.00', '20.00', '600.00', '0.00', 1, '1.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'default.png', NULL, NULL, '1', 'CODE128', NULL, 'New', 1, '5.00', '0.00', '0.00', NULL, NULL, NULL, '2021-11-10 13:45:43', '2022-01-02 08:01:39'),
(308, 1, '3 MM Rood', 'SCD5227352', 119, NULL, NULL, 4, 1, 1, NULL, '54.64', '57.37', '0.00', '0.00', '0.00', 1, '995.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'default.png', NULL, NULL, '1', 'CODE128', NULL, 'New', 1, '5.00', '0.00', '0.00', NULL, NULL, NULL, '2021-11-13 07:35:17', '2022-01-05 05:30:18'),
(309, 1, '60/80 Mash Power', 'SCD3824914', NULL, NULL, NULL, 4, NULL, 1, NULL, '3.50', '3.50', '-100.00', '0.00', '0.00', 1, '498998.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'default.png', NULL, NULL, '1', 'CODE128', NULL, 'New', 1, '3.00', '0.00', '0.00', NULL, NULL, NULL, '2021-11-13 07:36:35', '2022-01-05 05:31:30'),
(310, 1, 'Silica Powder', 'SCD2835318', NULL, NULL, NULL, 4, NULL, 1, NULL, '5.20', '5.20', '-100.00', '0.00', '0.00', 1, '49794.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'default.png', NULL, NULL, '1', 'CODE128', NULL, 'New', 1, '7.00', '0.00', '0.00', NULL, NULL, NULL, '2021-11-13 07:39:10', '2022-01-05 05:30:18'),
(311, 1, 'Scrap', 'SCD4147173', NULL, NULL, NULL, 4, NULL, 1, NULL, '50.00', '50.00', '-100.00', '0.00', '0.00', 1, '48991.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'default.png', NULL, NULL, '1', 'CODE128', NULL, 'New', 1, '10.00', '0.00', '0.00', NULL, NULL, NULL, '2021-11-13 07:40:25', '2022-01-05 05:30:18'),
(312, 1, '5 MM Rood', 'SCD7238438', 119, NULL, NULL, 3, NULL, 1, NULL, '250.00', '250.00', '0.00', '0.00', '0.00', 1, '0.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'default.png', NULL, NULL, '0', 'CODE128', NULL, 'New', 1, '0.00', '0.00', '0.00', NULL, NULL, NULL, '2021-11-13 07:44:22', '2021-11-13 07:44:22'),
(313, 1, '10 MM Rood', 'SCD6388487', NULL, NULL, NULL, 4, NULL, 1, NULL, '500.00', '500.00', '-100.00', '0.00', '0.00', 1, '0.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'default.png', NULL, NULL, '0', 'CODE128', NULL, 'New', 1, '0.00', '0.00', '0.00', NULL, NULL, NULL, '2021-11-13 07:50:16', '2021-11-13 07:50:16'),
(314, 1, 'Panga', 'SCD9923227', 119, NULL, NULL, 3, NULL, 1, NULL, '2000.00', '2000.00', '-100.00', '0.00', '0.00', 1, '0.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'default.png', NULL, NULL, '1', 'CODE128', NULL, 'New', 1, '1.00', '0.00', '0.00', NULL, NULL, NULL, '2021-11-18 04:38:05', '2021-11-18 12:25:12'),
(315, 1, 'Milk', 'SCD3215177', NULL, NULL, NULL, 13, NULL, 1, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 1, '0.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'default.png', NULL, NULL, '0', 'CODE128', NULL, 'New', 1, '0.00', '0.00', '0.00', NULL, NULL, NULL, '2021-11-18 12:33:08', '2021-11-18 12:33:08'),
(316, 1, 'Animal Feed 1', 'SCD4982386', NULL, NULL, NULL, 4, NULL, 1, NULL, '20.00', '20.00', '0.00', '0.00', '0.00', 1, '0.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'default.png', NULL, NULL, '0', 'CODE128', NULL, 'New', 1, '0.00', '0.00', '0.00', NULL, NULL, NULL, '2021-11-18 12:33:52', '2021-11-18 12:33:52'),
(317, 1, 'Animal Feed 2', 'SCD4539679', NULL, NULL, NULL, 4, NULL, 1, NULL, '25.00', '25.00', '-100.00', '0.00', '0.00', 1, '0.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'default.png', NULL, NULL, '0', 'CODE128', NULL, 'New', 1, '0.00', '0.00', '0.00', NULL, NULL, NULL, '2021-11-18 12:34:18', '2021-11-18 12:34:18'),
(318, 1, 'Alert Qty', 'SCD9672891', NULL, NULL, NULL, 3, NULL, 1, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 1, '5.00', '0.00', 9, 0, 0, 0, 0, 0, 1, NULL, 'default.png', NULL, NULL, '1', 'CODE128', NULL, 'New', 1, '5.00', '0.00', '0.00', NULL, NULL, NULL, '2021-11-20 04:44:47', '2022-01-05 05:30:19'),
(319, 1, 'Iphone', 'SCD9827372', NULL, NULL, NULL, 3, NULL, 1, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 1, '0.00', '0.00', 0, 0, 0, 1, 0, 1, 1, NULL, 'default.png', NULL, NULL, '0', 'CODE128', NULL, 'New', 1, '0.00', '0.00', '0.00', NULL, NULL, NULL, '2021-12-27 06:01:45', '2021-12-27 06:01:45'),
(320, 1, 'Test Product', '761883', 119, NULL, 28, 3, NULL, 1, 19, '80.00', '80.00', '70.00', '136.00', '0.00', 1, '73.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'default.png', NULL, NULL, '1', 'CODE128', NULL, NULL, 1, '7.00', '0.00', '0.00', NULL, NULL, NULL, '2022-01-02 08:09:13', '2022-01-05 05:30:19'),
(321, 1, 'Add Sale Product', '625932', 113, 117, NULL, 4, 1, 1, NULL, '120.00', '126.00', '0.00', '120.00', '0.00', 1, '9.00', '0.00', 0, 0, 0, 0, 0, 1, 1, NULL, 'default.png', NULL, NULL, '1', 'CODE128', NULL, NULL, 1, '1.00', '0.00', '0.00', NULL, NULL, NULL, '2022-01-03 07:12:30', '2022-01-05 05:30:19'),
(322, 1, 'Add Sale Product', '126765', 113, 117, NULL, 4, 1, 1, NULL, '120.00', '126.00', '0.00', '120.00', '0.00', 1, '8.00', '0.00', 0, 0, 0, 0, 0, 1, 1, NULL, 'default.png', NULL, NULL, '1', 'CODE128', NULL, NULL, 1, '2.00', '0.00', '0.00', NULL, NULL, NULL, '2022-01-03 07:12:30', '2022-01-06 11:06:27'),
(323, 1, 'Add Sale Product', '746699', 113, 117, NULL, 4, 1, 1, NULL, '120.00', '126.00', '0.00', '120.00', '0.00', 1, '9.00', '0.00', 0, 0, 0, 0, 0, 1, 1, NULL, 'default.png', NULL, NULL, '1', 'CODE128', NULL, NULL, 1, '1.00', '0.00', '0.00', NULL, NULL, NULL, '2022-01-03 07:12:30', '2022-01-06 11:06:27'),
(324, 1, 'Add Sale Product', '791847', 113, 117, NULL, 4, 1, 1, NULL, '120.00', '126.00', '0.00', '120.00', '0.00', 1, '8.00', '0.00', 0, 0, 0, 0, 0, 1, 1, NULL, 'default.png', NULL, NULL, '1', 'CODE128', NULL, NULL, 1, '2.00', '0.00', '0.00', NULL, NULL, NULL, '2022-01-03 07:12:30', '2022-01-06 11:06:27'),
(325, 1, 'Add Sale Product', '245847', 113, 117, NULL, 4, 1, 1, NULL, '120.00', '126.00', '0.00', '120.00', '0.00', 1, '9.00', '0.00', 0, 0, 0, 0, 0, 1, 1, NULL, 'default.png', NULL, NULL, '1', 'CODE128', NULL, NULL, 1, '1.00', '0.00', '0.00', NULL, NULL, NULL, '2022-01-03 07:12:30', '2022-01-06 11:06:27'),
(326, 1, 'Add Sale Product', '547217', NULL, NULL, NULL, 4, NULL, 1, NULL, '100.00', '100.00', '0.00', '100.00', '0.00', 1, '0.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'default.png', NULL, NULL, '1', 'CODE128', NULL, NULL, 1, '1.00', '0.00', '0.00', NULL, NULL, NULL, '2022-01-03 07:14:01', '2022-01-06 11:06:27'),
(327, 1, 'Add Sale Product', '415843', NULL, NULL, NULL, 3, NULL, 1, NULL, '100.00', '100.00', '0.00', '100.00', '0.00', 1, '0.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'default.png', NULL, NULL, '1', 'CODE128', NULL, NULL, 1, '1.00', '0.00', '0.00', NULL, NULL, NULL, '2022-01-03 07:14:18', '2022-01-06 11:06:27'),
(328, 1, 'Pos Sale Product', '284922', NULL, NULL, NULL, 3, NULL, 1, NULL, '100.00', '100.00', '0.00', '100.00', '0.00', 1, '0.00', '0.00', 0, 0, 0, 0, 0, 1, 1, NULL, 'default.png', NULL, NULL, '1', 'CODE128', NULL, NULL, 1, '1.00', '0.00', '0.00', NULL, NULL, NULL, '2022-01-03 07:15:47', '2022-01-03 07:28:46'),
(329, 1, 'Pos Sale Product', '398248', NULL, NULL, NULL, 3, NULL, 1, NULL, '100.00', '100.00', '0.00', '100.00', '0.00', 1, '94.00', '0.00', 0, 0, 0, 0, 0, 1, 1, NULL, 'default.png', NULL, NULL, '1', 'CODE128', NULL, NULL, 1, '6.00', '0.00', '0.00', NULL, NULL, NULL, '2022-01-03 07:18:15', '2022-01-06 11:06:27'),
(330, 1, 'Pos Sale Product', '258517', NULL, NULL, NULL, 3, NULL, 1, NULL, '100.00', '100.00', '0.00', '100.00', '0.00', 1, '93.00', '0.00', 0, 0, 0, 0, 0, 0, 1, NULL, 'default.png', NULL, NULL, '1', 'CODE128', NULL, NULL, 1, '7.00', '0.00', '0.00', NULL, NULL, NULL, '2022-01-03 07:18:39', '2022-01-06 11:06:27');

-- --------------------------------------------------------

--
-- Table structure for table `product_branches`
--

CREATE TABLE `product_branches` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_quantity` decimal(22,2) DEFAULT 0.00,
  `total_sale` decimal(22,2) NOT NULL DEFAULT 0.00,
  `total_purchased` decimal(22,2) NOT NULL DEFAULT 0.00,
  `total_adjusted` decimal(22,2) NOT NULL DEFAULT 0.00,
  `total_transferred` decimal(22,2) NOT NULL DEFAULT 0.00,
  `total_received` decimal(22,2) NOT NULL DEFAULT 0.00,
  `total_opening_stock` decimal(22,2) NOT NULL DEFAULT 0.00,
  `total_sale_return` decimal(22,2) NOT NULL DEFAULT 0.00,
  `total_purchase_return` decimal(22,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_branches`
--

INSERT INTO `product_branches` (`id`, `branch_id`, `product_id`, `product_quantity`, `total_sale`, `total_purchased`, `total_adjusted`, `total_transferred`, `total_received`, `total_opening_stock`, `total_sale_return`, `total_purchase_return`, `created_at`, `updated_at`) VALUES
(192, 33, 277, '1.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '2021-10-28 04:38:01', '2021-10-28 04:38:01'),
(193, 33, 281, '1.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '2021-10-28 04:38:01', '2021-10-28 04:38:01'),
(194, 33, 278, '1.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '2021-10-28 04:38:01', '2021-10-28 04:38:01'),
(195, 33, 283, '1.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '2021-10-28 04:38:01', '2021-10-28 04:38:01'),
(196, NULL, 273, '-10.00', '13.00', '3.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '2021-10-30 04:53:38', '2021-11-14 10:36:01'),
(197, NULL, 274, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '2021-10-30 04:53:38', '2021-10-30 04:53:38'),
(198, NULL, 275, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '2021-10-30 04:53:38', '2021-10-30 04:53:38'),
(199, NULL, 276, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '2021-10-30 04:53:38', '2021-10-30 04:53:38'),
(200, NULL, 277, '49.00', '79.00', '29.00', '0.00', '0.00', '0.00', '100.00', '1.00', '2.00', '2021-10-30 04:53:38', '2022-01-06 06:19:04'),
(201, NULL, 278, '101.00', '0.00', '1.00', '0.00', '0.00', '0.00', '100.00', '0.00', '0.00', '2021-10-30 04:53:38', '2021-11-14 10:36:01'),
(202, NULL, 279, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '2021-10-30 04:53:38', '2021-10-30 04:53:38'),
(203, NULL, 280, '94.00', '6.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '2021-10-30 04:53:38', '2021-11-11 10:28:42'),
(204, NULL, 281, '103.00', '3.00', '6.00', '0.00', '0.00', '0.00', '100.00', '0.00', '0.00', '2021-10-30 04:53:38', '2021-11-13 04:36:15'),
(205, NULL, 282, '95.00', '6.00', '1.00', '0.00', '0.00', '0.00', '100.00', '0.00', '0.00', '2021-10-30 04:53:38', '2021-11-14 10:36:01'),
(206, NULL, 283, '98.00', '6.00', '4.00', '0.00', '0.00', '0.00', '100.00', '0.00', '0.00', '2021-10-30 04:53:38', '2021-11-14 10:36:01'),
(207, NULL, 284, '5.00', '5.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '2021-10-30 04:53:38', '2021-11-11 10:28:42'),
(208, NULL, 285, '6.00', '7.00', '3.00', '0.00', '0.00', '0.00', '10.00', '0.00', '0.00', '2021-10-30 04:53:38', '2021-11-14 10:36:01'),
(209, NULL, 286, '10.00', '1.00', '1.00', '0.00', '0.00', '0.00', '10.00', '0.00', '0.00', '2021-10-30 04:53:38', '2021-11-14 10:36:01'),
(210, NULL, 287, '3.00', '8.00', '1.00', '0.00', '0.00', '0.00', '10.00', '0.00', '0.00', '2021-10-30 04:53:38', '2021-11-14 10:36:01'),
(211, NULL, 288, '1.00', '10.00', '1.00', '0.00', '0.00', '0.00', '10.00', '0.00', '0.00', '2021-10-30 04:53:38', '2021-11-14 10:36:01'),
(212, NULL, 298, '0.00', '2.00', '2.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '2021-10-30 09:13:11', '2021-12-14 04:40:07'),
(213, NULL, 294, '13.00', '1.00', '14.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '2021-10-30 09:13:20', '2021-11-22 08:13:12'),
(214, NULL, 299, '1.00', '1.00', '2.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '2021-11-09 08:08:00', '2021-12-19 08:08:44'),
(215, NULL, 306, '8.00', '3.00', '11.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '2021-11-10 12:27:14', '2022-01-02 08:51:40'),
(216, NULL, 307, '1.00', '5.00', '6.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '2021-11-10 13:46:11', '2022-01-02 08:01:39'),
(217, NULL, 309, '498998.00', '3.00', '500001.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '2021-11-14 08:46:58', '2022-01-05 05:31:30'),
(218, NULL, 310, '49794.00', '7.00', '50001.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '2021-11-14 08:46:58', '2022-01-05 05:30:18'),
(219, NULL, 311, '48991.00', '10.00', '50001.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '2021-11-14 08:46:58', '2022-01-05 05:30:19'),
(220, NULL, 302, '1.00', '0.00', '1.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '2021-11-14 10:36:01', '2021-11-14 10:36:01'),
(221, NULL, 301, '3.00', '0.00', '3.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '2021-11-14 10:36:01', '2021-11-14 10:36:01'),
(222, NULL, 303, '2.00', '0.00', '2.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '2021-11-14 10:36:01', '2021-11-14 10:36:01'),
(223, NULL, 304, '2.00', '0.00', '2.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '2021-11-14 10:36:01', '2021-11-14 10:36:01'),
(224, NULL, 296, '1.00', '0.00', '1.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '2021-11-14 10:36:01', '2021-11-14 10:36:01'),
(225, NULL, 305, '1.00', '0.00', '1.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '2021-11-14 10:36:01', '2021-11-14 10:36:01'),
(226, NULL, 295, '1.00', '0.00', '1.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '2021-11-14 10:36:01', '2021-11-14 10:36:01'),
(227, NULL, 300, '2.00', '0.00', '2.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '2021-11-14 10:36:01', '2021-11-14 10:36:01'),
(228, NULL, 290, '1.00', '0.00', '1.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '2021-11-14 10:36:01', '2021-11-14 10:36:01'),
(229, NULL, 291, '4.00', '0.00', '4.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '2021-11-14 10:36:01', '2021-12-27 05:33:42'),
(230, NULL, 297, '2.00', '0.00', '3.00', '1.00', '0.00', '0.00', '0.00', '0.00', '0.00', '2021-11-14 10:36:01', '2022-01-02 12:51:14'),
(231, NULL, 292, '1.00', '0.00', '1.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '2021-11-14 10:36:01', '2021-11-14 10:36:01'),
(232, NULL, 308, '995.00', '5.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '2021-11-15 10:56:54', '2022-01-05 05:30:18'),
(233, NULL, 314, '0.00', '1.00', '1.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '2021-11-18 04:39:17', '2021-11-18 12:25:12'),
(234, NULL, 318, '5.00', '5.00', '9.00', '0.00', '0.00', '0.00', '1.00', '0.00', '0.00', '2021-11-20 04:45:48', '2022-01-05 05:30:19'),
(235, NULL, 320, '73.00', '7.00', '0.00', '0.00', '0.00', '0.00', '80.00', '0.00', '0.00', '2022-01-02 08:09:13', '2022-01-05 05:30:19'),
(236, NULL, 321, '9.00', '1.00', '0.00', '0.00', '0.00', '0.00', '10.00', '0.00', '0.00', '2022-01-03 07:12:30', '2022-01-05 05:30:19'),
(237, NULL, 322, '8.00', '2.00', '0.00', '0.00', '0.00', '0.00', '10.00', '0.00', '0.00', '2022-01-03 07:12:30', '2022-01-06 11:06:27'),
(238, NULL, 323, '9.00', '1.00', '0.00', '0.00', '0.00', '0.00', '10.00', '0.00', '0.00', '2022-01-03 07:12:30', '2022-01-06 11:06:27'),
(239, NULL, 324, '8.00', '2.00', '0.00', '0.00', '0.00', '0.00', '10.00', '0.00', '0.00', '2022-01-03 07:12:30', '2022-01-06 11:06:27'),
(240, NULL, 325, '9.00', '1.00', '0.00', '0.00', '0.00', '0.00', '10.00', '0.00', '0.00', '2022-01-03 07:12:30', '2022-01-06 11:06:27'),
(241, NULL, 326, '0.00', '1.00', '0.00', '0.00', '0.00', '0.00', '1.00', '0.00', '0.00', '2022-01-03 07:14:01', '2022-01-06 11:06:27'),
(242, NULL, 327, '0.00', '1.00', '0.00', '0.00', '0.00', '0.00', '1.00', '0.00', '0.00', '2022-01-03 07:14:18', '2022-01-06 11:06:27'),
(243, NULL, 328, '0.00', '1.00', '0.00', '0.00', '0.00', '0.00', '1.00', '0.00', '0.00', '2022-01-03 07:15:47', '2022-01-03 07:28:46'),
(244, NULL, 329, '94.00', '6.00', '0.00', '0.00', '0.00', '0.00', '100.00', '0.00', '0.00', '2022-01-03 07:18:15', '2022-01-06 11:06:27'),
(245, NULL, 330, '93.00', '7.00', '0.00', '0.00', '0.00', '0.00', '100.00', '0.00', '0.00', '2022-01-03 07:18:39', '2022-01-06 11:06:27');

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
  `total_sale` decimal(22,2) NOT NULL DEFAULT 0.00,
  `total_purchased` decimal(22,2) NOT NULL DEFAULT 0.00,
  `total_adjusted` decimal(22,2) NOT NULL DEFAULT 0.00,
  `total_transferred` decimal(22,2) NOT NULL DEFAULT 0.00,
  `total_received` decimal(22,2) NOT NULL DEFAULT 0.00,
  `total_opening_stock` decimal(22,2) NOT NULL DEFAULT 0.00,
  `total_sale_return` decimal(22,2) NOT NULL DEFAULT 0.00,
  `total_purchase_return` decimal(22,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_branch_variants`
--

INSERT INTO `product_branch_variants` (`id`, `product_branch_id`, `product_id`, `product_variant_id`, `variant_quantity`, `total_sale`, `total_purchased`, `total_adjusted`, `total_transferred`, `total_received`, `total_opening_stock`, `total_sale_return`, `total_purchase_return`, `created_at`, `updated_at`) VALUES
(54, 196, 273, 61, '-12.00', '13.00', '1.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '2021-10-30 04:53:38', '2021-11-14 10:36:01'),
(55, 196, 273, 62, '1.00', '0.00', '1.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '2021-10-30 04:53:38', '2021-11-14 10:36:01'),
(56, 196, 273, 63, '1.00', '0.00', '1.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '2021-10-30 04:53:38', '2021-11-14 10:36:01');

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
(241, NULL, NULL, 283, NULL, '3500.00', '100.00', '350000.00', NULL, '2021-10-27 10:00:55', '2021-10-27 10:00:55'),
(242, NULL, NULL, 282, NULL, '250.00', '100.00', '25000.00', NULL, '2021-10-27 10:01:03', '2021-10-27 10:01:03'),
(243, NULL, NULL, 281, NULL, '18500.00', '100.00', '1850000.00', NULL, '2021-10-27 10:01:14', '2021-10-27 10:01:14'),
(244, NULL, NULL, 280, NULL, '80000.00', '100.00', '8000000.00', NULL, '2021-10-27 10:01:22', '2021-10-27 10:01:22'),
(245, NULL, NULL, 278, NULL, '17500.00', '100.00', '1750000.00', NULL, '2021-10-27 10:01:29', '2021-10-27 10:01:29'),
(246, NULL, NULL, 277, NULL, '18000.00', '100.00', '1800000.00', NULL, '2021-10-27 10:01:37', '2021-11-11 12:57:55'),
(247, NULL, NULL, 284, NULL, '0.00', '10.00', '0.00', NULL, '2021-10-27 10:06:44', '2021-10-27 10:06:44'),
(248, NULL, NULL, 285, NULL, '0.00', '10.00', '0.00', NULL, '2021-10-27 10:30:00', '2021-10-27 10:30:00'),
(249, NULL, NULL, 286, NULL, '0.00', '10.00', '0.00', NULL, '2021-10-27 11:59:38', '2021-10-27 11:59:38'),
(250, NULL, NULL, 287, NULL, '0.00', '10.00', '0.00', NULL, '2021-10-27 12:00:20', '2021-10-27 12:00:20'),
(251, NULL, NULL, 288, NULL, '0.00', '10.00', '0.00', NULL, '2021-10-27 12:00:39', '2021-10-27 12:00:39'),
(252, NULL, NULL, 318, NULL, '0.00', '1.00', '0.00', NULL, '2021-11-20 04:55:28', '2021-11-20 04:55:28'),
(253, NULL, NULL, 320, NULL, '0.00', '80.00', '0.00', NULL, '2022-01-02 08:09:13', '2022-01-02 08:09:13'),
(254, NULL, NULL, 321, NULL, '126.00', '10.00', '1260.00', NULL, '2022-01-03 07:12:30', '2022-01-03 07:12:30'),
(255, NULL, NULL, 322, NULL, '126.00', '10.00', '1260.00', NULL, '2022-01-03 07:12:30', '2022-01-03 07:12:30'),
(256, NULL, NULL, 323, NULL, '126.00', '10.00', '1260.00', NULL, '2022-01-03 07:12:30', '2022-01-03 07:12:30'),
(257, NULL, NULL, 324, NULL, '126.00', '10.00', '1260.00', NULL, '2022-01-03 07:12:30', '2022-01-03 07:12:30'),
(258, NULL, NULL, 325, NULL, '126.00', '10.00', '1260.00', NULL, '2022-01-03 07:12:30', '2022-01-03 07:12:30'),
(259, NULL, NULL, 326, NULL, '100.00', '1.00', '100.00', NULL, '2022-01-03 07:14:01', '2022-01-03 07:14:01'),
(260, NULL, NULL, 327, NULL, '100.00', '1.00', '100.00', NULL, '2022-01-03 07:14:18', '2022-01-03 07:14:18'),
(261, NULL, NULL, 328, NULL, '100.00', '1.00', '100.00', NULL, '2022-01-03 07:15:47', '2022-01-03 07:15:47'),
(262, NULL, NULL, 329, NULL, '100.00', '100.00', '10000.00', NULL, '2022-01-03 07:18:15', '2022-01-03 07:18:15'),
(263, NULL, NULL, 330, NULL, '100.00', '100.00', '10000.00', NULL, '2022-01-03 07:18:39', '2022-01-03 07:18:39');

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
(61, 273, '12GB', '12gb-SCD3157248', '-12.00', '13.00', '0.00', '0.00', '300.00', '300.00', '100.00', '600.00', NULL, 1, 0, '2021-10-27 04:26:28', '2021-11-14 10:36:01'),
(62, 273, '8GB', '8gb-SCD3157248', '1.00', '0.00', '0.00', '0.00', '200.00', '200.00', '100.00', '400.00', NULL, 1, 0, '2021-10-27 04:26:28', '2021-11-14 10:36:01'),
(63, 273, '4GB', '4gb-SCD3157248', '1.00', '0.00', '0.00', '0.00', '100.00', '100.00', '100.00', '200.00', NULL, 1, 0, '2021-10-27 04:26:28', '2021-11-14 10:36:01'),
(64, 319, '8GB', '8gb-SCD9827372', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, 0, 0, '2021-12-27 06:01:45', '2021-12-27 06:01:45'),
(65, 319, '4GB', '4gb-SCD9827372', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, 0, 0, '2021-12-27 06:01:45', '2021-12-27 06:01:45');

-- --------------------------------------------------------

--
-- Table structure for table `product_warehouses`
--

CREATE TABLE `product_warehouses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `warehouse_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_quantity` decimal(22,2) DEFAULT 0.00,
  `total_purchased` decimal(22,2) NOT NULL DEFAULT 0.00,
  `total_adjusted` decimal(22,2) NOT NULL DEFAULT 0.00,
  `total_transferred` decimal(22,2) NOT NULL DEFAULT 0.00,
  `total_received` decimal(22,2) NOT NULL DEFAULT 0.00,
  `total_sale_return` decimal(22,2) NOT NULL DEFAULT 0.00,
  `total_purchase_return` decimal(22,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `total_purchased` decimal(22,2) NOT NULL DEFAULT 0.00,
  `total_adjusted` decimal(22,2) NOT NULL DEFAULT 0.00,
  `total_transferred` decimal(22,2) NOT NULL DEFAULT 0.00,
  `total_received` decimal(22,2) NOT NULL DEFAULT 0.00,
  `total_sale_return` decimal(22,2) NOT NULL DEFAULT 0.00,
  `total_purchase_return` decimal(22,2) NOT NULL DEFAULT 0.00,
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
  `is_purchased` tinyint(1) NOT NULL DEFAULT 1,
  `date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `delivery_date` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `time` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `report_date` timestamp NULL DEFAULT NULL,
  `month` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `year` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_last_created` tinyint(1) NOT NULL DEFAULT 0,
  `is_return_available` tinyint(1) NOT NULL DEFAULT 0,
  `attachment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `po_qty` decimal(22,2) NOT NULL DEFAULT 0.00,
  `po_pending_qty` decimal(22,2) NOT NULL DEFAULT 0.00,
  `po_received_qty` decimal(22,2) NOT NULL DEFAULT 0.00,
  `po_receiving_status` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'This field only for order, which numeric status = 3',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchases`
--

INSERT INTO `purchases` (`id`, `invoice_id`, `warehouse_id`, `branch_id`, `supplier_id`, `pay_term`, `pay_term_number`, `total_item`, `net_total_amount`, `order_discount`, `order_discount_type`, `order_discount_amount`, `shipment_details`, `shipment_charge`, `purchase_note`, `purchase_tax_id`, `purchase_tax_percent`, `purchase_tax_amount`, `total_purchase_amount`, `paid`, `due`, `purchase_return_amount`, `purchase_return_due`, `payment_note`, `admin_id`, `purchase_status`, `is_purchased`, `date`, `delivery_date`, `time`, `report_date`, `month`, `year`, `is_last_created`, `is_return_available`, `attachment`, `po_qty`, `po_pending_qty`, `po_received_qty`, `po_receiving_status`, `created_at`, `updated_at`) VALUES
(406, '1021406', NULL, 33, 60, NULL, NULL, 4, '57500.00', '0.00', 1, '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '57500.00', '0.00', '57500.00', '0.00', '0.00', NULL, 16, 1, 1, '28-10-2021', NULL, '10:38:00 am', '2021-10-27 18:00:00', 'October', '2021', 0, 0, NULL, '4.00', '4.00', '0.00', NULL, '2021-10-28 04:38:00', '2021-11-18 04:39:16'),
(407, '1021407', NULL, NULL, 60, NULL, NULL, 1, '3500.00', '0.00', 1, '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '3500.00', '0.00', '3500.00', '0.00', '0.00', NULL, 2, 1, 1, '30-10-2021', NULL, '01:38:03 pm', '2021-10-29 18:00:00', 'October', '2021', 0, 0, NULL, '1.00', '1.00', '0.00', NULL, '2021-10-30 07:38:03', '2021-11-16 12:04:58'),
(408, '1021408', NULL, NULL, 60, NULL, NULL, 1, '3500.00', '0.00', 1, '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '3500.00', '0.00', '3500.00', '0.00', '0.00', NULL, 2, 1, 1, '30-10-2021', NULL, '01:40:16 pm', '2021-10-29 18:00:00', 'October', '2021', 0, 0, NULL, '1.00', '1.00', '0.00', NULL, '2021-10-30 07:40:16', '2021-11-16 12:04:58'),
(409, '1021409', NULL, NULL, 60, NULL, NULL, 1, '0.00', '0.00', 1, '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, 2, 1, 1, '30-10-2021', NULL, '01:57:23 pm', '2021-10-29 18:00:00', 'October', '2021', 0, 0, NULL, '1.00', '1.00', '0.00', NULL, '2021-10-30 07:57:23', '2021-11-16 12:04:58'),
(410, '1021410', NULL, NULL, 63, NULL, NULL, 1, '18000.00', '0.00', 1, '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '18000.00', '18000.00', '0.00', '0.00', '0.00', NULL, 2, 1, 1, '30-10-2021', NULL, '01:57:42 pm', '2021-10-29 18:00:00', 'October', '2021', 0, 0, NULL, '1.00', '1.00', '0.00', NULL, '2021-10-30 07:57:42', '2021-11-16 12:04:58'),
(411, '1021411', NULL, NULL, 60, NULL, NULL, 1, '0.00', '0.00', 1, '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, 2, 1, 1, '30-10-2021', NULL, '03:13:11 pm', '2021-10-29 18:00:00', 'October', '2021', 0, 0, NULL, '1.00', '1.00', '0.00', NULL, '2021-10-30 09:13:11', '2021-11-16 12:04:58'),
(412, '1021412', NULL, NULL, 60, NULL, NULL, 1, '0.00', '0.00', 1, '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, 2, 1, 1, '30-10-2021', NULL, '03:13:20 pm', '2021-10-29 18:00:00', 'October', '2021', 0, 0, NULL, '1.00', '1.00', '0.00', NULL, '2021-10-30 09:13:20', '2021-11-16 12:04:58'),
(414, '1121414', NULL, NULL, 60, NULL, NULL, 1, '18500.00', '0.00', 1, '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '18500.00', '0.00', '18500.00', '0.00', '0.00', NULL, 2, 1, 1, '08-11-2021', NULL, '06:34:15 pm', '2021-11-07 18:00:00', 'November', '2021', 0, 0, NULL, '1.00', '1.00', '0.00', NULL, '2021-11-08 12:34:15', '2021-11-16 12:04:58'),
(415, '1121415', NULL, NULL, 60, NULL, NULL, 1, '18000.00', '0.00', 1, '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '18000.00', '0.00', '18000.00', '0.00', '0.00', NULL, 2, 1, 1, '09-11-2021', NULL, '02:07:48 pm', '2021-11-08 18:00:00', 'November', '2021', 0, 0, NULL, '1.00', '1.00', '0.00', NULL, '2021-11-09 08:07:48', '2021-11-16 12:04:58'),
(416, '1121416', NULL, NULL, 66, NULL, NULL, 1, '0.00', '0.00', 1, '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, 2, 1, 1, '09-11-2021', NULL, '02:08:00 pm', '2021-11-08 18:00:00', 'November', '2021', 0, 0, NULL, '1.00', '1.00', '0.00', NULL, '2021-11-09 08:08:00', '2021-11-16 12:04:58'),
(418, '1121418', NULL, NULL, 66, NULL, NULL, 1, '0.00', '0.00', 1, '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, 2, 1, 1, '09-11-2021', NULL, '02:08:27 pm', '2021-11-08 18:00:00', 'November', '2021', 0, 0, NULL, '1.00', '1.00', '0.00', NULL, '2021-11-09 08:08:27', '2021-11-16 12:04:58'),
(419, '1121419', NULL, NULL, 63, NULL, NULL, 1, '74000.00', '0.00', 1, '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '74000.00', '74000.00', '0.00', '0.00', '0.00', NULL, 2, 1, 1, '09-11-2021', NULL, '04:19:22 pm', '2021-11-08 18:00:00', 'November', '2021', 0, 0, NULL, '4.00', '4.00', '0.00', NULL, '2021-11-09 10:19:22', '2021-11-16 12:04:58'),
(420, '1121420', NULL, NULL, 60, NULL, NULL, 1, '180000.00', '0.00', 1, '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '180000.00', '0.00', '180000.00', '0.00', '0.00', NULL, 2, 1, 1, '09-11-2021', NULL, '05:28:18 pm', '2021-11-08 18:00:00', 'November', '2021', 0, 0, NULL, '10.00', '10.00', '0.00', NULL, '2021-11-09 11:28:18', '2021-11-16 12:04:58'),
(421, '1121421', NULL, NULL, 60, NULL, NULL, 1, '18000.00', '0.00', 1, '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '36000.00', '0.00', '36000.00', '0.00', '0.00', NULL, 2, 1, 1, '09-11-2021', NULL, '05:35:27 pm', '2021-11-08 18:00:00', 'November', '2021', 0, 0, NULL, '1.00', '1.00', '0.00', NULL, '2021-11-09 11:35:27', '2021-11-16 12:04:58'),
(422, '1121422', NULL, NULL, 60, NULL, NULL, 1, '18500.00', '0.00', 1, '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '18500.00', '0.00', '18500.00', '0.00', '0.00', NULL, 2, 1, 1, '09-11-2021', NULL, '05:35:39 pm', '2021-11-08 18:00:00', 'November', '2021', 0, 0, NULL, '1.00', '1.00', '0.00', NULL, '2021-11-09 11:35:39', '2021-11-16 12:04:58'),
(423, '1121423', NULL, NULL, 60, NULL, NULL, 1, '0.00', '0.00', 1, '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, 2, 1, 1, '10-11-2021', NULL, '06:27:14 pm', '2021-11-09 18:00:00', 'November', '2021', 0, 0, NULL, '10.00', '10.00', '0.00', NULL, '2021-11-10 12:27:14', '2021-11-16 12:04:58'),
(424, '1121424', NULL, NULL, 60, NULL, NULL, 1, '18500.00', '0.00', 1, '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '18500.00', '0.00', '18500.00', '0.00', '0.00', NULL, 2, 1, 1, '10-11-2021', NULL, '07:32:00 pm', '2021-11-09 18:00:00', 'November', '2021', 0, 0, NULL, '1.00', '1.00', '0.00', NULL, '2021-11-10 13:32:00', '2021-11-16 12:04:58'),
(425, '1121425', NULL, NULL, 60, NULL, NULL, 1, '200.00', '0.00', 1, '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '200.00', '0.00', '200.00', '0.00', '0.00', NULL, 2, 1, 1, '10-11-2021', NULL, '07:46:11 pm', '2021-11-09 18:00:00', 'November', '2021', 0, 0, NULL, '1.00', '1.00', '0.00', NULL, '2021-11-10 13:46:11', '2021-11-16 12:04:58'),
(426, '1121426', NULL, NULL, 60, NULL, NULL, 1, '500.00', '0.00', 1, '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '500.00', '0.00', '500.00', '0.00', '0.00', NULL, 2, 1, 1, '10-11-2021', NULL, '07:52:03 pm', '2021-11-09 18:00:00', 'November', '2021', 0, 0, NULL, '1.00', '1.00', '0.00', NULL, '2021-11-10 13:52:03', '2021-11-16 12:04:58'),
(427, '1121427', NULL, NULL, 60, NULL, NULL, 1, '500.00', '0.00', 1, '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '500.00', '0.00', '500.00', '0.00', '0.00', NULL, 2, 1, 1, '10-11-2021', NULL, '07:52:14 pm', '2021-11-09 18:00:00', 'November', '2021', 0, 0, NULL, '1.00', '1.00', '0.00', NULL, '2021-11-10 13:52:14', '2021-11-16 12:04:58'),
(428, '1121428', NULL, NULL, 60, NULL, NULL, 1, '500.00', '0.00', 1, '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '500.00', '0.00', '500.00', '0.00', '0.00', NULL, 2, 1, 1, '10-11-2021', NULL, '07:52:37 pm', '2021-11-09 18:00:00', 'November', '2021', 0, 0, NULL, '1.00', '1.00', '0.00', NULL, '2021-11-10 13:52:37', '2021-11-16 12:04:58'),
(429, '1121429', NULL, NULL, 60, NULL, NULL, 1, '10000.00', '0.00', 1, '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '10000.00', '0.00', '10000.00', '0.00', '0.00', NULL, 2, 1, 1, '10-11-2021', NULL, '07:52:42 pm', '2021-11-09 18:00:00', 'November', '2021', 0, 0, NULL, '1.00', '1.00', '0.00', NULL, '2021-11-10 13:52:42', '2021-11-16 12:04:58'),
(430, '1121430', NULL, NULL, 60, NULL, NULL, 1, '18500.00', '0.00', 1, '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '18500.00', '0.00', '0.00', '18500.00', '0.00', NULL, 2, 1, 1, '11-11-2021', NULL, '10:34:37 am', '2021-11-10 18:00:00', 'November', '2021', 0, 1, NULL, '1.00', '1.00', '0.00', NULL, '2021-11-11 04:34:37', '2021-11-16 12:04:58'),
(431, '1121431', NULL, NULL, 60, NULL, NULL, 1, '18500.00', '0.00', 1, '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '18500.00', '0.00', '18500.00', '0.00', '0.00', NULL, 2, 1, 1, '11-11-2021', NULL, '10:41:53 am', '2021-11-10 18:00:00', 'November', '2021', 0, 0, NULL, '1.00', '1.00', '0.00', NULL, '2021-11-11 04:41:53', '2021-11-16 12:04:58'),
(432, '1121432', NULL, NULL, 60, NULL, NULL, 1, '18500.00', '0.00', 1, '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '18500.00', '18500.00', '0.00', '18500.00', '0.00', NULL, 2, 1, 1, '11-11-2021', NULL, '10:49:46 am', '2021-11-10 18:00:00', 'November', '2021', 0, 1, NULL, '1.00', '1.00', '0.00', NULL, '2021-11-11 04:49:46', '2021-11-16 12:04:58'),
(433, '1121433', NULL, NULL, 60, NULL, NULL, 1, '18500.00', '0.00', 1, '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '18500.00', '0.00', '18500.00', '0.00', '0.00', NULL, 2, 1, 1, '11-11-2021', NULL, '12:13:16 pm', '2021-11-10 18:00:00', 'November', '2021', 0, 0, NULL, '1.00', '1.00', '0.00', NULL, '2021-11-11 06:13:16', '2021-11-16 12:04:58'),
(434, '1121434', NULL, NULL, 60, NULL, NULL, 1, '19000.00', '0.00', 1, '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '19000.00', '0.00', '19000.00', '0.00', '0.00', NULL, 2, 1, 1, '11-11-2021', NULL, '01:50:03 pm', '2021-11-10 18:00:00', 'November', '2021', 0, 0, NULL, '1.00', '1.00', '0.00', NULL, '2021-11-11 07:50:03', '2021-11-16 12:04:58'),
(437, '1121435', NULL, NULL, 60, NULL, NULL, 1, '18500.00', '0.00', 1, '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '18500.00', '0.00', '18500.00', '0.00', '0.00', NULL, 2, 1, 1, '14-11-2021', NULL, '01:57:10 pm', '2021-11-13 18:00:00', 'November', '2021', 0, 0, NULL, '1.00', '1.00', '0.00', NULL, '2021-11-14 07:57:10', '2021-11-16 12:04:58'),
(438, '1121438', NULL, NULL, 60, NULL, NULL, 3, '4510000.00', '0.00', 1, '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '4510000.00', '4510000.00', '0.00', '0.00', '0.00', NULL, 2, 1, 1, '14-11-2021', NULL, '02:46:58 pm', '2021-11-13 18:00:00', 'November', '2021', 0, 0, NULL, '600000.00', '600000.00', '0.00', NULL, '2021-11-14 08:46:58', '2021-11-16 12:04:58'),
(439, '1121439', NULL, NULL, 73, NULL, NULL, 1, '18500.00', '0.00', 1, '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '18500.00', '6100.00', '12400.00', '0.00', '0.00', NULL, 2, 1, 1, '14-11-2021', NULL, '03:55:10 pm', '2021-11-13 18:00:00', 'November', '2021', 0, 0, NULL, '1.00', '1.00', '0.00', NULL, '2021-11-14 09:55:10', '2021-12-27 06:30:45'),
(440, '1121440', NULL, NULL, 60, NULL, NULL, 35, '44558.70', '0.00', 1, '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '44558.70', '44558.70', '0.00', '0.00', '0.00', NULL, 2, 1, 1, '14-11-2021', NULL, '04:36:00 pm', '2021-11-13 18:00:00', 'November', '2021', 0, 0, NULL, '46.00', '46.00', '0.00', NULL, '2021-11-14 10:36:00', '2021-11-16 12:04:58'),
(441, '1121441', NULL, NULL, 60, NULL, NULL, 1, '18500.00', '0.00', 1, '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '18500.00', '0.00', '18500.00', '0.00', '0.00', NULL, 2, 1, 1, '15-11-2021', NULL, '10:44:55 am', '2021-11-14 18:00:00', 'November', '2021', 0, 0, NULL, '1.00', '1.00', '0.00', NULL, '2021-11-15 04:44:55', '2021-11-16 12:04:58'),
(442, '00442', NULL, NULL, 60, NULL, NULL, 1, '18500.00', '0.00', 1, '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '18500.00', '0.00', '18500.00', '0.00', '0.00', NULL, 2, 1, 1, '15-11-2021', NULL, '10:51:16 am', '2021-11-14 18:00:00', 'November', '2021', 0, 0, NULL, '1.00', '1.00', '0.00', NULL, '2021-11-15 04:51:16', '2021-11-16 12:04:58'),
(443, '00443', NULL, NULL, 60, NULL, NULL, 1, '18500.00', '0.00', 1, '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '18500.00', '0.00', '18500.00', '0.00', '0.00', NULL, 2, 1, 1, '15-11-2021', NULL, '02:34:17 pm', '2021-11-14 18:00:00', 'November', '2021', 0, 0, NULL, '1.00', '1.00', '0.00', NULL, '2021-11-15 08:34:17', '2021-11-16 12:04:58'),
(444, 'PI00444', NULL, NULL, 60, NULL, NULL, 1, '18500.00', '0.00', 1, '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '18500.00', '0.00', '18500.00', '0.00', '0.00', NULL, 2, 1, 1, '15-11-2021', NULL, '02:35:06 pm', '2021-11-14 18:00:00', 'November', '2021', 0, 0, NULL, '1.00', '1.00', '0.00', NULL, '2021-11-15 08:35:06', '2021-11-16 12:04:58'),
(445, 'PI00445', NULL, NULL, 60, NULL, NULL, 1, '2000.00', '0.00', 1, '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '2000.00', '0.00', '2000.00', '0.00', '0.00', NULL, 2, 1, 1, '18-11-2021', NULL, '10:39:16 am', '2021-11-17 18:00:00', 'November', '2021', 0, 0, NULL, '1.00', '1.00', '0.00', NULL, '2021-11-18 04:39:16', '2021-11-20 04:45:48'),
(446, 'PI00446', NULL, NULL, 60, NULL, NULL, 1, '0.00', '0.00', 1, '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, 2, 1, 1, '20-11-2021', NULL, '10:45:48 am', '2021-11-19 18:00:00', 'November', '2021', 0, 0, NULL, '9.00', '9.00', '0.00', NULL, '2021-11-20 04:45:48', '2021-11-20 13:31:30'),
(447, 'PI00447', NULL, NULL, 67, NULL, NULL, 1, '37000.00', '0.00', 1, '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '37000.00', '0.00', '37000.00', '0.00', '0.00', NULL, 2, 1, 1, '20-11-2021', NULL, '07:21:11 pm', '2021-11-19 18:00:00', 'November', '2021', 0, 0, NULL, '1.00', '1.00', '0.00', NULL, '2021-11-20 13:21:11', '2021-11-22 08:13:12'),
(448, 'PI00448', NULL, NULL, 60, NULL, NULL, 1, '500.00', '0.00', 1, '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '500.00', '500.00', '0.00', '0.00', '0.00', NULL, 2, 1, 1, '22-11-2021', NULL, '02:13:12 pm', '2021-11-21 18:00:00', 'November', '2021', 0, 0, NULL, '10.00', '10.00', '0.00', NULL, '2021-11-22 08:13:12', '2021-12-26 08:24:14'),
(457, 'PI00457', NULL, NULL, 63, NULL, NULL, 1, '0.00', '0.00', 1, '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, 2, 1, 1, '26-12-2021', NULL, '04:40:42 pm', '2021-12-25 18:00:00', 'December', '2021', 0, 0, NULL, '1.00', '1.00', '0.00', NULL, '2021-12-26 10:40:42', '2021-12-26 11:25:21'),
(458, 'PI00458', NULL, NULL, 63, NULL, NULL, 1, '0.00', '0.00', 1, '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, 2, 3, 0, '26-12-2021', NULL, '05:25:21 pm', '2021-12-25 18:00:00', 'December', '2021', 0, 0, NULL, '2.00', '2.00', '0.00', 'Pending', '2021-12-26 11:25:21', '2021-12-26 11:49:03'),
(460, 'PI00459', NULL, NULL, 60, NULL, NULL, 1, '0.00', '0.00', 1, '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, 2, 3, 0, '26-12-2021', '31-12-2021', '05:51:05 pm', '2021-12-25 18:00:00', 'December', '2021', 0, 0, NULL, '0.00', '0.00', '0.00', 'Pending', '2021-12-26 11:51:05', '2021-12-26 12:52:32'),
(461, 'PI00461', NULL, NULL, 60, NULL, NULL, 1, '0.00', '0.00', 1, '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, 2, 1, 1, '26-12-2021', NULL, '06:52:32 pm', '2021-12-25 18:00:00', 'December', '2021', 0, 0, NULL, '1.00', '1.00', '0.00', NULL, '2021-12-26 12:52:32', '2021-12-26 13:00:26'),
(462, 'MYORDER2', NULL, NULL, 60, NULL, NULL, 1, '0.00', '0.00', 1, '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, 2, 3, 0, '26-12-2021', NULL, '07:00:26 pm', '2021-12-25 18:00:00', 'December', '2021', 0, 0, NULL, '0.00', '0.00', '0.00', 'Pending', '2021-12-26 13:00:26', '2021-12-26 13:02:34'),
(463, 'PI00463', NULL, NULL, 60, NULL, NULL, 1, '0.00', '0.00', 1, '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, 2, 1, 1, '26-12-2021', NULL, '07:02:34 pm', '2021-12-25 18:00:00', 'December', '2021', 0, 0, NULL, '1.00', '1.00', '0.00', NULL, '2021-12-26 13:02:34', '2021-12-26 13:03:08'),
(464, 'PI00464', NULL, NULL, 63, NULL, NULL, 1, '0.00', '0.00', 1, '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, 2, 3, 0, '26-12-2021', '30-12-2021', '07:03:08 pm', '2021-12-25 18:00:00', 'December', '2021', 0, 0, NULL, '0.00', '0.00', '0.00', 'Pending', '2021-12-26 13:03:08', '2021-12-27 04:43:18'),
(465, 'PI00465', NULL, NULL, 60, NULL, NULL, 2, '0.00', '0.00', 1, '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, 2, 3, 0, '27-12-2021', NULL, '10:43:18 am', '2021-12-26 18:00:00', 'December', '2021', 0, 0, NULL, '2.00', '2.00', '0.00', 'Pending', '2021-12-27 04:43:18', '2021-12-27 06:27:22'),
(466, 'PI00466', NULL, NULL, 60, NULL, NULL, 2, '0.00', '0.00', 1, '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, 2, 1, 1, '27-12-2021', NULL, '11:29:11 am', '2021-12-26 18:00:00', 'December', '2021', 0, 0, NULL, '2.00', '2.00', '0.00', NULL, '2021-12-27 05:29:11', '2021-12-27 05:30:36'),
(467, 'PI00467', NULL, NULL, 60, NULL, NULL, 2, '0.00', '0.00', 1, '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, 2, 1, 1, '27-12-2021', NULL, '11:30:36 am', '2021-12-26 18:00:00', 'December', '2021', 0, 0, NULL, '2.00', '2.00', '0.00', NULL, '2021-12-27 05:30:36', '2021-12-27 05:33:42'),
(468, 'PI00468', NULL, NULL, 60, NULL, NULL, 2, '0.00', '0.00', 1, '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, 2, 1, 1, '27-12-2021', NULL, '11:33:42 am', '2021-12-26 18:00:00', 'December', '2021', 0, 0, NULL, '2.00', '2.00', '0.00', NULL, '2021-12-27 05:33:42', '2021-12-27 05:34:26'),
(469, 'PI00469', NULL, NULL, 60, NULL, NULL, 1, '0.00', '0.00', 1, '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, 2, 1, 1, '27-12-2021', NULL, '11:34:26 am', '2021-12-26 18:00:00', 'December', '2021', 0, 0, NULL, '1.00', '1.00', '0.00', NULL, '2021-12-27 05:34:26', '2021-12-27 05:35:16'),
(470, 'PI00470', NULL, NULL, 60, NULL, NULL, 1, '0.00', '0.00', 1, '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, 2, 1, 1, '27-12-2021', NULL, '11:35:16 am', '2021-12-26 18:00:00', 'December', '2021', 0, 0, NULL, '1.00', '1.00', '0.00', NULL, '2021-12-27 05:35:16', '2021-12-27 05:36:32'),
(471, 'PI00471', NULL, NULL, 60, NULL, NULL, 1, '0.00', '0.00', 1, '0.00', NULL, '0.00', NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, 2, 1, 1, '27-12-2021', NULL, '11:36:32 am', '2021-12-26 18:00:00', 'December', '2021', 1, 0, NULL, '1.00', '1.00', '0.00', NULL, '2021-12-27 05:36:32', '2021-12-27 05:36:32');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_order_products`
--

CREATE TABLE `purchase_order_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `purchase_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `product_variant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `order_quantity` decimal(22,2) NOT NULL DEFAULT 0.00,
  `received_quantity` decimal(22,2) NOT NULL DEFAULT 0.00,
  `pending_quantity` decimal(22,2) NOT NULL DEFAULT 0.00,
  `unit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unit_cost` decimal(22,2) NOT NULL DEFAULT 0.00,
  `unit_discount` decimal(22,2) NOT NULL DEFAULT 0.00,
  `unit_cost_with_discount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `subtotal` decimal(22,2) NOT NULL DEFAULT 0.00 COMMENT 'Without_tax',
  `tax_id` bigint(20) UNSIGNED DEFAULT NULL,
  `unit_tax_percent` decimal(22,2) NOT NULL DEFAULT 0.00,
  `unit_tax` decimal(22,2) NOT NULL DEFAULT 0.00,
  `net_unit_cost` decimal(22,2) NOT NULL DEFAULT 0.00 COMMENT 'inc_tax',
  `ordered_unit_cost` decimal(22,2) NOT NULL DEFAULT 0.00 COMMENT 'inc_tax',
  `line_total` decimal(22,2) NOT NULL DEFAULT 0.00,
  `profit_margin` decimal(22,2) NOT NULL DEFAULT 0.00,
  `selling_price` decimal(22,2) NOT NULL DEFAULT 0.00,
  `description` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lot_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `delete_in_update` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_order_products`
--

INSERT INTO `purchase_order_products` (`id`, `purchase_id`, `product_id`, `product_variant_id`, `order_quantity`, `received_quantity`, `pending_quantity`, `unit`, `unit_cost`, `unit_discount`, `unit_cost_with_discount`, `subtotal`, `tax_id`, `unit_tax_percent`, `unit_tax`, `net_unit_cost`, `ordered_unit_cost`, `line_total`, `profit_margin`, `selling_price`, `description`, `lot_no`, `delete_in_update`, `created_at`, `updated_at`) VALUES
(21, 458, 293, NULL, '2.00', '0.00', '2.00', 'Piece', '0.00', '0.00', '0.00', '0.00', NULL, '0.00', '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', 'sdf sdfasd dsf sasdf sdf Edited', NULL, 0, '2021-12-26 11:25:21', '2021-12-26 11:35:22'),
(22, 460, 293, NULL, '1.00', '0.00', '1.00', 'Piece', '0.00', '0.00', '0.00', '0.00', NULL, '0.00', '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', 'ASDFsaldfhsuidf fdsalyhuwerlj sadf sadfouisad sdfkjsldu sfdjl jsdlf oisaduf sdf , sdafsioaroaejflasd, lfsfhyuioewasf, sadfsaufhsdfh ,sa fsdfuh saf skjflk, sdfhsadfhuihsdf, sdfhsahdfuishaifshadfsa, sfdsuioadfhsdfkdsjfuhyk', NULL, 0, '2021-12-26 11:51:05', '2021-12-26 11:51:05'),
(23, 462, 293, NULL, '4.00', '0.00', '4.00', 'Piece', '0.00', '0.00', '0.00', '0.00', NULL, '0.00', '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', 'fsa dfsaf\r\nsfd asfd\r\nsa df fsakldfjklsadj sadf sadf \r\nsadf asldfkjl asjdfksa uoifuusadiouf sadfj asldfuo jsadlfkjsa ou\r\n sdfsaj klfjsdafioy sdjflksaj fklsajd fjsaldfjsldfaoiusdu sdalfs aoyufiosdfsj aoui sjadflajs \r\nsdfjsadfhsadlfo uyiosdufsadfsdafsadfklsjdakl sdaf jsdf sakldfj lsakfj\r\nsadflsjalkfjsadlk jsaioudfysa fsafsadfjasdhfuisadyhfsd\r\nsa dfsaklfjs akdjfsadhyfusadhfdskfjkj dflsakdj lksdafj sad  asfjlsadjf osjadlkjaslk jfls jlasdjf lsadj sfl sajdf j', NULL, 0, '2021-12-26 13:00:26', '2021-12-26 13:00:26'),
(24, 464, 293, NULL, '1.00', '0.00', '1.00', 'Piece', '0.00', '0.00', '0.00', '0.00', NULL, '0.00', '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', 'sdfsa fsad f\r\nsa df sadf\r\n asdf a\r\nsdfa s\r\nfds a\r\nfd\r\n s\r\nadf \r\nsaf', NULL, 0, '2021-12-26 13:03:08', '2021-12-26 13:03:08'),
(25, 465, 291, NULL, '1.00', '0.00', '1.00', 'Piece', '0.00', '0.00', '0.00', '0.00', NULL, '0.00', '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', 'Service 3', NULL, 0, '2021-12-27 04:43:18', '2021-12-27 06:27:22'),
(26, 465, 293, NULL, '1.00', '0.00', '1.00', 'Piece', '0.00', '0.00', '0.00', '0.00', NULL, '0.00', '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', 'Service 1', NULL, 0, '2021-12-27 04:43:18', '2021-12-27 06:27:22');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_payments`
--

CREATE TABLE `purchase_payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `purchase_id` bigint(20) UNSIGNED DEFAULT NULL,
  `supplier_return_id` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'only_for_supplier_return_payments',
  `supplier_id` bigint(20) UNSIGNED DEFAULT NULL,
  `supplier_payment_id` bigint(20) UNSIGNED DEFAULT NULL,
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
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_advanced` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_payments`
--

INSERT INTO `purchase_payments` (`id`, `invoice_id`, `purchase_id`, `supplier_return_id`, `supplier_id`, `supplier_payment_id`, `account_id`, `pay_mode`, `paid_amount`, `payment_on`, `payment_type`, `payment_status`, `date`, `time`, `month`, `year`, `report_date`, `card_no`, `card_holder`, `card_type`, `card_transaction_no`, `card_month`, `card_year`, `card_secure_code`, `account_no`, `cheque_no`, `transaction_no`, `admin_id`, `note`, `attachment`, `created_at`, `updated_at`, `is_advanced`) VALUES
(415, 'PPV11211', 419, NULL, NULL, NULL, NULL, 'Cash', '74000.00', 1, 1, NULL, '09-11-2021', NULL, 'November', '2021', '2021-11-08 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, '2021-11-09 10:19:22', '2021-11-09 10:19:22', 0),
(416, 'PPV1121416', 432, NULL, NULL, NULL, NULL, 'Cash', '18500.00', 1, 1, NULL, '11-11-2021', NULL, 'November', '2021', '2021-11-10 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, '2021-11-11 04:49:46', '2021-11-11 04:49:46', 0),
(417, 'PPV1121417', 410, NULL, NULL, 124, 28, 'Cash', '18000.00', 1, 1, NULL, '13-11-2021', NULL, 'November', '2021', '2021-11-12 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, '2021-11-13 04:29:39', '2021-11-13 04:29:39', 0),
(419, 'PRP1121418', 432, NULL, NULL, NULL, 28, 'Cash', '18500.00', 1, 2, NULL, '13-11-2021', '11:19:48 am', 'November', '2021', '2021-11-12 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, '2021-11-13 05:19:48', '2021-11-13 05:19:48', 0),
(420, 'PPV1121420', 438, NULL, NULL, NULL, 28, 'Cash', '4510000.00', 1, 1, NULL, '14-11-2021', NULL, 'November', '2021', '2021-11-13 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, '2021-11-14 08:46:58', '2021-11-14 08:46:58', 0),
(421, 'PPV1121421', 439, NULL, NULL, NULL, NULL, 'Cash', '100.00', 1, 1, NULL, '14-11-2021', NULL, 'November', '2021', '2021-11-13 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, '2021-11-14 09:55:10', '2021-11-14 09:55:10', 0),
(422, 'PPV1121422', 440, NULL, NULL, NULL, 28, 'Cash', '44558.70', 1, 1, NULL, '15-11-2021', '02:32:55 pm', 'November', '2021', '2021-11-14 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, '2021-11-15 08:32:55', '2021-11-15 08:32:55', 0),
(423, 'PPV1121423', 448, NULL, NULL, NULL, 28, 'Cash', '500.00', 1, 1, NULL, '22-11-2021', '02:14:06 pm', 'November', '2021', '2021-11-21 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, '2021-11-22 08:14:06', '2021-11-22 08:14:06', 0),
(424, 'PPV1221424', 439, NULL, NULL, 126, 28, 'Cash', '6000.00', 1, 1, NULL, '27-12-2021', NULL, 'December', '2021', '2021-12-26 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL, '2021-12-27 06:30:45', '2021-12-27 06:30:45', 0);

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
  `description` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_received` tinyint(1) NOT NULL DEFAULT 0,
  `lot_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `delete_in_update` tinyint(1) NOT NULL DEFAULT 0,
  `product_order_product_id` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'when product add from purchase_order_products table',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_products`
--

INSERT INTO `purchase_products` (`id`, `purchase_id`, `product_id`, `product_variant_id`, `quantity`, `unit`, `unit_cost`, `unit_discount`, `unit_cost_with_discount`, `subtotal`, `unit_tax_percent`, `unit_tax`, `net_unit_cost`, `line_total`, `profit_margin`, `selling_price`, `description`, `is_received`, `lot_no`, `delete_in_update`, `product_order_product_id`, `created_at`, `updated_at`) VALUES
(661, 406, 277, NULL, '1.00', 'Piece', '18000.00', '0.00', '18000.00', '18000.00', '0.00', '0.00', '18000.00', '18000.00', '11.11', '20000.00', NULL, 0, NULL, 0, NULL, '2021-10-28 04:38:00', '2021-10-28 04:38:00'),
(662, 406, 281, NULL, '1.00', 'Piece', '18500.00', '0.00', '18500.00', '18500.00', '0.00', '0.00', '18500.00', '18500.00', '8.11', '20000.00', NULL, 0, NULL, 0, NULL, '2021-10-28 04:38:00', '2021-10-28 04:38:00'),
(663, 406, 278, NULL, '1.00', 'Piece', '17500.00', '0.00', '17500.00', '17500.00', '0.00', '0.00', '17500.00', '17500.00', '11.43', '19500.00', NULL, 0, NULL, 0, NULL, '2021-10-28 04:38:00', '2021-10-28 04:38:00'),
(664, 406, 283, NULL, '1.00', 'Piece', '3500.00', '0.00', '3500.00', '3500.00', '0.00', '0.00', '3500.00', '3500.00', '42.86', '5000.00', NULL, 0, NULL, 0, NULL, '2021-10-28 04:38:00', '2021-10-28 04:38:00'),
(665, 407, 283, NULL, '1.00', 'Piece', '3500.00', '0.00', '3500.00', '3500.00', '0.00', '0.00', '3500.00', '3500.00', '42.86', '5000.00', NULL, 0, NULL, 0, NULL, '2021-10-30 07:38:04', '2021-10-30 07:38:04'),
(666, 408, 283, NULL, '1.00', 'Piece', '3500.00', '0.00', '3500.00', '3500.00', '0.00', '0.00', '3500.00', '3500.00', '42.86', '5000.00', NULL, 0, NULL, 0, NULL, '2021-10-30 07:40:16', '2021-10-30 07:40:16'),
(667, 409, 285, NULL, '1.00', 'Piece', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '100.00', NULL, 0, NULL, 0, NULL, '2021-10-30 07:57:23', '2021-10-30 07:57:23'),
(668, 410, 277, NULL, '1.00', 'Piece', '18000.00', '0.00', '18000.00', '18000.00', '0.00', '0.00', '18000.00', '18000.00', '11.11', '20000.00', NULL, 0, NULL, 0, NULL, '2021-10-30 07:57:42', '2021-10-30 07:57:42'),
(669, 411, 298, NULL, '1.00', 'Piece', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '100.00', '10100.00', NULL, 0, NULL, 0, NULL, '2021-10-30 09:13:11', '2021-10-30 09:13:11'),
(670, 412, 294, NULL, '1.00', 'Piece', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', NULL, 0, NULL, 0, NULL, '2021-10-30 09:13:20', '2021-10-30 09:13:20'),
(671, 414, 281, NULL, '1.00', 'Piece', '18500.00', '0.00', '18500.00', '18500.00', '0.00', '0.00', '18500.00', '18500.00', '8.11', '20000.00', NULL, 0, NULL, 0, NULL, '2021-11-08 12:34:15', '2021-11-08 12:34:15'),
(672, 415, 277, NULL, '1.00', 'Piece', '18000.00', '0.00', '18000.00', '18000.00', '0.00', '0.00', '18000.00', '18000.00', '11.11', '20000.00', NULL, 0, NULL, 0, NULL, '2021-11-09 08:07:48', '2021-11-10 09:00:29'),
(673, 416, 299, NULL, '1.00', 'Piece', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', NULL, 0, NULL, 0, NULL, '2021-11-09 08:08:00', '2021-11-09 08:08:00'),
(675, 418, 294, NULL, '1.00', 'Piece', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', NULL, 0, NULL, 0, NULL, '2021-11-09 08:08:27', '2021-11-09 08:08:27'),
(676, 419, 281, NULL, '4.00', 'Piece', '18500.00', '0.00', '18500.00', '74000.00', '0.00', '0.00', '18500.00', '74000.00', '8.11', '20000.00', NULL, 0, NULL, 0, NULL, '2021-11-09 10:19:22', '2021-11-09 10:19:22'),
(677, 420, 277, NULL, '10.00', 'Piece', '18000.00', '0.00', '18000.00', '180000.00', '0.00', '0.00', '18000.00', '180000.00', '11.11', '20000.00', NULL, 0, NULL, 0, NULL, '2021-11-09 11:28:18', '2021-11-09 11:28:18'),
(678, 421, 277, NULL, '2.00', 'Piece', '18000.00', '0.00', '18000.00', '36000.00', '0.00', '0.00', '18000.00', '36000.00', '11.11', '20000.00', NULL, 0, NULL, 0, NULL, '2021-11-09 11:35:27', '2021-11-10 08:56:45'),
(679, 422, 281, NULL, '1.00', 'Piece', '18500.00', '0.00', '18500.00', '18500.00', '0.00', '0.00', '18500.00', '18500.00', '8.11', '20000.00', NULL, 0, NULL, 0, NULL, '2021-11-09 11:35:39', '2021-11-09 11:35:39'),
(680, 423, 306, NULL, '10.00', 'Piece', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '100.00', '200.00', NULL, 0, NULL, 0, NULL, '2021-11-10 12:27:14', '2021-11-11 11:02:23'),
(681, 424, 277, NULL, '1.00', 'Piece', '18500.00', '0.00', '18500.00', '18500.00', '0.00', '0.00', '18500.00', '18500.00', '11.11', '22000.00', NULL, 0, NULL, 0, NULL, '2021-11-10 13:32:00', '2021-11-10 13:32:00'),
(682, 425, 307, NULL, '1.00', 'Piece', '200.00', '0.00', '200.00', '200.00', '0.00', '0.00', '200.00', '200.00', '0.00', '250.00', NULL, 0, NULL, 0, NULL, '2021-11-10 13:46:11', '2021-11-10 13:46:11'),
(683, 426, 307, NULL, '1.00', 'Piece', '500.00', '0.00', '500.00', '500.00', '0.00', '0.00', '500.00', '500.00', '20.00', '600.00', NULL, 0, NULL, 0, NULL, '2021-11-10 13:52:03', '2021-11-11 10:41:50'),
(684, 427, 307, NULL, '1.00', 'Piece', '500.00', '0.00', '500.00', '500.00', '0.00', '0.00', '500.00', '500.00', '0.00', '0.00', NULL, 0, NULL, 0, NULL, '2021-11-10 13:52:14', '2021-11-10 13:52:14'),
(685, 428, 307, NULL, '1.00', 'Piece', '500.00', '0.00', '500.00', '500.00', '0.00', '0.00', '500.00', '500.00', '0.00', '0.00', NULL, 0, NULL, 0, NULL, '2021-11-10 13:52:37', '2021-11-10 13:52:37'),
(686, 429, 307, NULL, '1.00', 'Piece', '10000.00', '0.00', '10000.00', '10000.00', '0.00', '0.00', '10000.00', '10000.00', '100.00', '20000.00', NULL, 0, NULL, 0, NULL, '2021-11-10 13:52:42', '2021-11-10 13:58:04'),
(687, 430, 277, NULL, '1.00', 'Piece', '18500.00', '0.00', '18500.00', '18500.00', '0.00', '0.00', '18500.00', '18500.00', '0.00', '0.00', NULL, 0, NULL, 0, NULL, '2021-11-11 04:34:37', '2021-11-11 04:34:37'),
(688, 431, 277, NULL, '1.00', 'Piece', '18500.00', '0.00', '18500.00', '18500.00', '0.00', '0.00', '18500.00', '18500.00', '0.00', '0.00', NULL, 0, NULL, 0, NULL, '2021-11-11 04:41:53', '2021-11-11 04:41:53'),
(689, 432, 277, NULL, '1.00', 'Piece', '18500.00', '0.00', '18500.00', '18500.00', '0.00', '0.00', '18500.00', '18500.00', '0.00', '0.00', NULL, 0, NULL, 0, NULL, '2021-11-11 04:49:46', '2021-11-11 04:49:46'),
(690, 433, 277, NULL, '1.00', 'Piece', '18500.00', '0.00', '18500.00', '18500.00', '0.00', '0.00', '18500.00', '18500.00', '11.00', '20535.00', NULL, 0, NULL, 0, NULL, '2021-11-11 06:13:16', '2021-11-11 13:09:01'),
(691, 434, 277, NULL, '1.00', 'Piece', '19000.00', '0.00', '19000.00', '19000.00', '0.00', '0.00', '19000.00', '19000.00', '18.42', '22500.00', NULL, 0, NULL, 0, NULL, '2021-11-11 07:50:03', '2021-11-11 11:20:02'),
(694, 437, 277, NULL, '1.00', 'Piece', '18500.00', '0.00', '18500.00', '18500.00', '50.00', '9250.00', '18500.00', '18500.00', '11.00', '20535.00', NULL, 0, NULL, 0, NULL, '2021-11-14 07:57:10', '2021-11-14 10:00:09'),
(695, 438, 309, NULL, '500000.00', 'Kilogram', '3.50', '0.00', '3.50', '1750000.00', '0.00', '0.00', '3.50', '1750000.00', '-100.00', '0.00', NULL, 0, NULL, 0, NULL, '2021-11-14 08:46:58', '2021-11-14 08:46:58'),
(696, 438, 310, NULL, '50000.00', 'Kilogram', '5.20', '0.00', '5.20', '260000.00', '0.00', '0.00', '5.20', '260000.00', '-100.00', '0.00', NULL, 0, NULL, 0, NULL, '2021-11-14 08:46:58', '2021-11-14 08:46:58'),
(697, 438, 311, NULL, '50000.00', 'Kilogram', '50.00', '0.00', '50.00', '2500000.00', '0.00', '0.00', '50.00', '2500000.00', '-100.00', '0.00', NULL, 0, NULL, 0, NULL, '2021-11-14 08:46:58', '2021-11-14 08:46:58'),
(698, 439, 277, NULL, '1.00', 'Piece', '18500.00', '0.00', '18500.00', '18500.00', '50.00', '9250.00', '18500.00', '18500.00', '11.00', '20535.00', NULL, 0, NULL, 0, NULL, '2021-11-14 09:55:10', '2021-11-14 09:55:10'),
(699, 440, 276, NULL, '1.00', 'Piece', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '100.00', '500.00', NULL, 0, NULL, 0, NULL, '2021-11-14 10:36:00', '2021-11-14 10:36:00'),
(700, 440, 282, NULL, '1.00', 'Piece', '250.00', '0.00', '250.00', '250.00', '0.00', '0.00', '250.00', '250.00', '40.00', '350.00', NULL, 0, NULL, 0, NULL, '2021-11-14 10:36:00', '2021-11-14 10:36:00'),
(701, 440, 273, 63, '1.00', 'Piece', '100.00', '0.00', '100.00', '100.00', '0.00', '0.00', '100.00', '100.00', '100.00', '200.00', NULL, 0, NULL, 0, NULL, '2021-11-14 10:36:00', '2021-11-14 10:36:00'),
(702, 440, 273, 62, '1.00', 'Piece', '200.00', '0.00', '200.00', '200.00', '0.00', '0.00', '200.00', '200.00', '100.00', '400.00', NULL, 0, NULL, 0, NULL, '2021-11-14 10:36:00', '2021-11-14 10:36:00'),
(703, 440, 273, 61, '1.00', 'Piece', '300.00', '0.00', '300.00', '300.00', '0.00', '0.00', '300.00', '300.00', '100.00', '600.00', NULL, 0, NULL, 0, NULL, '2021-11-14 10:36:00', '2021-11-14 10:36:00'),
(704, 440, 302, NULL, '1.00', 'Piece', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', NULL, 0, NULL, 0, NULL, '2021-11-14 10:36:00', '2021-11-14 10:36:00'),
(705, 440, 279, NULL, '1.00', 'Piece', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '100.00', '150.00', NULL, 0, NULL, 0, NULL, '2021-11-14 10:36:00', '2021-11-14 10:36:00'),
(706, 440, 301, NULL, '3.00', 'Piece', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', NULL, 0, NULL, 0, NULL, '2021-11-14 10:36:00', '2021-11-14 10:36:00'),
(707, 440, 303, NULL, '2.00', 'Piece', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', NULL, 0, NULL, 0, NULL, '2021-11-14 10:36:00', '2021-11-14 10:36:00'),
(708, 440, 304, NULL, '2.00', 'Piece', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', NULL, 0, NULL, 0, NULL, '2021-11-14 10:36:00', '2021-11-14 10:36:00'),
(709, 440, 306, NULL, '1.00', 'Piece', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '100.00', '200.00', NULL, 0, NULL, 0, NULL, '2021-11-14 10:36:00', '2021-11-14 10:36:00'),
(710, 440, 309, NULL, '1.00', 'Kilogram', '3.50', '0.00', '3.50', '3.50', '0.00', '0.00', '3.50', '3.50', '-100.00', '0.00', NULL, 0, NULL, 0, NULL, '2021-11-14 10:36:00', '2021-11-14 10:36:00'),
(711, 440, 307, NULL, '1.00', 'Piece', '500.00', '0.00', '500.00', '500.00', '0.00', '0.00', '500.00', '500.00', '20.00', '600.00', NULL, 0, NULL, 0, NULL, '2021-11-14 10:36:00', '2021-11-14 10:36:00'),
(712, 440, 296, NULL, '1.00', 'Piece', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', NULL, 0, NULL, 0, NULL, '2021-11-14 10:36:00', '2021-11-14 10:36:00'),
(713, 440, 278, NULL, '1.00', 'Piece', '17500.00', '0.00', '17500.00', '17500.00', '0.00', '0.00', '17500.00', '17500.00', '11.43', '19500.00', NULL, 0, NULL, 0, NULL, '2021-11-14 10:36:00', '2021-11-14 10:36:00'),
(714, 440, 305, NULL, '1.00', 'Piece', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '100.00', '95.00', NULL, 0, NULL, 0, NULL, '2021-11-14 10:36:00', '2021-11-14 10:36:00'),
(715, 440, 286, NULL, '1.00', 'Piece', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', NULL, 0, NULL, 0, NULL, '2021-11-14 10:36:00', '2021-11-14 10:36:00'),
(716, 440, 287, NULL, '1.00', 'Piece', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '100.00', NULL, 0, NULL, 0, NULL, '2021-11-14 10:36:00', '2021-11-14 10:36:00'),
(717, 440, 288, NULL, '1.00', 'Piece', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '100.00', NULL, 0, NULL, 0, NULL, '2021-11-14 10:36:00', '2021-11-14 10:36:00'),
(718, 440, 295, NULL, '1.00', 'Piece', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', NULL, 0, NULL, 0, NULL, '2021-11-14 10:36:00', '2021-11-14 10:36:00'),
(719, 440, 300, NULL, '2.00', 'Piece', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', NULL, 0, NULL, 0, NULL, '2021-11-14 10:36:00', '2021-11-14 10:36:00'),
(720, 440, 310, NULL, '1.00', 'Kilogram', '5.20', '0.00', '5.20', '5.20', '0.00', '0.00', '5.20', '5.20', '-100.00', '0.00', NULL, 0, NULL, 0, NULL, '2021-11-14 10:36:00', '2021-11-14 10:36:00'),
(721, 440, 311, NULL, '1.00', 'Kilogram', '50.00', '0.00', '50.00', '50.00', '0.00', '0.00', '50.00', '50.00', '-100.00', '0.00', NULL, 0, NULL, 0, NULL, '2021-11-14 10:36:00', '2021-11-14 10:36:00'),
(722, 440, 274, NULL, '1.00', 'Piece', '150.00', '0.00', '150.00', '150.00', '0.00', '0.00', '150.00', '150.00', '100.00', '300.00', NULL, 0, NULL, 0, NULL, '2021-11-14 10:36:00', '2021-11-14 10:36:00'),
(723, 440, 285, NULL, '2.00', 'Piece', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '100.00', NULL, 0, NULL, 0, NULL, '2021-11-14 10:36:00', '2021-11-14 10:36:00'),
(724, 440, 290, NULL, '1.00', 'Piece', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', NULL, 0, NULL, 0, NULL, '2021-11-14 10:36:00', '2021-11-14 10:36:00'),
(725, 440, 291, NULL, '1.00', 'Piece', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', NULL, 0, NULL, 0, NULL, '2021-11-14 10:36:00', '2021-11-14 10:36:00'),
(726, 440, 275, NULL, '2.00', 'Piece', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '100.00', '500.00', NULL, 0, NULL, 0, NULL, '2021-11-14 10:36:00', '2021-11-14 10:36:00'),
(727, 440, 283, NULL, '2.00', 'Piece', '3500.00', '0.00', '3500.00', '7000.00', '0.00', '0.00', '3500.00', '7000.00', '42.86', '5000.00', NULL, 0, NULL, 0, NULL, '2021-11-14 10:36:00', '2021-11-14 10:36:00'),
(728, 440, 297, NULL, '3.00', 'Piece', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '100.00', '1000.00', NULL, 0, NULL, 0, NULL, '2021-11-14 10:36:00', '2021-11-14 10:36:00'),
(729, 440, 299, NULL, '1.00', 'Piece', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', NULL, 0, NULL, 0, NULL, '2021-11-14 10:36:00', '2021-11-14 10:36:00'),
(730, 440, 277, NULL, '1.00', 'Piece', '18500.00', '0.00', '18500.00', '18500.00', '50.00', '9250.00', '18500.00', '18500.00', '11.00', '20535.00', NULL, 0, NULL, 0, NULL, '2021-11-14 10:36:00', '2021-11-14 10:36:00'),
(731, 440, 292, NULL, '1.00', 'Piece', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, 0, NULL, 0, NULL, '2021-11-14 10:36:00', '2021-11-14 10:36:00'),
(732, 440, 294, NULL, '2.00', 'Piece', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', NULL, 0, NULL, 0, NULL, '2021-11-14 10:36:00', '2021-11-14 10:36:00'),
(733, 440, 298, NULL, '1.00', 'Piece', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '100.00', '10100.00', NULL, 0, NULL, 0, NULL, '2021-11-14 10:36:00', '2021-11-14 10:36:00'),
(734, 441, 277, NULL, '1.00', 'Piece', '18500.00', '0.00', '18500.00', '18500.00', '50.00', '9250.00', '18500.00', '18500.00', '11.00', '20535.00', NULL, 0, NULL, 0, NULL, '2021-11-15 04:44:55', '2021-11-15 04:44:55'),
(735, 442, 277, NULL, '1.00', 'Piece', '18500.00', '0.00', '18500.00', '18500.00', '50.00', '9250.00', '18500.00', '18500.00', '11.00', '20535.00', NULL, 0, NULL, 0, NULL, '2021-11-15 04:51:16', '2021-11-15 04:51:16'),
(736, 443, 277, NULL, '1.00', 'Piece', '18500.00', '0.00', '18500.00', '18500.00', '50.00', '9250.00', '18500.00', '18500.00', '11.00', '20535.00', NULL, 0, NULL, 0, NULL, '2021-11-15 08:34:17', '2021-11-15 08:34:17'),
(737, 444, 277, NULL, '1.00', 'Piece', '18500.00', '0.00', '18500.00', '18500.00', '50.00', '9250.00', '18500.00', '18500.00', '11.00', '20535.00', NULL, 0, NULL, 0, NULL, '2021-11-15 08:35:07', '2021-11-15 08:35:07'),
(738, 445, 314, NULL, '1.00', 'Piece', '2000.00', '0.00', '2000.00', '2000.00', '0.00', '0.00', '2000.00', '2000.00', '-100.00', '0.00', NULL, 0, NULL, 0, NULL, '2021-11-18 04:39:16', '2021-11-18 04:39:16'),
(739, 446, 318, NULL, '9.00', 'Piece', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, 0, NULL, 0, NULL, '2021-11-20 04:45:48', '2021-11-20 13:31:30'),
(740, 447, 277, NULL, '2.00', 'Piece', '18500.00', '0.00', '18500.00', '37000.00', '50.00', '9250.00', '18500.00', '37000.00', '11.00', '20535.00', NULL, 0, NULL, 0, NULL, '2021-11-20 13:21:11', '2021-11-20 13:21:37'),
(741, 448, 294, NULL, '10.00', 'Piece', '50.00', '0.00', '50.00', '500.00', '0.00', '0.00', '50.00', '500.00', '100.00', '100.00', NULL, 0, NULL, 0, NULL, '2021-11-22 08:13:12', '2021-11-22 08:13:12'),
(742, 457, 293, NULL, '1.00', 'Piece', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', 'sdf sdfasd dsf sasdf sdf', 0, NULL, 0, NULL, '2021-12-26 10:40:42', '2021-12-26 10:40:42'),
(744, 461, 293, NULL, '1.00', 'Piece', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', NULL, 0, NULL, 0, NULL, '2021-12-26 12:52:32', '2021-12-26 12:52:32'),
(745, 463, 293, NULL, '1.00', 'Piece', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', 'asdfsadf asdf \r\nas dfasdf\r\nasdf sa\r\ndf \r\nsdaf\r\n sa\r\ndf\r\n sad\r\nf a', 0, NULL, 0, NULL, '2021-12-26 13:02:34', '2021-12-26 13:02:34'),
(746, 466, 291, NULL, '1.00', 'Piece', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', 'Service 3', 0, NULL, 0, NULL, '2021-12-27 05:29:11', '2021-12-27 05:29:11'),
(747, 466, 293, NULL, '1.00', 'Piece', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', 'Service 1', 0, NULL, 0, NULL, '2021-12-27 05:29:11', '2021-12-27 05:29:11'),
(748, 467, 291, NULL, '1.00', 'Piece', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', 'servsdf sfd as', 0, NULL, 0, NULL, '2021-12-27 05:30:36', '2021-12-27 05:30:36'),
(749, 467, 293, NULL, '1.00', 'Piece', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', 'sadfsafd fdsa dfsa', 0, NULL, 0, NULL, '2021-12-27 05:30:36', '2021-12-27 05:30:36'),
(750, 468, 291, NULL, '1.00', 'Piece', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', 'dsaf df asdf asfd', 0, NULL, 0, NULL, '2021-12-27 05:33:42', '2021-12-27 05:33:42'),
(751, 468, 293, NULL, '1.00', 'Piece', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', 'sdfdas fas df a asf', 0, NULL, 0, NULL, '2021-12-27 05:33:42', '2021-12-27 05:33:42'),
(752, 469, 293, NULL, '1.00', 'Piece', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', NULL, 0, NULL, 0, NULL, '2021-12-27 05:34:26', '2021-12-27 05:34:26'),
(753, 470, 293, NULL, '1.00', 'Piece', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', 'dfsasdf sdf sadf sdafa dfa', 0, NULL, 0, NULL, '2021-12-27 05:35:16', '2021-12-27 05:35:16'),
(754, 471, 293, NULL, '1.00', 'Piece', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', NULL, 0, NULL, 0, NULL, '2021-12-27 05:36:32', '2021-12-27 05:36:32');

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
(77, '11211', 430, 2, NULL, NULL, NULL, 1, '18500.00', '0.00', '0.00', '0.00', '0.00', '13-11-2021', 'November', '2021', '2021-11-12 18:00:00', '2021-11-13 05:18:13', '2021-11-13 05:18:13'),
(78, '112178', 432, 2, NULL, NULL, NULL, 1, '18500.00', '0.00', '18500.00', '0.00', '0.00', '13-11-2021', 'November', '2021', '2021-11-12 18:00:00', '2021-11-13 05:18:38', '2021-11-13 05:19:48');

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
(209, 77, 687, 277, NULL, '1.00', 'Piece', '18500.00', 0, '2021-11-13 05:18:13', '2021-11-13 05:18:13'),
(210, 78, 689, 277, NULL, '1.00', 'Piece', '18500.00', 0, '2021-11-13 05:18:38', '2021-11-13 05:18:38');

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
(19, 'Brancher Manger', '2021-09-13 04:27:49', '2021-09-13 04:27:49'),
(38, 'Seller', '2022-01-04 12:15:23', '2022-01-04 12:15:23');

-- --------------------------------------------------------

--
-- Table structure for table `role_permissions`
--

CREATE TABLE `role_permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `purchase` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `s_adjust` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expense` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sale` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `register` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
  `others` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_super_admin_role` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_permissions`
--

INSERT INTO `role_permissions` (`id`, `role_id`, `user`, `contact`, `product`, `purchase`, `s_adjust`, `expense`, `sale`, `register`, `report`, `setup`, `dashboard`, `accounting`, `hrms`, `essential`, `manufacturing`, `project`, `repair`, `superadmin`, `e_commerce`, `others`, `is_super_admin_role`, `created_at`, `updated_at`) VALUES
(8, NULL, '{\"user_view\":1,\"user_add\":1,\"user_edit\":1,\"user_delete\":1,\"role_view\":1,\"role_add\":1,\"role_edit\":1,\"role_delete\":1}', '{\"supplier_all\":1,\"supplier_add\":1,\"supplier_import\":1,\"supplier_edit\":1,\"supplier_delete\":1,\"customer_all\":1,\"customer_add\":1,\"customer_import\":1,\"customer_edit\":1,\"customer_delete\":1,\"customer_group\":1}', '{\"product_all\":1,\"product_add\":1,\"product_edit\":1,\"openingStock_add\":1,\"product_delete\":0,\"categories\":1,\"brand\":1,\"units\":1,\"variant\":1,\"warranties\":1,\"selling_price_group\":1,\"generate_barcode\":1}', '{\"purchase_all\":1,\"purchase_add\":1,\"purchase_edit\":1,\"purchase_delete\":1,\"purchase_payment\":1,\"purchase_return\":1,\"status_update\":1}', '{\"adjustment_all\":1,\"adjustment_add_from_location\":1,\"adjustment_add_from_warehouse\":1,\"adjustment_delete\":1}', '{\"view_expense\":1,\"add_expense\":1,\"edit_expense\":1,\"delete_expense\":1,\"expense_category\":1,\"category_wise_expense\":1}', '{\"pos_all\":1,\"pos_add\":1,\"pos_edit\":1,\"pos_delete\":1,\"create_add_sale\":1,\"view_add_sale\":1,\"edit_add_sale\":1,\"delete_add_sale\":1,\"sale_draft\":1,\"sale_quotation\":1,\"sale_payment\":1,\"edit_price_sale_screen\":1,\"edit_price_pos_screen\":1,\"edit_discount_sale_screen\":1,\"edit_discount_pos_screen\":1,\"shipment_access\":1,\"view_product_cost_is_sale_screed\":1,\"view_own_sale\":1,\"return_access\":1}', '{\"register_view\":1,\"register_close\":1,\"another_register_close\":1}', '{\"loss_profit_report\":1,\"purchase_sale_report\":1,\"tax_report\":1,\"customer_report\":1,\"supplier_report\":1,\"stock_report\":1,\"stock_adjustment_report\":1,\"pro_purchase_report\":1,\"pro_sale_report\":1,\"purchase_payment_report\":1,\"sale_payment_report\":1,\"expanse_report\":1,\"c_register_report\":1,\"sale_representative_report\":1,\"payroll_report\":1,\"payroll_payment_report\":1,\"attendance_report\":1,\"production_report\":1,\"financial_report\":1}', '{\"tax\":1,\"branch\":1,\"warehouse\":1,\"g_settings\":1,\"p_settings\":1,\"inv_sc\":1,\"inv_lay\":1,\"barcode_settings\":1,\"cash_counters\":1}', '{\"dash_data\":1}', '{\"ac_access\":1}', '{\"hrm_dashboard\":1,\"leave_type\":1,\"leave_assign\":1,\"shift\":1,\"attendance\":1,\"view_allowance_and_deduction\":1,\"payroll\":1,\"holiday\":1,\"department\":1,\"designation\":1}', '{\"assign_todo\":1,\"work_space\":1,\"memo\":1,\"msg\":1}', '{\"process_view\":1,\"process_add\":1,\"process_edit\":1,\"process_delete\":1,\"production_view\":1,\"production_add\":1,\"production_edit\":1,\"production_delete\":1,\"manuf_settings\":1,\"manuf_report\":1}', '{\"proj_view\":1,\"proj_create\":1,\"proj_edit\":1,\"proj_delete\":1}', '{\"ripe_add_invo\":1,\"ripe_edit_invo\":1,\"ripe_view_invo\":1,\"ripe_delete_invo\":1,\"change_invo_status\":1,\"ripe_jop_sheet_status\":1,\"ripe_jop_sheet_add\":1,\"ripe_jop_sheet_edit\":1,\"ripe_jop_sheet_delete\":1,\"ripe_only_assinged_job_sheet\":1,\"ripe_view_all_job_sheet\":1}', '{\"superadmin_access_pack_subscrip\":1}', '{\"e_com_sync_pro_cate\":1,\"e_com_sync_pro\":1,\"e_com_sync_order\":1,\"e_com_map_tax_rate\":1}', '{\"today_summery\":1,\"communication\":1}', 1, '2021-01-26 10:45:14', '2021-01-26 10:45:14'),
(29, 38, '{\"user_view\":0,\"user_add\":0,\"user_edit\":0,\"user_delete\":0,\"role_view\":0,\"role_add\":0,\"role_edit\":0,\"role_delete\":0}', '{\"supplier_all\":1,\"supplier_add\":0,\"supplier_import\":0,\"supplier_edit\":0,\"supplier_delete\":0,\"customer_all\":1,\"customer_add\":0,\"customer_import\":0,\"customer_edit\":0,\"customer_delete\":0,\"customer_group\":0}', '{\"product_all\":0,\"product_add\":0,\"product_edit\":0,\"openingStock_add\":0,\"product_delete\":0,\"categories\":0,\"brand\":0,\"units\":0,\"variant\":0,\"warranties\":0,\"selling_price_group\":0,\"generate_barcode\":0}', '{\"purchase_all\":0,\"purchase_add\":0,\"purchase_edit\":0,\"purchase_delete\":0,\"purchase_payment\":0,\"purchase_return\":0,\"status_update\":0}', '{\"adjustment_all\":0,\"adjustment_add_from_location\":0,\"adjustment_add_from_warehouse\":0,\"adjustment_delete\":0}', '{\"view_expense\":0,\"add_expense\":0,\"edit_expense\":0,\"delete_expense\":0,\"expense_category\":0,\"category_wise_expense\":0}', '{\"pos_all\":1,\"pos_add\":1,\"pos_edit\":0,\"pos_delete\":0,\"create_add_sale\":0,\"view_add_sale\":0,\"edit_add_sale\":0,\"delete_add_sale\":0,\"sale_draft\":0,\"sale_quotation\":0,\"sale_payment\":1,\"edit_price_sale_screen\":0,\"edit_price_pos_screen\":0,\"edit_discount_sale_screen\":0,\"edit_discount_pos_screen\":0,\"shipment_access\":0,\"view_product_cost_is_sale_screed\":0,\"view_own_sale\":1,\"return_access\":0}', '{\"register_view\":0,\"register_close\":0,\"another_register_close\":0}', '{\"loss_profit_report\":0,\"purchase_sale_report\":0,\"tax_report\":0,\"customer_report\":0,\"supplier_report\":0,\"stock_report\":0,\"stock_adjustment_report\":0,\"pro_purchase_report\":0,\"pro_sale_report\":0,\"purchase_payment_report\":0,\"sale_payment_report\":0,\"expanse_report\":0,\"c_register_report\":0,\"sale_representative_report\":0,\"payroll_report\":0,\"payroll_payment_report\":0,\"attendance_report\":0,\"production_report\":0,\"financial_report\":0}', '{\"tax\":0,\"branch\":0,\"warehouse\":0,\"g_settings\":0,\"p_settings\":0,\"inv_sc\":0,\"inv_lay\":0,\"barcode_settings\":0,\"cash_counters\":0}', '{\"dash_data\":0}', '{\"ac_access\":0}', '{\"hrm_dashboard\":0,\"leave_type\":0,\"leave_assign\":0,\"shift\":0,\"attendance\":0,\"view_allowance_and_deduction\":0,\"payroll\":0,\"holiday\":0,\"department\":0,\"designation\":0}', '{\"assign_todo\":0,\"work_space\":0,\"memo\":0,\"msg\":0}', '{\"process_view\":0,\"process_add\":0,\"process_edit\":0,\"process_delete\":0,\"production_view\":0,\"production_add\":0,\"production_edit\":0,\"production_delete\":0,\"manuf_settings\":0,\"manuf_report\":0}', '{\"proj_view\":0,\"proj_create\":0,\"proj_edit\":0,\"proj_delete\":0}', '{\"ripe_add_invo\":0,\"ripe_edit_invo\":0,\"ripe_view_invo\":0,\"ripe_delete_invo\":0,\"change_invo_status\":0,\"ripe_jop_sheet_status\":0,\"ripe_jop_sheet_add\":0,\"ripe_jop_sheet_edit\":0,\"ripe_jop_sheet_delete\":0,\"ripe_only_assinged_job_sheet\":0,\"ripe_view_all_job_sheet\":0}', '{\"superadmin_access_pack_subscrip\":0}', '{\"e_com_sync_pro_cate\":0,\"e_com_sync_pro\":0,\"e_com_sync_order\":0,\"e_com_map_tax_rate\":0}', '{\"today_summery\":0,\"communication\":0}', 0, '2022-01-04 12:15:23', '2022-01-05 08:55:44');

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
  `redeem_point` decimal(8,2) NOT NULL DEFAULT 0.00,
  `redeem_point_rate` decimal(8,2) NOT NULL DEFAULT 0.00,
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

INSERT INTO `sales` (`id`, `invoice_id`, `branch_id`, `customer_id`, `pay_term`, `pay_term_number`, `total_item`, `net_total_amount`, `order_discount_type`, `order_discount`, `order_discount_amount`, `redeem_point`, `redeem_point_rate`, `shipment_details`, `shipment_address`, `shipment_charge`, `shipment_status`, `delivered_to`, `sale_note`, `order_tax_percent`, `order_tax_amount`, `total_payable_amount`, `paid`, `change_amount`, `due`, `is_return_available`, `ex_status`, `sale_return_amount`, `sale_return_due`, `payment_note`, `admin_id`, `status`, `is_fixed_challen`, `date`, `time`, `report_date`, `month`, `year`, `attachment`, `created_by`, `created_at`, `updated_at`) VALUES
(582, '2021582', NULL, 91, NULL, NULL, 1, '19500.00', 1, '0.00', '0.00', '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '19500.00', '19500.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '26-10-2021', '11:29:02 am', '2021-10-25 18:00:00', 'October', '2021', NULL, 2, '2021-10-26 05:29:02', '2021-10-26 05:29:02'),
(592, '2021583', NULL, 91, NULL, NULL, 1, '600.00', 1, '0.00', '0.00', '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '600.00', '600.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '27-10-2021', '01:21:05 pm', '2021-10-26 18:00:00', 'October', '2021', NULL, 1, '2021-10-27 07:21:05', '2021-10-30 07:12:20'),
(593, '2021593', NULL, 91, NULL, NULL, 1, '200.00', 1, '0.00', '0.00', '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '200.00', '200.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '27-10-2021', '02:39:03 pm', '2021-10-26 18:00:00', 'October', '2021', NULL, 2, '2021-10-27 08:39:03', '2021-10-27 10:10:53'),
(594, '2021594', NULL, 91, NULL, NULL, 24, '130450.00', 1, '0.00', '0.00', '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '130450.00', '130450.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '27-10-2021', '04:10:53 pm', '2021-10-26 18:00:00', 'October', '2021', NULL, 1, '2021-10-27 10:10:53', '2021-11-11 10:42:20'),
(595, '2021595', NULL, NULL, NULL, NULL, 14, '412050.00', 1, '0.00', '0.00', '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '412050.00', '412050.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '27-10-2021', '05:50:11 pm', '2021-10-26 18:00:00', 'October', '2021', NULL, 1, '2021-10-27 11:50:11', '2021-11-11 10:28:42'),
(596, '2021596', NULL, 91, NULL, NULL, 2, '200.00', 1, '0.00', '0.00', '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '200.00', '200.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '30-10-2021', '11:34:34 am', '2021-10-29 18:00:00', 'October', '2021', NULL, 2, '2021-10-30 05:34:34', '2021-10-30 05:34:34'),
(597, '2021597', NULL, 91, NULL, NULL, 2, '200.00', 1, '0.00', '0.00', '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '200.00', '200.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '30-10-2021', '11:35:34 am', '2021-10-29 18:00:00', 'October', '2021', NULL, 2, '2021-10-30 05:35:34', '2021-10-30 05:35:34'),
(598, '2021598', NULL, NULL, NULL, NULL, 2, '200.00', 1, '0.00', '0.00', '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '200.00', '200.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '30-10-2021', '11:36:35 am', '2021-10-29 18:00:00', 'October', '2021', NULL, 2, '2021-10-30 05:36:35', '2021-10-30 05:36:35'),
(599, '2021599', NULL, 91, NULL, NULL, 2, '200.00', 1, '0.00', '0.00', '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '200.00', '200.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '30-10-2021', '11:37:20 am', '2021-10-29 18:00:00', 'October', '2021', NULL, 2, '2021-10-30 05:37:20', '2021-10-30 05:37:20'),
(600, '2021600', NULL, NULL, NULL, NULL, 2, '200.00', 1, '0.00', '0.00', '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '200.00', '200.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '30-10-2021', '11:38:44 am', '2021-10-29 18:00:00', 'October', '2021', NULL, 2, '2021-10-30 05:38:44', '2021-10-30 05:38:44'),
(601, '2021601', NULL, NULL, NULL, NULL, 2, '750.00', 1, '0.00', '0.00', '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '750.00', '750.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '30-10-2021', '11:50:20 am', '2021-10-29 18:00:00', 'October', '2021', NULL, 2, '2021-10-30 05:50:20', '2021-10-30 05:50:20'),
(602, '2021602', NULL, NULL, NULL, NULL, 2, '350.00', 1, '0.00', '0.00', '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '350.00', '350.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '30-10-2021', '11:50:53 am', '2021-10-29 18:00:00', 'October', '2021', NULL, 2, '2021-10-30 05:50:53', '2021-10-30 05:50:53'),
(603, '2021603', NULL, 91, NULL, NULL, 7, '107800.00', 1, '0.00', '0.00', '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '107800.00', '107800.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '30-10-2021', '11:51:30 am', '2021-10-29 18:00:00', 'October', '2021', NULL, 2, '2021-10-30 05:51:30', '2021-10-30 05:51:30'),
(604, '2021604', NULL, NULL, NULL, NULL, 2, '200.00', 1, '0.00', '0.00', '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '200.00', '200.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '30-10-2021', '11:59:37 am', '2021-10-29 18:00:00', 'October', '2021', NULL, 2, '2021-10-30 05:59:37', '2021-10-30 05:59:37'),
(605, '2021605', NULL, NULL, NULL, NULL, 2, '200.00', 1, '0.00', '0.00', '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '200.00', '200.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '30-10-2021', '12:00:24 pm', '2021-10-29 18:00:00', 'October', '2021', NULL, 2, '2021-10-30 06:00:24', '2021-10-30 06:00:24'),
(606, '2021606', NULL, NULL, NULL, NULL, 2, '10200.00', 1, '0.00', '0.00', '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '10200.00', '10200.00', '1800.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '08-11-2021', '05:03:30 pm', '2021-11-07 18:00:00', 'November', '2021', NULL, 2, '2021-11-08 11:03:30', '2021-11-08 11:03:30'),
(607, '2021607', NULL, 91, NULL, NULL, 1, '20000.00', 1, '0.00', '0.00', '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '20000.00', '20000.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '09-11-2021', '10:36:54 am', '2021-11-08 18:00:00', 'November', '2021', NULL, 1, '2021-11-09 04:36:54', '2021-11-11 10:28:32'),
(608, '2021608', NULL, 91, NULL, NULL, 1, '20000.00', 1, '0.00', '0.00', '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '20000.00', '20000.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '09-11-2021', '10:37:48 am', '2021-11-08 18:00:00', 'November', '2021', NULL, 1, '2021-11-09 04:37:48', '2021-11-09 04:41:35'),
(609, '2021609', NULL, 91, NULL, NULL, 1, '20000.00', 1, '0.00', '0.00', '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '20000.00', '20000.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '09-11-2021', '10:41:35 am', '2021-11-08 18:00:00', 'November', '2021', NULL, 2, '2021-11-09 04:41:35', '2021-11-09 04:41:35'),
(610, '2021610', NULL, 91, NULL, NULL, 1, '20000.00', 1, '0.00', '0.00', '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '20000.00', '20000.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '09-11-2021', '10:43:02 am', '2021-11-08 18:00:00', 'November', '2021', NULL, 2, '2021-11-09 04:43:02', '2021-11-09 04:43:02'),
(611, '2021611', NULL, NULL, NULL, NULL, 1, '20000.00', 1, '0.00', '0.00', '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '20000.00', '20000.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '09-11-2021', '10:44:10 am', '2021-11-08 18:00:00', 'November', '2021', NULL, 2, '2021-11-09 04:44:10', '2021-11-09 04:44:10'),
(612, '2021612', NULL, NULL, NULL, NULL, 1, '20000.00', 1, '0.00', '0.00', '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '20000.00', '20000.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '09-11-2021', '10:45:13 am', '2021-11-08 18:00:00', 'November', '2021', NULL, 2, '2021-11-09 04:45:13', '2021-11-09 04:45:13'),
(613, '2021613', NULL, NULL, NULL, NULL, 1, '20000.00', 1, '0.00', '0.00', '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '20000.00', '20000.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '09-11-2021', '10:46:16 am', '2021-11-08 18:00:00', 'November', '2021', NULL, 2, '2021-11-09 04:46:16', '2021-11-09 04:46:16'),
(614, '2021614', NULL, NULL, NULL, NULL, 1, '20000.00', 1, '0.00', '0.00', '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '20000.00', '20000.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '09-11-2021', '10:47:56 am', '2021-11-08 18:00:00', 'November', '2021', NULL, 2, '2021-11-09 04:47:56', '2021-11-09 04:47:56'),
(615, '2021615', NULL, NULL, NULL, NULL, 1, '20000.00', 1, '0.00', '0.00', '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '20000.00', '20000.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '09-11-2021', '10:48:59 am', '2021-11-08 18:00:00', 'November', '2021', NULL, 2, '2021-11-09 04:48:59', '2021-11-09 04:48:59'),
(616, '2021616', NULL, NULL, NULL, NULL, 1, '60000.00', 1, '0.00', '0.00', '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '60000.00', '60000.00', '60000.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '09-11-2021', '10:49:54 am', '2021-11-08 19:11:46', 'November', '2021', NULL, 2, '2021-11-09 04:49:54', '2021-11-09 07:46:53'),
(618, '2021618', NULL, NULL, NULL, NULL, 1, '20000.00', 1, '0.00', '0.00', '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '20000.00', '20000.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '10-11-2021', '12:30:52 pm', '2021-11-09 18:00:00', 'November', '2021', NULL, 1, '2021-11-10 06:30:52', '2021-11-10 06:30:52'),
(619, '2021619', NULL, NULL, NULL, NULL, 1, '20000.00', 1, '0.00', '0.00', '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '20000.00', '20000.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '10-11-2021', '12:39:33 pm', '2021-11-09 18:00:00', 'November', '2021', NULL, 1, '2021-11-10 06:39:33', '2021-11-10 06:39:33'),
(620, '2021620', NULL, 103, NULL, NULL, 1, '20000.00', 1, '0.00', '0.00', '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '20000.00', '20000.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '10-11-2021', '04:53:52 pm', '2021-11-09 18:00:00', 'November', '2021', NULL, 1, '2021-11-10 10:53:52', '2021-11-10 10:53:52'),
(621, '2021621', NULL, NULL, NULL, NULL, 1, '20000.00', 1, '0.00', '0.00', '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '20000.00', '20000.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '10-11-2021', '07:16:27 pm', '2021-11-09 18:00:00', 'November', '2021', NULL, 2, '2021-11-10 13:16:27', '2021-11-10 13:16:27'),
(622, '2021/622', NULL, NULL, NULL, NULL, 1, '33749.70', 1, '0.00', '0.00', '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '33749.70', '33749.70', '0.00', '0.00', 1, 0, '33749.70', '0.00', NULL, 2, 1, 1, '11-11-2021', '04:23:27 pm', '2021-11-10 18:00:00', 'November', '2021', NULL, 1, '2021-11-11 10:23:27', '2021-11-16 07:27:43'),
(623, '2021623', NULL, 91, NULL, NULL, 1, '600.00', 1, '0.00', '0.00', '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '600.00', '600.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '11-11-2021', '04:42:20 pm', '2021-11-10 18:00:00', 'November', '2021', NULL, 2, '2021-11-11 10:42:20', '2021-11-11 10:42:20'),
(624, '2021624', NULL, NULL, NULL, NULL, 1, '200.00', 1, '0.00', '0.00', '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '200.00', '200.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '11-11-2021', '05:02:54 pm', '2021-11-10 23:11:23', 'November', '2021', NULL, 2, '2021-11-11 11:02:54', '2021-11-11 11:23:17'),
(625, '2021625', NULL, 103, NULL, NULL, 1, '1687500.00', 1, '0.00', '0.00', '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '1687500.00', '1687500.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '11-11-2021', '06:56:32 pm', '2021-11-10 18:00:00', 'November', '2021', NULL, 1, '2021-11-11 12:56:32', '2021-11-20 05:34:47'),
(626, '2021626', NULL, 91, NULL, NULL, 1, '100.00', 1, '0.00', '0.00', '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '100.00', '0.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 5, 0, '13-11-2021', '02:35:17 pm', '2021-11-12 18:00:00', 'November', '2021', NULL, 2, '2021-11-13 08:35:17', '2021-11-13 08:35:17'),
(627, '2021627', NULL, NULL, NULL, NULL, 1, '150.00', 1, '0.00', '0.00', '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '150.00', '150.00', '50.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '17-11-2021', '10:40:55 am', '2021-11-16 18:00:00', 'November', '2021', NULL, 2, '2021-11-17 04:40:55', '2021-11-17 04:40:55'),
(628, '2021628', NULL, NULL, NULL, NULL, 1, '157.50', 1, '0.00', '0.00', '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '157.50', '157.50', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '20-11-2021', '10:56:38 am', '2021-11-20 04:11:48', 'November', '2021', NULL, 2, '2021-11-17 04:56:38', '2021-11-20 04:48:08'),
(629, '2021629', NULL, 106, NULL, NULL, 1, '30802.50', 1, '0.00', '0.00', '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '30802.50', '30802.50', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 0, '18-11-2021', '05:57:39 pm', '2021-11-17 18:00:00', 'November', '2021', NULL, 1, '2021-11-18 11:57:39', '2021-12-12 08:35:06'),
(630, '2021630', NULL, 106, NULL, NULL, 1, '100.00', 1, '0.00', '0.00', '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '100.00', '100.00', '100.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 0, '18-11-2021', '06:24:46 pm', '2021-11-18 00:11:25', 'November', '2021', NULL, 2, '2021-11-18 12:24:46', '2021-11-18 12:25:12'),
(631, '2021631', NULL, 106, NULL, NULL, 1, '0.00', 1, '0.00', '0.00', '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '20-11-2021', '10:47:13 am', '2021-11-19 18:00:00', 'November', '2021', NULL, 1, '2021-11-20 04:47:13', '2021-11-20 04:47:13'),
(632, '2021632', NULL, NULL, NULL, NULL, 3, '600.00', 1, '0.00', '0.00', '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '600.00', '600.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '20-11-2021', '11:19:11 am', '2021-11-19 18:00:00', 'November', '2021', NULL, 2, '2021-11-20 05:19:11', '2021-11-20 05:19:11'),
(633, '2021633', NULL, 106, NULL, NULL, 1, '61605.00', 1, '0.00', '0.00', '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '61605.00', '61605.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '12-12-2021', '02:35:06 pm', '2021-12-11 18:00:00', 'December', '2021', NULL, 1, '2021-12-12 08:35:06', '2021-12-12 08:35:06'),
(634, '2021634', NULL, 104, NULL, NULL, 1, '10100.00', 1, '0.00', '0.00', '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '10100.00', '0.00', '0.00', '10100.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '14-12-2021', '10:40:07 am', '2021-12-13 18:00:00', 'December', '2021', NULL, 1, '2021-12-14 04:40:07', '2021-12-14 04:40:07'),
(635, '2021635', NULL, NULL, NULL, NULL, 2, '200.00', 1, '0.00', '0.00', '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '200.00', '0.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 5, 0, '14-12-2021', '10:41:19 am', '2021-12-13 18:00:00', 'December', '2021', NULL, 2, '2021-12-14 04:41:19', '2021-12-14 04:41:19'),
(636, '2021636', NULL, 103, NULL, NULL, 1, '150.00', 1, '0.00', '0.00', '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '150.00', '0.00', '0.00', '150.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '14-12-2021', '10:45:07 am', '2021-12-13 18:00:00', 'December', '2021', NULL, 2, '2021-12-14 04:45:07', '2021-12-14 04:45:07'),
(637, '2021637', NULL, NULL, NULL, NULL, 1, '100.00', 1, '0.00', '0.00', '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '100.00', '100.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '19-12-2021', '02:08:44 pm', '2021-12-18 18:00:00', 'December', '2021', NULL, 2, '2021-12-19 08:08:44', '2021-12-19 08:08:44'),
(638, '2021638', NULL, NULL, NULL, NULL, 6, '800.00', 1, '0.00', '0.00', '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '800.00', '800.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '28-12-2021', '12:11:07 pm', '2021-12-27 18:00:00', 'December', '2021', NULL, 2, '2021-12-28 06:11:07', '2021-12-28 06:11:07'),
(639, '2021639', NULL, NULL, NULL, NULL, 2, '100.00', 1, '0.00', '0.00', '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '100.00', '120.00', '20.00', '-20.00', 0, 1, '0.00', '20.00', NULL, 2, 1, 1, '28-12-2021', '12:13:47 pm', '2021-12-27 18:00:00', 'December', '2021', NULL, 2, '2021-12-28 06:13:47', '2022-01-04 12:08:43'),
(640, '2021640', NULL, 91, NULL, NULL, 2, '900.00', 1, '0.00', '0.00', '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '900.00', '347.50', '50.00', '552.50', 0, 1, '0.00', '0.00', NULL, 2, 1, 1, '28-12-2021', '12:19:26 pm', '2021-12-27 18:00:00', 'December', '2021', NULL, 2, '2021-12-28 06:19:26', '2022-01-02 11:26:08'),
(641, '2021641', NULL, NULL, NULL, NULL, 2, '-400.00', 1, '50.00', '50.00', '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '-450.00', '750.00', '1200.00', '2230.00', 0, 1, '0.00', '0.00', NULL, 2, 1, 1, '28-12-2021', '12:27:58 pm', '2021-12-27 18:00:00', 'December', '2021', NULL, 2, '2021-12-28 06:27:58', '2022-01-04 12:08:31'),
(642, '2021642', NULL, NULL, NULL, NULL, 3, '600.00', 1, '30.00', '30.00', '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '570.00', '570.00', '200.00', '0.00', 0, 1, '0.00', '0.00', NULL, 2, 1, 1, '28-12-2021', '12:33:23 pm', '2021-12-27 18:00:00', 'December', '2021', NULL, 2, '2021-12-28 06:33:23', '2022-01-02 07:14:29'),
(643, '2021643', NULL, NULL, NULL, NULL, 2, '0.00', 1, '10.00', '10.00', '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '-10.00', '-10.00', '210.00', '0.00', 0, 1, '0.00', '0.00', NULL, 2, 1, 1, '28-12-2021', '12:55:07 pm', '2021-12-27 18:00:00', 'December', '2021', NULL, 2, '2021-12-28 06:55:07', '2022-01-02 06:13:32'),
(644, '2022644', NULL, 114, NULL, NULL, 2, '720.00', 1, '0.00', '0.00', '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '720.00', '720.00', '80.00', '0.00', 0, 1, '0.00', '0.00', NULL, 2, 1, 1, '02-01-2022', '02:10:54 pm', '2022-01-01 18:00:00', 'January', '2022', NULL, 2, '2022-01-02 08:10:54', '2022-01-06 06:19:04'),
(645, '2022645', NULL, NULL, NULL, NULL, 1, '10.00', 1, '0.00', '0.00', '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '10.00', '10.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '02-01-2022', '02:22:35 pm', '2022-01-01 18:00:00', 'January', '2022', NULL, 2, '2022-01-02 08:22:35', '2022-01-02 08:22:35'),
(646, '2022646', NULL, NULL, NULL, NULL, 2, '336.00', 1, '0.00', '0.00', '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '336.00', '336.00', '54.00', '0.00', 0, 1, '0.00', '0.00', NULL, 2, 1, 1, '02-01-2022', '02:43:09 pm', '2022-01-01 18:00:00', 'January', '2022', NULL, 2, '2022-01-02 08:43:09', '2022-01-02 08:43:55'),
(647, '2022647', NULL, NULL, NULL, NULL, 3, '136.00', 1, '0.00', '0.00', '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '136.00', '136.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '02-01-2022', '04:24:47 pm', '2022-01-01 18:00:00', 'January', '2022', NULL, 2, '2022-01-02 10:24:47', '2022-01-02 10:24:47'),
(648, '2022648', NULL, 91, NULL, NULL, 1, '29000.00', 1, '0.00', '0.00', '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '29000.00', '30802.50', '1802.50', '-1802.50', 0, 1, '0.00', '1802.50', NULL, 2, 1, 1, '02-01-2022', '05:26:08 pm', '2022-01-01 18:00:00', 'January', '2022', NULL, 2, '2022-01-02 11:26:08', '2022-01-02 11:26:55'),
(649, '2022649', NULL, NULL, NULL, NULL, 1, '30802.50', 1, '0.00', '0.00', '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '30802.50', '30802.50', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '02-01-2022', '05:31:59 pm', '2022-01-01 18:00:00', 'January', '2022', NULL, 2, '2022-01-02 11:31:59', '2022-01-02 11:31:59'),
(650, '2022650', NULL, NULL, NULL, NULL, 1, '29000.00', 1, '0.00', '0.00', '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '29000.00', '30802.50', '1802.50', '-1802.50', 0, 1, '0.00', '1802.50', NULL, 2, 1, 1, '02-01-2022', '05:39:38 pm', '2022-01-01 18:00:00', 'January', '2022', NULL, 2, '2022-01-02 11:39:38', '2022-01-02 11:54:01'),
(651, '2022651', NULL, NULL, NULL, NULL, 1, '61605.00', 1, '0.00', '0.00', '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '61605.00', '61605.00', '0.00', '0.00', 0, 1, '0.00', '0.00', NULL, 2, 1, 1, '02-01-2022', '05:58:13 pm', '2022-01-02 00:01:10', 'January', '2022', NULL, 2, '2022-01-02 11:58:13', '2022-01-03 04:50:52'),
(652, '2022652', NULL, NULL, NULL, NULL, 1, '50.00', 1, '0.00', '0.00', '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '50.00', '50.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '05-01-2022', '10:53:17 am', '2022-01-05 05:01:02', 'January', '2022', NULL, 2, '2022-01-03 04:53:17', '2022-01-05 05:02:32'),
(653, '2022653', NULL, NULL, NULL, NULL, 1, '136.00', 1, '0.00', '0.00', '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '136.00', '136.00', '14.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '03-01-2022', '11:35:58 am', '2022-01-02 18:00:00', 'January', '2022', NULL, 2, '2022-01-03 05:35:58', '2022-01-03 05:35:58'),
(654, '2022654', NULL, NULL, NULL, NULL, 1, '136.00', 1, '0.00', '0.00', '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '136.00', '136.00', '14.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '03-01-2022', '11:37:21 am', '2022-01-02 18:00:00', 'January', '2022', NULL, 2, '2022-01-03 05:37:21', '2022-01-03 05:37:21'),
(655, '2022655', NULL, 102, NULL, NULL, 2, '200.00', 1, '0.00', '0.00', '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '200.00', '200.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '03-01-2022', '01:28:22 pm', '2022-01-02 18:00:00', 'January', '2022', NULL, 2, '2022-01-03 07:28:22', '2022-01-03 07:28:22'),
(656, '2022656', NULL, NULL, NULL, NULL, 3, '300.00', 1, '0.00', '0.00', '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '300.00', '300.00', '700.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '03-01-2022', '01:28:46 pm', '2022-01-02 18:00:00', 'January', '2022', NULL, 2, '2022-01-03 07:28:46', '2022-01-03 07:28:46'),
(657, '2022657', NULL, NULL, NULL, NULL, 2, '200.00', 1, '0.00', '0.00', '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '200.00', '200.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '03-01-2022', '01:37:47 pm', '2022-01-02 18:00:00', 'January', '2022', NULL, 2, '2022-01-03 07:37:47', '2022-01-03 07:37:47'),
(658, '2022658', NULL, NULL, NULL, NULL, 10, '640.00', 1, '0.00', '0.00', '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '640.00', '640.00', '0.00', '0.00', 0, 1, '0.00', '0.00', NULL, 2, 1, 1, '05-01-2022', '11:30:18 am', '2022-01-04 18:00:00', 'January', '2022', NULL, 2, '2022-01-05 05:30:18', '2022-01-05 05:31:30'),
(659, '2022659', NULL, NULL, NULL, NULL, 2, '200.00', 1, '0.00', '0.00', '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '200.00', '200.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 18, 1, 1, '05-01-2022', '02:47:33 pm', '2022-01-04 18:00:00', 'January', '2022', NULL, 2, '2022-01-05 08:47:33', '2022-01-05 08:47:33'),
(660, '2022660', NULL, NULL, NULL, NULL, 2, '200.00', 1, '0.00', '0.00', '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '200.00', '200.00', '100.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '05-01-2022', '04:00:04 pm', '2022-01-04 18:00:00', 'January', '2022', NULL, 2, '2022-01-05 10:00:04', '2022-01-05 10:00:04'),
(661, '2022661', NULL, NULL, NULL, NULL, 1, '100.00', 1, '0.00', '0.00', '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '100.00', '100.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '05-01-2022', '05:22:12 pm', '2022-01-04 18:00:00', 'January', '2022', NULL, 2, '2022-01-05 11:22:12', '2022-01-05 11:22:12'),
(662, '2022662', NULL, NULL, NULL, NULL, 1, '100.00', 1, '0.00', '0.00', '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '100.00', '100.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '05-01-2022', '05:22:38 pm', '2022-01-04 18:00:00', 'January', '2022', NULL, 2, '2022-01-05 11:22:38', '2022-01-05 11:22:38'),
(663, '2022663', NULL, NULL, NULL, NULL, 1, '100.00', 1, '0.00', '0.00', '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '100.00', '100.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '05-01-2022', '05:31:30 pm', '2022-01-04 18:00:00', 'January', '2022', NULL, 2, '2022-01-05 11:31:30', '2022-01-05 11:31:30'),
(664, '2022664', NULL, NULL, NULL, NULL, 1, '100.00', 1, '0.00', '0.00', '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '100.00', '100.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '05-01-2022', '05:34:21 pm', '2022-01-04 18:00:00', 'January', '2022', NULL, 2, '2022-01-05 11:34:21', '2022-01-05 11:34:21'),
(665, '2022665', NULL, NULL, NULL, NULL, 1, '30802.50', 1, '0.00', '0.00', '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '30802.50', '30802.50', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '05-01-2022', '05:36:36 pm', '2022-01-04 18:00:00', 'January', '2022', NULL, 1, '2022-01-05 11:36:36', '2022-01-05 11:36:36'),
(666, '2022666', NULL, NULL, NULL, NULL, 1, '20000.00', 1, '0.00', '0.00', '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '20000.00', '20000.00', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '05-01-2022', '05:38:26 pm', '2022-01-04 18:00:00', 'January', '2022', NULL, 1, '2022-01-05 11:38:26', '2022-01-05 11:38:26'),
(667, '2022667', NULL, 114, NULL, NULL, 1, '30802.50', 1, '0.00', '0.00', '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '30802.50', '30802.50', '0.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '06-01-2022', '12:19:04 pm', '2022-01-05 18:00:00', 'January', '2022', NULL, 1, '2022-01-06 06:19:04', '2022-01-06 06:19:04'),
(668, '2022668', NULL, NULL, NULL, NULL, 8, '904.00', 1, '0.00', '0.00', '0.00', '0.00', NULL, NULL, '0.00', NULL, NULL, NULL, '0.00', '0.00', '904.00', '904.00', '96.00', '0.00', 0, 0, '0.00', '0.00', NULL, 2, 1, 1, '06-01-2022', '05:06:27 pm', '2022-01-05 18:00:00', 'January', '2022', NULL, 2, '2022-01-06 11:06:27', '2022-01-06 11:06:27');

-- --------------------------------------------------------

--
-- Table structure for table `sale_payments`
--

CREATE TABLE `sale_payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sale_id` bigint(20) UNSIGNED DEFAULT NULL,
  `customer_payment_id` bigint(20) UNSIGNED DEFAULT NULL,
  `customer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `pay_mode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_method_id` bigint(20) UNSIGNED DEFAULT NULL,
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

INSERT INTO `sale_payments` (`id`, `invoice_id`, `sale_id`, `customer_payment_id`, `customer_id`, `account_id`, `pay_mode`, `payment_method_id`, `paid_amount`, `payment_on`, `payment_type`, `payment_status`, `date`, `time`, `month`, `year`, `report_date`, `card_no`, `card_holder`, `card_type`, `card_transaction_no`, `card_month`, `card_year`, `card_secure_code`, `account_no`, `cheque_no`, `transaction_no`, `attachment`, `note`, `admin_id`, `created_at`, `updated_at`) VALUES
(534, 'SPV1021534', 582, NULL, 91, NULL, 'Cash', NULL, '19500.00', 1, 1, NULL, '26-10-2021', '11:29:02 am', 'October', '2021', '2021-10-25 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-10-26 05:29:02', '2021-10-26 05:29:02'),
(536, 'SPV211027535', 592, NULL, 91, 28, 'Cash', NULL, '600.00', 1, 1, NULL, '27-10-2021', '01:48:30 pm', 'October', '2021', '2021-10-26 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-10-27 07:48:30', '2021-10-27 07:48:30'),
(537, 'SPV211027594', 594, NULL, 91, NULL, 'Cash', NULL, '129150.00', 1, 1, NULL, '27-10-2021', '04:10:53 pm', 'October', '2021', '2021-10-26 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-10-27 10:10:53', '2021-10-27 10:10:53'),
(538, 'SPV211027538', 593, NULL, 91, NULL, 'Cash', NULL, '200.00', 1, 1, NULL, '27-10-2021', '04:10:53 pm', 'October', '2021', '2021-10-26 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-10-27 10:10:53', '2021-10-27 10:10:53'),
(539, 'SPV211027595', 595, NULL, NULL, NULL, 'Cash', NULL, '391750.00', 1, 1, NULL, '27-10-2021', '05:50:11 pm', 'October', '2021', '2021-10-26 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-10-27 11:50:11', '2021-10-27 11:50:11'),
(540, 'SPV1021540', 596, NULL, 91, NULL, 'Cash', NULL, '200.00', 1, 1, NULL, '30-10-2021', '11:34:34 am', 'October', '2021', '2021-10-29 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-10-30 05:34:34', '2021-10-30 05:34:34'),
(541, 'SPV211030541', 594, NULL, 91, NULL, 'Cash', NULL, '1100.00', 2, 1, NULL, '30-10-2021', '11:34:34 am', 'October', '2021', '2021-10-29 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-10-30 05:34:34', '2021-10-30 05:34:34'),
(542, 'SPV211030542', 597, NULL, 91, NULL, 'Cash', NULL, '200.00', 1, 1, NULL, '30-10-2021', '11:35:34 am', 'October', '2021', '2021-10-29 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-10-30 05:35:34', '2021-10-30 05:35:34'),
(543, 'SPV211030543', 598, NULL, NULL, NULL, 'Cash', NULL, '200.00', 1, 1, NULL, '30-10-2021', '11:36:35 am', 'October', '2021', '2021-10-29 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-10-30 05:36:35', '2021-10-30 05:36:35'),
(544, 'SPV211030544', 599, NULL, 91, NULL, 'Cash', NULL, '200.00', 1, 1, NULL, '30-10-2021', '11:37:21 am', 'October', '2021', '2021-10-29 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-10-30 05:37:21', '2021-10-30 05:37:21'),
(545, 'SPV211030545', 600, NULL, NULL, NULL, 'Cash', NULL, '200.00', 1, 1, NULL, '30-10-2021', '11:38:44 am', 'October', '2021', '2021-10-29 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-10-30 05:38:44', '2021-10-30 05:38:44'),
(546, 'SPV211030546', 601, NULL, NULL, NULL, 'Cash', NULL, '750.00', 1, 1, NULL, '30-10-2021', '11:50:20 am', 'October', '2021', '2021-10-29 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-10-30 05:50:20', '2021-10-30 05:50:20'),
(547, 'SPV211030547', 602, NULL, NULL, NULL, 'Cash', NULL, '350.00', 1, 1, NULL, '30-10-2021', '11:50:53 am', 'October', '2021', '2021-10-29 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-10-30 05:50:53', '2021-10-30 05:50:53'),
(548, 'SPV211030548', 603, NULL, 91, NULL, 'Cash', NULL, '107800.00', 1, 1, NULL, '30-10-2021', '11:51:31 am', 'October', '2021', '2021-10-29 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-10-30 05:51:31', '2021-10-30 05:51:31'),
(549, 'SPV211030549', 604, NULL, NULL, NULL, 'Cash', NULL, '200.00', 1, 1, NULL, '30-10-2021', '11:59:37 am', 'October', '2021', '2021-10-29 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-10-30 05:59:37', '2021-10-30 05:59:37'),
(550, 'SPV211030550', 605, NULL, NULL, NULL, 'Cash', NULL, '200.00', 1, 1, NULL, '30-10-2021', '12:00:24 pm', 'October', '2021', '2021-10-29 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-10-30 06:00:24', '2021-10-30 06:00:24'),
(551, 'SPV211108551', 595, NULL, NULL, 28, 'Cash', NULL, '20300.00', 1, 1, NULL, '08-11-2021', '04:58:41 pm', 'November', '2021', '2021-11-07 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-11-08 10:58:41', '2021-11-08 10:58:41'),
(552, 'SPV211108552', 606, NULL, NULL, NULL, 'Cash', NULL, '12000.00', 1, 1, NULL, '08-11-2021', '05:03:30 pm', 'November', '2021', '2021-11-07 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-11-08 11:03:30', '2021-11-08 11:03:30'),
(553, 'SPV1121553', 609, NULL, 91, NULL, 'Cash', NULL, '20000.00', 1, 1, NULL, '09-11-2021', '10:41:35 am', 'November', '2021', '2021-11-08 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-11-09 04:41:35', '2021-11-09 04:41:35'),
(554, 'SPV211109554', 607, NULL, 91, NULL, 'Cash', NULL, '20000.00', 2, 1, NULL, '09-11-2021', '10:41:35 am', 'November', '2021', '2021-11-08 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-11-09 04:41:35', '2021-11-09 04:41:35'),
(555, 'SPV211109555', 608, NULL, 91, NULL, 'Cash', NULL, '20000.00', 2, 1, NULL, '09-11-2021', '10:41:35 am', 'November', '2021', '2021-11-08 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-11-09 04:41:35', '2021-11-09 04:41:35'),
(556, 'SPV211109556', 610, NULL, 91, NULL, 'Cash', NULL, '20000.00', 1, 1, NULL, '09-11-2021', '10:43:02 am', 'November', '2021', '2021-11-08 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-11-09 04:43:02', '2021-11-09 04:43:02'),
(557, 'SPV211109557', 611, NULL, NULL, NULL, 'Cash', NULL, '20000.00', 1, 1, NULL, '09-11-2021', '10:44:10 am', 'November', '2021', '2021-11-08 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-11-09 04:44:10', '2021-11-09 04:44:10'),
(558, 'SPV211109558', 612, NULL, NULL, NULL, 'Cash', NULL, '20000.00', 1, 1, NULL, '09-11-2021', '10:45:13 am', 'November', '2021', '2021-11-08 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-11-09 04:45:13', '2021-11-09 04:45:13'),
(559, 'SPV211109559', 613, NULL, NULL, NULL, 'Cash', NULL, '20000.00', 1, 1, NULL, '09-11-2021', '10:46:16 am', 'November', '2021', '2021-11-08 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-11-09 04:46:17', '2021-11-09 04:46:17'),
(560, 'SPV211109560', 614, NULL, NULL, NULL, 'Cash', NULL, '20000.00', 1, 1, NULL, '09-11-2021', '10:47:56 am', 'November', '2021', '2021-11-08 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-11-09 04:47:56', '2021-11-09 04:47:56'),
(561, 'SPV211109561', 615, NULL, NULL, NULL, 'Cash', NULL, '20000.00', 1, 1, NULL, '09-11-2021', '10:48:59 am', 'November', '2021', '2021-11-08 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-11-09 04:48:59', '2021-11-09 04:48:59'),
(563, 'SPV1121562', 616, NULL, NULL, NULL, 'Cash', NULL, '60000.00', 1, 1, NULL, '09-11-2021', '01:46:53', 'November', '2021', '2021-11-08 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-11-09 07:46:53', '2021-11-09 07:46:53'),
(564, 'SPV1121564', 619, NULL, NULL, NULL, 'Cash', NULL, '20000.00', 1, 1, NULL, '10-11-2021', '12:39:33 pm', 'November', '2021', '2021-11-09 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-11-10 06:39:33', '2021-11-10 06:39:33'),
(565, 'SPV1121565', 620, NULL, 103, NULL, 'Cash', NULL, '20000.00', 1, 1, NULL, '10-11-2021', '04:53:52 pm', 'November', '2021', '2021-11-09 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-11-10 10:53:52', '2021-11-10 10:53:52'),
(566, 'SPV211110566', 621, NULL, NULL, NULL, 'Cash', NULL, '20000.00', 1, 1, NULL, '10-11-2021', '07:16:27 pm', 'November', '2021', '2021-11-09 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-11-10 13:16:27', '2021-11-10 13:16:27'),
(567, 'SPV1121567', 622, NULL, NULL, 28, 'Cash', NULL, '33749.70', 1, 1, NULL, '11-11-2021', '04:23:27 pm', 'November', '2021', '2021-11-10 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-11-11 10:23:27', '2021-11-16 07:27:43'),
(568, 'SPV1121568', 623, NULL, 91, NULL, 'Cash', NULL, '600.00', 1, 1, NULL, '11-11-2021', '04:42:20 pm', 'November', '2021', '2021-11-10 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-11-11 10:42:20', '2021-11-11 10:42:20'),
(569, 'SPV211111569', 594, NULL, 91, NULL, 'Cash', NULL, '200.00', 2, 1, NULL, '11-11-2021', '04:42:20 pm', 'November', '2021', '2021-11-10 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-11-11 10:42:20', '2021-11-11 10:42:20'),
(571, 'SPV1121570', 624, NULL, NULL, NULL, 'Cash', NULL, '200.00', 1, 1, NULL, '11-11-2021', '05:23:18', 'November', '2021', '2021-11-10 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-11-11 11:23:18', '2021-11-11 11:23:18'),
(572, 'SRPV1121572', 622, NULL, NULL, 28, 'Cash', NULL, '33749.70', 1, 2, NULL, '13-11-2021', '11:21:08 am', 'November', '2021', '2021-11-12 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-11-13 05:21:08', '2021-11-13 05:21:08'),
(573, 'SPV211117573', 627, NULL, NULL, NULL, 'Cash', NULL, '200.00', 1, 1, NULL, '17-11-2021', '10:40:55 am', 'November', '2021', '2021-11-16 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-11-17 04:40:55', '2021-11-17 04:40:55'),
(575, 'SPV1121575', 630, NULL, 106, NULL, 'Cash', NULL, '100.00', 1, 1, NULL, '18-11-2021', '06:25:12', 'November', '2021', '2021-11-17 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-11-18 12:25:12', '2021-11-18 12:25:12'),
(576, 'SPV1121576', 628, NULL, NULL, NULL, 'Cash', NULL, '157.50', 1, 1, NULL, '20-11-2021', '10:48:08', 'November', '2021', '2021-11-19 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-11-20 04:48:08', '2021-11-20 04:48:08'),
(577, 'SPV211120577', 632, NULL, NULL, NULL, 'Cash', NULL, '600.00', 1, 1, NULL, '20-11-2021', '11:19:11 am', 'November', '2021', '2021-11-19 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-11-20 05:19:11', '2021-11-20 05:19:11'),
(578, 'SPV1121578', 625, 59, 103, 28, 'Cash', NULL, '1687500.00', 1, 1, NULL, '20-11-2021', '11:34:47 am', 'November', '2021', '2021-11-19 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-11-20 05:34:47', '2021-11-20 05:34:47'),
(579, 'SPV1221579', 633, NULL, 106, NULL, 'Cash', NULL, '61605.00', 1, 1, NULL, '12-12-2021', '02:35:06 pm', 'December', '2021', '2021-12-11 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-12-12 08:35:06', '2021-12-12 08:35:06'),
(580, 'SPV1221580', 629, NULL, 106, NULL, 'Cash', NULL, '30802.50', 1, 1, NULL, '12-12-2021', '02:35:06 pm', 'December', '2021', '2021-12-11 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-12-12 08:35:06', '2021-12-12 08:35:06'),
(581, 'SPV211219581', 637, NULL, NULL, 28, 'Cash', NULL, '100.00', 1, 1, NULL, '19-12-2021', '02:08:44 pm', 'December', '2021', '2021-12-18 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-12-19 08:08:44', '2021-12-19 08:08:44'),
(582, 'SPV211228582', 638, NULL, NULL, 28, 'Cash', NULL, '800.00', 1, 1, NULL, '28-12-2021', '12:11:07 pm', 'December', '2021', '2021-12-27 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-12-28 06:11:07', '2021-12-28 06:11:07'),
(583, 'SPV211228583', 639, NULL, NULL, 28, 'Cash', NULL, '120.00', 1, 1, NULL, '28-12-2021', '12:13:47 pm', 'December', '2021', '2021-12-27 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-12-28 06:13:47', '2021-12-28 06:13:47'),
(584, 'SPV211228584', 641, NULL, NULL, 28, 'Cash', NULL, '750.00', 1, 1, NULL, '28-12-2021', '12:27:58 pm', 'December', '2021', '2021-12-27 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-12-28 06:27:58', '2021-12-28 06:27:58'),
(585, 'SPV211228585', 642, NULL, NULL, 28, 'Cash', NULL, '770.00', 1, 1, NULL, '28-12-2021', '12:33:23 pm', 'December', '2021', '2021-12-27 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-12-28 06:33:23', '2021-12-28 06:33:23'),
(586, 'SPV211228586', 643, NULL, NULL, 28, 'Cash', NULL, '190.00', 1, 1, NULL, '28-12-2021', '12:55:07 pm', 'December', '2021', '2021-12-27 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2021-12-28 06:55:07', '2021-12-28 06:55:07'),
(587, 'SPV220102587', 644, NULL, 114, 28, 'Cash', NULL, '400.00', 1, 1, NULL, '02-01-2022', '02:10:54 pm', 'January', '2022', '2022-01-01 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2022-01-02 08:10:54', '2022-01-02 08:10:54'),
(588, 'SPV220102588', 645, NULL, NULL, 28, 'Cash', NULL, '10.00', 1, 1, NULL, '02-01-2022', '02:22:35 pm', 'January', '2022', '2022-01-01 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2022-01-02 08:22:35', '2022-01-02 08:22:35'),
(589, 'SPV220102589', 646, NULL, NULL, 28, 'Cash', NULL, '146.00', 1, 1, NULL, '02-01-2022', '02:43:09 pm', 'January', '2022', '2022-01-01 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2022-01-02 08:43:09', '2022-01-02 08:43:09'),
(590, 'SPV012298888', 646, NULL, NULL, 28, 'Cash', NULL, '190.00', 1, 1, NULL, '02-01-2022', '02:43:55', 'January', '2022', '2022-01-01 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2022-01-02 08:43:55', '2022-01-02 08:43:55'),
(591, 'SPV012269429', 640, NULL, 91, 28, 'Cash', NULL, '150.00', 1, 1, NULL, '02-01-2022', '02:51:40', 'January', '2022', '2022-01-01 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2022-01-02 08:51:40', '2022-01-02 08:51:40'),
(592, 'SPV220102592', 647, NULL, NULL, 28, 'Cash', NULL, '136.00', 1, 1, NULL, '02-01-2022', '04:24:47 pm', 'January', '2022', '2022-01-01 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2022-01-02 10:24:47', '2022-01-02 10:24:47'),
(593, 'SPV0122593', 648, NULL, 91, 28, 'Cash', NULL, '30802.50', 1, 1, NULL, '02-01-2022', '05:26:08 pm', 'January', '2022', '2022-01-01 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2022-01-02 11:26:08', '2022-01-02 11:26:08'),
(594, 'SPV220102594', 640, NULL, 91, 28, 'Cash', NULL, '197.50', 2, 1, NULL, '02-01-2022', '05:26:08 pm', 'January', '2022', '2022-01-01 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2022-01-02 11:26:08', '2022-01-02 11:26:08'),
(595, 'SPV220102595', 649, NULL, NULL, 28, 'Cash', NULL, '30802.50', 1, 1, NULL, '02-01-2022', '05:32:00 pm', 'January', '2022', '2022-01-01 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2022-01-02 11:32:00', '2022-01-02 11:32:00'),
(596, 'SPV220102596', 650, NULL, NULL, 28, 'Cash', NULL, '30802.50', 1, 1, NULL, '02-01-2022', '05:39:38 pm', 'January', '2022', '2022-01-01 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2022-01-02 11:39:38', '2022-01-02 11:39:38'),
(598, 'SPV0122597', 651, NULL, NULL, NULL, 'Cash', NULL, '30802.50', 1, 1, NULL, '02-01-2022', '06:10:04', 'January', '2022', '2022-01-01 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2022-01-02 12:10:04', '2022-01-02 12:10:04'),
(599, 'SPV012288917', 651, NULL, NULL, 28, 'Cash', NULL, '30802.50', 1, 1, NULL, '03-01-2022', '10:50:52', 'January', '2022', '2022-01-02 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2022-01-03 04:50:52', '2022-01-03 04:50:52'),
(601, 'SPV220103601', 653, NULL, NULL, 28, 'Cash', NULL, '136.00', 1, 1, NULL, '03-01-2022', '11:35:58 am', 'January', '2022', '2022-01-02 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2022-01-03 05:35:58', '2022-01-03 05:35:58'),
(602, 'SPV220103602', 654, NULL, NULL, 28, 'Cash', NULL, '136.00', 1, 1, NULL, '03-01-2022', '11:37:21 am', 'January', '2022', '2022-01-02 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2022-01-03 05:37:21', '2022-01-03 05:37:21'),
(603, 'SPV220103603', 655, NULL, 102, 28, 'Cash', NULL, '200.00', 1, 1, NULL, '03-01-2022', '01:28:22 pm', 'January', '2022', '2022-01-02 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2022-01-03 07:28:22', '2022-01-03 07:28:22'),
(604, 'SPV220103604', 656, NULL, NULL, 28, 'Cash', NULL, '300.00', 1, 1, NULL, '03-01-2022', '01:28:46 pm', 'January', '2022', '2022-01-02 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2022-01-03 07:28:46', '2022-01-03 07:28:46'),
(605, 'SPV220103605', 657, NULL, NULL, 28, 'Cash', NULL, '200.00', 1, 1, NULL, '03-01-2022', '01:37:47 pm', 'January', '2022', '2022-01-02 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2022-01-03 07:37:47', '2022-01-03 07:37:47'),
(610, 'RPV0122610', 641, 62, 91, 28, 'Cash', NULL, '1150.00', 1, 2, NULL, '04-01-22', '06:08:31 pm', 'January', '2022', '2022-01-03 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2022-01-04 12:08:31', '2022-01-04 12:08:31'),
(611, 'SPV0122611', 652, NULL, NULL, NULL, 'Cash', NULL, '50.00', 1, 1, NULL, '05-01-2022', '11:02:32', 'January', '2022', '2022-01-04 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2022-01-05 05:02:32', '2022-01-05 05:02:32'),
(612, 'SPV220105612', 658, NULL, NULL, 28, 'Cash', NULL, '640.00', 1, 1, NULL, '05-01-2022', '11:30:19 am', 'January', '2022', '2022-01-04 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2022-01-05 05:30:19', '2022-01-05 05:30:19'),
(613, 'SPV220105613', 659, NULL, NULL, NULL, 'Cash', NULL, '200.00', 1, 1, NULL, '05-01-2022', '02:47:33 pm', 'January', '2022', '2022-01-04 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 18, '2022-01-05 08:47:33', '2022-01-05 08:47:33'),
(614, 'SPV220105614', 660, NULL, NULL, 28, 'Cash', NULL, '200.00', 1, 1, NULL, '05-01-2022', '04:00:04 pm', 'January', '2022', '2022-01-04 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2022-01-05 10:00:04', '2022-01-05 10:00:04'),
(615, 'SPV220105615', 664, NULL, NULL, 28, 'Cash', NULL, '100.00', 1, 1, NULL, '05-01-2022', '05:34:21 pm', 'January', '2022', '2022-01-04 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2022-01-05 11:34:21', '2022-01-05 11:34:21'),
(616, 'SPV0122616', 665, NULL, NULL, NULL, 'Cash', NULL, '30802.50', 1, 1, NULL, '05-01-2022', '05:36:36 pm', 'January', '2022', '2022-01-04 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2022-01-05 11:36:36', '2022-01-05 11:36:36'),
(617, 'SPV0122617', 666, NULL, NULL, NULL, 'Cash', NULL, '20000.00', 1, 1, NULL, '05-01-2022', '05:38:26 pm', 'January', '2022', '2022-01-04 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2022-01-05 11:38:26', '2022-01-05 11:38:26'),
(618, 'SPV0122618', 667, NULL, 114, NULL, NULL, NULL, '30802.50', 1, 1, NULL, '06-01-2022', '12:19:04 pm', 'January', '2022', '2022-01-05 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2022-01-06 06:19:04', '2022-01-06 06:19:04'),
(619, 'SPV0122619', 644, NULL, 114, NULL, NULL, NULL, '320.00', 1, 1, NULL, '06-01-2022', '12:19:04 pm', 'January', '2022', '2022-01-05 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2022-01-06 06:19:04', '2022-01-06 06:19:04'),
(620, 'SPV220106620', 668, NULL, NULL, 28, 'Cash', NULL, '904.00', 1, 1, NULL, '06-01-2022', '05:06:27 pm', 'January', '2022', '2022-01-05 18:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '2022-01-06 11:06:27', '2022-01-06 11:06:27');

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
(996, 592, 274, NULL, '3.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '0.00', '200.00', '200.00', '600.00', NULL, '0.00', 0, 0, '2021-10-27 07:21:05', '2021-10-30 07:12:20'),
(997, 593, 274, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '0.00', '200.00', '200.00', '200.00', NULL, '0.00', 0, 0, '2021-10-27 08:39:03', '2021-10-27 08:39:03'),
(998, 594, 284, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '0.00', '250.00', '250.00', '250.00', NULL, '0.00', 0, 0, '2021-10-27 10:10:53', '2021-11-10 10:34:42'),
(999, 594, 280, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '80000.00', '82000.00', '82000.00', '82000.00', NULL, '0.00', 0, 0, '2021-10-27 10:10:53', '2021-11-10 10:34:42'),
(1000, 594, 274, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '150.00', '300.00', '300.00', '300.00', NULL, '0.00', 0, 0, '2021-10-27 10:10:53', '2021-11-10 10:34:42'),
(1001, 594, 277, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '18000.00', '20000.00', '20000.00', '20000.00', NULL, '0.00', 0, 0, '2021-10-27 10:10:53', '2021-11-10 10:34:42'),
(1002, 594, 281, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '18500.00', '20000.00', '20000.00', '20000.00', NULL, '0.00', 0, 0, '2021-10-27 10:10:53', '2021-11-10 10:34:42'),
(1003, 594, 276, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '0.00', '500.00', '500.00', '500.00', NULL, '0.00', 0, 0, '2021-10-27 10:10:53', '2021-11-10 10:34:42'),
(1004, 594, 282, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '250.00', '350.00', '350.00', '350.00', NULL, '0.00', 0, 0, '2021-10-27 10:10:53', '2021-11-10 10:34:42'),
(1005, 594, 275, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '0.00', '500.00', '500.00', '500.00', NULL, '0.00', 0, 0, '2021-10-27 10:10:53', '2021-11-10 10:34:42'),
(1006, 594, 279, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '0.00', '150.00', '150.00', '150.00', NULL, '0.00', 0, 0, '2021-10-27 10:10:53', '2021-11-10 10:34:42'),
(1008, 594, 283, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '3500.00', '5000.00', '5000.00', '5000.00', NULL, '0.00', 0, 0, '2021-10-27 10:10:53', '2021-11-10 10:34:42'),
(1009, 594, 285, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', '100.00', '(Channel Art, Profile design)sadfasf asdfas dfas fasdf asdf asdf a(Channel Art, Profile design)sadfasf asdfas dfas fasdf asdf asdf a(Channel Art, Profile design)sadfasf asdfas dfas fasdf asdf asdf a(Channel Art, Profile design)sadfasf asdfas dfas fasdf asdf asdf a(Channel Art, Profile design)sadfasf asdfas dfas fasdf asdf asdf a(Channel Art, Profile design)sadfasf asdfas dfas fasdf asdf asdf a(Channel Art, Profile design)sadfasf asdfas dfas fasdf asdf asdf a(Channel Art, Profile design)sadfasf asdfas dfas fasdf asdf asdf   (Channel Art, Profile design)sadfasf asdfas dfas fasdf asdf asdf a(Channel Art, Profile design)sadfasf asdfas dfas fasdf asdf asdf a(Channel Art, Profile design)sadfasf asdfas dfas fasdf asdf asdf a(Channel Art, Profile design)sadfasf asdfas dfas fasdf asdf asdf a(Channel Art, Profile design)sadfasf asdfas dfas fasdf asdf asdf a(Channel Art, Profile design)sadfasf asdfas dfas fasdf asdf asdf a(Channel Art, Profile design)sadfasf asdfas dfas fasdf asdf asdf a(Channel Art, Profile design)sadfasf asdfas dfas fasdf asdf asdf a(Channel Art, Profile design)sadfasf asdfas dfas fasdf asdf asdf a(Channel Art, Profile design)sadfasf asdfas dfas fasdf asdf asdf a(Channel Art, Profile design)sadfasf asdfas dfas fasdf asdf asdf a(Channel Art, Profile design)sadfasf asdfas dfas fasdf asdf asdf aa', '0.00', 0, 0, '2021-10-27 10:30:24', '2021-11-10 10:34:43'),
(1055, 595, 284, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '0.00', '250.00', '250.00', '250.00', NULL, '0.00', 0, 0, '2021-10-27 11:50:11', '2021-11-11 10:28:42'),
(1056, 595, 275, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '0.00', '500.00', '500.00', '500.00', NULL, '0.00', 0, 0, '2021-10-27 11:50:11', '2021-11-11 10:28:42'),
(1057, 595, 276, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '0.00', '500.00', '500.00', '500.00', NULL, '0.00', 0, 0, '2021-10-27 11:50:11', '2021-11-11 10:28:42'),
(1058, 595, 281, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '18500.00', '20000.00', '20000.00', '20000.00', NULL, '0.00', 0, 0, '2021-10-27 11:50:11', '2021-11-11 10:28:42'),
(1059, 595, 274, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '150.00', '300.00', '300.00', '300.00', NULL, '0.00', 0, 0, '2021-10-27 11:50:11', '2021-11-11 10:28:42'),
(1060, 595, 285, NULL, '5.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', '500.00', NULL, '0.00', 0, 0, '2021-10-27 11:50:11', '2021-11-11 10:28:42'),
(1061, 595, 277, NULL, '2.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '18000.00', '20000.00', '20000.00', '40000.00', NULL, '0.00', 0, 0, '2021-10-27 11:50:11', '2021-11-11 10:28:42'),
(1062, 595, 279, NULL, '2.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '0.00', '150.00', '150.00', '300.00', NULL, '0.00', 0, 0, '2021-10-27 11:50:11', '2021-11-11 10:28:42'),
(1063, 595, 282, NULL, '4.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '250.00', '350.00', '350.00', '1400.00', NULL, '0.00', 0, 0, '2021-10-27 11:50:11', '2021-11-11 10:28:42'),
(1064, 595, 283, NULL, '4.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '3500.00', '5000.00', '5000.00', '20000.00', NULL, '0.00', 0, 0, '2021-10-27 11:50:11', '2021-11-11 10:28:42'),
(1065, 595, 280, NULL, '4.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '80000.00', '82000.00', '82000.00', '328000.00', NULL, '0.00', 0, 0, '2021-10-27 11:50:11', '2021-11-11 10:28:42'),
(1066, 595, 288, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', '100.00', NULL, '0.00', 0, 0, '2021-10-27 12:00:42', '2021-11-11 10:28:42'),
(1067, 595, 287, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', '100.00', NULL, '0.00', 0, 0, '2021-10-27 12:00:42', '2021-11-11 10:28:42'),
(1068, 595, 286, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', '100.00', NULL, '0.00', 0, 0, '2021-10-27 12:00:42', '2021-11-11 10:28:42'),
(1069, 596, 287, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', '100.00', NULL, '0.00', 0, 0, '2021-10-30 05:34:34', '2021-10-30 05:34:34'),
(1070, 596, 288, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', '100.00', NULL, '0.00', 0, 0, '2021-10-30 05:34:34', '2021-10-30 05:34:34'),
(1071, 597, 287, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', '100.00', NULL, '0.00', 0, 0, '2021-10-30 05:35:34', '2021-10-30 05:35:34'),
(1072, 597, 288, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', '100.00', NULL, '0.00', 0, 0, '2021-10-30 05:35:34', '2021-10-30 05:35:34'),
(1073, 598, 287, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', '100.00', NULL, '0.00', 0, 0, '2021-10-30 05:36:35', '2021-10-30 05:36:35'),
(1074, 598, 288, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', '100.00', NULL, '0.00', 0, 0, '2021-10-30 05:36:35', '2021-10-30 05:36:35'),
(1075, 599, 287, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', '100.00', NULL, '0.00', 0, 0, '2021-10-30 05:37:20', '2021-10-30 05:37:20'),
(1076, 599, 288, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', '100.00', NULL, '0.00', 0, 0, '2021-10-30 05:37:20', '2021-10-30 05:37:20'),
(1077, 600, 287, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', '100.00', NULL, '0.00', 0, 0, '2021-10-30 05:38:44', '2021-10-30 05:38:44'),
(1078, 600, 288, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', '100.00', NULL, '0.00', 0, 0, '2021-10-30 05:38:44', '2021-10-30 05:38:44'),
(1079, 601, 284, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '0.00', '250.00', '250.00', '250.00', NULL, '0.00', 0, 0, '2021-10-30 05:50:20', '2021-10-30 05:50:20'),
(1080, 601, 276, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '0.00', '500.00', '500.00', '500.00', NULL, '0.00', 0, 0, '2021-10-30 05:50:20', '2021-10-30 05:50:20'),
(1081, 602, 288, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', '100.00', NULL, '0.00', 0, 0, '2021-10-30 05:50:53', '2021-10-30 05:50:53'),
(1082, 602, 284, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '0.00', '250.00', '250.00', '250.00', NULL, '0.00', 0, 0, '2021-10-30 05:50:53', '2021-10-30 05:50:53'),
(1083, 603, 282, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '250.00', '350.00', '350.00', '350.00', NULL, '0.00', 0, 0, '2021-10-30 05:51:30', '2021-10-30 05:51:30'),
(1084, 603, 281, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '18500.00', '20000.00', '20000.00', '20000.00', NULL, '0.00', 0, 0, '2021-10-30 05:51:30', '2021-10-30 05:51:30'),
(1085, 603, 280, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '80000.00', '82000.00', '82000.00', '82000.00', NULL, '0.00', 0, 0, '2021-10-30 05:51:30', '2021-10-30 05:51:30'),
(1086, 603, 283, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '3500.00', '5000.00', '5000.00', '5000.00', NULL, '0.00', 0, 0, '2021-10-30 05:51:30', '2021-10-30 05:51:30'),
(1087, 603, 284, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '0.00', '250.00', '250.00', '250.00', NULL, '0.00', 0, 0, '2021-10-30 05:51:30', '2021-10-30 05:51:30'),
(1088, 603, 285, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', '100.00', NULL, '0.00', 0, 0, '2021-10-30 05:51:30', '2021-10-30 05:51:30'),
(1089, 603, 288, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', '100.00', NULL, '0.00', 0, 0, '2021-10-30 05:51:30', '2021-10-30 05:51:30'),
(1090, 604, 287, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', '100.00', NULL, '0.00', 0, 0, '2021-10-30 05:59:37', '2021-10-30 05:59:37'),
(1091, 604, 288, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', '100.00', NULL, '0.00', 0, 0, '2021-10-30 05:59:37', '2021-10-30 05:59:37'),
(1092, 605, 287, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', '100.00', NULL, '0.00', 0, 0, '2021-10-30 06:00:24', '2021-10-30 06:00:24'),
(1093, 605, 288, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', '100.00', NULL, '0.00', 0, 0, '2021-10-30 06:00:24', '2021-10-30 06:00:24'),
(1094, 606, 294, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', '100.00', NULL, '0.00', 0, 0, '2021-11-08 11:03:30', '2021-11-08 11:03:30'),
(1095, 606, 298, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '0.00', '10100.00', '10100.00', '10100.00', NULL, '0.00', 0, 0, '2021-11-08 11:03:30', '2021-11-08 11:03:30'),
(1096, 607, 277, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '18000.00', '20000.00', '20000.00', '20000.00', NULL, '0.00', 0, 0, '2021-11-09 04:36:54', '2021-11-11 10:28:32'),
(1097, 608, 277, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '18000.00', '20000.00', '20000.00', '20000.00', NULL, '0.00', 0, 0, '2021-11-09 04:37:48', '2021-11-09 04:37:48'),
(1098, 609, 277, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '18000.00', '20000.00', '20000.00', '20000.00', NULL, '0.00', 0, 0, '2021-11-09 04:41:35', '2021-11-09 04:41:35'),
(1099, 610, 277, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '18000.00', '20000.00', '20000.00', '20000.00', NULL, '0.00', 0, 0, '2021-11-09 04:43:02', '2021-11-09 04:43:02'),
(1100, 611, 277, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '18000.00', '20000.00', '20000.00', '20000.00', NULL, '0.00', 0, 0, '2021-11-09 04:44:10', '2021-11-09 04:44:10'),
(1101, 612, 277, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '18000.00', '20000.00', '20000.00', '20000.00', NULL, '0.00', 0, 0, '2021-11-09 04:45:13', '2021-11-09 04:45:13'),
(1102, 613, 277, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '18000.00', '20000.00', '20000.00', '20000.00', NULL, '0.00', 0, 0, '2021-11-09 04:46:16', '2021-11-09 04:46:16'),
(1103, 614, 277, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '18000.00', '20000.00', '20000.00', '20000.00', NULL, '0.00', 0, 0, '2021-11-09 04:47:56', '2021-11-09 04:47:56'),
(1104, 615, 277, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '18000.00', '20000.00', '20000.00', '20000.00', NULL, '0.00', 0, 0, '2021-11-09 04:48:59', '2021-11-09 04:48:59'),
(1105, 616, 277, NULL, '3.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '18000.00', '20000.00', '20000.00', '60000.00', NULL, '0.00', 0, 0, '2021-11-09 04:49:54', '2021-11-09 07:46:53'),
(1107, 618, 277, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '18000.00', '20000.00', '20000.00', '20000.00', NULL, '0.00', 0, 0, '2021-11-10 06:30:52', '2021-11-10 06:30:52'),
(1108, 619, 277, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '18000.00', '20000.00', '20000.00', '20000.00', NULL, '0.00', 0, 0, '2021-11-10 06:39:33', '2021-11-10 06:39:33'),
(1109, 594, 273, 61, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', '100.00', NULL, '0.00', 0, 0, '2021-11-10 10:34:43', '2021-11-10 10:34:43'),
(1110, 594, 273, 61, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', '100.00', NULL, '0.00', 0, 0, '2021-11-10 10:34:43', '2021-11-10 10:34:43'),
(1111, 594, 273, 61, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', '100.00', NULL, '0.00', 0, 0, '2021-11-10 10:34:43', '2021-11-10 10:34:43'),
(1112, 594, 273, 61, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', '100.00', NULL, '0.00', 0, 0, '2021-11-10 10:34:43', '2021-11-10 10:34:43'),
(1113, 594, 273, 61, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', '100.00', NULL, '0.00', 0, 0, '2021-11-10 10:34:43', '2021-11-10 10:34:43'),
(1114, 594, 273, 61, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', '100.00', NULL, '0.00', 0, 0, '2021-11-10 10:34:43', '2021-11-10 10:34:43'),
(1115, 594, 273, 61, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', '100.00', NULL, '0.00', 0, 0, '2021-11-10 10:34:43', '2021-11-10 10:34:43'),
(1116, 594, 273, 61, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', '100.00', NULL, '0.00', 0, 0, '2021-11-10 10:34:43', '2021-11-10 10:34:43'),
(1117, 594, 273, 61, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', '100.00', NULL, '0.00', 0, 0, '2021-11-10 10:34:43', '2021-11-10 10:34:43'),
(1118, 594, 273, 61, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', '100.00', NULL, '0.00', 0, 0, '2021-11-10 10:34:43', '2021-11-10 10:34:43'),
(1119, 594, 273, 61, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', '100.00', NULL, '0.00', 0, 0, '2021-11-10 10:34:43', '2021-11-10 10:34:43'),
(1120, 594, 273, 61, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', '100.00', NULL, '0.00', 0, 0, '2021-11-10 10:34:43', '2021-11-10 10:34:43'),
(1121, 594, 273, 61, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', '100.00', NULL, '0.00', 0, 0, '2021-11-10 10:34:43', '2021-11-10 10:34:43'),
(1122, 620, 277, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '18000.00', '20000.00', '20000.00', '20000.00', NULL, '0.00', 0, 0, '2021-11-10 10:53:52', '2021-11-10 10:53:52'),
(1123, 621, 277, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '18000.00', '20000.00', '20000.00', '20000.00', NULL, '0.00', 0, 0, '2021-11-10 13:16:27', '2021-11-10 13:16:27'),
(1124, 622, 277, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '50.00', '11249.90', '28500.00', '22499.80', '33749.70', '33749.70', NULL, '0.00', 0, 0, '2021-11-11 10:23:27', '2021-11-11 13:00:53'),
(1125, 623, 307, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '500.00', '600.00', '600.00', '600.00', NULL, '0.00', 0, 0, '2021-11-11 10:42:20', '2021-11-11 10:42:20'),
(1126, 624, 306, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '0.00', '200.00', '200.00', '200.00', NULL, '0.00', 0, 0, '2021-11-11 11:02:54', '2021-11-11 11:23:17'),
(1127, 625, 277, NULL, '50.00', 'Piece', 1, '0.00', '0.00', '50.00', '11250.00', '19000.00', '22500.00', '33750.00', '1687500.00', NULL, '0.00', 0, 0, '2021-11-11 12:56:32', '2021-11-11 12:56:32'),
(1128, 626, 299, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', '100.00', NULL, '0.00', 0, 0, '2021-11-13 08:35:17', '2021-11-13 08:35:17'),
(1129, 627, 311, NULL, '1.00', 'Kilogram', 1, '0.00', '0.00', '0.00', '0.00', '50.00', '150.00', '150.00', '150.00', NULL, '0.00', 0, 0, '2021-11-17 04:40:55', '2021-11-17 04:40:55'),
(1130, 628, 308, NULL, '1.00', 'Kilogram', 1, '0.00', '0.00', '5.00', '7.50', '67.77', '150.00', '157.50', '157.50', NULL, '0.00', 0, 0, '2021-11-17 04:56:38', '2021-11-20 04:48:08'),
(1131, 629, 277, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '50.00', '10267.50', '18500.00', '20535.00', '30802.50', '30802.50', NULL, '0.00', 0, 0, '2021-11-18 11:57:39', '2021-11-18 11:59:41'),
(1132, 630, 314, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '2000.00', '100.00', '100.00', '100.00', NULL, '0.00', 0, 0, '2021-11-18 12:24:46', '2021-11-18 12:25:12'),
(1133, 631, 318, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '0.00', 0, 0, '2021-11-20 04:47:13', '2021-11-20 04:47:13'),
(1134, 632, 307, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '500.00', '600.00', '600.00', '600.00', NULL, '0.00', 0, 0, '2021-11-20 05:19:11', '2021-11-20 05:19:11'),
(1135, 632, 310, NULL, '1.00', 'Kilogram', 1, '0.00', '0.00', '0.00', '0.00', '5.20', '0.00', '0.00', '0.00', NULL, '0.00', 0, 0, '2021-11-20 05:19:11', '2021-11-20 05:19:11'),
(1136, 632, 318, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '0.00', 0, 0, '2021-11-20 05:19:11', '2021-11-20 05:19:11'),
(1137, 633, 277, NULL, '2.00', 'Piece', 1, '0.00', '0.00', '50.00', '10267.50', '18500.00', '20535.00', '30802.50', '61605.00', NULL, '0.00', 0, 0, '2021-12-12 08:35:06', '2021-12-12 08:35:06'),
(1138, 634, 298, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '0.00', '10100.00', '10100.00', '10100.00', NULL, '0.00', 0, 0, '2021-12-14 04:40:07', '2021-12-14 04:40:07'),
(1139, 635, 310, NULL, '1.00', 'Kilogram', 1, '0.00', '0.00', '0.00', '0.00', '5.20', '100.00', '100.00', '100.00', NULL, '0.00', 0, 0, '2021-12-14 04:41:19', '2021-12-14 04:41:19'),
(1140, 635, 311, NULL, '1.00', 'Kilogram', 1, '0.00', '0.00', '0.00', '0.00', '50.00', '100.00', '100.00', '100.00', NULL, '0.00', 0, 0, '2021-12-14 04:41:19', '2021-12-14 04:41:19'),
(1141, 636, 311, NULL, '1.00', 'Kilogram', 1, '0.00', '0.00', '0.00', '0.00', '50.00', '150.00', '150.00', '150.00', NULL, '0.00', 0, 0, '2021-12-14 04:45:07', '2021-12-14 04:45:07'),
(1142, 637, 299, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', '100.00', NULL, '0.00', 0, 0, '2021-12-19 08:08:44', '2021-12-19 08:08:44'),
(1143, 638, 306, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '0.00', '200.00', '200.00', '200.00', NULL, '0.00', 0, 0, '2021-12-28 06:11:07', '2021-12-28 06:11:07'),
(1144, 638, 307, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '500.00', '600.00', '600.00', '600.00', NULL, '0.00', 0, 0, '2021-12-28 06:11:07', '2021-12-28 06:11:07'),
(1145, 638, 308, NULL, '1.00', 'Kilogram', 1, '0.00', '0.00', '5.00', '0.00', '57.37', '0.00', '0.00', '0.00', NULL, '0.00', 0, 0, '2021-12-28 06:11:07', '2021-12-28 06:11:07'),
(1146, 638, 311, NULL, '1.00', 'Kilogram', 1, '0.00', '0.00', '0.00', '0.00', '50.00', '0.00', '0.00', '0.00', NULL, '0.00', 0, 0, '2021-12-28 06:11:07', '2021-12-28 06:11:07'),
(1147, 638, 309, NULL, '1.00', 'Kilogram', 1, '0.00', '0.00', '0.00', '0.00', '3.50', '0.00', '0.00', '0.00', NULL, '0.00', 0, 0, '2021-12-28 06:11:07', '2021-12-28 06:11:07'),
(1148, 638, 318, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '0.00', 0, 0, '2021-12-28 06:11:07', '2021-12-28 06:11:07'),
(1149, 639, 309, NULL, '1.00', 'Kilogram', 1, '0.00', '0.00', '0.00', '0.00', '3.50', '100.00', '100.00', '100.00', NULL, '0.00', 0, 0, '2021-12-28 06:13:47', '2021-12-28 06:13:47'),
(1150, 639, 310, NULL, '0.00', 'Kilogram', 1, '0.00', '0.00', '0.00', '0.00', '5.20', '20.00', '20.00', '0.00', NULL, '-1.00', 2, 0, '2021-12-28 06:13:47', '2022-01-02 08:04:47'),
(1151, 640, 306, NULL, '0.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '0.00', '200.00', '200.00', '0.00', NULL, '-1.00', 2, 0, '2021-12-28 06:19:26', '2022-01-02 08:51:40'),
(1152, 640, 307, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '500.00', '600.00', '600.00', '600.00', NULL, '0.00', 0, 0, '2021-12-28 06:19:26', '2021-12-28 06:19:26'),
(1153, 641, 306, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '0.00', '200.00', '200.00', '200.00', NULL, '0.00', 0, 0, '2021-12-28 06:27:58', '2021-12-28 06:27:58'),
(1154, 641, 307, NULL, '0.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '500.00', '600.00', '600.00', '0.00', NULL, '-1.00', 2, 0, '2021-12-28 06:27:58', '2022-01-02 08:01:39'),
(1155, 642, 306, NULL, '0.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '0.00', '200.00', '200.00', '0.00', NULL, '-1.00', 2, 0, '2021-12-28 06:33:23', '2022-01-02 07:14:29'),
(1156, 642, 308, NULL, '1.00', 'Kilogram', 1, '0.00', '0.00', '5.00', '0.00', '57.37', '0.00', '0.00', '0.00', NULL, '0.00', 0, 0, '2021-12-28 06:33:23', '2021-12-28 06:33:23'),
(1157, 642, 307, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '500.00', '600.00', '600.00', '600.00', NULL, '0.00', 0, 0, '2021-12-28 06:33:23', '2021-12-28 06:33:23'),
(1158, 643, 306, NULL, '0.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '0.00', '200.00', '200.00', '0.00', NULL, '-1.00', 0, 0, '2021-12-28 06:55:07', '2022-01-02 06:14:58'),
(1159, 643, 308, NULL, '1.00', 'Kilogram', 1, '0.00', '0.00', '5.00', '0.00', '57.37', '0.00', '0.00', '0.00', NULL, '-1.00', 1, 0, '2021-12-28 06:55:07', '2022-01-02 06:14:58'),
(1160, 644, 311, NULL, '4.00', 'Kilogram', 1, '0.00', '0.00', '0.00', '0.00', '50.00', '80.00', '80.00', '320.00', NULL, '-1.00', 2, 0, '2022-01-02 08:10:54', '2022-01-02 08:13:28'),
(1161, 644, 310, NULL, '2.00', 'Kilogram', 1, '0.00', '0.00', '0.00', '0.00', '5.20', '200.00', '200.00', '400.00', NULL, '0.00', 0, 0, '2022-01-02 08:10:54', '2022-01-02 08:10:54'),
(1162, 645, 310, NULL, '1.00', 'Kilogram', 1, '0.00', '0.00', '0.00', '0.00', '5.20', '10.00', '10.00', '10.00', NULL, '0.00', 0, 0, '2022-01-02 08:22:35', '2022-01-02 08:22:35'),
(1163, 646, 318, NULL, '0.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '0.00', '10.00', '10.00', '0.00', NULL, '-1.00', 0, 0, '2022-01-02 08:43:09', '2022-01-02 08:56:32'),
(1164, 646, 320, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '80.00', '136.00', '136.00', '136.00', NULL, '-1.00', 0, 0, '2022-01-02 08:43:09', '2022-01-02 08:59:09'),
(1165, 646, 310, NULL, '1.00', 'Kilogram', 1, '0.00', '0.00', '0.00', '0.00', '5.20', '0.00', '200.00', '200.00', NULL, '0.00', 0, 0, '2022-01-02 08:43:55', '2022-01-02 08:43:55'),
(1166, 640, 311, NULL, '1.00', 'Kilogram', 1, '0.00', '0.00', '0.00', '0.00', '50.00', '0.00', '300.00', '300.00', NULL, '0.00', 0, 0, '2022-01-02 08:51:40', '2022-01-02 08:51:40'),
(1167, 647, 311, NULL, '1.00', 'Kilogram', 1, '0.00', '0.00', '0.00', '0.00', '50.00', '0.00', '0.00', '0.00', NULL, '0.00', 0, 0, '2022-01-02 10:24:47', '2022-01-02 10:24:47'),
(1168, 647, 310, NULL, '1.00', 'Kilogram', 1, '0.00', '0.00', '0.00', '0.00', '5.20', '0.00', '0.00', '0.00', NULL, '0.00', 0, 0, '2022-01-02 10:24:47', '2022-01-02 10:24:47'),
(1169, 647, 320, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '80.00', '136.00', '136.00', '136.00', NULL, '0.00', 0, 0, '2022-01-02 10:24:47', '2022-01-02 10:24:47'),
(1170, 648, 277, NULL, '0.00', 'Piece', 1, '0.00', '0.00', '50.00', '10267.50', '18500.00', '20535.00', '30802.50', '0.00', NULL, '-1.00', 2, 0, '2022-01-02 11:26:08', '2022-01-02 11:26:55'),
(1171, 648, 320, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '80.00', '0.00', '29000.00', '29000.00', NULL, '0.00', 0, 0, '2022-01-02 11:26:55', '2022-01-02 11:26:55'),
(1172, 649, 277, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '50.00', '10267.50', '18500.00', '20535.00', '30802.50', '30802.50', NULL, '0.00', 0, 0, '2022-01-02 11:31:59', '2022-01-02 11:31:59'),
(1173, 650, 277, NULL, '0.00', 'Piece', 1, '0.00', '0.00', '50.00', '10267.50', '18500.00', '20535.00', '30802.50', '0.00', NULL, '-1.00', 2, 0, '2022-01-02 11:39:38', '2022-01-02 11:54:01'),
(1174, 650, 320, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '80.00', '0.00', '29000.00', '29000.00', NULL, '0.00', 0, 0, '2022-01-02 11:54:01', '2022-01-02 11:54:01'),
(1175, 651, 277, NULL, '2.00', 'Piece', 1, '0.00', '0.00', '50.00', '10267.50', '18500.00', '20535.00', '30802.50', '61605.00', NULL, '1.00', 2, 0, '2022-01-02 11:58:13', '2022-01-03 04:50:52'),
(1176, 652, 318, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '0.00', '50.00', '50.00', '50.00', NULL, '0.00', 0, 0, '2022-01-03 04:53:17', '2022-01-05 05:02:32'),
(1177, 653, 320, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '80.00', '136.00', '136.00', '136.00', NULL, '0.00', 0, 0, '2022-01-03 05:35:58', '2022-01-03 05:35:58'),
(1178, 654, 320, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '80.00', '136.00', '136.00', '136.00', NULL, '0.00', 0, 0, '2022-01-03 05:37:21', '2022-01-03 05:37:21'),
(1179, 655, 330, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', '100.00', '100.00', NULL, '0.00', 0, 0, '2022-01-03 07:28:22', '2022-01-03 07:28:22'),
(1180, 655, 329, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', '100.00', '100.00', NULL, '0.00', 0, 0, '2022-01-03 07:28:22', '2022-01-03 07:28:22'),
(1181, 656, 328, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', '100.00', '100.00', NULL, '0.00', 0, 0, '2022-01-03 07:28:46', '2022-01-03 07:28:46'),
(1182, 656, 329, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', '100.00', '100.00', NULL, '0.00', 0, 0, '2022-01-03 07:28:46', '2022-01-03 07:28:46'),
(1183, 656, 330, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', '100.00', '100.00', NULL, '0.00', 0, 0, '2022-01-03 07:28:46', '2022-01-03 07:28:46'),
(1184, 657, 330, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', '100.00', '100.00', NULL, '0.00', 0, 0, '2022-01-03 07:37:47', '2022-01-03 07:37:47'),
(1185, 657, 329, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', '100.00', '100.00', NULL, '-1.00', 1, 0, '2022-01-03 07:37:47', '2022-01-05 06:44:06'),
(1186, 658, 308, NULL, '1.00', 'Kilogram', 1, '0.00', '0.00', '5.00', '0.00', '57.37', '0.00', '0.00', '0.00', NULL, '0.00', 0, 0, '2022-01-05 05:30:18', '2022-01-05 05:30:18'),
(1187, 658, 309, NULL, '1.00', 'Kilogram', 1, '0.00', '0.00', '0.00', '0.00', '3.50', '0.00', '126.00', '126.00', NULL, '0.00', 0, 0, '2022-01-05 05:30:18', '2022-01-05 05:31:30'),
(1188, 658, 310, NULL, '1.00', 'Kilogram', 1, '0.00', '0.00', '0.00', '0.00', '5.20', '0.00', '0.00', '0.00', NULL, '0.00', 0, 0, '2022-01-05 05:30:18', '2022-01-05 05:30:18'),
(1189, 658, 311, NULL, '1.00', 'Kilogram', 1, '0.00', '0.00', '0.00', '0.00', '50.00', '0.00', '0.00', '0.00', NULL, '0.00', 0, 0, '2022-01-05 05:30:18', '2022-01-05 05:30:18'),
(1190, 658, 318, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', NULL, '0.00', 0, 0, '2022-01-05 05:30:18', '2022-01-05 05:30:18'),
(1191, 658, 321, NULL, '1.00', 'Kilogram', 1, '0.00', '0.00', '5.00', '6.00', '126.00', '120.00', '126.00', '126.00', NULL, '0.00', 0, 0, '2022-01-05 05:30:18', '2022-01-05 05:30:18'),
(1192, 658, 320, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '80.00', '136.00', '136.00', '136.00', NULL, '0.00', 0, 0, '2022-01-05 05:30:18', '2022-01-05 05:30:18'),
(1193, 658, 322, NULL, '1.00', 'Kilogram', 1, '0.00', '0.00', '5.00', '6.00', '126.00', '120.00', '126.00', '126.00', NULL, '0.00', 0, 0, '2022-01-05 05:30:18', '2022-01-05 05:30:18'),
(1194, 658, 324, NULL, '1.00', 'Kilogram', 1, '0.00', '0.00', '5.00', '6.00', '126.00', '120.00', '126.00', '126.00', NULL, '0.00', 0, 0, '2022-01-05 05:30:18', '2022-01-05 05:30:18'),
(1195, 658, 323, NULL, '0.00', 'Kilogram', 1, '0.00', '0.00', '5.00', '6.00', '126.00', '120.00', '126.00', '0.00', NULL, '-1.00', 2, 0, '2022-01-05 05:30:18', '2022-01-05 05:31:30'),
(1196, 659, 329, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', '100.00', '100.00', NULL, '0.00', 0, 0, '2022-01-05 08:47:33', '2022-01-05 08:47:33'),
(1197, 659, 330, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', '100.00', '100.00', NULL, '0.00', 0, 0, '2022-01-05 08:47:33', '2022-01-05 08:47:33'),
(1198, 660, 329, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', '100.00', '100.00', NULL, '0.00', 0, 0, '2022-01-05 10:00:04', '2022-01-05 10:00:04'),
(1199, 660, 330, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', '100.00', '100.00', NULL, '0.00', 0, 0, '2022-01-05 10:00:04', '2022-01-05 10:00:04'),
(1200, 664, 330, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', '100.00', '100.00', NULL, '0.00', 0, 0, '2022-01-05 11:34:21', '2022-01-05 11:34:21'),
(1201, 665, 277, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '50.00', '10267.50', '18500.00', '20535.00', '30802.50', '30802.50', NULL, '0.00', 0, 0, '2022-01-05 11:36:36', '2022-01-05 11:36:36'),
(1202, 666, 277, NULL, '1.00', 'Piece', 1, '535.00', '535.00', '0.00', '0.00', '18500.00', '20535.00', '20000.00', '20000.00', NULL, '0.00', 0, 0, '2022-01-05 11:38:26', '2022-01-05 11:38:26'),
(1203, 667, 277, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '50.00', '10267.50', '18500.00', '20535.00', '30802.50', '30802.50', NULL, '0.00', 0, 0, '2022-01-06 06:19:04', '2022-01-06 06:19:04'),
(1204, 668, 322, NULL, '1.00', 'Kilogram', 1, '0.00', '0.00', '5.00', '6.00', '126.00', '120.00', '126.00', '126.00', NULL, '0.00', 0, 0, '2022-01-06 11:06:27', '2022-01-06 11:06:27'),
(1205, 668, 323, NULL, '1.00', 'Kilogram', 1, '0.00', '0.00', '5.00', '6.00', '126.00', '120.00', '126.00', '126.00', NULL, '0.00', 0, 0, '2022-01-06 11:06:27', '2022-01-06 11:06:27'),
(1206, 668, 324, NULL, '1.00', 'Kilogram', 1, '0.00', '0.00', '5.00', '6.00', '126.00', '120.00', '126.00', '126.00', NULL, '0.00', 0, 0, '2022-01-06 11:06:27', '2022-01-06 11:06:27'),
(1207, 668, 327, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', '100.00', '100.00', NULL, '0.00', 0, 0, '2022-01-06 11:06:27', '2022-01-06 11:06:27'),
(1208, 668, 326, NULL, '1.00', 'Kilogram', 1, '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', '100.00', '100.00', NULL, '0.00', 0, 0, '2022-01-06 11:06:27', '2022-01-06 11:06:27'),
(1209, 668, 325, NULL, '1.00', 'Kilogram', 1, '0.00', '0.00', '5.00', '6.00', '126.00', '120.00', '126.00', '126.00', NULL, '0.00', 0, 0, '2022-01-06 11:06:27', '2022-01-06 11:06:27'),
(1210, 668, 330, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', '100.00', '100.00', NULL, '0.00', 0, 0, '2022-01-06 11:06:27', '2022-01-06 11:06:27'),
(1211, 668, 329, NULL, '1.00', 'Piece', 1, '0.00', '0.00', '0.00', '0.00', '100.00', '100.00', '100.00', '100.00', NULL, '0.00', 0, 0, '2022-01-06 11:06:27', '2022-01-06 11:06:27');

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
(20, 'SRI11211', 622, 2, NULL, NULL, 1, '0.00', '0.00', '33749.70', '33749.70', '0.00', '33749.70', '13-11-2021', 'November', '2021', '2021-11-12 18:00:00', '2021-11-13 05:20:53', '2021-11-13 05:21:08');

-- --------------------------------------------------------

--
-- Table structure for table `sale_return_products`
--

CREATE TABLE `sale_return_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sale_return_id` bigint(20) UNSIGNED NOT NULL,
  `sale_product_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_variant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `return_qty` decimal(22,2) NOT NULL DEFAULT 0.00,
  `unit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `return_subtotal` decimal(22,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sale_return_products`
--

INSERT INTO `sale_return_products` (`id`, `sale_return_id`, `sale_product_id`, `product_id`, `product_variant_id`, `return_qty`, `unit`, `return_subtotal`, `created_at`, `updated_at`) VALUES
(18, 20, 1124, 277, NULL, '1.00', 'Piece', '33749.70', '2021-11-13 05:20:53', '2021-11-13 05:20:53');

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
(18, 'sales.create', 'Add Sale', 'fas fa-cart-plus', NULL, NULL),
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

--
-- Dumping data for table `short_menu_users`
--

INSERT INTO `short_menu_users` (`id`, `short_menu_id`, `user_id`, `is_delete_in_update`, `created_at`, `updated_at`) VALUES
(244, 1, 2, 0, '2021-10-07 06:49:41', '2021-10-07 06:49:53'),
(245, 2, 2, 0, '2021-10-07 06:49:42', '2021-10-07 06:49:53'),
(246, 3, 2, 0, '2021-10-07 06:49:44', '2021-10-07 06:49:53'),
(247, 4, 2, 0, '2021-10-07 06:49:45', '2021-10-07 06:49:53'),
(248, 26, 2, 0, '2021-10-07 06:49:48', '2021-10-07 06:49:53'),
(249, 18, 2, 0, '2021-10-07 06:49:52', '2021-10-07 06:49:53'),
(250, 19, 2, 0, '2021-10-07 06:49:53', '2021-10-07 06:49:53');

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
(25, NULL, NULL, '01221', 1, '0.00', '0.00', '0.00', 1, '02-01-2022', '06:51:14 pm', 'January', '2022', NULL, '2022-01-01 18:00:00', 2, '2022-01-02 12:51:14', '2022-01-02 12:51:14');

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
(15, 25, 297, NULL, '1.00', 'Piece', '0.00', '0.00', 0, '2022-01-02 12:51:14', '2022-01-02 12:51:14');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` bigint(20) UNSIGNED NOT NULL,
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
  `total_return` decimal(8,2) NOT NULL DEFAULT 0.00,
  `total_purchase_return_due` decimal(22,2) NOT NULL DEFAULT 0.00,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `prefix` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `contact_id`, `name`, `business_name`, `phone`, `alternative_phone`, `alternate_phone`, `landline`, `email`, `date_of_birth`, `tax_number`, `opening_balance`, `pay_term`, `pay_term_number`, `address`, `shipping_address`, `city`, `state`, `country`, `zip_code`, `total_purchase`, `total_paid`, `total_purchase_due`, `total_return`, `total_purchase_return_due`, `status`, `prefix`, `created_at`, `updated_at`) VALUES
(60, '27812', 'Mr. Bailey', NULL, '+12341234123', '+12341234123', NULL, '+12341234123', NULL, NULL, NULL, '0.00', NULL, NULL, 'Warehouse No: 1', NULL, NULL, NULL, NULL, NULL, '5108258.70', '4573558.70', '516200.00', '37000.00', '0.00', 1, 's27812', '2021-10-17 12:15:44', '2021-12-27 06:27:22'),
(63, '39372', 'Test', NULL, '455522', '455522', NULL, '455522', NULL, NULL, NULL, '0.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '92000.00', '110500.00', '-17500.00', '0.00', '17500.00', 1, 'T39372', '2021-10-30 07:41:03', '2021-12-26 13:03:08'),
(66, 'S0064', 'Mr. Karim Hossain', NULL, '08801700000', '08801700000', NULL, '08801700000', NULL, NULL, NULL, '0.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 1, 'M64', '2021-11-09 06:25:41', '2021-11-09 08:08:27'),
(67, 'S0067', 'Mr. Karim', NULL, '08801700000', '08801700000', NULL, '08801700000', NULL, NULL, NULL, '0.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '37000.00', '0.00', '37000.00', '0.00', '0.00', 1, 'M67', '2021-11-09 06:25:55', '2021-11-20 13:21:37'),
(68, 'S0068', 'index.html', NULL, '08801700000', '08801700000', NULL, '08801700000', NULL, NULL, NULL, '0.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 1, 'i68', '2021-11-09 07:51:32', '2021-11-09 07:53:06'),
(69, 'S0069', 'Salgado', NULL, '01225254444', '01225254444', NULL, '01225254444', NULL, NULL, NULL, '0.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 1, 'S69', '2021-11-10 06:01:59', '2021-11-10 06:02:42'),
(70, 'S0070', 'Build Materials', NULL, '100', NULL, NULL, NULL, NULL, NULL, NULL, '100.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '100.00', '0.00', '0.00', 1, 'B0070', '2021-11-14 06:45:10', '2021-11-14 06:45:10'),
(71, 'S0071', 'Mr.Supplier', NULL, '455522', NULL, NULL, NULL, NULL, NULL, NULL, '0.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 1, 'M0071', '2021-11-14 06:51:35', '2021-11-14 06:51:35'),
(72, 'S0072', 'Mr.Supplier 2', NULL, '08801225444', NULL, NULL, NULL, NULL, NULL, NULL, '0.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', '0.00', '0.00', '0.00', '0.00', 1, 'M0072', '2021-11-14 06:55:00', '2021-11-14 06:55:00'),
(73, 'S0073', 'Mr Supplier', NULL, '015857445', NULL, NULL, NULL, NULL, NULL, NULL, '0.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '18500.00', '6100.00', '12400.00', '0.00', '0.00', 1, 'M73', '2021-11-14 09:55:02', '2021-12-27 06:30:45');

-- --------------------------------------------------------

--
-- Table structure for table `supplier_ledgers`
--

CREATE TABLE `supplier_ledgers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `supplier_id` bigint(20) UNSIGNED DEFAULT NULL,
  `purchase_id` bigint(20) UNSIGNED DEFAULT NULL,
  `purchase_payment_id` bigint(20) UNSIGNED DEFAULT NULL,
  `supplier_payment_id` bigint(20) UNSIGNED DEFAULT NULL,
  `row_type` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1=purchase;2=purchase_payment;3=opening_balance;4=direct_payment',
  `amount` decimal(22,2) DEFAULT NULL COMMENT 'only_for_opening',
  `report_date` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `supplier_ledgers`
--

INSERT INTO `supplier_ledgers` (`id`, `supplier_id`, `purchase_id`, `purchase_payment_id`, `supplier_payment_id`, `row_type`, `amount`, `report_date`, `created_at`, `updated_at`) VALUES
(533, 60, NULL, NULL, NULL, 3, '0.00', '2021-10-16 18:00:00', '2021-10-17 12:15:44', '2021-10-17 12:15:44'),
(590, 60, 406, NULL, NULL, 1, NULL, '2021-10-27 18:00:00', '2021-10-28 04:38:00', '2021-10-28 04:38:00'),
(591, 60, 407, NULL, NULL, 1, NULL, '2021-10-29 18:00:00', '2021-10-30 07:38:04', '2021-10-30 07:38:04'),
(592, 60, 408, NULL, NULL, 1, NULL, '2021-10-29 18:00:00', '2021-10-30 07:40:16', '2021-10-30 07:40:16'),
(593, 63, NULL, NULL, NULL, 3, '0.00', '2021-10-29 18:00:00', '2021-10-30 07:41:03', '2021-10-30 07:41:03'),
(594, 60, 409, NULL, NULL, 1, NULL, '2021-10-29 18:00:00', '2021-10-30 07:57:23', '2021-10-30 07:57:23'),
(595, 63, 410, NULL, NULL, 1, NULL, '2021-10-29 18:00:00', '2021-10-30 07:57:42', '2021-10-30 07:57:42'),
(596, 60, 411, NULL, NULL, 1, NULL, '2021-10-29 18:00:00', '2021-10-30 09:13:11', '2021-10-30 09:13:11'),
(597, 60, 412, NULL, NULL, 1, NULL, '2021-10-29 18:00:00', '2021-10-30 09:13:20', '2021-10-30 09:13:20'),
(599, 60, 414, NULL, NULL, 1, NULL, '2021-11-07 18:00:00', '2021-11-08 12:34:15', '2021-11-08 12:34:15'),
(602, 66, NULL, NULL, NULL, 3, '0.00', '2021-11-08 18:00:00', '2021-11-09 06:25:41', '2021-11-09 06:25:41'),
(603, 67, NULL, NULL, NULL, 3, '0.00', '2021-11-08 18:00:00', '2021-11-09 06:25:55', '2021-11-09 06:25:55'),
(604, 68, NULL, NULL, NULL, 3, '0.00', '2021-11-08 18:00:00', '2021-11-09 07:51:32', '2021-11-09 07:51:32'),
(605, 60, 415, NULL, NULL, 1, NULL, '2021-11-08 18:00:00', '2021-11-09 08:07:48', '2021-11-10 09:00:29'),
(606, 66, 416, NULL, NULL, 1, NULL, '2021-11-08 18:00:00', '2021-11-09 08:08:00', '2021-11-09 08:08:00'),
(608, 66, 418, NULL, NULL, 1, NULL, '2021-11-08 18:00:00', '2021-11-09 08:08:27', '2021-11-09 08:08:27'),
(609, 63, 419, NULL, NULL, 1, NULL, '2021-11-08 18:00:00', '2021-11-09 10:19:22', '2021-11-09 10:19:22'),
(610, 63, NULL, 415, NULL, 2, NULL, '2021-11-08 18:00:00', '2021-11-09 10:19:22', '2021-11-09 10:19:22'),
(611, 60, 420, NULL, NULL, 1, NULL, '2021-11-08 18:00:00', '2021-11-09 11:28:18', '2021-11-09 11:28:18'),
(612, 60, 421, NULL, NULL, 1, NULL, '2021-11-08 18:00:00', '2021-11-09 11:35:27', '2021-11-09 11:35:27'),
(613, 60, 422, NULL, NULL, 1, NULL, '2021-11-08 18:00:00', '2021-11-09 11:35:39', '2021-11-09 11:35:39'),
(614, 69, NULL, NULL, NULL, 3, '0.00', '2021-11-09 18:00:00', '2021-11-10 06:01:59', '2021-11-10 06:01:59'),
(615, 60, 423, NULL, NULL, 1, NULL, '2021-11-09 18:00:00', '2021-11-10 12:27:14', '2021-11-11 11:02:23'),
(616, 60, 424, NULL, NULL, 1, NULL, '2021-11-09 18:00:00', '2021-11-10 13:32:00', '2021-11-10 13:32:00'),
(617, 60, 425, NULL, NULL, 1, NULL, '2021-11-09 18:00:00', '2021-11-10 13:46:11', '2021-11-10 13:46:11'),
(618, 60, 426, NULL, NULL, 1, NULL, '2021-11-09 18:00:00', '2021-11-10 13:52:03', '2021-11-11 10:41:50'),
(619, 60, 427, NULL, NULL, 1, NULL, '2021-11-09 18:00:00', '2021-11-10 13:52:14', '2021-11-10 13:52:14'),
(620, 60, 428, NULL, NULL, 1, NULL, '2021-11-09 18:00:00', '2021-11-10 13:52:37', '2021-11-10 13:52:37'),
(621, 60, 429, NULL, NULL, 1, NULL, '2021-11-09 18:00:00', '2021-11-10 13:52:42', '2021-11-10 13:58:04'),
(622, 60, 430, NULL, NULL, 1, NULL, '2021-11-10 18:00:00', '2021-11-11 04:34:37', '2021-11-11 04:34:37'),
(623, 60, 431, NULL, NULL, 1, NULL, '2021-11-10 18:00:00', '2021-11-11 04:41:53', '2021-11-11 04:41:53'),
(624, 60, 432, NULL, NULL, 1, NULL, '2021-11-10 18:00:00', '2021-11-11 04:49:46', '2021-11-11 04:49:46'),
(625, 60, NULL, 416, NULL, 2, NULL, '2021-11-10 18:00:00', '2021-11-11 04:49:46', '2021-11-11 04:49:46'),
(626, 60, 433, NULL, NULL, 1, NULL, '2021-11-10 18:00:00', '2021-11-11 06:13:16', '2021-11-11 13:09:01'),
(627, 60, 434, NULL, NULL, 1, NULL, '2021-11-10 18:00:00', '2021-11-11 07:50:03', '2021-11-11 11:20:02'),
(630, 63, NULL, NULL, 124, 4, NULL, '2021-11-12 18:00:00', '2021-11-13 04:29:39', '2021-11-13 04:29:39'),
(631, 63, NULL, NULL, 125, 4, NULL, '2021-11-12 18:00:00', '2021-11-13 04:36:38', '2021-11-13 04:36:38'),
(632, 60, NULL, 419, NULL, 2, NULL, '2021-11-12 18:00:00', '2021-11-13 05:19:48', '2021-11-13 05:19:48'),
(633, 70, NULL, NULL, NULL, 3, '100.00', '2021-11-13 18:00:00', '2021-11-14 06:45:10', '2021-11-14 06:45:10'),
(634, 60, 437, NULL, NULL, 1, NULL, '2021-11-13 18:00:00', '2021-11-14 07:57:10', '2021-11-14 10:00:09'),
(635, 60, 438, NULL, NULL, 1, NULL, '2021-11-13 18:00:00', '2021-11-14 08:46:58', '2021-11-14 08:46:58'),
(636, 60, NULL, 420, NULL, 2, NULL, '2021-11-13 18:00:00', '2021-11-14 08:46:58', '2021-11-14 08:46:58'),
(637, 73, 439, NULL, NULL, 1, NULL, '2021-11-13 18:00:00', '2021-11-14 09:55:10', '2021-11-14 09:55:10'),
(638, 73, NULL, 421, NULL, 2, NULL, '2021-11-13 18:00:00', '2021-11-14 09:55:10', '2021-11-14 09:55:10'),
(639, 60, 440, NULL, NULL, 1, NULL, '2021-11-13 18:00:00', '2021-11-14 10:36:00', '2021-11-14 10:36:00'),
(640, 60, 441, NULL, NULL, 1, NULL, '2021-11-14 18:00:00', '2021-11-15 04:44:55', '2021-11-15 04:44:55'),
(641, 60, 442, NULL, NULL, 1, NULL, '2021-11-14 18:00:00', '2021-11-15 04:51:16', '2021-11-15 04:51:16'),
(642, 60, NULL, 422, NULL, 2, NULL, '2021-11-14 18:00:00', '2021-11-15 08:32:55', '2021-11-15 08:32:55'),
(643, 60, 443, NULL, NULL, 1, NULL, '2021-11-14 18:00:00', '2021-11-15 08:34:17', '2021-11-15 08:34:17'),
(644, 60, 444, NULL, NULL, 1, NULL, '2021-11-14 18:00:00', '2021-11-15 08:35:07', '2021-11-15 08:35:07'),
(645, 60, 445, NULL, NULL, 1, NULL, '2021-11-17 18:00:00', '2021-11-18 04:39:17', '2021-11-18 04:39:17'),
(646, 60, 446, NULL, NULL, 1, NULL, '2021-11-19 18:00:00', '2021-11-20 04:45:48', '2021-11-20 13:31:30'),
(647, 67, 447, NULL, NULL, 1, NULL, '2021-11-19 18:00:00', '2021-11-20 13:21:11', '2021-11-20 13:21:37'),
(648, 60, 448, NULL, NULL, 1, NULL, '2021-11-21 18:00:00', '2021-11-22 08:13:12', '2021-11-22 08:13:12'),
(649, 60, NULL, 423, NULL, 2, NULL, '2021-11-21 18:00:00', '2021-11-22 08:14:06', '2021-11-22 08:14:06'),
(652, 63, 457, NULL, NULL, 1, NULL, '2021-12-25 18:00:00', '2021-12-26 10:40:42', '2021-12-26 10:40:42'),
(653, 63, 458, NULL, NULL, 1, NULL, '2021-12-25 18:00:00', '2021-12-26 11:25:21', '2021-12-26 11:35:22'),
(655, 60, 460, NULL, NULL, 1, NULL, '2021-12-25 18:00:00', '2021-12-26 11:51:05', '2021-12-26 11:51:05'),
(656, 60, 461, NULL, NULL, 1, NULL, '2021-12-25 18:00:00', '2021-12-26 12:52:32', '2021-12-26 12:52:32'),
(657, 60, 462, NULL, NULL, 1, NULL, '2021-12-25 18:00:00', '2021-12-26 13:00:26', '2021-12-26 13:00:26'),
(658, 60, 463, NULL, NULL, 1, NULL, '2021-12-25 18:00:00', '2021-12-26 13:02:34', '2021-12-26 13:02:34'),
(659, 63, 464, NULL, NULL, 1, NULL, '2021-12-25 18:00:00', '2021-12-26 13:03:08', '2021-12-26 13:03:08'),
(660, 60, 465, NULL, NULL, 1, NULL, '2021-12-26 18:00:00', '2021-12-27 04:43:18', '2021-12-27 06:27:22'),
(661, 60, 466, NULL, NULL, 1, NULL, '2021-12-26 18:00:00', '2021-12-27 05:29:11', '2021-12-27 05:29:11'),
(662, 60, 467, NULL, NULL, 1, NULL, '2021-12-26 18:00:00', '2021-12-27 05:30:36', '2021-12-27 05:30:36'),
(663, 60, 468, NULL, NULL, 1, NULL, '2021-12-26 18:00:00', '2021-12-27 05:33:42', '2021-12-27 05:33:42'),
(664, 60, 469, NULL, NULL, 1, NULL, '2021-12-26 18:00:00', '2021-12-27 05:34:26', '2021-12-27 05:34:26'),
(665, 60, 470, NULL, NULL, 1, NULL, '2021-12-26 18:00:00', '2021-12-27 05:35:16', '2021-12-27 05:35:16'),
(666, 60, 471, NULL, NULL, 1, NULL, '2021-12-26 18:00:00', '2021-12-27 05:36:32', '2021-12-27 05:36:32'),
(667, 73, NULL, NULL, 126, 4, NULL, '2021-12-26 18:00:00', '2021-12-27 06:30:45', '2021-12-27 06:30:45');

-- --------------------------------------------------------

--
-- Table structure for table `supplier_payments`
--

CREATE TABLE `supplier_payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `voucher_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `supplier_id` bigint(20) UNSIGNED DEFAULT NULL,
  `account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `paid_amount` decimal(22,2) NOT NULL DEFAULT 0.00,
  `report_date` timestamp NULL DEFAULT NULL,
  `type` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1=purchase_payment;2=purchase_return_payment',
  `pay_mode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `time` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
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
  `note` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `admin_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `supplier_payments`
--

INSERT INTO `supplier_payments` (`id`, `voucher_no`, `branch_id`, `supplier_id`, `account_id`, `paid_amount`, `report_date`, `type`, `pay_mode`, `date`, `time`, `month`, `year`, `card_no`, `card_holder`, `card_type`, `card_transaction_no`, `card_month`, `card_year`, `card_secure_code`, `account_no`, `cheque_no`, `transaction_no`, `attachment`, `note`, `admin_id`, `created_at`, `updated_at`) VALUES
(124, 'SPV11211', NULL, 63, 28, '36500.00', '2021-11-12 18:00:00', 1, 'Cash', '13-11-2021', '10:29:39 am', 'November', '2021', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-11-13 04:29:39', '2021-12-27 07:20:38'),
(125, 'RPV1121125', NULL, 63, 28, '1000.00', '2021-11-12 18:00:00', 2, 'Cash', '13-11-2021', '10:36:38 am', 'November', '2021', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-11-13 04:36:38', '2021-11-13 04:36:38'),
(126, 'SPV1221126', NULL, 73, 28, '6000.00', '2021-12-26 18:00:00', 1, 'Cash', '27-12-2021', '12:30:44 pm', 'December', '2021', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-12-27 06:30:44', '2021-12-27 07:20:38');

-- --------------------------------------------------------

--
-- Table structure for table `supplier_payment_invoices`
--

CREATE TABLE `supplier_payment_invoices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `supplier_payment_id` bigint(20) UNSIGNED DEFAULT NULL,
  `purchase_id` bigint(20) UNSIGNED DEFAULT NULL,
  `supplier_return_id` bigint(20) UNSIGNED DEFAULT NULL,
  `paid_amount` decimal(22,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `type` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1=purchase_due;2=purchase_return_due'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `supplier_payment_invoices`
--

INSERT INTO `supplier_payment_invoices` (`id`, `supplier_payment_id`, `purchase_id`, `supplier_return_id`, `paid_amount`, `created_at`, `updated_at`, `type`) VALUES
(133, 124, 410, NULL, '18000.00', '2021-11-13 04:29:39', '2021-11-13 04:29:39', 1),
(135, 126, 439, NULL, '6000.00', '2021-12-27 06:30:45', '2021-12-27 06:30:45', 1);

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
(104, 60, 273, 61, 1, '2021-10-27 04:27:16', '2021-11-14 10:36:00'),
(105, 60, 274, NULL, 1, '2021-10-27 07:30:02', '2021-11-14 10:36:00'),
(106, 60, 282, NULL, 1, '2021-10-28 04:36:22', '2021-11-14 10:36:00'),
(107, 60, 278, NULL, 1, '2021-10-28 04:36:22', '2021-11-14 10:36:00'),
(108, 60, 277, NULL, 16, '2021-10-28 04:36:22', '2021-11-15 08:35:06'),
(109, 60, 283, NULL, 2, '2021-10-28 04:36:22', '2021-11-14 10:36:00'),
(110, 60, 281, NULL, 1, '2021-10-28 04:38:00', '2021-11-09 11:35:39'),
(111, 60, 285, NULL, 2, '2021-10-30 07:57:23', '2021-11-14 10:36:00'),
(112, 63, 277, NULL, 0, '2021-10-30 07:57:42', '2021-11-08 12:33:51'),
(113, 60, 298, NULL, 1, '2021-10-30 09:13:11', '2021-11-14 10:36:00'),
(114, 60, 294, NULL, 12, '2021-10-30 09:13:20', '2021-11-22 08:13:12'),
(115, 66, 299, NULL, 0, '2021-11-09 08:08:00', '2021-11-09 11:26:19'),
(116, 63, 281, NULL, -1, '2021-11-09 08:08:08', '2021-11-13 04:36:15'),
(117, 66, 294, NULL, 0, '2021-11-09 08:08:27', '2021-11-09 11:26:45'),
(118, 60, 306, NULL, 11, '2021-11-10 12:27:14', '2021-11-14 10:36:00'),
(119, 60, 307, NULL, 6, '2021-11-10 13:46:11', '2021-11-14 10:36:00'),
(120, 60, 309, NULL, 500001, '2021-11-14 08:46:58', '2021-11-14 10:36:00'),
(121, 60, 310, NULL, 50001, '2021-11-14 08:46:58', '2021-11-14 10:36:00'),
(122, 60, 311, NULL, 50001, '2021-11-14 08:46:58', '2021-11-14 10:36:00'),
(123, 73, 277, NULL, 1, '2021-11-14 09:55:10', '2021-11-14 09:55:10'),
(124, 60, 276, NULL, 1, '2021-11-14 10:36:00', '2021-11-14 10:36:00'),
(125, 60, 273, 63, 1, '2021-11-14 10:36:00', '2021-11-14 10:36:00'),
(126, 60, 273, 62, 1, '2021-11-14 10:36:00', '2021-11-14 10:36:00'),
(127, 60, 302, NULL, 1, '2021-11-14 10:36:00', '2021-11-14 10:36:00'),
(128, 60, 279, NULL, 1, '2021-11-14 10:36:00', '2021-11-14 10:36:00'),
(129, 60, 301, NULL, 3, '2021-11-14 10:36:00', '2021-11-14 10:36:00'),
(130, 60, 303, NULL, 2, '2021-11-14 10:36:00', '2021-11-14 10:36:00'),
(131, 60, 304, NULL, 2, '2021-11-14 10:36:00', '2021-11-14 10:36:00'),
(132, 60, 296, NULL, 1, '2021-11-14 10:36:00', '2021-11-14 10:36:00'),
(133, 60, 305, NULL, 1, '2021-11-14 10:36:00', '2021-11-14 10:36:00'),
(134, 60, 286, NULL, 1, '2021-11-14 10:36:00', '2021-11-14 10:36:00'),
(135, 60, 287, NULL, 1, '2021-11-14 10:36:00', '2021-11-14 10:36:00'),
(136, 60, 288, NULL, 1, '2021-11-14 10:36:00', '2021-11-14 10:36:00'),
(137, 60, 295, NULL, 1, '2021-11-14 10:36:00', '2021-11-14 10:36:00'),
(138, 60, 300, NULL, 2, '2021-11-14 10:36:00', '2021-11-14 10:36:00'),
(139, 60, 290, NULL, 1, '2021-11-14 10:36:00', '2021-11-14 10:36:00'),
(140, 60, 291, NULL, 7, '2021-11-14 10:36:00', '2021-12-27 06:27:22'),
(141, 60, 275, NULL, 2, '2021-11-14 10:36:00', '2021-11-14 10:36:00'),
(142, 60, 297, NULL, 3, '2021-11-14 10:36:00', '2021-11-14 10:36:00'),
(143, 60, 299, NULL, 1, '2021-11-14 10:36:00', '2021-11-14 10:36:00'),
(144, 60, 292, NULL, 6, '2021-11-14 10:36:00', '2021-12-26 11:23:05'),
(145, 60, 314, NULL, 1, '2021-11-18 04:39:16', '2021-11-18 04:39:16'),
(146, 60, 318, NULL, 9, '2021-11-20 04:45:48', '2021-11-20 13:31:30'),
(147, 67, 277, NULL, 2, '2021-11-20 13:21:11', '2021-11-20 13:21:37'),
(148, 60, 293, NULL, 19, '2021-12-26 10:21:48', '2021-12-27 06:27:22'),
(149, 63, 293, NULL, 10, '2021-12-26 10:29:44', '2021-12-26 13:03:08');

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
(7, 'Task-2', '2021/6214', 'Urgent', 'Complated', '2021-07-07 18:00:00', 'D-S', NULL, 2, '2021-07-07 18:00:00', '2021-09-12 10:16:49'),
(8, 'Create a data base for our new project.', '2021/9211', 'Medium', 'Complated', '2021-07-07 18:00:00', 'Create a data base for our new project.', NULL, 2, '2021-07-07 18:00:00', '2021-09-06 07:26:23'),
(14, 'ff', '2021/3531', 'Low', 'Complated', '2021-01-08 18:00:00', NULL, NULL, 2, '2021-09-10 18:00:00', '2022-01-03 11:59:50'),
(15, 'Edit All Sale', '01228131', 'Low', 'New', '2021-12-06 18:00:00', NULL, NULL, 2, '2022-01-02 18:00:00', '2022-01-03 12:07:56'),
(16, 'Edit All Sale', '01226733', 'Low', 'New', '2022-01-02 18:00:00', NULL, NULL, 2, '2022-01-02 18:00:00', NULL);

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
(1, 7, 2, 0, NULL, '2021-09-12 10:16:49'),
(3, 8, 2, 0, NULL, '2021-09-06 07:26:23'),
(23, 14, 2, 0, NULL, NULL),
(24, 15, 2, 0, NULL, '2022-01-03 12:07:56'),
(25, 16, 2, 0, NULL, NULL);

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
(9, 'Pound', 'PND', NULL, '2021-01-19 04:29:11', '2021-01-19 04:29:11'),
(10, 'Unit', 'UT', NULL, '2021-07-15 06:08:10', '2021-07-15 06:08:10'),
(11, 'Item', 'ITM', NULL, '2021-07-15 06:53:29', '2021-07-15 06:53:29'),
(13, 'Liter', '1', NULL, '2021-11-18 12:32:46', '2021-11-18 12:32:46'),
(14, 'Box', 'BX', NULL, '2021-12-07 05:32:47', '2021-12-07 05:32:47');

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
(19, '1 Year warranty', '1', 'Years', NULL, 1, '2021-08-12 07:25:51', '2021-08-31 10:43:01');

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
  ADD KEY `accounts_bank_id_foreign` (`bank_id`);

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
  ADD KEY `cash_flows_payroll_payment_id_foreign` (`payroll_payment_id`),
  ADD KEY `cash_flows_loan_id_foreign` (`loan_id`),
  ADD KEY `cash_flows_customer_payment_id_foreign` (`customer_payment_id`),
  ADD KEY `cash_flows_supplier_payment_id_foreign` (`supplier_payment_id`),
  ADD KEY `cash_flows_loan_payment_id_foreign` (`loan_payment_id`);

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
  ADD KEY `customer_ledgers_money_receipt_id_foreign` (`money_receipt_id`),
  ADD KEY `customer_ledgers_customer_payment_id_foreign` (`customer_payment_id`);

--
-- Indexes for table `customer_payments`
--
ALTER TABLE `customer_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_payments_branch_id_foreign` (`branch_id`),
  ADD KEY `customer_payments_customer_id_foreign` (`customer_id`),
  ADD KEY `customer_payments_account_id_foreign` (`account_id`),
  ADD KEY `customer_payments_admin_id_foreign` (`admin_id`);

--
-- Indexes for table `customer_payment_invoices`
--
ALTER TABLE `customer_payment_invoices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_payment_invoices_customer_payment_id_foreign` (`customer_payment_id`),
  ADD KEY `customer_payment_invoices_sale_id_foreign` (`sale_id`);

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
-- Indexes for table `loans`
--
ALTER TABLE `loans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `loans_loan_company_id_foreign` (`loan_company_id`),
  ADD KEY `loans_account_id_foreign` (`account_id`),
  ADD KEY `loans_created_user_id_foreign` (`created_user_id`),
  ADD KEY `loans_branch_id_foreign` (`branch_id`),
  ADD KEY `loans_expense_id_foreign` (`expense_id`),
  ADD KEY `loans_purchase_id_foreign` (`purchase_id`);

--
-- Indexes for table `loan_companies`
--
ALTER TABLE `loan_companies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `loan_companies_branch_id_foreign` (`branch_id`);

--
-- Indexes for table `loan_payments`
--
ALTER TABLE `loan_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `loan_payments_company_id_foreign` (`company_id`),
  ADD KEY `loan_payments_branch_id_foreign` (`branch_id`),
  ADD KEY `loan_payments_user_id_foreign` (`user_id`),
  ADD KEY `loan_payments_account_id_foreign` (`account_id`);

--
-- Indexes for table `loan_payment_distributions`
--
ALTER TABLE `loan_payment_distributions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `loan_payment_distributions_loan_payment_id_foreign` (`loan_payment_id`),
  ADD KEY `loan_payment_distributions_loan_id_foreign` (`loan_id`);

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
  ADD PRIMARY KEY (`id`),
  ADD KEY `payment_methods_account_id_foreign` (`account_id`);

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
-- Indexes for table `processes`
--
ALTER TABLE `processes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `processes_product_id_foreign` (`product_id`),
  ADD KEY `processes_variant_id_foreign` (`variant_id`),
  ADD KEY `processes_unit_id_foreign` (`unit_id`);

--
-- Indexes for table `process_ingredients`
--
ALTER TABLE `process_ingredients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `process_ingredients_process_id_foreign` (`process_id`),
  ADD KEY `process_ingredients_product_id_foreign` (`product_id`),
  ADD KEY `process_ingredients_variant_id_foreign` (`variant_id`),
  ADD KEY `process_ingredients_unit_id_foreign` (`unit_id`);

--
-- Indexes for table `productions`
--
ALTER TABLE `productions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `productions_warehouse_id_foreign` (`warehouse_id`),
  ADD KEY `productions_branch_id_foreign` (`branch_id`),
  ADD KEY `productions_product_id_foreign` (`product_id`),
  ADD KEY `productions_variant_id_foreign` (`variant_id`),
  ADD KEY `productions_unit_id_foreign` (`unit_id`),
  ADD KEY `productions_stock_warehouse_id_foreign` (`stock_warehouse_id`),
  ADD KEY `productions_stock_branch_id_foreign` (`stock_branch_id`),
  ADD KEY `productions_tax_id_foreign` (`tax_id`);

--
-- Indexes for table `production_ingredients`
--
ALTER TABLE `production_ingredients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `production_ingredients_product_id_foreign` (`product_id`),
  ADD KEY `production_ingredients_variant_id_foreign` (`variant_id`),
  ADD KEY `production_ingredients_unit_id_foreign` (`unit_id`),
  ADD KEY `production_ingredients_production_id_foreign` (`production_id`);

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
-- Indexes for table `purchase_order_products`
--
ALTER TABLE `purchase_order_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_order_products_purchase_id_foreign` (`purchase_id`),
  ADD KEY `purchase_order_products_product_id_foreign` (`product_id`),
  ADD KEY `purchase_order_products_product_variant_id_foreign` (`product_variant_id`);

--
-- Indexes for table `purchase_payments`
--
ALTER TABLE `purchase_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_payments_purchase_id_foreign` (`purchase_id`),
  ADD KEY `purchase_payments_supplier_id_foreign` (`supplier_id`),
  ADD KEY `purchase_payments_account_id_foreign` (`account_id`),
  ADD KEY `purchase_payments_supplier_payment_id_foreign` (`supplier_payment_id`),
  ADD KEY `purchase_payments_supplier_return_id_foreign` (`supplier_return_id`);

--
-- Indexes for table `purchase_products`
--
ALTER TABLE `purchase_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_products_purchase_id_foreign` (`purchase_id`),
  ADD KEY `purchase_products_product_id_foreign` (`product_id`),
  ADD KEY `purchase_products_product_variant_id_foreign` (`product_variant_id`),
  ADD KEY `purchase_products_product_order_product_id_foreign` (`product_order_product_id`);

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
  ADD KEY `sale_payments_customer_payment_id_foreign` (`customer_payment_id`),
  ADD KEY `sale_payments_payment_method_id_foreign` (`payment_method_id`);

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
  ADD KEY `sale_return_products_sale_product_id_foreign` (`sale_product_id`),
  ADD KEY `sale_return_products_product_id_foreign` (`product_id`),
  ADD KEY `sale_return_products_product_variant_id_foreign` (`product_variant_id`);

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
  ADD KEY `supplier_ledgers_purchase_payment_id_foreign` (`purchase_payment_id`),
  ADD KEY `supplier_ledgers_supplier_payment_id_foreign` (`supplier_payment_id`);

--
-- Indexes for table `supplier_payments`
--
ALTER TABLE `supplier_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `supplier_payments_branch_id_foreign` (`branch_id`),
  ADD KEY `supplier_payments_supplier_id_foreign` (`supplier_id`),
  ADD KEY `supplier_payments_account_id_foreign` (`account_id`),
  ADD KEY `supplier_payments_admin_id_foreign` (`admin_id`);

--
-- Indexes for table `supplier_payment_invoices`
--
ALTER TABLE `supplier_payment_invoices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `supplier_payment_invoices_supplier_payment_id_foreign` (`supplier_payment_id`),
  ADD KEY `supplier_payment_invoices_purchase_id_foreign` (`purchase_id`),
  ADD KEY `supplier_payment_invoices_supplier_return_id_foreign` (`supplier_return_id`);

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `addons`
--
ALTER TABLE `addons`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `admin_and_users`
--
ALTER TABLE `admin_and_users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `asset_types`
--
ALTER TABLE `asset_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `branch_payment_methods`
--
ALTER TABLE `branch_payment_methods`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

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
-- AUTO_INCREMENT for table `cash_counters`
--
ALTER TABLE `cash_counters`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `cash_flows`
--
ALTER TABLE `cash_flows`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=520;

--
-- AUTO_INCREMENT for table `cash_registers`
--
ALTER TABLE `cash_registers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `cash_register_transactions`
--
ALTER TABLE `cash_register_transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=231;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=120;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=115;

--
-- AUTO_INCREMENT for table `customer_groups`
--
ALTER TABLE `customer_groups`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `customer_ledgers`
--
ALTER TABLE `customer_ledgers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=936;

--
-- AUTO_INCREMENT for table `customer_payments`
--
ALTER TABLE `customer_payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `customer_payment_invoices`
--
ALTER TABLE `customer_payment_invoices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `expanses`
--
ALTER TABLE `expanses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `expanse_categories`
--
ALTER TABLE `expanse_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `expanse_payments`
--
ALTER TABLE `expanse_payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT for table `expense_descriptions`
--
ALTER TABLE `expense_descriptions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `hrm_payroll_allowances`
--
ALTER TABLE `hrm_payroll_allowances`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `hrm_payroll_deductions`
--
ALTER TABLE `hrm_payroll_deductions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `hrm_payroll_payments`
--
ALTER TABLE `hrm_payroll_payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `hrm_shifts`
--
ALTER TABLE `hrm_shifts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `loans`
--
ALTER TABLE `loans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `loan_companies`
--
ALTER TABLE `loan_companies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `loan_payments`
--
ALTER TABLE `loan_payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loan_payment_distributions`
--
ALTER TABLE `loan_payment_distributions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `memos`
--
ALTER TABLE `memos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `memo_users`
--
ALTER TABLE `memo_users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=315;

--
-- AUTO_INCREMENT for table `money_receipts`
--
ALTER TABLE `money_receipts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `months`
--
ALTER TABLE `months`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `payment_methods`
--
ALTER TABLE `payment_methods`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `pos_short_menus`
--
ALTER TABLE `pos_short_menus`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `pos_short_menu_users`
--
ALTER TABLE `pos_short_menu_users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=113;

--
-- AUTO_INCREMENT for table `price_groups`
--
ALTER TABLE `price_groups`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `price_group_products`
--
ALTER TABLE `price_group_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `processes`
--
ALTER TABLE `processes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `process_ingredients`
--
ALTER TABLE `process_ingredients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `productions`
--
ALTER TABLE `productions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT for table `production_ingredients`
--
ALTER TABLE `production_ingredients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=240;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=331;

--
-- AUTO_INCREMENT for table `product_branches`
--
ALTER TABLE `product_branches`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=246;

--
-- AUTO_INCREMENT for table `product_branch_variants`
--
ALTER TABLE `product_branch_variants`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `product_opening_stocks`
--
ALTER TABLE `product_opening_stocks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=264;

--
-- AUTO_INCREMENT for table `product_variants`
--
ALTER TABLE `product_variants`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `product_warehouses`
--
ALTER TABLE `product_warehouses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=110;

--
-- AUTO_INCREMENT for table `product_warehouse_variants`
--
ALTER TABLE `product_warehouse_variants`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `purchases`
--
ALTER TABLE `purchases`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=472;

--
-- AUTO_INCREMENT for table `purchase_order_products`
--
ALTER TABLE `purchase_order_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `purchase_payments`
--
ALTER TABLE `purchase_payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=425;

--
-- AUTO_INCREMENT for table `purchase_products`
--
ALTER TABLE `purchase_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=755;

--
-- AUTO_INCREMENT for table `purchase_returns`
--
ALTER TABLE `purchase_returns`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT for table `purchase_return_products`
--
ALTER TABLE `purchase_return_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=211;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `role_permissions`
--
ALTER TABLE `role_permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=669;

--
-- AUTO_INCREMENT for table `sale_payments`
--
ALTER TABLE `sale_payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=621;

--
-- AUTO_INCREMENT for table `sale_products`
--
ALTER TABLE `sale_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1212;

--
-- AUTO_INCREMENT for table `sale_returns`
--
ALTER TABLE `sale_returns`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `sale_return_products`
--
ALTER TABLE `sale_return_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `short_menus`
--
ALTER TABLE `short_menus`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `short_menu_users`
--
ALTER TABLE `short_menu_users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=251;

--
-- AUTO_INCREMENT for table `stock_adjustments`
--
ALTER TABLE `stock_adjustments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `stock_adjustment_products`
--
ALTER TABLE `stock_adjustment_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `supplier_ledgers`
--
ALTER TABLE `supplier_ledgers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=668;

--
-- AUTO_INCREMENT for table `supplier_payments`
--
ALTER TABLE `supplier_payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=127;

--
-- AUTO_INCREMENT for table `supplier_payment_invoices`
--
ALTER TABLE `supplier_payment_invoices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=136;

--
-- AUTO_INCREMENT for table `supplier_products`
--
ALTER TABLE `supplier_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=150;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `todo_users`
--
ALTER TABLE `todo_users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `transfer_stock_to_branches`
--
ALTER TABLE `transfer_stock_to_branches`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `transfer_stock_to_branch_products`
--
ALTER TABLE `transfer_stock_to_branch_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `transfer_stock_to_warehouses`
--
ALTER TABLE `transfer_stock_to_warehouses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `transfer_stock_to_warehouse_products`
--
ALTER TABLE `transfer_stock_to_warehouse_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `units`
--
ALTER TABLE `units`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `warehouses`
--
ALTER TABLE `warehouses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `warranties`
--
ALTER TABLE `warranties`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `workspaces`
--
ALTER TABLE `workspaces`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `workspace_attachments`
--
ALTER TABLE `workspace_attachments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `workspace_tasks`
--
ALTER TABLE `workspace_tasks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `workspace_users`
--
ALTER TABLE `workspace_users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

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
-- Constraints for table `cash_counters`
--
ALTER TABLE `cash_counters`
  ADD CONSTRAINT `cash_counters_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cash_flows`
--
ALTER TABLE `cash_flows`
  ADD CONSTRAINT `cash_flows_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cash_flows_customer_payment_id_foreign` FOREIGN KEY (`customer_payment_id`) REFERENCES `customer_payments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cash_flows_expanse_payment_id_foreign` FOREIGN KEY (`expanse_payment_id`) REFERENCES `expanse_payments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cash_flows_loan_id_foreign` FOREIGN KEY (`loan_id`) REFERENCES `loans` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cash_flows_loan_payment_id_foreign` FOREIGN KEY (`loan_payment_id`) REFERENCES `loan_payments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cash_flows_money_receipt_id_foreign` FOREIGN KEY (`money_receipt_id`) REFERENCES `money_receipts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cash_flows_payroll_id_foreign` FOREIGN KEY (`payroll_id`) REFERENCES `hrm_payrolls` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cash_flows_payroll_payment_id_foreign` FOREIGN KEY (`payroll_payment_id`) REFERENCES `hrm_payroll_payments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cash_flows_purchase_payment_id_foreign` FOREIGN KEY (`purchase_payment_id`) REFERENCES `purchase_payments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cash_flows_receiver_account_id_foreign` FOREIGN KEY (`receiver_account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cash_flows_sale_payment_id_foreign` FOREIGN KEY (`sale_payment_id`) REFERENCES `sale_payments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cash_flows_sender_account_id_foreign` FOREIGN KEY (`sender_account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cash_flows_supplier_payment_id_foreign` FOREIGN KEY (`supplier_payment_id`) REFERENCES `supplier_payments` (`id`) ON DELETE CASCADE;

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
  ADD CONSTRAINT `customer_ledgers_customer_payment_id_foreign` FOREIGN KEY (`customer_payment_id`) REFERENCES `customer_payments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `customer_ledgers_money_receipt_id_foreign` FOREIGN KEY (`money_receipt_id`) REFERENCES `money_receipts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `customer_ledgers_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `customer_ledgers_sale_payment_id_foreign` FOREIGN KEY (`sale_payment_id`) REFERENCES `sale_payments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `customer_payments`
--
ALTER TABLE `customer_payments`
  ADD CONSTRAINT `customer_payments_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `customer_payments_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `admin_and_users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `customer_payments_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `customer_payments_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `customer_payment_invoices`
--
ALTER TABLE `customer_payment_invoices`
  ADD CONSTRAINT `customer_payment_invoices_customer_payment_id_foreign` FOREIGN KEY (`customer_payment_id`) REFERENCES `customer_payments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `customer_payment_invoices_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE;

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
-- Constraints for table `loans`
--
ALTER TABLE `loans`
  ADD CONSTRAINT `loans_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `loans_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `loans_created_user_id_foreign` FOREIGN KEY (`created_user_id`) REFERENCES `admin_and_users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `loans_expense_id_foreign` FOREIGN KEY (`expense_id`) REFERENCES `expanses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `loans_loan_company_id_foreign` FOREIGN KEY (`loan_company_id`) REFERENCES `loan_companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `loans_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `loan_companies`
--
ALTER TABLE `loan_companies`
  ADD CONSTRAINT `loan_companies_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `loan_payments`
--
ALTER TABLE `loan_payments`
  ADD CONSTRAINT `loan_payments_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `loan_payments_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `loan_payments_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `loan_companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `loan_payments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `admin_and_users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `loan_payment_distributions`
--
ALTER TABLE `loan_payment_distributions`
  ADD CONSTRAINT `loan_payment_distributions_loan_id_foreign` FOREIGN KEY (`loan_id`) REFERENCES `loans` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `loan_payment_distributions_loan_payment_id_foreign` FOREIGN KEY (`loan_payment_id`) REFERENCES `loan_payments` (`id`) ON DELETE CASCADE;

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
-- Constraints for table `payment_methods`
--
ALTER TABLE `payment_methods`
  ADD CONSTRAINT `payment_methods_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE SET NULL;

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
-- Constraints for table `processes`
--
ALTER TABLE `processes`
  ADD CONSTRAINT `processes_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `processes_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `processes_variant_id_foreign` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `process_ingredients`
--
ALTER TABLE `process_ingredients`
  ADD CONSTRAINT `process_ingredients_process_id_foreign` FOREIGN KEY (`process_id`) REFERENCES `processes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `process_ingredients_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `process_ingredients_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `process_ingredients_variant_id_foreign` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `productions`
--
ALTER TABLE `productions`
  ADD CONSTRAINT `productions_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `productions_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `productions_stock_branch_id_foreign` FOREIGN KEY (`stock_branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `productions_stock_warehouse_id_foreign` FOREIGN KEY (`stock_warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `productions_tax_id_foreign` FOREIGN KEY (`tax_id`) REFERENCES `taxes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `productions_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `productions_variant_id_foreign` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `productions_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `production_ingredients`
--
ALTER TABLE `production_ingredients`
  ADD CONSTRAINT `production_ingredients_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `production_ingredients_production_id_foreign` FOREIGN KEY (`production_id`) REFERENCES `productions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `production_ingredients_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `production_ingredients_variant_id_foreign` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE;

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
-- Constraints for table `purchase_order_products`
--
ALTER TABLE `purchase_order_products`
  ADD CONSTRAINT `purchase_order_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_order_products_product_variant_id_foreign` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_order_products_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `purchase_payments`
--
ALTER TABLE `purchase_payments`
  ADD CONSTRAINT `purchase_payments_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_payments_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_payments_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_payments_supplier_payment_id_foreign` FOREIGN KEY (`supplier_payment_id`) REFERENCES `supplier_payments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_payments_supplier_return_id_foreign` FOREIGN KEY (`supplier_return_id`) REFERENCES `purchase_returns` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `purchase_products`
--
ALTER TABLE `purchase_products`
  ADD CONSTRAINT `purchase_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_products_product_order_product_id_foreign` FOREIGN KEY (`product_order_product_id`) REFERENCES `purchase_order_products` (`id`) ON DELETE CASCADE,
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
  ADD CONSTRAINT `sale_payments_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sale_payments_customer_payment_id_foreign` FOREIGN KEY (`customer_payment_id`) REFERENCES `customer_payments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sale_payments_payment_method_id_foreign` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_methods` (`id`) ON DELETE SET NULL,
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
  ADD CONSTRAINT `sale_return_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sale_return_products_product_variant_id_foreign` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE,
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
  ADD CONSTRAINT `supplier_ledgers_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `supplier_ledgers_supplier_payment_id_foreign` FOREIGN KEY (`supplier_payment_id`) REFERENCES `supplier_payments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `supplier_payments`
--
ALTER TABLE `supplier_payments`
  ADD CONSTRAINT `supplier_payments_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `supplier_payments_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `admin_and_users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `supplier_payments_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `supplier_payments_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `supplier_payment_invoices`
--
ALTER TABLE `supplier_payment_invoices`
  ADD CONSTRAINT `supplier_payment_invoices_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `supplier_payment_invoices_supplier_payment_id_foreign` FOREIGN KEY (`supplier_payment_id`) REFERENCES `supplier_payments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `supplier_payment_invoices_supplier_return_id_foreign` FOREIGN KEY (`supplier_return_id`) REFERENCES `purchase_returns` (`id`) ON DELETE CASCADE;

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
