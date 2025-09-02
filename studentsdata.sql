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

/*Data for the table `paises` */

insert  into `paises`(`id_paises`,`pais`) values (1,'Alemania'),(2,'Brazil'),(3,'Canada'),(4,'China'),(5,'Estados Unidos'),(6,'India'),(7,'Indonesia'),(8,'Japon'),(9,'Mexico'),(10,'Rusia');

/*Data for the table `sexo` */

insert  into `sexo`(`id_sexo`,`descripcion`) values (1,'Masculino'),(2,'Femenino'),(3,'Otro');

/*Data for the table `student` */

insert  into `student`(`id_students`,`nombre`,`id_sexo`,`edad`,`nacimiento`,`id_paises`,`telefono`,`correo`,`domicilio`,`foto`,`lista`,`excel`) values (1,'Willie Houston\r\n    ',1,18,'2007-04-06',8,806071555766,'willie@gmail.com','5869 Isabel Travessa, Ponte de Sor, PA 8955','/uploads/documents/images.jpg','/home/sebastian/Documents/uploads/documents/lista.txt','/home/sebastian/Documents/uploads/documents/lista.csv'),(2,'Doris Powell\r\n',2,25,'2000-03-19',3,15489028700,'doris 23@hotmail.com\r\n','Apt. 683 487 Jerome Port, Bernhardfort, WY 59789-4131','','',''),(3,'Milo Barrett\r\n',1,34,'1991-10-25',1,4915310454309,'123juan@gmail.com\r\n','Zimmer 620 Sonderburger Str. 1, Malikberg, BW 17608','','',''),(4,'Benjamin Hurst\r\n',1,23,'2002-08-24',5,13052556891,'dayne.baumbach@gmail.com\r\n','Suite 917 6808 Annie Forest, Lake Shelachester, GA 56230','','',''),(5,'Caiden Chen\r\n',1,17,'2008-04-15',2,5535937861638,'80leonora champlin@hotmail.com','Puerta 831 Salida Laura, 98, Paterna, Bal 19389','','',''),(6,'Caitlyn Holden',2,21,'2004-12-22',10,79355293637,'eudora.vandervort70@gmail.com','Apt. 590 al. Łukasik 970, Mława, PM 65-288','','',''),(7,'Kelvin Gardner',1,20,'2005-03-07',3,17533871307,'lorna_gottlieb91@yahoo.com','480 Ferne Shores, Auermouth, KS 81250','','',''),(8,'Enya Nash',2,17,'2008-07-09',7,6287643519210,'johathan5@mail.net\r\n','Jl. Hayamwuruk No. 77, Intan Jaya, JW 41660','','',''),(9,'Tina Chandler\r\n',2,31,'1994-01-10',4,8617405689715,'bell.predovic58@gmail.com\r\n','62630 Peggie Port, Bartonberg, CO 43458-6975','','',''),(10,'Haroon Hudson',1,45,'1979-11-14',9,524954854367,'77elmira@hotmail.com\r\n','Parque César 85 Esc. 828, Benalmádena, Ext 60878','','',''),(11,'Faizan Boone',2,20,'2005-05-23',1,4916294146737,'noah6_@gmail.com','3 OG Sandstr. 45c, Jeremiegrün, HE 50723','','',''),(12,'Eleni Wilkinson',2,14,'2011-07-01',2,553771516979,'mina9@hotmail.com','Jl. Rasuna Said No. 65, Jakarta Barat, YO 72880','','',''),(13,'Haseeb Richmond',1,18,'2007-08-14',6,917477473348,'destany_bayèr@yahoo.com\r\n','Lote 43 2434 Carvalho Viela, Mangualde, MA 3117','','',''),(14,'Penny Benjamin',2,16,'2009-03-06',10,79926911701,'*dasia54@yahoo.com\r\n','al. Golec 173, Bytom Odrzański, PK 28-156','','',''),(15,'Omari Lowe',2,37,'1988-02-18',8,808074983691,'jeanette_fay40@gmail.com','19056 Silas Drive, Rockyfurt, MD 41345','','',''),(16,'Nicholas Daniel\r\n',1,34,'1991-03-31',4,8618580687830,'àriane_olson@yahoo.com','al. Skalski 664, Zambrów, LS 95-860','','',''),(17,'Kyle Parker\r\n',1,54,'1971-12-18',7,6283702962929,'emil33@hotmail.com\r\n','Jl. Gatot Soebroto No. 83, Cilacap, SA 63944','','',''),(18,'Jake Vasquez\r\n',1,21,'2004-06-22',3,19027451024,'desiree31@hotmail.com\r\n','2825 Wiza Port, Hilpertchester, CT 02715-8367','','',''),(19,'Lenny Holmes',1,23,'2002-04-26',2,555471014777,'adolfo49!@yahoo.com','Edificio Elisa 29 Esc. 358, Cuenta, Man 63032','','',''),(20,'Victor Hayden\r\n',1,19,'2006-01-12',1,4916093023754,'marcelo.bartell29@yahoo.com','Graf-Galen-Platz 73, Bad Ninohagen, BB 46183','','','');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
