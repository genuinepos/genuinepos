-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 17, 2021 at 02:38 PM
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
(2, 'Mr', 'Super', 'Admin', NULL, 'superadmin', 'superadmin@gamil.com', NULL, 1, NULL, 8, 1, NULL, 1, '$2y$10$rd3uLXbr7OXtcZAh5VAj1u.nHtBpy0.gZx5HYXJ1uSR/TpT/nVBai', '0.00', '0.00', NULL, NULL, NULL, NULL, NULL, 'default.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'en', NULL, NULL, '0.00', NULL, '2021-04-07 07:04:03', '2021-05-25 08:29:27');

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
(32, NULL, NULL, NULL, 2, NULL, '0.00', NULL, NULL, 1, NULL, '2021-07-17 12:26:45', '2021-07-17 12:26:45', NULL);

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
(136, 32, NULL, 2, 1, '100.00', '2021-07-17 12:26:45', '2021-07-17 12:26:45');

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
(18, 'Net Bill', 'NB-125', NULL, NULL);

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
(1, '{\"shop_name\":\"Premium Multi Trade.\",\"address\":\"Gausia Kasem Center 10\\/2 Arambagh (7th floor), Motijheel-1000\",\"phone\":\"01720444544\",\"email\":\"speeddigitinfo@gmail.com\",\"start_date\":\"07-04-2021\",\"default_profit\":0,\"currency\":\"\\u09f3\",\"currency_placement\":null,\"date_format\":\"dd-mm-yyyy\",\"financial_year_start\":\"Januaray\",\"time_format\":\"12\",\"business_logo\":\"60f2ccce79632-.png\",\"timezone\":\"Asia\\/Dhaka\"}', '{\"tax_1_name\":\"Tax\",\"tax_1_no\":\"1\",\"tax_2_name\":\"GST\",\"tax_2_no\":\"2\",\"is_tax_en_purchase_sale\":1}', '{\"product_code_prefix\":null,\"default_unit_id\":\"null\",\"is_enable_brands\":1,\"is_enable_categories\":1,\"is_enable_sub_categories\":1,\"is_enable_price_tax\":1,\"is_enable_warranty\":1}', '{\"default_sale_discount\":\"0.00\",\"default_tax_id\":\"null\",\"sales_cmsn_agnt\":\"select_form_cmsn_list\",\"default_price_group_id\":\"7\"}', '{\"is_disable_draft\":0,\"is_disable_quotation\":0,\"is_disable_challan\":0,\"is_disable_hold_invoice\":0,\"is_disable_multiple_pay\":1,\"is_show_recent_transactions\":0,\"is_disable_discount\":0,\"is_disable_order_tax\":0,\"is_show_credit_sale_button\":1,\"is_show_partial_sale_button\":1}', '{\"is_edit_pro_price\":0,\"is_enable_status\":1,\"is_enable_lot_no\":1}', '{\"view_stock_expiry_alert_for\":\"31\"}', '[]', '{\"purchase_invoice\":\"PI\",\"sale_invoice\":\"SI\",\"purchase_return\":\"PRI\",\"stock_transfer\":\"STI\",\"stock_djustment\":\"SAR\",\"sale_return\":\"SRI\",\"expenses\":\"EXI\",\"supplier_id\":\"SID\",\"customer_id\":null,\"purchase_payment\":\"PPI\",\"sale_payment\":\"SPI\",\"expanse_payment\":\"EXPI\"}', '[]', '[]', '{\"purchases\":1,\"add_sale\":1,\"pos\":1,\"transfer_stock\":1,\"stock_adjustment\":1,\"expenses\":1,\"accounting\":1,\"contacts\":1,\"hrms\":1,\"requisite\":1}', '{\"enable_cus_point\":1,\"point_display_name\":\"Reward Point\",\"amount_for_unit_rp\":\"10\",\"min_order_total_for_rp\":\"100\",\"max_rp_per_order\":\"\",\"redeem_amount_per_unit_rp\":\"0.10\",\"min_order_total_for_redeem\":\"\",\"min_redeem_point\":\"\",\"max_redeem_point\":\"\"}', 0, 0, 0, 0, 0, 0, 0, '50000000.00', NULL, '2021-07-17 12:27:58');

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
(1, 'Default layout', 1, 1, NULL, 1, 4, 1, 1, 0, 1, 1, NULL, NULL, NULL, 'Invoice/Bill', 'Quotation', 'Draft', 'Challan', 0, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 1, 1, 1, 0, 1, 1, 0, 0, 'If you need any support, Feel free to contact us. email: speeddigitinfo@gmail.com.', 0, 1, NULL, 'AL-ARAFA ISLAMI BANK Ltd.', 'Nawabpur', 'Speed Digit Pvt. Ltd', '0121020028467', 1, '2021-03-02 12:24:36', '2021-07-15 07:32:24'),
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
(9, 'test', '2', NULL, NULL, 1, '2021/', '2021-06-06 12:02:32', '2021-06-24 12:28:04');

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
(187, '2020_12_29_092109_create_stock_adjustment_products_table', 97);

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
(6, 'Wholesale Price', 'Only For wholesale.', 'Active', NULL, '2021-06-30 11:11:13'),
(7, 'Retail Price', 'Only for retail sale.', 'Active', NULL, '2021-06-30 11:11:15'),
(8, 'Special Price', NULL, 'Active', NULL, '2021-07-11 06:03:51'),
(9, 'Special Customer Price', NULL, 'Active', NULL, '2021-07-11 06:03:50');

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
(8, NULL, '{\"user_view\":1,\"user_add\":1,\"user_edit\":1,\"user_delete\":1}', '{\"role_view\":1,\"role_add\":1,\"role_edit\":1,\"role_delete\":1}', '{\"supplier_all\":1,\"supplier_add\":1,\"supplier_edit\":1,\"supplier_delete\":1}', '{\"customer_all\":1,\"customer_add\":1,\"customer_edit\":1,\"customer_delete\":1}', '{\"product_all\":1,\"product_add\":1,\"product_edit\":1,\"openingStock_add\":1,\"product_delete\":1,\"pro_unit_cost\":1}', '{\"purchase_all\":1,\"purchase_add\":1,\"purchase_edit\":1,\"purchase_delete\":1,\"purchase_payment\":1,\"purchase_return\":1,\"status_update\":1}', '{\"adjustment_all\":1,\"adjustment_add\":1,\"adjustment_delete\":1}', '{\"pos_all\":1,\"pos_add\":1,\"pos_edit\":1,\"pos_delete\":1,\"sale_access\":1,\"sale_draft\":1,\"sale_quotation\":1,\"sale_all_own\":1,\"sale_payment\":1,\"edit_price_sale_screen\":1,\"edit_price_pos_screen\":1,\"edit_discount_pos_screen\":1,\"edit_discount_sale_screen\":1,\"shipment_access\":1,\"return_access\":1}', '{\"register_view\":1,\"register_close\":1}', '{\"brand_all\":1,\"brand_add\":1,\"brand_edit\":1,\"brand_delete\":1}', '{\"category_all\":1,\"category_add\":1,\"category_edit\":1,\"category_delete\":1}', '{\"unit_all\":1,\"unit_add\":1,\"unit_edit\":1,\"unit_delete\":1}', '{\"loss_profit_report\":1,\"purchase_sale_report\":1,\"tax_report\":1,\"cus_sup_report\":1,\"stock_report\":1,\"stock_adjustment_report\":1,\"tranding_report\":1,\"item_report\":1,\"pro_purchase_report\":1,\"pro_sale_report\":1,\"purchase_payment_report\":1,\"sale_payment_report\":1,\"expanse_report\":1,\"register_report\":1,\"representative_report\":1}', '{\"tax\":1,\"branch\":1,\"warehouse\":1,\"g_settings\":1,\"p_settings\":1,\"inv_sc\":1,\"inv_lay\":1,\"barcode_settings\":1,\"cash_counters\":1}', '{\"dash_data\":1}', '{\"ac_access\":1}', '{\"leave_type\":1,\"view_own_leave\":1,\"leave_approve\":1,\"attendance_all\":1,\"view_own_attendance\":1,\"view_a_d\":1,\"department\":1,\"designation\":1}', '{\"assign_todo\":1,\"create_msg\":1,\"view_msg\":1}', '{\"menuf_view\":1,\"menuf_add\":1,\"menuf_edit\":1,\"menuf_delete\":1}', '{\"proj_view\":1,\"proj_create\":1,\"proj_edit\":1,\"proj_delete\":1}', '{\"ripe_add_invo\":1,\"ripe_edit_invo\":1,\"ripe_view_invo\":1,\"ripe_delete_invo\":1,\"change_invo_status\":1,\"ripe_jop_sheet_status\":1,\"ripe_jop_sheet_add\":1,\"ripe_jop_sheet_edit\":1,\"ripe_jop_sheet_delete\":1,\"ripe_only_assinged_job_sheet\":1,\"ripe_view_all_job_sheet\":1}', '{\"superadmin_access_pack_subscrip\":1}', '{\"e_com_sync_pro_cate\":1,\"e_com_sync_pro\":1,\"e_com_sync_order\":1,\"e_com_map_tax_rate\":1}', 1, '2021-01-26 10:45:14', '2021-01-26 10:45:14');

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
(8, 'Create a data base for our new project.', '2021/9211', 'Medium', 'On-Hold', '2021-07-07 18:00:00', 'Create a data base for our new project.', NULL, 2, '2021-07-07 18:00:00', '2021-07-10 11:51:31');

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
(3, 8, 2, 0, NULL, '2021-07-10 11:19:07');

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `bulk_variants`
--
ALTER TABLE `bulk_variants`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `bulk_variant_children`
--
ALTER TABLE `bulk_variant_children`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=163;

--
-- AUTO_INCREMENT for table `cash_registers`
--
ALTER TABLE `cash_registers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `cash_register_transactions`
--
ALTER TABLE `cash_register_transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=137;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `customer_groups`
--
ALTER TABLE `customer_groups`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `customer_ledgers`
--
ALTER TABLE `customer_ledgers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=371;

--
-- AUTO_INCREMENT for table `expanses`
--
ALTER TABLE `expanses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `expanse_categories`
--
ALTER TABLE `expanse_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `expanse_payments`
--
ALTER TABLE `expanse_payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `expense_descriptions`
--
ALTER TABLE `expense_descriptions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=188;

--
-- AUTO_INCREMENT for table `money_receipts`
--
ALTER TABLE `money_receipts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=207;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=205;

--
-- AUTO_INCREMENT for table `product_variants`
--
ALTER TABLE `product_variants`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `product_warehouses`
--
ALTER TABLE `product_warehouses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT for table `product_warehouse_variants`
--
ALTER TABLE `product_warehouse_variants`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `purchases`
--
ALTER TABLE `purchases`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=169;

--
-- AUTO_INCREMENT for table `purchase_payments`
--
ALTER TABLE `purchase_payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=164;

--
-- AUTO_INCREMENT for table `purchase_products`
--
ALTER TABLE `purchase_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=367;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `role_permissions`
--
ALTER TABLE `role_permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=337;

--
-- AUTO_INCREMENT for table `sale_payments`
--
ALTER TABLE `sale_payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=309;

--
-- AUTO_INCREMENT for table `sale_products`
--
ALTER TABLE `sale_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=678;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `stock_adjustment_products`
--
ALTER TABLE `stock_adjustment_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `supplier_ledgers`
--
ALTER TABLE `supplier_ledgers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=114;

--
-- AUTO_INCREMENT for table `supplier_products`
--
ALTER TABLE `supplier_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `transfer_stock_to_branch_products`
--
ALTER TABLE `transfer_stock_to_branch_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `warranties`
--
ALTER TABLE `warranties`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

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
