-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 14, 2025 at 10:55 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `scentaura`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `Admin_id` int(2) NOT NULL,
  `Admin_name` varchar(30) NOT NULL,
  `Admin_password` text NOT NULL,
  `Admin_email` text NOT NULL,
  `currency` varchar(10) DEFAULT 'usd',
  `timezone` varchar(10) DEFAULT 'utc',
  `notifications` tinyint(1) DEFAULT 1,
  `dark_mode` tinyint(1) DEFAULT 0,
  `payment_gateway` varchar(50) DEFAULT 'paypal',
  `shipping_zone` varchar(50) DEFAULT 'domestic'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`Admin_id`, `Admin_name`, `Admin_password`, `Admin_email`, `currency`, `timezone`, `notifications`, `dark_mode`, `payment_gateway`, `shipping_zone`) VALUES
(1, 'Hetansh Shah', '$2y$10$C04KIhycc.W2ttPhywplG.kkqfsvHjn/UC2tB/PVYXZwJbb3mKGFS', 'hetanshshah2111@gmail.com', 'usd', 'ist', 1, 0, 'paypal', 'domestic');

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `origin_country` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `name`, `description`, `origin_country`, `created_at`) VALUES
(1, 'Chanel', 'Iconic French luxury brand known for timeless fragrances like Chanel No. 5.', 'France', '2025-04-17 12:55:30'),
(2, 'Dior', 'High-end brand with elegant perfumes like Sauvage and J\'adore.', 'France', '2025-04-17 12:56:15'),
(3, 'Tom Ford', 'Modern luxury brand with bold and sensual scents.', 'United States', '2025-04-17 12:57:45'),
(4, 'Versace ', 'Italian brand with vibrant and seductive fragrances.', 'Italy', '2025-04-17 12:58:17'),
(5, 'Gucci', 'Fashion-forward brand with a unique perfume line.', 'Italy', '2025-04-17 12:58:37'),
(6, 'Armani', 'Offers a sophisticated range of fragrances under Giorgio Armani.', 'Italy', '2025-04-17 12:58:53'),
(7, 'Yves Saint Laurent', 'Known for stylish and daring perfumes like Black Opium.', 'France', '2025-04-17 12:59:10'),
(8, 'Burberry', 'Classic British brand offering refined and elegant scents.', 'United Kingdom', '2025-04-17 12:59:57'),
(9, 'Calvin Klein', 'Known for minimalist and clean fragrances like CK One.', 'United States', '2025-04-17 13:01:24'),
(10, 'Jo Malone', 'Elegant British brand offering unique fragrance layering options.', 'United Kingdom', '2025-04-17 13:01:48');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 1,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `featured_products`
--

CREATE TABLE `featured_products` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `featured_products`
--

INSERT INTO `featured_products` (`id`, `product_id`, `created_at`) VALUES
(1, 1, '2025-05-13 16:56:20'),
(2, 2, '2025-05-13 16:56:32'),
(3, 4, '2025-05-13 16:56:36');

-- --------------------------------------------------------

--
-- Table structure for table `newsletter_subscribers`
--

CREATE TABLE `newsletter_subscribers` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `subscribed_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `newsletter_subscribers`
--

INSERT INTO `newsletter_subscribers` (`id`, `email`, `subscribed_at`) VALUES
(1, 'shahhetansh06@gmail.com', '2025-05-14 14:25:08');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `brand_id` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `stock` int(11) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `scent` varchar(100) DEFAULT NULL,
  `size` varchar(50) DEFAULT NULL,
  `pack_size` varchar(100) DEFAULT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `occasion` varchar(100) DEFAULT NULL,
  `concentration` varchar(50) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `brand_id`, `price`, `stock`, `category`, `scent`, `size`, `pack_size`, `gender`, `occasion`, `concentration`, `image_path`) VALUES
(1, 'Sauvage Eau de Toilette', 'Fresh and spicy scent from Dior for men', 2, 85.00, 50, '0', 'Bergamot, Pepper, Ambroxan', '100', 'Single', 'male', 'casual', 'EDT', 'uploads/1744951670_Y0685240_F068524009_E01_ZHC.jpg'),
(2, 'Chanel No. 5', 'Timeless floral scent for women', 1, 129.99, 30, '0', 'Jasmine, Rose, Vanilla', '100', 'Gift Pack', 'female', 'Formal', 'EDP', 'uploads/5.avif'),
(3, 'Chanel Coco Mademoiselle Eau de Parfum', 'A timeless floral scent for women with elegant woody undertones.', 1, 120.00, 50, '0', 'Citrus, Floral, Woody', '100', 'single', 'female', 'special, formal', 'EDP', 'uploads/1746634349_coco.avif'),
(4, 'Armani Code Profumo Eau de Parfum', 'Warm Spicy Elegance', 6, 115.00, 85, '0', 'Cardamom, Amber, Tonka Bean, Leather', '110', 'Single', 'male', 'Evening, Formal', 'EDP', 'uploads/1747114787_armani.webp'),
(5, 'Jo Malone Peony & Blush Suede Cologne', 'A delicate yet charming fragrance, Peony & Blush Suede blends soft peony petals with juicy red apple', 10, 145.00, 50, '0', 'Peony, Red Apple, Suede', '100', 'Single', 'female', 'Casual, Romantic, Special', 'Cologne', 'uploads/1747142894_1.avif');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `password`, `created_at`, `role`) VALUES
(1, 'Hetansh shah', 'shahhetansh06@gmail.com', '$2y$10$BAO8FS.YB2zYtWaNXJ5M5O.xSkh8u3JnlR5HyqERCAC6DuDrq5Mta', '2025-04-19 07:02:26', 'user'),
(2, 'Om Makadia', 'om.makadia@gmail.com', '$2y$10$7iWiS5STM4phvSphq.TdEuh5JmMyPUm5QwELf5FrOl2KiJjgXMpWC', '2025-04-19 07:04:17', 'user'),
(3, 'Hetansh', 'hetanshshah2111@gmail.com', '$2y$10$0BVYqHqvXZSOxr2nizGns.DMkx9abWrOHKTC9GGVKs8sW5A7pvVem', '2025-05-11 08:59:24', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`Admin_id`);

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `featured_products`
--
ALTER TABLE `featured_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `newsletter_subscribers`
--
ALTER TABLE `newsletter_subscribers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `brand_id` (`brand_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `Admin_id` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `featured_products`
--
ALTER TABLE `featured_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `newsletter_subscribers`
--
ALTER TABLE `newsletter_subscribers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `featured_products`
--
ALTER TABLE `featured_products`
  ADD CONSTRAINT `featured_products_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
