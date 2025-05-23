-- MariaDB dump 10.19  Distrib 10.4.28-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: bookstore_plus
-- ------------------------------------------------------
-- Server version	10.4.28-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `admin_users`
--

DROP TABLE IF EXISTS `admin_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_users`
--

LOCK TABLES `admin_users` WRITE;
/*!40000 ALTER TABLE `admin_users` DISABLE KEYS */;
INSERT INTO `admin_users` VALUES (1,'admin','$2y$10$J1KANgdYxddt75VAwliBBe8zWLfsYSylKfzYXJM0Gp7MN9JrNFnKq','2025-05-23 00:36:47');
/*!40000 ALTER TABLE `admin_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Fiction','Novels, short stories, and other fictional works','2025-05-23 01:00:01'),(2,'Non-Fiction','Educational books, biographies, and reference materials','2025-05-23 01:00:01'),(3,'Textbooks','Academic books for students of all levels','2025-05-23 01:00:01'),(4,'School Supplies','Notebooks, pens, pencils, and other stationery items','2025-05-23 01:00:01'),(5,'Art Supplies','Drawing materials, paints, and craft supplies','2025-05-23 01:00:01'),(6,'Children\'s Books','Books for young readers and picture books','2025-05-23 01:00:01'),(7,'Reference Books','Dictionaries, encyclopedias, and study guides','2025-05-23 01:00:01'),(8,'Office Supplies','Folders, binders, and other office materials','2025-05-23 01:00:01');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `subject` varchar(200) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messages`
--

LOCK TABLES `messages` WRITE;
/*!40000 ALTER TABLE `messages` DISABLE KEYS */;
INSERT INTO `messages` VALUES (1,'Alexa Reyes','alexareyes23@gmail.com','Order Issue - Missing Item','Hi, I recently placed an order (Order #NBS20250520) through your online store and just received the package today. However, one of the items I ordered — The Subtle Art of Not Giving a F*ck — wasn't included in the box. Can you please check if it was shipped separately or if there was a problem with the order? Looking forward to your help. Thanks!','2025-05-23 02:12:33');
/*!40000 ALTER TABLE `messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
INSERT INTO `order_items` VALUES (1,1,5,1,900.00),(2,2,5,1,900.00),(3,3,5,1,900.00),(4,4,5,1,900.00),(5,5,5,1,900.00),(6,6,5,1,900.00),(7,7,5,1,900.00),(8,8,5,1,900.00),(9,9,5,1,900.00),(10,10,5,1,900.00),(11,11,5,1,900.00),(12,12,5,1,900.00),(13,13,5,1,900.00),(14,14,5,1,900.00),(15,15,4,1,50.00),(16,16,4,1,50.00),(17,17,5,1,900.00),(18,18,5,1,900.00),(19,19,5,1,900.00),(20,20,4,1,50.00),(21,21,3,4,20.00),(22,22,5,1,900.00),(23,23,5,1,900.00),(24,24,5,1,900.00),(25,25,5,1,900.00),(26,26,5,1,900.00);
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (1,1,900.00,'delivered','2025-05-23 03:07:14'),(2,2,900.00,'processing','2025-05-23 03:40:05'),(3,2,900.00,'pending','2025-05-23 05:36:47'),(4,2,900.00,'pending','2025-05-23 05:36:52'),(5,2,900.00,'pending','2025-05-23 05:37:29'),(6,2,900.00,'pending','2025-05-23 05:39:14'),(7,2,900.00,'pending','2025-05-23 05:39:51'),(8,2,900.00,'pending','2025-05-23 05:40:08'),(9,2,900.00,'pending','2025-05-23 05:40:45'),(10,2,900.00,'pending','2025-05-23 05:44:25'),(11,2,900.00,'pending','2025-05-23 05:46:48'),(12,2,900.00,'pending','2025-05-23 05:47:23'),(13,2,900.00,'pending','2025-05-23 05:47:48'),(14,2,900.00,'pending','2025-05-23 05:48:03'),(15,2,50.00,'pending','2025-05-23 05:49:43'),(16,2,50.00,'processing','2025-05-23 05:50:04'),(17,2,900.00,'processing','2025-05-23 05:55:19'),(18,2,900.00,'pending','2025-05-23 05:56:22'),(19,2,900.00,'cancelled','2025-05-23 05:57:50'),(20,2,50.00,'delivered','2025-05-23 05:58:02'),(21,2,80.00,'delivered','2025-05-23 05:58:11'),(22,2,900.00,'pending','2025-05-23 06:12:34'),(23,2,900.00,'pending','2025-05-23 06:12:39'),(24,2,900.00,'pending','2025-05-23 06:13:01'),(25,2,900.00,'pending','2025-05-23 06:14:18'),(26,2,900.00,'pending','2025-05-23 06:14:24');
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (3,4,'Pen','a writing instrument used to apply ink or a similar fluid to a surface, usually paper, for writing or drawing',20.00,96,'uploads/products/682fccda98ca6.png','2025-05-23 01:18:18'),(4,4,'Notebook','A book or stack of paper pages that are often ruled and used for purposes such as note-taking, journaling or other writing, drawing, or scrapbooking and more.',50.00,147,'uploads/products/682fd1af06c1e.jpg','2025-05-23 01:38:55'),(5,2,'The Subtle Art of Not Giving a F*ck','Finding what is truly important in life and discarding everything else',900.00,20,'uploads/products/682fda573fff6.jpg','2025-05-23 02:15:51'),(7,4,'Mongol Pencil 2','A writing or drawing tool, typically a slender, cylindrical object containing a graphite or colored core, enclosed in a protective casing, usually wood.',12.00,50,'uploads/products/683015c0d5e60.jpg','2025-05-23 06:29:20');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'John Doe','john@gmail.com','$2y$10$2e0ZvfylGoNIzj.eqiZCtOI4rF4UYSCcy17xfOJoa6YhXgsX22ARe','2025-05-23 03:07:00'),(2,'Mark Angelo Aguillon','mark@gmail.com','$2y$10$95ieccv/sokWU8mExlve7e5q7DEiNyijSTeMvizqi1ujgwb7pl7uy','2025-05-23 03:12:05');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-05-23 14:40:25
