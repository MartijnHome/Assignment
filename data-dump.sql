-- MariaDB dump 10.19  Distrib 10.9.4-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: blogsite
-- ------------------------------------------------------
-- Server version	10.9.4-MariaDB

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
-- Table structure for table `blog`
--

DROP TABLE IF EXISTS `blog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `blog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `subtitle` varchar(255) NOT NULL,
  `publish_date` datetime NOT NULL,
  `text` longtext NOT NULL,
  `main_image` longtext NOT NULL,
  `additional_images` longtext DEFAULT NULL,
  `archived` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_C0155143A76ED395` (`user_id`),
  CONSTRAINT `FK_C0155143A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blog`
--

LOCK TABLES `blog` WRITE;
/*!40000 ALTER TABLE `blog` DISABLE KEYS */;
INSERT INTO `blog` VALUES
(1,1,'Test 1','subtitle','2022-12-11 13:26:52','text','Banner-forestpack-40681641-1920-700-6395cc8c109c8.jpg',NULL,0),
(2,1,'Test 2','test','2022-12-11 13:27:28','test','Banner-forestpack-40681641-1920-700-6395ccb0df339.jpg',NULL,0),
(3,1,'Test 3','test','2022-12-11 13:27:42','test','Banner-forestpack-40681641-1920-700-6395ccbe424b1.jpg',NULL,0),
(4,1,'Test 4','test','2022-12-11 13:27:51','test','Banner-forestpack-40681641-1920-700-6395ccc7045ef.jpg',NULL,0),
(5,1,'Test 5','test','2022-12-11 13:28:20','test','Banner-forestpack-40681641-1920-700-6395cce4ef1a9.jpg',NULL,0),
(6,2,'Test 6','test','2022-12-11 13:29:22','test','Banner-forestpack-40681641-1920-700-6395cd22ad38f.jpg',NULL,0),
(7,2,'Test 7','test','2022-12-11 13:29:31','test','Banner-forestpack-40681641-1920-700-6395cd2bb9a3a.jpg',NULL,0),
(8,2,'Test 8','test','2022-12-11 13:33:04','test','Banner-forestpack-40681641-1920-700-6395ce00a6b66.jpg',NULL,0),
(9,3,'De aquarium blog','Vol met mooie vissen','2022-12-11 13:35:04','Klik hieronder op de fotos!','aquarium-banner-6395ce78ccfde.jpg','1-6395ce78cd188.jpg;2-6395ce78cd23f.jpg;3-6395ce78cd2e9.jpg;4-6395ce78cd39b.jpg;5-6395ce78cd440.jpg;6-6395ce78cd4e4.jpg',0),
(10,3,'Mijn schilder hobby','Schilderen is al jaren een van mijn hobbies','2022-12-11 13:42:02','Hieronder staan een paar voorbeelden','banner-6395d01a8dc9a.jpg','1-6395d01a8ddeb.jpg;2-6395d01a8ded1.jpg;3-6395d01a8dfee.jpg;4-6395d01a8e132.jpg;5-6395d01a8e402.jpg;6-6395d01a8e53e.jpg;7-6395d01a8e6af.jpg;8-6395d01a8e883.jpg',0),
(11,3,'jTitan game engine','Mijn hobby project','2022-12-11 13:54:32','Al jaren werk ik aan jTitan. Een hobby OpenGL game engine gemaakt met LWJGL 3 en Java. De laatste twee foto\'s zijn van vroeger.','banner-6395d30855642.jpg','0-6395d3c683115.jpg;1-6395d3c68340a.jpg;2-6395d3c683654.jpg;3-6395d3c68382c.jpg;4-6395d3c6839fe.jpg;10-6395d3c683bd7.jpg;11-6395d3c683f27.jpg;12-6395d3c6840ee.jpg',0);
/*!40000 ALTER TABLE `blog` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `commentary`
--

DROP TABLE IF EXISTS `commentary`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `commentary` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `blog_id` int(11) NOT NULL,
  `text` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_1CAC12CAA76ED395` (`user_id`),
  KEY `IDX_1CAC12CADAE07E97` (`blog_id`),
  CONSTRAINT `FK_1CAC12CAA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_1CAC12CADAE07E97` FOREIGN KEY (`blog_id`) REFERENCES `blog` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `commentary`
--

LOCK TABLES `commentary` WRITE;
/*!40000 ALTER TABLE `commentary` DISABLE KEYS */;
INSERT INTO `commentary` VALUES
(1,2,6,'Test comment 1'),
(2,2,6,'Test comment 2'),
(3,2,3,'Mooi');
/*!40000 ALTER TABLE `commentary` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `doctrine_migration_versions`
--

DROP TABLE IF EXISTS `doctrine_migration_versions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doctrine_migration_versions`
--

LOCK TABLES `doctrine_migration_versions` WRITE;
/*!40000 ALTER TABLE `doctrine_migration_versions` DISABLE KEYS */;
INSERT INTO `doctrine_migration_versions` VALUES
('DoctrineMigrations\\Version20221206121229','2022-12-11 11:29:31',118),
('DoctrineMigrations\\Version20221209195045','2022-12-11 11:29:32',7);
/*!40000 ALTER TABLE `doctrine_migration_versions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messenger_messages`
--

DROP TABLE IF EXISTS `messenger_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `messenger_messages` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `body` longtext NOT NULL,
  `headers` longtext NOT NULL,
  `queue_name` varchar(190) NOT NULL,
  `created_at` datetime NOT NULL,
  `available_at` datetime NOT NULL,
  `delivered_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  KEY `IDX_75EA56E016BA31DB` (`delivered_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messenger_messages`
--

LOCK TABLES `messenger_messages` WRITE;
/*!40000 ALTER TABLE `messenger_messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `messenger_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(180) NOT NULL,
  `roles` longtext NOT NULL COMMENT '(DC2Type:json)',
  `password` varchar(255) NOT NULL,
  `is_verified` tinyint(1) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES
(1,'a@a.nl','[]','$2y$13$Q2n808r6Nd5qG7gnF3FPce6PzvwE80FYLpELbj0Py1YnCgyQaV9Hu',1,'Test User A'),
(2,'b@b.nl','[]','$2y$13$buf96wkZA14aYj99lOXsceYHSNlJ2Id2yke5mJoAEGe/FscCAD7Qu',1,'Test User B'),
(3,'m@test.nl','[]','$2y$13$z.GCj4FcwoZqnTVH0WOYte4X.PoWJNuM5UNEAB7I/.4hKbduJof4O',1,'Martijn van Sliedregt');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-12-11 14:24:55
