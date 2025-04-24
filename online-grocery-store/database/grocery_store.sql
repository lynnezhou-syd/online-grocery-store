-- MySQL dump 10.13  Distrib 9.2.0, for macos14.7 (arm64)
--
-- Host: localhost    Database: grocery_store
-- ------------------------------------------------------
-- Server version	9.2.0

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
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Frozen'),(2,'Fresh'),(3,'Beverages'),(4,'Home & Pet-food'),(5,'Household');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_id` int DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  `quantity` int DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
INSERT INTO `order_items` VALUES (56,53,7,2,3.49);
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `recipient_name` varchar(255) NOT NULL,
  `street` varchar(255) NOT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(50) NOT NULL,
  `mobile_number` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (53,NULL,'Lynne','123 Broadway St','Ultimo','NSW','0412345678','test@example.com',6.98,'pending','2025-04-22 02:11:02');
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `category_id` int NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int NOT NULL DEFAULT '0',
  `image_url` varchar(255) DEFAULT NULL,
  `description` text,
  `unit` varchar(50) NOT NULL DEFAULT 'unit',
  `subcategory_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  KEY `subcategory_id` (`subcategory_id`),
  CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `products_ibfk_2` FOREIGN KEY (`subcategory_id`) REFERENCES `subcategories` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,'Salmon Fillet',1,15.99,100,'images/frozen_salmon.jpg','Premium quality frozen salmon fillet.','500g',1),(3,'Pizza Margherita',1,7.49,100,'images/frozen_pizza.jpg','Delicious frozen pizza Margherita, ready to bake.','100g',2),(4,'Strawberries',2,4.99,100,'images/fresh_strawberries.jpg','Fresh strawberries, locally grown.','250g',3),(5,'Organic Broccoli',2,3.29,100,'images/organic_broccoli.jpg','Organic fresh broccoli, perfect for healthy meals.','1EA',4),(7,'Orange Juice',3,3.49,100,'images/orange_juice.jpg','100% freshly squeezed orange juice.','1L',5),(8,'Green Tea',3,2.99,100,'images/green_tea.jpg','Refreshing bottled green tea.','500ml',6),(9,'Espresso Coffee',3,5.99,100,'images/espresso.jpg','Strong and aromatic espresso coffee pack.','250g',6),(10,'Chicken Formula',4,12.99,100,'images/pet_food_chicken.jpg','Nutritious chicken-based pet food.','1kg',8),(11,'Laundry Detergent',5,6.49,100,'images/laundry_detergent.jpg','High-efficiency laundry detergent.','2L',9),(12,'Cat Litter',5,8.99,100,'images/cat_litter.jpg','Odor-absorbing cat litter pack.','5kg',11),(13,'Hot Chocolate',3,6.99,100,'images/hot_chocolate.jpg','Sweet to death.','250ml',6),(15,'Shrimp',1,12.99,100,'images/shrimp.jpg','High-quality shrimp, ready to cook.','1kg',1),(17,'Lasagna',1,8.99,100,'images/lasagna.jpg','Classic lasagna, easy to prepare.','500g',2),(19,'Blueberries',2,5.99,100,'images/blueberries.jpg','Sweet and juicy blueberries.','200g',3),(21,'Carrots',2,2.99,100,'images/carrots.jpg','Crisp and fresh carrots, great for salads and cooking.','1kg',4),(23,'Apple Juice',3,3.99,100,'images/apple_juice.jpg','Pure and refreshing apple juice.','1L',5),(26,'Chicken Formula Dog Food',4,12.99,100,'images/dog_food_chicken.jpg','Nutritious chicken-based dog food.','1kg',7),(27,'Beef Flavor Dog Treats',4,9.99,100,'images/dog_treats_beef.jpg','Delicious beef-flavored dog treats.','500g',7),(28,'Salmon Formula Cat Food',4,10.99,100,'images/cat_food_salmon.jpg','High-quality salmon-based cat food.','1kg',8),(29,'Tuna Flavor Cat Treats',4,7.99,100,'images/cat_treats_tuna.jpg','Irresistible tuna-flavored cat treats.','200g',8),(30,'Multi-Surface Cleaner',5,5.99,100,'images/multi_surface_cleaner.jpg','Effective cleaner for all surfaces, removes grease and stains.','1L',9),(31,'Microfiber Mop',5,12.99,100,'images/microfiber_mop.jpg','High-quality microfiber mop for streak-free cleaning.','1EA',9),(32,'Natural Shampoo',5,8.49,100,'images/natural_shampoo.jpg','Gentle shampoo with natural ingredients for all hair types.','500ml',10),(33,'Colgate Total Toothpaste',5,3.99,100,'images/colgate_toothpaste.jpg','Advanced toothpaste for cavity protection and fresh breath.','100g',10),(34,'Flea & Tick Spray',5,14.99,100,'images/flea_tick_spray.jpg','Effective spray to protect pets from fleas and ticks.','500ml',11);
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subcategories`
--

DROP TABLE IF EXISTS `subcategories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `subcategories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `category_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `subcategories_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subcategories`
--

LOCK TABLES `subcategories` WRITE;
/*!40000 ALTER TABLE `subcategories` DISABLE KEYS */;
INSERT INTO `subcategories` VALUES (1,'Seafood',1),(2,'Meals',1),(3,'Fruits',2),(4,'Vegetables',2),(5,'Juices',3),(6,'Hot Drinks',3),(7,'Dog Food',4),(8,'Cat Food',4),(9,'Cleaning Supplies',5),(10,'Personal Care',5),(11,'Pet Care',5);
/*!40000 ALTER TABLE `subcategories` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-04-22 12:40:04
