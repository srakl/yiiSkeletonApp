-- MySQL Administrator dump 1.4
--
-- ------------------------------------------------------
-- Server version	5.1.53-community-log


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


--
-- Create schema testdrive
--

CREATE DATABASE IF NOT EXISTS testdrive;
USE testdrive;

--
-- Definition of table `events`
--

DROP TABLE IF EXISTS `events`;
CREATE TABLE `events` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(64) NOT NULL,
  `all_day` tinyint(1) NOT NULL,
  `start` int(11) unsigned NOT NULL,
  `end` int(11) unsigned DEFAULT NULL,
  `url` varchar(255) NOT NULL,
  `class_name` varchar(32) NOT NULL,
  `editable` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `events`
--

/*!40000 ALTER TABLE `events` DISABLE KEYS */;
INSERT INTO `events` (`id`,`title`,`all_day`,`start`,`end`,`url`,`class_name`,`editable`) VALUES 
 (1,'Event One',0,1360677600,1360706400,'','',0);
/*!40000 ALTER TABLE `events` ENABLE KEYS */;


--
-- Definition of table `fb_user`
--

DROP TABLE IF EXISTS `fb_user`;
CREATE TABLE `fb_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `fb_uid` varchar(45) NOT NULL,
  PRIMARY KEY (`id`,`user_id`) USING BTREE,
  KEY `user_fb_fk` (`user_id`),
  CONSTRAINT `user_fb_fk` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 PACK_KEYS=0;

--
-- Dumping data for table `fb_user`
--

/*!40000 ALTER TABLE `fb_user` DISABLE KEYS */;
INSERT INTO `fb_user` (`id`,`user_id`,`fb_uid`) VALUES 
 (1,1,'100002077901514');
/*!40000 ALTER TABLE `fb_user` ENABLE KEYS */;


--
-- Definition of table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(150) NOT NULL,
  `email` varchar(255) NOT NULL,
  `first_name` varchar(45) NOT NULL,
  `last_name` varchar(45) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(45) NOT NULL,
  `state` varchar(45) NOT NULL,
  `postal_code` varchar(45) NOT NULL,
  `phone` varchar(45) NOT NULL,
  `password` varchar(63) NOT NULL,
  `salt` varchar(63) NOT NULL,
  `activate` varchar(63) NOT NULL,
  `last_login` datetime NOT NULL,
  `password_reset` int(11) unsigned NOT NULL,
  `admin` tinyint(1) unsigned NOT NULL,
  `email_verified` tinyint(1) unsigned NOT NULL,
  `login_disabled` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_username` (`username`) USING BTREE,
  UNIQUE KEY `unique_email` (`email`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` (`id`,`username`,`email`,`first_name`,`last_name`,`address`,`city`,`state`,`postal_code`,`phone`,`password`,`salt`,`activate`,`last_login`,`password_reset`,`admin`,`email_verified`,`login_disabled`) VALUES 
 (1,'admin','stroud.travis@gmail.com','Travis','Stroud','1000 Rio Grande','El Paso','TX','79902','9152555347','3b9a88a5e94a64e374c43ca5e36fa568d8cff2de','8aa2c95dc0a6833d2d0cb944555739cc','','2013-02-09 23:28:15',1360285721,1,1,0),
 (2,'','travis@travisstroud.co.uk','Travis','Stroud','','','','','','b5d7095ade54ae7f79558d7830bd0d22b63e6d62','81836b7cd16991abb7febfd7832927fd','70458437d5f122c68ed8e87f84e64392','2013-02-09 22:54:10',0,0,0,0);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;




/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
