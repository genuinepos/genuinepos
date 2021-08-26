-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 26, 2021 at 02:09 PM
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

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pos_short_menus`
--
ALTER TABLE `pos_short_menus`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pos_short_menus`
--
ALTER TABLE `pos_short_menus`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
