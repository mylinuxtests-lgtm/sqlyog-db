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

insert  into `paises`(`id_paises`,`pais`) values (1,'Alemania'),(2,'Brazil'),(3,'Canadá'),(4,'China'),(5,'Estados Unidos'),(6,'India'),(7,'Indonesia'),(8,'Japón'),(9,'México'),(10,'Rusia');

/*Table structure for table `sexo` */

DROP TABLE IF EXISTS `sexo`;

CREATE TABLE `sexo` (
  `id_sexo` int(11) NOT NULL,
  `descripcion` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id_sexo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

/*Data for the table `sexo` */

insert  into `sexo`(`id_sexo`,`descripcion`) values (1,'Masculino'),(2,'Femenino'),(3,'Otro');

/*Table structure for table `student` */

DROP TABLE IF EXISTS `student`;

CREATE TABLE `student` (
  `id_students` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `id_sexo` int(11) DEFAULT NULL,
  `edad` int(3) NOT NULL,
  `nacimiento` date NOT NULL,
  `id_paises` int(11) DEFAULT NULL,
  `telefono` varchar(13) NOT NULL,
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

/*Data for the table `student` */

insert  into `student`(`id_students`,`nombre`,`id_sexo`,`edad`,`nacimiento`,`id_paises`,`telefono`,`correo`,`domicilio`,`foto`,`lista`,`excel`) values (1,'Willie Houston',1,18,'2007-04-06',8,'806071555766','willie@gmail.com','5869 Isabel Travessa, Ponte de Sor, PA 8955','uploads/documents/images.jpg','uploads/documents/lista.txt','uploads/documents/lista.csv'),(2,'Doris Powell',2,25,'2000-03-19',3,'15489028700','doris 23@hotmail.com','Apt. 683 487 Jerome Port, Bernhardfort, WY 59789-4131','uploads/documents/red-fox.jpg','uploads/documents/compras.txt','uploads/documents/lista.xlsx'),(3,'Milo Barrett',1,34,'1991-10-25',1,'4915310454309','123juan@gmail.com','Zimmer 620 Sonderburger Str. 1, Malikberg, BW 17608','uploads/documents/images.jpg','uploads/documents/lista.txt','uploads/documents/lista.csv'),(4,'Benjamin Hurst',1,23,'2002-08-24',5,'13052556891','dayne.baumbach@gmail.com','Suite 917 6808 Annie Forest, Lake Shelachester, GA 56230','uploads/documents/red-fox.jpg','uploads/documents/compras.txt','uploads/documents/lista.xlsx'),(5,'Caiden Chen',1,17,'2008-04-15',2,'5535937861638','80leonora champlin@hotmail.com','Puerta 831 Salida Laura, 98, Paterna, Bal 19389','uploads/documents/images.jpg','uploads/documents/lista.txt','uploads/documents/lista.csv'),(6,'Caitlyn Holden',2,21,'2004-12-22',10,'79355293637','eudora.vandervort70@gmail.com','Apt. 590 al. Łukasik 970, Mława, PM 65-288','uploads/documents/red-fox.jpg','uploads/documents/compras.txt','uploads/documents/lista.xlsx'),(7,'Kelvin Gardner',1,20,'2005-03-07',3,'17533871307','lorna_gottlieb91@yahoo.com','480 Ferne Shores, Auermouth, KS 81250','uploads/documents/images.jpg','uploads/documents/lista.txt','uploads/documents/lista.csv'),(8,'Enya Nash',2,17,'2008-07-09',7,'6287643519210','johathan5@mail.net','Jl. Hayamwuruk No. 77, Intan Jaya, JW 41660','uploads/documents/red-fox.jpg','uploads/documents/compras.txt','uploads/documents/lista.xlsx'),(9,'Tina Chandler',2,31,'1994-01-10',4,'8617405689715','bell.predovic58@gmail.com','62630 Peggie Port, Bartonberg, CO 43458-6975','uploads/documents/images.jpg','uploads/documents/lista.txt','uploads/documents/lista.csv'),(10,'Haroon Hudson',1,45,'1979-11-14',9,'524954854367','77elmira@hotmail.com','Parque César 85 Esc. 828, Benalmádena, Ext 60878','uploads/documents/red-fox.jpg','uploads/documents/compras.txt','uploads/documents/lista.xlsx'),(11,'Faizan Boone',2,20,'2005-05-23',1,'4916294146737','noah6_@gmail.com','3 OG Sandstr. 45c, Jeremiegrün, HE 50723','uploads/documents/images.jpg','uploads/documents/lista.txt','uploads/documents/lista.csv'),(12,'Eleni Wilkinson',2,14,'2011-07-01',2,'553771516979','mina9@hotmail.com','Jl. Rasuna Said No. 65, Jakarta Barat, YO 72880','uploads/documents/red-fox.jpg','uploads/documents/compras.txt','uploads/documents/lista.xlsx'),(13,'Haseeb Richmond',1,18,'2007-08-14',6,'917477473348','destany_bayèr@yahoo.com','Lote 43 2434 Carvalho Viela, Mangualde, MA 3117','uploads/documents/images.jpg','uploads/documents/lista.txt','uploads/documents/lista.csv'),(14,'Penny Benjamin',2,16,'2009-03-06',10,'79926911701','*dasia54@yahoo.com','al. Golec 173, Bytom Odrzański, PK 28-156','uploads/documents/red-fox.jpg','uploads/documents/compras.txt','uploads/documents/lista.xlsx'),(15,'Omari Lowe',2,37,'1988-02-18',8,'808074983691','jeanette_fay40@gmail.com','19056 Silas Drive, Rockyfurt, MD 41345','uploads/documents/images.jpg','uploads/documents/lista.txt','uploads/documents/lista.csv'),(16,'Nicholas Daniel',1,34,'1991-03-31',4,'8618580687830','àriane_olson@yahoo.com','al. Skalski 664, Zambrów, LS 95-860','uploads/documents/red-fox.jpg','uploads/documents/compras.txt','uploads/documents/lista.xlsx'),(17,'Kyle Parker',1,54,'1971-12-18',7,'6283702962929','emil33@hotmail.com','Jl. Gatot Soebroto No. 83, Cilacap, SA 63944','uploads/documents/images.jpg','uploads/documents/lista.txt','uploads/documents/lista.csv'),(18,'Jake Vasquez',1,21,'2004-06-22',3,'19027451024','desiree31@hotmail.com','2825 Wiza Port, Hilpertchester, CT 02715-8367','uploads/documents/red-fox.jpg','uploads/documents/compras.txt','uploads/documents/lista.xlsx'),(19,'Lenny Holmes',1,23,'2002-04-26',2,'555471014777','adolfo49!@yahoo.com','Edificio Elisa 29 Esc. 358, Cuenta, Man 63032','uploads/documents/images.jpg','uploads/documents/lista.txt','uploads/documents/lista.csv'),(20,'Victor Hayden',1,19,'2006-01-12',1,'4916093023754','marcelo.bartell29@yahoo.com','Graf-Galen-Platz 73, Bad Ninohagen, BB 46183','uploads/documents/red-fox.jpg','uploads/documents/compras.txt','uploads/documents/lista.xlsx');

/*Table structure for table `vw_estudiantes_correo` */

DROP TABLE IF EXISTS `vw_estudiantes_correo`;

/*!50001 DROP VIEW IF EXISTS `vw_estudiantes_correo` */;
/*!50001 DROP TABLE IF EXISTS `vw_estudiantes_correo` */;

/*!50001 CREATE TABLE  `vw_estudiantes_correo`(
 `ID` int(11) ,
 `Nombre` varchar(50) ,
 `Sexo` varchar(20) ,
 `Edad` int(3) ,
 `Fecha_Nacimiento` date ,
 `País` char(50) ,
 `Teléfono` varchar(13) ,
 `Correo` varchar(50) ,
 `Domicilio` varchar(255) ,
 `Foto` varchar(255) ,
 `Lista` varchar(255) ,
 `Excel` varchar(255) 
)*/;

/*Table structure for table `vw_estudiantes` */

DROP TABLE IF EXISTS `vw_estudiantes`;

/*!50001 DROP VIEW IF EXISTS `vw_estudiantes` */;
/*!50001 DROP TABLE IF EXISTS `vw_estudiantes` */;

/*!50001 CREATE TABLE  `vw_estudiantes`(
 `ID` int(11) ,
 `Nombre` varchar(50) ,
 `Sexo` varchar(20) ,
 `Edad` int(3) ,
 `Fecha_Nacimiento` date ,
 `País` char(50) ,
 `Teléfono` varchar(13) ,
 `Correo` varchar(50) ,
 `Domicilio` varchar(255) ,
 `Foto` varchar(255) ,
 `Lista` varchar(255) ,
 `Excel` varchar(255) 
)*/;

/*Table structure for table `vw_estudiantes_sexo` */

DROP TABLE IF EXISTS `vw_estudiantes_sexo`;

/*!50001 DROP VIEW IF EXISTS `vw_estudiantes_sexo` */;
/*!50001 DROP TABLE IF EXISTS `vw_estudiantes_sexo` */;

/*!50001 CREATE TABLE  `vw_estudiantes_sexo`(
 `ID` int(11) ,
 `Nombre` varchar(50) ,
 `Sexo` varchar(20) ,
 `Edad` int(3) ,
 `Fecha_Nacimiento` date ,
 `País` char(50) ,
 `Teléfono` varchar(13) ,
 `Correo` varchar(50) ,
 `Domicilio` varchar(255) ,
 `Foto` varchar(255) ,
 `Lista` varchar(255) ,
 `Excel` varchar(255) 
)*/;

/*View structure for view vw_estudiantes_correo */

/*!50001 DROP TABLE IF EXISTS `vw_estudiantes_correo` */;
/*!50001 DROP VIEW IF EXISTS `vw_estudiantes_correo` */;

/*!50001 CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `vw_estudiantes_correo` AS select `vw_estudiantes`.`ID` AS `ID`,`vw_estudiantes`.`Nombre` AS `Nombre`,`vw_estudiantes`.`Sexo` AS `Sexo`,`vw_estudiantes`.`Edad` AS `Edad`,`vw_estudiantes`.`Fecha_Nacimiento` AS `Fecha_Nacimiento`,`vw_estudiantes`.`País` AS `País`,`vw_estudiantes`.`Teléfono` AS `Teléfono`,`vw_estudiantes`.`Correo` AS `Correo`,`vw_estudiantes`.`Domicilio` AS `Domicilio`,`vw_estudiantes`.`Foto` AS `Foto`,`vw_estudiantes`.`Lista` AS `Lista`,`vw_estudiantes`.`Excel` AS `Excel` from `vw_estudiantes` where `vw_estudiantes`.`Correo` regexp '^[A-Za-z][A-Za-z0-9._%-]*@[A-Za-z0-9.-]+\\.[A-Za-z]{2,4}$' and !(`vw_estudiantes`.`Correo` regexp '\\.[A-Za-z0-9._%-]*@') and `vw_estudiantes`.`Correo` <> '' and `vw_estudiantes`.`Correo` is not null order by `vw_estudiantes`.`Correo` */;

/*View structure for view vw_estudiantes */

/*!50001 DROP TABLE IF EXISTS `vw_estudiantes` */;
/*!50001 DROP VIEW IF EXISTS `vw_estudiantes` */;

/*!50001 CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `vw_estudiantes` AS select `s`.`id_students` AS `ID`,`s`.`nombre` AS `Nombre`,`sex`.`descripcion` AS `Sexo`,`s`.`edad` AS `Edad`,`s`.`nacimiento` AS `Fecha_Nacimiento`,`p`.`pais` AS `País`,`s`.`telefono` AS `Teléfono`,`s`.`correo` AS `Correo`,`s`.`domicilio` AS `Domicilio`,`s`.`foto` AS `Foto`,`s`.`lista` AS `Lista`,`s`.`excel` AS `Excel` from ((`student` `s` join `sexo` `sex` on(`s`.`id_sexo` = `sex`.`id_sexo`)) join `paises` `p` on(`s`.`id_paises` = `p`.`id_paises`)) */;

/*View structure for view vw_estudiantes_sexo */

/*!50001 DROP TABLE IF EXISTS `vw_estudiantes_sexo` */;
/*!50001 DROP VIEW IF EXISTS `vw_estudiantes_sexo` */;

/*!50001 CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `vw_estudiantes_sexo` AS select `vw_estudiantes`.`ID` AS `ID`,`vw_estudiantes`.`Nombre` AS `Nombre`,`vw_estudiantes`.`Sexo` AS `Sexo`,`vw_estudiantes`.`Edad` AS `Edad`,`vw_estudiantes`.`Fecha_Nacimiento` AS `Fecha_Nacimiento`,`vw_estudiantes`.`País` AS `País`,`vw_estudiantes`.`Teléfono` AS `Teléfono`,`vw_estudiantes`.`Correo` AS `Correo`,`vw_estudiantes`.`Domicilio` AS `Domicilio`,`vw_estudiantes`.`Foto` AS `Foto`,`vw_estudiantes`.`Lista` AS `Lista`,`vw_estudiantes`.`Excel` AS `Excel` from `vw_estudiantes` where `vw_estudiantes`.`Sexo` = 'Femenino' */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
