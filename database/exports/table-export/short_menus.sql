-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 26, 2021 at 02:10 PM
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

--
-- Indexes for dumped tables
--

--
-- Indexes for table `short_menus`
--
ALTER TABLE `short_menus`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `short_menus`
--
ALTER TABLE `short_menus`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
