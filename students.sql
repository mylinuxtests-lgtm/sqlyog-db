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
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

/*Data for the table `student` */

insert  into `student`(`id_students`,`nombre`,`sexo`,`edad`,`nacimiento`,`id_paises`,`telefono`,`correo`,`domicilio`,`foto`,`lista`,`excel`) values (1,'Willie Houston\r\n',1,18,'1969-12-17',8,4827471182,'willie@gmail.com\r\n','hola','/uploads/documents/images.jpg','/home/sebastian/Documents/uploads/documents/lista.txt','/home/sebastian/Documents/uploads/documents/lista.csv'),(2,'Doris Powell\r\n',2,25,'2025-03-19',3,4771647617,'doris 23@hotmail.com\r\n','yhnah','','',''),(3,'Milo Barrett\r\n',1,34,'1984-10-25',1,4831926311,'123juan@gmail.com\r\n','dadfawq','','',''),(4,'Benjamin Hurst\r\n',1,23,'2025-09-24',5,4794377263,'dayne.baumbach@gmail.com\r\n','fhhaks','','',''),(5,'Caiden Chen\r\n',1,17,'2009-04-15',2,4913777173,'80leonora champlin@hotmail.com','qfdasqf','','',''),(6,'Caitlyn Holden',2,21,'1999-12-22',10,3488288324,'eudora.vandervort70@gmail.com','jjdjw','','',''),(7,'Kelvin Gardner',1,20,'2013-03-07',3,4883771456,'lorna_gottlieb91@yahoo.com','roowi','','',''),(8,'Enya Nash',2,17,'1983-09-09',7,5882774756,'johathan5@mail.net\r\n','pkqjhhr','','',''),(9,'Tina Chandler\r\n',2,31,'2008-01-10',4,9388462177,'bell.predovic58@gmail.com\r\n','mjqjfhha','','',''),(10,'Haroon Hudson',1,45,'2018-11-14',9,3991743991,'77elmira@hotmail.com\r\n','qyyrhha','','',''),(11,'Faizan Boone',2,20,'2008-05-23',1,2994771848,'noah6_@gmail.com','iiuurh','','',''),(12,'Eleni Wilkinson',2,14,'2000-07-01',2,6994781994,'mina9@hotmail.com','ijmfyhq','','',''),(13,'Haseeb Richmond',1,18,'1986-08-14',6,1288367184,'destany_bayèr@yahoo.com\r\n','ooqujdy','','',''),(14,'Penny Benjamin',2,16,'2019-03-06',10,177747189,'*dasia54@yahoo.com\r\n','ujnehd','','',''),(15,'Omari Lowe',2,37,'1999-02-18',8,5718835518,'jeanette_fay40@gmail.com','tthayynd','','',''),(16,'Nicholas Daniel\r\n',1,34,'1976-03-31',4,7667167461,'àriane_olson@yahoo.com','ahhhsqh','','',''),(17,'Kyle Parker\r\n',1,54,'2017-12-18',7,3991646718,'emil33@hotmail.com\r\n','bbatghhe','','',''),(18,'Jake Vasquez\r\n',1,21,'2011-06-22',3,4989174713,'desiree31@hotmail.com\r\n','yhayhrg','','',''),(19,'Lenny Holmes',1,23,'2022-04-26',2,1826417119,'adolfo49!@yahoo.com','eqhhftb','','',''),(20,'Victor Hayden\r\n',1,19,'2008-01-12',1,5919467184,'marcelo.bartell29@yahoo.com','nghad','','','');

/*Table structure for table `vw_estudiantes` */

DROP TABLE IF EXISTS `vw_estudiantes`;

/*!50001 DROP VIEW IF EXISTS `vw_estudiantes` */;
/*!50001 DROP TABLE IF EXISTS `vw_estudiantes` */;

/*!50001 CREATE TABLE  `vw_estudiantes`(
 `id_students` int(11) ,
 `Nombre` varchar(50) ,
 `Sexo` varchar(15) ,
 `Edad` decimal(10,0) ,
 `Nacimiento` date ,
 `Pais` char(50) ,
 `Telefono` decimal(10,0) ,
 `Correo` varchar(50) ,
 `Domicilio` varchar(255) ,
 `Foto` varchar(255) ,
 `Lista` varchar(255) ,
 `Excel` varchar(255) 
)*/;

/*View structure for view vw_estudiantes */

/*!50001 DROP TABLE IF EXISTS `vw_estudiantes` */;
/*!50001 DROP VIEW IF EXISTS `vw_estudiantes` */;

/*!50001 CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `vw_estudiantes` AS select `s`.`id_students` AS `id_students`,`s`.`nombre` AS `Nombre`,case when `s`.`sexo` = 1 then 'Hombre' when `s`.`sexo` = 2 then 'Mujer' when `s`.`sexo` = 3 then 'Otro' else 'No especificado' end AS `Sexo`,`s`.`edad` AS `Edad`,`s`.`nacimiento` AS `Nacimiento`,`p`.`pais` AS `Pais`,`s`.`telefono` AS `Telefono`,`s`.`correo` AS `Correo`,`s`.`domicilio` AS `Domicilio`,`s`.`foto` AS `Foto`,`s`.`lista` AS `Lista`,`s`.`excel` AS `Excel` from (`student` `s` left join `paises` `p` on(`s`.`id_paises` = `p`.`id_paises`)) */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
