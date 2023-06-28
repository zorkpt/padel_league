-- MySQL dump 10.13  Distrib 8.0.33, for Linux (x86_64)
--
-- Host: localhost    Database: zorkpadel
-- ------------------------------------------------------
-- Server version	8.0.33-0ubuntu0.22.04.2

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
-- Table structure for table `Chat`
--

DROP TABLE IF EXISTS `Chat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Chat` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_liga` int DEFAULT NULL,
  `id_utilizador` int DEFAULT NULL,
  `mensagem` text,
  `data_hora` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_liga` (`id_liga`),
  KEY `id_utilizador` (`id_utilizador`),
  CONSTRAINT `Chat_ibfk_1` FOREIGN KEY (`id_liga`) REFERENCES `Ligas` (`id`),
  CONSTRAINT `Chat_ibfk_2` FOREIGN KEY (`id_utilizador`) REFERENCES `Utilizadores` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Chat`
--

LOCK TABLES `Chat` WRITE;
/*!40000 ALTER TABLE `Chat` DISABLE KEYS */;
/*!40000 ALTER TABLE `Chat` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Jogadores_Jogo`
--

DROP TABLE IF EXISTS `Jogadores_Jogo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Jogadores_Jogo` (
  `id_utilizador` int NOT NULL,
  `id_jogo` int NOT NULL,
  `pontuacao` int DEFAULT NULL,
  `equipa` int DEFAULT NULL,
  PRIMARY KEY (`id_utilizador`,`id_jogo`),
  KEY `id_jogo` (`id_jogo`),
  CONSTRAINT `Jogadores_Jogo_ibfk_1` FOREIGN KEY (`id_utilizador`) REFERENCES `Utilizadores` (`id`),
  CONSTRAINT `Jogadores_Jogo_ibfk_2` FOREIGN KEY (`id_jogo`) REFERENCES `Jogos` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Jogadores_Jogo`
--

LOCK TABLES `Jogadores_Jogo` WRITE;
/*!40000 ALTER TABLE `Jogadores_Jogo` DISABLE KEYS */;
INSERT INTO `Jogadores_Jogo` VALUES (1,3,NULL,1),(6,3,NULL,1),(6,4,NULL,NULL),(7,3,NULL,2),(8,3,NULL,2),(9,5,NULL,1),(9,6,NULL,1),(10,5,NULL,1),(10,6,NULL,2),(11,5,NULL,2),(11,6,NULL,2),(12,5,NULL,2),(12,6,NULL,1);
/*!40000 ALTER TABLE `Jogadores_Jogo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Jogos`
--

DROP TABLE IF EXISTS `Jogos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Jogos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_liga` int DEFAULT NULL,
  `local` varchar(255) NOT NULL,
  `data_hora` datetime DEFAULT NULL,
  `status` int DEFAULT NULL,
  `team1_score` int DEFAULT '0',
  `team2_score` int DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `id_liga` (`id_liga`),
  CONSTRAINT `Jogos_ibfk_1` FOREIGN KEY (`id_liga`) REFERENCES `Ligas` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Jogos`
--

LOCK TABLES `Jogos` WRITE;
/*!40000 ALTER TABLE `Jogos` DISABLE KEYS */;
INSERT INTO `Jogos` VALUES (1,1,'Amorim','2023-06-30 16:00:00',1,0,0),(3,3,'Porto','2023-07-05 16:00:00',2,2,1),(4,1,'amorim','2023-07-31 15:00:00',1,0,0),(5,4,'Porto','2023-07-01 16:00:00',0,2,1),(6,4,'caxinas','2023-08-01 18:00:00',0,2,1);
/*!40000 ALTER TABLE `Jogos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Ligas`
--

DROP TABLE IF EXISTS `Ligas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Ligas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `descricao` varchar(255) DEFAULT NULL,
  `id_criador` int DEFAULT NULL,
  `data_criacao` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `codigo_convite` varchar(5) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_criador` (`id_criador`),
  CONSTRAINT `Ligas_ibfk_1` FOREIGN KEY (`id_criador`) REFERENCES `Utilizadores` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Ligas`
--

LOCK TABLES `Ligas` WRITE;
/*!40000 ALTER TABLE `Ligas` DISABLE KEYS */;
INSERT INTO `Ligas` VALUES (1,'krokets','Liga dos kroketoes',6,'2023-06-25 21:49:37','BBBBB'),(3,'Snakes','Descrição desta liga de padel',6,'2023-06-25 23:30:14','AAAAA'),(4,'Thunders','Thundercats',6,'2023-06-27 20:02:36','XVX2Z');
/*!40000 ALTER TABLE `Ligas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Membros_Liga`
--

DROP TABLE IF EXISTS `Membros_Liga`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Membros_Liga` (
  `id_utilizador` int NOT NULL,
  `id_liga` int NOT NULL,
  `data_admissao` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `admin` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id_utilizador`,`id_liga`),
  KEY `id_liga` (`id_liga`),
  CONSTRAINT `Membros_Liga_ibfk_1` FOREIGN KEY (`id_utilizador`) REFERENCES `Utilizadores` (`id`),
  CONSTRAINT `Membros_Liga_ibfk_2` FOREIGN KEY (`id_liga`) REFERENCES `Ligas` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Membros_Liga`
--

LOCK TABLES `Membros_Liga` WRITE;
/*!40000 ALTER TABLE `Membros_Liga` DISABLE KEYS */;
INSERT INTO `Membros_Liga` VALUES (1,3,'2023-06-26 21:32:02',0),(6,1,'2023-06-25 21:51:01',6),(6,3,'2023-06-25 22:30:14',1),(6,4,'2023-06-27 19:02:36',1),(7,3,'2023-06-26 21:32:02',0),(8,3,'2023-06-26 21:32:02',0),(9,4,'2023-06-27 20:56:59',0),(10,4,'2023-06-27 20:58:25',0),(11,4,'2023-06-27 20:58:57',0),(12,4,'2023-06-27 21:09:16',0);
/*!40000 ALTER TABLE `Membros_Liga` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Ranking`
--

DROP TABLE IF EXISTS `Ranking`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Ranking` (
  `id_utilizador` int NOT NULL,
  `id_liga` int NOT NULL,
  `pontos` int DEFAULT '0',
  `jogos_jogados` int DEFAULT '0',
  `jogos_ganhos` int DEFAULT '0',
  `jogos_perdidos` int DEFAULT '0',
  PRIMARY KEY (`id_utilizador`,`id_liga`),
  KEY `id_liga` (`id_liga`),
  CONSTRAINT `Ranking_ibfk_1` FOREIGN KEY (`id_utilizador`) REFERENCES `Utilizadores` (`id`),
  CONSTRAINT `Ranking_ibfk_2` FOREIGN KEY (`id_liga`) REFERENCES `Ligas` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Ranking`
--

LOCK TABLES `Ranking` WRITE;
/*!40000 ALTER TABLE `Ranking` DISABLE KEYS */;
INSERT INTO `Ranking` VALUES (9,4,7,8,7,1),(10,4,6,7,6,1),(11,4,1,7,1,6),(12,4,1,7,1,6);
/*!40000 ALTER TABLE `Ranking` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Utilizadores`
--

DROP TABLE IF EXISTS `Utilizadores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Utilizadores` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome_utilizador` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `data_registo` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `avatar` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Utilizadores`
--

LOCK TABLES `Utilizadores` WRITE;
/*!40000 ALTER TABLE `Utilizadores` DISABLE KEYS */;
INSERT INTO `Utilizadores` VALUES (1,'zorkpt','hugopocas@gmail.com','$2y$10$V4Oxb9UNZgEgERgaEb1Jm.p3nt9clsyAYg1IK7TNHEz2Acu.3pyiy','2023-06-23 17:06:41',NULL),(6,'admin','hugopocas@gmail.comddd','$2y$10$0P1YRUZfBejv7FNvlzRpnOBH/sN8FCDI.Um.DYHTNRJDuGw3yV5G6','2023-06-25 21:42:49',NULL),(7,'karapodre','dasda@dasd.dasd','dasdasd','2023-06-25 21:42:49',NULL),(8,'Joao','joao@dasd.dd','dasd','2023-06-25 21:42:49',NULL),(9,'user1','user@sada.dsad','$2y$10$ru5cR16gtq7sRcrXRlrzYuBTROv2h8.TEL7ugjn6Y2qr3Gc47ghK6','2023-06-27 21:56:36',NULL),(10,'user2','sda@dasd.dsad','$2y$10$5ujTwBI1b5x3sMx3YfEguOZvqStxLItuC6rUQ7sL9.c.MePBJkjr.','2023-06-27 21:58:07',NULL),(11,'user3','sad@sadasd.das','$2y$10$aDxdao/G1fYVHlIIBvj2fuGkwaPwum9WPaE5MIBl/vA.xvvjQVjka','2023-06-27 21:58:48',NULL),(12,'user5','usd@dasd.dd','$2y$10$7pc7e5LZNG/yx8MTOB7WgedNO5ecEI8hUwSTGTgvSmdC3UsvPeCNm','2023-06-27 22:08:58',NULL);
/*!40000 ALTER TABLE `Utilizadores` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-06-28 14:18:05
