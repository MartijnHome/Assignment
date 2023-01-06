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
-- Table structure for table `avatar`
--

DROP TABLE IF EXISTS `avatar`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `avatar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `avatar`
--

LOCK TABLES `avatar` WRITE;
/*!40000 ALTER TABLE `avatar` DISABLE KEYS */;
INSERT INTO `avatar` VALUES
(1,'pasfoto-63b7ffe19488e.png'),
(2,'depositphotos-7661369-stock-illustration-chef-cartoon-63b803e980fc2.jpg');
/*!40000 ALTER TABLE `avatar` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blog`
--

DROP TABLE IF EXISTS `blog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `blog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `lead_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `subtitle` varchar(255) NOT NULL,
  `publish_date` datetime NOT NULL,
  `text` longtext NOT NULL,
  `archived` tinyint(1) NOT NULL,
  `style` int(11) NOT NULL,
  `gallery` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_C015514355458D` (`lead_id`),
  KEY `IDX_C0155143A76ED395` (`user_id`),
  CONSTRAINT `FK_C015514355458D` FOREIGN KEY (`lead_id`) REFERENCES `image` (`id`),
  CONSTRAINT `FK_C0155143A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blog`
--

LOCK TABLES `blog` WRITE;
/*!40000 ALTER TABLE `blog` DISABLE KEYS */;
INSERT INTO `blog` VALUES
(1,2,1,'Easy Beef Lasagne','A quick and easy beef lasagne that\'s extra cheesy and ready to eat in just 1 hour! And best of all, a time saver, as there\'s absolutely no need to make a bechamel sauce! The perfect midweek family dinner.','2023-01-06 12:23:36','What You Need\r\nThis easy beef lasagne is made using a handful of basic ingredients (many of which you will already have at home):\r\n~2~\r\n\r\nStep 1 - Sauté\r\n\r\nAdd the diced onion and minced garlic to a large frying pan.\r\na frying pan with diced onion and minced garlic\r\n\r\nSauté the onion and garlic until soft and translucent.\r\nlightly sautéed diced onion and garlic in a frying pan\r\n~3~\r\n\r\nStep 2 - Add The Beef\r\n\r\nAdd the beef mince, breaking up the clumps with a spoon.\r\nraw mince added to a frying pan with softened onion\r\n~4~\r\n\r\nCook until the mince is browned.\r\nmince browned in a frying pan with diced onion\r\n~5~\r\n\r\nStep 3 - Cook The Sauce\r\n\r\nAdd the tomato paste, tomatoes, beef stock, Italian herbs and salt and pepper.\r\nstock, canned tomatoes, herbs and seasoning added to browned mince in a frying pan\r\n~6~\r\n\r\nBring to a boil and then simmer (stirring occasionally) for 15 - 20 minutes.\r\nLasagne meat sauce cooked in a frying pan\r\n~7~\r\n\r\nStep 4 - Assemble\r\n\r\nAssemble the lasagne by adding a layer of sauce to the bottom of the prepared baking dish.\r\n~8~\r\n\r\nTop with fresh lasagne sheets and then shredded mozzarella cheese.\r\nan oval baking dish with beef mince filling layered with lasagne sheets\r\n\r\nRepeat the layers until you have used all of the sauce and lasagne sheets.\r\n\r\nFinish with a layer of mozzarella cheese and add a sprinkle of grated parmesan over the top.\r\ngrated cheese layered on top of a beef lasagne\r\n~9~\r\n\r\nStep 5 - Bake\r\n\r\nBake in a preheated oven for 30-40 minutes or until cooked through and the cheese has melted and turned golden on top.\r\n\r\nTip: If you notice the cheese over-browning too much, add a loose sheet of foil over the top or move to a lower shelf in the oven.\r\n~10~\r\n\r\nEnjoy!!!',0,2,0),
(2,3,11,'Sealife','The wonderful world of fish!','2023-01-06 12:49:08','Check out these beautiful fish!',0,0,1),
(3,1,18,'jTitan game engine','My Java OpenGL game engine project','2023-01-06 13:19:47','For almost 10 years I have been working on a game engine, based on LWJGL 3 and OpenGL, purely as hobby.\r\n~25~\r\nCurrently my engine supports reflections, multi texturing, physics, collision detection, multi lighting, OpenGL 4.6, multi-threading, GLSL shading and resource management.',0,2,1),
(4,1,27,'My painting hobby','A small collection of my paintings. Painting is a hobby of me.','2023-01-06 14:06:53','For over 10 years I have been painting. Mostly landscapes and sometimes abstract art. Inspired by Bob Ross who I watched on TV as a kid.\r\n~32~\r\nOccasionally I sell a painting or make paintings on demand.',0,1,1);
/*!40000 ALTER TABLE `blog` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blogtag`
--

DROP TABLE IF EXISTS `blogtag`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `blogtag` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_BF280C245E237E06` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blogtag`
--

LOCK TABLES `blogtag` WRITE;
/*!40000 ALTER TABLE `blogtag` DISABLE KEYS */;
INSERT INTO `blogtag` VALUES
(1,'COOKING'),
(3,'FISH'),
(6,'GAMES'),
(2,'LASAGNE'),
(5,'OPENGL'),
(8,'PAINTING'),
(4,'SEALIFE'),
(7,'TEST');
/*!40000 ALTER TABLE `blogtag` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blogtag_blog`
--

DROP TABLE IF EXISTS `blogtag_blog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `blogtag_blog` (
  `blogtag_id` int(11) NOT NULL,
  `blog_id` int(11) NOT NULL,
  PRIMARY KEY (`blogtag_id`,`blog_id`),
  KEY `IDX_B6A4B20BC005CC45` (`blogtag_id`),
  KEY `IDX_B6A4B20BDAE07E97` (`blog_id`),
  CONSTRAINT `FK_B6A4B20BC005CC45` FOREIGN KEY (`blogtag_id`) REFERENCES `blogtag` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_B6A4B20BDAE07E97` FOREIGN KEY (`blog_id`) REFERENCES `blog` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blogtag_blog`
--

LOCK TABLES `blogtag_blog` WRITE;
/*!40000 ALTER TABLE `blogtag_blog` DISABLE KEYS */;
INSERT INTO `blogtag_blog` VALUES
(1,1),
(2,1),
(3,2),
(4,2),
(5,3),
(6,3),
(8,4);
/*!40000 ALTER TABLE `blogtag_blog` ENABLE KEYS */;
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
  `date` datetime NOT NULL,
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
(1,3,1,'That lasagne tasted amazing! Thanks for the recipe.','2023-01-06 12:49:37'),
(2,1,2,'Mooie viskes!','2023-01-06 13:53:18'),
(3,1,1,'Goei spulleke','2023-01-06 14:07:55');
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
('DoctrineMigrations\\Version20230106110101','2023-01-06 11:01:06',651);
/*!40000 ALTER TABLE `doctrine_migration_versions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `image`
--

DROP TABLE IF EXISTS `image`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `image` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `blog_id` int(11) DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `filename` varchar(255) NOT NULL,
  `is_lead` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_C53D045FDAE07E97` (`blog_id`),
  CONSTRAINT `FK_C53D045FDAE07E97` FOREIGN KEY (`blog_id`) REFERENCES `blog` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `image`
--

LOCK TABLES `image` WRITE;
/*!40000 ALTER TABLE `image` DISABLE KEYS */;
INSERT INTO `image` VALUES
(1,1,NULL,'bop-pd-header-lasagne-bolognese-63b804b851036.jpg',1),
(2,1,'1 tbs olive oil\n▢ 1 onion finely diced\n▢ 1 clove garlic crushed or 1 tsp minced garlic\n▢ 500 g beef mince\n▢ 65 g (5 tbsp) tomato paste\n▢ 800 g canned tomatoes\n▢ 500 g (2 cups) liquid beef stock\n▢ 2 tsp dried Italian herbs oregano, parsley\n▢ salt and pepper to taste\n▢ 250 g mozzarella cheese shredded\n▢ 50 g parmesan cheese grated\n▢ 375 g packet fresh lasagne sheets','Beef-Lasagne-Process-63b805e67962b.jpg',0),
(3,1,NULL,'Beef-Lasagne-Process-3-63b805e6798db.jpg',0),
(4,1,NULL,'Beef-Lasagne-Process-4-63b805e679abf.jpg',0),
(5,1,NULL,'Beef-Lasagne-Process-5-63b805e679d0a.jpg',0),
(6,1,NULL,'Beef-Lasagne-Process-6-63b805e679f81.jpg',0),
(7,1,NULL,'Beef-Lasagne-Process-7-63b805e67a1c1.jpg',0),
(8,1,NULL,'Beef-Lasagne-Process-8-63b805e67a398.jpg',0),
(9,1,NULL,'Beef-Lasagne-Process-9-63b805e67a4d1.jpg',0),
(10,1,NULL,'Beef-Lasagne-5-63b80688b1ea8.jpg',0),
(11,2,NULL,'aquarium-banner-63b80ab435c62.jpg',1),
(12,2,NULL,'1-63b80ab435e54.jpg',0),
(13,2,NULL,'2-63b80ab435f3a.jpg',0),
(14,2,NULL,'3-63b80ab43600e.jpg',0),
(15,2,NULL,'4-63b80ab4360e6.jpg',0),
(16,2,NULL,'5-63b80ab43627a.jpg',0),
(17,2,NULL,'6-63b80ab436372.jpg',0),
(18,3,NULL,'banner-63b811e31d81f.jpg',1),
(19,3,NULL,'2-63b811e31db7d.jpg',0),
(20,3,NULL,'4-63b811e31dcea.jpg',0),
(21,3,NULL,'3-63b811e31de76.jpg',0),
(22,3,NULL,'12-63b811e31dffe.jpg',0),
(23,3,NULL,'0-63b811e31e19b.jpg',0),
(24,3,NULL,'1-63b811e31e38a.jpg',0),
(25,3,'The first \"hello world\" scene my engine rendered. Great graphics! :)','11-63b811e31e580.jpg',0),
(26,3,NULL,'10-63b811e31e779.jpg',0),
(27,4,NULL,'banner-63b81ceddf95b.jpg',1),
(28,4,NULL,'1-63b81ceddfa7b.jpg',0),
(29,4,NULL,'3-63b81ceddfc82.jpg',0),
(30,4,NULL,'2-63b81ceddfe65.jpg',0),
(31,4,NULL,'5-63b81cede002c.jpg',0),
(32,4,'One of my best works','6-63b81cede0210.jpg',0),
(33,4,NULL,'7-63b81cede04b0.jpg',0),
(34,4,NULL,'4-63b81cede0846.jpg',0);
/*!40000 ALTER TABLE `image` ENABLE KEYS */;
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
  `avatar_id` int(11) DEFAULT NULL,
  `email` varchar(180) NOT NULL,
  `roles` longtext NOT NULL COMMENT '(DC2Type:json)',
  `password` varchar(255) NOT NULL,
  `is_verified` tinyint(1) NOT NULL,
  `name` varchar(255) NOT NULL,
  `biography` longtext DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`),
  UNIQUE KEY `UNIQ_8D93D64986383B10` (`avatar_id`),
  CONSTRAINT `FK_8D93D64986383B10` FOREIGN KEY (`avatar_id`) REFERENCES `avatar` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES
(1,1,'a@a.nl','[]','$2y$13$NjaXI5ns1uwxZUqM4qanFuzJRBMzTJ79ZooBbUaYtPDprxCsBL8nu',1,'Martijn van Sliedregt','Hi, I’m Martijn! I\'m a developer with experience in frontend and backend programming. In a nutshell, I create websites that help organizations address business challenges and meet their needs. My expertise lies within full stack development, and the main languages in my tech stack are PHP, JavaScript, Vue.JS and of course HTML/CSS. I’m a lifelong learner (Technology never stands still!)  and love to paint, play chess and work on my Java Game Engine hobby project!'),
(2,2,'b@b.nl','[]','$2y$13$.YsluPLwccu6a4NxWrvBy.BAqizwzNy7GeEy3E39illiE5iwY/AVC',1,'Japie','Japie is the best chef of the world!'),
(3,NULL,'c@c.nl','[]','$2y$13$N31uENlXVXw9BCD2cA3poeQaQ6r/vXxFGUU.cCleue5a9vBI./o8y',1,'Foobar','Hi all!');
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

-- Dump completed on 2023-01-06 16:09:54
