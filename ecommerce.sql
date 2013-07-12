-- MySQL dump 10.13  Distrib 5.1.36, for apple-darwin9.5.0 (i386)
--
-- Host: localhost    Database: mydb
-- ------------------------------------------------------
-- Server version	5.1.36

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
-- Temporary table structure for view `all_products`
--

DROP TABLE IF EXISTS `all_products`;
/*!50001 DROP VIEW IF EXISTS `all_products`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `all_products` (
  `cat_name` varchar(45),
  `cat_image` varchar(45),
  `pid` int(11),
  `cid` int(11),
  `name` varchar(100),
  `descr` text,
  `price` double,
  `availability` int(11),
  `prd_image` varchar(50)
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `cid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `image` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`cid`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Desktop','desktop.svg'),(2,'Server','server.svg'),(3,'Accessories','accessories.svg'),(4,'Notebook','notebook.svg'),(5,'Software','software.svg'),(6,'Phones','IPhone.svg'),(7,'Cables and docks','cable.svg'),(12,'Shop iPod','ipod.svg');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `config`
--

DROP TABLE IF EXISTS `config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `config` (
  `attrib` varchar(100) NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY (`attrib`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `config`
--

LOCK TABLES `config` WRITE;
/*!40000 ALTER TABLE `config` DISABLE KEYS */;
INSERT INTO `config` VALUES ('theme','blue'),('lang','en'),('rss','on'),('smtp_host','localhost'),('smtp_port','25'),('smtp_user','avalidusername'),('smtp_pass','somepassword'),('smtp_helo','mail.devzero.it');
/*!40000 ALTER TABLE `config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `langs`
--

DROP TABLE IF EXISTS `langs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `langs` (
  `id` varchar(10) NOT NULL,
  `name` varchar(200) DEFAULT NULL,
  `meta` text,
  `error_text` varchar(250) DEFAULT NULL,
  `encoding` varchar(16) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `langs`
--

LOCK TABLES `langs` WRITE;
/*!40000 ALTER TABLE `langs` DISABLE KEYS */;
INSERT INTO `langs` VALUES ('en','English',NULL,'Not available','UTF-8'),('it','Italiano',NULL,'Non disponibile','UTF-8');
/*!40000 ALTER TABLE `langs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `log_id_seq`
--

DROP TABLE IF EXISTS `log_id_seq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `log_id_seq` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=220 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `log_id_seq`
--

LOCK TABLES `log_id_seq` WRITE;
/*!40000 ALTER TABLE `log_id_seq` DISABLE KEYS */;
INSERT INTO `log_id_seq` VALUES (219);
/*!40000 ALTER TABLE `log_id_seq` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `log_table`
--

DROP TABLE IF EXISTS `log_table`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `log_table` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `logtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `ident` char(16) NOT NULL,
  `priority` int(11) NOT NULL,
  `message` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=220 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `log_table`
--

LOCK TABLES `log_table` WRITE;
/*!40000 ALTER TABLE `log_table` DISABLE KEYS */;
INSERT INTO `log_table` VALUES (73,'2009-07-27 15:33:37','ident',6,'User admin logged out'),(74,'2009-07-27 16:04:41','ident',6,'User admin logged in'),(75,'2009-07-27 16:05:36','ident',6,'User admin logged out'),(76,'2009-07-28 09:48:26','ident',6,'User admin logged in'),(77,'2009-07-28 14:54:02','ident',6,'User admin logged out'),(78,'2009-07-28 14:54:11','ident',6,'User admin logged in'),(79,'2009-07-28 14:54:19','ident',6,'User admin logged out'),(80,'2009-07-29 09:45:57','ident',6,'User admin logged in'),(81,'2009-07-29 10:27:31','ident',6,'User admin logged in'),(82,'2009-07-29 11:33:49','ident',6,'User admin logged out'),(83,'2009-07-29 12:14:08','ident',6,'User unixo logged in'),(84,'2009-07-29 15:47:04','ident',6,'User unixo logged out'),(85,'2009-07-29 15:47:08','ident',6,'User unixo logged in'),(86,'2009-07-29 15:49:02','ident',6,'User unixo logged out'),(87,'2009-07-29 15:49:05','ident',6,'User admin logged in'),(88,'2009-07-29 15:49:18','ident',6,'User admin logged out'),(89,'2009-07-30 10:30:53','ident',6,'User unixo logged in'),(90,'2009-07-30 13:51:19','ident',6,'User unixo logged out'),(91,'2009-07-30 13:58:10','ident',6,'User unixo logged in'),(92,'2009-07-30 14:38:15','ident',6,'User unixo logged out'),(93,'2009-07-30 14:38:18','ident',6,'User admin logged in'),(94,'2009-07-30 15:14:34','ident',6,'User admin logged out'),(95,'2009-07-30 21:57:53','ident',6,'User unixo logged in'),(96,'2009-07-31 05:08:11','ident',6,'User #2 bought 2# of 1'),(97,'2009-07-31 05:08:11','ident',6,'User #2 bought 1# of 20'),(98,'2009-07-31 05:08:11','ident',6,'User #2 bought 1# of 12'),(99,'2009-07-31 10:53:57','ident',6,'User unixo logged out'),(100,'2009-07-31 10:54:00','ident',6,'User admin logged in'),(101,'2009-07-31 10:54:31','ident',6,'User admin logged out'),(102,'2009-07-31 11:24:00','ident',6,'User admin logged in'),(103,'2009-07-31 11:29:44','ident',6,'User admin logged out'),(104,'2009-07-31 14:40:07','ident',6,'User unixo logged in'),(105,'2009-08-01 09:53:07','ident',6,'User unixo logged in'),(106,'2009-08-01 09:54:42','ident',6,'User unixo logged out'),(107,'2009-08-01 09:54:47','ident',6,'User admin logged in'),(108,'2009-08-01 09:56:23','ident',6,'User admin logged out'),(109,'2009-08-02 07:22:04','ident',6,'User admin logged in'),(110,'2009-08-02 07:42:17','ident',6,'User admin logged out'),(111,'2009-08-02 07:46:37','ident',6,'User admin logged in'),(112,'2009-08-02 07:46:47','ident',6,'User admin logged out'),(113,'2009-08-02 12:28:12','ident',6,'User admin logged in'),(114,'2009-08-02 12:32:33','ident',5,'A new product has been added by admin'),(115,'2009-08-03 08:31:29','ident',6,'User unixo logged in'),(116,'2009-08-03 09:15:16','ident',6,'User unixo logged out'),(117,'2009-08-03 09:21:51','ident',6,'User admin logged in'),(118,'2009-08-03 13:29:31','ident',6,'User admin logged in'),(119,'2009-08-03 13:38:10','ident',6,'User admin logged out'),(120,'2009-08-03 13:42:01','ident',5,'A confirmation email has been sent to madiash'),(121,'2009-08-03 13:42:01','ident',5,'A new user has been registered (madiash)'),(122,'2009-08-03 14:52:35','ident',6,'User admin logged in'),(123,'2009-08-03 15:19:26','ident',6,'User admin logged out'),(124,'2009-08-03 15:28:57','ident',6,'User madiash logged in'),(125,'2009-08-03 15:29:04','ident',6,'User madiash logged out'),(126,'2009-08-03 15:44:01','ident',6,'User madiash logged in'),(127,'2009-08-04 20:52:30','ident',6,'User admin logged out'),(128,'2009-08-04 21:21:24','ident',6,'User admin logged out'),(129,'2009-08-04 21:21:43','ident',6,'User unixo logged out'),(130,'2009-08-06 06:56:23','ident',6,'User unixo logged out'),(131,'2009-08-06 07:08:47','ident',6,'User unixo logged out'),(132,'2009-08-06 09:05:19','ident',6,'User unixo logged out'),(133,'2009-08-06 09:26:50','ident',6,'User unixo logged out'),(134,'2009-08-06 09:57:52','ident',6,'User admin logged out'),(135,'2009-08-08 07:47:32','ident',5,'A new category has been added by admin'),(136,'2009-08-08 07:51:45','ident',5,'A new category has been added by admin'),(137,'2009-08-08 07:53:16','ident',5,'A new category has been added by admin'),(138,'2009-08-08 08:00:54','ident',5,'A new category has been added by admin'),(139,'2009-08-09 06:35:37','ident',6,'User admin logged out'),(140,'2009-08-09 18:03:54','ident',6,'User admin logged out'),(141,'2009-08-09 18:04:00','ident',6,'User admin logged out'),(142,'2009-08-09 21:26:22','ident',6,'User admin logged out'),(143,'2009-08-10 15:58:55','ident',6,'User admin logged out'),(144,'2009-08-10 16:00:59','ident',6,'User #10 bought 1# of 24'),(145,'2009-08-10 16:00:59','ident',6,'User #10 bought 1# of 11'),(146,'2009-08-10 16:05:04','ident',6,'User madiash logged out'),(147,'2009-08-10 16:27:20','ident',6,'User admin logged out'),(148,'2009-08-10 16:39:20','ident',6,'User admin logged out'),(149,'2009-08-10 17:22:11','ident',6,'User madiash logged out'),(150,'2009-08-11 08:08:30','ident',6,'User madiash logged out'),(151,'2009-08-11 08:12:17','ident',6,'User admin logged out'),(152,'2009-08-12 06:45:35','ident',6,'User madiash logged out'),(153,'2009-08-15 10:49:50','ident',6,'User  logged out'),(154,'2009-08-15 10:50:17','ident',6,'User  logged out'),(155,'2009-08-15 10:59:03','ident',6,'User  logged out'),(156,'2009-08-15 11:03:16','ident',6,'User  logged out'),(157,'2009-08-15 11:15:13','ident',6,'User unixo logged out'),(158,'2009-08-15 11:17:05','ident',6,'User unixo logged out'),(159,'2009-08-15 11:18:39','ident',6,'User unixo logged out'),(160,'2009-08-15 11:26:15','ident',6,'User unixo logged out'),(161,'2009-08-15 11:31:11','ident',6,'User madiash logged out'),(162,'2009-08-15 11:31:23','ident',6,'User  logged out'),(163,'2009-08-15 11:44:05','ident',6,'User unixo logged out'),(164,'2009-08-15 15:50:01','ident',6,'User #2 bought #1 of 6'),(165,'2009-08-15 15:50:04','ident',6,'User unixo logged out'),(166,'2009-08-15 15:50:24','ident',6,'User admin logged out'),(167,'2009-08-15 15:52:53','ident',6,'User admin logged out'),(168,'2009-08-15 15:53:22','ident',6,'User #10 bought #1 of 1'),(169,'2009-08-15 15:53:24','ident',6,'User madiash logged out'),(170,'2009-08-15 15:53:47','ident',6,'User admin logged out'),(171,'2009-08-15 16:19:12','ident',6,'User admin logged out'),(172,'2009-08-15 16:19:40','ident',6,'User madiash logged out'),(173,'2009-08-15 16:53:29','ident',5,'A new product has been added by admin'),(174,'2009-08-16 08:48:43','ident',5,'A new product has been added by admin'),(175,'2009-08-16 09:15:48','ident',5,'A new product has been added by admin'),(176,'2009-08-16 09:16:35','ident',6,'User admin logged out'),(177,'2009-08-16 09:17:09','ident',6,'User unixo logged out'),(178,'2009-08-16 09:17:42','ident',6,'User madiash logged out'),(179,'2009-08-16 09:20:03','ident',5,'A new product has been added by admin'),(180,'2009-08-16 09:20:39','ident',6,'User admin logged out'),(181,'2009-08-16 09:21:44','ident',6,'User unixo logged out'),(182,'2009-08-16 09:35:03','ident',6,'User madiash logged out'),(183,'2009-08-16 09:38:24','ident',5,'A new product has been added by admin'),(184,'2009-08-17 09:14:29','ident',6,'User admin logged out'),(185,'2009-08-17 11:02:05','ident',5,'A new product has been added by admin'),(186,'2009-08-17 15:14:04','ident',6,'User admin logged out'),(187,'2009-08-17 15:18:37','ident',6,'User #2 bought #1 of 31'),(188,'2009-08-17 15:18:40','ident',6,'User unixo logged out'),(189,'2009-08-17 15:19:05','ident',6,'User admin logged out'),(190,'2009-08-17 17:35:07','ident',6,'User unixo logged out'),(191,'2009-08-18 12:44:06','ident',6,'User unixo logged out'),(192,'2009-08-18 17:27:04','ident',6,'User unixo logged out'),(193,'2009-08-18 17:29:02','ident',6,'User unixo logged out'),(194,'2009-08-18 17:29:35','ident',6,'User unixo logged out'),(195,'2009-08-18 17:30:42','ident',6,'User unixo logged out'),(196,'2009-08-18 17:31:19','ident',6,'User unixo logged out'),(197,'2009-08-18 17:34:34','ident',6,'User unixo logged out'),(198,'2009-08-18 17:41:02','ident',6,'User unixo logged out'),(199,'2009-08-18 17:41:33','ident',6,'User unixo logged out'),(200,'2009-08-18 17:41:50','ident',6,'User unixo logged out'),(201,'2009-08-18 17:41:50','ident',6,'User unixo logged out'),(202,'2009-08-18 17:42:01','ident',6,'User unixo logged out'),(203,'2009-08-19 08:39:23','ident',6,'User admin logged out'),(204,'2009-08-19 15:07:36','ident',5,'A new product has been added by admin'),(205,'2009-08-19 19:40:23','ident',6,'User admin logged out'),(206,'2009-08-19 19:40:23','ident',6,'User admin logged out'),(207,'2009-08-20 08:00:54','ident',6,'User admin logged out'),(208,'2009-08-20 18:51:52','ident',6,'User madiash logged out'),(209,'2009-08-21 06:48:09','ident',6,'User madiash logged out'),(210,'2009-08-24 00:50:45','ident',6,'User admin logged out'),(211,'2009-08-26 07:31:29','ident',6,'User admin logged out'),(212,'2009-08-27 19:12:48','ident',6,'User unixo logged out'),(213,'2009-08-29 08:43:29','ident',5,'A new product has been added by admin'),(214,'2009-08-29 09:03:03','ident',5,'A new product has been added by admin'),(215,'2009-08-29 09:03:22','ident',6,'User admin logged out'),(216,'2009-08-29 09:04:22','ident',6,'User unixo logged out'),(217,'2009-08-29 09:10:01','ident',6,'User madiash logged out'),(218,'2009-09-08 08:51:01','ident',6,'User admin logged out'),(219,'2009-09-08 14:47:23','ident',6,'User unixo logged out');
/*!40000 ALTER TABLE `log_table` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products` (
  `pid` int(11) NOT NULL AUTO_INCREMENT,
  `cid` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `descr` text,
  `price` double DEFAULT '0',
  `availability` int(11) DEFAULT '0',
  `image` varchar(50) DEFAULT NULL,
  `rating` int(11) DEFAULT '0',
  `ship_time` int(11) DEFAULT '24',
  PRIMARY KEY (`pid`),
  KEY `category_id` (`cid`),
  CONSTRAINT `category_id` FOREIGN KEY (`cid`) REFERENCES `categories` (`cid`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,4,'MacBook Pro 15&quot; 2.53GHz','Configurazione proposta:<div><ul><li>Intel Core 2 Duo</li><li>4GB Memory</li><li>320GB hard drive</li><li>SD card slot</li><li>Built-in 7-hour battery</li><li>NVIDIA GeForce 9400M + 9600M GT with 256MB</li></ul></div>',1799,5,'6094-IMG0114s.gif',0,24),(2,4,'MacBook Pro 13\" 2.26GHz','Processore Intel Core 2 Duo<br/>2GB di Memoria<br/>Disco rigido da 160GB',1149,1,'macbook.gif',0,24),(3,5,'iWork','iWork, Apple&acirc;€™s productivity suite, is the easiest way to create great-looking documents, spreadsheets, and presentations.&amp;nbsp;&lt;div&gt;Writing and page layout are easy using Pages.&amp;nbsp;&lt;/div&gt;&lt;div&gt;Numbers gives you simple ways to make sense of your data. New cinematic animations, transitions, and effects in Keynote will keep your audience captivated.&amp;nbsp;&lt;/div&gt;&lt;div&gt;And iWork is compatible with Microsoft Office, so sharing your work is even easier.&lt;/div&gt;&lt;div&gt;Install the iWork &acirc;€™09 Family Pack* on up to five Mac computers in your household.&lt;/div&gt;',150,5,'iwork.gif',4,24),(4,2,'Mac Pro Quad-core','One 2.66GHz Quad-Core Intel Xeon &quot;Nehalem&quot; processor &lt;div&gt;&lt;div&gt;3GB (three 1GB) memory&lt;/div&gt;&lt;div&gt;640GB hard drive&lt;/div&gt;&lt;div&gt;18x double-layer SuperDrive&lt;/div&gt;&lt;div&gt;NVIDIA GeForce GT 120 with 512MB&lt;/div&gt;&lt;/div&gt;',2299,0,'mac-pro.png',0,24),(5,2,'Mac Pro 8-core','&lt;div&gt;Two 2.26GHz Quad-Core Intel Xeon &quot;Nehalem&quot; processors&lt;/div&gt;&lt;div&gt;6GB (six 1GB) memory&lt;/div&gt;&lt;div&gt;640GB hard drive&lt;/div&gt;&lt;div&gt;18x double-layer SuperDrive&lt;/div&gt;&lt;div&gt;NVIDIA GeForce GT 120 with 512MB&lt;/div&gt;',2999,1,'mac-pro.png',0,24),(6,1,'Mac mini 2.0GHz : 120GB','Intel Core 2 Duo a 2GHz',599,18,'mac-mini.png',0,24),(7,3,'Apple Mighty Mouse wireless','Finalmente potrete fare vostro l\'ormai famosissimo &lt;b&gt;Mighty Mouse&lt;/b&gt;, ora senza coda.&amp;nbsp;&lt;div&gt;Grazie alla tecnologia &lt;b&gt;Bluetooth&lt;/b&gt;, Mighty Mouse wireless vi regala la massima libert&Atilde;&nbsp; di movimento senza grovigli di cavi.&amp;nbsp;&lt;/div&gt;&lt;div&gt;Presenta inoltre l\'incredibile rotellina di scorrimento che vi consente di spostarvi in ogni punto del documento senza sollevare neppure un dito.&amp;nbsp;&lt;/div&gt;&lt;div&gt;E la tecnologia di puntamento laser vi permetter&Atilde;&nbsp; di lavorare su pi&Atilde;&sup1; superfici con maggiore precisione.      &lt;/div&gt;',69,3,'MB111.gif',0,24),(9,3,'AirPort Express Base Station with 802.11n and','Introducing the new AirPort Express Base Station, now with 802.11n wireless. Easily create a wireless network at home. Enjoy your iTunes music library in virtually any room of your house. Share a USB printer without obtrusive cables. And do it all with the latest wireless technology.',99,0,'MB321.gif',0,24),(10,3,'Time Capsule - 1 TB','Back up a lifetime\'s worth of memories with the all-new Time Capsule, a wireless hard drive that works seamlessly with Time Machine in Mac OS X Leopard. ItÃ¢â‚¬â„¢s also a full-featured 802.11n Wi-Fi base station with simultaneous dual-band support.1 Choose from 500GB and 1TB models',499,100,'MB765_AV1.png',0,24),(11,7,'AirPort Express Stereo Connection Kit with Monster Cables','The AirPort Express Stereo Connection Kit with Monster Cables includes everything you need to get the most from AirPort Express: a Monster mini-to-RCA left/right audio cable, a Monster mini-to-optical digital Toslink audio cable and an AirPort Express power extension cord',39,1,'M9573.gif',4,24),(12,6,'iPhone 3GS','Click the Get Started button below to review your eligibility, check out pricing, and select an AT&T rate plan. Then choose the iPhone model you want.',99,999,'iphone.gif',0,24),(13,12,'Altec Lansing inMotion MAX','A premium portable stereo for iPhone and iPod with FM radio tuner, inMotion MAX lets you go with your music. AC or rechargeable-battery powered.',199.95,20,'Altec.gif',3,24),(14,1,'iMac 20-inch : 2.66GHz','2.66GHz Intel Core 2 Duo<br/>2GB memory<br/>320GB hard drive1',1199,23,'iMac.png',0,24),(15,1,'iMac 24-inch : 2.66GHz','2.66GHz Intel Core 2 Duo<br/>4GB memory<br/>640GB hard drive1',1499,2,'iMac.png',0,24),(16,1,'iMac 24-inch : 2.93GHz','2.93GHz Intel Core 2 Duo<br/>4GB memory<br/>640GB hard drive1<br/>NVIDIA GeForce GT 120 with 256MB memory',1799,19,'iMac.png',0,24),(17,1,'iMac 24-inch : 3.06GHz','3.06GHz Intel Core 2 Duo<br/>4GB memory<br/>1TB hard drive1<br/>NVIDIA GeForce GT 130 with 512MB memory',1799,19,'iMac.png',0,24),(19,1,'Apple TV 40Gb','Mac + PC<br/>802.11n Wi-Fi wireless2<br/>40GB hard drive for up to 50 hours of video<br/>Apple Remote',229,43,'apple_tv.gif',0,24),(20,3,'Bose QuietComfort 2 Acoustic Noise Cancelling','Bose QuietComfort 2 Acoustic Noise Cancelling Headphones combine the exceptional noise reduction technology of the original QuietComfort headphones with dramatically improved audio performance and enhanced convenience. Supports all iPod models.\r\n<ul>\r\n<li>World-class audio with acclaimed noise-reducing technology</li>\r\n<li>Comfortable around-ear style</li>\r\n<li>AAA battery included</li>\r\n<li>Fold-flat design for easy storage in the slim carrying case</li>\r\n</ul>',269.65,33,'T6832.png',5,24),(22,3,'Belkin TuneBase Direct with Handsfree for iPhone','Get the best possible sound quality by plugging your iPhone or iPod directly into your car stereo\'s 3.5mm audio input with TuneBase Direct. Plus, its new hands-free feature lets you easily and safely answer your iPhone while driving.\r\n<ul>\r\n<li>Listen to music from your iPhone or iPod while it charges</li>\r\n<li>Talk on your iPhone and answer calls, hands-free, as you drive</li>\r\n</ul>',69.95,48,'TW380_AV1.png',5,24),(23,12,'iPod 8Gb','<strong>Meet a musical Genius.</strong>\r\nWith just a few clicks, the new Genius feature finds the songs on your iPod nano that go great together and makes a Genius Playlist for you.\r\n\r\n<strong>Rock and roll over.</strong>\r\nThanks to the built-in accelerometer, you can rotate iPod nano to flip through album art with Cover Flow. Watch movies and TV shows in widescreen. And view photos in either portrait or landscape.\r\n \r\n<strong>Shake to shuffle.</strong>\r\nJust give iPod nano a shake and it shuffles to a different song in your music library',149,299,'nano-red.gif',0,24),(24,5,'Final Cut Studio','As the next major release of the leading video post-production suite, the new Final Cut Studio offers powerful new features, dramatically improved performance, and tighter integration. Its six applications give video editors all the tools they need to edit, animate, mix, grade, and delivered at a price that\'s more affordable than ever.&amp;nbsp;&lt;div&gt;&lt;b&gt;Applications included in Final Cut Studio&lt;/b&gt;\r\n&lt;ul&gt;\r\n&lt;li&gt;Final Cut Pro 7 for video and film editing&lt;/li&gt;\r\n&lt;li&gt;Motion 4 for motion graphics and animation&lt;/li&gt;\r\n&lt;li&gt;Soundtrack Pro 3 for audio post-production&lt;/li&gt;\r\n&lt;li&gt;Color 1.5 for color grading and finishing&lt;/li&gt;\r\n&lt;li&gt;Compressor 3.5 and DVD Studio Pro 4 for digital delivery virtually anywhere &amp;nbsp;Apple devices, the web, and disc&lt;/li&gt;      &lt;/ul&gt;&lt;/div&gt;',999,12,'finalcut.gif',4,24),(25,5,'Logic Express 9','&lt;div&gt;Record, edit, and mix your music like a pro. Open your GarageBand files in Logic Express 9 and get right to work, or start from scratch with a new composition. Innovative production tools and over 100 instrument and effect plug-ins from Logic Studio make it easier than ever to translate musical inspiration into professional recordings.&lt;/div&gt;&lt;div&gt;&lt;br&gt;&lt;/div&gt;&lt;div&gt;&lt;b&gt;Top new features in Logic Express&lt;/b&gt;&lt;/div&gt;&lt;div&gt;&lt;ul&gt;&lt;li&gt;Fully compatible with GarageBand projects &acirc;€” open them and take them further.&lt;/li&gt;&lt;li&gt;Flex Time lets you manipulate audio timing and tempo like never before.&lt;/li&gt;&lt;li&gt;Build dream guitar rigs with the new Amp Designer and Pedalboard.&lt;/li&gt;&lt;li&gt;Inspire your music with 36 instrument plug-ins and 70 effect plug-ins.&lt;/li&gt;&lt;/ul&gt;&lt;/div&gt;',199,2,'logic.gif',5,24),(26,4,'MacBook Air 1.86GHz','&lt;ul&gt;&lt;li&gt;Intel Core 2 Duo processor with 1066MHz frontside bus&lt;/li&gt;&lt;li&gt;2GB Memory&lt;/li&gt;&lt;li&gt;120GB SATA hard drive&lt;/li&gt;&lt;li&gt;NVIDIA GeForce 9400M graphics&lt;/li&gt;&lt;/ul&gt;',1499,10,'macbook-air.gif',0,24),(27,4,'MacBook Air 2.13GHz','<ul><li>Intel Core 2 Duo processor with 1066MHz frontside bus</li><li>2GB Memory</li><li>128GB solid-state drive</li><li>NVIDIA GeForce 9400M graphics</li></ul>',1799,9,'macbook-air.gif',0,24),(28,7,'Apple iPod shuffle Dock','This USB cable with integrated Dock is the ideal spare or replacement docking station for your second-generation iPod shuffle (&lt;i&gt;aluminum&lt;/i&gt;). Use it to charge and sync with a computer or power adapter',29,19,'dock.gif',3,36),(29,7,'Apple Universal Dock','The Apple Universal Dock offers easy access to a USB port for charging and syncing your iPod or iPhone or for connecting to a TV or speakers. It includes the Apple Remote for easy control from across the room.',49,50,'MB125.gif',0,24),(30,6,'iPhone 3G','It\'s faster, and much cheaper than its predecessor.&amp;nbsp;&lt;div&gt;Starting at &acirc;‚&not;99 (with a two-year contract), you get an 8 gigabyte device with GPS that works on AT&amp;amp;T\'s high-speed 3G network (as opposed to the slower EDGE network the original iPhone used). A 16 gigabyte version sells for $299. Apple claims the battery is supposed to support 300 hours of standby time, 5 to 6 hours of Web browsing, 7 hours of video, and 24 hours of audio. However, talk time is cut in half from 10 hours to 5 hours, when using the 3G network.      &lt;/div&gt;',99,34,'iphone_3g.gif',0,24),(31,12,'iPod Shuffle','4Gb of storage<div>Up to 1,000 songs</div>',79,399,'shuffle-silver.gif',0,24),(32,5,'Pixelmator','&lt;div&gt;An easy-to-use, fast and powerful image editing program, Pixelmator has everything you need to enhance, edit and retouch your photos.&lt;/div&gt;&lt;div&gt;&lt;ul&gt;&lt;li&gt;130-plus filters and special effects&lt;/li&gt;&lt;li&gt;Layer-based image editing&lt;/li&gt;&lt;li&gt;Color correction tools&lt;/li&gt;&lt;li&gt;Compatible with iPhoto and iSight&lt;/li&gt;&lt;/ul&gt;&lt;/div&gt;',59.95,10,'pixelmator.gif',0,24),(33,6,'Kensington Car Charger','Listen to your tunes through the car\\\'s stereo auxiliary port and keep your iPhone or iPod within reach with the Kensington Car Charger Deluxe.      ',49,10,'kensington.gif',0,24),(34,6,'JBL OnStage III Speakers','JBL On Stage 3 with RF remote is a compact yet powerful high-performance sound system for your iPod.',169.95,5,'jbl.gif',2,24);
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reviews` (
  `rid` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `value` text NOT NULL,
  `rating` int(11) DEFAULT '0',
  PRIMARY KEY (`rid`),
  KEY `product_id` (`pid`),
  KEY `user_id` (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reviews`
--

LOCK TABLES `reviews` WRITE;
/*!40000 ALTER TABLE `reviews` DISABLE KEYS */;
INSERT INTO `reviews` VALUES (1,3,2,'Finaly a solution, but should be easier on Macs','I have a new JVC camcorder which creates those lovely .MOD files. I used to use Pinnacle Studio to edit stuff on the PC and it worked fine if I just changed the file name. But for the Mac it wasn\'t that easy (PC died and I switched to Mac). After downloading at least 5 Mac applications to try and do the conversion, I put out the 20 bucks and also downloaded Streamclip. So much easier for me! Audio worked fine. Video was shot in 16:9 and it kept the format. Most other conversion applications only had 4:3 output. While iMovie is not as good as Studio (IMO) this now at least let\'s me edit some quick family video and burn to DVD.',5),(2,20,2,'Awesome!','This is my first time to have this kind of headphone and no regrets at all. It is so amazing how really quiet it is every time I use it to travel by train. Sometimes, I let my friends to try it and they are really amazed that they wanted to have it. Unfortunately, when they learn the price, they will become \"hands-off\" to it, but that\'s another story.',5),(3,22,1,'Works for me','I debated long over buying this product and used any and all reviews I could find. It is funny how you can read 10 good reviews but the 1 bad one sticks in your mind. I imagine the task of making a product that will work even adequately in a large number of different vehicles is difficult and compromising. For me the tunebase direct does a good job at all the things I wanted it for. It provides a mount/holder, although no matter where I move the arm it does hinder access to the climate controls, but not enough to worry about. It does play music/etc. through my aux connection, and although not up to audiophile addict standards it sounds pretty good to me. And it does allow me to have hands free calling, and this feature seemed to be the most suspect in most reviews, but after adjusting volume levels on the phone and on the stereo it seems to work just fine. The caller claimed to hear me clearly enough and I heard them very clearly. And of course it charges the phone. The one thing that I might complain about is having to plug in a 3.5mm to the headphone jack. I\'m no engineer and maybe it is obvious to those who are why this has to be, but I can\'t see why they can\'t make it so it only needs to be connected to the bottom dock connection. Other than that I am very satisfied with this unit.',4),(4,22,2,'Audio Quality is Lacking','Since this is first and foremost a product for connecting an audio product to an auxiliary port on a car stereo, audio quality should be the first thing they get right. Unfortunately, there is a constant buzzing in the background when playing audio through the device. Other than that, it does everything else in an ok manner. But an audio product should not have audio issues. Also, contacting Belkin\'s customer support was a miserable experience and I am still trying to get things worked out. I may just have to eat the cost and find something else.',1),(5,24,2,'Amazing piece of software','Final cut has been refined to the very edge. Compressor used to cause many compatibility problems in the past but these issues are now gone. I tested it with a large uncompressed HD file. I have to say that I am very impressed, and apple keeps selling studio products are very affordable prices. The last comment that I would like to make is that Final cut now better uses the resources of my 8 core mac pro and runs a lot faster than the previous version. Probably because it now only runs on intel macs.',5),(6,24,2,'Lame','Wow there is virtually nothing this new version offers over Final Cut Studio 2.\r\n\r\nThis really should have been a free software update.\r\n\r\nNext time I hope Apple releases another paid update to FCS they include DVD Studio Pro with Blu-Ray support! Or how about Color with a Apple UI? Wheres the Shake update phenomenon?\r\n\r\nHOW ABOUT LETTING YOU USE A QUICK CLUSTER IN COMPRESSOR STRAIGHT FROM FCP WITHOUT MAKING A REFERENCE QUCIKTIME FILE AND NOT CRASHING QMASTER.\r\n\r\nWhat they really need is to do some under the hood updates like making it 64-bit so you can use more RAM, including Grand Central and Open CL features, and use Core Image and Core Video more.\r\n\r\nSeems like the only thing they did in this update was to remove PPC support, which is dumb because FCS2 ran fine on G5\'s and even on G4\'s.\r\n\r\nThe only reason to get this is if you are a first time buyer, or if you have dragged your feet forever to update a really old copy of Final Cut.\r\n\r\nBottom line for Final Cut Studio 2 owners this should have been free!',3),(7,24,2,'DVD Studio Pro','Seriously, cool new gadgets. But we fully expected the new Final Cut Studio to let you make Blue Ray discs in DVD Studio Pro. At least address this issue and make a new upgrade or respond or something. I feel like I paid a lot of money hoping for this feature and it\'s not there!!',3),(8,13,2,'Altec Inmotion Max','For the money this a great system. I don\'t like the adapters that come with it, but the iPhone 3g works fine without one. Love the fact that it charges the phone and iPod touch while playing them. Also works while listening to Internet radio. 4.5 stars',5),(9,13,2,'Bad','When I first got the product it was awsome and it worked great. \r\nthen I took it on a trip and it stopped working. \r\nI sent it back and they gave me a new one, except this one right out of the box didn\'t work.\r\nIf you are looking for a reliable product don\'t buy this one.',1),(10,11,2,'Optical cable sounds amazing','None of the previous reviews encompassed what I needed, so I thought I\'d write my own. \r\n\r\nThe mini-to-toslink (optical) cable is awesome. I have a fairly nice stereo system (Rotel/B&amp;W), and wanted to stream music from my laptop to receiver (via the AirPort Express) without losing quality. This cable works as good as I expected. Just for kicks I tested the mini-to-RCA cable, and its ok, but not great (I\'m sure it works well for analog). The optical cable is so clear I can hear I can hear each string of the guitar strumming, almost like the artist is sitting right there. No loss of quality, which is exactly what I wanted.\r\n\r\nOnce I got home, it took all of 30 seconds to install; literally you just plug it in. And yes, please take off the tiny plastic caps! Some people on here apparently think Monster cables are a rip-off, but to be honest, I could care less if radio shack sells something similar for $5. Cheap cables break, sound terrible, and don\'t last long. These cables look, feel, and sound, high quality. I wanted something that produces great sound, and this does exactly that.\r\n\r\nHope this helps!',5),(11,11,1,'perfect for airport express','These are definitely what an audiophile would need to hookup his/her airport express to their systems. The digital audio out capability is exactly what I needed to see.',5),(12,25,10,'Nice','Lots of new Features for Guitarists. This is what Logic has needed all along.',5),(13,28,2,'Great product!','I absolutely love my shuffle, and nothing has happened to the dock. I have had it for almost a year now, and it is still working perfectly.',5),(14,28,10,'garbage','This doesn\\\'t do well with everyday use. The pole has snapped off like many other people\\\'s but I did absolutely nothing to it!! I\\\'m thinking it\\\'s just from wear and tear after a year of putting the ipod on to charge and sync. You would think a big name like apple would make something a little more resilient ....',1),(15,3,10,'It\\\'s about time','I bought my new imac 2 weeks ago and put my nearly extinct G4 on the spare desk. I\\\'ve been wrestling with WORD on the new computer but it\\\'s not been cooperating at all...crashing....corrupting files...awful.\r\niWork is just wonderful.....something made just for mac but with files my mac deprived PC friends can still open.\r\n:)',4),(16,11,10,'The Toslink cable is the good part','Forget the mini to RCA, it will not do any better than a much cheaper cable. The Toslink cable on the other hand... \r\nToslink is full digital, your mac can send a digital signal through the cable to any reciever that has a toslink input. You can set your mac to send the signal raw from your dvd player. The receiver will do the digital conversion and you can get full surround, dolby or DTS. It sounds great. Of course toslink cables can be had cheaper as well.',3),(17,34,2,'BE CAREFUL!!!!','This is a good small speaker and it does charge the iphone. BUT (and this is important) Unlike its\\\' predecessors it DOES NOT SYNCH your iphone with itunes. They have a place in the back that looks like they were going to put a port that would allow a synch cable to be connected. It is a shame because to synch you have to remove the iphone from the dock and plug it into a separate connection to synch. Poor design.',3),(18,34,10,'Cracking noise','I went through three of them with the touch 2g they all had a cracking noise brought to Mac store they could not fix I sent back all of them back stay away',1);
/*!40000 ALTER TABLE `reviews` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `strings`
--

DROP TABLE IF EXISTS `strings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `strings` (
  `string_id` text NOT NULL,
  `page_id` varchar(100) DEFAULT NULL,
  `en` text,
  `it` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `strings`
--

LOCK TABLES `strings` WRITE;
/*!40000 ALTER TABLE `strings` DISABLE KEYS */;
INSERT INTO `strings` VALUES ('how_are_you','basic','How are you?',NULL),('hello_world','basic','Hello, world',NULL),('index','index','Index','Indice'),('shopping_cart','index','Shopping Cart','Carrello'),('prd_in_cart','index','Product(s) in cart','Prodotti(i)'),('total','index','Total','Totale'),('logout','index','Logout','Esci'),('cart_summary','index','Cart summary','Riepilogo carrello'),('empty_cart','index','Empty cart','Svuota carrello'),('edit_profile','index','Edit profile','Modifica profilo'),('checkout','index','Checkout','Vai alla cassa'),('user_corner','index','User corner','Angolo dell\'utente'),('register','index','Register','Registrati'),('login','index','Login','Accedi'),('browse_prd','index','Browse products','Elenco prodotti'),('category_shop','index','Shop by category','Cerca per categorie'),('top_rated','index','Top rated','I pi&ugrave; votati'),('last_news','index','New to the Shop','Ultime novit&agrave;'),('categories','index','Categories','Categorie'),('aboutus','index','About us','L\'Azienda'),('contactus','index','Contact us','Contattaci'),('products','index','Products','Prodotti'),('from_store','main','From our store','Direttamente dal negozio'),('one_top_rated','main','Choose one of our top rated products','Scegli un prodotto tra i pi&ugrave; votati'),('quick_shop','index','Quick shop','Acquisti veloci'),('admin_tool','index','Admin Tools','Amministrazione'),('shop_art','index','Shopping cart','Riepilogo carrello'),('settings','admin','Site settings','Impostazioni sito'),('browse_prd','admin','Browse products','Elenco prodotti'),('insert_prd','admin','Insert a new product','Inserimento nuovo prodotto'),('browse_cat','admin','Browse categories','Elenco categorie'),('insert_cat','admin','Insert a new category','Inserimento nuova categoria'),('browse_users','admin','Browse users','Elenco utenti'),('log_console','admin','Log console','Elenco eventi'),('search','index','Search','Ricerca'),('search_store','index','search our store','ricerca tra i prodotti'),('forgot_pwd','login','Forgot password?','Dimenticato la password?'),('new_user','login','New user?','Nuovo utente?'),('register','login','Register','Registrati'),('ship_time','detail','Ships: Within','Consegna: entro'),('hours','detail','hrs','ore'),('not_available','detail','Not available','Non disponibile'),('read_reviews','detail','Read all reviews','Leggi le recensioni'),('write_review','detail','Write a review','Scrivi una recensione'),('cust_rating','detail','Customer Rating','Valutazione acquirenti'),('based_on','detail','Based on','Basato su'),('reviews','detail','reviews','recensioni'),('cart_summary','summary','Items in your cart','Prodotti nel tuo carrello'),('category_shop','category','Shop by category','Prodotti per categoria'),('written_by','reviews','Written by','Scritto da'),('top_products','top_products','Top rated products','Prodotti pi&ugrave; votati'),('username','login','Username','Nome utente'),('purge_logs','admin','Purge log table','Svuota tabella eventi'),('all_necessary','new_product','All fields are mandatory','Tutti i campi sono necessari'),('product_name','new_product','product name','nome prodotto'),('category','new_product','category','categoria'),('description','new_product','description','descrizione'),('description_txt','new_product','[here goes the description of your brand new product]','[inserire qui la descrizione del prodotto]'),('page_title','new_product','Insert a new product','Inserisci un nuovo prodotto'),('availability','new_product','availability','disponibilit&agrave;'),('price','new_product','price','prezzo'),('image','new_product','image','immagine'),('privacy','index','Privacy policy','Privacy'),('invalid_login','login','Invalid username or password','Nome utente o password errata'),('page_title','edit_product','Edit product','Modifica scheda prodotto'),('ship_time','edit_product','Ship time','Tempi di consegna'),('update','edit_product','Update','Modifica'),('price_numeric','edit_product','Price must be numeric','Il prezzo deve essere numerico'),('avail_numeric','edit_product','Availability must be numeric','La disponibilit&agrave; deve essere numerica'),('product_name','edit_product','product name','nome prodotto'),('category','edit_product','category','categoria'),('description','edit_product','description','descrizione'),('price','edit_product','price','prezzo'),('availability','edit_product','availability','disponibilit&agrave;'),('password','login','Password','Parola chiave'),('auth','login','Authenticate','Autenticati'),('login_required','login','Login is required','Il campo utente &egrave; obbligatorio'),('pass_required','login','Password is required','Il campo password &egrave; obbligatorio'),('required_note','login','denotes required field','indica un campo obbligatorio'),('catame','category','Category name','Nome categoria'),('image','category','SVG file','File SVG'),('catname_required','category','Category name is required','Il nome della categoria &egrave; obbligatorio'),('insert','category','Insert','Aggiungi'),('required_note','category','denotes required field','indica un campo obbligatorio'),('image_required','category','Category image is required','L\'immagine della categoria &egrave; obbligatorio'),('file_too_big','category','File size is too big','La dimensione del file &egrave; troppo grande'),('first_name','edit','First name','Nome'),('form_header','edit','Edit user profile','Modifica profilo utente'),('last_name','edit','Last name','Cognome'),('update','edit','Update','Aggiorna'),('email','edit','e-mail','Indirizzo mail'),('email_required','edit','email address is required','Il campo email &egrave; obbligatorio'),('pass_required','edit','Password is required','Il campo password &egrave; obbligatorio'),('required_note','edit','denotes required field','indica un campo obbligatorio'),('password','edit','Password','Parola chiave'),('address','edit','Address','Indirizzo'),('address_group','edit','Address details','Dettaglio indirizzo'),('cap','edit','Code','CAP'),('city','edit','City','Citt&agrave;'),('state','edit','State','Stato'),('passwd_err','edit','The two passwords do not match. Please re-enter your password correctly.','Le parole chiave non sono uguali tra loro. Inserirle correttamente.'),('fname_required','edit','First name is required','Il nome &egrave; obbligatorio'),('lname_required','edit','Last name is required','Il cognome &egrave; obbligatorio'),('page_title','settings','Site settings','Impostazioni sito'),('theme','settings','Theme','Tema'),('theme_header','settings','Theme selection','Selezione tema'),('lang_header','settings','Default language','Lingua predefinita'),('lang','settings','Languages','Lingue'),('rss_header','settings','Product RSS','RSS dei prodotti'),('rss','settings','Feed RSS','RSS feed'),('smtp_header','settings','SMTP settings','Impostazioni SMTP'),('smtp_server','settings','SMTP server','Server SMTP'),('smtp_port','settings','Port (default 25)','Porta (predef. 25)'),('smtp_user','settings','Username','Utente'),('smtp_pass','settings','Password','Parola chiave'),('smtp_helo','settings','HELO string','Stringa di HELO'),('save','settings','Save','Salva'),('port_num_err','settings','SMTP port must be numeric','La porta SMTP deve essere numerica'),('smtp_server_req','settings','SMTP server is required','Il serverSMTP &egrave; obbligatorio'),('required_note','settings','denotes required field','indica un campo obbligatorio'),('smtp_port_req','settings','SMTP port is required','La porta SMTP &egrave; obbligatoria'),('trans_list','admin','Transactions list','Elenco transazioni'),('page_title','trans','Transactions list','Elenco transazioni'),('page_title','edit','Edit user profile','Modifica profilo utente'),('page_title','admin','Admin tools','Strumenti di amministrazione'),('product_name','search','Product name','Nome prodotto'),('search','search','Search','Cerca'),('descr_search','search','Search in description','Cerca anche nella descrizione'),('page_title','search','Product search','Ricerca prodotti'),('page_title','login','User login','Autenticazione utente'),('login_header','login','Authorized user only','Accesso consentito ai soli utenti autorizzati'),('page_title','category','Add a new category','Creazione nuova categoria'),('cat_header','category','New category detail','Dettagli nuova categoria'),('search_header','search','Product search','Ricerca prodotti'),('page_title','wr_review','Write a review','Scrivi una recensione'),('err_required','wr_review','All fields are mandatory','Tutti i campi sono necessari'),('tip1','wr_review','Explain why you like or dislike the product, focusing your comments on the product\'s features & functionality and your own experience using the product','Spiega perch&egrave; apprezzi o meno il prodotto, focalizzando i commenti sulle funzionalit&agrave; del prodotto e la tua esperienza nell\'uso del prodotto stesso'),('tip2','wr_review','Limit your review to 300 words or less','Limita la recensione a non pi&ugrave; di 300 parole'),('tip3','wr_review','Avoid single-word reviews, bad language, contact information (email addresses, phone numbers, etc.), URLs, time-sensitive material or alternative ordering information','Evita recensioni di una sola parola, linguaggio offensivo, informazioni su contatti (indirizzi email, numeri di telefono, ecc), URL, o informazioni relative a ordini alternativi'),('tip4','wr_review','Avoid comments about non-product related issues such as service and support, resellers, shipping, sales policies, other /dev/zero partners or /dev/zero topics not directly related to the product\'s features or functionality','Evita commenti riguardo il supporto, rivenditori, consegne, politiche di vendita, altri partner di /dev/zero o argomenti di /dev/zero non direttamente connessi al prodotto o alle sue funzionalit&agrave;'),('tip5','wr_review','Check back in five business days to see your review','Controlla la tua recensione dopo cinque giorni lavorativi'),('tip_header','wr_review','Review Tips','Suggerimenti'),('write_for','wr_review','Write a review for','Scrivi una recensione per'),('write_for-end','wr_review','Your experiences and opinions will help others make decisions before they make important purchases.','Le tue esperienze e opinioni aiuteranno gli altri a prendere decisioni importanti per l\'acquisto'),('cc_error','checkout','Specify the credit card number','Specificare il numero di carta di credito'),('page_title','checkout','Cart checkout','Pagamento ordine'),('cc','checkout','Credit Card','Carta di credito'),('similiar','detail','Similiar products','Prodotti simili'),('image','edit_product','Image','Immagine'),('ship_time','new_product','Ship time','Tempo di spedizione'),('page_title','forgot','Forgot password','Password dimenticata'),('username','forgot','username','nome utente'),('reset','forgot','Reset password','Reset password'),('invalid_code','register','Invalid security code','Codice di sicurezza errato'),('req_fields','register','Marked fields are mandatory: fill them all','I campi evidenziati sono obbligatori: riempirli tutti'),('err_pwd','register','Passwords don\'t match: retype correctly','Le password non sono uguali: digitarle correttamente'),('err_policy','register','You must read and approve Our privacy policy','Si deve leggere e approvare la nostra politica della privacy'),('err_email','register','Invalid email format','Formato email non valido'),('err_login','register','Login already used! Choose another one.','Login gi&agrave; utilizzata: sceglierne un\'altra'),('err_pwd_short','register','Password too short (at least 8 chars)','Password troppo corta (almeno 8 caratteri'),('err_pwd_char','register','Password contains invalid chars','La password contiene caratteri non validi'),('err_pwd_variety','register','Password doesn\'t contain enough chars variety','La password non contiene una sufficiente variet&agrave; di caratteri'),('page_title','register','Register new user','Registrazione nuovo utente'),('first_name','register','first name','nome'),('last_name','register','last name','cognome'),('email','register','e-mail address','indirizzo email'),('loading','register','Loading','Caricamento'),('repeat_pwd','register','repeat password','ripeti password'),('address','register','address','indirizzo'),('city','register','city','citt&agrave;'),('state','register','state','stato'),('sec_code','register','security code','codice di sicurezza'),('save','register','Save','Registra'),('policy1','register','By checking this checkbox, you indicate that you agree to our User Agreement and','Marcando questo campo, si dichiara la volont&agrave; di accettare il nostro User Agreement e'),('policy2','register','Privacy Policy','Politica della privacy'),('page_title','detail','Product detail','Dettaglio prodotto'),('page_title','products','Products list','Catalogo prodotti'),('page_title','top_rated','Top rated products','Prodotti pi&ugrave; votati'),('sent_msg','forgot','If user exists, a new password will be sent to his mailbox','Qualora l\'utente esista, verr&agrave; inviata una nuova password alla sua mailbox'),('req_field','forgot','Please specify a login','Si prega di specificare una login'),('order_total','summary','Order Total','Totale ordine'),('vat','summary','V.A.T.','IVA'),('sub_total','summary','Sub-total','Sub-totale'),('not_found','search','No products were found with these criteria','Non &egrave; stato trvato alcun prodotto'),('page_title','last_product','New to the Shop','Ultimi prodotti inseriti'),('last_header','last_product','How many products','Quanti prodotti'),('count','last_product','Products','Prodotti'),('update','last_product','Update','Aggiorna'),('insert','new_product','Insert','Aggiungi'),('required_note','new_product','denotes required field','indica un campo obbligatorio'),('name_required','new_product','product name is required','il nome del prodotto &egrave; obbligatorio'),('file_too_big','new_product','File size is too big','La dimensione del file &egrave; troppo grande'),('image_required','new_product','Product image is required','L\'immagine del prodotto &egrave; obbligatoria'),('price_required','new_product','Product price is required','Il prezzo del prodotto &egrave; obbligatorio'),('avail_required','new_product','Product availability is required','La disponibilit&agrave; del prodotto &egrave; obbligatorio'),('shiptime_required','new_product','Product ship time is required','Il tempo di consegna del prodotto &egrave; obbligatorio');
/*!40000 ALTER TABLE `strings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transactions`
--

DROP TABLE IF EXISTS `transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transactions` (
  `tid` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL,
  `uid` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
  `count` int(11) NOT NULL,
  `total` double NOT NULL,
  PRIMARY KEY (`tid`),
  KEY `uid` (`uid`),
  KEY `pid` (`pid`),
  CONSTRAINT `pid` FOREIGN KEY (`pid`) REFERENCES `products` (`pid`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `uid` FOREIGN KEY (`uid`) REFERENCES `users` (`uid`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transactions`
--

LOCK TABLES `transactions` WRITE;
/*!40000 ALTER TABLE `transactions` DISABLE KEYS */;
INSERT INTO `transactions` VALUES (5,'2009-07-20 13:20:10',2,1,1,1799),(6,'2009-07-20 13:20:10',2,3,1,150),(7,'2009-07-20 13:22:06',2,3,1,150),(8,'2009-07-22 13:57:47',2,1,2,3598),(9,'2009-07-31 07:08:11',2,1,2,3598),(10,'2009-07-31 07:08:11',2,20,1,269.65),(11,'2009-07-31 07:08:11',2,12,1,99),(12,'2009-08-10 18:00:59',10,24,1,999),(13,'2009-08-10 18:00:59',10,11,1,39),(14,'2009-08-15 17:50:01',2,6,1,599),(15,'2009-08-15 17:53:22',10,1,1,1799),(16,'2009-08-17 17:18:37',2,31,1,79);
/*!40000 ALTER TABLE `transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(45) NOT NULL,
  `last_name` varchar(45) NOT NULL,
  `login` varchar(45) NOT NULL,
  `password` varchar(45) NOT NULL,
  `avatar` varchar(45) DEFAULT 'default.png',
  `email` varchar(50) NOT NULL,
  `valid` tinyint(1) DEFAULT '0',
  `level` int(11) NOT NULL DEFAULT '1',
  `cap` varchar(5) DEFAULT NULL,
  `address` varchar(65) DEFAULT NULL,
  `city` varchar(45) DEFAULT NULL,
  `state` varchar(45) DEFAULT NULL,
  `basket` text,
  `securecode` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Admin','User','admin','*58C036CDA51D8E8BBBBF2F9EA5ABF111ADA444F0','admin.png','unixo@devzero.it',1,0,'00100','via da qualche parte','rome','italia','O:9:\"FV_Basket\":2:{s:15:\"\0FV_Basket\0_uid\";s:5:\"admin\";s:17:\"\0FV_Basket\0_items\";a:0:{}}',NULL),(2,'Ferruccio','Vitale','unixo','*180222C0FDFC23751B7A5479FAD9D5CCA0D8A5F8','user.png','ferruccio.vitale@tin.it',1,1,'00040','via del Sagittario 17/F','Pomezia (RM)','Italia','O:9:\"FV_Basket\":2:{s:15:\"\0FV_Basket\0_uid\";s:5:\"unixo\";s:17:\"\0FV_Basket\0_items\";a:0:{}}',NULL),(5,'Alessandro','Roverelli','tazly','*EEEAAECF6668CDDC1BBDCB7F754B4083725D47C0','default.png','alessandro@roverelli.net',1,1,'','','','',NULL,NULL),(6,'Giancarlo','Morri','morri','*F08CE430EF76B0BD53B32DD5C92F5D62EF07AA95','default.png','morri@sti.uniurb.it',1,1,NULL,NULL,NULL,NULL,NULL,NULL),(8,'pippo','pluto','pippo','*51AE483480626D9414B2E159D3427DBAD51DF9C3','default.png','pippopippo.1968@gmail.com',0,1,'000','qq','qerr','fiidsufio',NULL,'0965187739bb42541f66455a2c4a70ab'),(9,'Ferruccio','Vitale','ferruccio','*2D6E2FE75C804D7E702B836DAEF8FF36BBF89CDF','default.png','ferruccio.vitale@tin.it',1,1,'00040','via Sagittario 17F','Pomezia','Italy',NULL,'ee3763ed49b1d92b5f164b8823037951'),(10,'Maria Grazia','Staiani','madiash','*396EC0C40CC3CA530B6CFC08E67FF17E78CD7288','default.png','mgstaiani@gmail.com',1,1,'00040','via Sagittario 17F','pomezia','italy','O:9:\"FV_Basket\":2:{s:15:\"\0FV_Basket\0_uid\";s:7:\"madiash\";s:17:\"\0FV_Basket\0_items\";a:0:{}}','a00e8d6d41c47e519942eecdedce9a59');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Final view structure for view `all_products`
--

/*!50001 DROP TABLE `all_products`*/;
/*!50001 DROP VIEW IF EXISTS `all_products`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = latin1 */;
/*!50001 SET character_set_results     = latin1 */;
/*!50001 SET collation_connection      = latin1_swedish_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `all_products` AS select `C`.`name` AS `cat_name`,`C`.`image` AS `cat_image`,`P`.`pid` AS `pid`,`P`.`cid` AS `cid`,`P`.`name` AS `name`,`P`.`descr` AS `descr`,`P`.`price` AS `price`,`P`.`availability` AS `availability`,`P`.`image` AS `prd_image` from (`categories` `C` join `products` `P`) where (`C`.`cid` = `P`.`cid`) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2009-09-09 14:44:58
