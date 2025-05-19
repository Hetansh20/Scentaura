-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 19, 2025 at 09:28 PM
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
  `Admin_email` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`Admin_id`, `Admin_name`, `Admin_password`, `Admin_email`) VALUES
(1, 'Hetansh Shah', '$2y$10$LKZIh4JIeedRGsGmYO4lXOg5AQvUbtgYMdjsK2fnmewNppyQgVMGS', 'hetanshshah2111@gmail.com');

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
  `added_on` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `product_id`, `quantity`, `added_on`) VALUES
(8, 1, 4, 2, '2025-05-18 18:19:17'),
(10, 1, 5, 1, '2025-05-18 18:38:11');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `name`, `email`, `message`, `submitted_at`) VALUES
(1, '', '', '', '2025-05-16 13:39:43'),
(2, '', '', '', '2025-05-16 13:42:13');

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
(1, 'shahhetansh06@gmail.com', '2025-05-14 14:25:08'),
(13, 'hetanshshah2111@gmail.com', '2025-05-14 16:30:37'),
(14, 'ommakadia1615@gmail.com', '2025-05-14 16:31:22'),
(15, 'kashah71@gmail.com', '2025-05-14 16:47:39'),
(16, 'aaryan.vaghasana@gmail.com', '2025-05-16 12:23:14'),
(17, 'jaineelchhatraliya@gmail.com', '2025-05-16 12:37:57');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `payment_status` enum('pending','completed','failed') DEFAULT 'pending',
  `order_status` enum('processing','confirmed','shipped','delivered','cancelled') DEFAULT 'processing',
  `payment_method` varchar(50) DEFAULT NULL,
  `transaction_id` varchar(100) DEFAULT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `total_amount`, `payment_status`, `order_status`, `payment_method`, `transaction_id`, `order_date`) VALUES
(1, 3, 204.95, 'completed', 'confirmed', 'Stripe', 'txn_d2f9b5bb39eac0f3', '2025-05-19 08:36:56'),
(2, 3, 90.98, 'completed', 'confirmed', 'Stripe', 'txn_17d48d7e5f2800ff', '2025-05-19 08:56:44'),
(3, 3, 60.98, 'completed', 'confirmed', 'Stripe', 'txn_b9cd4aa6943e0f78', '2025-05-19 09:21:27');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(1, 1, 23, 5, 40.99),
(2, 2, 23, 1, 40.99),
(3, 2, 22, 1, 49.99),
(4, 3, 22, 1, 49.99),
(5, 3, 21, 1, 10.99);

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
(5, 'Jo Malone Peony & Blush Suede Cologne', 'A delicate yet charming fragrance, Peony & Blush Suede blends soft peony petals with juicy red apple', 10, 145.00, 50, '0', 'Peony, Red Apple, Suede', '100', 'Single', 'female', 'Casual, Romantic, Special', 'Cologne', 'uploads/1747142894_1.avif'),
(6, 'Blue De Chanel', 'Sophisticated, woody oriental.', 1, 209.99, 100, '0', 'aromatic, intensely woody fragrance', '150', 'single', 'male', 'formal', 'EDP', 'uploads/1747215543_bleu-de-chanel-parfum-spray-5fl-oz--packshot-default-107190-8819960250398.avif'),
(7, 'Acqua di Gio Pour Homme', 'Floral, modern, and feminine.', 6, 89.99, 20, '0', 'Marine, Citrus, Woody', '75', 'single', 'male', 'formal', 'EDP', 'uploads/1747216239_AcquadiGioEDP.webp'),
(8, 'Stronger With You Parfum', 'Warm, spicy, and sensual.', 6, 99.99, 10, '0', 'Chestnut, Vanilla, Spices', '200', 'single', 'unisex', 'formal', 'EDT', 'uploads/1747216392_931a97a5-13da-5169-ba51-2a244cafc152.jpg'),
(9, 'My Way Eau de Parfum', 'Floral, modern, and feminine.', 6, 79.99, 15, '0', 'Orange Blossom, Tuberose, Vanilla', '250', 'gift pack', 'female', 'special ', 'EDT', 'uploads/1747216514_df84dae3-034c-5269-a884-7d6ffbdcad6e.jpg'),
(10, 'Sì Passione Eau de Parfum Intense', 'Bold, fruity floral.', 6, 169.99, 26, '0', ' Blackcurrant, Rose, Vanilla', '100', 'pack of 3', 'female', 'formal', 'EDP', 'uploads/1747216645_cc30c19d-c7b8-55e3-a158-584ed1d73e2c.jpg'),
(11, 'Armani Code Eau de Parfum', 'Sophisticated, woody oriental.', 6, 49.99, 95, '0', 'Chestnut, Vanilla, Spices', '150', 'single', 'male', 'formal', 'EDP', 'uploads/1747216732_fdbc2ceGIOAA00000069_3.avif'),
(12, 'Acqua di Gioia', 'Fresh, aquatic, feminine.', 6, 56.79, 50, '0', 'Mint, Lemon, Jasmine', '250', 'single', 'female', 'causal ', 'EDT', 'uploads/1747216877_fdbc2ceGIOAA00000069_3.avif'),
(13, 'Gucci Guilty (Women)', 'Amber floral, modern classic.', 5, 49.99, 78, '0', 'Pepper, Lilac, Amber', '250', 'single', 'female', 'special ', 'EDP', 'uploads/1747217187_10Untitleddesign_7b43a217-b091-4ff0-8b49-ff8d69e55348_2048x.webp'),
(14, 'Flora by Gucci Eau de Parfum', 'Elegant floral.', 5, 75.00, 20, '0', ' Peony, Rose, Citrus, Sandalwood', '200', 'gift pack', 'unisex', 'formal', 'EDT', 'uploads/1747217346_d9bf1275-969d-51d5-b0df-41063e375860.jpg'),
(15, 'Gucci Bamboo', 'Soft, woody floral.', 5, 39.99, 89, '0', 'Lily, Ylang-Ylang, Vanilla', '150', 'single', 'female', 'special ', 'EDP', 'uploads/1747217562_b510fd45-11a7-5d66-a151-1cbe2f6b0976.jpg'),
(16, 'Gucci Intense Oud', 'Deep, woody amber unisex.', 5, 25.99, 20, '0', 'Oud, Incense, Amber, Leather', '250', 'single', 'unisex', 'causal ', 'EDP', 'uploads/1747217684_13250946-789b-528c-beb4-088d26143bd3.jpg'),
(18, 'Gucci Bloom Profumo Di Fiori', 'Rich, floral bouquet.', 5, 29.99, 20, '0', 'Jasmine Sambac, Rangoon Creeper', '100', 'single', 'unisex', 'causal ', 'EDT', 'uploads/1747218042_68165feeaee5b965c10a96cb-gucci-bloom-profumo-di-fiori-edp-spray.jpg'),
(19, 'Gucci Guilty Pour Homme', 'Woody aromatic for men.', 5, 45.99, 39, '0', 'Lavender, Lemon, Cedarwood', '100', 'single', 'male', 'special ', 'EDP', 'uploads/1747218165_ed2b237b-22c1-5e4e-af87-a9a73475743b.jpg'),
(20, 'Gucci Oud', 'Amber woody unisex.', 5, 79.99, 25, '0', 'Oud, Rose, Amber, Musk', '75', 'single', 'unisex', 'special ', 'EDT', 'uploads/1747218307_GMi3GkF2jdZIzgoq7TFohA6nNTcoC5Eu1SSWp4gu.jpg'),
(21, 'Gucci Rush', 'Fruity chypre for women.', 5, 10.99, 90, '0', 'Peach, Gardenia, Jasmine, Patchouli', '50', 'single', 'female', 'special ', 'EDT', 'uploads/1747218433_gucci-rush-for-women-sampledecants-267798.webp'),
(22, 'Versace Bright Crystal', 'Fresh, floral, feminine.', 4, 49.99, 29, '0', 'Pomegranate, Peony, Magnolia', '75', 'single', 'female', 'formal', 'EDP', 'uploads/1747218613_41692b15-aec1-5df5-b985-91ad45cf8775.jpg'),
(23, 'Versace Crystal Noir', 'Warm, spicy, mysterious.', 4, 40.99, 70, '0', 'Gardenia, Amber, Sandalwood', '50', 'single', 'female', 'special ', 'EDT', 'uploads/1747218715_67d1483f0883b56ea81125f4-versace-crystal-noir-eau-de-toilette.jpg');

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
(3, 'Hetansh', 'hetanshshah2111@gmail.com', '$2y$10$Yayjt2tnSgakpFvv1cOj8uls2zFJz0WzgXeYNu5w5r/ukhnVsfyKS', '2025-05-11 08:59:24', 'user');

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
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `featured_products`
--
ALTER TABLE `featured_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `newsletter_subscribers`
--
ALTER TABLE `newsletter_subscribers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

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
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `featured_products`
--
ALTER TABLE `featured_products`
  ADD CONSTRAINT `featured_products_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
