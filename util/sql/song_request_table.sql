CREATE DATABASE  IF NOT EXISTS `natalieandnic_com` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `natalieandnic_com`;
-- MySQL dump 10.13  Distrib 5.7.9, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: natalieandnic_com
-- ------------------------------------------------------
-- Server version	5.7.11-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `song_request`
--

DROP TABLE IF EXISTS `song_request`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `song_request` (
  `song_request_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `requested_by_guest_id` int(10) unsigned NOT NULL,
  `song_artist` varchar(64) DEFAULT NULL,
  `song_title` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`song_request_id`),
  UNIQUE KEY `song_request_id_UNIQUE` (`song_request_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `song_request`
--

LOCK TABLES `song_request` WRITE;
/*!40000 ALTER TABLE `song_request` DISABLE KEYS */;
INSERT INTO `song_request` VALUES (1,14,'Pavement','Shady Lane'),(2,15,'Pavement','Shady Lane'),(3,14,'Radiohead','Airbag'),(4,3,'The Smiths','There Is A Light (And It Never Goes Out)'),(5,3,'The Cure','Lovesong'),(6,14,'Mac DeMarco','Still Together'),(7,15,'The Beatles','All My Loving');
/*!40000 ALTER TABLE `song_request` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-02-23 22:04:44
ALTER TABLE `natalieandnic_com`.`song_request` 
CHANGE COLUMN `song_artist` `song_artist` VARCHAR(64) NOT NULL,
CHANGE COLUMN `song_title` `song_title` VARCHAR(64) NOT NULL,
DROP PRIMARY KEY,
ADD PRIMARY KEY (`requested_by_guest_id`, `song_artist`, `song_title`);