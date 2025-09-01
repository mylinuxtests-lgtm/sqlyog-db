/*
SQLyog Community v13.3.0 (64 bit)
MySQL - 12.0.2-MariaDB : Database - students
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`students` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci */;

USE `students`;

/*Table structure for table `paises` */

DROP TABLE IF EXISTS `paises`;

CREATE TABLE `paises` (
  `id_paises` int(11) NOT NULL AUTO_INCREMENT,
  `pais` char(50) DEFAULT NULL,
  PRIMARY KEY (`id_paises`),
  KEY `id_paises` (`id_paises`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

/*Data for the table `paises` */

insert  into `paises`(`id_paises`,`pais`) values (1,'Alemania'),(2,'Brazil'),(3,'Canada'),(4,'China'),(5,'Estados Unidos'),(6,'India'),(7,'Indonesia'),(8,'Japon'),(9,'Mexico'),(10,'Rusia');

/*Table structure for table `student` */

DROP TABLE IF EXISTS `student`;

CREATE TABLE `student` (
  `id_students` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `sexo` int(11) NOT NULL,
  `edad` decimal(10,0) NOT NULL,
  `nacimiento` date NOT NULL,
  `id_paises` int(11) DEFAULT NULL,
  `telefono` decimal(10,0) NOT NULL,
  `correo` varchar(50) NOT NULL,
  `domicilio` varchar(255) NOT NULL,
  `foto` varchar(255) NOT NULL,
  `lista` varchar(255) NOT NULL,
  `excel` varchar(255) NOT NULL,
  PRIMARY KEY (`id_students`),
  KEY `id_paises` (`id_paises`),
  CONSTRAINT `student_ibfk_1` FOREIGN KEY (`id_paises`) REFERENCES `paises` (`id_paises`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

/*Data for the table `student` */

insert  into `student`(`id_students`,`nombre`,`sexo`,`edad`,`nacimiento`,`id_paises`,`telefono`,`correo`,`domicilio`,`foto`,`lista`,`excel`) values (1,'Willie Houston\r\n',1,18,'1969-12-17',8,4827471182,'willie@gmail.com\r\n','hola','/uploads/documents/images.jpg','/home/sebastian/Documents/uploads/documents/lista.txt','/home/sebastian/Documents/uploads/documents/lista.csv'),(2,'Doris Powell\r\n',2,25,'2025-03-19',3,4771647617,'doris 23@hotmail.com\r\n','yhnah','','',''),(3,'Milo Barrett\r\n',1,34,'1984-10-25',1,4831926311,'123juan@gmail.com\r\n','','','',''),(4,'Benjamin Hurst\r\n',1,23,'2025-09-24',5,4794377263,'dayne.baumbach@gmail.com\r\n','fhhaks','','',''),(5,'Caiden Chen\r\n',1,17,'2009-04-15',2,4913777173,'80leonora champlin@hotmail.com','fds','','',''),(6,'Caitlyn Holden',2,21,'1999-12-22',10,3488288324,'eudora.vandervort70@gmail.com','jjdjw','','',''),(7,'Kelvin Gardner',1,20,'2013-03-07',3,4883771456,'lorna_gottlieb91@yahoo.com','roowi','','',''),(8,'Enya Nash',2,17,'1983-09-09',7,5882774756,'johathan5@mail.net\r\n','pkqjhhr','','',''),(9,'Tina Chandler\r\n',2,31,'2008-01-10',4,9388462177,'bell.predovic58@gmail.com\r\n','mjqjfhha','','',''),(10,'Haroon Hudson',1,45,'2018-11-14',9,3991743991,'77elmira@hotmail.com\r\n','qyyrhha','','','');

/*Table structure for table `vw_students` */

DROP TABLE IF EXISTS `vw_students`;

/*!50001 DROP VIEW IF EXISTS `vw_students` */;
/*!50001 DROP TABLE IF EXISTS `vw_students` */;

/*!50001 CREATE TABLE  `vw_students`(
 `id_students` int(11) ,
 `nombre` varchar(50) ,
 `edad` decimal(10,0) ,
 `nacimiento` date ,
 `pais` char(50) ,
 `telefono` decimal(10,0) ,
 `correo` varchar(50) ,
 `domicilio` varchar(255) ,
 `foto` varchar(255) ,
 `lista` varchar(255) ,
 `excel` varchar(255) 
)*/;

/*View structure for view vw_students */

/*!50001 DROP TABLE IF EXISTS `vw_students` */;
/*!50001 DROP VIEW IF EXISTS `vw_students` */;

/*!50001 CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `vw_students` AS select `s`.`id_students` AS `id_students`,`s`.`nombre` AS `nombre`,`s`.`edad` AS `edad`,`s`.`nacimiento` AS `nacimiento`,`p`.`pais` AS `pais`,`s`.`telefono` AS `telefono`,`s`.`correo` AS `correo`,`s`.`domicilio` AS `domicilio`,`s`.`foto` AS `foto`,`s`.`lista` AS `lista`,`s`.`excel` AS `excel` from (`student` `s` left join `paises` `p` on(`s`.`id_paises` = `p`.`id_paises`)) */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
