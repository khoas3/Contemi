/*
SQLyog Enterprise - MySQL GUI v8.1 
MySQL - 5.5.41-0ubuntu0.14.04.1 : Database - contemi
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

CREATE DATABASE /*!32312 IF NOT EXISTS*/`contemi` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `contemi`;

/*Table structure for table `gcide` */

DROP TABLE IF EXISTS `gcide`;

CREATE TABLE `gcide` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `w` varchar(50) NOT NULL,
  `def` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=123825 DEFAULT CHARSET=latin1;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
