-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jul 15, 2024 at 07:01 AM
-- Server version: 5.7.33
-- PHP Version: 8.1.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pos_testtenant2`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounting_vouchers`
--

CREATE TABLE `accounting_vouchers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `voucher_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `voucher_type` tinyint(4) NOT NULL,
  `mode` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1=single_mode,2=multiple_mode',
  `reference` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sale_ref_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sale_return_ref_id` bigint(20) UNSIGNED DEFAULT NULL,
  `purchase_ref_id` bigint(20) UNSIGNED DEFAULT NULL,
  `purchase_return_ref_id` bigint(20) UNSIGNED DEFAULT NULL,
  `stock_adjustment_ref_id` bigint(20) UNSIGNED DEFAULT NULL,
  `payroll_ref_id` bigint(20) UNSIGNED DEFAULT NULL,
  `debit_total` decimal(22,2) NOT NULL DEFAULT '0.00',
  `credit_total` decimal(22,2) NOT NULL DEFAULT '0.00',
  `total_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_ts` timestamp NULL DEFAULT NULL,
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `is_transaction_details` tinyint(1) NOT NULL DEFAULT '0',
  `created_by_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `accounting_voucher_descriptions`
--

CREATE TABLE `accounting_voucher_descriptions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `accounting_voucher_id` bigint(20) UNSIGNED DEFAULT NULL,
  `account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `payment_method_id` bigint(20) UNSIGNED DEFAULT NULL,
  `transaction_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cheque_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cheque_serial_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cheque_issue_date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `note` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `is_delete_in_update` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `account_group_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_walk_in_customer` tinyint(1) NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_id` bigint(20) UNSIGNED DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `account_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_id` bigint(20) UNSIGNED DEFAULT NULL,
  `bank_branch` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_address` text COLLATE utf8mb4_unicode_ci,
  `tax_percent` decimal(22,2) DEFAULT NULL,
  `bank_code` text COLLATE utf8mb4_unicode_ci,
  `swift_code` text COLLATE utf8mb4_unicode_ci,
  `opening_balance` decimal(22,2) NOT NULL DEFAULT '0.00',
  `opening_balance_type` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remark` mediumtext COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_by_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_fixed` tinyint(1) DEFAULT NULL,
  `is_main_capital_account` tinyint(1) DEFAULT NULL,
  `is_main_pl_account` tinyint(1) DEFAULT NULL,
  `is_global` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `branch_id`, `account_group_id`, `is_walk_in_customer`, `name`, `phone`, `contact_id`, `address`, `account_number`, `bank_id`, `bank_branch`, `bank_address`, `tax_percent`, `bank_code`, `swift_code`, `opening_balance`, `opening_balance_type`, `remark`, `status`, `created_by_id`, `is_fixed`, `is_main_capital_account`, `is_main_pl_account`, `is_global`, `created_at`, `updated_at`) VALUES
(1, NULL, 4, 0, 'Cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', NULL, NULL, '0.00', 'dr', NULL, 1, 1, 1, NULL, NULL, 0, '2023-08-04 11:33:01', '2023-08-04 11:33:01'),
(2, NULL, 31, 0, 'Sales Ledger Account', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', NULL, NULL, '0.00', 'dr', NULL, 1, 1, 1, NULL, NULL, 0, '2023-08-06 06:02:13', '2023-08-06 06:02:13'),
(3, NULL, 16, 0, 'Tax@5%', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '5.00', NULL, NULL, '0.00', 'dr', NULL, 1, 1, NULL, NULL, NULL, 1, '2023-08-06 10:59:55', '2023-08-06 10:59:55'),
(4, NULL, 16, 0, 'Tax@8%', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '8.00', NULL, NULL, '0.00', 'dr', NULL, 1, 1, NULL, NULL, NULL, 1, '2023-08-06 11:00:18', '2023-08-06 11:00:18'),
(5, NULL, 27, 0, 'Purchase Ledger Account', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', NULL, NULL, '0.00', 'dr', NULL, 1, 1, NULL, NULL, NULL, 0, '2023-08-08 12:09:48', '2023-08-08 12:09:48'),
(6, NULL, 25, 0, 'Net Bill', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', NULL, NULL, '0.00', 'dr', NULL, 1, 1, NULL, NULL, NULL, 0, '2023-08-08 12:10:36', '2023-08-08 12:10:36'),
(7, NULL, 25, 0, 'Electricity Bill', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', NULL, NULL, '0.00', 'dr', NULL, 1, 1, NULL, NULL, NULL, 0, '2023-08-08 12:10:53', '2023-08-08 12:10:53'),
(8, NULL, 25, 0, 'Snacks Bill', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', NULL, NULL, '0.00', 'dr', NULL, 1, 1, NULL, NULL, NULL, 0, '2023-08-08 12:11:16', '2023-08-08 12:11:16'),
(9, NULL, 25, 0, 'Roll Pages', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', NULL, NULL, '0.00', 'dr', NULL, 1, 1, NULL, NULL, NULL, 0, '2023-08-08 12:11:59', '2023-08-08 12:11:59'),
(10, NULL, 29, 0, 'Sale Damage Goods', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', NULL, NULL, '0.00', 'dr', NULL, 1, 1, NULL, NULL, NULL, 0, '2023-08-08 12:12:33', '2023-08-08 12:12:33'),
(11, NULL, 25, 0, 'Lost/Damage Stock', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', NULL, NULL, '0.00', 'dr', NULL, 1, 1, NULL, NULL, NULL, 0, '2023-08-08 12:13:13', '2023-08-08 12:13:13'),
(12, NULL, 8, 1, 'Walk-In-Customer', '0', NULL, NULL, NULL, NULL, NULL, NULL, '0.00', NULL, NULL, '0.00', 'dr', NULL, 1, 1, NULL, NULL, NULL, 0, '2023-08-08 12:13:13', '2023-08-08 12:13:13'),
(13, NULL, 13, 0, 'Capital Account', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.00', NULL, NULL, '0.00', 'dr', NULL, 1, 1, NULL, 1, NULL, 1, '2023-08-08 12:14:40', '2023-08-08 12:14:40');

-- --------------------------------------------------------

--
-- Table structure for table `account_groups`
--

CREATE TABLE `account_groups` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sorting_number` bigint(20) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent_group_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_reserved` tinyint(1) NOT NULL DEFAULT '0',
  `is_allowed_bank_details` tinyint(1) NOT NULL DEFAULT '0',
  `is_bank_or_cash_ac` tinyint(1) NOT NULL DEFAULT '0',
  `is_fixed_tax_calculator` tinyint(1) NOT NULL DEFAULT '0',
  `is_default_tax_calculator` tinyint(1) NOT NULL DEFAULT '0',
  `is_main_group` tinyint(1) NOT NULL DEFAULT '0',
  `is_sub_group` tinyint(1) NOT NULL DEFAULT '0',
  `is_parent_sub_group` tinyint(1) NOT NULL DEFAULT '0',
  `is_sub_sub_group` tinyint(1) NOT NULL DEFAULT '0',
  `is_parent_sub_sub_group` tinyint(1) NOT NULL DEFAULT '0',
  `main_group_number` int(11) DEFAULT NULL,
  `sub_group_number` int(11) DEFAULT NULL,
  `sub_sub_group_number` int(11) DEFAULT NULL,
  `main_group_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sub_group_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sub_sub_group_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `default_balance_type` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_global` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `account_groups`
--

INSERT INTO `account_groups` (`id`, `sorting_number`, `name`, `parent_group_id`, `is_reserved`, `is_allowed_bank_details`, `is_bank_or_cash_ac`, `is_fixed_tax_calculator`, `is_default_tax_calculator`, `is_main_group`, `is_sub_group`, `is_parent_sub_group`, `is_sub_sub_group`, `is_parent_sub_sub_group`, `main_group_number`, `sub_group_number`, `sub_sub_group_number`, `main_group_name`, `sub_group_name`, `sub_sub_group_name`, `default_balance_type`, `created_at`, `updated_at`, `is_global`) VALUES
(1, 0, 'Assets', NULL, 1, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1, NULL, NULL, 'Assets', NULL, NULL, 'dr', NULL, NULL, 0),
(2, 1, 'Current Assets', 1, 1, 0, 0, 0, 0, 0, 1, 1, 0, 0, 1, 1, NULL, 'Assets', 'Current Assets', NULL, 'dr', NULL, '2022-11-27 15:40:53', 0),
(3, 3, 'Bank Accounts', 2, 1, 1, 1, 0, 0, 0, 0, 0, 1, 1, 1, 1, 1, 'Assets', 'Current Assets', 'Bank Accounts', 'dr', NULL, '2022-11-27 19:59:22', 0),
(4, 2, 'Cash-In-Hand', 2, 1, 0, 1, 0, 0, 0, 0, 0, 1, 1, 1, 1, 2, 'Assets', 'Current Assets', 'Cash-In-Hand', 'dr', NULL, '2022-11-27 19:59:29', 0),
(5, 4, 'Deposits (Asset)', 2, 1, 0, 0, 0, 0, 0, 0, 0, 1, 1, 1, 1, 3, 'Assets', 'Current Assets', 'Deposits (Asset)', 'dr', NULL, '2022-11-26 20:29:03', 0),
(6, 5, 'Loan & Advance (Asset)', 2, 1, 0, 0, 0, 0, 0, 0, 0, 1, 1, 1, 1, 4, 'Assets', 'Current Assets', 'Loan & Advance (Asset)', 'dr', NULL, '2022-11-26 19:30:20', 0),
(7, 6, 'Stock-In-Hand', 2, 1, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, 1, 5, 'Assets', 'Current Assets', 'Stock-In-Hand', 'dr', NULL, '2022-11-26 20:13:12', 0),
(8, 7, 'Account Receivable', 2, 1, 0, 0, 0, 0, 0, 0, 0, 1, 1, 1, 1, 6, 'Assets', 'Current Assets', 'Sundry Debtors', 'dr', NULL, '2023-01-04 02:47:14', 0),
(9, 8, 'Fixed Assets', 1, 1, 0, 0, 0, 0, 0, 1, 1, 0, 0, 1, 2, NULL, 'Assets', 'Fixed Assets', NULL, 'dr', NULL, NULL, 0),
(10, 9, 'Investments', 1, 0, 0, 0, 0, 0, 0, 1, 1, 0, 0, 1, 3, NULL, 'Assets', 'Investments', NULL, 'dr', NULL, NULL, 0),
(11, 10, 'Liabilities', NULL, 1, 0, 0, 0, 0, 1, 0, 0, 0, 0, 2, NULL, NULL, 'Liabilities', NULL, NULL, 'cr', NULL, NULL, 0),
(12, 11, 'Branch / Divisions', 11, 1, 0, 0, 0, 0, 0, 1, 1, 0, 0, 2, 5, NULL, 'Liabilities', 'Branch / Divisions', NULL, 'cr', NULL, NULL, 0),
(13, 12, 'Capital Account', 11, 1, 0, 0, 0, 0, 0, 1, 1, 0, 0, 2, 6, NULL, 'Liabilities', 'Capital Account', NULL, 'cr', NULL, NULL, 1),
(15, 14, 'Current Liabilities', 11, 1, 0, 0, 0, 0, 0, 1, 1, 0, 0, 2, 7, NULL, 'Liabilities', 'Current Liabilities', NULL, 'cr', NULL, NULL, 0),
(16, 15, 'Duties & Taxes', 15, 1, 0, 0, 1, 1, 0, 0, 0, 1, 1, 2, 7, 8, 'Liabilities', 'Current Liabilities', 'Duties & Taxes', 'cr', NULL, '2022-11-26 20:17:19', 1),
(17, 16, 'Provisions', 15, 1, 0, 0, 0, 0, 0, 0, 0, 1, 1, 2, 7, 9, 'Liabilities', 'Current Liabilities', 'Provisions', 'cr', NULL, NULL, 0),
(18, 17, 'Account Payable', 15, 1, 0, 0, 0, 0, 0, 0, 0, 1, 1, 2, 7, 10, 'Liabilities', 'Current Liabilities', 'Sundry Creditors', 'cr', NULL, '2023-01-04 02:47:27', 1),
(19, 18, 'Loans (Liability)', 11, 1, 0, 0, 0, 0, 0, 1, 1, 0, 0, 2, 8, NULL, 'Liabilities', 'Loans (Liability)', NULL, 'cr', NULL, '2022-11-26 20:30:51', 0),
(20, 19, 'Bank OD A/c', 19, 1, 1, 1, 0, 0, 0, 0, 0, 1, 1, 2, 8, 11, 'Liabilities', 'Loans (Liability)', 'Bank OD A/c', 'cr', NULL, '2022-11-26 19:33:14', 0),
(21, 20, 'Secure Loans', 19, 1, 0, 0, 0, 0, 0, 0, 0, 1, 1, 2, 8, 12, 'Liabilities', 'Loans (Liability)', 'Secure Loans', 'cr', NULL, '2022-11-27 15:41:04', 0),
(22, 21, 'Unsecure Loans', 19, 1, 0, 0, 0, 0, 0, 0, 0, 1, 1, 2, 8, 13, 'Liabilities', 'Loans (Liability)', 'Unsecure Loans', 'cr', NULL, NULL, 0),
(23, 22, 'Suspense A/c', 11, 1, 0, 0, 0, 0, 0, 1, 1, 0, 0, 2, 9, NULL, 'Liabilities', 'Suspense', NULL, 'cr', NULL, NULL, 0),
(24, 23, 'Expenses', NULL, 1, 0, 0, 0, 0, 1, 0, 0, 0, 0, 3, NULL, NULL, 'Expenses', NULL, NULL, 'dr', NULL, NULL, 0),
(25, 24, 'Direct Expenses', 24, 1, 0, 0, 0, 0, 0, 1, 1, 0, 0, 3, 10, NULL, 'Expenses', 'Direct Expenses', NULL, 'dr', NULL, '2022-11-26 19:26:55', 0),
(26, 25, 'Indirect Expenses', 24, 1, 0, 0, 0, 0, 0, 1, 0, 0, 0, 3, 11, NULL, 'Expenses', 'Indirect Expenses', NULL, 'dr', NULL, NULL, 0),
(27, 26, 'Purchase Accounts', 24, 1, 0, 0, 0, 0, 0, 1, 1, 0, 0, 3, 12, NULL, 'Expenses', 'Purchase Accounts', NULL, 'dr', NULL, '2022-11-26 19:22:02', 0),
(28, 27, 'Incomes', NULL, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 4, NULL, NULL, 'Incomes', NULL, NULL, 'cr', NULL, NULL, 0),
(29, 28, 'Direct Incomes', 28, 1, 0, 0, 0, 0, 0, 1, 1, 0, 0, 4, 13, NULL, 'Incomes', 'Direct Incomes', NULL, 'cr', NULL, '2022-11-26 19:27:52', 0),
(30, 29, 'Indirect Incomes', 28, 1, 0, 0, 0, 0, 0, 1, 1, 0, 0, 4, 14, NULL, 'Incomes', 'Indirect Incomes', NULL, 'cr', NULL, '2022-11-26 19:29:13', 0),
(31, 30, 'Sales Accounts', 28, 1, 0, 0, 0, 0, 0, 1, 1, 0, 0, 4, 15, NULL, 'Incomes', 'Sales Accounts', NULL, 'cr', NULL, '2022-11-26 20:30:24', 0);

-- --------------------------------------------------------

--
-- Table structure for table `account_ledgers`
--

CREATE TABLE `account_ledgers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `date` timestamp NULL DEFAULT NULL,
  `voucher_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sale_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sale_product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sale_return_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sale_return_product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `purchase_id` bigint(20) UNSIGNED DEFAULT NULL,
  `purchase_product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `purchase_return_id` bigint(20) UNSIGNED DEFAULT NULL,
  `purchase_return_product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `adjustment_id` bigint(20) UNSIGNED DEFAULT NULL,
  `payroll_id` bigint(20) UNSIGNED DEFAULT NULL,
  `loan_id` bigint(20) UNSIGNED DEFAULT NULL,
  `loan_payment_id` bigint(20) UNSIGNED DEFAULT NULL,
  `voucher_description_id` bigint(20) UNSIGNED DEFAULT NULL,
  `debit` decimal(22,2) NOT NULL DEFAULT '0.00',
  `credit` decimal(22,2) NOT NULL DEFAULT '0.00',
  `running_balance` decimal(22,2) NOT NULL DEFAULT '0.00',
  `amount_type` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'debit/credit',
  `is_cash_flow` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `account_opening_balances`
--

CREATE TABLE `account_opening_balances` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `account_id` bigint(20) UNSIGNED NOT NULL,
  `opening_balance` decimal(22,2) NOT NULL DEFAULT '0.00',
  `opening_balance_type` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `advertisements`
--

CREATE TABLE `advertisements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `content_type` tinyint(4) NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `advertise_attachments`
--

CREATE TABLE `advertise_attachments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `advertisement_id` bigint(20) UNSIGNED NOT NULL,
  `content_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `caption` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `video` text COLLATE utf8mb4_unicode_ci,
  `is_delete_in_update` tinyint(1) NOT NULL DEFAULT '0',
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
  `is_delete_in_update` tinyint(1) DEFAULT '0',
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
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bank_access_branches`
--

CREATE TABLE `bank_access_branches` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED NOT NULL,
  `bank_account_id` bigint(20) UNSIGNED NOT NULL,
  `is_delete_in_update` tinyint(1) NOT NULL DEFAULT '0',
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
  `description` mediumtext COLLATE utf8mb4_unicode_ci,
  `is_continuous` tinyint(1) NOT NULL DEFAULT '0',
  `top_margin` double(22,4) NOT NULL DEFAULT '0.0000',
  `left_margin` double(22,4) NOT NULL DEFAULT '0.0000',
  `sticker_width` double(22,4) NOT NULL DEFAULT '0.0000',
  `sticker_height` double(22,4) NOT NULL DEFAULT '0.0000',
  `paper_width` double(22,4) NOT NULL DEFAULT '0.0000',
  `paper_height` double(22,4) NOT NULL DEFAULT '0.0000',
  `row_distance` double(22,4) NOT NULL DEFAULT '0.0000',
  `column_distance` double(22,4) NOT NULL DEFAULT '0.0000',
  `stickers_in_a_row` bigint(20) NOT NULL DEFAULT '0',
  `stickers_in_one_sheet` bigint(20) NOT NULL DEFAULT '0',
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `is_fixed` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `barcode_settings`
--

INSERT INTO `barcode_settings` (`id`, `name`, `description`, `is_continuous`, `top_margin`, `left_margin`, `sticker_width`, `sticker_height`, `paper_width`, `paper_height`, `row_distance`, `column_distance`, `stickers_in_a_row`, `stickers_in_one_sheet`, `is_default`, `is_fixed`, `created_at`, `updated_at`) VALUES
(1, 'Sticker Print, Continuous feed or rolls , Barcode Size: 38mm X 25mm', NULL, 1, 0.0000, 0.0000, 2.0000, 0.5000, 1.8000, 0.9843, 0.0000, 0.0000, 1, 1, 0, 1, NULL, '2022-12-05 04:50:05'),
(2, 'Bulk - A4 Page', NULL, 0, 0.2000, 0.0000, 1.5000, 0.8000, 8.0000, 11.0000, 0.2000, 0.2000, 1, 1, 0, 1, NULL, '2022-12-05 04:50:05');

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

CREATE TABLE `branches` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `parent_branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `branch_type` tinyint(4) NOT NULL DEFAULT '1',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `area_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `branch_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zip_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alternate_phone_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bin` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tin` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `purchase_permission` tinyint(1) NOT NULL DEFAULT '1',
  `expire_date` date DEFAULT NULL,
  `shop_expire_date_history_id` bigint(20) UNSIGNED DEFAULT NULL,
  `current_price_period` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bulk_variants`
--

CREATE TABLE `bulk_variants` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by_id` bigint(20) UNSIGNED DEFAULT NULL,
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
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_delete_in_update` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

--
-- Dumping data for table `cash_counters`
--

INSERT INTO `cash_counters` (`id`, `branch_id`, `counter_name`, `short_name`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Cash Counter 1', 'CN1', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cash_registers`
--

CREATE TABLE `cash_registers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sale_account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `cash_counter_id` bigint(20) UNSIGNED DEFAULT NULL,
  `cash_account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `opening_cash` decimal(22,2) NOT NULL DEFAULT '0.00',
  `date` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `closed_at` timestamp NULL DEFAULT NULL,
  `closing_cash` decimal(22,2) NOT NULL DEFAULT '0.00',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1=open;0=closed;',
  `closing_note` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cash_register_transactions`
--

CREATE TABLE `cash_register_transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `cash_register_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sale_id` bigint(20) UNSIGNED DEFAULT NULL,
  `voucher_description_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sale_ref_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `parent_category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
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
  `quantity` decimal(22,2) DEFAULT '0.00',
  `product_variant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `delete_in_update` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `communication_contacts`
--

CREATE TABLE `communication_contacts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `communication_contact_group_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `whatsapp_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mailing_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `communication_contact_groups`
--

CREATE TABLE `communication_contact_groups` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `type` tinyint(4) NOT NULL,
  `contact_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_group_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `business_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alternative_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `landline` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_of_birth` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tax_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `opening_balance` decimal(22,2) NOT NULL DEFAULT '0.00',
  `opening_balance_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `credit_limit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pay_term_number` tinyint(4) DEFAULT NULL,
  `pay_term` tinyint(4) DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `shipping_address` text COLLATE utf8mb4_unicode_ci,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zip_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `is_fixed` tinyint(1) NOT NULL DEFAULT '0',
  `is_chain_shop_contact` tinyint(1) NOT NULL DEFAULT '0',
  `reward_point` decimal(8,2) NOT NULL DEFAULT '0.00',
  `prefix` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contact_credit_limits`
--

CREATE TABLE `contact_credit_limits` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `contact_id` bigint(20) UNSIGNED DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by_id` bigint(20) UNSIGNED DEFAULT NULL,
  `credit_limit` decimal(22,2) DEFAULT NULL,
  `pay_term` tinyint(4) DEFAULT NULL COMMENT '1=months,2=days',
  `pay_term_number` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `currencies`
--

CREATE TABLE `currencies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `country` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `symbol` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `thousand_separator` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `decimal_separator` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dialing_code` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_rate` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `currencies`
--

INSERT INTO `currencies` (`id`, `country`, `currency`, `code`, `symbol`, `thousand_separator`, `decimal_separator`, `dialing_code`, `currency_rate`, `created_at`, `updated_at`) VALUES
(1, 'Albania', 'Leke', 'ALL', 'Lek', ',', '.', NULL, NULL, NULL, NULL),
(2, 'America', 'Dollars', 'USD', '$', ',', '.', NULL, NULL, NULL, NULL),
(3, 'Afghanistan', 'Afghanis', 'AF', '؋', ',', '.', NULL, NULL, NULL, NULL),
(4, 'Argentina', 'Pesos', 'ARS', '$', ',', '.', NULL, NULL, NULL, NULL),
(5, 'Aruba', 'Guilders', 'AWG', 'ƒ', ',', '.', NULL, NULL, NULL, NULL),
(6, 'Australia', 'Dollars', 'AUD', '$', ',', '.', NULL, NULL, NULL, NULL),
(7, 'Azerbaijan', 'New Manats', 'AZ', 'ман', ',', '.', NULL, NULL, NULL, NULL),
(8, 'Bahamas', 'Dollars', 'BSD', '$', ',', '.', NULL, NULL, NULL, NULL),
(9, 'Barbados', 'Dollars', 'BBD', '$', ',', '.', NULL, NULL, NULL, NULL),
(10, 'Belarus', 'Rubles', 'BYR', 'p.', ',', '.', NULL, NULL, NULL, NULL),
(11, 'Belgium', 'Euro', 'EUR', '€', ',', '.', NULL, NULL, NULL, NULL),
(12, 'Beliz', 'Dollars', 'BZD', 'BZ$', ',', '.', NULL, NULL, NULL, NULL),
(13, 'Bermuda', 'Dollars', 'BMD', '$', ',', '.', NULL, NULL, NULL, NULL),
(14, 'Bolivia', 'Bolivianos', 'BOB', '$b', ',', '.', NULL, NULL, NULL, NULL),
(15, 'Bosnia and Herzegovina', 'Convertible Marka', 'BAM', 'KM', ',', '.', NULL, NULL, NULL, NULL),
(16, 'Botswana', 'Pula''s', 'BWP', 'P', ',', '.', NULL, NULL, NULL, NULL),
(17, 'Bulgaria', 'Leva', 'BG', 'лв', ',', '.', NULL, NULL, NULL, NULL),
(18, 'Brazil', 'Reais', 'BRL', 'R$', ',', '.', NULL, NULL, NULL, NULL),
(19, 'Britain [United Kingdom]', 'Pounds', 'GBP', '£', ',', '.', NULL, NULL, NULL, NULL),
(20, 'Brunei Darussalam', 'Dollars', 'BND', '$', ',', '.', NULL, NULL, NULL, NULL),
(21, 'Cambodia', 'Riels', 'KHR', '៛', ',', '.', NULL, NULL, NULL, NULL),
(22, 'Canada', 'Dollars', 'CAD', '$', ',', '.', NULL, NULL, NULL, NULL),
(23, 'Cayman Islands', 'Dollars', 'KYD', '$', ',', '.', NULL, NULL, NULL, NULL),
(24, 'Chile', 'Pesos', 'CLP', '$', ',', '.', NULL, NULL, NULL, NULL),
(25, 'China', 'Yuan Renminbi', 'CNY', '¥', ',', '.', NULL, NULL, NULL, NULL),
(26, 'Colombia', 'Pesos', 'COP', '$', ',', '.', NULL, NULL, NULL, NULL),
(27, 'Costa Rica', 'Colón', 'CRC', '₡', ',', '.', NULL, NULL, NULL, NULL),
(28, 'Croatia', 'Kuna', 'HRK', 'kn', ',', '.', NULL, NULL, NULL, NULL),
(29, 'Cuba', 'Pesos', 'CUP', '₱', ',', '.', NULL, NULL, NULL, NULL),
(30, 'Cyprus', 'Euro', 'EUR', '€', '.', ',', NULL, NULL, NULL, NULL),
(31, 'Czech Republic', 'Koruny', 'CZK', 'Kč', ',', '.', NULL, NULL, NULL, NULL),
(32, 'Denmark', 'Kroner', 'DKK', 'kr', ',', '.', NULL, NULL, NULL, NULL),
(33, 'Dominican Republic', 'Pesos', 'DOP ', 'RD$', ',', '.', NULL, NULL, NULL, NULL),
(34, 'East Caribbean', 'Dollars', 'XCD', '$', ',', '.', NULL, NULL, NULL, NULL),
(35, 'Egypt', 'Pounds', 'EGP', '£', ',', '.', NULL, NULL, NULL, NULL),
(36, 'El Salvador', 'Colones', 'SVC', '$', ',', '.', NULL, NULL, NULL, NULL),
(37, 'England [United Kingdom]', 'Pounds', 'GBP', '£', ',', '.', NULL, NULL, NULL, NULL),
(38, 'Euro', 'Euro', 'EUR', '€', '.', ',', NULL, NULL, NULL, NULL),
(39, 'Falkland Islands', 'Pounds', 'FKP', '£', ',', '.', NULL, NULL, NULL, NULL),
(40, 'Fiji', 'Dollars', 'FJD', '$', ',', '.', NULL, NULL, NULL, NULL),
(41, 'France', 'Euro', 'EUR', '€', '.', ',', NULL, NULL, NULL, NULL),
(42, 'Ghana', 'Cedis', 'GHC', '¢', ',', '.', NULL, NULL, NULL, NULL),
(43, 'Gibraltar', 'Pounds', 'GIP', '£', ',', '.', NULL, NULL, NULL, NULL),
(44, 'Greece', 'Euro', 'EUR', '€', '.', ',', NULL, NULL, NULL, NULL),
(45, 'Guatemala', 'Quetzales', 'GTQ', 'Q', ',', '.', NULL, NULL, NULL, NULL),
(46, 'Guernsey', 'Pounds', 'GGP', '£', ',', '.', NULL, NULL, NULL, NULL),
(47, 'Guyana', 'Dollars', 'GYD', '$', ',', '.', NULL, NULL, NULL, NULL),
(48, 'Holland [Netherlands]', 'Euro', 'EUR', '€', '.', ',', NULL, NULL, NULL, NULL),
(49, 'Honduras', 'Lempiras', 'HNL', 'L', ',', '.', NULL, NULL, NULL, NULL),
(50, 'Hong Kong', 'Dollars', 'HKD', '$', ',', '.', NULL, NULL, NULL, NULL),
(51, 'Hungary', 'Forint', 'HUF', 'Ft', ',', '.', NULL, NULL, NULL, NULL),
(52, 'Iceland', 'Kronur', 'ISK', 'kr', ',', '.', NULL, NULL, NULL, NULL),
(53, 'India', 'Rupees', 'INR', '₹', ',', '.', NULL, NULL, NULL, NULL),
(54, 'Indonesia', 'Rupiahs', 'IDR', 'Rp', ',', '.', NULL, NULL, NULL, NULL),
(55, 'Iran', 'Rials', 'IRR', '﷼', ',', '.', NULL, NULL, NULL, NULL),
(56, 'Ireland', 'Euro', 'EUR', '€', '.', ',', NULL, NULL, NULL, NULL),
(57, 'Isle of Man', 'Pounds', 'IMP', '£', ',', '.', NULL, NULL, NULL, NULL),
(58, 'Israel', 'New Shekels', 'ILS', '₪', ',', '.', NULL, NULL, NULL, NULL),
(59, 'Italy', 'Euro', 'EUR', '€', '.', ',', NULL, NULL, NULL, NULL),
(60, 'Jamaica', 'Dollars', 'JMD', 'J$', ',', '.', NULL, NULL, NULL, NULL),
(61, 'Japan', 'Yen', 'JPY', '¥', ',', '.', NULL, NULL, NULL, NULL),
(62, 'Jersey', 'Pounds', 'JEP', '£', ',', '.', NULL, NULL, NULL, NULL),
(63, 'Kazakhstan', 'Tenge', 'KZT', 'лв', ',', '.', NULL, NULL, NULL, NULL),
(64, 'Korea [North]', 'Won', 'KPW', '₩', ',', '.', NULL, NULL, NULL, NULL),
(65, 'Korea [South]', 'Won', 'KRW', '₩', ',', '.', NULL, NULL, NULL, NULL),
(66, 'Kyrgyzstan', 'Soms', 'KGS', 'лв', ',', '.', NULL, NULL, NULL, NULL),
(67, 'Laos', 'Kips', 'LAK', '₭', ',', '.', NULL, NULL, NULL, NULL),
(68, 'Latvia', 'Lati', 'LVL', 'Ls', ',', '.', NULL, NULL, NULL, NULL),
(69, 'Lebanon', 'Pounds', 'LBP', '£', ',', '.', NULL, NULL, NULL, NULL),
(70, 'Liberia', 'Dollars', 'LRD', '$', ',', '.', NULL, NULL, NULL, NULL),
(71, 'Liechtenstein', 'Switzerland Francs', 'CHF', 'CHF', ',', '.', NULL, NULL, NULL, NULL),
(72, 'Lithuania', 'Litai', 'LTL', 'Lt', ',', '.', NULL, NULL, NULL, NULL),
(73, 'Luxembourg', 'Euro', 'EUR', '€', '.', ',', NULL, NULL, NULL, NULL),
(74, 'Macedonia', 'Denars', 'MKD', 'ден', ',', '.', NULL, NULL, NULL, NULL),
(75, 'Malaysia', 'Ringgits', 'MYR', 'RM', ',', '.', NULL, NULL, NULL, NULL),
(76, 'Malta', 'Euro', 'EUR', '€', '.', ',', NULL, NULL, NULL, NULL),
(77, 'Mauritius', 'Rupees', 'MUR', '₨', ',', '.', NULL, NULL, NULL, NULL),
(78, 'Mexico', 'Pesos', 'MXN', '$', ',', '.', NULL, NULL, NULL, NULL),
(79, 'Mongolia', 'Tugriks', 'MNT', '₮', ',', '.', NULL, NULL, NULL, NULL),
(80, 'Mozambique', 'Meticais', 'MZ', 'MT', ',', '.', NULL, NULL, NULL, NULL),
(81, 'Namibia', 'Dollars', 'NAD', '$', ',', '.', NULL, NULL, NULL, NULL),
(82, 'Nepal', 'Rupees', 'NPR', '₨', ',', '.', NULL, NULL, NULL, NULL),
(83, 'Netherlands Antilles', 'Guilders', 'ANG', 'ƒ', ',', '.', NULL, NULL, NULL, NULL),
(84, 'Netherlands', 'Euro', 'EUR', '€', '.', ',', NULL, NULL, NULL, NULL),
(85, 'New Zealand', 'Dollars', 'NZD', '$', ',', '.', NULL, NULL, NULL, NULL),
(86, 'Nicaragua', 'Cordobas', 'NIO', 'C$', ',', '.', NULL, NULL, NULL, NULL),
(87, 'Nigeria', 'Nairas', 'NG', '₦', ',', '.', NULL, NULL, NULL, NULL),
(88, 'North Korea', 'Won', 'KPW', '₩', ',', '.', NULL, NULL, NULL, NULL),
(89, 'Norway', 'Krone', 'NOK', 'kr', ',', '.', NULL, NULL, NULL, NULL),
(90, 'Oman', 'Rials', 'OMR', '﷼', ',', '.', NULL, NULL, NULL, NULL),
(91, 'Pakistan', 'Rupees', 'PKR', '₨', ',', '.', NULL, NULL, NULL, NULL),
(92, 'Panama', 'Balboa', 'PAB', 'B/.', ',', '.', NULL, NULL, NULL, NULL),
(93, 'Paraguay', 'Guarani', 'PYG', 'Gs', ',', '.', NULL, NULL, NULL, NULL),
(94, 'Peru', 'Nuevos Soles', 'PE', 'S/.', ',', '.', NULL, NULL, NULL, NULL),
(95, 'Philippines', 'Pesos', 'PHP', 'Php', ',', '.', NULL, NULL, NULL, NULL),
(96, 'Poland', 'Zlotych', 'PL', 'zł', ',', '.', NULL, NULL, NULL, NULL),
(97, 'Qatar', 'Rials', 'QAR', '﷼', ',', '.', NULL, NULL, NULL, NULL),
(98, 'Romania', 'New Lei', 'RO', 'lei', ',', '.', NULL, NULL, NULL, NULL),
(99, 'Russia', 'Rubles', 'RUB', 'руб', ',', '.', NULL, NULL, NULL, NULL),
(100, 'Saint Helena', 'Pounds', 'SHP', '£', ',', '.', NULL, NULL, NULL, NULL),
(101, 'Saudi Arabia', 'Riyals', 'SAR', '﷼', ',', '.', NULL, NULL, NULL, NULL),
(102, 'Serbia', 'Dinars', 'RSD', 'Дин.', ',', '.', NULL, NULL, NULL, NULL),
(103, 'Seychelles', 'Rupees', 'SCR', '₨', ',', '.', NULL, NULL, NULL, NULL),
(104, 'Singapore', 'Dollars', 'SGD', '$', ',', '.', NULL, NULL, NULL, NULL),
(105, 'Slovenia', 'Euro', 'EUR', '€', '.', ',', NULL, NULL, NULL, NULL),
(106, 'Solomon Islands', 'Dollars', 'SBD', '$', ',', '.', NULL, NULL, NULL, NULL),
(107, 'Somalia', 'Shillings', 'SOS', 'S', ',', '.', NULL, NULL, NULL, NULL),
(108, 'South Africa', 'Rand', 'ZAR', 'R', ',', '.', NULL, NULL, NULL, NULL),
(109, 'South Korea', 'Won', 'KRW', '₩', ',', '.', NULL, NULL, NULL, NULL),
(110, 'Spain', 'Euro', 'EUR', '€', '.', ',', NULL, NULL, NULL, NULL),
(111, 'Sri Lanka', 'Rupees', 'LKR', '₨', ',', '.', NULL, NULL, NULL, NULL),
(112, 'Sweden', 'Kronor', 'SEK', 'kr', ',', '.', NULL, NULL, NULL, NULL),
(113, 'Switzerland', 'Francs', 'CHF', 'CHF', ',', '.', NULL, NULL, NULL, NULL),
(114, 'Suriname', 'Dollars', 'SRD', '$', ',', '.', NULL, NULL, NULL, NULL),
(115, 'Syria', 'Pounds', 'SYP', '£', ',', '.', NULL, NULL, NULL, NULL),
(116, 'Taiwan', 'New Dollars', 'TWD', 'NT$', ',', '.', NULL, NULL, NULL, NULL),
(117, 'Thailand', 'Baht', 'THB', '฿', ',', '.', NULL, NULL, NULL, NULL),
(118, 'Trinidad and Tobago', 'Dollars', 'TTD', 'TT$', ',', '.', NULL, NULL, NULL, NULL),
(119, 'Turkey', 'Lira', 'TRY', 'TL', ',', '.', NULL, NULL, NULL, NULL),
(120, 'Turkey', 'Liras', 'TRL', '£', ',', '.', NULL, NULL, NULL, NULL),
(121, 'Tuvalu', 'Dollars', 'TVD', '$', ',', '.', NULL, NULL, NULL, NULL),
(122, 'Ukraine', 'Hryvnia', 'UAH', '₴', ',', '.', NULL, NULL, NULL, NULL),
(123, 'United Kingdom', 'Pounds', 'GBP', '£', ',', '.', NULL, NULL, NULL, NULL),
(124, 'United States of America', 'Dollars', 'USD', '$', ',', '.', NULL, NULL, NULL, NULL),
(125, 'Uruguay', 'Pesos', 'UYU', '$U', ',', '.', NULL, NULL, NULL, NULL),
(126, 'Uzbekistan', 'Sums', 'UZS', 'лв', ',', '.', NULL, NULL, NULL, NULL),
(127, 'Vatican City', 'Euro', 'EUR', '€', '.', ',', NULL, NULL, NULL, NULL),
(128, 'Venezuela', 'Bolivares Fuertes', 'VEF', 'Bs', ',', '.', NULL, NULL, NULL, NULL),
(129, 'Vietnam', 'Dong', 'VND', '₫', ',', '.', NULL, NULL, NULL, NULL),
(130, 'Yemen', 'Rials', 'YER', '﷼', ',', '.', NULL, NULL, NULL, NULL),
(131, 'Zimbabwe', 'Zimbabwe Dollars', 'ZWD', 'Z$', ',', '.', NULL, NULL, NULL, NULL),
(132, 'Iraq', 'Iraqi dinar', 'IQD', 'د.ع', ',', '.', NULL, NULL, NULL, NULL),
(133, 'Kenya', 'Kenyan shilling', 'KES', 'KSh', ',', '.', NULL, NULL, NULL, NULL),
(134, 'Bangladesh', 'Taka', 'BDT', 'TK.', ',', '.', NULL, NULL, NULL, NULL),
(135, 'Algerie', 'Algerian dinar', 'DZD', 'د.ج', ' ', '.', NULL, NULL, NULL, NULL),
(136, 'United Arab Emirates', 'United Arab Emirates dirham', 'AED', 'د.إ', ',', '.', NULL, NULL, NULL, NULL),
(137, 'Uganda', 'Uganda shillings', 'UGX', 'USh', ',', '.', NULL, NULL, NULL, NULL),
(138, 'Tanzania', 'Tanzanian shilling', 'TZS', 'TSh', ',', '.', NULL, NULL, NULL, NULL),
(139, 'Angola', 'Kwanza', 'AOA', 'Kz', ',', '.', NULL, NULL, NULL, NULL),
(140, 'Kuwait', 'Kuwaiti dinar', 'KWD', 'KD', ',', '.', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `customer_groups`
--

CREATE TABLE `customer_groups` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price_calculation_type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1=percentage,2=selling_price_group',
  `calculation_percentage` decimal(22,2) NOT NULL DEFAULT '0.00',
  `price_group_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `day_books`
--

CREATE TABLE `day_books` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `date_ts` timestamp NULL DEFAULT NULL,
  `voucher_type` tinyint(4) DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sale_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sale_return_id` bigint(20) UNSIGNED DEFAULT NULL,
  `purchase_id` bigint(20) UNSIGNED DEFAULT NULL,
  `purchase_return_id` bigint(20) UNSIGNED DEFAULT NULL,
  `stock_adjustment_id` bigint(20) UNSIGNED DEFAULT NULL,
  `production_id` bigint(20) UNSIGNED DEFAULT NULL,
  `transfer_stock_id` bigint(20) UNSIGNED DEFAULT NULL,
  `stock_issue_id` bigint(20) UNSIGNED DEFAULT NULL,
  `payroll_id` bigint(20) UNSIGNED DEFAULT NULL,
  `voucher_description_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `variant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `amount` double NOT NULL DEFAULT '0',
  `amount_type` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `discounts`
--

CREATE TABLE `discounts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `priority` bigint(20) NOT NULL DEFAULT '0',
  `start_at` date DEFAULT NULL,
  `end_at` date DEFAULT NULL,
  `brand_id` bigint(20) UNSIGNED DEFAULT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `discount_type` tinyint(4) NOT NULL DEFAULT '0',
  `discount_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `price_group_id` bigint(20) UNSIGNED DEFAULT NULL,
  `apply_in_customer_group` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `discount_products`
--

CREATE TABLE `discount_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `discount_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_delete_in_update` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `emails`
--

CREATE TABLE `emails` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `mail` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` text COLLATE utf8mb4_unicode_ci,
  `attachment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `email_bodies`
--

CREATE TABLE `email_bodies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `format` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `body` text COLLATE utf8mb4_unicode_ci,
  `is_important` tinyint(4) NOT NULL DEFAULT '0',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `email_send`
--

CREATE TABLE `email_send` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `mail` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `group_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` text COLLATE utf8mb4_unicode_ci,
  `attachment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0 means not send, 1 means send, 2 means draft, 3 means junk, 4 means trash',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `email_servers`
--

CREATE TABLE `email_servers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `server_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `host` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `port` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `encryption` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `email_templates`
--

CREATE TABLE `email_templates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `mail_subject` text COLLATE utf8mb4_unicode_ci,
  `format_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `body_format` longtext COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '0',
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
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rating` double(8,2) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `general_settings`
--

CREATE TABLE `general_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `parent_branch_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `general_settings`
--

INSERT INTO `general_settings` (`id`, `key`, `value`, `branch_id`, `parent_branch_id`) VALUES
(1, 'business_or_shop__business_name', 'TENANT2', NULL, NULL),
(2, 'business_or_shop__address', 'Uttara, Dhaka-1100, Bangladesh', NULL, NULL),
(3, 'business_or_shop__phone', '0141111111111', NULL, NULL),
(4, 'business_or_shop__email', 'example111@email.com', NULL, NULL),
(5, 'business_or_shop__account_start_date', '15-07-2024', NULL, NULL),
(6, 'business_or_shop__financial_year_start_month', '1', NULL, NULL),
(7, 'business_or_shop__default_profit', '0', NULL, NULL),
(8, 'business_or_shop__currency_id', '2', NULL, NULL),
(9, 'business_or_shop__currency_symbol', '$', NULL, NULL),
(10, 'business_or_shop__date_format', 'd-m-Y', NULL, NULL),
(11, 'business_or_shop__stock_accounting_method', '1', NULL, NULL),
(12, 'business_or_shop__time_format', '12', NULL, NULL),
(13, 'business_or_shop__business_logo', NULL, NULL, NULL),
(14, 'business_or_shop__timezone', 'Asia/Dhaka', NULL, NULL),
(15, 'system__theme_color', 'dark-theme', NULL, NULL),
(16, 'system__datatables_page_entry', '50', NULL, NULL),
(17, 'pos__is_enabled_multiple_pay', '1', NULL, NULL),
(18, 'pos__is_enabled_draft', '1', NULL, NULL),
(19, 'pos__is_enabled_quotation', '1', NULL, NULL),
(20, 'pos__is_enabled_suspend', '1', NULL, NULL),
(21, 'pos__is_enabled_discount', '1', NULL, NULL),
(22, 'pos__is_enabled_order_tax', '1', NULL, NULL),
(23, 'pos__is_enabled_credit_full_sale', '1', NULL, NULL),
(24, 'pos__is_enabled_hold_invoice', '1', NULL, NULL),
(25, 'product__product_code_prefix', NULL, NULL, NULL),
(26, 'product__default_unit_id', NULL, NULL, NULL),
(27, 'product__is_enable_brands', '1', NULL, NULL),
(28, 'product__is_enable_categories', '1', NULL, NULL),
(29, 'product__is_enable_sub_categories', '1', NULL, NULL),
(30, 'product__is_enable_price_tax', '0', NULL, NULL),
(31, 'product__is_enable_warranty', '1', NULL, NULL),
(32, 'add_sale__default_sale_discount', '0.00', NULL, NULL),
(33, 'add_sale__default_price_group_id', NULL, NULL, NULL),
(34, 'pos__is_disable_draft', '0', NULL, NULL),
(35, 'pos__is_disable_quotation', '0', NULL, NULL),
(36, 'pos__is_disable_delivery_note', '0', NULL, NULL),
(37, 'pos__is_disable_hold_invoice', '0', NULL, NULL),
(38, 'pos__is_disable_multiple_pay', '1', NULL, NULL),
(39, 'pos__is_show_recent_transactions', '1', NULL, NULL),
(40, 'pos__is_disable_discount', '0', NULL, NULL),
(41, 'pos__is_disable_order_tax', '0', NULL, NULL),
(42, 'pos__is_show_credit_sale_button', '1', NULL, NULL),
(43, 'pos__is_show_partial_sale_button', '1', NULL, NULL),
(44, 'purchase__is_edit_pro_price', '0', NULL, NULL),
(45, 'purchase__is_enable_status', '1', NULL, NULL),
(46, 'purchase__is_enable_lot_no', '1', NULL, NULL),
(47, 'dashboard__view_stock_expiry_alert_for', '31', NULL, NULL),
(48, 'prefix__sales_invoice_prefix', 'SI', NULL, NULL),
(49, 'prefix__quotation_prefix', 'Q', NULL, NULL),
(50, 'prefix__sales_order_prefix', 'SO', NULL, NULL),
(51, 'prefix__sales_return_prefix', 'SR', NULL, NULL),
(52, 'prefix__payment_voucher_prefix', 'PV', NULL, NULL),
(53, 'prefix__receipt_voucher_prefix', 'RV', NULL, NULL),
(54, 'prefix__expense_voucher_prefix', 'EX', NULL, NULL),
(55, 'prefix__contra_voucher_prefix', 'CO', NULL, NULL),
(56, 'prefix__purchase_invoice_prefix', 'PI', NULL, NULL),
(57, 'prefix__purchase_order_prefix', 'PO', NULL, NULL),
(58, 'prefix__purchase_return_prefix', 'PR', NULL, NULL),
(59, 'prefix__stock_adjustment_prefix', 'SA', NULL, NULL),
(60, 'prefix__payroll_voucher_prefix', 'PRL', NULL, NULL),
(61, 'prefix__payroll_payment_voucher_prefix', 'PRLP', NULL, NULL),
(62, 'prefix__stock_issue_voucher_prefix', 'STI', NULL, NULL),
(63, 'prefix__job_card_no_prefix', 'JOB', NULL, NULL),
(64, 'prefix__supplier_id', 'S', NULL, NULL),
(65, 'prefix__customer_id', 'C', NULL, NULL),
(66, 'modules__purchases', '1', NULL, NULL),
(67, 'modules__add_sale', '1', NULL, NULL),
(68, 'modules__pos', '1', NULL, NULL),
(69, 'modules__transfer_stock', '1', NULL, NULL),
(70, 'modules__stock_adjustments', '1', NULL, NULL),
(71, 'modules__accounting', '1', NULL, NULL),
(72, 'modules__contacts', '1', NULL, NULL),
(73, 'modules__hrms', '1', NULL, NULL),
(74, 'modules__manage_task', '1', NULL, NULL),
(75, 'modules__manufacturing', '1', NULL, NULL),
(76, 'modules__service', '1', NULL, NULL),
(77, 'reward_point_settings__enable_cus_point', '0', NULL, NULL),
(78, 'reward_point_settings__point_display_name', 'Reward Point', NULL, NULL),
(79, 'reward_point_settings__amount_for_unit_rp', '10', NULL, NULL),
(80, 'reward_point_settings__min_order_total_for_rp', '100', NULL, NULL),
(81, 'reward_point_settings__max_rp_per_order', '', NULL, NULL),
(82, 'reward_point_settings__redeem_amount_per_unit_rp', '0.10', NULL, NULL),
(83, 'reward_point_settings__min_order_total_for_redeem', '', NULL, NULL),
(84, 'reward_point_settings__min_redeem_point', '', NULL, NULL),
(85, 'reward_point_settings__max_redeem_point', '', NULL, NULL),
(86, 'send_email__send_invoice_via_email', '0', NULL, NULL),
(87, 'send_email__send_notification_via_email', '0', NULL, NULL),
(88, 'send_email__customer_due_reminder_via_email', '0', NULL, NULL),
(89, 'send_email__user_forget_password_via_email', '0', NULL, NULL),
(90, 'send_email__coupon_offer_via_email', '0', NULL, NULL),
(91, 'send_sms__send_invoice_via_sms', '0', NULL, NULL),
(92, 'send_sms__send_notification_via_sms', '0', NULL, NULL),
(93, 'send_sms__customer_due_reminder_via_sms', '0', NULL, NULL),
(94, 'add_sale__default_tax_ac_id', NULL, NULL, NULL),
(95, 'pos__default_tax_ac_id', NULL, NULL, NULL),
(96, 'manufacturing__production_voucher_prefix', 'MF', NULL, NULL),
(97, 'manufacturing__is_edit_ingredients_qty_in_production', '1', NULL, NULL),
(98, 'manufacturing__is_update_product_cost_and_price_in_production', '1', NULL, NULL),
(99, 'invoice_layout__add_sale_invoice_layout_id', '1', NULL, NULL),
(100, 'invoice_layout__pos_sale_invoice_layout_id', '1', NULL, NULL),
(101, 'print_page_size__add_sale_page_size', '1', NULL, NULL),
(102, 'print_page_size__pos_sale_page_size', '1', NULL, NULL),
(103, 'print_page_size__quotation_page_size', '1', NULL, NULL),
(104, 'print_page_size__sales_order_page_size', '1', NULL, NULL),
(105, 'print_page_size__draft_page_size', '1', NULL, NULL),
(106, 'print_page_size__sales_return_page_size', '1', NULL, NULL),
(107, 'print_page_size__purchase_page_size', '1', NULL, NULL),
(108, 'print_page_size__purchase_order_page_size', '1', NULL, NULL),
(109, 'print_page_size__purchase_return_page_size', '1', NULL, NULL),
(110, 'print_page_size__transfer_stock_voucher_page_size', '1', NULL, NULL),
(111, 'print_page_size__stock_adjustment_voucher_page_size', '1', NULL, NULL),
(112, 'print_page_size__receipt_voucher_page_size', '1', NULL, NULL),
(113, 'print_page_size__payment_voucher_page_size', '1', NULL, NULL),
(114, 'print_page_size__expense_voucher_page_size', '1', NULL, NULL),
(115, 'print_page_size__contra_voucher_page_size', '1', NULL, NULL),
(116, 'print_page_size__payroll_voucher_page_size', '1', NULL, NULL),
(117, 'print_page_size__payroll_payment_voucher_page_size', '1', NULL, NULL),
(118, 'print_page_size__bom_voucher_page_size', '1', NULL, NULL),
(119, 'print_page_size__production_voucher_page_size', '1', NULL, NULL),
(120, 'service_settings__default_status_id', NULL, NULL, NULL),
(121, 'service_settings__default_checklist', NULL, NULL, NULL),
(122, 'service_settings__product_configuration', NULL, NULL, NULL),
(123, 'service_settings__default_problems_report', NULL, NULL, NULL),
(124, 'service_settings__product_condition', NULL, NULL, NULL),
(125, 'service_settings__terms_and_condition', NULL, NULL, NULL),
(126, 'service_settings__custom_field_1_label', NULL, NULL, NULL),
(127, 'service_settings__custom_field_2_label', NULL, NULL, NULL),
(128, 'service_settings__custom_field_3_label', NULL, NULL, NULL),
(129, 'service_settings__custom_field_4_label', NULL, NULL, NULL),
(130, 'service_settings__custom_field_5_label', NULL, NULL, NULL),
(131, 'service_settings_pdf_label__show_customer_info', '1', NULL, NULL),
(132, 'service_settings_pdf_label__customer_label_name', NULL, NULL, NULL),
(133, 'service_settings_pdf_label__show_contact_id', '0', NULL, NULL),
(134, 'service_settings_pdf_label__customer_id_label_name', NULL, NULL, NULL),
(135, 'service_settings_pdf_label__show_customer_tax_no', '0', NULL, NULL),
(136, 'service_settings_pdf_label__customer_tax_no_label_name', NULL, NULL, NULL),
(137, 'service_settings_pdf_label__show_custom_field_1', '0', NULL, NULL),
(138, 'service_settings_pdf_label__show_custom_field_2', '0', NULL, NULL),
(139, 'service_settings_pdf_label__show_custom_field_3', '0', NULL, NULL),
(140, 'service_settings_pdf_label__show_custom_field_4', '0', NULL, NULL),
(141, 'service_settings_pdf_label__show_custom_field_5', '0', NULL, NULL),
(142, 'service_settings_pdf_label__label_width', '75', NULL, NULL),
(143, 'service_settings_pdf_label__label_height', '55', NULL, NULL),
(144, 'service_settings_pdf_label__customer_name_in_label', '1', NULL, NULL),
(145, 'service_settings_pdf_label__customer_address_in_label', '1', NULL, NULL),
(146, 'service_settings_pdf_label__customer_phone_in_label', '1', NULL, NULL),
(147, 'service_settings_pdf_label__customer_alt_phone_in_label', '1', NULL, NULL),
(148, 'service_settings_pdf_label__customer_email_in_label', '1', NULL, NULL),
(149, 'service_settings_pdf_label__sales_person_in_label', '1', NULL, NULL),
(150, 'service_settings_pdf_label__barcode_in_label', '1', NULL, NULL),
(151, 'service_settings_pdf_label__status_in_label', '1', NULL, NULL),
(152, 'service_settings_pdf_label__due_date_in_label', '1', NULL, NULL),
(153, 'service_settings_pdf_label__technician_in_label', '1', NULL, NULL),
(154, 'service_settings_pdf_label__problems_in_label_in_label', NULL, NULL, NULL),
(155, 'service_settings_pdf_label__job_card_no_in_label', '1', NULL, NULL),
(156, 'service_settings_pdf_label__serial_in_label', '1', NULL, NULL),
(157, 'service_settings_pdf_label__model_in_label', '1', NULL, NULL),
(158, 'service_settings_pdf_label__location_in_label', NULL, NULL, NULL),
(159, 'service_settings_pdf_label__password_in_label', '1', NULL, NULL),
(160, 'service_settings_pdf_label__problems_in_label', '1', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `hrm_allowances`
--

CREATE TABLE `hrm_allowances` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1=Allowance;2=Deduction',
  `amount_type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1=fixed;2=percentage',
  `amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hrm_attendances`
--

CREATE TABLE `hrm_attendances` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `clock_in_date` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `clock_out_date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `clock_in` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `clock_out` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `work_duration` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `clock_in_note` text COLLATE utf8mb4_unicode_ci,
  `clock_out_note` text COLLATE utf8mb4_unicode_ci,
  `month` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `year` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `clock_in_ts` timestamp NULL DEFAULT NULL,
  `clock_out_ts` timestamp NULL DEFAULT NULL,
  `at_date_ts` timestamp NULL DEFAULT NULL,
  `is_completed` tinyint(1) NOT NULL DEFAULT '0',
  `shift_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hrm_departments`
--

CREATE TABLE `hrm_departments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hrm_designations`
--

CREATE TABLE `hrm_designations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hrm_holidays`
--

CREATE TABLE `hrm_holidays` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `note` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hrm_holiday_branches`
--

CREATE TABLE `hrm_holiday_branches` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `holiday_id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_delete_in_update` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hrm_leaves`
--

CREATE TABLE `hrm_leaves` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `leave_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `leave_type_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `start_date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `end_date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reason` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint(4) NOT NULL,
  `created_by_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hrm_leave_types`
--

CREATE TABLE `hrm_leave_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `max_leave_count` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `expense_account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `voucher_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `duration_time` decimal(22,2) NOT NULL DEFAULT '0.00',
  `duration_unit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount_per_unit` decimal(22,2) NOT NULL DEFAULT '0.00',
  `total_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `total_allowance` decimal(22,2) NOT NULL DEFAULT '0.00',
  `total_deduction` decimal(22,2) NOT NULL DEFAULT '0.00',
  `gross_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `paid` decimal(22,2) NOT NULL DEFAULT '0.00',
  `due` decimal(22,2) NOT NULL DEFAULT '0.00',
  `date_ts` timestamp NULL DEFAULT NULL,
  `date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `month` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `year` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by_id` bigint(20) UNSIGNED DEFAULT NULL,
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
  `allowance_id` bigint(20) UNSIGNED DEFAULT NULL,
  `allowance_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `allowance_percent` decimal(22,2) NOT NULL DEFAULT '0.00',
  `allowance_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `is_delete_in_update` tinyint(1) NOT NULL DEFAULT '0',
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
  `deduction_id` bigint(20) UNSIGNED DEFAULT NULL,
  `deduction_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount_type` tinyint(4) NOT NULL DEFAULT '1',
  `deduction_percent` decimal(22,2) NOT NULL DEFAULT '0.00',
  `deduction_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `is_delete_in_update` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hrm_shifts`
--

CREATE TABLE `hrm_shifts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_time` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `late_count` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `end_time` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoice_layouts`
--

CREATE TABLE `invoice_layouts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `show_shop_logo` tinyint(1) NOT NULL DEFAULT '0',
  `header_text` text COLLATE utf8mb4_unicode_ci,
  `is_header_less` tinyint(1) NOT NULL DEFAULT '0',
  `gap_from_top` bigint(20) DEFAULT NULL,
  `customer_name` tinyint(1) NOT NULL DEFAULT '1',
  `customer_tax_no` tinyint(1) NOT NULL DEFAULT '0',
  `customer_address` tinyint(1) NOT NULL DEFAULT '0',
  `customer_phone` tinyint(1) NOT NULL DEFAULT '0',
  `sales_order_heading` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sub_heading_1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sub_heading_2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sub_heading_3` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `invoice_heading` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quotation_heading` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `delivery_note_heading` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `branch_city` tinyint(1) NOT NULL DEFAULT '0',
  `branch_state` tinyint(1) NOT NULL DEFAULT '0',
  `branch_country` tinyint(1) NOT NULL DEFAULT '0',
  `branch_zipcode` tinyint(1) NOT NULL DEFAULT '0',
  `branch_phone` tinyint(1) NOT NULL DEFAULT '0',
  `branch_alternate_number` tinyint(1) NOT NULL DEFAULT '0',
  `branch_email` tinyint(1) NOT NULL DEFAULT '0',
  `product_imei` tinyint(1) NOT NULL DEFAULT '0',
  `product_w_type` tinyint(1) NOT NULL DEFAULT '0',
  `product_w_duration` tinyint(1) NOT NULL DEFAULT '0',
  `product_w_discription` tinyint(1) NOT NULL DEFAULT '0',
  `product_discount` tinyint(1) NOT NULL DEFAULT '0',
  `product_tax` tinyint(1) NOT NULL DEFAULT '0',
  `product_price_inc_tax` tinyint(1) NOT NULL DEFAULT '0',
  `product_price_exc_tax` tinyint(1) NOT NULL DEFAULT '0',
  `invoice_notice` text COLLATE utf8mb4_unicode_ci,
  `sale_note` tinyint(1) NOT NULL DEFAULT '0',
  `show_total_in_word` tinyint(1) NOT NULL DEFAULT '0',
  `footer_text` text COLLATE utf8mb4_unicode_ci,
  `bank_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_branch` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoice_layouts`
--

INSERT INTO `invoice_layouts` (`id`, `branch_id`, `name`, `show_shop_logo`, `header_text`, `is_header_less`, `gap_from_top`, `customer_name`, `customer_tax_no`, `customer_address`, `customer_phone`, `sales_order_heading`, `sub_heading_1`, `sub_heading_2`, `sub_heading_3`, `invoice_heading`, `quotation_heading`, `delivery_note_heading`, `branch_city`, `branch_state`, `branch_country`, `branch_zipcode`, `branch_phone`, `branch_alternate_number`, `branch_email`, `product_imei`, `product_w_type`, `product_w_duration`, `product_w_discription`, `product_discount`, `product_tax`, `product_price_inc_tax`, `product_price_exc_tax`, `invoice_notice`, `sale_note`, `show_total_in_word`, `footer_text`, `bank_name`, `bank_branch`, `account_name`, `account_no`, `is_default`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Default layout', 1, NULL, 0, NULL, 1, 0, 1, 1, 'Sales Order', NULL, NULL, NULL, 'Invoice', 'Quotation', 'Delivery Note', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 1, 1, 0, 0, 'Thanks for buying from us', 0, 1, NULL, NULL, NULL, NULL, NULL, 1, '2021-03-02 12:24:36', '2023-12-03 11:50:35');

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
(1, '2022_01_01_000001_1_create_shop_expire_date_histories_table', 1),
(2, '2022_01_01_000001_create_branches_table', 1),
(3, '2022_01_02_000003_4_create_banks_table', 1),
(4, '2022_01_02_000026_create_price_groups_table', 1),
(5, '2022_01_02_113358_create_hrm_departments_table', 1),
(6, '2022_01_02_113358_create_hrm_designations_table', 1),
(7, '2022_01_02_113358_create_hrm_shifts_table', 1),
(8, '2022_07_01_000001_1_create_currencies_table', 1),
(9, '2022_07_01_000001_create_users_table', 1),
(10, '2022_07_01_000002_create_role_permissions_raleted_tables', 1),
(11, '2022_08_28_000001_create_sms_table', 1),
(12, '2022_08_29_000001_create_communication_contact_groups_table', 1),
(13, '2022_08_29_000001_create_communication_contacts_table', 1),
(14, '2022_09_09_000001_create_emails_table', 1),
(15, '2022_09_09_000001_create_whatsapps_messages_table', 1),
(16, '2023_01_02_000001_create_account_groups_table', 1),
(17, '2023_01_02_000001_create_customer_groups_table', 1),
(18, '2023_01_02_000002_create_contacts_table', 1),
(19, '2023_01_02_000002_create_units_table', 1),
(20, '2023_01_02_000002_create_warehouses_table', 1),
(21, '2023_01_02_000003_01_create_account_opening_balances_table', 1),
(22, '2023_01_02_000003_0_create_accounts_table', 1),
(23, '2023_01_02_000003_0_create_brands_table', 1),
(24, '2023_01_02_000003_1_create_categories_table', 1),
(25, '2023_01_02_000003_1_create_products__table', 1),
(26, '2023_01_02_000003_2_create_bulk_variants_table', 1),
(27, '2023_01_02_000003_2_create_product_variants_table', 1),
(28, '2023_01_02_000003_3_create_bulk_variant_children_table', 1),
(29, '2023_01_02_000003_4_create_product_units_table', 1),
(30, '2023_01_02_000004_create_product_opening_stocks_table', 1),
(31, '2023_01_02_000004_create_purchases_table', 1),
(32, '2023_01_02_000005_create_purchase_order_products_table', 1),
(33, '2023_01_02_000008_create_purchase_returns_table', 1),
(34, '2023_01_02_000010_create_sales_table', 1),
(35, '2023_01_02_000011_create_sale_products_table', 1),
(36, '2023_01_02_000012_create_sale_returns_table', 1),
(37, '2023_01_02_000013_create_sale_return_products_table', 1),
(38, '2023_01_02_000016_create_stock_adjustments_table', 1),
(39, '2023_01_02_000017_create_stock_adjustment_products_table', 1),
(40, '2023_01_02_000018_create_processes_table', 1),
(41, '2023_01_02_000019_create_process_ingredients_table', 1),
(42, '2023_01_02_000021_create_productions_table', 1),
(43, '2023_01_02_000022_create_production_ingredients_table', 1),
(44, '2023_01_02_000024_create_transfer_stocks_table', 1),
(45, '2023_01_02_000025_create_transfer_stock_products_table', 1),
(46, '2023_01_02_000026_create_purchase_products_table', 1),
(47, '2023_01_02_000026_create_purchase_return_products_table', 1),
(48, '2023_01_02_000027_create_price_group_products_table', 1),
(49, '2023_01_02_000028_create_payment_methods_table', 1),
(50, '2023_01_02_000029_create_payment_method_settings_table', 1),
(51, '2023_01_02_000030_1_create_cash_counters_table', 1),
(52, '2023_01_02_000030_create_cash_registers_table', 1),
(53, '2023_01_02_113357_1_create_hrm_payrolls_table', 1),
(54, '2023_01_02_113357_create_short_menus_table', 1),
(55, '2023_01_02_113357_create_workspaces_table', 1),
(56, '2023_01_02_113358_1_create_hrm_holidays_table', 1),
(57, '2023_01_02_113358_2_create_hrm_holiday_branches_table', 1),
(58, '2023_01_02_113358_create_asset_types_table', 1),
(59, '2023_01_02_113358_create_assets_table', 1),
(60, '2023_01_02_113358_create_barcode_settings_table', 1),
(61, '2023_01_02_113358_create_combo_products_table', 1),
(62, '2023_01_02_113358_create_failed_jobs_table', 1),
(63, '2023_01_02_113358_create_general_settings_table', 1),
(64, '2023_01_02_113358_create_hrm_allowance_table', 1),
(65, '2023_01_02_113358_create_hrm_attendances_table', 1),
(66, '2023_01_02_113358_create_hrm_leave_types_table', 1),
(67, '2023_01_02_113358_create_hrm_leaves_table', 1),
(68, '2023_01_02_113358_create_hrm_payroll_allowances_table', 1),
(69, '2023_01_02_113358_create_hrm_payroll_deductions_table', 1),
(70, '2023_01_02_113358_create_invoice_layouts_table', 1),
(71, '2023_01_02_113358_create_jobs_table', 1),
(72, '2023_01_02_113358_create_loan_companies_table', 1),
(73, '2023_01_02_113358_create_loan_payments_table', 1),
(74, '2023_01_02_113358_create_loans_table', 1),
(75, '2023_01_02_113358_create_messages_table', 1),
(76, '2023_01_02_113358_create_money_receipts_table', 1),
(77, '2023_01_02_113358_create_months_table', 1),
(78, '2023_01_02_113358_create_password_resets_table', 1),
(79, '2023_01_02_113358_create_purchase_order_product_receives_table', 1),
(80, '2023_01_02_113358_create_short_menu_users_table', 1),
(81, '2023_01_02_113358_create_todos_table', 1),
(82, '2023_01_02_113358_create_user_activity_logs_table', 1),
(83, '2023_01_02_113358_create_warranties_table', 1),
(84, '2023_01_02_113358_create_workspace_tasks_table', 1),
(85, '2023_01_02_113358_create_workspace_users_table', 1),
(86, '2023_01_02_113359_create_allowance_employees_table', 1),
(87, '2023_01_02_113359_create_discounts_table', 1),
(88, '2023_01_02_113359_create_loan_payment_distributions_table', 1),
(89, '2023_01_02_113359_create_todo_users_table', 1),
(90, '2023_01_02_113359_create_workspace_attachments_table', 1),
(91, '2023_01_02_113360_create_discount_products_table', 1),
(92, '2023_01_16_162441_create_feedback_table', 1),
(93, '2023_01_18_131114_create_email_templates_table', 1),
(94, '2023_01_19_102114_create_email_servers_table', 1),
(95, '2023_01_19_102900_create_sms_servers_table', 1),
(96, '2023_07_20_120146_create_contact_credit_limits_table', 1),
(97, '2023_08_02_141406_create_bank_access_branches_table', 1),
(98, '2023_08_06_124429_create_product_access_branches_table', 1),
(99, '2023_08_07_190805_create_product_stocks_table', 1),
(100, '2023_09_03_194336_create_accounting_vouchers_table', 1),
(101, '2023_09_03_195559_create_accounting_voucher_descriptions_table', 1),
(102, '2023_09_03_195559_create_cash_register_transactions_table', 1),
(103, '2023_09_03_200245_create_voucher_description_references_table', 1),
(104, '2023_09_03_200246_create_account_ledgers_table', 1),
(105, '2023_09_03_200605_create_day_books_table', 1),
(106, '2023_09_06_130048_create_product_ledgers_table', 1),
(107, '2024_02_04_123720_create_subscriptions_table', 1),
(108, '2024_02_04_125219_create_subscription_transactions_table', 1),
(109, '2024_03_04_1152100_create_sms_bodies_table', 1),
(110, '2024_03_04_115243_create_email_bodies_table', 1),
(111, '2024_03_04_164912_create_email_send_table', 1),
(112, '2024_03_04_1649200_create_sms_send_table', 1),
(113, '2024_03_23_163345_create_stock_issues_table', 1),
(114, '2024_03_23_163908_create_stock_issue_products_table', 1),
(115, '2024_03_23_163909_create_stock_chains_table', 1),
(116, '2024_03_25_140659_create_advertisements_table', 1),
(117, '2024_03_25_153313_create_advertise_attachments_table', 1),
(118, '2024_06_04_202654_create_service_status_table', 1),
(119, '2024_06_05_160013_create_service_devices_table', 1),
(120, '2024_06_05_173835_create_service_device_models_table', 1),
(121, '2024_06_09_164059_create_service_job_cards_table', 1),
(122, '2024_06_09_172552_create_service_job_card_products_table', 1),
(123, '2024_02_06_164422_change_col_from_currency_table', 2),
(124, '2024_02_06_164423_add_new_cols_to_users_table', 2),
(125, '2024_02_06_173021_drop_some_cols_from_users_table', 2),
(126, '2024_02_12_142818_add_col_to_branches_table', 2),
(127, '2024_02_12_142819_drop_col_from_branches_table', 2),
(128, '2024_02_12_144712_drop_cols_from_branches_table', 2),
(129, '2024_02_12_145430_drop_cols_from_users_table', 2),
(130, '2024_02_13_124649_add_new_col_to_branches_table', 2),
(131, '2024_02_15_120748_add_new_cols_to_subscriptions_table', 2),
(132, '2024_02_17_163122_modify_subscriptions_table', 2),
(133, '2024_02_18_204127_add_new_col_to_subscriptions_table', 2),
(134, '2024_03_03_153817_drop_col_col_from_todos_table', 2),
(135, '2024_03_03_154339_new_new_col_to_todos_table', 2),
(136, '2024_03_03_203617_rename_col_from_todos_table', 2),
(137, '2024_03_04_134141_drop_col_from_workspaces_table', 2),
(138, '2024_03_04_134312_add_col_to_workspaces_table', 2),
(139, '2024_03_04_134556_remame_col_from_workspaces_table', 2),
(140, '2024_03_04_193621_drop_memos_table', 2),
(141, '2024_03_04_194051_drop_memo_users_table', 2),
(142, '2024_03_06_201021_add_new_col_to_branches_table', 2),
(143, '2024_03_06_203431_add_new_col_to_shop_expire_date_histories_table', 2),
(144, '2024_03_13_135624_change_col_from_categories_table', 2),
(145, '2024_03_13_200009_rename_col_from_purchase_products_table', 2),
(146, '2024_03_16_125200_add_new_col_to_categories_table', 2),
(147, '2024_03_16_131659_change_col_from_brands_table', 2),
(148, '2024_03_16_142035_add_new_col_to_brands_table', 2),
(149, '2024_03_16_150216_add_new_col_to_units_table', 2),
(150, '2024_03_16_164924_add_new_col_to_warranties_table', 2),
(151, '2024_03_18_194742_add_new_col_to_shop_expire_date_histories_table', 2),
(152, '2024_03_18_205155_modify_subscription_transaction_table', 2),
(153, '2024_03_19_202111_modify_subscription_table', 2),
(154, '2024_03_20_121416_rename_col_to_shop_expire_date_histories_table', 2),
(155, '2024_03_20_133424_change_col_from_subscription_transaction_table', 2),
(156, '2024_03_20_201357_add_new_col_to_users_table', 2),
(157, '2024_03_20_215408_add_new_col_to_subscriptions_table', 2),
(158, '2024_03_21_150012_change_col_from_users_table', 2),
(159, '2024_03_23_191419_change_name_purchase_sale_product_chains_table', 2),
(160, '2024_03_23_192446_add_new_cols_stock_chains_table', 2),
(161, '2024_03_23_195236_change_col_name_from_stock_chains_table', 2),
(162, '2024_03_24_191841_add_new_col_to_product_ledgers_table', 2),
(163, '2024_03_24_195025_add_new_col_to_day_books_table', 2),
(164, '2024_03_26_133733_add_new_cols_stock_chains_table', 2),
(165, '2024_03_31_212119_add_new_col_to_subscription_transactions_table', 2),
(166, '2024_04_02_180029_modify_shop_expire_date_histories_table', 2),
(167, '2024_04_03_131436_drop_col_shop_expire_date_histories_table', 2),
(168, '2024_04_18_202326_edit_col_from_products_table', 2),
(169, '2024_04_18_202649_drop_col_from_products_table', 2),
(170, '2024_04_20_113756_change_col_from_branches_table', 2),
(171, '2024_04_20_180314_change_col_from_users_table', 2),
(172, '2024_04_25_155033_drop_pos_short_menus_table', 2),
(173, '2024_04_25_155034_drop_pos_short_menu_users_table', 2),
(174, '2024_04_25_160034_add_new_col_pos_short_menu_users_table', 2),
(175, '2024_04_25_180851_add_new_col_pos_short_menus_table', 2),
(176, '2024_04_30_173321_drop_contact_opening_balances_table', 2),
(177, '2024_05_05_125249_add_new_cols_with_foreign_key_to_day_books_table', 2),
(178, '2024_05_16_133423_add_new_cols_sales__table', 2),
(179, '2024_05_19_203703_add_new_col_purchases_table', 2),
(180, '2024_05_19_213235_add_new_col_purchases_table', 2),
(181, '2024_06_02_142352_add_new_col_user_activity_logs_table', 2),
(182, '2024_06_22_165650_add_new_col_to_service_job_cards_table', 2),
(183, '2024_07_03_180400_drop_assets_table', 2),
(184, '2024_07_03_180456_drop_asset_types_table', 2),
(185, '2024_07_03_181102_drop_loans_table', 2),
(186, '2024_07_03_181142_drop_loan_companies_table', 2),
(187, '2024_07_03_181300_drop_loan_payments_table', 2),
(188, '2024_07_03_181449_drop_loan_payment_distributions_table', 2),
(189, '2024_07_08_152556_add_new_cols_to_short_menus_table', 2),
(190, '2024_07_09_144029_add_new_col_to_advertisements_table', 2),
(191, '2024_07_09_204314_add_new_col_to_advertise_attachments_table', 2),
(192, '2024_07_09_205826_add_foreign_key_to_advertise_attachments_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `money_receipts`
--

CREATE TABLE `money_receipts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `contact_id` bigint(20) UNSIGNED NOT NULL,
  `voucher_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` decimal(22,2) DEFAULT NULL,
  `is_customer_name` tinyint(1) NOT NULL DEFAULT '0',
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `note` mediumtext COLLATE utf8mb4_unicode_ci,
  `receiver` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ac_details` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_date` tinyint(1) NOT NULL DEFAULT '0',
  `is_header_less` tinyint(1) NOT NULL DEFAULT '0',
  `gap_from_top` bigint(20) DEFAULT NULL,
  `date_ts` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_methods`
--

CREATE TABLE `payment_methods` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_fixed` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payment_methods`
--

INSERT INTO `payment_methods` (`id`, `name`, `is_fixed`, `created_at`, `updated_at`) VALUES
(1, 'Cash', 1, NULL, NULL),
(2, 'Debit-Card', 1, NULL, NULL),
(3, 'Credit-Card', 1, NULL, NULL),
(4, 'Cheque', 1, NULL, NULL),
(5, 'Bank-Transfer', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `payment_method_settings`
--

CREATE TABLE `payment_method_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `payment_method_id` bigint(20) UNSIGNED DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
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
  `description` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Table structure for table `processes`
--

CREATE TABLE `processes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `variant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `total_ingredient_cost` decimal(22,2) NOT NULL DEFAULT '0.00',
  `wastage_percent` decimal(22,2) NOT NULL DEFAULT '0.00',
  `wastage_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `total_output_qty` decimal(22,2) NOT NULL DEFAULT '0.00',
  `unit_id` bigint(20) UNSIGNED DEFAULT NULL,
  `additional_production_cost` decimal(22,2) NOT NULL DEFAULT '0.00',
  `net_cost` decimal(22,2) NOT NULL DEFAULT '0.00',
  `production_instruction` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `process_ingredients`
--

CREATE TABLE `process_ingredients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `process_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `variant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `wastage_percent` decimal(22,2) NOT NULL DEFAULT '0.00',
  `wastage_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `final_qty` decimal(22,2) NOT NULL DEFAULT '0.00',
  `unit_id` bigint(20) UNSIGNED DEFAULT NULL,
  `unit_cost_exc_tax` decimal(22,2) NOT NULL DEFAULT '0.00',
  `tax_ac_id` bigint(20) UNSIGNED DEFAULT NULL,
  `unit_tax_type` tinyint(4) NOT NULL DEFAULT '1',
  `unit_tax_percent` decimal(22,2) NOT NULL DEFAULT '0.00',
  `unit_tax_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `unit_cost_inc_tax` decimal(22,2) NOT NULL DEFAULT '0.00',
  `subtotal` decimal(22,2) NOT NULL DEFAULT '0.00',
  `is_delete_in_update` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `productions`
--

CREATE TABLE `productions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `voucher_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `store_warehouse_id` bigint(20) UNSIGNED DEFAULT NULL,
  `stock_warehouse_id` bigint(20) UNSIGNED DEFAULT NULL,
  `process_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `variant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `total_ingredient_cost` decimal(22,2) DEFAULT NULL,
  `total_output_quantity` decimal(22,2) DEFAULT NULL,
  `unit_id` bigint(20) UNSIGNED DEFAULT NULL,
  `total_parameter_quantity` decimal(22,2) NOT NULL DEFAULT '0.00',
  `total_wasted_quantity` decimal(22,2) DEFAULT NULL,
  `total_final_output_quantity` decimal(22,2) NOT NULL DEFAULT '0.00',
  `additional_production_cost` decimal(22,2) DEFAULT NULL,
  `net_cost` decimal(22,2) DEFAULT NULL,
  `per_unit_cost_exc_tax` decimal(22,2) NOT NULL DEFAULT '0.00',
  `tax_ac_id` bigint(20) UNSIGNED DEFAULT NULL,
  `tax_type` tinyint(4) DEFAULT NULL,
  `unit_tax_percent` decimal(22,2) NOT NULL DEFAULT '0.00',
  `unit_tax_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `per_unit_cost_inc_tax` decimal(22,2) NOT NULL DEFAULT '0.00',
  `profit_margin` decimal(22,2) NOT NULL DEFAULT '0.00',
  `per_unit_price_exc_tax` decimal(22,2) NOT NULL DEFAULT '0.00',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `is_last_entry` tinyint(1) NOT NULL DEFAULT '0',
  `is_default_price` tinyint(1) NOT NULL DEFAULT '0',
  `date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_ts` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `production_ingredients`
--

CREATE TABLE `production_ingredients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `production_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `variant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `parameter_quantity` decimal(22,2) NOT NULL DEFAULT '0.00',
  `final_qty` decimal(22,2) NOT NULL DEFAULT '0.00',
  `unit_id` bigint(20) UNSIGNED DEFAULT NULL,
  `unit_cost_exc_tax` decimal(22,2) NOT NULL DEFAULT '0.00',
  `tax_ac_id` bigint(20) UNSIGNED DEFAULT NULL,
  `unit_tax_type` tinyint(4) NOT NULL DEFAULT '1',
  `unit_tax_percent` decimal(22,2) NOT NULL DEFAULT '0.00',
  `unit_tax_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `unit_cost_inc_tax` decimal(22,2) NOT NULL DEFAULT '0.00',
  `subtotal` decimal(22,2) NOT NULL DEFAULT '0.00',
  `is_delete_in_update` tinyint(1) NOT NULL DEFAULT '0',
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
  `sub_category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `brand_id` bigint(20) UNSIGNED DEFAULT NULL,
  `unit_id` bigint(20) UNSIGNED DEFAULT NULL,
  `tax_ac_id` bigint(20) UNSIGNED DEFAULT NULL,
  `tax_type` tinyint(4) NOT NULL DEFAULT '1',
  `warranty_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_cost` decimal(22,2) NOT NULL DEFAULT '0.00',
  `product_cost_with_tax` decimal(22,2) NOT NULL DEFAULT '0.00',
  `profit` decimal(22,2) NOT NULL DEFAULT '0.00',
  `product_price` decimal(22,2) NOT NULL DEFAULT '0.00',
  `offer_price` decimal(22,2) NOT NULL DEFAULT '0.00',
  `is_manage_stock` tinyint(1) NOT NULL DEFAULT '1',
  `quantity` decimal(22,2) NOT NULL DEFAULT '0.00',
  `combo_price` decimal(22,2) NOT NULL DEFAULT '0.00',
  `alert_quantity` bigint(20) NOT NULL DEFAULT '0',
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `is_combo` tinyint(1) NOT NULL DEFAULT '0',
  `has_multiple_unit` tinyint(1) NOT NULL DEFAULT '0',
  `is_variant` tinyint(1) NOT NULL DEFAULT '0',
  `is_show_in_ecom` tinyint(1) NOT NULL DEFAULT '0',
  `is_show_emi_on_pos` tinyint(1) NOT NULL DEFAULT '0',
  `has_batch_no_expire_date` tinyint(1) NOT NULL DEFAULT '0',
  `is_for_sale` tinyint(1) NOT NULL DEFAULT '1',
  `thumbnail_photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expire_date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_details` text COLLATE utf8mb4_unicode_ci,
  `is_purchased` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `barcode_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `weight` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_condition` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_access_branches`
--

CREATE TABLE `product_access_branches` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `variant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_delete_in_update` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_ledgers`
--

CREATE TABLE `product_ledgers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `voucher_type` tinyint(4) NOT NULL,
  `warehouse_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `variant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sale_product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sale_return_product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `purchase_product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `purchase_return_product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `opening_stock_product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `stock_adjustment_product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `production_id` bigint(20) UNSIGNED DEFAULT NULL,
  `production_ingredient_id` bigint(20) UNSIGNED DEFAULT NULL,
  `transfer_stock_product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `stock_issue_product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `rate` decimal(22,2) NOT NULL DEFAULT '0.00',
  `in` decimal(22,2) NOT NULL DEFAULT '0.00',
  `out` decimal(22,2) NOT NULL DEFAULT '0.00',
  `subtotal` decimal(22,2) NOT NULL DEFAULT '0.00',
  `type` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_ts` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_opening_stocks`
--

CREATE TABLE `product_opening_stocks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `warehouse_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `variant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `unit_cost_inc_tax` decimal(22,2) NOT NULL DEFAULT '0.00',
  `quantity` decimal(22,2) NOT NULL DEFAULT '0.00',
  `subtotal` decimal(22,2) NOT NULL DEFAULT '0.00',
  `lot_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_ts` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_stocks`
--

CREATE TABLE `product_stocks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `warehouse_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `variant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `stock` decimal(22,2) NOT NULL DEFAULT '0.00',
  `stock_value` decimal(22,2) NOT NULL DEFAULT '0.00',
  `all_stock` decimal(22,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_units`
--

CREATE TABLE `product_units` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `variant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `base_unit_id` bigint(20) UNSIGNED NOT NULL,
  `base_unit_multiplier` decimal(22,2) NOT NULL DEFAULT '0.00',
  `assigned_unit_quantity` decimal(22,2) NOT NULL DEFAULT '0.00',
  `assigned_unit_id` bigint(20) UNSIGNED NOT NULL,
  `unit_cost_exc_tax` decimal(22,2) NOT NULL DEFAULT '0.00',
  `unit_cost_inc_tax` decimal(22,2) NOT NULL DEFAULT '0.00',
  `unit_price_exc_tax` decimal(22,2) NOT NULL DEFAULT '0.00',
  `is_delete_in_update` tinyint(1) NOT NULL DEFAULT '0',
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
  `variant_quantity` decimal(22,2) NOT NULL DEFAULT '0.00',
  `variant_cost` decimal(22,2) NOT NULL,
  `variant_cost_with_tax` decimal(22,2) NOT NULL DEFAULT '0.00',
  `variant_profit` decimal(22,2) NOT NULL DEFAULT '0.00',
  `variant_price` decimal(22,2) NOT NULL,
  `variant_image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_purchased` tinyint(1) NOT NULL DEFAULT '0',
  `is_delete_in_update` tinyint(1) NOT NULL DEFAULT '0',
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
  `challan_no` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `warehouse_id` bigint(20) UNSIGNED DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `supplier_account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `pay_term` tinyint(4) DEFAULT NULL,
  `pay_term_number` bigint(20) DEFAULT NULL,
  `total_item` bigint(20) NOT NULL,
  `total_qty` decimal(22,2) NOT NULL DEFAULT '0.00',
  `net_total_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `order_discount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `order_discount_type` tinyint(4) NOT NULL DEFAULT '1',
  `order_discount_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `shipment_details` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipment_charge` decimal(22,2) NOT NULL DEFAULT '0.00',
  `purchase_note` mediumtext COLLATE utf8mb4_unicode_ci,
  `purchase_tax_ac_id` bigint(20) UNSIGNED DEFAULT NULL,
  `purchase_tax_percent` decimal(22,2) NOT NULL DEFAULT '0.00',
  `purchase_tax_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `total_purchase_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `paid` decimal(22,2) NOT NULL DEFAULT '0.00',
  `due` decimal(22,2) NOT NULL DEFAULT '0.00',
  `purchase_return_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `purchase_return_due` decimal(22,2) NOT NULL DEFAULT '0.00',
  `payment_note` mediumtext COLLATE utf8mb4_unicode_ci,
  `admin_id` bigint(20) UNSIGNED DEFAULT NULL,
  `purchase_status` tinyint(4) NOT NULL DEFAULT '1',
  `is_purchased` tinyint(1) NOT NULL DEFAULT '1',
  `date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `delivery_date` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `time` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `report_date` timestamp NULL DEFAULT NULL,
  `month` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `year` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_last_created` tinyint(1) NOT NULL DEFAULT '0',
  `is_return_available` tinyint(1) NOT NULL DEFAULT '0',
  `attachment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `po_qty` decimal(22,2) NOT NULL DEFAULT '0.00',
  `po_pending_qty` decimal(22,2) NOT NULL DEFAULT '0.00',
  `po_received_qty` decimal(22,2) NOT NULL DEFAULT '0.00',
  `po_receiving_status` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'This field only for order, which numeric status = 3',
  `purchase_order_id` bigint(20) UNSIGNED DEFAULT NULL,
  `purchase_account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_order_products`
--

CREATE TABLE `purchase_order_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `purchase_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `variant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ordered_quantity` decimal(22,2) NOT NULL DEFAULT '0.00',
  `received_quantity` decimal(22,2) NOT NULL DEFAULT '0.00',
  `pending_quantity` decimal(22,2) NOT NULL DEFAULT '0.00',
  `unit_id` bigint(20) UNSIGNED DEFAULT NULL,
  `unit_cost_exc_tax` decimal(22,2) NOT NULL DEFAULT '0.00',
  `unit_discount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `unit_discount_type` tinyint(4) NOT NULL DEFAULT '1',
  `unit_discount_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `unit_cost_with_discount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `subtotal` decimal(22,2) NOT NULL DEFAULT '0.00' COMMENT 'Without_tax',
  `tax_ac_id` bigint(20) UNSIGNED DEFAULT NULL,
  `unit_tax_type` tinyint(4) NOT NULL DEFAULT '1',
  `unit_tax_percent` decimal(22,2) NOT NULL DEFAULT '0.00',
  `unit_tax_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `net_unit_cost` decimal(22,2) NOT NULL DEFAULT '0.00' COMMENT 'inc_tax',
  `line_total` decimal(22,2) NOT NULL DEFAULT '0.00',
  `profit_margin` decimal(22,2) NOT NULL DEFAULT '0.00',
  `selling_price` decimal(22,2) NOT NULL DEFAULT '0.00',
  `description` mediumtext COLLATE utf8mb4_unicode_ci,
  `is_delete_in_update` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_order_product_receives`
--

CREATE TABLE `purchase_order_product_receives` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_product_id` bigint(20) UNSIGNED NOT NULL,
  `delivery_note_heading` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lot_number` bigint(20) UNSIGNED DEFAULT NULL,
  `received_date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qty_received` decimal(22,2) NOT NULL DEFAULT '0.00',
  `report_date` timestamp NULL DEFAULT NULL,
  `is_delete_in_update` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_products`
--

CREATE TABLE `purchase_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'This column for track branch wise FIFO/LIFO method.',
  `purchase_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `variant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `quantity` decimal(22,2) DEFAULT '0.00',
  `unit_id` bigint(20) UNSIGNED DEFAULT NULL,
  `unit_cost_exc_tax` decimal(22,2) NOT NULL DEFAULT '0.00',
  `unit_discount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `unit_discount_type` tinyint(4) NOT NULL DEFAULT '1',
  `unit_discount_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `unit_cost_with_discount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `subtotal` decimal(22,2) NOT NULL DEFAULT '0.00' COMMENT 'Without_tax',
  `unit_tax_percent` decimal(22,2) NOT NULL DEFAULT '0.00',
  `tax_ac_id` bigint(20) UNSIGNED DEFAULT NULL,
  `tax_type` tinyint(4) DEFAULT NULL,
  `unit_tax_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `net_unit_cost` decimal(22,2) NOT NULL DEFAULT '0.00' COMMENT 'With_tax',
  `line_total` decimal(22,2) NOT NULL DEFAULT '0.00',
  `profit_margin` decimal(22,2) NOT NULL DEFAULT '0.00',
  `selling_price` decimal(22,2) NOT NULL DEFAULT '0.00',
  `description` mediumtext COLLATE utf8mb4_unicode_ci,
  `is_received` tinyint(1) NOT NULL DEFAULT '0',
  `lot_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `batch_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expire_date` timestamp NULL DEFAULT NULL,
  `delete_in_update` tinyint(1) NOT NULL DEFAULT '0',
  `purchase_order_product_id` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'when product add from purchase_order_products table',
  `label_left_qty` decimal(22,2) NOT NULL DEFAULT '0.00',
  `left_qty` decimal(22,2) NOT NULL DEFAULT '0.00',
  `production_id` bigint(20) UNSIGNED DEFAULT NULL,
  `opening_stock_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sale_return_product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `transfer_stock_product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_returns`
--

CREATE TABLE `purchase_returns` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `voucher_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `purchase_id` bigint(20) UNSIGNED DEFAULT NULL,
  `supplier_account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `purchase_account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `total_item` decimal(22,2) NOT NULL DEFAULT '0.00',
  `total_qty` decimal(22,2) NOT NULL DEFAULT '0.00',
  `net_total_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `return_discount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `return_discount_type` decimal(22,2) NOT NULL DEFAULT '0.00',
  `return_discount_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `return_tax_ac_id` bigint(20) UNSIGNED DEFAULT NULL,
  `return_tax_percent` decimal(22,2) NOT NULL DEFAULT '0.00',
  `return_tax_type` tinyint(4) NOT NULL DEFAULT '1',
  `return_tax_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `total_return_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `due` decimal(22,2) NOT NULL DEFAULT '0.00',
  `received_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `note` text COLLATE utf8mb4_unicode_ci,
  `date_ts` timestamp NULL DEFAULT NULL,
  `created_by_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_return_products`
--

CREATE TABLE `purchase_return_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `warehouse_id` bigint(20) UNSIGNED DEFAULT NULL,
  `purchase_return_id` bigint(20) UNSIGNED NOT NULL,
  `purchase_product_id` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'this_field_only_for_purchase_invoice_return.',
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `variant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `unit_cost_exc_tax` decimal(22,2) NOT NULL DEFAULT '0.00',
  `return_qty` decimal(22,2) NOT NULL DEFAULT '0.00',
  `purchased_qty` decimal(22,2) NOT NULL DEFAULT '0.00',
  `unit_id` bigint(20) UNSIGNED DEFAULT NULL,
  `unit_discount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `unit_discount_type` tinyint(4) NOT NULL DEFAULT '1',
  `unit_discount_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `tax_ac_id` bigint(20) UNSIGNED DEFAULT NULL,
  `unit_tax_type` tinyint(4) NOT NULL DEFAULT '1',
  `unit_tax_percent` decimal(22,2) NOT NULL DEFAULT '0.00',
  `unit_tax_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `unit_cost_inc_tax` decimal(22,2) NOT NULL DEFAULT '0.00',
  `return_subtotal` decimal(22,2) NOT NULL DEFAULT '0.00',
  `is_delete_in_update` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1=final;2=draft;3=order;4=quotation;5=hold;6=suspended',
  `invoice_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quotation_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `draft_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hold_invoice_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `suspend_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `customer_account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sale_account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `pay_term` tinyint(4) DEFAULT NULL,
  `pay_term_number` bigint(20) DEFAULT NULL,
  `total_item` decimal(8,2) NOT NULL DEFAULT '0.00',
  `total_qty` decimal(22,2) NOT NULL DEFAULT '0.00',
  `total_sold_qty` decimal(22,2) NOT NULL DEFAULT '0.00',
  `total_quotation_qty` decimal(22,2) NOT NULL DEFAULT '0.00',
  `total_ordered_qty` decimal(22,2) NOT NULL DEFAULT '0.00',
  `total_delivered_qty` decimal(22,2) NOT NULL DEFAULT '0.00',
  `total_left_qty` decimal(8,2) NOT NULL DEFAULT '0.00',
  `order_delivery_status` tinyint(4) DEFAULT NULL,
  `net_total_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `order_discount_type` tinyint(4) NOT NULL DEFAULT '1',
  `order_discount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `order_discount_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `earned_point` decimal(22,2) NOT NULL DEFAULT '0.00',
  `redeemed_point` decimal(22,2) NOT NULL DEFAULT '0.00',
  `redeem_point_rate` decimal(22,2) NOT NULL DEFAULT '0.00',
  `shipment_details` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipment_address` mediumtext COLLATE utf8mb4_unicode_ci,
  `shipment_charge` decimal(22,2) NOT NULL DEFAULT '0.00',
  `shipment_status` tinyint(4) NOT NULL DEFAULT '0',
  `delivered_to` mediumtext COLLATE utf8mb4_unicode_ci,
  `note` mediumtext COLLATE utf8mb4_unicode_ci,
  `sale_tax_ac_id` bigint(20) UNSIGNED DEFAULT NULL,
  `order_tax_percent` decimal(22,2) NOT NULL DEFAULT '0.00',
  `order_tax_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `total_invoice_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `paid` decimal(22,2) NOT NULL DEFAULT '0.00',
  `change_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `due` decimal(22,2) NOT NULL DEFAULT '0.00',
  `is_return_available` tinyint(1) NOT NULL DEFAULT '0',
  `exchange_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0=not_exchanged,1=exchanged',
  `sale_return_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `date` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_ts` timestamp NULL DEFAULT NULL,
  `sale_date_ts` timestamp NULL DEFAULT NULL,
  `quotation_date_ts` timestamp NULL DEFAULT NULL,
  `order_date_ts` timestamp NULL DEFAULT NULL,
  `draft_date_ts` timestamp NULL DEFAULT NULL,
  `quotation_status` tinyint(1) NOT NULL DEFAULT '0',
  `order_status` tinyint(1) NOT NULL DEFAULT '0',
  `draft_status` tinyint(1) NOT NULL DEFAULT '0',
  `sale_screen` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1=add_sale;2=pos',
  `sales_order_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by_id` bigint(20) UNSIGNED DEFAULT NULL,
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
  `variant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `warehouse_id` bigint(20) UNSIGNED DEFAULT NULL,
  `quantity` decimal(22,2) NOT NULL DEFAULT '0.00',
  `ordered_quantity` decimal(22,2) NOT NULL DEFAULT '0.00',
  `delivered_quantity` decimal(22,2) NOT NULL DEFAULT '0.00',
  `left_quantity` decimal(22,2) NOT NULL DEFAULT '0.00',
  `unit_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_unit_id` bigint(20) UNSIGNED DEFAULT NULL,
  `unit_discount_type` tinyint(4) NOT NULL DEFAULT '1',
  `unit_discount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `unit_discount_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `tax_ac_id` bigint(20) UNSIGNED DEFAULT NULL,
  `tax_type` tinyint(4) NOT NULL DEFAULT '1',
  `unit_tax_percent` decimal(22,2) NOT NULL DEFAULT '0.00',
  `unit_tax_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `unit_cost_inc_tax` decimal(22,2) NOT NULL DEFAULT '0.00' COMMENT 'this_col_for_invoice_profit_report',
  `unit_price_exc_tax` decimal(22,2) NOT NULL DEFAULT '0.00',
  `unit_price_inc_tax` decimal(22,2) NOT NULL DEFAULT '0.00',
  `subtotal` decimal(22,2) NOT NULL DEFAULT '0.00',
  `description` mediumtext COLLATE utf8mb4_unicode_ci,
  `ex_quantity` decimal(22,2) NOT NULL DEFAULT '0.00',
  `ex_status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0=no_exchanged,1=prepare_to_exchange,2=exchanged',
  `is_delete_in_update` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sale_returns`
--

CREATE TABLE `sale_returns` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `voucher_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sale_id` bigint(20) UNSIGNED DEFAULT NULL,
  `customer_account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `warehouse_id` bigint(20) UNSIGNED DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sale_account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `total_item` bigint(20) NOT NULL DEFAULT '0',
  `total_qty` decimal(22,2) NOT NULL DEFAULT '0.00',
  `net_total_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `return_discount_type` tinyint(4) NOT NULL DEFAULT '1',
  `return_discount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `return_discount_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `return_tax_ac_id` bigint(20) UNSIGNED DEFAULT NULL,
  `return_tax_percent` decimal(22,2) NOT NULL DEFAULT '0.00',
  `return_tax_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `total_return_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `paid` decimal(22,2) NOT NULL DEFAULT '0.00',
  `due` decimal(22,2) NOT NULL DEFAULT '0.00',
  `date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_ts` timestamp NULL DEFAULT NULL,
  `note` text COLLATE utf8mb4_unicode_ci,
  `created_by_id` bigint(20) UNSIGNED DEFAULT NULL,
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
  `sale_product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `variant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sold_quantity` decimal(22,2) NOT NULL DEFAULT '0.00',
  `unit_cost_inc_tax` decimal(22,2) NOT NULL DEFAULT '0.00',
  `unit_price_exc_tax` decimal(22,2) NOT NULL DEFAULT '0.00',
  `unit_price_inc_tax` decimal(22,2) NOT NULL DEFAULT '0.00',
  `unit_discount_type` tinyint(4) NOT NULL DEFAULT '1',
  `unit_discount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `unit_discount_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `tax_ac_id` bigint(20) UNSIGNED DEFAULT NULL,
  `tax_type` tinyint(4) NOT NULL DEFAULT '1',
  `unit_tax_percent` decimal(22,2) NOT NULL DEFAULT '0.00',
  `unit_tax_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `return_qty` decimal(22,2) NOT NULL DEFAULT '0.00',
  `unit_id` bigint(20) UNSIGNED DEFAULT NULL,
  `return_subtotal` decimal(22,2) NOT NULL DEFAULT '0.00',
  `is_delete_in_update` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `service_devices`
--

CREATE TABLE `service_devices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `short_description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `service_device_models`
--

CREATE TABLE `service_device_models` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `brand_id` bigint(20) UNSIGNED DEFAULT NULL,
  `device_id` bigint(20) UNSIGNED DEFAULT NULL,
  `service_checklist` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `service_job_cards`
--

CREATE TABLE `service_job_cards` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sale_id` bigint(20) UNSIGNED DEFAULT NULL,
  `job_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_account_id` bigint(20) UNSIGNED NOT NULL,
  `service_type` tinyint(4) NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `brand_id` bigint(20) UNSIGNED DEFAULT NULL,
  `device_id` bigint(20) UNSIGNED DEFAULT NULL,
  `device_model_id` bigint(20) UNSIGNED DEFAULT NULL,
  `serial_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service_checklist` text COLLATE utf8mb4_unicode_ci,
  `product_configuration` text COLLATE utf8mb4_unicode_ci,
  `problems_report` text COLLATE utf8mb4_unicode_ci,
  `product_condition` text COLLATE utf8mb4_unicode_ci,
  `technician_comment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_id` bigint(20) UNSIGNED DEFAULT NULL,
  `document` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `send_notification` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_field_1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_field_2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_field_3` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_field_4` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_field_5` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_item` decimal(22,2) NOT NULL DEFAULT '0.00',
  `total_qty` decimal(22,2) NOT NULL DEFAULT '0.00',
  `total_cost` decimal(22,2) NOT NULL DEFAULT '0.00',
  `date_ts` timestamp NOT NULL,
  `delivery_date_ts` timestamp NULL DEFAULT NULL,
  `due_date_ts` timestamp NULL DEFAULT NULL,
  `completed_at_ts` timestamp NULL DEFAULT NULL,
  `created_by_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `service_job_card_products`
--

CREATE TABLE `service_job_card_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `job_card_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `variant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `quantity` decimal(22,2) NOT NULL DEFAULT '0.00',
  `unit_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_unit_id` bigint(20) UNSIGNED DEFAULT NULL,
  `unit_discount_type` tinyint(4) NOT NULL DEFAULT '1',
  `unit_discount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `unit_discount_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `tax_ac_id` bigint(20) UNSIGNED DEFAULT NULL,
  `tax_type` tinyint(4) NOT NULL DEFAULT '1',
  `unit_tax_percent` decimal(22,2) NOT NULL DEFAULT '0.00',
  `unit_tax_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `unit_cost_inc_tax` decimal(22,2) NOT NULL DEFAULT '0.00' COMMENT 'this_col_for_invoice_profit_report',
  `unit_price_exc_tax` decimal(22,2) NOT NULL DEFAULT '0.00',
  `unit_price_inc_tax` decimal(22,2) NOT NULL DEFAULT '0.00',
  `subtotal` decimal(22,2) NOT NULL DEFAULT '0.00',
  `is_delete_in_update` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `service_status`
--

CREATE TABLE `service_status` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `color_code` varchar(17) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#000',
  `sort_order` int(11) DEFAULT NULL,
  `status_as_complete` tinyint(1) NOT NULL DEFAULT '0',
  `sms_template` text COLLATE utf8mb4_unicode_ci,
  `email_subject` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_body` text COLLATE utf8mb4_unicode_ci,
  `created_by_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shop_expire_date_histories`
--

CREATE TABLE `shop_expire_date_histories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `price_period` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `adjustable_price` decimal(22,2) DEFAULT NULL,
  `start_date` timestamp NOT NULL,
  `expire_date` date NOT NULL,
  `is_created` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
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
  `permission` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `plan_feature` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `enable_module` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `short_menus`
--

INSERT INTO `short_menus` (`id`, `url`, `name`, `icon`, `permission`, `plan_feature`, `enable_module`, `created_at`, `updated_at`) VALUES
(1, 'categories.index', 'Categories', 'fas fa-list', 'product_category_index', 'inventory', NULL, NULL, NULL),
(3, 'brands.index', 'Brands', 'fas fa-list', 'product_brand_index', 'inventory', NULL, NULL, NULL),
(4, 'products.index', 'Product List', 'fas fa-list', 'product_all', 'inventory', NULL, NULL, NULL),
(5, 'products.create', 'Add Product', 'fas fa-plus-circle', 'product_add', 'inventory', NULL, NULL, NULL),
(6, 'product.bulk.variants.index', 'Variants', 'fas fa-list', 'product_variant_index', 'inventory', NULL, NULL, NULL),
(7, 'product.import.create', 'Import Products', 'fas fa-file-import', 'product_import', 'inventory', NULL, NULL, NULL),
(8, 'selling.price.groups.index', 'Price Group', 'fas fa-list', 'selling_price_group_index', 'inventory', NULL, NULL, NULL),
(9, 'barcode.index', 'Barcodes', 'fas fa-barcode', 'generate_barcode', 'inventory', NULL, NULL, NULL),
(10, 'warranties.index', 'Warranties ', 'fas fa-list', 'product_warranty_index', 'inventory', NULL, NULL, NULL),
(11, 'contacts.manage.supplier.index,1', 'Suppliers', 'fas fa-list', 'supplier_all', 'contacts', 'modules__contacts', NULL, NULL),
(12, 'contacts.suppliers.import.create', 'Import Suppliers', 'fas fa-file-import', 'supplier_import', 'contacts', 'modules__contacts', NULL, NULL),
(13, 'contacts.manage.customer.index,1', 'Customers', 'fas fa-list', 'customer_all', 'contacts', 'modules__contacts', NULL, NULL),
(14, 'contacts.customers.import.create', 'Import Customers', 'fas fa-file-import', 'customer_import', 'contacts', 'modules__contacts', NULL, NULL),
(15, 'purchases.create', 'Add Purchase', 'fas fa-plus-circle', 'purchase_add', 'purchase', 'modules__purchases', NULL, NULL),
(16, 'purchases.index', 'Purchase List', 'fas fa-list', 'purchase_all', 'purchase', 'modules__purchases', NULL, NULL),
(17, 'purchase.orders.create', 'Add Purchase Order', 'fas fa-plus-circle', 'purchase_order_add', 'purchase', 'modules__purchases', NULL, NULL),
(18, 'purchase.orders.index', 'P/o List', 'fas fa-list', 'purchase_order_index', 'purchase', 'modules__purchases', NULL, NULL),
(19, 'purchase.returns.create', 'Add Purchase Return', 'fas fa-plus-circle', 'purchase_return_add', 'purchase', 'modules__purchases', NULL, NULL),
(20, 'purchase.returns.index', 'Purchase Return List', 'fas fa-list', 'purchase_return_index', 'purchase', 'modules__purchases', NULL, NULL),
(21, 'sales.create', 'Add Sale', 'fas fa-plus-circle', 'sales_create_by_add_sale', 'sales', 'modules__add_sale', NULL, NULL),
(22, 'sales.index', 'Manage Sales', 'fas fa-list', 'sales_index', 'sales', 'modules__add_sale', NULL, NULL),
(23, 'sales.pos.create', 'POS', 'fas fa-plus-circle', 'sales_create_by_pos', 'sales', 'modules__pos', NULL, NULL),
(25, 'sale.products.index', 'Sold Product List', 'fas fa-list', 'sold_product_list', 'sales', NULL, NULL, NULL),
(26, 'sale.orders.index', 'Sales Order List', 'fas fa-list', 'sales_orders_index', 'sales', NULL, NULL, NULL),
(29, 'sale.quotations.index', 'Quotation List', 'fas fa-list', 'sale_quotations_index', 'sales', NULL, NULL, NULL),
(30, 'sale.drafts.index', 'Draft List', 'fas fa-list', 'sale_drafts_index', 'sales', NULL, NULL, NULL),
(31, 'sale.shipments.index', 'Shipment List', 'fas fa-plus-circle', 'shipment_access', 'sales', NULL, NULL, NULL),
(32, 'sales.discounts.index', 'Discounts', 'fas fa-list', 'discounts', 'sales', NULL, NULL, NULL),
(33, 'sales.returns.create', 'Add Sales Return', 'fas fa-plus-circle', 'create_sales_return', 'sales', NULL, NULL, NULL),
(35, 'sales.returns.index', 'Sales Return List', 'fas fa-list', 'sales_return_index', 'sales', NULL, NULL, NULL),
(46, 'transfer.stocks.create', 'Add Transfer Stock', 'fas fa-plus-circle ', 'transfer_stock_create', 'transfer_stocks', 'modules__transfer_stock', NULL, NULL),
(47, 'transfer.stocks.index', 'Transfer Stock', 'fas fa-list', 'transfer_stock_index', 'transfer_stocks', 'modules__transfer_stock', NULL, NULL),
(48, 'receive.stock.from.branch.index', 'Receive From Warehouse', 'fas fa-list', 'transfer_stock_receive_from_warehouse', 'transfer_stocks', 'modules__transfer_stock', NULL, NULL),
(49, 'receive.stock.from.warehouse.index', 'Receive From Store/Company', 'fas fa-list', 'transfer_stock_receive_from_branch', 'transfer_stocks', 'modules__transfer_stock', NULL, NULL),
(50, 'stock.adjustments.create', 'Add Stock Adjustment', 'fas fa-plus-circle', 'stock_adjustment_add', 'stock_adjustments', 'modules__stock_adjustments', NULL, NULL),
(51, 'stock.adjustments.index', 'Stock Adjustment List', 'fas fa-list', 'stock_adjustment_all', 'stock_adjustments', 'modules__stock_adjustments', NULL, NULL),
(52, 'banks.index', 'Banks', 'fas fa-list', 'banks_index', 'accounting', 'modules__accounting', NULL, NULL),
(53, 'accounts.index', 'Accounts', 'fas fa-list', 'accounts_index', 'accounting', 'modules__accounting', NULL, NULL),
(54, 'receipts.index', 'Receipts', 'fas fa-list', 'receipts_index', 'accounting', 'modules__accounting', NULL, NULL),
(55, 'payments.index', 'Payments', 'fas fa-list', 'payments_index', 'accounting', 'modules__accounting', NULL, NULL),
(56, 'expenses.index', 'Expenses', 'fas fa-list', 'expenses_index', 'accounting', 'modules__accounting', NULL, NULL),
(57, 'contras.index', 'Contras', 'fas fa-list', 'contras_index', 'accounting', 'modules__accounting', NULL, NULL),
(58, 'users.create', 'Add User', 'fas fa-plus-circle', 'user_add', 'users', NULL, NULL, NULL),
(59, 'users.index', 'User List', 'fas fa-list', 'user_view', 'users', NULL, NULL, NULL),
(60, 'users.role.create', 'Add Role', 'fas fa-plus-circle', 'role_add', 'users', NULL, NULL, NULL),
(61, 'users.role.index', 'Role List', 'fas fa-list', 'role_view', 'users', NULL, NULL, NULL),
(62, 'settings.general.index', 'General Settings', 'fas fa-cogs', 'general_settings', 'setup', NULL, NULL, NULL),
(63, 'warehouses.index', 'Warehouses', 'fas fa-list', 'warehouses_index', 'setup', NULL, NULL, NULL),
(64, 'cash.counters.index', 'Cash Counters', 'fas fa-list', 'cash_counters_index', 'setup', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `short_menu_users`
--

CREATE TABLE `short_menu_users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `short_menu_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `screen_type` tinyint(4) NOT NULL DEFAULT '1',
  `is_delete_in_update` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sms`
--

CREATE TABLE `sms` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `to` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sms_bodies`
--

CREATE TABLE `sms_bodies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `format` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `body` text COLLATE utf8mb4_unicode_ci,
  `is_important` tinyint(4) NOT NULL DEFAULT '0',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sms_send`
--

CREATE TABLE `sms_send` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0 means not send, 1 means send, 2 means draft, 3 means junk, 4 means trash',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sms_servers`
--

CREATE TABLE `sms_servers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `server_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `host` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `api_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sender_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sender_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stock_adjustments`
--

CREATE TABLE `stock_adjustments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `expense_account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `voucher_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_item` bigint(20) NOT NULL DEFAULT '0',
  `total_qty` decimal(22,2) NOT NULL DEFAULT '0.00',
  `net_total_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `recovered_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  `date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_ts` timestamp NULL DEFAULT NULL,
  `reason` text COLLATE utf8mb4_unicode_ci,
  `created_by_id` bigint(20) UNSIGNED DEFAULT NULL,
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
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `warehouse_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `variant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `quantity` decimal(22,2) NOT NULL DEFAULT '0.00',
  `unit_id` bigint(20) UNSIGNED DEFAULT NULL,
  `unit_cost_inc_tax` decimal(22,2) NOT NULL DEFAULT '0.00',
  `subtotal` decimal(22,2) NOT NULL DEFAULT '0.00',
  `is_delete_in_update` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stock_chains`
--

CREATE TABLE `stock_chains` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `variant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `purchase_product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sale_product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `stock_issue_product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `stock_adjustment_product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `out_qty` decimal(22,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stock_issues`
--

CREATE TABLE `stock_issues` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `voucher_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `department_id` bigint(20) UNSIGNED DEFAULT NULL,
  `reported_by_id` bigint(20) UNSIGNED DEFAULT NULL,
  `total_item` bigint(20) NOT NULL DEFAULT '0',
  `total_qty` decimal(22,2) NOT NULL DEFAULT '0.00',
  `net_total_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_ts` timestamp NULL DEFAULT NULL,
  `remarks` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stock_issue_products`
--

CREATE TABLE `stock_issue_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `stock_issue_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `variant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `warehouse_id` bigint(20) UNSIGNED DEFAULT NULL,
  `quantity` decimal(22,2) NOT NULL DEFAULT '0.00',
  `unit_id` bigint(20) UNSIGNED DEFAULT NULL,
  `unit_cost_inc_tax` decimal(22,2) NOT NULL DEFAULT '0.00',
  `subtotal` decimal(22,2) NOT NULL DEFAULT '0.00',
  `is_delete_in_update` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions`
--

CREATE TABLE `subscriptions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `plan_id` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'initial plan and upgraded plan id will be go here.',
  `trial_start_date` timestamp NULL DEFAULT NULL,
  `current_shop_count` bigint(20) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `initial_plan_start_date` timestamp NULL DEFAULT NULL,
  `has_due_amount` tinyint(1) NOT NULL DEFAULT '0',
  `due_repayment_date` date DEFAULT NULL COMMENT 'if has any due so a date will come, on the other hand this col will be null',
  `has_business` tinyint(1) NOT NULL DEFAULT '0',
  `business_start_date` timestamp NULL DEFAULT NULL,
  `business_price_period` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `business_adjustable_price` decimal(22,2) DEFAULT NULL,
  `business_expire_date` date DEFAULT NULL,
  `is_completed_business_startup` tinyint(1) NOT NULL DEFAULT '0',
  `is_completed_branch_startup` tinyint(1) NOT NULL DEFAULT '0',
  `canceled_date` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscription_transactions`
--

CREATE TABLE `subscription_transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `transaction_type` tinyint(4) NOT NULL DEFAULT '0',
  `subscription_id` bigint(20) UNSIGNED NOT NULL,
  `plan_id` bigint(20) UNSIGNED NOT NULL,
  `payment_method_provider_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_method_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_trans_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `net_total` decimal(22,2) NOT NULL DEFAULT '0.00',
  `coupon_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discount_percent` decimal(22,2) NOT NULL DEFAULT '0.00',
  `discount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `total_payable_amount` decimal(22,2) NOT NULL,
  `paid` decimal(22,2) NOT NULL DEFAULT '0.00',
  `due` decimal(22,2) NOT NULL DEFAULT '0.00',
  `payment_status` tinyint(1) NOT NULL DEFAULT '0',
  `payment_date` timestamp NULL DEFAULT NULL,
  `details_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `details` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `todos`
--

CREATE TABLE `todos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `task` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `todo_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `priority` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `due_date` timestamp NULL DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `todo_users`
--

CREATE TABLE `todo_users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `todo_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `is_delete_in_update` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transfer_stocks`
--

CREATE TABLE `transfer_stocks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `voucher_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sender_branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `receiver_branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sender_warehouse_id` bigint(20) UNSIGNED DEFAULT NULL,
  `receiver_warehouse_id` bigint(20) UNSIGNED DEFAULT NULL,
  `total_item` decimal(22,2) NOT NULL DEFAULT '0.00',
  `total_qty` decimal(22,2) NOT NULL DEFAULT '0.00',
  `total_stock_value` decimal(22,2) NOT NULL DEFAULT '0.00',
  `total_send_qty` decimal(22,2) NOT NULL DEFAULT '0.00',
  `total_received_qty` decimal(22,2) NOT NULL DEFAULT '0.00',
  `total_pending_qty` decimal(22,2) NOT NULL DEFAULT '0.00',
  `received_stock_value` decimal(22,2) NOT NULL DEFAULT '0.00',
  `receive_status` tinyint(4) NOT NULL DEFAULT '0',
  `transfer_note` text COLLATE utf8mb4_unicode_ci,
  `receiver_note` text COLLATE utf8mb4_unicode_ci,
  `date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_ts` timestamp NULL DEFAULT NULL,
  `receive_date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `send_by_id` bigint(20) UNSIGNED DEFAULT NULL,
  `received_by_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transfer_stock_products`
--

CREATE TABLE `transfer_stock_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `transfer_stock_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `variant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `unit_id` bigint(20) UNSIGNED DEFAULT NULL,
  `unit_cost_inc_tax` decimal(22,2) NOT NULL DEFAULT '0.00',
  `subtotal` decimal(22,2) NOT NULL DEFAULT '0.00',
  `send_qty` decimal(22,2) NOT NULL DEFAULT '0.00',
  `received_qty` decimal(22,2) NOT NULL DEFAULT '0.00',
  `pending_qty` decimal(22,2) NOT NULL DEFAULT '0.00',
  `is_delete_in_update` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `units`
--

CREATE TABLE `units` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `base_unit_id` bigint(20) UNSIGNED DEFAULT NULL,
  `base_unit_multiplier` decimal(22,4) DEFAULT NULL,
  `created_by_id` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `units`
--

INSERT INTO `units` (`id`, `code`, `name`, `code_name`, `base_unit_id`, `base_unit_multiplier`, `created_by_id`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'U-001', 'Pieces', 'Pc', NULL, NULL, NULL, NULL, '2020-11-02 04:57:56', '2020-11-02 04:57:56'),
(2, 'U-002', 'Kilogram', 'Kg', NULL, NULL, NULL, NULL, '2020-11-03 00:41:16', '2020-11-03 00:41:16'),
(3, 'U-003', 'Dozen', 'Dz', NULL, NULL, NULL, NULL, '2020-11-03 00:42:06', '2020-12-30 00:26:39'),
(4, 'U-004', 'Gram', 'Gm', NULL, NULL, NULL, NULL, '2020-12-30 03:13:06', '2020-12-30 03:13:18'),
(5, 'U-005', 'Ton', 'tn', NULL, NULL, NULL, NULL, '2021-01-19 04:27:58', '2021-01-19 04:27:58'),
(6, 'U-006', 'Pound', 'lb', NULL, NULL, NULL, NULL, '2021-01-19 04:29:11', '2021-01-19 04:29:11'),
(7, 'U-007', 'Liter', 'lt', NULL, NULL, NULL, NULL, '2021-11-18 12:32:46', '2021-11-18 12:32:46'),
(8, 'U-008', 'Meter', 'm', NULL, NULL, NULL, NULL, '2022-11-20 11:46:50', '2022-11-20 11:46:50'),
(9, 'U-009', 'Millimeter', 'mm', NULL, NULL, NULL, NULL, '2022-11-20 11:46:50', '2022-11-20 11:46:50');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_type` tinyint(4) NOT NULL DEFAULT '1',
  `prefix` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `emp_id` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `username` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `shift_id` bigint(20) UNSIGNED DEFAULT NULL,
  `role_type` int(11) DEFAULT NULL COMMENT '1=super_admin,2=admin,3=others',
  `allow_login` tinyint(1) NOT NULL DEFAULT '0',
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_belonging_an_area` tinyint(1) NOT NULL DEFAULT '1',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sales_commission_percent` decimal(8,2) NOT NULL DEFAULT '0.00',
  `max_sales_discount_percent` decimal(8,2) NOT NULL DEFAULT '0.00',
  `phone` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_of_birth` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `marital_status` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `blood_group` varchar(8) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `facebook_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `twitter_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `instagram_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `guardian_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_proof_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_proof_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_id` bigint(20) UNSIGNED DEFAULT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postal_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `permanent_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `current_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_ac_holder_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_ac_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_identifier_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_branch` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tax_payer_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `language` varchar(6) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `department_id` bigint(20) UNSIGNED DEFAULT NULL,
  `designation_id` bigint(20) UNSIGNED DEFAULT NULL,
  `salary` decimal(22,2) NOT NULL DEFAULT '0.00',
  `salary_type` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_activity_logs`
--

CREATE TABLE `user_activity_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ip` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `date` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `report_date` timestamp NULL DEFAULT NULL,
  `action` tinyint(4) DEFAULT NULL,
  `subject_type` int(11) DEFAULT NULL,
  `descriptions` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `voucher_description_references`
--

CREATE TABLE `voucher_description_references` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `voucher_description_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sale_id` bigint(20) UNSIGNED DEFAULT NULL,
  `purchase_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sale_return_id` bigint(20) UNSIGNED DEFAULT NULL,
  `purchase_return_id` bigint(20) UNSIGNED DEFAULT NULL,
  `stock_adjustment_id` bigint(20) UNSIGNED DEFAULT NULL,
  `payroll_id` bigint(20) UNSIGNED DEFAULT NULL,
  `amount` decimal(22,2) NOT NULL DEFAULT '0.00',
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
  `is_global` tinyint(1) NOT NULL DEFAULT '0',
  `warehouse_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `warehouse_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` mediumtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `warranties`
--

CREATE TABLE `warranties` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `duration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `duration_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` mediumtext COLLATE utf8mb4_unicode_ci,
  `type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1=warranty;2=guaranty ',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `whatsapp_messages`
--

CREATE TABLE `whatsapp_messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `to` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '0',
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
  `workspace_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `priority` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` timestamp NULL DEFAULT NULL,
  `end_date` timestamp NULL DEFAULT NULL,
  `created_by_id` bigint(20) UNSIGNED DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
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
  `is_delete_in_update` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounting_vouchers`
--
ALTER TABLE `accounting_vouchers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `accounting_vouchers_branch_id_foreign` (`branch_id`),
  ADD KEY `accounting_vouchers_sale_ref_id_foreign` (`sale_ref_id`),
  ADD KEY `accounting_vouchers_sale_return_ref_id_foreign` (`sale_return_ref_id`),
  ADD KEY `accounting_vouchers_purchase_ref_id_foreign` (`purchase_ref_id`),
  ADD KEY `accounting_vouchers_purchase_return_ref_id_foreign` (`purchase_return_ref_id`),
  ADD KEY `accounting_vouchers_stock_adjustment_ref_id_foreign` (`stock_adjustment_ref_id`),
  ADD KEY `accounting_vouchers_payroll_ref_id_foreign` (`payroll_ref_id`),
  ADD KEY `accounting_vouchers_created_by_id_foreign` (`created_by_id`);

--
-- Indexes for table `accounting_voucher_descriptions`
--
ALTER TABLE `accounting_voucher_descriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `accounting_voucher_descriptions_accounting_voucher_id_foreign` (`accounting_voucher_id`),
  ADD KEY `accounting_voucher_descriptions_account_id_foreign` (`account_id`),
  ADD KEY `accounting_voucher_descriptions_payment_method_id_foreign` (`payment_method_id`);

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `accounts_account_group_id_foreign` (`account_group_id`),
  ADD KEY `accounts_contact_id_foreign` (`contact_id`),
  ADD KEY `accounts_created_by_id_foreign` (`created_by_id`),
  ADD KEY `accounts_branch_id_foreign` (`branch_id`),
  ADD KEY `accounts_bank_id_foreign` (`bank_id`);

--
-- Indexes for table `account_groups`
--
ALTER TABLE `account_groups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `account_groups_parent_group_id_foreign` (`parent_group_id`);

--
-- Indexes for table `account_ledgers`
--
ALTER TABLE `account_ledgers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `account_ledgers_voucher_description_id_foreign` (`voucher_description_id`),
  ADD KEY `account_ledgers_purchase_product_id_foreign` (`purchase_product_id`),
  ADD KEY `account_ledgers_purchase_return_product_id_foreign` (`purchase_return_product_id`),
  ADD KEY `account_ledgers_account_id_foreign` (`account_id`),
  ADD KEY `account_ledgers_adjustment_id_foreign` (`adjustment_id`),
  ADD KEY `account_ledgers_payroll_id_foreign` (`payroll_id`),
  ADD KEY `account_ledgers_loan_id_foreign` (`loan_id`),
  ADD KEY `account_ledgers_loan_payment_id_foreign` (`loan_payment_id`),
  ADD KEY `account_ledgers_purchase_id_foreign` (`purchase_id`),
  ADD KEY `account_ledgers_purchase_return_id_foreign` (`purchase_return_id`),
  ADD KEY `account_ledgers_sale_id_foreign` (`sale_id`),
  ADD KEY `account_ledgers_sale_product_id_foreign` (`sale_product_id`),
  ADD KEY `account_ledgers_sale_return_id_foreign` (`sale_return_id`),
  ADD KEY `account_ledgers_sale_return_product_id_foreign` (`sale_return_product_id`),
  ADD KEY `account_ledgers_branch_id_foreign` (`branch_id`);

--
-- Indexes for table `account_opening_balances`
--
ALTER TABLE `account_opening_balances`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `advertisements`
--
ALTER TABLE `advertisements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `advertisements_branch_id_foreign` (`branch_id`);

--
-- Indexes for table `advertise_attachments`
--
ALTER TABLE `advertise_attachments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `advertise_attachments_advertisement_id_foreign` (`advertisement_id`);

--
-- Indexes for table `allowance_employees`
--
ALTER TABLE `allowance_employees`
  ADD PRIMARY KEY (`id`),
  ADD KEY `allowance_employees_allowance_id_foreign` (`allowance_id`),
  ADD KEY `allowance_employees_user_id_foreign` (`user_id`);

--
-- Indexes for table `banks`
--
ALTER TABLE `banks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bank_access_branches`
--
ALTER TABLE `bank_access_branches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bank_access_branches_branch_id_foreign` (`branch_id`),
  ADD KEY `bank_access_branches_bank_account_id_foreign` (`bank_account_id`);

--
-- Indexes for table `barcode_settings`
--
ALTER TABLE `barcode_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `branches`
--
ALTER TABLE `branches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `branches_parent_branch_id_foreign` (`parent_branch_id`),
  ADD KEY `branches_shop_expire_date_history_id_foreign` (`shop_expire_date_history_id`);

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bulk_variants`
--
ALTER TABLE `bulk_variants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bulk_variants_created_by_id_foreign` (`created_by_id`);

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
-- Indexes for table `cash_registers`
--
ALTER TABLE `cash_registers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cash_registers_sale_account_id_foreign` (`sale_account_id`),
  ADD KEY `cash_registers_cash_counter_id_foreign` (`cash_counter_id`),
  ADD KEY `cash_registers_cash_account_id_foreign` (`cash_account_id`),
  ADD KEY `cash_registers_branch_id_foreign` (`branch_id`),
  ADD KEY `cash_registers_user_id_foreign` (`user_id`);

--
-- Indexes for table `cash_register_transactions`
--
ALTER TABLE `cash_register_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cash_register_transactions_cash_register_id_foreign` (`cash_register_id`),
  ADD KEY `cash_register_transactions_sale_id_foreign` (`sale_id`),
  ADD KEY `cash_register_transactions_voucher_description_id_foreign` (`voucher_description_id`),
  ADD KEY `cash_register_transactions_sale_ref_id_foreign` (`sale_ref_id`);

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
  ADD KEY `combo_products_combo_product_id_foreign` (`combo_product_id`),
  ADD KEY `combo_products_product_id_foreign` (`product_id`),
  ADD KEY `combo_products_product_variant_id_foreign` (`product_variant_id`);

--
-- Indexes for table `communication_contacts`
--
ALTER TABLE `communication_contacts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `communication_contacts_communication_contact_group_id_foreign` (`communication_contact_group_id`);

--
-- Indexes for table `communication_contact_groups`
--
ALTER TABLE `communication_contact_groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contacts_customer_group_id_foreign` (`customer_group_id`),
  ADD KEY `contacts_branch_id_foreign` (`branch_id`);

--
-- Indexes for table `contact_credit_limits`
--
ALTER TABLE `contact_credit_limits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contact_credit_limits_contact_id_foreign` (`contact_id`);

--
-- Indexes for table `currencies`
--
ALTER TABLE `currencies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_groups`
--
ALTER TABLE `customer_groups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_groups_branch_id_foreign` (`branch_id`),
  ADD KEY `customer_groups_price_group_id_foreign` (`price_group_id`);

--
-- Indexes for table `day_books`
--
ALTER TABLE `day_books`
  ADD PRIMARY KEY (`id`),
  ADD KEY `day_books_branch_id_foreign` (`branch_id`),
  ADD KEY `day_books_account_id_foreign` (`account_id`),
  ADD KEY `day_books_sale_id_foreign` (`sale_id`),
  ADD KEY `day_books_sale_return_id_foreign` (`sale_return_id`),
  ADD KEY `day_books_purchase_id_foreign` (`purchase_id`),
  ADD KEY `day_books_purchase_return_id_foreign` (`purchase_return_id`),
  ADD KEY `day_books_stock_adjustment_id_foreign` (`stock_adjustment_id`),
  ADD KEY `day_books_production_id_foreign` (`production_id`),
  ADD KEY `day_books_transfer_stock_id_foreign` (`transfer_stock_id`),
  ADD KEY `day_books_payroll_id_foreign` (`payroll_id`),
  ADD KEY `day_books_voucher_description_id_foreign` (`voucher_description_id`),
  ADD KEY `day_books_product_id_foreign` (`product_id`),
  ADD KEY `day_books_variant_id_foreign` (`variant_id`),
  ADD KEY `day_books_stock_issue_id_foreign` (`stock_issue_id`);

--
-- Indexes for table `discounts`
--
ALTER TABLE `discounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `discounts_branch_id_foreign` (`branch_id`),
  ADD KEY `discounts_brand_id_foreign` (`brand_id`),
  ADD KEY `discounts_category_id_foreign` (`category_id`),
  ADD KEY `discounts_price_group_id_foreign` (`price_group_id`);

--
-- Indexes for table `discount_products`
--
ALTER TABLE `discount_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `discount_products_discount_id_foreign` (`discount_id`),
  ADD KEY `discount_products_product_id_foreign` (`product_id`);

--
-- Indexes for table `emails`
--
ALTER TABLE `emails`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `email_bodies`
--
ALTER TABLE `email_bodies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `email_send`
--
ALTER TABLE `email_send`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `email_servers`
--
ALTER TABLE `email_servers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `email_templates`
--
ALTER TABLE `email_templates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `general_settings`
--
ALTER TABLE `general_settings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `general_settings_branch_id_foreign` (`branch_id`),
  ADD KEY `general_settings_parent_branch_id_foreign` (`parent_branch_id`);

--
-- Indexes for table `hrm_allowances`
--
ALTER TABLE `hrm_allowances`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hrm_attendances`
--
ALTER TABLE `hrm_attendances`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hrm_attendances_shift_id_foreign` (`shift_id`),
  ADD KEY `hrm_attendances_branch_id_foreign` (`branch_id`),
  ADD KEY `hrm_attendances_user_id_foreign` (`user_id`);

--
-- Indexes for table `hrm_departments`
--
ALTER TABLE `hrm_departments`
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
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hrm_holiday_branches`
--
ALTER TABLE `hrm_holiday_branches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hrm_holiday_branches_holiday_id_foreign` (`holiday_id`),
  ADD KEY `hrm_holiday_branches_branch_id_foreign` (`branch_id`);

--
-- Indexes for table `hrm_leaves`
--
ALTER TABLE `hrm_leaves`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hrm_leaves_branch_id_foreign` (`branch_id`),
  ADD KEY `hrm_leaves_leave_type_id_foreign` (`leave_type_id`),
  ADD KEY `hrm_leaves_employee_id_foreign` (`user_id`),
  ADD KEY `hrm_leaves_created_by_id_foreign` (`created_by_id`);

--
-- Indexes for table `hrm_leave_types`
--
ALTER TABLE `hrm_leave_types`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hrm_leave_types_branch_id_foreign` (`branch_id`);

--
-- Indexes for table `hrm_payrolls`
--
ALTER TABLE `hrm_payrolls`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hrm_payrolls_branch_id_foreign` (`branch_id`),
  ADD KEY `hrm_payrolls_user_id_foreign` (`user_id`),
  ADD KEY `hrm_payrolls_expense_account_id_foreign` (`expense_account_id`),
  ADD KEY `hrm_payrolls_created_by_id_foreign` (`created_by_id`);

--
-- Indexes for table `hrm_payroll_allowances`
--
ALTER TABLE `hrm_payroll_allowances`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hrm_payroll_allowances_payroll_id_foreign` (`payroll_id`),
  ADD KEY `hrm_payroll_allowances_allowance_id_foreign` (`allowance_id`);

--
-- Indexes for table `hrm_payroll_deductions`
--
ALTER TABLE `hrm_payroll_deductions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hrm_payroll_deductions_payroll_id_foreign` (`payroll_id`),
  ADD KEY `hrm_payroll_deductions_deduction_id_foreign` (`deduction_id`);

--
-- Indexes for table `hrm_shifts`
--
ALTER TABLE `hrm_shifts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoice_layouts`
--
ALTER TABLE `invoice_layouts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice_layouts_branch_id_foreign` (`branch_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

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
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `money_receipts`
--
ALTER TABLE `money_receipts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `money_receipts_contact_id_foreign` (`contact_id`),
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
-- Indexes for table `payment_method_settings`
--
ALTER TABLE `payment_method_settings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payment_method_settings_payment_method_id_foreign` (`payment_method_id`),
  ADD KEY `payment_method_settings_branch_id_foreign` (`branch_id`),
  ADD KEY `payment_method_settings_account_id_foreign` (`account_id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

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
  ADD KEY `processes_branch_id_foreign` (`branch_id`),
  ADD KEY `processes_product_id_foreign` (`product_id`),
  ADD KEY `processes_variant_id_foreign` (`variant_id`),
  ADD KEY `processes_unit_id_foreign` (`unit_id`);

--
-- Indexes for table `process_ingredients`
--
ALTER TABLE `process_ingredients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `process_ingredients_tax_ac_id_foreign` (`tax_ac_id`),
  ADD KEY `process_ingredients_process_id_foreign` (`process_id`),
  ADD KEY `process_ingredients_product_id_foreign` (`product_id`),
  ADD KEY `process_ingredients_variant_id_foreign` (`variant_id`),
  ADD KEY `process_ingredients_unit_id_foreign` (`unit_id`);

--
-- Indexes for table `productions`
--
ALTER TABLE `productions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `productions_branch_id_foreign` (`branch_id`),
  ADD KEY `productions_store_warehouse_id_foreign` (`store_warehouse_id`),
  ADD KEY `productions_stock_warehouse_id_foreign` (`stock_warehouse_id`),
  ADD KEY `productions_process_id_foreign` (`process_id`),
  ADD KEY `productions_product_id_foreign` (`product_id`),
  ADD KEY `productions_variant_id_foreign` (`variant_id`),
  ADD KEY `productions_unit_id_foreign` (`unit_id`),
  ADD KEY `productions_tax_ac_id_foreign` (`tax_ac_id`);

--
-- Indexes for table `production_ingredients`
--
ALTER TABLE `production_ingredients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `production_ingredients_tax_ac_id_foreign` (`tax_ac_id`),
  ADD KEY `production_ingredients_production_id_foreign` (`production_id`),
  ADD KEY `production_ingredients_product_id_foreign` (`product_id`),
  ADD KEY `production_ingredients_variant_id_foreign` (`variant_id`),
  ADD KEY `production_ingredients_unit_id_foreign` (`unit_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `products_tax_ac_id_foreign` (`tax_ac_id`),
  ADD KEY `products_category_id_foreign` (`category_id`),
  ADD KEY `products_sub_category_id_foreign` (`sub_category_id`),
  ADD KEY `products_brand_id_foreign` (`brand_id`),
  ADD KEY `products_unit_id_foreign` (`unit_id`),
  ADD KEY `products_warranty_id_foreign` (`warranty_id`);

--
-- Indexes for table `product_access_branches`
--
ALTER TABLE `product_access_branches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_access_branches_branch_id_foreign` (`branch_id`),
  ADD KEY `product_access_branches_product_id_foreign` (`product_id`),
  ADD KEY `product_access_branches_variant_id_foreign` (`variant_id`);

--
-- Indexes for table `product_ledgers`
--
ALTER TABLE `product_ledgers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_ledgers_branch_id_foreign` (`branch_id`),
  ADD KEY `product_ledgers_warehouse_id_foreign` (`warehouse_id`),
  ADD KEY `product_ledgers_product_id_foreign` (`product_id`),
  ADD KEY `product_ledgers_variant_id_foreign` (`variant_id`),
  ADD KEY `product_ledgers_sale_product_id_foreign` (`sale_product_id`),
  ADD KEY `product_ledgers_purchase_product_id_foreign` (`purchase_product_id`),
  ADD KEY `product_ledgers_purchase_return_product_id_foreign` (`purchase_return_product_id`),
  ADD KEY `product_ledgers_opening_stock_product_id_foreign` (`opening_stock_product_id`),
  ADD KEY `product_ledgers_stock_adjustment_product_id_foreign` (`stock_adjustment_product_id`),
  ADD KEY `product_ledgers_production_id_foreign` (`production_id`),
  ADD KEY `product_ledgers_production_ingredient_id_foreign` (`production_ingredient_id`),
  ADD KEY `product_ledgers_transfer_stock_product_id_foreign` (`transfer_stock_product_id`),
  ADD KEY `product_ledgers_stock_issue_product_id_foreign` (`stock_issue_product_id`);

--
-- Indexes for table `product_opening_stocks`
--
ALTER TABLE `product_opening_stocks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_opening_stocks_branch_id_foreign` (`branch_id`),
  ADD KEY `product_opening_stocks_warehouse_id_foreign` (`warehouse_id`),
  ADD KEY `product_opening_stocks_product_id_foreign` (`product_id`),
  ADD KEY `product_opening_stocks_variant_id_foreign` (`variant_id`);

--
-- Indexes for table `product_stocks`
--
ALTER TABLE `product_stocks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_stocks_branch_id_foreign` (`branch_id`),
  ADD KEY `product_stocks_warehouse_id_foreign` (`warehouse_id`),
  ADD KEY `product_stocks_product_id_foreign` (`product_id`),
  ADD KEY `product_stocks_variant_id_foreign` (`variant_id`);

--
-- Indexes for table `product_units`
--
ALTER TABLE `product_units`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_units_product_id_foreign` (`product_id`),
  ADD KEY `product_units_variant_id_foreign` (`variant_id`),
  ADD KEY `product_units_base_unit_id_foreign` (`base_unit_id`),
  ADD KEY `product_units_assigned_unit_id_foreign` (`assigned_unit_id`);

--
-- Indexes for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_variants_product_id_foreign` (`product_id`);

--
-- Indexes for table `purchases`
--
ALTER TABLE `purchases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchases_supplier_account_id_foreign` (`supplier_account_id`),
  ADD KEY `purchases_purchase_tax_ac_id_foreign` (`purchase_tax_ac_id`),
  ADD KEY `purchases_purchase_order_id_foreign` (`purchase_order_id`),
  ADD KEY `purchases_warehouse_id_foreign` (`warehouse_id`),
  ADD KEY `purchases_branch_id_foreign` (`branch_id`),
  ADD KEY `purchases_purchase_account_id_foreign` (`purchase_account_id`);

--
-- Indexes for table `purchase_order_products`
--
ALTER TABLE `purchase_order_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_order_products_purchase_id_foreign` (`purchase_id`),
  ADD KEY `purchase_order_products_product_id_foreign` (`product_id`),
  ADD KEY `purchase_order_products_variant_id_foreign` (`variant_id`),
  ADD KEY `purchase_order_products_unit_id_foreign` (`unit_id`),
  ADD KEY `purchase_order_products_tax_ac_id_foreign` (`tax_ac_id`);

--
-- Indexes for table `purchase_order_product_receives`
--
ALTER TABLE `purchase_order_product_receives`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_order_product_receives_order_product_id_foreign` (`order_product_id`);

--
-- Indexes for table `purchase_products`
--
ALTER TABLE `purchase_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_products_unit_id_foreign` (`unit_id`),
  ADD KEY `purchase_products_tax_ac_id_foreign` (`tax_ac_id`),
  ADD KEY `purchase_products_branch_id_foreign` (`branch_id`),
  ADD KEY `purchase_products_purchase_id_foreign` (`purchase_id`),
  ADD KEY `purchase_products_product_id_foreign` (`product_id`),
  ADD KEY `purchase_products_variant_id_foreign` (`variant_id`),
  ADD KEY `purchase_products_purchase_order_product_id_foreign` (`purchase_order_product_id`),
  ADD KEY `purchase_products_production_id_foreign` (`production_id`),
  ADD KEY `purchase_products_opening_stock_id_foreign` (`opening_stock_id`),
  ADD KEY `purchase_products_sale_return_product_id_foreign` (`sale_return_product_id`),
  ADD KEY `purchase_products_transfer_stock_product_id_foreign` (`transfer_stock_product_id`);

--
-- Indexes for table `purchase_returns`
--
ALTER TABLE `purchase_returns`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_returns_branch_id_foreign` (`branch_id`),
  ADD KEY `purchase_returns_purchase_id_foreign` (`purchase_id`),
  ADD KEY `purchase_returns_supplier_account_id_foreign` (`supplier_account_id`),
  ADD KEY `purchase_returns_purchase_account_id_foreign` (`purchase_account_id`),
  ADD KEY `purchase_returns_return_tax_ac_id_foreign` (`return_tax_ac_id`),
  ADD KEY `purchase_returns_created_by_id_foreign` (`created_by_id`);

--
-- Indexes for table `purchase_return_products`
--
ALTER TABLE `purchase_return_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_return_products_branch_id_foreign` (`branch_id`),
  ADD KEY `purchase_return_products_warehouse_id_foreign` (`warehouse_id`),
  ADD KEY `purchase_return_products_purchase_return_id_foreign` (`purchase_return_id`),
  ADD KEY `purchase_return_products_purchase_product_id_foreign` (`purchase_product_id`),
  ADD KEY `purchase_return_products_product_id_foreign` (`product_id`),
  ADD KEY `purchase_return_products_variant_id_foreign` (`variant_id`),
  ADD KEY `purchase_return_products_unit_id_foreign` (`unit_id`),
  ADD KEY `purchase_return_products_tax_ac_id_foreign` (`tax_ac_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sales_branch_id_foreign` (`branch_id`),
  ADD KEY `sales_sale_tax_ac_id_foreign` (`sale_tax_ac_id`),
  ADD KEY `sales_customer_account_id_foreign` (`customer_account_id`),
  ADD KEY `sales_sale_account_id_foreign` (`sale_account_id`),
  ADD KEY `sales_sales_order_id_foreign` (`sales_order_id`),
  ADD KEY `sales_created_by_id_foreign` (`created_by_id`);

--
-- Indexes for table `sale_products`
--
ALTER TABLE `sale_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sale_products_product_id_foreign` (`product_id`),
  ADD KEY `sale_products_variant_id_foreign` (`variant_id`),
  ADD KEY `sale_products_unit_id_foreign` (`unit_id`),
  ADD KEY `sale_products_product_unit_id_foreign` (`product_unit_id`),
  ADD KEY `sale_products_tax_ac_id_foreign` (`tax_ac_id`),
  ADD KEY `sale_products_sale_id_foreign` (`sale_id`),
  ADD KEY `sale_products_branch_id_foreign` (`branch_id`),
  ADD KEY `sale_products_warehouse_id_foreign` (`warehouse_id`);

--
-- Indexes for table `sale_returns`
--
ALTER TABLE `sale_returns`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sale_returns_return_tax_ac_id_foreign` (`return_tax_ac_id`),
  ADD KEY `sale_returns_created_by_id_foreign` (`created_by_id`),
  ADD KEY `sale_returns_sale_id_foreign` (`sale_id`),
  ADD KEY `sale_returns_customer_account_id_foreign` (`customer_account_id`),
  ADD KEY `sale_returns_warehouse_id_foreign` (`warehouse_id`),
  ADD KEY `sale_returns_branch_id_foreign` (`branch_id`),
  ADD KEY `sale_returns_sale_account_id_foreign` (`sale_account_id`);

--
-- Indexes for table `sale_return_products`
--
ALTER TABLE `sale_return_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sale_return_products_unit_id_foreign` (`unit_id`),
  ADD KEY `sale_return_products_tax_ac_id_foreign` (`tax_ac_id`),
  ADD KEY `sale_return_products_sale_return_id_foreign` (`sale_return_id`),
  ADD KEY `sale_return_products_sale_product_id_foreign` (`sale_product_id`),
  ADD KEY `sale_return_products_product_id_foreign` (`product_id`),
  ADD KEY `sale_return_products_variant_id_foreign` (`variant_id`);

--
-- Indexes for table `service_devices`
--
ALTER TABLE `service_devices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `service_devices_branch_id_foreign` (`branch_id`),
  ADD KEY `service_devices_created_by_id_foreign` (`created_by_id`);

--
-- Indexes for table `service_device_models`
--
ALTER TABLE `service_device_models`
  ADD PRIMARY KEY (`id`),
  ADD KEY `service_device_models_branch_id_foreign` (`branch_id`),
  ADD KEY `service_device_models_brand_id_foreign` (`brand_id`),
  ADD KEY `service_device_models_device_id_foreign` (`device_id`),
  ADD KEY `service_device_models_created_by_id_foreign` (`created_by_id`);

--
-- Indexes for table `service_job_cards`
--
ALTER TABLE `service_job_cards`
  ADD PRIMARY KEY (`id`),
  ADD KEY `service_job_cards_branch_id_foreign` (`branch_id`),
  ADD KEY `service_job_cards_sale_id_foreign` (`sale_id`),
  ADD KEY `service_job_cards_customer_account_id_foreign` (`customer_account_id`),
  ADD KEY `service_job_cards_brand_id_foreign` (`brand_id`),
  ADD KEY `service_job_cards_device_id_foreign` (`device_id`),
  ADD KEY `service_job_cards_device_model_id_foreign` (`device_model_id`),
  ADD KEY `service_job_cards_status_id_foreign` (`status_id`),
  ADD KEY `service_job_cards_created_by_id_foreign` (`created_by_id`);

--
-- Indexes for table `service_job_card_products`
--
ALTER TABLE `service_job_card_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `service_job_card_products_product_id_foreign` (`product_id`),
  ADD KEY `service_job_card_products_variant_id_foreign` (`variant_id`),
  ADD KEY `service_job_card_products_unit_id_foreign` (`unit_id`),
  ADD KEY `service_job_card_products_product_unit_id_foreign` (`product_unit_id`),
  ADD KEY `service_job_card_products_tax_ac_id_foreign` (`tax_ac_id`),
  ADD KEY `service_job_card_products_job_card_id_foreign` (`job_card_id`);

--
-- Indexes for table `service_status`
--
ALTER TABLE `service_status`
  ADD PRIMARY KEY (`id`),
  ADD KEY `service_status_branch_id_foreign` (`branch_id`),
  ADD KEY `service_status_created_by_id_foreign` (`created_by_id`);

--
-- Indexes for table `shop_expire_date_histories`
--
ALTER TABLE `shop_expire_date_histories`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `sms`
--
ALTER TABLE `sms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sms_bodies`
--
ALTER TABLE `sms_bodies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sms_send`
--
ALTER TABLE `sms_send`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sms_servers`
--
ALTER TABLE `sms_servers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stock_adjustments`
--
ALTER TABLE `stock_adjustments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_adjustments_expense_account_id_foreign` (`expense_account_id`),
  ADD KEY `stock_adjustments_branch_id_foreign` (`branch_id`),
  ADD KEY `stock_adjustments_created_by_id_foreign` (`created_by_id`);

--
-- Indexes for table `stock_adjustment_products`
--
ALTER TABLE `stock_adjustment_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_adjustment_products_unit_id_foreign` (`unit_id`),
  ADD KEY `stock_adjustment_products_stock_adjustment_id_foreign` (`stock_adjustment_id`),
  ADD KEY `stock_adjustment_products_branch_id_foreign` (`branch_id`),
  ADD KEY `stock_adjustment_products_warehouse_id_foreign` (`warehouse_id`),
  ADD KEY `stock_adjustment_products_product_id_foreign` (`product_id`),
  ADD KEY `stock_adjustment_products_variant_id_foreign` (`variant_id`);

--
-- Indexes for table `stock_chains`
--
ALTER TABLE `stock_chains`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_chains_variant_id_foreign` (`variant_id`),
  ADD KEY `stock_chains_product_id_foreign` (`product_id`),
  ADD KEY `branches_branch_id_foreign` (`branch_id`),
  ADD KEY `stock_chains_purchase_product_id_foreign` (`purchase_product_id`),
  ADD KEY `stock_chains_sale_product_id_foreign` (`sale_product_id`),
  ADD KEY `stock_chains_stock_issue_product_id_foreign` (`stock_issue_product_id`),
  ADD KEY `stock_chains_stock_adjustment_product_id_foreign` (`stock_adjustment_product_id`);

--
-- Indexes for table `stock_issues`
--
ALTER TABLE `stock_issues`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_issues_branch_id_foreign` (`branch_id`),
  ADD KEY `stock_issues_department_id_foreign` (`department_id`),
  ADD KEY `stock_issues_reported_by_id_foreign` (`reported_by_id`),
  ADD KEY `stock_issues_created_by_id_foreign` (`created_by_id`);

--
-- Indexes for table `stock_issue_products`
--
ALTER TABLE `stock_issue_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_issue_products_stock_issue_id_foreign` (`stock_issue_id`),
  ADD KEY `stock_issue_products_product_id_foreign` (`product_id`),
  ADD KEY `stock_issue_products_variant_id_foreign` (`variant_id`),
  ADD KEY `stock_issue_products_branch_id_foreign` (`branch_id`),
  ADD KEY `stock_issue_products_warehouse_id_foreign` (`warehouse_id`),
  ADD KEY `stock_issue_products_unit_id_foreign` (`unit_id`);

--
-- Indexes for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subscription_transactions`
--
ALTER TABLE `subscription_transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `todos`
--
ALTER TABLE `todos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `todos_created_by_id_foreign` (`created_by_id`),
  ADD KEY `todos_branch_id_foreign` (`branch_id`);

--
-- Indexes for table `todo_users`
--
ALTER TABLE `todo_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `todo_users_todo_id_foreign` (`todo_id`),
  ADD KEY `todo_users_user_id_foreign` (`user_id`);

--
-- Indexes for table `transfer_stocks`
--
ALTER TABLE `transfer_stocks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transfer_stocks_sender_branch_id_foreign` (`sender_branch_id`),
  ADD KEY `transfer_stocks_receiver_branch_id_foreign` (`receiver_branch_id`),
  ADD KEY `transfer_stocks_sender_warehouse_id_foreign` (`sender_warehouse_id`),
  ADD KEY `transfer_stocks_receiver_warehouse_id_foreign` (`receiver_warehouse_id`),
  ADD KEY `transfer_stocks_send_by_id_foreign` (`send_by_id`),
  ADD KEY `transfer_stocks_received_by_id_foreign` (`received_by_id`);

--
-- Indexes for table `transfer_stock_products`
--
ALTER TABLE `transfer_stock_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transfer_stock_products_transfer_stock_id_foreign` (`transfer_stock_id`),
  ADD KEY `transfer_stock_products_product_id_foreign` (`product_id`),
  ADD KEY `transfer_stock_products_variant_id_foreign` (`variant_id`),
  ADD KEY `transfer_stock_products_unit_id_foreign` (`unit_id`);

--
-- Indexes for table `units`
--
ALTER TABLE `units`
  ADD PRIMARY KEY (`id`),
  ADD KEY `units_base_unit_id_foreign` (`base_unit_id`),
  ADD KEY `units_created_by_id_foreign` (`created_by_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_currency_id_foreign` (`currency_id`),
  ADD KEY `users_shift_id_foreign` (`shift_id`),
  ADD KEY `users_branch_id_foreign` (`branch_id`),
  ADD KEY `users_department_id_foreign` (`department_id`),
  ADD KEY `users_designation_id_foreign` (`designation_id`);

--
-- Indexes for table `user_activity_logs`
--
ALTER TABLE `user_activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_activity_logs_branch_id_foreign` (`branch_id`),
  ADD KEY `user_activity_logs_user_id_foreign` (`user_id`);

--
-- Indexes for table `voucher_description_references`
--
ALTER TABLE `voucher_description_references`
  ADD PRIMARY KEY (`id`),
  ADD KEY `voucher_description_references_voucher_description_id_foreign` (`voucher_description_id`),
  ADD KEY `voucher_description_references_sale_id_foreign` (`sale_id`),
  ADD KEY `voucher_description_references_purchase_id_foreign` (`purchase_id`),
  ADD KEY `voucher_description_references_purchase_return_id_foreign` (`purchase_return_id`),
  ADD KEY `voucher_description_references_stock_adjustment_id_foreign` (`stock_adjustment_id`),
  ADD KEY `voucher_description_references_payroll_id_foreign` (`payroll_id`);

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
-- Indexes for table `whatsapp_messages`
--
ALTER TABLE `whatsapp_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `workspaces`
--
ALTER TABLE `workspaces`
  ADD PRIMARY KEY (`id`),
  ADD KEY `workspaces_branch_id_foreign` (`branch_id`),
  ADD KEY `workspaces_created_by_id_foreign` (`created_by_id`);

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
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounting_vouchers`
--
ALTER TABLE `accounting_vouchers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `accounting_voucher_descriptions`
--
ALTER TABLE `accounting_voucher_descriptions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `account_groups`
--
ALTER TABLE `account_groups`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `account_ledgers`
--
ALTER TABLE `account_ledgers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `account_opening_balances`
--
ALTER TABLE `account_opening_balances`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `advertisements`
--
ALTER TABLE `advertisements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `advertise_attachments`
--
ALTER TABLE `advertise_attachments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `allowance_employees`
--
ALTER TABLE `allowance_employees`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `banks`
--
ALTER TABLE `banks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bank_access_branches`
--
ALTER TABLE `bank_access_branches`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `barcode_settings`
--
ALTER TABLE `barcode_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bulk_variants`
--
ALTER TABLE `bulk_variants`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bulk_variant_children`
--
ALTER TABLE `bulk_variant_children`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cash_counters`
--
ALTER TABLE `cash_counters`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cash_registers`
--
ALTER TABLE `cash_registers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cash_register_transactions`
--
ALTER TABLE `cash_register_transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `combo_products`
--
ALTER TABLE `combo_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `communication_contacts`
--
ALTER TABLE `communication_contacts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `communication_contact_groups`
--
ALTER TABLE `communication_contact_groups`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contact_credit_limits`
--
ALTER TABLE `contact_credit_limits`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `currencies`
--
ALTER TABLE `currencies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=141;

--
-- AUTO_INCREMENT for table `customer_groups`
--
ALTER TABLE `customer_groups`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `day_books`
--
ALTER TABLE `day_books`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `discounts`
--
ALTER TABLE `discounts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `discount_products`
--
ALTER TABLE `discount_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `emails`
--
ALTER TABLE `emails`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `email_bodies`
--
ALTER TABLE `email_bodies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `email_send`
--
ALTER TABLE `email_send`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `email_servers`
--
ALTER TABLE `email_servers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `email_templates`
--
ALTER TABLE `email_templates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `general_settings`
--
ALTER TABLE `general_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=161;

--
-- AUTO_INCREMENT for table `hrm_allowances`
--
ALTER TABLE `hrm_allowances`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hrm_attendances`
--
ALTER TABLE `hrm_attendances`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hrm_departments`
--
ALTER TABLE `hrm_departments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hrm_designations`
--
ALTER TABLE `hrm_designations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hrm_holidays`
--
ALTER TABLE `hrm_holidays`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hrm_holiday_branches`
--
ALTER TABLE `hrm_holiday_branches`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hrm_leaves`
--
ALTER TABLE `hrm_leaves`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hrm_leave_types`
--
ALTER TABLE `hrm_leave_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hrm_payrolls`
--
ALTER TABLE `hrm_payrolls`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hrm_payroll_allowances`
--
ALTER TABLE `hrm_payroll_allowances`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hrm_payroll_deductions`
--
ALTER TABLE `hrm_payroll_deductions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hrm_shifts`
--
ALTER TABLE `hrm_shifts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoice_layouts`
--
ALTER TABLE `invoice_layouts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=193;

--
-- AUTO_INCREMENT for table `money_receipts`
--
ALTER TABLE `money_receipts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `months`
--
ALTER TABLE `months`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_methods`
--
ALTER TABLE `payment_methods`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `payment_method_settings`
--
ALTER TABLE `payment_method_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `price_groups`
--
ALTER TABLE `price_groups`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `price_group_products`
--
ALTER TABLE `price_group_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `processes`
--
ALTER TABLE `processes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `process_ingredients`
--
ALTER TABLE `process_ingredients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `productions`
--
ALTER TABLE `productions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `production_ingredients`
--
ALTER TABLE `production_ingredients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_access_branches`
--
ALTER TABLE `product_access_branches`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_ledgers`
--
ALTER TABLE `product_ledgers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_opening_stocks`
--
ALTER TABLE `product_opening_stocks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_stocks`
--
ALTER TABLE `product_stocks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_units`
--
ALTER TABLE `product_units`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_variants`
--
ALTER TABLE `product_variants`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchases`
--
ALTER TABLE `purchases`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_order_products`
--
ALTER TABLE `purchase_order_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_order_product_receives`
--
ALTER TABLE `purchase_order_product_receives`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_products`
--
ALTER TABLE `purchase_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_returns`
--
ALTER TABLE `purchase_returns`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_return_products`
--
ALTER TABLE `purchase_return_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sale_products`
--
ALTER TABLE `sale_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sale_returns`
--
ALTER TABLE `sale_returns`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sale_return_products`
--
ALTER TABLE `sale_return_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `service_devices`
--
ALTER TABLE `service_devices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `service_device_models`
--
ALTER TABLE `service_device_models`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `service_job_cards`
--
ALTER TABLE `service_job_cards`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `service_job_card_products`
--
ALTER TABLE `service_job_card_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `service_status`
--
ALTER TABLE `service_status`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shop_expire_date_histories`
--
ALTER TABLE `shop_expire_date_histories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `short_menus`
--
ALTER TABLE `short_menus`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `short_menu_users`
--
ALTER TABLE `short_menu_users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sms`
--
ALTER TABLE `sms`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sms_bodies`
--
ALTER TABLE `sms_bodies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sms_send`
--
ALTER TABLE `sms_send`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sms_servers`
--
ALTER TABLE `sms_servers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock_adjustments`
--
ALTER TABLE `stock_adjustments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock_adjustment_products`
--
ALTER TABLE `stock_adjustment_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock_chains`
--
ALTER TABLE `stock_chains`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock_issues`
--
ALTER TABLE `stock_issues`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock_issue_products`
--
ALTER TABLE `stock_issue_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subscription_transactions`
--
ALTER TABLE `subscription_transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `todos`
--
ALTER TABLE `todos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `todo_users`
--
ALTER TABLE `todo_users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transfer_stocks`
--
ALTER TABLE `transfer_stocks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transfer_stock_products`
--
ALTER TABLE `transfer_stock_products`
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
-- AUTO_INCREMENT for table `user_activity_logs`
--
ALTER TABLE `user_activity_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `voucher_description_references`
--
ALTER TABLE `voucher_description_references`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `warehouses`
--
ALTER TABLE `warehouses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `warranties`
--
ALTER TABLE `warranties`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `whatsapp_messages`
--
ALTER TABLE `whatsapp_messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `workspaces`
--
ALTER TABLE `workspaces`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `workspace_attachments`
--
ALTER TABLE `workspace_attachments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `workspace_tasks`
--
ALTER TABLE `workspace_tasks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `workspace_users`
--
ALTER TABLE `workspace_users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `accounting_vouchers`
--
ALTER TABLE `accounting_vouchers`
  ADD CONSTRAINT `accounting_vouchers_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `accounting_vouchers_created_by_id_foreign` FOREIGN KEY (`created_by_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `accounting_vouchers_payroll_ref_id_foreign` FOREIGN KEY (`payroll_ref_id`) REFERENCES `hrm_payrolls` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `accounting_vouchers_purchase_ref_id_foreign` FOREIGN KEY (`purchase_ref_id`) REFERENCES `purchases` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `accounting_vouchers_purchase_return_ref_id_foreign` FOREIGN KEY (`purchase_return_ref_id`) REFERENCES `purchase_returns` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `accounting_vouchers_sale_ref_id_foreign` FOREIGN KEY (`sale_ref_id`) REFERENCES `sales` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `accounting_vouchers_sale_return_ref_id_foreign` FOREIGN KEY (`sale_return_ref_id`) REFERENCES `sale_returns` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `accounting_vouchers_stock_adjustment_ref_id_foreign` FOREIGN KEY (`stock_adjustment_ref_id`) REFERENCES `stock_adjustments` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `accounting_voucher_descriptions`
--
ALTER TABLE `accounting_voucher_descriptions`
  ADD CONSTRAINT `accounting_voucher_descriptions_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `accounting_voucher_descriptions_accounting_voucher_id_foreign` FOREIGN KEY (`accounting_voucher_id`) REFERENCES `accounting_vouchers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `accounting_voucher_descriptions_payment_method_id_foreign` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_methods` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `accounts`
--
ALTER TABLE `accounts`
  ADD CONSTRAINT `accounts_account_group_id_foreign` FOREIGN KEY (`account_group_id`) REFERENCES `account_groups` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `accounts_bank_id_foreign` FOREIGN KEY (`bank_id`) REFERENCES `banks` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `accounts_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `accounts_contact_id_foreign` FOREIGN KEY (`contact_id`) REFERENCES `contacts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `accounts_created_by_id_foreign` FOREIGN KEY (`created_by_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `account_groups`
--
ALTER TABLE `account_groups`
  ADD CONSTRAINT `account_groups_parent_group_id_foreign` FOREIGN KEY (`parent_group_id`) REFERENCES `account_groups` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `account_ledgers`
--
ALTER TABLE `account_ledgers`
  ADD CONSTRAINT `account_ledgers_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `account_ledgers_adjustment_id_foreign` FOREIGN KEY (`adjustment_id`) REFERENCES `stock_adjustments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `account_ledgers_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `account_ledgers_loan_id_foreign` FOREIGN KEY (`loan_id`) REFERENCES `loans` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `account_ledgers_loan_payment_id_foreign` FOREIGN KEY (`loan_payment_id`) REFERENCES `loan_payments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `account_ledgers_payroll_id_foreign` FOREIGN KEY (`payroll_id`) REFERENCES `hrm_payrolls` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `account_ledgers_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `account_ledgers_purchase_product_id_foreign` FOREIGN KEY (`purchase_product_id`) REFERENCES `purchase_products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `account_ledgers_purchase_return_id_foreign` FOREIGN KEY (`purchase_return_id`) REFERENCES `purchase_returns` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `account_ledgers_purchase_return_product_id_foreign` FOREIGN KEY (`purchase_return_product_id`) REFERENCES `purchase_return_products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `account_ledgers_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `account_ledgers_sale_product_id_foreign` FOREIGN KEY (`sale_product_id`) REFERENCES `sale_products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `account_ledgers_sale_return_id_foreign` FOREIGN KEY (`sale_return_id`) REFERENCES `sale_returns` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `account_ledgers_sale_return_product_id_foreign` FOREIGN KEY (`sale_return_product_id`) REFERENCES `sale_return_products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `account_ledgers_voucher_description_id_foreign` FOREIGN KEY (`voucher_description_id`) REFERENCES `accounting_voucher_descriptions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `advertisements`
--
ALTER TABLE `advertisements`
  ADD CONSTRAINT `advertisements_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `advertise_attachments`
--
ALTER TABLE `advertise_attachments`
  ADD CONSTRAINT `advertise_attachments_advertisement_id_foreign` FOREIGN KEY (`advertisement_id`) REFERENCES `advertisements` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `allowance_employees`
--
ALTER TABLE `allowance_employees`
  ADD CONSTRAINT `allowance_employees_allowance_id_foreign` FOREIGN KEY (`allowance_id`) REFERENCES `hrm_allowances` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `allowance_employees_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `bank_access_branches`
--
ALTER TABLE `bank_access_branches`
  ADD CONSTRAINT `bank_access_branches_bank_account_id_foreign` FOREIGN KEY (`bank_account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bank_access_branches_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `branches`
--
ALTER TABLE `branches`
  ADD CONSTRAINT `branches_parent_branch_id_foreign` FOREIGN KEY (`parent_branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `branches_shop_expire_date_history_id_foreign` FOREIGN KEY (`shop_expire_date_history_id`) REFERENCES `shop_expire_date_histories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `bulk_variants`
--
ALTER TABLE `bulk_variants`
  ADD CONSTRAINT `bulk_variants_created_by_id_foreign` FOREIGN KEY (`created_by_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `bulk_variant_children`
--
ALTER TABLE `bulk_variant_children`
  ADD CONSTRAINT `bulk_variant_children_bulk_variant_id_foreign` FOREIGN KEY (`bulk_variant_id`) REFERENCES `bulk_variants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cash_counters`
--
ALTER TABLE `cash_counters`
  ADD CONSTRAINT `cash_counters_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `cash_registers`
--
ALTER TABLE `cash_registers`
  ADD CONSTRAINT `cash_registers_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cash_registers_cash_account_id_foreign` FOREIGN KEY (`cash_account_id`) REFERENCES `accounts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `cash_registers_cash_counter_id_foreign` FOREIGN KEY (`cash_counter_id`) REFERENCES `cash_counters` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `cash_registers_sale_account_id_foreign` FOREIGN KEY (`sale_account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cash_registers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `cash_register_transactions`
--
ALTER TABLE `cash_register_transactions`
  ADD CONSTRAINT `cash_register_transactions_cash_register_id_foreign` FOREIGN KEY (`cash_register_id`) REFERENCES `cash_registers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cash_register_transactions_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cash_register_transactions_sale_ref_id_foreign` FOREIGN KEY (`sale_ref_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cash_register_transactions_voucher_description_id_foreign` FOREIGN KEY (`voucher_description_id`) REFERENCES `accounting_voucher_descriptions` (`id`) ON DELETE CASCADE;

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
-- Constraints for table `communication_contacts`
--
ALTER TABLE `communication_contacts`
  ADD CONSTRAINT `communication_contacts_communication_contact_group_id_foreign` FOREIGN KEY (`communication_contact_group_id`) REFERENCES `communication_contact_groups` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `contacts`
--
ALTER TABLE `contacts`
  ADD CONSTRAINT `contacts_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `contacts_customer_group_id_foreign` FOREIGN KEY (`customer_group_id`) REFERENCES `customer_groups` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `contact_credit_limits`
--
ALTER TABLE `contact_credit_limits`
  ADD CONSTRAINT `contact_credit_limits_contact_id_foreign` FOREIGN KEY (`contact_id`) REFERENCES `contacts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `customer_groups`
--
ALTER TABLE `customer_groups`
  ADD CONSTRAINT `customer_groups_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `customer_groups_price_group_id_foreign` FOREIGN KEY (`price_group_id`) REFERENCES `price_groups` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `day_books`
--
ALTER TABLE `day_books`
  ADD CONSTRAINT `day_books_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `day_books_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `day_books_payroll_id_foreign` FOREIGN KEY (`payroll_id`) REFERENCES `hrm_payrolls` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `day_books_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `day_books_production_id_foreign` FOREIGN KEY (`production_id`) REFERENCES `productions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `day_books_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `day_books_purchase_return_id_foreign` FOREIGN KEY (`purchase_return_id`) REFERENCES `purchase_returns` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `day_books_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `day_books_sale_return_id_foreign` FOREIGN KEY (`sale_return_id`) REFERENCES `sale_returns` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `day_books_stock_adjustment_id_foreign` FOREIGN KEY (`stock_adjustment_id`) REFERENCES `stock_adjustments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `day_books_stock_issue_id_foreign` FOREIGN KEY (`stock_issue_id`) REFERENCES `stock_issues` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `day_books_transfer_stock_id_foreign` FOREIGN KEY (`transfer_stock_id`) REFERENCES `transfer_stocks` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `day_books_variant_id_foreign` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `day_books_voucher_description_id_foreign` FOREIGN KEY (`voucher_description_id`) REFERENCES `accounting_voucher_descriptions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `discounts`
--
ALTER TABLE `discounts`
  ADD CONSTRAINT `discounts_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `discounts_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `discounts_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `discounts_price_group_id_foreign` FOREIGN KEY (`price_group_id`) REFERENCES `price_groups` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `discount_products`
--
ALTER TABLE `discount_products`
  ADD CONSTRAINT `discount_products_discount_id_foreign` FOREIGN KEY (`discount_id`) REFERENCES `discounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `discount_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `general_settings`
--
ALTER TABLE `general_settings`
  ADD CONSTRAINT `general_settings_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `general_settings_parent_branch_id_foreign` FOREIGN KEY (`parent_branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hrm_attendances`
--
ALTER TABLE `hrm_attendances`
  ADD CONSTRAINT `hrm_attendances_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hrm_attendances_shift_id_foreign` FOREIGN KEY (`shift_id`) REFERENCES `hrm_shifts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `hrm_attendances_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hrm_holiday_branches`
--
ALTER TABLE `hrm_holiday_branches`
  ADD CONSTRAINT `hrm_holiday_branches_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hrm_holiday_branches_holiday_id_foreign` FOREIGN KEY (`holiday_id`) REFERENCES `hrm_holidays` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hrm_leaves`
--
ALTER TABLE `hrm_leaves`
  ADD CONSTRAINT `hrm_leaves_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hrm_leaves_created_by_id_foreign` FOREIGN KEY (`created_by_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hrm_leaves_leave_type_id_foreign` FOREIGN KEY (`leave_type_id`) REFERENCES `hrm_leave_types` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `hrm_leaves_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hrm_leave_types`
--
ALTER TABLE `hrm_leave_types`
  ADD CONSTRAINT `hrm_leave_types_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hrm_payrolls`
--
ALTER TABLE `hrm_payrolls`
  ADD CONSTRAINT `hrm_payrolls_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hrm_payrolls_created_by_id_foreign` FOREIGN KEY (`created_by_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `hrm_payrolls_expense_account_id_foreign` FOREIGN KEY (`expense_account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hrm_payrolls_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hrm_payroll_allowances`
--
ALTER TABLE `hrm_payroll_allowances`
  ADD CONSTRAINT `hrm_payroll_allowances_allowance_id_foreign` FOREIGN KEY (`allowance_id`) REFERENCES `hrm_allowances` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hrm_payroll_allowances_payroll_id_foreign` FOREIGN KEY (`payroll_id`) REFERENCES `hrm_payrolls` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hrm_payroll_deductions`
--
ALTER TABLE `hrm_payroll_deductions`
  ADD CONSTRAINT `hrm_payroll_deductions_deduction_id_foreign` FOREIGN KEY (`deduction_id`) REFERENCES `hrm_allowances` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hrm_payroll_deductions_payroll_id_foreign` FOREIGN KEY (`payroll_id`) REFERENCES `hrm_payrolls` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `invoice_layouts`
--
ALTER TABLE `invoice_layouts`
  ADD CONSTRAINT `invoice_layouts_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `messages_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `money_receipts`
--
ALTER TABLE `money_receipts`
  ADD CONSTRAINT `money_receipts_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `money_receipts_contact_id_foreign` FOREIGN KEY (`contact_id`) REFERENCES `contacts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payment_method_settings`
--
ALTER TABLE `payment_method_settings`
  ADD CONSTRAINT `payment_method_settings_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `payment_method_settings_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payment_method_settings_payment_method_id_foreign` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_methods` (`id`) ON DELETE CASCADE;

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
  ADD CONSTRAINT `processes_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `processes_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `processes_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `processes_variant_id_foreign` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `process_ingredients`
--
ALTER TABLE `process_ingredients`
  ADD CONSTRAINT `process_ingredients_process_id_foreign` FOREIGN KEY (`process_id`) REFERENCES `processes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `process_ingredients_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `process_ingredients_tax_ac_id_foreign` FOREIGN KEY (`tax_ac_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `process_ingredients_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `process_ingredients_variant_id_foreign` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `productions`
--
ALTER TABLE `productions`
  ADD CONSTRAINT `productions_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `productions_process_id_foreign` FOREIGN KEY (`process_id`) REFERENCES `processes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `productions_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `productions_stock_warehouse_id_foreign` FOREIGN KEY (`stock_warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `productions_store_warehouse_id_foreign` FOREIGN KEY (`store_warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `productions_tax_ac_id_foreign` FOREIGN KEY (`tax_ac_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `productions_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `productions_variant_id_foreign` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `production_ingredients`
--
ALTER TABLE `production_ingredients`
  ADD CONSTRAINT `production_ingredients_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `production_ingredients_production_id_foreign` FOREIGN KEY (`production_id`) REFERENCES `productions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `production_ingredients_tax_ac_id_foreign` FOREIGN KEY (`tax_ac_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `production_ingredients_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `production_ingredients_variant_id_foreign` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_tax_ac_id_foreign` FOREIGN KEY (`tax_ac_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_access_branches`
--
ALTER TABLE `product_access_branches`
  ADD CONSTRAINT `product_access_branches_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_access_branches_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_access_branches_variant_id_foreign` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_ledgers`
--
ALTER TABLE `product_ledgers`
  ADD CONSTRAINT `product_ledgers_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_ledgers_opening_stock_product_id_foreign` FOREIGN KEY (`opening_stock_product_id`) REFERENCES `product_opening_stocks` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_ledgers_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_ledgers_production_id_foreign` FOREIGN KEY (`production_id`) REFERENCES `productions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_ledgers_production_ingredient_id_foreign` FOREIGN KEY (`production_ingredient_id`) REFERENCES `production_ingredients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_ledgers_purchase_product_id_foreign` FOREIGN KEY (`purchase_product_id`) REFERENCES `purchase_products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_ledgers_purchase_return_product_id_foreign` FOREIGN KEY (`purchase_return_product_id`) REFERENCES `purchase_return_products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_ledgers_sale_product_id_foreign` FOREIGN KEY (`sale_product_id`) REFERENCES `sale_products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_ledgers_stock_adjustment_product_id_foreign` FOREIGN KEY (`stock_adjustment_product_id`) REFERENCES `stock_adjustment_products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_ledgers_stock_issue_product_id_foreign` FOREIGN KEY (`stock_issue_product_id`) REFERENCES `stock_issue_products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_ledgers_transfer_stock_product_id_foreign` FOREIGN KEY (`transfer_stock_product_id`) REFERENCES `transfer_stock_products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_ledgers_variant_id_foreign` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_ledgers_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_opening_stocks`
--
ALTER TABLE `product_opening_stocks`
  ADD CONSTRAINT `product_opening_stocks_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_opening_stocks_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_opening_stocks_variant_id_foreign` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_opening_stocks_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_stocks`
--
ALTER TABLE `product_stocks`
  ADD CONSTRAINT `product_stocks_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_stocks_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_stocks_variant_id_foreign` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_stocks_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_units`
--
ALTER TABLE `product_units`
  ADD CONSTRAINT `product_units_assigned_unit_id_foreign` FOREIGN KEY (`assigned_unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_units_base_unit_id_foreign` FOREIGN KEY (`base_unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_units_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_units_variant_id_foreign` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD CONSTRAINT `product_variants_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `purchases`
--
ALTER TABLE `purchases`
  ADD CONSTRAINT `purchases_purchase_order_id_foreign` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchases` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchases_purchase_tax_ac_id_foreign` FOREIGN KEY (`purchase_tax_ac_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchases_supplier_account_id_foreign` FOREIGN KEY (`supplier_account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `purchase_order_products`
--
ALTER TABLE `purchase_order_products`
  ADD CONSTRAINT `purchase_order_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_order_products_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_order_products_tax_ac_id_foreign` FOREIGN KEY (`tax_ac_id`) REFERENCES `accounts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `purchase_order_products_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `purchase_order_products_variant_id_foreign` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `purchase_order_product_receives`
--
ALTER TABLE `purchase_order_product_receives`
  ADD CONSTRAINT `purchase_order_product_receives_order_product_id_foreign` FOREIGN KEY (`order_product_id`) REFERENCES `purchase_order_products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `purchase_products`
--
ALTER TABLE `purchase_products`
  ADD CONSTRAINT `purchase_products_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_products_opening_stock_id_foreign` FOREIGN KEY (`opening_stock_id`) REFERENCES `product_opening_stocks` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_products_production_id_foreign` FOREIGN KEY (`production_id`) REFERENCES `productions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_products_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_products_purchase_order_product_id_foreign` FOREIGN KEY (`purchase_order_product_id`) REFERENCES `purchase_order_products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_products_sale_return_product_id_foreign` FOREIGN KEY (`sale_return_product_id`) REFERENCES `sale_return_products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_products_tax_ac_id_foreign` FOREIGN KEY (`tax_ac_id`) REFERENCES `accounts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `purchase_products_transfer_stock_product_id_foreign` FOREIGN KEY (`transfer_stock_product_id`) REFERENCES `transfer_stock_products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_products_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `purchase_products_variant_id_foreign` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `purchase_returns`
--
ALTER TABLE `purchase_returns`
  ADD CONSTRAINT `purchase_returns_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_returns_created_by_id_foreign` FOREIGN KEY (`created_by_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `purchase_returns_purchase_account_id_foreign` FOREIGN KEY (`purchase_account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_returns_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_returns_return_tax_ac_id_foreign` FOREIGN KEY (`return_tax_ac_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_returns_supplier_account_id_foreign` FOREIGN KEY (`supplier_account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `purchase_return_products`
--
ALTER TABLE `purchase_return_products`
  ADD CONSTRAINT `purchase_return_products_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_return_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_return_products_purchase_product_id_foreign` FOREIGN KEY (`purchase_product_id`) REFERENCES `purchase_products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_return_products_purchase_return_id_foreign` FOREIGN KEY (`purchase_return_id`) REFERENCES `purchase_returns` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_return_products_tax_ac_id_foreign` FOREIGN KEY (`tax_ac_id`) REFERENCES `accounts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `purchase_return_products_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `purchase_return_products_variant_id_foreign` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_return_products_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sales_created_by_id_foreign` FOREIGN KEY (`created_by_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `sales_customer_account_id_foreign` FOREIGN KEY (`customer_account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sales_sale_account_id_foreign` FOREIGN KEY (`sale_account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sales_sale_tax_ac_id_foreign` FOREIGN KEY (`sale_tax_ac_id`) REFERENCES `accounts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `sales_sales_order_id_foreign` FOREIGN KEY (`sales_order_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sale_products`
--
ALTER TABLE `sale_products`
  ADD CONSTRAINT `sale_products_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sale_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sale_products_product_unit_id_foreign` FOREIGN KEY (`product_unit_id`) REFERENCES `product_units` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `sale_products_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sale_products_tax_ac_id_foreign` FOREIGN KEY (`tax_ac_id`) REFERENCES `accounts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `sale_products_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `sale_products_variant_id_foreign` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sale_products_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sale_returns`
--
ALTER TABLE `sale_returns`
  ADD CONSTRAINT `sale_returns_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sale_returns_created_by_id_foreign` FOREIGN KEY (`created_by_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `sale_returns_customer_account_id_foreign` FOREIGN KEY (`customer_account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sale_returns_return_tax_ac_id_foreign` FOREIGN KEY (`return_tax_ac_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sale_returns_sale_account_id_foreign` FOREIGN KEY (`sale_account_id`) REFERENCES `accounts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `sale_returns_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sale_returns_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sale_return_products`
--
ALTER TABLE `sale_return_products`
  ADD CONSTRAINT `sale_return_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sale_return_products_sale_product_id_foreign` FOREIGN KEY (`sale_product_id`) REFERENCES `sale_products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sale_return_products_sale_return_id_foreign` FOREIGN KEY (`sale_return_id`) REFERENCES `sale_returns` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sale_return_products_tax_ac_id_foreign` FOREIGN KEY (`tax_ac_id`) REFERENCES `accounts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `sale_return_products_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `sale_return_products_variant_id_foreign` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `service_devices`
--
ALTER TABLE `service_devices`
  ADD CONSTRAINT `service_devices_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `service_devices_created_by_id_foreign` FOREIGN KEY (`created_by_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `service_device_models`
--
ALTER TABLE `service_device_models`
  ADD CONSTRAINT `service_device_models_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `service_device_models_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `service_device_models_created_by_id_foreign` FOREIGN KEY (`created_by_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `service_device_models_device_id_foreign` FOREIGN KEY (`device_id`) REFERENCES `service_devices` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `service_job_cards`
--
ALTER TABLE `service_job_cards`
  ADD CONSTRAINT `service_job_cards_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `service_job_cards_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `service_job_cards_created_by_id_foreign` FOREIGN KEY (`created_by_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `service_job_cards_customer_account_id_foreign` FOREIGN KEY (`customer_account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `service_job_cards_device_id_foreign` FOREIGN KEY (`device_id`) REFERENCES `service_devices` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `service_job_cards_device_model_id_foreign` FOREIGN KEY (`device_model_id`) REFERENCES `service_device_models` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `service_job_cards_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `service_job_cards_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `service_status` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `service_job_card_products`
--
ALTER TABLE `service_job_card_products`
  ADD CONSTRAINT `service_job_card_products_job_card_id_foreign` FOREIGN KEY (`job_card_id`) REFERENCES `service_job_cards` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `service_job_card_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `service_job_card_products_product_unit_id_foreign` FOREIGN KEY (`product_unit_id`) REFERENCES `product_units` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `service_job_card_products_tax_ac_id_foreign` FOREIGN KEY (`tax_ac_id`) REFERENCES `accounts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `service_job_card_products_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `service_job_card_products_variant_id_foreign` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `service_status`
--
ALTER TABLE `service_status`
  ADD CONSTRAINT `service_status_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `service_status_created_by_id_foreign` FOREIGN KEY (`created_by_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `short_menu_users`
--
ALTER TABLE `short_menu_users`
  ADD CONSTRAINT `short_menu_users_short_menu_id_foreign` FOREIGN KEY (`short_menu_id`) REFERENCES `short_menus` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `short_menu_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stock_adjustments`
--
ALTER TABLE `stock_adjustments`
  ADD CONSTRAINT `stock_adjustments_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_adjustments_created_by_id_foreign` FOREIGN KEY (`created_by_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `stock_adjustments_expense_account_id_foreign` FOREIGN KEY (`expense_account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stock_adjustment_products`
--
ALTER TABLE `stock_adjustment_products`
  ADD CONSTRAINT `stock_adjustment_products_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_adjustment_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_adjustment_products_stock_adjustment_id_foreign` FOREIGN KEY (`stock_adjustment_id`) REFERENCES `stock_adjustments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_adjustment_products_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_adjustment_products_variant_id_foreign` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_adjustment_products_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stock_chains`
--
ALTER TABLE `stock_chains`
  ADD CONSTRAINT `stock_chains_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_chains_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_chains_purchase_product_id_foreign` FOREIGN KEY (`purchase_product_id`) REFERENCES `purchase_products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_chains_sale_product_id_foreign` FOREIGN KEY (`sale_product_id`) REFERENCES `sale_products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_chains_stock_adjustment_product_id_foreign` FOREIGN KEY (`stock_adjustment_product_id`) REFERENCES `stock_adjustment_products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_chains_stock_issue_product_id_foreign` FOREIGN KEY (`stock_issue_product_id`) REFERENCES `stock_issue_products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_chains_variant_id_foreign` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stock_issues`
--
ALTER TABLE `stock_issues`
  ADD CONSTRAINT `stock_issues_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_issues_created_by_id_foreign` FOREIGN KEY (`created_by_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `stock_issues_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `hrm_departments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_issues_reported_by_id_foreign` FOREIGN KEY (`reported_by_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `stock_issue_products`
--
ALTER TABLE `stock_issue_products`
  ADD CONSTRAINT `stock_issue_products_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_issue_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_issue_products_stock_issue_id_foreign` FOREIGN KEY (`stock_issue_id`) REFERENCES `stock_issues` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_issue_products_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `stock_issue_products_variant_id_foreign` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_issue_products_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `todos`
--
ALTER TABLE `todos`
  ADD CONSTRAINT `todos_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `todos_created_by_id_foreign` FOREIGN KEY (`created_by_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `todo_users`
--
ALTER TABLE `todo_users`
  ADD CONSTRAINT `todo_users_todo_id_foreign` FOREIGN KEY (`todo_id`) REFERENCES `todos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `todo_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transfer_stocks`
--
ALTER TABLE `transfer_stocks`
  ADD CONSTRAINT `transfer_stocks_received_by_id_foreign` FOREIGN KEY (`received_by_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transfer_stocks_receiver_branch_id_foreign` FOREIGN KEY (`receiver_branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transfer_stocks_receiver_warehouse_id_foreign` FOREIGN KEY (`receiver_warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transfer_stocks_send_by_id_foreign` FOREIGN KEY (`send_by_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transfer_stocks_sender_branch_id_foreign` FOREIGN KEY (`sender_branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transfer_stocks_sender_warehouse_id_foreign` FOREIGN KEY (`sender_warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transfer_stock_products`
--
ALTER TABLE `transfer_stock_products`
  ADD CONSTRAINT `transfer_stock_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transfer_stock_products_transfer_stock_id_foreign` FOREIGN KEY (`transfer_stock_id`) REFERENCES `transfer_stocks` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transfer_stock_products_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `transfer_stock_products_variant_id_foreign` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `units`
--
ALTER TABLE `units`
  ADD CONSTRAINT `units_base_unit_id_foreign` FOREIGN KEY (`base_unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `units_created_by_id_foreign` FOREIGN KEY (`created_by_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `users_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `users_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `hrm_departments` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `users_designation_id_foreign` FOREIGN KEY (`designation_id`) REFERENCES `hrm_designations` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `users_shift_id_foreign` FOREIGN KEY (`shift_id`) REFERENCES `hrm_shifts` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `user_activity_logs`
--
ALTER TABLE `user_activity_logs`
  ADD CONSTRAINT `user_activity_logs_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_activity_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `voucher_description_references`
--
ALTER TABLE `voucher_description_references`
  ADD CONSTRAINT `voucher_description_references_payroll_id_foreign` FOREIGN KEY (`payroll_id`) REFERENCES `hrm_payrolls` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `voucher_description_references_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `voucher_description_references_purchase_return_id_foreign` FOREIGN KEY (`purchase_return_id`) REFERENCES `purchase_returns` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `voucher_description_references_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `voucher_description_references_stock_adjustment_id_foreign` FOREIGN KEY (`stock_adjustment_id`) REFERENCES `stock_adjustments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `voucher_description_references_voucher_description_id_foreign` FOREIGN KEY (`voucher_description_id`) REFERENCES `accounting_voucher_descriptions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `warehouses`
--
ALTER TABLE `warehouses`
  ADD CONSTRAINT `warehouses_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `workspaces`
--
ALTER TABLE `workspaces`
  ADD CONSTRAINT `workspaces_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `workspaces_created_by_id_foreign` FOREIGN KEY (`created_by_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `workspace_attachments`
--
ALTER TABLE `workspace_attachments`
  ADD CONSTRAINT `workspace_attachments_workspace_id_foreign` FOREIGN KEY (`workspace_id`) REFERENCES `workspaces` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `workspace_tasks`
--
ALTER TABLE `workspace_tasks`
  ADD CONSTRAINT `workspace_tasks_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `workspace_tasks_workspace_id_foreign` FOREIGN KEY (`workspace_id`) REFERENCES `workspaces` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `workspace_users`
--
ALTER TABLE `workspace_users`
  ADD CONSTRAINT `workspace_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `workspace_users_workspace_id_foreign` FOREIGN KEY (`workspace_id`) REFERENCES `workspaces` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
