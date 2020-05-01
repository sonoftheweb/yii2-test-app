-- MySQL dump 10.13  Distrib 8.0.19, for Linux (x86_64)
--
-- Host: 127.0.0.1    Database: tracker_app
-- ------------------------------------------------------
-- Server version	8.0.19-0ubuntu0.19.10.3

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
-- Table structure for table `countries`
--

DROP TABLE IF EXISTS `countries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `countries` (
  `id` int NOT NULL AUTO_INCREMENT,
  `country_name` varchar(50) NOT NULL,
  `country_code` varchar(3) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `country_name` (`country_name`),
  UNIQUE KEY `country_code` (`country_code`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `countries`
--

LOCK TABLES `countries` WRITE;
/*!40000 ALTER TABLE `countries` DISABLE KEYS */;
INSERT INTO `countries` VALUES (1,'Canada','CA'),(2,'United States','US'),(3,'Mexico','MX');
/*!40000 ALTER TABLE `countries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customers`
--

DROP TABLE IF EXISTS `customers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `customers` (
  `id` int NOT NULL AUTO_INCREMENT,
  `first_name` varchar(20) NOT NULL,
  `last_name` varchar(20) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customers`
--

LOCK TABLES `customers` WRITE;
/*!40000 ALTER TABLE `customers` DISABLE KEYS */;
INSERT INTO `customers` VALUES (1,'Jacob','Ekanem','prometheus6001@gmail.com','9024993130'),(2,'Bumble','bee','email@bee.com','08182004785'),(3,'James','Ekanem','beecroft@email.com','07033600000'),(4,'Naomi','Ekanem ','nomnom@email.com','9024993130'),(5,'mo','mi','mimo@email.com','+1 (800) 499 3130');
/*!40000 ALTER TABLE `customers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migration`
--

DROP TABLE IF EXISTS `migration`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migration`
--

LOCK TABLES `migration` WRITE;
/*!40000 ALTER TABLE `migration` DISABLE KEYS */;
INSERT INTO `migration` VALUES ('m000000_000000_base',1588274223),('m200427_195020_create_status_table',1588274224),('m200428_151335_create_customers_table',1588274224),('m200428_151348_create_orders_table',1588274224),('m200428_152004_create_countries_table',1588274224),('m200428_152240_add_foreign_key_constraints',1588274225);
/*!40000 ALTER TABLE `migration` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `customer_id` int NOT NULL,
  `status_id` int DEFAULT '1',
  `order_type` enum('delivery','servicing','installation') DEFAULT NULL,
  `order_value` float DEFAULT '0',
  `schedule_date` date NOT NULL,
  `street_address` text NOT NULL,
  `city` varchar(50) NOT NULL,
  `state_province` varchar(50) NOT NULL,
  `postal_zip_code` varchar(7) NOT NULL,
  `country_id` int NOT NULL,
  `longitude` varchar(255) NOT NULL,
  `latitude` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx-order-customer_id` (`customer_id`),
  KEY `idx-order-country_id` (`country_id`),
  KEY `idx-order-status_id` (`status_id`),
  CONSTRAINT `fk-order-country_id` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk-order-customer_id` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk-order-status_id` FOREIGN KEY (`status_id`) REFERENCES `status` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (3,3,4,'delivery',2345,'2020-05-04','Crisland School VGC, Nigeria','Eti-Osa','Lagos','1234',1,'3.549501','6.463283'),(4,1,2,'delivery',1500,'2020-06-04','2490 Creighton Street, NS, Canada','Halifax','Nova Scotia','1234',1,'-63.590303','44.655417'),(5,2,1,'delivery',1000,'2020-06-25','1877 Hollis St, NS, Canada','Halifax','Nova Scotia','B3J 1W5',1,'-63.573933','44.650038'),(6,4,1,'delivery',300,'2020-05-20','1870 Hollis St, NS, Canada','Halifax','Nova Scotia','B3J 1W6',1,'-63.574185','44.649802'),(7,1,3,'delivery',123,'2020-05-01','1877 Hollis Street, NS, Canada','Halifax','Nova Scotia','B3J 1W6',1,'-63.57394','44.650086'),(8,1,1,'delivery',1500,'2020-06-04','2490 Creighton Street, NS, Canada','Halifax','Nova Scotia','1234',1,'-63.590303','44.655417'),(9,1,1,'delivery',1400,'2020-05-14','2490 Creighton St, NS, Canada','Halifax','Nova Scotia','B3K 3S1',1,'-63.590264','44.655423'),(10,5,1,'installation',125,'2020-05-28','2495 Creighton St, NS, Canada','Halifax','Nova Scotia','B3K 3S2',1,'-63.590084','44.655643');
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `status`
--

DROP TABLE IF EXISTS `status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `status` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `tag` varchar(255) DEFAULT NULL,
  `color_code` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `status`
--

LOCK TABLES `status` WRITE;
/*!40000 ALTER TABLE `status` DISABLE KEYS */;
INSERT INTO `status` VALUES (1,'Pending','pending','light'),(2,'Assigned','assigned','primary'),(3,'On Route','on_route','warning'),(4,'Done','done','success'),(5,'Cancelled','cancelled','danger');
/*!40000 ALTER TABLE `status` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-05-01  5:46:35
