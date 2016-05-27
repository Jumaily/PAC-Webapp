# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: localhost (MySQL 5.7.11)
# Database: RISuk
# Generation Time: 2016-05-26 18:59:14 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table addprocedures
# ------------------------------------------------------------

DROP TABLE IF EXISTS `addprocedures`;

CREATE TABLE `addprocedures` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uname` varchar(10) DEFAULT NULL,
  `fullname` varchar(100) DEFAULT NULL,
  `sessionkey` varchar(20) DEFAULT NULL,
  `processed` enum('N','Y') NOT NULL DEFAULT 'N',
  `syngo_sent` enum('N','Y') NOT NULL DEFAULT 'N',
  `locationsentfrom` varchar(100) DEFAULT NULL,
  `datetimestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `dept` varchar(5) DEFAULT NULL,
  `proc_desc_short` varchar(5) DEFAULT '',
  `proc_desc_long` varchar(32) DEFAULT NULL,
  `cpt_code1` varchar(10) DEFAULT NULL,
  `cpt_descp2` varchar(10) DEFAULT NULL,
  `cpt_descp3` varchar(12) DEFAULT NULL,
  `cpt_descp4` varchar(5) DEFAULT NULL,
  `body_part_mne` varchar(10) DEFAULT NULL,
  `orderable` enum('N','Y') NOT NULL DEFAULT 'N',
  `proc_no` int(10) DEFAULT NULL,
  `dtl_svc_cd` varchar(50) DEFAULT NULL,
  `sub_folder` varchar(15) DEFAULT NULL,
  `hosp` varchar(5) DEFAULT 'UKH',
  `proc_left_right` varchar(5) DEFAULT NULL,
  `user_cd_1` varchar(45) DEFAULT NULL,
  `user_cd1_descp` varchar(45) DEFAULT NULL,
  `user_cd_2` varchar(45) DEFAULT NULL,
  `user_cd2_descp` varchar(45) DEFAULT NULL,
  `mammography_flag` enum('N','Y') NOT NULL DEFAULT 'N',
  `active_flag` enum('N','Y') NOT NULL DEFAULT 'Y',
  `view_reactions` enum('N','Y') NOT NULL DEFAULT 'Y',
  `pacs_flag` enum('N','Y') NOT NULL DEFAULT 'Y',
  PRIMARY KEY (`id`),
  KEY `uname` (`uname`),
  KEY `syngo_sent` (`processed`),
  KEY `processed` (`processed`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table admins
# ------------------------------------------------------------

DROP TABLE IF EXISTS `admins`;

CREATE TABLE `admins` (
  `admins` varchar(50) NOT NULL,
  PRIMARY KEY (`admins`),
  UNIQUE KEY `admins_UNIQUE` (`admins`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `admins` WRITE;
/*!40000 ALTER TABLE `admins` DISABLE KEYS */;

INSERT INTO `admins` (`admins`)
VALUES
	('talju0');

/*!40000 ALTER TABLE `admins` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table departments
# ------------------------------------------------------------

DROP TABLE IF EXISTS `departments`;

CREATE TABLE `departments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dept` varchar(45) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `rcode` varchar(5) DEFAULT NULL,
  `costcenter` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  UNIQUE KEY `dept_UNIQUE` (`dept`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `departments` WRITE;
/*!40000 ALTER TABLE `departments` DISABLE KEYS */;

INSERT INTO `departments` (`id`, `dept`, `description`, `rcode`, `costcenter`)
VALUES
	(1,'CAR','CARDIOLOGY','R01','2522'),
	(2,'CEI','CT ED INTERVENTIONAL','R27','2244'),
	(3,'CTE','CT ED','R23','2244'),
	(4,'CTG','CT GILL','G03','2243'),
	(5,'CTH','CT HOSPITAL','R04','2233'),
	(6,'CTI','CT HOSPITAL - INVASIVE','R05','2233'),
	(7,'FLU','FLUOROSCOPY','R06','2231'),
	(8,'GRC','GENERAL RADIOGRAPHY CLINIC','R07','2237'),
	(9,'GRE','GENERAL RADIOGRAPHY ED','R22','2244'),
	(10,'GRG','GENERAL RADIOLOGY GILL','G08','2243'),
	(11,'GRH','GENERAL RADIOGRAPHY HOSPITAL','R09','2231'),
	(12,'GRN','POLK DALTON CLINIC NORTH','R10','8920'),
	(13,'IR ','INTERVENTIONAL RADIOGRAPHY','R12','2235'),
	(14,'KSM','KENTUCKY SPORTS MEDICINE','R13','2245'),
	(15,'MAC','COMPREHENSIVE BREAST CARE CENTER','R14','1595'),
	(16,'MRC','MRI CLINIC','R16','2237'),
	(17,'MRG','MRI GILL','G17','2243'),
	(18,'MRH','MRI HOSPITAL','R18','2234'),
	(19,'NUC','NUCLEAR MEDICINE','R19','2240'),
	(20,'OUT','OUTSIDE STUDIES','R26','2231'),
	(21,'POC','POINT OF CARE','R25','1180'),
	(22,'USE','ULTRASOUND ED','R24','2244'),
	(23,'USH','ULTRASOUND HOSPITAL','R20','2241'),
	(24,'VAS','VASCULAR LAB','R26','none'),
	(25,'WHC','WHITE HOUSE CLINIC','R27',''),
	(26,'XCC','GS CARDIAC CATH','X01','none'),
	(27,'XCI','GS CT INVASIVE','X02','7210'),
	(28,'XCT','GS CT','X03','7210'),
	(29,'XFL','GS FLUOROSCOPY','X04','7200'),
	(30,'XIR','GS INTERVENTIONAL','X05','7300'),
	(31,'XMR','GS DIAGNOSTIC CENTER MRI','X07','7270'),
	(32,'XNM','GS NUCLEAR MEDICINE','X08','7220'),
	(33,'XUS','GS ULTRASOUND','X09','7250'),
	(34,'XXR','GS GENERAL','X10','7200');

/*!40000 ALTER TABLE `departments` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  UNIQUE KEY `user_UNIQUE` (`user`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;

INSERT INTO `users` (`id`, `user`)
VALUES
	(1,'talju0a');

/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table weblogs
# ------------------------------------------------------------

DROP TABLE IF EXISTS `weblogs`;

CREATE TABLE `weblogs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uname` varchar(20) DEFAULT NULL,
  `firstname` varchar(50) DEFAULT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `action` varchar(255) DEFAULT NULL,
  `location` varchar(20) DEFAULT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `datetime` (`datetime`),
  KEY `uname` (`uname`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `weblogs` WRITE;
/*!40000 ALTER TABLE `weblogs` DISABLE KEYS */;

INSERT INTO `weblogs` (`id`, `uname`, `firstname`, `lastname`, `action`, `location`, `datetime`)
VALUES
	(1,'talju0','Taha','Al-jumaily','Successful Login','172.21.13.174','2016-05-11 08:28:28'),
	(2,'talju0','Taha','Al-jumaily','Successful Login','172.21.13.174','2016-05-12 15:12:08'),
	(3,'talju0','Taha','Al-jumaily','Deleting Technical Charge... - Accession#  : Invalid Number','172.21.13.174','2016-05-12 15:16:19'),
	(4,'talju0','Taha','Al-jumaily','Deleting Technical Charge... - Accession# 234234234234 : Error: No Matching Record Found','172.21.13.174','2016-05-12 15:16:42'),
	(5,'talju0','Taha','Al-jumaily','Deleting Technical Charge... Accession# 123412312 Error: No Matching Record Found','172.21.13.174','2016-05-12 15:17:50'),
	(6,'talju0','Taha','Al-jumaily','Deleting Technical Charge... Accession# 6987456 Error: No Matching Record Found','172.21.13.174','2016-05-12 15:18:11'),
	(7,'talju0','Taha','Al-jumaily','Successful Login','172.21.13.174','2016-05-12 20:03:22'),
	(8,'talju0','Taha','Al-jumaily','Submit Procedures - Sessionkey: oQAQ8-1605122013','172.21.13.174','2016-05-12 20:13:46'),
	(9,'talju0','Taha','Al-jumaily','Submit Procedures - Sessionkey: 6TID0-1605122025','172.21.13.174','2016-05-12 20:25:55'),
	(10,'talju0','Taha','Al-jumaily','Submit Procedures - Sessionkey: i0CZd-1605122026','172.21.13.174','2016-05-12 20:26:55'),
	(11,'talju0','Taha','Al-jumaily','Submit Procedures - Sessionkey: 7UjiX-1605122100','172.21.13.174','2016-05-12 21:00:40'),
	(12,'talju0','Taha','Al-jumaily','Submit Procedures - Sessionkey: 5eVT8-1605122125','172.21.13.174','2016-05-12 21:25:55'),
	(13,'talju0','Taha','Al-jumaily','Submit Procedures - Sessionkey: MVECU-1605122145','172.21.13.174','2016-05-12 21:45:48'),
	(14,'talju0','Taha','Al-jumaily','Submit Procedures - Sessionkey: jYBPn-1605122147','172.21.13.174','2016-05-12 21:47:41'),
	(15,'talju0','Taha','Al-jumaily','DELETE FROM addprocedures WHERE (id=\'9\')','172.21.13.174','2016-05-12 21:55:26'),
	(16,'talju0','Taha','Al-jumaily','DELETE FROM addprocedures WHERE (id=\'8\') && (uname=\'talju0\')','172.21.13.174','2016-05-12 21:56:31'),
	(17,'talju0','Taha','Al-jumaily','mysql -> DELETE FROM addprocedures WHERE (id=\'7\') && (uname=\'talju0\')','172.21.13.174','2016-05-12 21:57:13'),
	(18,'talju0','Taha','Al-jumaily','Submit Procedures - Sessionkey: ac1n6-1605122159','172.21.13.174','2016-05-12 21:59:21'),
	(19,'talju0','Taha','Al-jumaily','Successful Login','172.21.13.174','2016-05-12 22:40:57'),
	(20,'talju0','Taha','Al-jumaily','Successful Login','172.21.13.174','2016-05-12 22:46:05'),
	(21,'talju0','Taha','Al-jumaily','Successful Login','172.21.13.174','2016-05-12 22:54:17'),
	(22,'talju0','Taha','Al-jumaily','Submit Procedures - Sessionkey: V8Xle-1605122301','172.21.13.174','2016-05-12 23:01:17'),
	(23,'talju0','Taha','Al-jumaily','Successful Login','172.21.13.174','2016-05-13 08:30:40'),
	(24,'talju0a','Taha','Al-jumaily','Successful Login','172.21.13.174','2016-05-13 08:44:39'),
	(25,'talju0','Taha','Al-jumaily','Successful Login','172.21.13.174','2016-05-13 08:45:02'),
	(26,'talju0a','Taha','Al-jumaily','Successful Login','172.21.13.174','2016-05-13 08:58:19'),
	(27,'talju0a','Taha','Al-jumaily','Submit Procedures - Sessionkey: MpxaL-1605130858','172.21.13.174','2016-05-13 08:58:34');

/*!40000 ALTER TABLE `weblogs` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
