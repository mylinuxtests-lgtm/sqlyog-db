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
  PRIMARY KEY (`id_students`),
  KEY `id_paises` (`id_paises`),
  CONSTRAINT `student_ibfk_1` FOREIGN KEY (`id_paises`) REFERENCES `paises` (`id_paises`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

/*Data for the table `student` */

insert  into `student`(`id_students`,`nombre`,`sexo`,`edad`,`nacimiento`,`id_paises`,`telefono`,`correo`,`domicilio`) values (1,'sebastian',1,21,'1969-12-17',3,4827471182,'fdsfsd@gfd,com','hola'),(2,'elena',2,76,'2025-03-19',3,4771647617,'ujkajnhnd@gkma.com','yhnah'),(3,'vazquez',3,43,'1984-10-25',1,4831926311,'',''),(4,'luis',1,13,'2025-09-24',5,4794377263,'iklasdgyuab@gdiusn.com','fhhaks');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
