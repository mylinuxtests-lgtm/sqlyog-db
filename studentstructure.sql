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

/*Table structure for table `sexo` */

DROP TABLE IF EXISTS `sexo`;

CREATE TABLE `sexo` (
  `id_sexo` int(11) NOT NULL,
  `descripcion` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id_sexo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

/*Table structure for table `student` */

DROP TABLE IF EXISTS `student`;

CREATE TABLE `student` (
  `id_students` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `id_sexo` int(11) DEFAULT NULL,
  `edad` decimal(10,0) NOT NULL,
  `nacimiento` date NOT NULL,
  `id_paises` int(11) DEFAULT NULL,
  `telefono` decimal(13,0) NOT NULL,
  `correo` varchar(50) NOT NULL,
  `domicilio` varchar(255) NOT NULL,
  `foto` varchar(255) NOT NULL,
  `lista` varchar(255) NOT NULL,
  `excel` varchar(255) NOT NULL,
  PRIMARY KEY (`id_students`),
  KEY `id_paises` (`id_paises`),
  KEY `id_sexo` (`id_sexo`),
  CONSTRAINT `student_ibfk_1` FOREIGN KEY (`id_paises`) REFERENCES `paises` (`id_paises`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `student_ibfk_2` FOREIGN KEY (`id_sexo`) REFERENCES `sexo` (`id_sexo`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

/*Table structure for table `vw_estudiantes` */

DROP TABLE IF EXISTS `vw_estudiantes`;

/*!50001 DROP VIEW IF EXISTS `vw_estudiantes` */;
/*!50001 DROP TABLE IF EXISTS `vw_estudiantes` */;

/*!50001 CREATE TABLE  `vw_estudiantes`(
 `ID` int(11) ,
 `Nombre` varchar(50) ,
 `Sexo` varchar(20) ,
 `Edad` decimal(10,0) ,
 `Fecha_Nacimiento` date ,
 `País` char(50) ,
 `Teléfono` decimal(13,0) ,
 `Correo` varchar(50) ,
 `Domicilio` varchar(255) ,
 `Foto` varchar(255) ,
 `Lista` varchar(255) ,
 `Excel` varchar(255) 
)*/;

/*View structure for view vw_estudiantes */

/*!50001 DROP TABLE IF EXISTS `vw_estudiantes` */;
/*!50001 DROP VIEW IF EXISTS `vw_estudiantes` */;

/*!50001 CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `vw_estudiantes` AS select `s`.`id_students` AS `ID`,`s`.`nombre` AS `Nombre`,`sex`.`descripcion` AS `Sexo`,`s`.`edad` AS `Edad`,`s`.`nacimiento` AS `Fecha_Nacimiento`,`p`.`pais` AS `País`,`s`.`telefono` AS `Teléfono`,`s`.`correo` AS `Correo`,`s`.`domicilio` AS `Domicilio`,`s`.`foto` AS `Foto`,`s`.`lista` AS `Lista`,`s`.`excel` AS `Excel` from ((`student` `s` join `sexo` `sex` on(`s`.`id_sexo` = `sex`.`id_sexo`)) join `paises` `p` on(`s`.`id_paises` = `p`.`id_paises`)) */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
