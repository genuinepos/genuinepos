-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jul 02, 2024 at 08:52 AM
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
-- Database: `pos_testtenant`
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
-- Table structure for table `assets`
--

CREATE TABLE `assets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `asset_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type_id` bigint(20) UNSIGNED DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `quantity` decimal(22,2) NOT NULL DEFAULT '0.00',
  `per_unit_value` decimal(22,2) NOT NULL DEFAULT '0.00',
  `total_value` decimal(22,2) NOT NULL DEFAULT '0.00',
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
-- Table structure for table `loans`
--

CREATE TABLE `loans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `purchase_id` bigint(20) UNSIGNED DEFAULT NULL,
  `reference_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `loan_company_id` bigint(20) UNSIGNED DEFAULT NULL,
  `account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `loan_account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `type` tinyint(4) DEFAULT NULL,
  `loan_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `due` decimal(22,2) NOT NULL DEFAULT '0.00',
  `total_paid` decimal(22,2) NOT NULL DEFAULT '0.00',
  `total_receive` decimal(22,2) NOT NULL DEFAULT '0.00',
  `report_date` timestamp NULL DEFAULT NULL,
  `loan_reason` text COLLATE utf8mb4_unicode_ci,
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
  `address` mediumtext COLLATE utf8mb4_unicode_ci,
  `pay_loan_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `pay_loan_due` decimal(22,2) NOT NULL DEFAULT '0.00',
  `get_loan_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `get_loan_due` decimal(22,2) NOT NULL DEFAULT '0.00',
  `total_pay` decimal(22,2) NOT NULL DEFAULT '0.00',
  `total_receive` decimal(22,2) NOT NULL DEFAULT '0.00',
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
  `paid_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `pay_mode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_method_id` bigint(20) UNSIGNED DEFAULT NULL,
  `date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `report_date` timestamp NULL DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `payment_type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1=pay_loan_payment;2=get_loan_payment',
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
  `paid_amount` decimal(22,2) NOT NULL DEFAULT '0.00',
  `payment_type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1=pay_loan_payment;2=get_loan_payment',
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
(122, '2024_06_09_172552_create_service_job_card_products_table', 1);

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
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
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
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `advertise_attachments`
--
ALTER TABLE `advertise_attachments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `allowance_employees`
--
ALTER TABLE `allowance_employees`
  ADD PRIMARY KEY (`id`),
  ADD KEY `allowance_employees_allowance_id_foreign` (`allowance_id`),
  ADD KEY `allowance_employees_user_id_foreign` (`user_id`);

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
  ADD KEY `day_books_variant_id_foreign` (`variant_id`);

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
-- Indexes for table `loans`
--
ALTER TABLE `loans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `loans_branch_id_foreign` (`branch_id`),
  ADD KEY `loans_purchase_id_foreign` (`purchase_id`),
  ADD KEY `loans_loan_company_id_foreign` (`loan_company_id`),
  ADD KEY `loans_account_id_foreign` (`account_id`),
  ADD KEY `loans_loan_account_id_foreign` (`loan_account_id`),
  ADD KEY `loans_created_user_id_foreign` (`created_user_id`);

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
  ADD KEY `loan_payments_account_id_foreign` (`account_id`),
  ADD KEY `loan_payments_payment_method_id_foreign` (`payment_method_id`),
  ADD KEY `loan_payments_user_id_foreign` (`user_id`);

--
-- Indexes for table `loan_payment_distributions`
--
ALTER TABLE `loan_payment_distributions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `loan_payment_distributions_loan_payment_id_foreign` (`loan_payment_id`),
  ADD KEY `loan_payment_distributions_loan_id_foreign` (`loan_id`);

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
  ADD KEY `product_ledgers_transfer_stock_product_id_foreign` (`transfer_stock_product_id`);

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `account_groups`
--
ALTER TABLE `account_groups`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

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
-- AUTO_INCREMENT for table `assets`
--
ALTER TABLE `assets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `asset_types`
--
ALTER TABLE `asset_types`
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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loans`
--
ALTER TABLE `loans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loan_companies`
--
ALTER TABLE `loan_companies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

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
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=123;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

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
-- Constraints for table `allowance_employees`
--
ALTER TABLE `allowance_employees`
  ADD CONSTRAINT `allowance_employees_allowance_id_foreign` FOREIGN KEY (`allowance_id`) REFERENCES `hrm_allowances` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `allowance_employees_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `assets`
--
ALTER TABLE `assets`
  ADD CONSTRAINT `assets_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `assets_type_id_foreign` FOREIGN KEY (`type_id`) REFERENCES `asset_types` (`id`) ON DELETE CASCADE;

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
-- Constraints for table `loans`
--
ALTER TABLE `loans`
  ADD CONSTRAINT `loans_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `loans_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `loans_created_user_id_foreign` FOREIGN KEY (`created_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `loans_loan_account_id_foreign` FOREIGN KEY (`loan_account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
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
  ADD CONSTRAINT `loan_payments_payment_method_id_foreign` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_methods` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `loan_payments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `loan_payment_distributions`
--
ALTER TABLE `loan_payment_distributions`
  ADD CONSTRAINT `loan_payment_distributions_loan_id_foreign` FOREIGN KEY (`loan_id`) REFERENCES `loans` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `loan_payment_distributions_loan_payment_id_foreign` FOREIGN KEY (`loan_payment_id`) REFERENCES `loan_payments` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

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
