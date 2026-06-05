-- MySQL dump 10.13  Distrib 8.0.46, for Linux (x86_64)
--
-- Host: scentaura-db.c9cgyiy62ylw.ap-south-1.rds.amazonaws.com    Database: scentaura
-- ------------------------------------------------------
-- Server version	8.4.8

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
SET @MYSQLDUMP_TEMP_LOG_BIN = @@SESSION.SQL_LOG_BIN;
SET @@SESSION.SQL_LOG_BIN= 0;

--
-- GTID state at the beginning of the backup 
--

SET @@GLOBAL.GTID_PURGED=/*!80000 '+'*/ '';

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin` (
  `Admin_id` int NOT NULL AUTO_INCREMENT,
  `Admin_name` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `Admin_password` text COLLATE utf8mb4_general_ci NOT NULL,
  `Admin_email` text COLLATE utf8mb4_general_ci NOT NULL,
  `currency` varchar(10) COLLATE utf8mb4_general_ci DEFAULT 'usd',
  `timezone` varchar(10) COLLATE utf8mb4_general_ci DEFAULT 'utc',
  `notifications` tinyint(1) DEFAULT '1',
  `dark_mode` tinyint(1) DEFAULT '0',
  `payment_gateway` varchar(50) COLLATE utf8mb4_general_ci DEFAULT 'paypal',
  `shipping_zone` varchar(50) COLLATE utf8mb4_general_ci DEFAULT 'domestic',
  PRIMARY KEY (`Admin_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin`
--

LOCK TABLES `admin` WRITE;
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
INSERT INTO `admin` VALUES (1,'Hetansh Shah','$2y$10$C04KIhycc.W2ttPhywplG.kkqfsvHjn/UC2tB/PVYXZwJbb3mKGFS','hetanshshah2111@gmail.com','usd','ist',1,0,'paypal','domestic');
/*!40000 ALTER TABLE `admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `brands`
--

DROP TABLE IF EXISTS `brands`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `brands` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `origin_country` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `brands`
--

LOCK TABLES `brands` WRITE;
/*!40000 ALTER TABLE `brands` DISABLE KEYS */;
INSERT INTO `brands` VALUES (1,'Chanel','Iconic French luxury brand known for timeless fragrances like Chanel No. 5.','France','2025-04-17 12:55:30'),(2,'Dior','High-end brand with elegant perfumes like Sauvage and J\'adore.','France','2025-04-17 12:56:15'),(3,'Tom Ford','Modern luxury brand with bold and sensual scents.','United States','2025-04-17 12:57:45'),(4,'Versace ','Italian brand with vibrant and seductive fragrances.','Italy','2025-04-17 12:58:17'),(5,'Gucci','Fashion-forward brand with a unique perfume line.','Italy','2025-04-17 12:58:37'),(6,'Armani','Offers a sophisticated range of fragrances under Giorgio Armani.','Italy','2025-04-17 12:58:53'),(7,'Yves Saint Laurent','Known for stylish and daring perfumes like Black Opium.','France','2025-04-17 12:59:10'),(8,'Burberry','Classic British brand offering refined and elegant scents.','United Kingdom','2025-04-17 12:59:57'),(9,'Calvin Klein','Known for minimalist and clean fragrances like CK One.','United States','2025-04-17 13:01:24'),(10,'Jo Malone','Elegant British brand offering unique fragrance layering options.','United Kingdom','2025-04-17 13:01:48');
/*!40000 ALTER TABLE `brands` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cart`
--

DROP TABLE IF EXISTS `cart`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cart` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int DEFAULT '1',
  `added_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cart`
--

LOCK TABLES `cart` WRITE;
/*!40000 ALTER TABLE `cart` DISABLE KEYS */;
/*!40000 ALTER TABLE `cart` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `featured_products`
--

DROP TABLE IF EXISTS `featured_products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `featured_products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `product_id` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `featured_products_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `featured_products`
--

LOCK TABLES `featured_products` WRITE;
/*!40000 ALTER TABLE `featured_products` DISABLE KEYS */;
INSERT INTO `featured_products` VALUES (1,1,'2025-05-13 16:56:20'),(2,2,'2025-05-13 16:56:32'),(3,4,'2025-05-13 16:56:36');
/*!40000 ALTER TABLE `featured_products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `newsletter_subscribers`
--

DROP TABLE IF EXISTS `newsletter_subscribers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `newsletter_subscribers` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `subscribed_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `newsletter_subscribers`
--

LOCK TABLES `newsletter_subscribers` WRITE;
/*!40000 ALTER TABLE `newsletter_subscribers` DISABLE KEYS */;
INSERT INTO `newsletter_subscribers` VALUES (1,'shahhetansh06@gmail.com','2025-05-14 14:25:08'),(2,'hetanshshah2111@gmail.com','2026-06-04 14:27:52');
/*!40000 ALTER TABLE `newsletter_subscribers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `expires_at` datetime NOT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `brand_id` int DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `stock` int DEFAULT NULL,
  `category` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `scent` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `size` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pack_size` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `gender` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `occasion` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `concentration` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `image_path` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `brand_id` (`brand_id`),
  CONSTRAINT `products_ibfk_1` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,'Sauvage Eau de Toilette','Fresh and spicy scent from Dior for men',2,85.00,50,'0','Bergamot, Pepper, Ambroxan','100','Single','male','casual','EDT','uploads/1744951670_Y0685240_F068524009_E01_ZHC.jpg'),(2,'Chanel No. 5','Timeless floral scent for women',1,129.99,30,'0','Jasmine, Rose, Vanilla','100','Gift Pack','female','Formal','EDP','uploads/5.avif'),(3,'Chanel Coco Mademoiselle Eau de Parfum','A timeless floral scent for women with elegant woody undertones.',1,120.00,50,'0','Citrus, Floral, Woody','100','single','female','special, formal','EDP','uploads/1746634349_coco.avif'),(4,'Armani Code Profumo Eau de Parfum','Warm Spicy Elegance',6,115.00,85,'0','Cardamom, Amber, Tonka Bean, Leather','110','Single','male','Evening, Formal','EDP','uploads/1747114787_armani.webp'),(5,'Jo Malone Peony & Blush Suede Cologne','A delicate yet charming fragrance, Peony & Blush Suede blends soft peony petals with juicy red apple',10,145.00,50,'0','Peony, Red Apple, Suede','100','Single','female','Casual, Romantic, Special','Cologne','uploads/1747142894_1.avif');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `full_name` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `role` varchar(5) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Hetansh shah','shahhetansh06@gmail.com','$2y$10$BAO8FS.YB2zYtWaNXJ5M5O.xSkh8u3JnlR5HyqERCAC6DuDrq5Mta','2025-04-19 07:02:26','user'),(2,'Om Makadia','om.makadia@gmail.com','$2y$10$7iWiS5STM4phvSphq.TdEuh5JmMyPUm5QwELf5FrOl2KiJjgXMpWC','2025-04-19 07:04:17','user'),(3,'Hetansh','hetanshshah2111@gmail.com','$2y$10$3qBvR5l8ZMuqOl914TNAFuCuVW8Qd1Vj2dwxyTEqNjQJjrLmP2wyC','2025-05-11 08:59:24','user'),(4,'Muchhala Bhavin','muchhalabhavin@gmail.com','$2y$10$8ebuCWI86LXqMfHk5gqhPeiMHsUZuBfCUmLd4u8iDncvPJI8rQXGm','2026-06-04 02:20:18',''),(5,'Viraj Vaghasiya','virajvaghasiya1811@gmail.com','$2y$10$L4EPGgprAokhpvl6jH2UwuG6uhbzg5RFiWspaHN20GImOEUl62Th6','2026-06-04 02:20:20',''),(6,'Hetansh Shah','hetansh.shah131322@marwadiuniversity.ac.in','$2y$10$9.GnEtSKyNsgiy11NTvLz.kyqhx3OeT4TlKRJ.CSSRRYHEIgcoJH6','2026-06-04 14:26:25','');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
SET @@SESSION.SQL_LOG_BIN = @MYSQLDUMP_TEMP_LOG_BIN;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-06-05 12:41:18
