-- MySQL dump 10.13  Distrib 8.4.3, for Win64 (x86_64)
--
-- Host: localhost    Database: balresplay
-- ------------------------------------------------------
-- Server version	8.4.3

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

--
-- Table structure for table `admin_users`
--

DROP TABLE IF EXISTS `admin_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin_users` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `role` enum('Super Admin','Kasir','Dapur') COLLATE utf8mb4_general_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1=Aktif, 0=Nonaktif',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_users`
--

LOCK TABLES `admin_users` WRITE;
/*!40000 ALTER TABLE `admin_users` DISABLE KEYS */;
INSERT INTO `admin_users` VALUES (2,'mulyamin','$2y$10$huUm1WXhlpHYOR.Vc4FO8Ob4oKd2y/65Wy3/MA6mDnrKnOvzNFppa','Super Admin',1,'2025-11-08 11:38:24'),(3,'kasir','$2y$10$g2NEIPftYNajO/42U1yIlOFHIRh7zkdvvIpbW06Cu/BScP3qiT0zS','Kasir',1,'2025-11-12 18:25:24'),(4,'dapur','$2y$10$V/NFweNt8P/nUOZmXydbfe49Ofz8X94rlj45A9DwFxuotiN5blyGq','Dapur',1,'2025-11-12 18:25:49');
/*!40000 ALTER TABLE `admin_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_items` (
  `order_item_id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `variant_id` int NOT NULL,
  `quantity` int NOT NULL,
  `price_per_item` decimal(10,2) NOT NULL,
  PRIMARY KEY (`order_item_id`),
  KEY `order_id` (`order_id`),
  KEY `variant_id` (`variant_id`),
  CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`variant_id`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
INSERT INTO `order_items` VALUES (36,26,13,1,40000.00),(37,27,25,2,20000.00),(38,27,52,1,30000.00),(39,27,13,1,40000.00),(40,28,13,1,40000.00),(41,28,57,1,22000.00),(42,29,45,1,30000.00),(43,30,16,2,30000.00),(44,31,24,1,30000.00),(45,32,55,1,29000.00),(46,33,15,1,35000.00);
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `order_id` int NOT NULL AUTO_INCREMENT,
  `table_number` int NOT NULL,
  `order_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('Menunggu Pembayaran','Kirim ke Dapur','Sedang Dimasak','Siap Diantar','Selesai','Dibatalkan') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Menunggu Pembayaran',
  `payment_method` enum('Cash','QRIS') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `notes` text COLLATE utf8mb4_general_ci,
  `confirmed_at` datetime DEFAULT NULL,
  `started_cooking_at` datetime DEFAULT NULL,
  `completed_at` datetime DEFAULT NULL,
  PRIMARY KEY (`order_id`),
  KEY `idx_orders_status` (`status`),
  KEY `idx_orders_table_number` (`table_number`),
  KEY `idx_orders_time` (`order_time`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (26,1,'2025-11-17 09:15:02','Selesai','QRIS',42000.00,'','2025-11-17 09:15:23','2025-11-17 09:16:33','2025-11-17 09:16:37'),(27,1,'2025-11-17 09:19:33','Selesai','Cash',112000.00,'Americano: no sugar; Fried Rice Chicken Grill: Tidak pedas','2025-11-17 09:19:50','2025-11-17 09:20:06','2025-11-17 09:20:23'),(28,2,'2025-11-17 13:44:44','Selesai','Cash',64000.00,'Fried Rice Chicken Grill: tidak pedas; Americano: less sugar','2025-11-17 13:45:29','2025-11-17 13:46:45','2025-11-17 13:46:55'),(29,1,'2025-11-17 18:55:52','Selesai','Cash',32000.00,'Bisscoff Caramello: Less sugar','2025-11-17 18:56:41','2025-11-17 18:57:24','2025-11-17 18:57:25'),(30,18,'2025-11-17 18:56:15','Selesai','Cash',62000.00,'Ayam Geprek: Nasi setengah','2025-11-17 18:56:45','2025-11-17 18:57:24','2025-11-17 18:57:26'),(31,5,'2025-11-17 20:04:10','Menunggu Pembayaran','Cash',32000.00,'Burger Bal: Krabby Patty',NULL,NULL,NULL),(32,7,'2025-11-17 20:04:41','Kirim ke Dapur','Cash',31000.00,'Passion Punch: Extra gula','2025-11-17 20:13:48',NULL,NULL),(33,7,'2025-11-17 20:06:17','Kirim ke Dapur','Cash',37000.00,'','2025-11-17 20:13:53',NULL,NULL);
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_variants`
--

DROP TABLE IF EXISTS `product_variants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_variants` (
  `variant_id` int NOT NULL AUTO_INCREMENT,
  `product_id` int NOT NULL,
  `variant_name` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `is_available` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`variant_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `product_variants_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=68 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_variants`
--

LOCK TABLES `product_variants` WRITE;
/*!40000 ALTER TABLE `product_variants` DISABLE KEYS */;
INSERT INTO `product_variants` VALUES (13,8,NULL,40000.00,1),(14,9,NULL,35000.00,1),(15,10,NULL,35000.00,1),(16,11,NULL,30000.00,1),(17,12,NULL,30000.00,1),(18,13,NULL,35000.00,1),(19,14,NULL,39000.00,1),(20,15,NULL,38000.00,1),(21,16,NULL,20000.00,1),(22,17,NULL,20000.00,1),(23,18,NULL,25000.00,1),(24,19,NULL,30000.00,1),(25,20,'Hot',20000.00,1),(26,21,NULL,35000.00,1),(27,22,NULL,28000.00,1),(28,23,'Hot',23000.00,1),(29,24,'Hot',23000.00,1),(30,25,'Hot',22000.00,1),(31,26,'Hot',20000.00,1),(32,27,'Hot',22000.00,1),(33,28,'Ice',30000.00,1),(34,29,'Ice',30000.00,1),(35,30,'Hot',30000.00,1),(36,31,'Hot',10000.00,1),(37,32,'Hot',20000.00,1),(38,33,'Hot',20000.00,1),(39,34,'Hot',20000.00,1),(40,35,'Hot',20000.00,1),(41,36,'Ice',25000.00,1),(42,37,'Ice',22000.00,1),(43,38,'Hot',28000.00,1),(44,38,'Ice',30000.00,1),(45,39,NULL,30000.00,1),(46,40,'Ice',30000.00,1),(47,41,'Ice',28000.00,1),(48,33,'Ice',22000.00,1),(49,42,'Ice',29000.00,1),(50,43,'Ice',28000.00,1),(51,44,'Ice',30000.00,1),(52,45,'Ice',30000.00,1),(53,46,'Ice',30000.00,1),(54,47,'Ice',28000.00,1),(55,48,'Ice',29000.00,1),(56,49,'Ice',28000.00,1),(57,20,'Ice',22000.00,1),(58,23,'Ice',22000.00,1),(59,24,'Ice',22000.00,1),(60,25,'Ice',23000.00,1),(61,30,'Ice',32000.00,1),(62,31,'Ice',10000.00,1),(63,32,'Ice',22000.00,1),(64,34,'Ice',22000.00,1),(65,35,'Ice',22000.00,1),(66,50,NULL,25000.00,1);
/*!40000 ALTER TABLE `product_variants` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `product_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `category` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `image_url` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`product_id`),
  KEY `idx_products_category` (`category`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (8,'Fried Rice Chicken Grill','Nasi goreng spesial disajikan dengan ayam panggang','rice','images/uploads/691ac15f9e5d3-friedricechickengrill.jpeg','2025-11-16 13:57:43','2025-11-18 10:29:24'),(9,'Fried Rice Seafood','Nasi goreng dengan aneka hidangan laut segar.','rice','images/uploads/691b0c8f20b10-seafoodfriedrice.jpeg','2025-11-16 13:58:36','2025-11-17 18:52:47'),(10,'Ayam Bakar','Ayam yang dibumbui dan dibakar dengan sempurna.','rice',NULL,'2025-11-16 14:01:25','2025-11-16 14:01:25'),(11,'Ayam Geprek','Ayam goreng renyah yang ditumbuk dengan sambal.','rice',NULL,'2025-11-16 14:03:22','2025-11-16 14:03:22'),(12,'Spaghetti Aglio Olio','Spaghetti dengan bumbu bawang putih dan minyak zaitun.','noodles','','2025-11-16 14:04:28','2025-11-16 14:06:01'),(13,'Spaghetti Bolognese','Spaghetti dengan saus daging cincang klasik.','noodles','','2025-11-16 14:05:44','2025-11-16 14:07:00'),(14,'Chicken Char Kwetiau','Kwetiau goreng dengan potongan ayam dan bumbu khas.','noodles',NULL,'2025-11-16 14:08:13','2025-11-16 14:08:13'),(15,'Seafood Fried Noodle ','Mie goreng dengan aneka hidangan laut segar.','noodles','','2025-11-16 14:09:40','2025-11-17 18:54:16'),(16,'Crinkle Fries','Kentang goreng renyah dengan potongan berkerut.','lite-easy',NULL,'2025-11-16 14:10:32','2025-11-16 14:10:32'),(17,'Pisang Crispy','Pisang goreng dengan balutan adonan renyah.','lite-easy',NULL,'2025-11-16 14:11:57','2025-11-16 14:11:57'),(18,'Roti Bakar Mix','Roti panggang dengan isian cokelat dan keju.','lite-easy','images/uploads/691b125e3beac-rotibakar.jpeg','2025-11-16 14:13:36','2025-11-17 19:17:34'),(19,'Burger Bal','Burger spesial dengan patty dan saus khas.','lite-easy','images/uploads/691b0ad1b408f-burgerbal.jpeg','2025-11-16 14:14:13','2025-11-17 18:45:21'),(20,'Americano','Shot espresso yang disajikan dengan tambahan air.','coffee','','2025-11-16 14:17:25','2025-11-16 15:24:32'),(21,'Caramel Pistachio Macchiato','Macchiato dengan sentuhan karamel dan pistachio.','coffee','','2025-11-16 14:18:00','2025-11-16 15:25:23'),(22,'Caramel Macchiato','Sajian kopi dengan susu dan saus karamel.','coffee',NULL,'2025-11-16 14:18:44','2025-11-16 14:18:44'),(23,'Cappucino','Kombinasi espresso, susu, dan busa susu.','coffee','images/uploads/691b0cbb95670-capucinohot.jpeg','2025-11-16 14:19:36','2025-11-17 18:53:31'),(24,'Cafe Latte','Espresso dengan porsi susu lebih banyak.','coffee','images/uploads/691b0aeb5506c-cafelattehot.jpeg','2025-11-16 14:20:33','2025-11-17 18:45:47'),(25,'Coffee Milk','Perpaduan kopi dan susu yang klasik.','coffee','','2025-11-16 14:21:05','2025-11-16 15:31:10'),(26,'Espresso Single','Satu shot ekstrak kopi murni.','coffee','','2025-11-16 14:21:47','2025-11-16 15:34:18'),(27,'Espresso Double','Dua shot ekstrak kopi untuk rasa lebih intens.','coffee','','2025-11-16 14:23:14','2025-11-16 15:34:40'),(28,'Es Kopi Aren','Kopi susu dengan pemanis gula aren asli.','coffee','','2025-11-16 14:24:59','2025-11-16 15:35:50'),(29,'Es Kopi Ubi','Perpaduan unik kopi susu dengan rasa ubi.','coffee','','2025-11-16 14:26:52','2025-11-16 15:38:15'),(30,'Mocha Latte','Perpaduan espresso, cokelat, dan susu steamed.','coffee','','2025-11-16 14:28:27','2025-11-16 15:38:52'),(31,'Black Tea','Teh hitam klasik dengan rasa yang kuat.','tea','','2025-11-16 14:30:18','2025-11-16 15:41:09'),(32,'Green Tea','Teh hijau klasik yang menyegarkan.','tea','','2025-11-16 14:33:26','2025-11-16 15:41:46'),(33,'Thai Tea','Teh khas Thailand dengan rasa manis dan creamy.','tea','','2025-11-16 14:37:35','2025-11-16 14:55:16'),(34,'Milk Tea','Teh yang dipadukan dengan susu lembut.','tea','','2025-11-16 14:39:50','2025-11-16 15:42:36'),(35,'Lemon Tea','Kesegaran teh dengan perasan lemon.','tea','','2025-11-16 14:40:40','2025-11-16 15:43:40'),(36,'Lychee Tea','Teh dengan rasa buah leci yang manis dan segar.','tea','','2025-11-16 14:41:07','2025-11-16 15:43:57'),(37,'Peach Tea','Kesegaran teh dengan aroma dan rasa buah persik.','tea','','2025-11-16 14:42:10','2025-11-16 15:44:13'),(38,'Choco Latte Bal','Cokelat premium yang lembut dan kaya rasa.','non-coffee','','2025-11-16 14:44:15','2025-11-16 14:46:23'),(39,'Bisscoff Caramello','Minuman manis dengan rasa biskuit Biscoff dan karamel.','non-coffee','images/uploads/691b0a698c8a9-biscoffcaramelo.jpeg','2025-11-16 14:50:05','2025-11-17 18:43:37'),(40,'Ice Childhood','Minuman dingin dengan rasa nostalgia masa kecil.','non-coffee','','2025-11-16 14:51:49','2025-11-16 14:53:39'),(41,'Marrone Caramello','Minuman karamel dengan sentuhan rasa kastanye.','non-coffee','','2025-11-16 14:52:25','2025-11-16 14:53:11'),(42,'Matcha Latte Bal','Bubuk matcha berkualitas dipadukan dengan susu creamy.','non-coffee',NULL,'2025-11-16 15:07:13','2025-11-16 15:07:13'),(43,'Taro Latte Bal','Minuman latte dengan rasa talas yang unik dan manis.','non-coffee',NULL,'2025-11-16 15:08:04','2025-11-16 15:08:04'),(44,'Kiwi Mojito','Kesegaran kiwi dan mint dalam mocktail soda.','signature',NULL,'2025-11-16 15:09:47','2025-11-16 15:09:47'),(45,'Alyster Sunrise','Mocktail cerah dengan gradasi warna yang cantik.','signature',NULL,'2025-11-16 15:11:05','2025-11-16 15:11:05'),(46,'Rose Coke','Kombinasi soda dengan sentuhan sirup mawar.','signature',NULL,'2025-11-16 15:12:01','2025-11-16 15:12:01'),(47,'Choco Mint','Kombinasi klasik cokelat dan sensasi dingin dari mint.','signature',NULL,'2025-11-16 15:16:29','2025-11-16 15:16:29'),(48,'Passion Punch','Kombinasi klasik cokelat dan sensasi dingin dari mint.','signature',NULL,'2025-11-16 15:17:23','2025-11-16 15:17:23'),(49,'Peach Mojhito','Mojito dengan sentuhan manis dari buah persik.','signature',NULL,'2025-11-16 15:17:56','2025-11-16 15:17:56'),(50,'Kwetiau Seafood','Kwetiau dengan udang dan cumi','noodles','images/uploads/691b10898350f-seafoodkwetiau.jpeg','2025-11-17 19:09:45','2025-11-17 19:09:45');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `settings` (
  `setting_key` varchar(50) NOT NULL,
  `setting_value` varchar(255) NOT NULL,
  PRIMARY KEY (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES ('table_count','15');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-11-18 10:37:54
