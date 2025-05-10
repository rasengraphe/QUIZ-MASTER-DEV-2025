-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : sam. 26 avr. 2025 à 13:38
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
-- Base de données : `quiz`
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
(1, 'marc', 'deux', 'admin@example.com', '0192023a7bbd73250516f069df18b500', 'super_joueur', '2025-04-13 15:01:57'),
(2, 'Editor', 'John', 'editor@example.com', '50116a1a3b67657572a00ea8c6680cb9', 'joueur', '2025-04-13 15:01:57'),
(3, 'Marchal', 'Sébastien', 'rasengraphe@gmail.com', '$2y$10$73OGo0NL6EYT.96eB6pwTuiKUszawOWREYCUHnwYzr4ryiQxpo146', 'super_admin', '2025-04-13 19:21:08');

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
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `quiz_player`
--

INSERT INTO `quiz_player` (`Id_player`, `name`, `first_name`, `email`, `password`, `score`, `Id_Avatar`, `Id_player_class`, `date_creation`) VALUES
(1, 'Doe', 'John', 'john.doe@example.com', '482c811da5d5b4bc6d497ffa98491e38', 0, 1, 1, '2025-04-13 15:01:57'),
(2, 'Smith', 'Jane', 'jane.smith@example.com', '482c811da5d5b4bc6d497ffa98491e38', 0, 2, 2, '2025-04-13 15:01:57'),
(3, 'Brown', 'Charlie', 'charlie.brown@example.com', '482c811da5d5b4bc6d497ffa98491e38', 0, 3, 3, '2025-04-13 15:01:57');

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
(3, 'Expert'),
(4, 'Débutant'),
(5, 'Pro'),
(6, 'Expert');

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
) ENGINE=MyISAM AUTO_INCREMENT=62 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `quiz_question`
--

INSERT INTO `quiz_question` (`Id_question`, `Id_question_category`, `Id_admin_editor`, `Id_question_difficulte`, `text`, `picture`, `date_creation`) VALUES
(57, 1, 1, 1, 'Quel est le bon moyen de déclarer une variable en PHP ?', NULL, '2025-04-21 18:56:21'),
(61, 1, 1, 1, 'Quel est le bon moyen de déclarer une variable en PHP ?', NULL, '2025-04-22 23:42:31');

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
) ENGINE=MyISAM AUTO_INCREMENT=157 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `quiz_question_answer`
--

INSERT INTO `quiz_question_answer` (`Id_question_answer`, `text`, `correct`, `Id_question`) VALUES
(123, 'include_file(\'filename.php\');', 0, 59),
(122, 'include_file(\'filename.php\');', 0, 59),
(121, 'include_file(\'filename.php\');', 1, 59),
(120, 'var variableName;', 0, 58),
(119, 'var variableName;', 0, 58),
(118, 'var variableName;', 1, 58),
(145, 'lena20132013', 1, 57),
(146, 'var variableName;', 0, 57),
(147, 'if (condition) { }', 0, 57),
(148, 'if (condition) { }', 1, 60),
(149, 'if (condition) { }', 0, 60),
(150, 'if (condition) { }', 0, 60),
(154, 'k', 1, 61),
(155, 'var variableName;', 0, 61),
(156, 'var variableName;', 0, 61),
(108, 'var variableName;', 0, 54),
(109, 'var variableName;', 1, 55),
(110, 'var variableName;', 0, 55),
(111, 'if (condition) { }', 0, 55),
(112, 'if (condition) { }', 1, 56),
(113, 'var variableName;', 0, 56),
(114, 'var variableName;', 0, 56);

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
-- Structure de la table `quiz_question_details`
--

DROP TABLE IF EXISTS `quiz_question_details`;
CREATE TABLE IF NOT EXISTS `quiz_question_details` (
  `Id_question` int NOT NULL,
  `picture` varchar(250) DEFAULT NULL,
  `active` varchar(50) DEFAULT NULL,
  `title` varchar(250) DEFAULT NULL,
  `text` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`Id_question`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `quiz_question_details`
--

INSERT INTO `quiz_question_details` (`Id_question`, `picture`, `active`, `title`, `text`) VALUES
(1, 'question1.jpg', 'yes', 'Qu\'est-ce qu\'une balise HTML ?', 'Choisissez la bonne réponse.'),
(2, 'question2.jpg', 'yes', 'Qu\'est-ce qu\'un sélecteur CSS ?', 'Choisissez la bonne réponse.'),
(3, 'question3.jpg', 'yes', 'Qu\'est-ce qu\'une fonction JavaScript ?', 'Choisissez la bonne réponse.'),
(21, 'question1.jpg', 'yes', 'Quelle commande est utilisée pour sélectionner toutes les colonnes d\'une table ?', 'Choisissez la bonne réponse.'),
(22, 'question2.jpg', 'yes', 'Comment insérer une nouvelle ligne dans une table ?', 'Choisissez la bonne réponse.'),
(23, 'question3.jpg', 'yes', 'Quelle commande est utilisée pour supprimer une table ?', 'Choisissez la bonne réponse.'),
(24, 'question4.jpg', 'yes', 'Comment mettre à jour une colonne dans une table ?', 'Choisissez la bonne réponse.'),
(25, 'question5.jpg', 'yes', 'Quelle commande est utilisée pour supprimer toutes les lignes d\'une table sans supprimer la table elle-même ?', 'Choisissez la bonne réponse.'),
(26, 'question6.jpg', 'yes', 'Comment ajouter une nouvelle colonne à une table existante ?', 'Choisissez la bonne réponse.'),
(27, 'question7.jpg', 'yes', 'Quelle commande est utilisée pour créer une nouvelle base de données ?', 'Choisissez la bonne réponse.'),
(28, 'question8.jpg', 'yes', 'Comment récupérer uniquement les lignes uniques d\'une table ?', 'Choisissez la bonne réponse.'),
(29, 'question9.jpg', 'yes', 'Quelle commande est utilisée pour trier les résultats d\'une requête ?', 'Choisissez la bonne réponse.'),
(30, 'question10.jpg', 'yes', 'Comment limiter le nombre de résultats retournés par une requête ?', 'Choisissez la bonne réponse.'),
(31, 'question11.jpg', 'yes', 'Comment renommer une table existante ?', 'Choisissez la bonne réponse.'),
(32, 'question12.jpg', 'yes', 'Quelle commande est utilisée pour compter le nombre de lignes dans une table ?', 'Choisissez la bonne réponse.'),
(33, 'question13.jpg', 'yes', 'Comment ajouter une contrainte de clé étrangère à une table ?', 'Choisissez la bonne réponse.'),
(34, 'question14.jpg', 'yes', 'Comment supprimer une colonne d\'une table ?', 'Choisissez la bonne réponse.'),
(35, 'question15.jpg', 'yes', 'Quelle commande est utilisée pour afficher la structure d\'une table ?', 'Choisissez la bonne réponse.'),
(36, 'question16.jpg', 'yes', 'Comment ajouter une contrainte UNIQUE à une colonne ?', 'Choisissez la bonne réponse.'),
(37, 'question17.jpg', 'yes', 'Comment sélectionner les lignes où une colonne est NULL ?', 'Choisissez la bonne réponse.'),
(38, 'question18.jpg', 'yes', 'Comment supprimer une contrainte de clé étrangère ?', 'Choisissez la bonne réponse.'),
(39, 'question19.jpg', 'yes', 'Comment regrouper les résultats d\'une requête ?', 'Choisissez la bonne réponse.'),
(40, 'question20.jpg', 'yes', 'Comment afficher les bases de données disponibles ?', 'Choisissez la bonne réponse.');

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
(3, 'Difficile'),
(4, 'Facile'),
(5, 'Moyen'),
(6, 'Difficile');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
