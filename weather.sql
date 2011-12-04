-- MySQL dump 10.13  Distrib 5.1.41, for debian-linux-gnu (i486)
--
-- ------------------------------------------------------
-- Server version	5.1.41-3ubuntu12.10

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
-- Table structure for table `audit`
--

DROP TABLE IF EXISTS `audit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `audit` (
  `id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL,
  `IP` varchar(40) NOT NULL,
  `message` text NOT NULL,
  `created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `audit`
--

LOCK TABLES `audit` WRITE;
/*!40000 ALTER TABLE `audit` DISABLE KEYS */;
/*!40000 ALTER TABLE `audit` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `banlist`
--

DROP TABLE IF EXISTS `banlist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `banlist` (
  `id` int(11) NOT NULL DEFAULT '0',
  `IP` varchar(40) NOT NULL,
  `attempts` int(5) NOT NULL DEFAULT '1',
  `maxattempts` tinyint(2) NOT NULL DEFAULT '5',
  `created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `expires` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `banlist`
--

LOCK TABLES `banlist` WRITE;
/*!40000 ALTER TABLE `banlist` DISABLE KEYS */;
/*!40000 ALTER TABLE `banlist` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inbox`
--

DROP TABLE IF EXISTS `inbox`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `inbox` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `number` varchar(20) NOT NULL DEFAULT '',
  `smsdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `text` text,
  `phone` tinyint(4) DEFAULT NULL,
  `processed` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inbox`
--

LOCK TABLES `inbox` WRITE;
/*!40000 ALTER TABLE `inbox` DISABLE KEYS */;
/*!40000 ALTER TABLE `inbox` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `log`
--

DROP TABLE IF EXISTS `log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `log` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `message` text NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=66 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `log`
--

LOCK TABLES `log` WRITE;
/*!40000 ALTER TABLE `log` DISABLE KEYS */;
/*!40000 ALTER TABLE `log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `members`
--

DROP TABLE IF EXISTS `members`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `members` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL DEFAULT '',
  `user_password` char(129) NOT NULL,
  `email` varchar(500) NOT NULL,
  `IP` varchar(40) NOT NULL DEFAULT '127.0.0.1',
  `created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `is_admin` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `members`
--

LOCK TABLES `members` WRITE;
/*!40000 ALTER TABLE `members` DISABLE KEYS */;
INSERT INTO `members` VALUES (1,'admin','3b87340dcd31c4698184cf34bf73a6d920edbf4354c7b2ddc375efd966df19cb23340befb406c701ae70334dc92249ea1d757ac3684fda593425534dbc52a989','admin@example.org','127.0.0.1','0000-00-00 00:00:00',1);
/*!40000 ALTER TABLE `members` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `multipartinbox`
--

DROP TABLE IF EXISTS `multipartinbox`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `multipartinbox` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `number` varchar(20) NOT NULL DEFAULT '',
  `smsdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `text` text,
  `phone` tinyint(4) DEFAULT NULL,
  `processed` tinyint(4) NOT NULL DEFAULT '0',
  `refnum` int(8) DEFAULT NULL,
  `maxnum` int(8) DEFAULT NULL,
  `curnum` int(8) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `multipartinbox`
--

LOCK TABLES `multipartinbox` WRITE;
/*!40000 ALTER TABLE `multipartinbox` DISABLE KEYS */;
/*!40000 ALTER TABLE `multipartinbox` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `outbox`
--

DROP TABLE IF EXISTS `outbox`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `outbox` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `number` varchar(20) NOT NULL DEFAULT '',
  `processed_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `text` varchar(160) DEFAULT NULL,
  `phone` tinyint(4) DEFAULT NULL,
  `processed` tinyint(4) NOT NULL DEFAULT '0',
  `error` tinyint(4) NOT NULL DEFAULT '-1',
  `dreport` tinyint(4) NOT NULL DEFAULT '0',
  `not_before` time NOT NULL DEFAULT '00:00:00',
  `not_after` time NOT NULL DEFAULT '23:59:59',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `outbox`
--

LOCK TABLES `outbox` WRITE;
/*!40000 ALTER TABLE `outbox` DISABLE KEYS */;
/*!40000 ALTER TABLE `outbox` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `process_email`
--

DROP TABLE IF EXISTS `process_email`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `process_email` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `from_email` varchar(255) NOT NULL,
  `from_name` varchar(255) DEFAULT '',
  `subject` varchar(255) DEFAULT '',
  `to_email` varchar(255) NOT NULL,
  `to_name` varchar(255) DEFAULT '',
  `body` text,
  `processed` tinyint(4) NOT NULL DEFAULT '0',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `process_email`
--

LOCK TABLES `process_email` WRITE;
/*!40000 ALTER TABLE `process_email` DISABLE KEYS */;
/*!40000 ALTER TABLE `process_email` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `station`
--

DROP TABLE IF EXISTS `station`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `station` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `number` int(7) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `station`
--

LOCK TABLES `station` WRITE;
/*!40000 ALTER TABLE `station` DISABLE KEYS */;
INSERT INTO `station` VALUES (1,'DC1',6000001),(2,'DC2',7888888),(5,'DC3',9999995);
/*!40000 ALTER TABLE `station` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `weatherlog`
--

DROP TABLE IF EXISTS `weatherlog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `weatherlog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `station_id` int(5) NOT NULL,
  `data` varchar(255) NOT NULL,
  `received` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `weatherlog`
--

LOCK TABLES `weatherlog` WRITE;
/*!40000 ALTER TABLE `weatherlog` DISABLE KEYS */;
INSERT INTO `weatherlog` VALUES (5,2,'15863 52856 51428=','2011-12-03 11:19:45'),(3,2,'823823 237890 8237892=','2011-12-03 00:38:19'),(4,2,'25863 52856 51428=','2011-12-03 00:46:43'),(6,4,'61711 12453 2435=','2011-12-03 13:09:04'),(7,2,'62377 238772 2377923 238923=','2011-12-03 16:49:50'),(8,2,'737273 72377 729799 367879=','2011-12-03 16:49:50'),(9,2,'84927 2399237 2883828=','2011-12-03 16:49:50'),(10,4,'61711 12453 2435=','2011-12-03 16:49:50');
/*!40000 ALTER TABLE `weatherlog` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2011-12-04  0:02:38
