-- MariaDB dump 10.19  Distrib 10.6.8-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: blog_db2
-- ------------------------------------------------------
-- Server version	10.6.8-MariaDB-1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `articles`
--

DROP TABLE IF EXISTS `articles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `articles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `author_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `articles`
--

LOCK TABLES `articles` WRITE;
/*!40000 ALTER TABLE `articles` DISABLE KEYS */;
INSERT INTO `articles` VALUES (1,1,'Статья1','Текст статьи 1','2022-04-11 14:03:57'),(4,2,'Статья2','Текст статьи 2\r\nТекст статьи 2, 2, 2\r\nvbn vbn','2022-04-19 10:49:44'),(5,2,'Ещё одна статья','Просто бестолковая статья.\r\n\r\nКонечно!!! ?','2022-04-20 08:51:49'),(12,7,'123','123\r\n123\r\n123\r\n456','2022-05-13 11:36:28'),(13,1,'Просто статья','Текст просто статьи...','2022-05-17 12:11:49');
/*!40000 ALTER TABLE `articles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nickname` varchar(128) NOT NULL,
  `email` varchar(255) NOT NULL,
  `is_confirmed` tinyint(1) NOT NULL DEFAULT 0,
  `role` enum('admin','user') NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `auth_token` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `nickname` (`nickname`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin','admin@gmail.com',1,'admin','hash1','token1','2022-04-11 11:57:28'),(2,'user','user@gmail.com',1,'user','hash2','token2','2022-04-11 11:57:51'),(3,'vvv','vvv@mail.ru',0,'user','$2y$10$2/8ijmURQDGHIJGoHkxe/.TK6oo2COi.3AxZjChk2tEEKaV.hua.S','86577f9d5b8eac4e24fe9232056e19115e1ca40db9d6da88dd80bb05133dddf553991094ca1d4312','2022-04-25 13:18:26'),(6,'444','444@mail.ru',1,'user','$2y$10$kvEXVUTtRG/.naTFvf4M7OQd2U7gwjhbHCejRFcd.X3B9UJFhPxsO','fa7a97cf3ff39cf72975f71077a5c60553622c5e0d5fcbb1e766d1bf4c535441fb792fede6a1acd1','2022-04-28 07:14:37'),(7,'555','555@mail.ru',1,'admin','$2y$10$aBKjapMbrDrfWYlYh1Fc8OumoIiSs81zf.ZBcwGO6bE5pkjoxmQbi','dc0ba02bfd7c1e31aad512b8a2ec23caac166ace4dad2578c44c0f87937a01080be7ed94366fd4f6','2022-04-28 12:19:31'),(8,'777','777@mail.ru',1,'user','$2y$10$uIWewDNILa.ii..TSMtfCutnToUgYa6iMRjsPt157gg/NChu.6Wf2','103737a56b79686f8820947c5aa1772b54e6ee4cd04d749b0a5b4ad649cc328691b51385697da09d','2022-04-29 14:13:12'),(9,'222','222@mail.ru',1,'user','$2y$10$vILPBARjO6/stNrOObv4zOQuYZtRcFp/Vfq54QD.TAXWH9N9J3RIe','862c8fa64ab48c66346e94cbf2d3b859f2d37c8df8ca3a1d3fdec82994b9ab1bc32eac6203ef1e50','2022-05-04 14:18:20'),(10,'778','778@mail.ru',1,'user','$2y$10$UrXpe2ra2AlAYQckNDZjpOEayKX.U2MdMLqqxF8gdD4szc28aGfsq','aa06bc79210efc9cc469a6e7691e65d05036d1865110ef60150eebe8644707f9966b371e1fc49b41','2022-05-11 13:17:38');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-09-02 14:39:49
