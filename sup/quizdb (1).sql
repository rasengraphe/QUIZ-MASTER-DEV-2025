-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : dim. 04 mai 2025 à 17:45
-- Version du serveur : 8.3.0
-- Version de PHP : 8.1.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `quizdb`
--

-- --------------------------------------------------------

--
-- Structure de la table `quiz_admin_editor`
--

DROP TABLE IF EXISTS `quiz_admin_editor`;
CREATE TABLE IF NOT EXISTS `quiz_admin_editor` (
  `Id_admin_editor` int NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL,
  `first_name` varchar(250) NOT NULL,
  `email` varchar(250) DEFAULT NULL,
  `password` varchar(250) NOT NULL,
  `role` varchar(50) NOT NULL DEFAULT 'editor',
  `date_creation` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Id_admin_editor`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `quiz_admin_editor`
--

INSERT INTO `quiz_admin_editor` (`Id_admin_editor`, `name`, `first_name`, `email`, `password`, `role`, `date_creation`) VALUES
(3, 'Marchal', 'Sébastien', 'rasengraphe@gmail.com', '$2y$10$73OGo0NL6EYT.96eB6pwTuiKUszawOWREYCUHnwYzr4ryiQxpo146', 'super_admin', '2025-04-13 19:21:08');

-- --------------------------------------------------------

--
-- Structure de la table `quiz_game_history`
--

DROP TABLE IF EXISTS `quiz_game_history`;
CREATE TABLE IF NOT EXISTS `quiz_game_history` (
  `id` int NOT NULL AUTO_INCREMENT,
  `player_id` int NOT NULL,
  `quiz_id` int NOT NULL,
  `score` int NOT NULL DEFAULT '0',
  `completed` tinyint(1) NOT NULL DEFAULT '0',
  `played_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `player_id` (`player_id`),
  KEY `quiz_id` (`quiz_id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `quiz_game_history`
--

INSERT INTO `quiz_game_history` (`id`, `player_id`, `quiz_id`, `score`, `completed`, `played_on`) VALUES
(1, 3, 57, 0, 1, '2025-05-04 10:58:30'),
(2, 3, 57, 0, 1, '2025-05-04 10:58:58'),
(3, 10, 1, 1, 1, '2025-05-04 12:45:56'),
(4, 10, 1, 1, 1, '2025-05-04 12:46:05'),
(5, 10, 1, 1, 1, '2025-05-04 13:00:34'),
(6, 10, 1, 1, 1, '2025-05-04 13:00:48'),
(7, 10, 1, 3, 1, '2025-05-04 15:22:00'),
(8, 10, 1, 2, 1, '2025-05-04 15:22:52');

-- --------------------------------------------------------

--
-- Structure de la table `quiz_player`
--

DROP TABLE IF EXISTS `quiz_player`;
CREATE TABLE IF NOT EXISTS `quiz_player` (
  `Id_player` int NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL,
  `first_name` varchar(250) NOT NULL,
  `email` varchar(250) DEFAULT NULL,
  `password` varchar(250) NOT NULL,
  `score` int DEFAULT NULL,
  `Id_Avatar` int NOT NULL,
  `Id_player_class` int NOT NULL,
  `date_creation` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Id_player`),
  KEY `Id_Avatar` (`Id_Avatar`),
  KEY `Id_player_class` (`Id_player_class`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `quiz_player`
--

INSERT INTO `quiz_player` (`Id_player`, `name`, `first_name`, `email`, `password`, `score`, `Id_Avatar`, `Id_player_class`, `date_creation`) VALUES
(1, 'Doe', 'John', 'john.doe@example.com', '482c811da5d5b4bc6d497ffa98491e38', 0, 1, 1, '2025-04-13 15:01:57'),
(2, 'Smith', 'Jane', 'jane.smith@example.com', '482c811da5d5b4bc6d497ffa98491e38', 0, 2, 2, '2025-04-13 15:01:57'),
(3, 'Brown', 'Charlie', 'charlie.brown@example.com', '482c811da5d5b4bc6d497ffa98491e38', 0, 3, 3, '2025-04-13 15:01:57'),
(4, 'k', 'k', 'k@gmail.com', '$2y$10$kzPpzrdmS7WiHtC8cf4s.OQpvcUQAXbbMLWjhPL49osaJpKZvhJk.', NULL, 1, 0, '2025-05-02 01:03:36'),
(7, 'm', 's', 's@gmail.com', '$2y$10$Rkm6n8UP5qhNoZszbnjV6eT5ALQcuXZvMBON87vKC.509q24baENq', 0, 1, 0, '2025-05-02 10:00:11'),
(8, 'm', 'm', 'm@gmail.com', '$2y$10$40.umbVcWfQ/s4wQ0UjrEO2tcgUzPXD7bPRTALUXf64M2CNlgGOI2', 0, 1, 0, '2025-05-02 10:19:02'),
(9, 'Marchal', 'Sébastien', 's@gmail.com', '$2y$10$N5CuzPZ6Lmcuu96SmEqcp.RrLrfnej03sNjWAtv8yfzLVNXtpJk.6', NULL, 1, 0, '2025-05-02 10:38:59'),
(10, 'KK', 'i', 'i@gmail.com', '$2y$10$ZHpnR8a34tteaT3RRijg6u0qzABRLqiPyxwiRiVWWOGBg2.WGlUq.', 0, 3, 0, '2025-05-02 10:40:22');

-- --------------------------------------------------------

--
-- Structure de la table `quiz_player_avatar`
--

DROP TABLE IF EXISTS `quiz_player_avatar`;
CREATE TABLE IF NOT EXISTS `quiz_player_avatar` (
  `Id_Avatar` int NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`Id_Avatar`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `quiz_player_avatar`
--

INSERT INTO `quiz_player_avatar` (`Id_Avatar`) VALUES
(1),
(2),
(3),
(4),
(5);

-- --------------------------------------------------------

--
-- Structure de la table `quiz_player_class`
--

DROP TABLE IF EXISTS `quiz_player_class`;
CREATE TABLE IF NOT EXISTS `quiz_player_class` (
  `Id_player_class` int NOT NULL AUTO_INCREMENT,
  `class_name` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`Id_player_class`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `quiz_player_class`
--

INSERT INTO `quiz_player_class` (`Id_player_class`, `class_name`) VALUES
(1, 'Débutant'),
(2, 'Pro'),
(3, 'Expert');

-- --------------------------------------------------------

--
-- Structure de la table `quiz_player_game`
--

DROP TABLE IF EXISTS `quiz_player_game`;
CREATE TABLE IF NOT EXISTS `quiz_player_game` (
  `Id_player_game` int NOT NULL AUTO_INCREMENT,
  `progression` varchar(50) DEFAULT NULL,
  `result_player_game` int DEFAULT NULL,
  `date_game` datetime DEFAULT CURRENT_TIMESTAMP,
  `Id_question` int NOT NULL,
  `Id_player` int NOT NULL,
  PRIMARY KEY (`Id_player_game`),
  KEY `Id_question` (`Id_question`),
  KEY `Id_player` (`Id_player`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `quiz_player_game`
--

INSERT INTO `quiz_player_game` (`Id_player_game`, `progression`, `result_player_game`, `date_game`, `Id_question`, `Id_player`) VALUES
(1, 'En cours', NULL, '2025-04-13 15:01:58', 1, 1),
(2, 'Terminé', 10, '2025-04-13 15:01:58', 2, 2),
(3, 'Terminé', 8, '2025-04-13 15:01:58', 3, 3),
(4, 'Terminé', 0, '2025-04-20 18:46:16', 0, 0),
(5, 'Terminé', 1, '2025-04-23 19:51:29', 0, 0),
(6, 'Terminé', NULL, '2025-04-24 21:14:27', 0, 0);

-- --------------------------------------------------------

--
-- Structure de la table `quiz_question`
--

DROP TABLE IF EXISTS `quiz_question`;
CREATE TABLE IF NOT EXISTS `quiz_question` (
  `Id_question` int NOT NULL AUTO_INCREMENT,
  `Id_question_category` int NOT NULL,
  `Id_admin_editor` int NOT NULL,
  `Id_question_difficulte` int NOT NULL,
  `text` varchar(255) NOT NULL,
  `picture` varchar(255) DEFAULT NULL,
  `date_creation` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Id_question`),
  KEY `Id_question_category` (`Id_question_category`),
  KEY `Id_admin_editor` (`Id_admin_editor`),
  KEY `Id_question_difficulte` (`Id_question_difficulte`)
) ENGINE=MyISAM AUTO_INCREMENT=86 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `quiz_question`
--

INSERT INTO `quiz_question` (`Id_question`, `Id_question_category`, `Id_admin_editor`, `Id_question_difficulte`, `text`, `picture`, `date_creation`) VALUES
(82, 2, 3, 1, 'question2', NULL, '2025-05-04 16:48:50'),
(84, 2, 3, 1, 'la france est elle belle ?', '', '2025-05-04 17:06:05'),
(85, 1, 1, 1, 'lena est belle', NULL, '2025-05-04 18:28:56'),
(81, 2, 3, 1, 'question1', 'https://cdn.pixabay.com/photo/2018/08/04/11/30/draw-3583548_1280.png', '2025-05-04 16:46:12');

-- --------------------------------------------------------

--
-- Structure de la table `quiz_question_answer`
--

DROP TABLE IF EXISTS `quiz_question_answer`;
CREATE TABLE IF NOT EXISTS `quiz_question_answer` (
  `Id_question_answer` int NOT NULL AUTO_INCREMENT,
  `text` varchar(250) DEFAULT NULL,
  `correct` int NOT NULL,
  `Id_question` int NOT NULL,
  PRIMARY KEY (`Id_question_answer`),
  KEY `Id_question` (`Id_question`)
) ENGINE=MyISAM AUTO_INCREMENT=224 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `quiz_question_answer`
--

INSERT INTO `quiz_question_answer` (`Id_question_answer`, `text`, `correct`, `Id_question`) VALUES
(218, '2', 0, 85),
(219, '3', 0, 85),
(220, 'Réponse 1 ', 1, 84),
(221, 'Réponse 2 ', 0, 84),
(222, 'Réponse 3', 0, 84),
(223, 'Test de réponse insérée', 1, 84),
(208, '10000000', 1, 81),
(209, '2', 0, 81),
(210, '3', 0, 81),
(211, '1', 1, 82),
(212, '2', 0, 82),
(213, '3', 0, 82),
(214, '1', 1, 83),
(215, '2', 0, 83),
(216, '3', 0, 83),
(217, '1', 1, 85);

-- --------------------------------------------------------

--
-- Structure de la table `quiz_question_category`
--

DROP TABLE IF EXISTS `quiz_question_category`;
CREATE TABLE IF NOT EXISTS `quiz_question_category` (
  `Id_question_category` int NOT NULL AUTO_INCREMENT,
  `date_creation` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Id_question_category`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `quiz_question_category`
--

INSERT INTO `quiz_question_category` (`Id_question_category`, `date_creation`) VALUES
(1, '2025-04-13 15:01:57'),
(2, '2025-04-13 15:01:57'),
(3, '2025-04-13 15:01:57');

-- --------------------------------------------------------

--
-- Structure de la table `quiz_question_category_details`
--

DROP TABLE IF EXISTS `quiz_question_category_details`;
CREATE TABLE IF NOT EXISTS `quiz_question_category_details` (
  `Id_question_category` int NOT NULL,
  `picture` varchar(250) DEFAULT NULL,
  `active` varchar(50) DEFAULT NULL,
  `title` varchar(250) DEFAULT NULL,
  `text` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`Id_question_category`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `quiz_question_category_details`
--

INSERT INTO `quiz_question_category_details` (`Id_question_category`, `picture`, `active`, `title`, `text`) VALUES
(1, 'category1.jpg', 'yes', 'HTML Basics', 'Questions sur les bases du HTML'),
(2, 'category2.jpg', 'yes', 'CSS Basics', 'Questions sur les bases du CSS'),
(3, 'category3.jpg', 'yes', 'JavaScript Basics', 'Questions sur les bases du JavaScript');

-- --------------------------------------------------------

--
-- Structure de la table `quiz_question_difficulte`
--

DROP TABLE IF EXISTS `quiz_question_difficulte`;
CREATE TABLE IF NOT EXISTS `quiz_question_difficulte` (
  `Id_question_difficulte` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`Id_question_difficulte`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `quiz_question_difficulte`
--

INSERT INTO `quiz_question_difficulte` (`Id_question_difficulte`, `name`) VALUES
(1, 'Facile'),
(2, 'Moyen'),
(3, 'Difficile');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
