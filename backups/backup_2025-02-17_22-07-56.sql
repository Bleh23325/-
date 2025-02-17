-- MySQL dump 10.13  Distrib 8.2.0, for Win64 (x86_64)
--
-- Host: localhost    Database: tz3
-- ------------------------------------------------------
-- Server version	8.2.0

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `address`
--

DROP TABLE IF EXISTS `address`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `address` (
  `id_adres` int NOT NULL AUTO_INCREMENT,
  `street` varchar(30) NOT NULL,
  `house` int NOT NULL,
  `Worker` int NOT NULL,
  `city` varchar(50) NOT NULL,
  `apartment` int NOT NULL,
  PRIMARY KEY (`id_adres`),
  KEY `Worker` (`Worker`),
  CONSTRAINT `address_ibfk_1` FOREIGN KEY (`Worker`) REFERENCES `Worker` (`id_w`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `address`
--

LOCK TABLES `address` WRITE;
/*!40000 ALTER TABLE `address` DISABLE KEYS */;
INSERT INTO `address` VALUES (6,'Григорьевская',2,6,'Ярославль',2),(7,'Прощина',3,8,'Нижний новгород',3),(8,'Гаврилова',1,9,'Ульяновск',2),(9,'Опорина',3,11,'Набережные челны',1),(10,'Колотушкина',2,12,'Новгород',2),(15,'Улица',2,23,'Тутаев',5);
/*!40000 ALTER TABLE `address` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `data_worker`
--

DROP TABLE IF EXISTS `data_worker`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `data_worker` (
  `id_dw` int NOT NULL AUTO_INCREMENT,
  `seria_pasporta` int NOT NULL,
  `nomer_pasporta` int NOT NULL,
  `Worker` int NOT NULL,
  `who_issue` varchar(60) NOT NULL,
  `when_issue` date NOT NULL,
  PRIMARY KEY (`id_dw`),
  KEY `id_dw` (`id_dw`),
  KEY `Worker` (`Worker`),
  CONSTRAINT `data_worker_ibfk_1` FOREIGN KEY (`Worker`) REFERENCES `Worker` (`id_w`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `data_worker`
--

LOCK TABLES `data_worker` WRITE;
/*!40000 ALTER TABLE `data_worker` DISABLE KEYS */;
INSERT INTO `data_worker` VALUES (6,5151,214124,6,'УМВД','2025-02-01'),(7,1241,452345,8,'УМВД','2025-02-06'),(8,1133,414141,9,'УМВД','2025-02-04'),(9,1235,764317,11,'УМВД','2025-02-02'),(10,1223,125151,12,'УМВД','2025-02-07'),(15,1241,123512,23,'УМВД','2020-02-13');
/*!40000 ALTER TABLE `data_worker` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `department`
--

DROP TABLE IF EXISTS `department`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `department` (
  `id_departament` int NOT NULL AUTO_INCREMENT,
  `department` varchar(30) NOT NULL,
  PRIMARY KEY (`id_departament`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `department`
--

LOCK TABLES `department` WRITE;
/*!40000 ALTER TABLE `department` DISABLE KEYS */;
INSERT INTO `department` VALUES (1,'3В'),(2,'2Е'),(3,'Системный'),(4,'Управленческий'),(5,'6Г');
/*!40000 ALTER TABLE `department` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Dismissed`
--

DROP TABLE IF EXISTS `Dismissed`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Dismissed` (
  `id_dis` int NOT NULL AUTO_INCREMENT,
  `dismissed` varchar(15) NOT NULL,
  PRIMARY KEY (`id_dis`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Dismissed`
--

LOCK TABLES `Dismissed` WRITE;
/*!40000 ALTER TABLE `Dismissed` DISABLE KEYS */;
INSERT INTO `Dismissed` VALUES (1,'Уволен'),(2,'Работает'),(3,'В отпуске'),(4,'В декрете'),(5,'На больничном');
/*!40000 ALTER TABLE `Dismissed` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `info_worker`
--

DROP TABLE IF EXISTS `info_worker`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `info_worker` (
  `id_iw` int NOT NULL AUTO_INCREMENT,
  `phone` varchar(30) NOT NULL,
  `Worker` int NOT NULL,
  PRIMARY KEY (`id_iw`),
  KEY `Worker` (`Worker`),
  CONSTRAINT `info_worker_ibfk_1` FOREIGN KEY (`Worker`) REFERENCES `Worker` (`id_w`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `info_worker`
--

LOCK TABLES `info_worker` WRITE;
/*!40000 ALTER TABLE `info_worker` DISABLE KEYS */;
INSERT INTO `info_worker` VALUES (7,'+7 (213) 523-15-21',6),(8,'+7 (512) 421-42-44',8),(9,'+7 (112) 241-41-41',9),(10,'+7 (658) 585-68-56',11),(11,'+7 (898) 797-97-97',12),(18,'+7 (121) 251-23-55',23);
/*!40000 ALTER TABLE `info_worker` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Job_title`
--

DROP TABLE IF EXISTS `Job_title`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Job_title` (
  `id_jt` int NOT NULL AUTO_INCREMENT,
  `Job_title` varchar(30) NOT NULL,
  PRIMARY KEY (`id_jt`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Job_title`
--

LOCK TABLES `Job_title` WRITE;
/*!40000 ALTER TABLE `Job_title` DISABLE KEYS */;
INSERT INTO `Job_title` VALUES (1,'Системный администратор'),(2,'Бухгалтер'),(3,'HR'),(4,'Менеджер по продажам'),(5,'Уборщик');
/*!40000 ALTER TABLE `Job_title` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roots`
--

DROP TABLE IF EXISTS `roots`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roots` (
  `id` int NOT NULL,
  `name` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roots`
--

LOCK TABLES `roots` WRITE;
/*!40000 ALTER TABLE `roots` DISABLE KEYS */;
INSERT INTO `roots` VALUES (1,'Админ'),(2,'Менеджер');
/*!40000 ALTER TABLE `roots` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `time_of_absence`
--

DROP TABLE IF EXISTS `time_of_absence`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `time_of_absence` (
  `id` int NOT NULL AUTO_INCREMENT,
  `fst_date` date NOT NULL,
  `last_date` date DEFAULT NULL,
  `worker` int NOT NULL,
  `statys` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `worker` (`worker`),
  KEY `statys` (`statys`),
  CONSTRAINT `time_of_absence_ibfk_1` FOREIGN KEY (`worker`) REFERENCES `Worker` (`id_w`),
  CONSTRAINT `time_of_absence_ibfk_2` FOREIGN KEY (`statys`) REFERENCES `Dismissed` (`id_dis`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `time_of_absence`
--

LOCK TABLES `time_of_absence` WRITE;
/*!40000 ALTER TABLE `time_of_absence` DISABLE KEYS */;
INSERT INTO `time_of_absence` VALUES (1,'2025-01-01',NULL,11,2),(2,'2024-12-01','2025-01-07',8,4),(3,'2012-01-25','2019-01-25',9,3),(5,'2025-02-01',NULL,6,2),(6,'2025-02-01','2025-02-14',8,4),(7,'2025-02-01','2025-02-10',9,5),(8,'2025-02-14',NULL,12,1),(9,'2025-02-09',NULL,23,2);
/*!40000 ALTER TABLE `time_of_absence` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL,
  `name` varchar(30) NOT NULL,
  `password` varchar(50) NOT NULL,
  `roots` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `roots` (`roots`),
  CONSTRAINT `users_ibfk_1` FOREIGN KEY (`roots`) REFERENCES `roots` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin','admin',1),(2,'user','user',2);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Worker`
--

DROP TABLE IF EXISTS `Worker`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Worker` (
  `id_w` int NOT NULL AUTO_INCREMENT,
  `Familia` varchar(30) NOT NULL,
  `Ima` varchar(30) NOT NULL,
  `Otchestvo` varchar(30) NOT NULL,
  `department` int NOT NULL,
  `jod_title` int NOT NULL,
  `data_rojdenia` date NOT NULL,
  `zarplata` int NOT NULL,
  `data_zachislenia` date NOT NULL,
  PRIMARY KEY (`id_w`),
  KEY `telefon` (`department`,`jod_title`),
  KEY `department` (`department`),
  KEY `jod_title` (`jod_title`),
  CONSTRAINT `worker_ibfk_1` FOREIGN KEY (`department`) REFERENCES `department` (`id_departament`),
  CONSTRAINT `worker_ibfk_5` FOREIGN KEY (`jod_title`) REFERENCES `Job_title` (`id_jt`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Worker`
--

LOCK TABLES `Worker` WRITE;
/*!40000 ALTER TABLE `Worker` DISABLE KEYS */;
INSERT INTO `Worker` VALUES (6,'Григорьев','Дмитрий','Григорьевич',2,1,'2024-10-05',1231,'2024-10-03'),(8,'Олегов','Дмитрий','Олегович',4,4,'2024-10-03',55,'2024-10-05'),(9,'Анатольев','Анаьолий','Анатольевич',3,4,'2024-10-02',41414,'2024-10-04'),(11,'Агапов','Олег','Агапович',1,5,'1990-10-01',25000,'2024-08-01'),(12,'Борисов','Денис','Владимирович',1,1,'2025-02-01',12000,'2025-02-02'),(23,'Бутузов','Иван','Александрович',1,1,'2006-03-14',67000,'2025-02-09');
/*!40000 ALTER TABLE `Worker` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`%`*/ /*!50003 TRIGGER `after_worker_insert` AFTER INSERT ON `worker` FOR EACH ROW BEGIN
    -- Вставка новой записи в таблицу time_of_absence с использованием данных из вставленного работника
    INSERT INTO time_of_absence (fst_date, worker, statys)
    VALUES (NEW.data_zachislenia, NEW.id_w, 2);
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-02-17 22:07:56
