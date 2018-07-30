# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.7.22)
# Database: voucher
# Generation Time: 2018-07-29 19:44:51 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table phinxlog
# ------------------------------------------------------------

DROP TABLE IF EXISTS `phinxlog`;

CREATE TABLE `phinxlog` (
  `version` bigint(20) NOT NULL,
  `migration_name` varchar(100) DEFAULT NULL,
  `start_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `end_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `phinxlog` WRITE;
/*!40000 ALTER TABLE `phinxlog` DISABLE KEYS */;

INSERT INTO `phinxlog` (`version`, `migration_name`, `start_time`, `end_time`)
VALUES
	(20180729110811,'AddDatabaseSchema','2018-07-29 13:23:26','2018-07-29 13:23:26');

/*!40000 ALTER TABLE `phinxlog` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table special_offers
# ------------------------------------------------------------

DROP TABLE IF EXISTS `special_offers`;

CREATE TABLE `special_offers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `discount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` enum('active','disabled') NOT NULL DEFAULT 'active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `special_offers` WRITE;
/*!40000 ALTER TABLE `special_offers` DISABLE KEYS */;

INSERT INTO `special_offers` (`id`, `name`, `discount`, `created_at`, `updated_at`, `status`)
VALUES
	(1,'aloz ki',60.00,'2018-07-29 13:25:16','2018-07-29 13:25:16','active'),
	(2,'aloz ki',60.00,'2018-07-29 18:06:45','2018-07-29 18:06:45','active'),
	(3,'aloz ki',60.00,'2018-07-29 19:28:20','2018-07-29 19:28:20','active'),
	(4,'new offer',70.00,'2018-07-29 19:32:42','2018-07-29 19:32:42','active'),
	(5,'aloz ki',60.00,'2018-07-29 19:36:18','2018-07-29 19:36:18','active');

/*!40000 ALTER TABLE `special_offers` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` char(40) NOT NULL,
  `name` varchar(150) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` enum('active','disabled') NOT NULL DEFAULT 'active',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;

INSERT INTO `users` (`id`, `uuid`, `name`, `email`, `created_at`, `updated_at`, `status`)
VALUES
	(1,'DSX89GIW7U','aloz aloz','aloz@gmail.com','2018-07-29 13:25:09','2018-07-29 13:25:09','active'),
	(2,'E67IQDPM4R','aloz aloz','alozz@gmail.com','2018-07-29 19:30:39','2018-07-29 19:30:39','active');

/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table vouchers
# ------------------------------------------------------------

DROP TABLE IF EXISTS `vouchers`;

CREATE TABLE `vouchers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(150) NOT NULL,
  `user_id` int(11) NOT NULL,
  `special_offer_id` int(11) NOT NULL,
  `expiry_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `expire_interval` int(11) NOT NULL,
  `is_used` tinyint(1) NOT NULL DEFAULT '0',
  `date_of_usage` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` enum('active','disabled') NOT NULL DEFAULT 'active',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `special_offer_id` (`special_offer_id`),
  CONSTRAINT `vouchers_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `vouchers_ibfk_2` FOREIGN KEY (`special_offer_id`) REFERENCES `special_offers` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `vouchers` WRITE;
/*!40000 ALTER TABLE `vouchers` DISABLE KEYS */;

INSERT INTO `vouchers` (`id`, `code`, `user_id`, `special_offer_id`, `expiry_date`, `expire_interval`, `is_used`, `date_of_usage`, `created_at`, `updated_at`, `status`)
VALUES
	(1,'4AM775S8',1,1,'2018-07-29 18:27:16',120,1,'2018-07-29 17:41:57','2018-07-29 13:25:16','2018-07-29 17:41:43','disabled'),
	(3,'H2185XE4',1,1,'2018-07-29 13:36:41',400,1,'2018-07-29 13:34:16','2018-07-29 13:30:01','2018-07-29 13:30:01','disabled'),
	(4,'LF4UZAIM',1,1,'2018-07-29 13:48:53',400,1,'2018-07-29 13:45:11','2018-07-29 13:42:55','2018-07-29 13:45:11','disabled'),
	(5,'8OVULPW1',1,2,'2018-07-29 18:08:45',120,0,NULL,'2018-07-29 18:06:45','2018-07-29 18:06:45','disabled'),
	(6,'5HF2F36P',1,2,'2018-07-29 18:13:25',400,0,NULL,'2018-07-29 18:06:45','2018-07-29 18:06:45','active'),
	(7,'5GKU5LRG',1,2,'2018-07-29 18:13:26',400,0,NULL,'2018-07-29 18:06:46','2018-07-29 18:06:46','active'),
	(8,'VQSY1AKA',1,3,'2018-07-29 19:30:20',120,0,NULL,'2018-07-29 19:28:20','2018-07-29 19:28:20','active'),
	(9,'7I9GLCKZ',1,2,'2018-07-29 19:35:00',400,0,NULL,'2018-07-29 19:28:20','2018-07-29 19:28:20','active'),
	(10,'LIDBAFKR',1,2,'2018-07-29 19:35:00',400,0,NULL,'2018-07-29 19:28:20','2018-07-29 19:28:20','active'),
	(11,'JQQMM34G',1,3,'2018-07-29 19:39:58',400,0,NULL,'2018-07-29 19:33:18','2018-07-29 19:33:18','active'),
	(12,'FYFIDBDW',1,4,'2018-07-29 19:40:21',400,1,'2018-07-29 19:35:50','2018-07-29 19:33:41','2018-07-29 19:33:41','disabled'),
	(13,'EWU1Z2X8',2,4,'2018-07-29 19:40:21',400,0,NULL,'2018-07-29 19:33:41','2018-07-29 19:33:41','active'),
	(14,'MUBUHQG0',1,5,'2018-07-29 19:38:18',120,0,NULL,'2018-07-29 19:36:18','2018-07-29 19:36:18','active'),
	(15,'XLDP0JSP',2,5,'2018-07-29 19:38:18',120,0,NULL,'2018-07-29 19:36:18','2018-07-29 19:36:18','active'),
	(16,'TTLGR7ED',1,2,'2018-07-29 19:42:58',400,0,NULL,'2018-07-29 19:36:18','2018-07-29 19:36:18','active'),
	(17,'53X6WJ5G',1,2,'2018-07-29 19:42:59',400,0,NULL,'2018-07-29 19:36:19','2018-07-29 19:36:19','active'),
	(18,'S5EJR39L',2,2,'2018-07-29 19:42:59',400,0,NULL,'2018-07-29 19:36:19','2018-07-29 19:36:19','active');

/*!40000 ALTER TABLE `vouchers` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
