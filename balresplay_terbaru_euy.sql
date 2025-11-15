-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 12, 2025 at 06:15 PM
-- Server version: 11.7.2-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `balresplay`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('Super Admin','Kasir','Dapur') NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1=Aktif, 0=Nonaktif',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`user_id`, `username`, `password_hash`, `role`, `status`, `created_at`) VALUES
(2, 'mulyamin', '$2y$10$huUm1WXhlpHYOR.Vc4FO8Ob4oKd2y/65Wy3/MA6mDnrKnOvzNFppa', 'Super Admin', 1, '2025-11-08 11:38:24'),
(3, 'kasir', '$2y$10$g2NEIPftYNajO/42U1yIlOFHIRh7zkdvvIpbW06Cu/BScP3qiT0zS', 'Kasir', 1, '2025-11-12 18:25:24'),
(4, 'dapur', '$2y$10$V/NFweNt8P/nUOZmXydbfe49Ofz8X94rlj45A9DwFxuotiN5blyGq', 'Dapur', 1, '2025-11-12 18:25:49');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `table_number` int(11) NOT NULL,
  `order_time` datetime NOT NULL DEFAULT current_timestamp(),
  `status` enum('Menunggu Pembayaran','Kirim ke Dapur','Sedang Dimasak','Siap Diantar','Selesai','Dibatalkan') NOT NULL DEFAULT 'Menunggu Pembayaran',
  `payment_method` enum('Cash','QRIS') DEFAULT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `notes` text DEFAULT NULL,
  `confirmed_at` datetime DEFAULT NULL,
  `started_cooking_at` datetime DEFAULT NULL,
  `completed_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `table_number`, `order_time`, `status`, `payment_method`, `total_price`, `notes`, `confirmed_at`, `started_cooking_at`, `completed_at`, `created_at`) VALUES
(15, 1, '2025-11-12 21:30:54', 'Sedang Dimasak', 'QRIS', 43000.00, '', '2025-11-12 21:32:21', '2025-11-12 23:28:49', NULL, '2025-11-12 21:30:54'),
(16, 2, '2025-11-12 21:31:21', 'Menunggu Pembayaran', 'Cash', 112000.00, '', NULL, NULL, NULL, '2025-11-12 21:31:21'),
(17, 1, '2025-11-12 21:56:57', 'Menunggu Pembayaran', 'QRIS', 42000.00, '', NULL, NULL, NULL, '2025-11-12 21:56:57'),
(18, 1, '2025-11-12 21:57:15', 'Menunggu Pembayaran', 'QRIS', 42000.00, '', NULL, NULL, NULL, '2025-11-12 21:57:15'),
(19, 1, '2025-11-12 22:13:19', 'Menunggu Pembayaran', 'QRIS', 42000.00, '', NULL, NULL, NULL, '2025-11-12 22:13:19'),
(20, 1, '2025-11-12 23:02:53', 'Siap Diantar', 'Cash', 42000.00, '', '2025-11-12 23:04:03', '2025-11-12 23:05:32', '2025-11-12 23:05:33', '2025-11-12 23:02:53'),
(21, 6, '2025-11-12 23:03:12', 'Sedang Dimasak', 'QRIS', 32000.00, '', '2025-11-12 23:04:08', '2025-11-12 23:04:58', NULL, '2025-11-12 23:03:12'),
(22, 1, '2025-11-12 23:19:22', 'Kirim ke Dapur', 'QRIS', 42000.00, '', '2025-11-13 00:00:30', NULL, NULL, '2025-11-12 23:19:22'),
(23, 6, '2025-11-12 23:42:31', 'Siap Diantar', 'Cash', 72000.00, '', '2025-11-12 23:43:17', '2025-11-12 23:52:34', '2025-11-12 23:54:12', '2025-11-12 23:42:31'),
(24, 6, '2025-11-13 00:00:52', 'Sedang Dimasak', 'QRIS', 22500.00, '', '2025-11-13 00:01:32', '2025-11-13 00:03:25', NULL, '2025-11-13 00:00:52');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `variant_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price_per_item` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`order_item_id`, `order_id`, `variant_id`, `quantity`, `price_per_item`) VALUES
(22, 15, 8, 2, 20500.00),
(23, 16, 9, 2, 40000.00),
(24, 16, 10, 1, 30000.00),
(25, 17, 9, 1, 40000.00),
(26, 18, 9, 1, 40000.00),
(27, 19, 9, 1, 40000.00),
(28, 20, 9, 1, 40000.00),
(29, 21, 10, 1, 30000.00),
(30, 22, 9, 1, 40000.00),
(31, 23, 9, 1, 40000.00),
(32, 23, 10, 1, 30000.00),
(33, 24, 8, 1, 20500.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `category` varchar(50) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `name`, `description`, `category`, `image_url`, `created_at`, `updated_at`) VALUES
(5, 'Nasi Goreng', 'nasgor pedas asam manis', 'rice', 'images/uploads/69146fc27b555-liquid-purple-art-painting-abstract-colorful-background-with-color-splash-paints-modern-art.jpg', '2025-11-12 18:30:10', '2025-11-12 23:51:39'),
(6, 'Es teh panas', 'sejuk', 'tea', 'images/uploads/6914702a5552e-Figure_2.png', '2025-11-12 18:31:54', '2025-11-12 18:31:54');

-- --------------------------------------------------------

--
-- Table structure for table `product_variants`
--

CREATE TABLE `product_variants` (
  `variant_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `variant_name` varchar(50) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `is_available` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_variants`
--

INSERT INTO `product_variants` (`variant_id`, `product_id`, `variant_name`, `price`, `is_available`) VALUES
(8, 5, NULL, 20500.00, 1),
(9, 6, 'cold', 40000.00, 1),
(10, 6, 'hot', 30000.00, 1),
(11, 6, 'sejuk', 10500.00, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `idx_orders_status` (`status`),
  ADD KEY `idx_orders_table_number` (`table_number`),
  ADD KEY `idx_orders_time` (`order_time`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `variant_id` (`variant_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `idx_products_category` (`category`);

--
-- Indexes for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD PRIMARY KEY (`variant_id`),
  ADD KEY `product_id` (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `product_variants`
--
ALTER TABLE `product_variants`
  MODIFY `variant_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`variant_id`);

--
-- Constraints for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD CONSTRAINT `product_variants_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
