-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mar. 06 mai 2025 à 12:55
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
-- Structure de la table `quiz_avatar`
--

DROP TABLE IF EXISTS `quiz_avatar`;
CREATE TABLE IF NOT EXISTS `quiz_avatar` (
  `Id_Avatar` int NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`Id_Avatar`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `quiz_avatar`
--

INSERT INTO `quiz_avatar` (`Id_Avatar`) VALUES
(1),
(2),
(3),
(4),
(5);

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
) ENGINE=MyISAM AUTO_INCREMENT=79 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `quiz_game_history`
--

INSERT INTO `quiz_game_history` (`id`, `player_id`, `quiz_id`, `score`, `completed`, `played_on`) VALUES
(1, 3, 87, 1, 1, '2025-05-04 10:58:30'),
(2, 3, 87, 5, 1, '2025-05-04 10:58:58'),
(3, 10, 86, 1, 1, '2025-05-04 12:45:56'),
(4, 10, 86, 0, 1, '2025-05-04 12:46:05'),
(5, 10, 86, 3, 1, '2025-05-04 13:00:34'),
(6, 10, 86, 0, 1, '2025-05-04 13:00:48'),
(7, 10, 86, 0, 1, '2025-05-04 15:22:00'),
(8, 10, 86, 4, 1, '2025-05-04 15:22:52'),
(9, 10, 86, 3, 1, '2025-05-04 19:47:01'),
(10, 10, 86, 2, 1, '2025-05-04 19:47:27'),
(11, 10, 86, 2, 1, '2025-05-04 19:48:37'),
(12, 10, 86, 3, 1, '2025-05-04 19:50:10'),
(13, 10, 86, 4, 1, '2025-05-04 19:53:06'),
(14, 10, 86, 1, 1, '2025-05-04 19:54:38'),
(15, 10, 86, 4, 1, '2025-05-04 19:56:34'),
(16, 10, 86, 0, 1, '2025-05-04 20:00:26'),
(17, 10, 86, 1, 1, '2025-05-04 20:07:56'),
(18, 10, 86, 5, 1, '2025-05-04 20:08:02'),
(19, 10, 86, 3, 1, '2025-05-04 20:45:46'),
(20, 10, 86, 2, 1, '2025-05-04 20:47:55'),
(21, 10, 86, 1, 1, '2025-05-04 20:51:18'),
(22, 10, 86, 0, 1, '2025-05-04 20:53:21'),
(23, 10, 86, 3, 1, '2025-05-04 20:53:26'),
(24, 10, 86, 4, 1, '2025-05-04 20:53:32'),
(25, 10, 86, 0, 1, '2025-05-04 20:53:36'),
(26, 10, 86, 4, 1, '2025-05-04 20:54:00'),
(27, 10, 86, 4, 1, '2025-05-04 20:54:36'),
(28, 10, 86, 2, 1, '2025-05-04 21:03:27'),
(29, 10, 86, 4, 1, '2025-05-04 21:07:53'),
(30, 10, 86, 1, 1, '2025-05-04 21:11:17'),
(31, 10, 86, 2, 1, '2025-05-04 21:12:58'),
(32, 10, 86, 2, 1, '2025-05-04 21:13:14'),
(33, 10, 86, 3, 1, '2025-05-04 21:16:30'),
(34, 10, 86, 0, 1, '2025-05-04 21:17:39'),
(35, 10, 86, 0, 1, '2025-05-04 21:17:55'),
(36, 10, 86, 4, 1, '2025-05-04 21:19:44'),
(37, 10, 86, 1, 1, '2025-05-04 21:20:01'),
(38, 10, 86, 0, 1, '2025-05-04 21:27:42'),
(39, 10, 86, 2, 1, '2025-05-04 21:34:14'),
(40, 10, 86, 0, 1, '2025-05-04 21:34:30'),
(41, 10, 86, 1, 1, '2025-05-04 21:34:57'),
(42, 10, 86, 4, 1, '2025-05-04 21:35:18'),
(43, 10, 86, 0, 1, '2025-05-04 21:38:24'),
(44, 10, 86, 2, 1, '2025-05-04 21:38:59'),
(45, 10, 86, 3, 1, '2025-05-04 21:41:31'),
(46, 10, 86, 4, 1, '2025-05-04 21:41:46'),
(47, 10, 86, 4, 1, '2025-05-04 21:42:12'),
(48, 10, 86, 5, 1, '2025-05-04 21:42:18'),
(49, 10, 86, 1, 1, '2025-05-04 21:42:51'),
(50, 10, 86, 3, 1, '2025-05-04 21:42:54'),
(51, 10, 86, 4, 1, '2025-05-04 21:43:11'),
(52, 10, 86, 2, 1, '2025-05-04 21:45:10'),
(53, 10, 86, 4, 1, '2025-05-04 21:45:31'),
(54, 10, 86, 0, 1, '2025-05-04 21:49:40'),
(55, 10, 86, 3, 1, '2025-05-04 21:49:59'),
(56, 10, 86, 4, 1, '2025-05-04 21:53:29'),
(57, 10, 86, 0, 1, '2025-05-04 21:53:44'),
(58, 10, 86, 4, 1, '2025-05-04 22:00:32'),
(59, 10, 86, 0, 1, '2025-05-04 22:00:37'),
(60, 10, 86, 4, 1, '2025-05-04 22:01:53'),
(61, 10, 86, 1, 1, '2025-05-04 22:02:52'),
(62, 10, 86, 2, 1, '2025-05-04 22:03:54'),
(63, 10, 86, 0, 1, '2025-05-04 22:04:49'),
(64, 10, 86, 2, 1, '2025-05-04 22:09:04'),
(65, 10, 88, 3, 1, '2025-05-06 09:00:56'),
(66, 10, 88, 2, 1, '2025-05-06 09:01:05'),
(67, 10, 86, 4, 1, '2025-05-06 09:39:31'),
(68, 10, 86, 0, 1, '2025-05-06 10:48:03'),
(69, 10, 86, 3, 1, '2025-05-06 10:48:18'),
(70, 10, 86, 4, 1, '2025-05-06 14:00:00'),
(71, 9, 86, 1, 1, '2025-05-06 14:15:00'),
(72, 8, 86, 2, 1, '2025-05-06 14:30:00'),
(73, 10, 87, 2, 1, '2025-05-06 15:00:00'),
(74, 9, 87, 4, 1, '2025-05-06 15:15:00'),
(75, 8, 87, 5, 1, '2025-05-06 15:30:00'),
(76, 10, 88, 5, 1, '2025-05-06 16:00:00'),
(77, 9, 88, 5, 1, '2025-05-06 16:15:00'),
(78, 8, 88, 0, 1, '2025-05-06 16:30:00');

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
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `quiz_player_game`
--

INSERT INTO `quiz_player_game` (`Id_player_game`, `progression`, `result_player_game`, `date_game`, `Id_question`, `Id_player`) VALUES
(1, 'En cours', NULL, '2025-04-13 15:01:58', 1, 1),
(2, 'Terminé', 10, '2025-04-13 15:01:58', 2, 2),
(3, 'Terminé', 8, '2025-04-13 15:01:58', 3, 3),
(4, 'Terminé', 0, '2025-04-20 18:46:16', 0, 0),
(5, 'Terminé', 1, '2025-04-23 19:51:29', 0, 0),
(6, 'Terminé', NULL, '2025-04-24 21:14:27', 0, 0),
(7, 'Terminé', 8, '2025-05-06 14:52:13', 82, 10),
(8, 'Terminé', 7, '2025-05-06 14:52:13', 84, 10),
(9, 'Terminé', 9, '2025-05-06 14:52:13', 85, 10),
(10, 'Terminé', 6, '2025-05-06 14:52:13', 86, 9),
(11, 'Terminé', 8, '2025-05-06 14:52:13', 87, 9);

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
) ENGINE=InnoDB AUTO_INCREMENT=124 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `quiz_question`
--

INSERT INTO `quiz_question` (`Id_question`, `Id_question_category`, `Id_admin_editor`, `Id_question_difficulte`, `text`, `picture`, `date_creation`) VALUES
(82, 1, 3, 1, 'Quelle balise est utilisée pour créer un titre de premier niveau en HTML ?', 'https://www.w3.org/html/logo/downloads/HTML5_Badge_512.png', '2025-05-04 16:48:50'),
(84, 1, 3, 1, 'Quelle balise permet de créer un lien hypertexte en HTML ?', 'https://www.w3.org/html/logo/downloads/HTML5_Badge_512.png', '2025-05-04 17:06:05'),
(85, 1, 1, 1, 'Quelle balise HTML est utilisée pour créer une liste non-ordonnée ?', 'https://www.w3.org/html/logo/downloads/HTML5_Badge_512.png', '2025-05-04 18:28:56'),
(86, 1, 8, 1, 'Comment définir un titre de page en HTML ?', 'https://www.w3.org/html/logo/downloads/HTML5_Badge_512.png', '2025-05-06 13:23:31'),
(87, 1, 8, 1, 'Quelle balise est utilisée pour créer un lien vers une autre page ?', 'https://www.w3.org/html/logo/downloads/HTML5_Badge_512.png', '2025-05-06 13:39:47'),
(88, 2, 8, 1, 'Quelle propriété CSS permet de changer la couleur du texte ?', 'https://www.w3.org/Style/CSS/t-shirt-100.png', '2025-05-06 14:47:06'),
(89, 2, 8, 1, 'Quelle propriété CSS permet de définir la largeur d\'un élément ?', 'https://www.w3.org/Style/CSS/t-shirt-100.png', '2025-05-06 14:47:06'),
(90, 2, 8, 1, 'Comment centrer horizontalement un élément avec CSS ?', 'https://www.w3.org/Style/CSS/t-shirt-100.png', '2025-05-06 14:47:06'),
(91, 2, 8, 1, 'Quelle propriété CSS permet de changer la police de caractères ?', 'https://www.w3.org/Style/CSS/t-shirt-100.png', '2025-05-06 14:47:06'),
(92, 2, 8, 1, 'Comment ajouter une bordure à un élément en CSS ?', 'https://www.w3.org/Style/CSS/t-shirt-100.png', '2025-05-06 14:47:06'),
(93, 3, 8, 1, 'Quelle méthode JavaScript permet de sélectionner un élément HTML par son ID ?', 'https://upload.wikimedia.org/wikipedia/commons/6/6a/JavaScript-logo.png', '2025-05-06 14:47:49'),
(94, 3, 8, 1, 'Comment déclarer une variable en JavaScript moderne ?', 'https://upload.wikimedia.org/wikipedia/commons/6/6a/JavaScript-logo.png', '2025-05-06 14:47:49'),
(95, 3, 8, 1, 'Quelle est la différence entre let et const en JavaScript ?', 'https://upload.wikimedia.org/wikipedia/commons/6/6a/JavaScript-logo.png', '2025-05-06 14:47:49'),
(96, 3, 8, 1, 'Comment ajouter un événement click à un élément en JavaScript ?', 'https://upload.wikimedia.org/wikipedia/commons/6/6a/JavaScript-logo.png', '2025-05-06 14:47:49'),
(97, 3, 8, 1, 'Comment créer un tableau en JavaScript ?', 'https://upload.wikimedia.org/wikipedia/commons/6/6a/JavaScript-logo.png', '2025-05-06 14:47:49'),
(98, 3, 8, 2, 'Quelle est la différence entre == et === en JavaScript ?', 'https://upload.wikimedia.org/wikipedia/commons/6/6a/JavaScript-logo.png', '2025-05-06 14:48:40'),
(99, 3, 8, 2, 'Qu\'est-ce qu\'une closure en JavaScript ?', 'https://upload.wikimedia.org/wikipedia/commons/6/6a/JavaScript-logo.png', '2025-05-06 14:48:40'),
(100, 3, 8, 2, 'Comment fonctionne l\'hoisting en JavaScript ?', 'https://upload.wikimedia.org/wikipedia/commons/6/6a/JavaScript-logo.png', '2025-05-06 14:48:40'),
(101, 3, 8, 2, 'Quelle est la différence entre synchrone et asynchrone en JavaScript ?', 'https://upload.wikimedia.org/wikipedia/commons/6/6a/JavaScript-logo.png', '2025-05-06 14:48:40'),
(102, 3, 8, 2, 'Comment fonctionne le this en JavaScript ?', 'https://upload.wikimedia.org/wikipedia/commons/6/6a/JavaScript-logo.png', '2025-05-06 14:48:40'),
(103, 2, 8, 2, 'Quelle est la différence entre margin et padding en CSS ?', 'https://www.w3.org/Style/CSS/t-shirt-100.png', '2025-05-06 14:49:20'),
(104, 2, 8, 2, 'Comment fonctionne le modèle de boîte (box model) en CSS ?', 'https://www.w3.org/Style/CSS/t-shirt-100.png', '2025-05-06 14:49:20'),
(105, 2, 8, 2, 'Quelle est la différence entre position: relative et position: absolute ?', 'https://www.w3.org/Style/CSS/t-shirt-100.png', '2025-05-06 14:49:20'),
(106, 2, 8, 2, 'Comment centrer verticalement un élément en CSS ?', 'https://www.w3.org/Style/CSS/t-shirt-100.png', '2025-05-06 14:49:20'),
(107, 2, 8, 2, 'Quelle est la différence entre display: inline et display: block ?', 'https://www.w3.org/Style/CSS/t-shirt-100.png', '2025-05-06 14:49:20'),
(108, 2, 8, 2, 'Quelle est la différence entre margin et padding en CSS ?', 'https://www.w3.org/Style/CSS/t-shirt-100.png', '2025-05-06 14:50:02'),
(109, 2, 8, 2, 'Comment fonctionne le modèle de boîte (box model) en CSS ?', 'https://www.w3.org/Style/CSS/t-shirt-100.png', '2025-05-06 14:50:02'),
(110, 2, 8, 2, 'Quelle est la différence entre position: relative et position: absolute ?', 'https://www.w3.org/Style/CSS/t-shirt-100.png', '2025-05-06 14:50:02'),
(111, 2, 8, 2, 'Comment centrer verticalement un élément en CSS ?', 'https://www.w3.org/Style/CSS/t-shirt-100.png', '2025-05-06 14:50:02'),
(112, 2, 8, 2, 'Quelle est la différence entre display: inline et display: block ?', 'https://www.w3.org/Style/CSS/t-shirt-100.png', '2025-05-06 14:50:02'),
(113, 1, 8, 2, 'Quelle est la différence entre <div> et <span> en HTML ?', 'https://www.w3.org/html/logo/downloads/HTML5_Logo_512.png', '2025-05-06 14:50:46'),
(114, 1, 8, 2, 'Comment créer un formulaire accessible en HTML5 ?', 'https://www.w3.org/html/logo/downloads/HTML5_Logo_512.png', '2025-05-06 14:50:46'),
(115, 1, 8, 2, 'Quelle est l\'utilité de l\'attribut alt sur une image ?', 'https://www.w3.org/html/logo/downloads/HTML5_Logo_512.png', '2025-05-06 14:50:46'),
(116, 1, 8, 2, 'Comment utiliser les balises sémantiques en HTML5 ?', 'https://www.w3.org/html/logo/downloads/HTML5_Logo_512.png', '2025-05-06 14:50:46'),
(117, 1, 8, 2, 'Quelle est la différence entre GET et POST dans un formulaire HTML ?', 'https://www.w3.org/html/logo/downloads/HTML5_Logo_512.png', '2025-05-06 14:50:46'),
(118, 1, 8, 3, 'Comment prévenir les attaques XSS (Cross-Site Scripting) en HTML ?', 'https://www.w3.org/html/logo/downloads/HTML5_Logo_512.png', '2025-05-06 14:53:04'),
(119, 1, 8, 3, 'Pourquoi est-il important d\'utiliser HTTPS pour un formulaire HTML ?', 'https://www.w3.org/html/logo/downloads/HTML5_Logo_512.png', '2025-05-06 14:53:04'),
(120, 2, 8, 3, 'Comment se protéger contre les attaques CSS Injection ?', 'https://www.w3.org/Style/CSS/t-shirt-100.png', '2025-05-06 14:53:04'),
(121, 2, 8, 3, 'Quelle est l\'importance du Content Security Policy (CSP) pour CSS ?', 'https://www.w3.org/Style/CSS/t-shirt-100.png', '2025-05-06 14:53:04'),
(122, 3, 8, 3, 'Comment prévenir les attaques CSRF en JavaScript ?', 'https://upload.wikimedia.org/wikipedia/commons/6/6a/JavaScript-logo.png', '2025-05-06 14:53:04'),
(123, 3, 8, 3, 'Quelle est la meilleure pratique pour stocker des données sensibles en JavaScript ?', 'https://upload.wikimedia.org/wikipedia/commons/6/6a/JavaScript-logo.png', '2025-05-06 14:53:04');

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
) ENGINE=MyISAM AUTO_INCREMENT=341 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `quiz_question_answer`
--

INSERT INTO `quiz_question_answer` (`Id_question_answer`, `text`, `correct`, `Id_question`) VALUES
(241, 'align: center;', 0, 90),
(242, 'font-family', 1, 91),
(243, 'text-font', 0, 91),
(244, 'type-face', 0, 91),
(245, 'border', 1, 92),
(246, 'outline', 0, 92),
(247, 'stroke', 0, 92),
(248, 'document.getElementById()', 1, 93),
(218, '<list>', 0, 85),
(219, '<ol>', 0, 85),
(220, '<a>', 1, 84),
(221, '<link>', 0, 84),
(222, '<href>', 0, 84),
(236, 'width', 1, 89),
(237, 'size', 0, 89),
(238, 'length', 0, 89),
(239, 'margin: 0 auto;', 1, 90),
(240, 'text-align: center;', 0, 90),
(235, 'font-color', 0, 88),
(234, 'text-color', 0, 88),
(224, '<title>Mon titre</title>', 1, 86),
(225, '<header>Mon titre</header>', 0, 86),
(226, '<heading>Mon titre</heading>', 0, 86),
(233, 'color', 1, 88),
(232, '<href>Lien</href>', 0, 87),
(231, '<link>Lien</link>', 0, 87),
(227, '<h1>', 1, 82),
(228, '<title>', 0, 82),
(229, '<header>', 0, 82),
(230, '<a href=\"page.html\">Lien</a>', 1, 87),
(217, '<ul>', 1, 85),
(249, 'document.querySelector()', 0, 93),
(250, 'document.findElement()', 0, 93),
(251, 'let maVariable = valeur;', 1, 94),
(252, 'var maVariable = valeur;', 0, 94),
(253, 'variable maVariable = valeur;', 0, 94),
(254, 'let peut être réassigné, const non', 1, 95),
(255, 'const est plus rapide que let', 0, 95),
(256, 'Il n\'y a pas de différence', 0, 95),
(257, 'element.addEventListener(\"click\", function(){})', 1, 96),
(258, 'element.onClick = function(){}', 0, 96),
(259, 'element.click(function(){})', 0, 96),
(260, 'let tableau = [];', 1, 97),
(261, 'let tableau = array();', 0, 97),
(262, 'let tableau = new Array;', 0, 97),
(263, '== compare les valeurs, === compare les valeurs et les types', 1, 98),
(264, 'Il n\'y a pas de différence', 0, 98),
(265, '=== est plus rapide que ==', 0, 98),
(266, 'Une fonction qui a accès aux variables de son scope parent', 1, 99),
(267, 'Une fonction qui se ferme automatiquement', 0, 99),
(268, 'Une fonction qui n\'a pas de paramètres', 0, 99),
(269, 'Les déclarations sont déplacées en haut du scope', 1, 100),
(270, 'Le code est exécuté de haut en bas', 0, 100),
(271, 'Les variables sont supprimées', 0, 100),
(272, 'Synchrone bloque l\'exécution, asynchrone non', 1, 101),
(273, 'Il n\'y a pas de différence', 0, 101),
(274, 'Asynchrone est plus rapide', 0, 101),
(275, 'this fait référence au contexte d\'exécution', 1, 102),
(276, 'this fait toujours référence à window', 0, 102),
(277, 'this n\'existe pas en JavaScript', 0, 102),
(278, 'margin est externe, padding est interne', 1, 103),
(279, 'Ils font la même chose', 0, 103),
(280, 'padding est plus important que margin', 0, 103),
(281, 'content + padding + border + margin', 1, 104),
(282, 'width + height seulement', 0, 104),
(283, 'margin + padding seulement', 0, 104),
(284, 'relative se base sur la position normale, absolute sur le parent', 1, 105),
(285, 'Ils font la même chose', 0, 105),
(286, 'absolute est toujours fixe', 0, 105),
(287, 'display: flex et align-items: center', 1, 106),
(288, 'text-align: center', 0, 106),
(289, 'vertical-align: middle', 0, 106),
(290, 'block prend toute la largeur, inline non', 1, 107),
(291, 'Ils font la même chose', 0, 107),
(292, 'inline est toujours visible', 0, 107),
(293, 'margin est externe, padding est interne', 1, 108),
(294, 'Ils font la même chose', 0, 108),
(295, 'padding est plus important que margin', 0, 108),
(296, 'content + padding + border + margin', 1, 109),
(297, 'width + height seulement', 0, 109),
(298, 'margin + padding seulement', 0, 109),
(299, 'relative se base sur la position normale, absolute sur le parent', 1, 110),
(300, 'Ils font la même chose', 0, 110),
(301, 'absolute est toujours fixe', 0, 110),
(302, 'display: flex et align-items: center', 1, 111),
(303, 'text-align: center', 0, 111),
(304, 'vertical-align: middle', 0, 111),
(305, 'block prend toute la largeur, inline non', 1, 112),
(306, 'Ils font la même chose', 0, 112),
(307, 'inline est toujours visible', 0, 112),
(308, '<div> est un élément block, <span> est inline', 1, 113),
(309, 'Ils font la même chose', 0, 113),
(310, '<span> est plus important que <div>', 0, 113),
(311, 'Utiliser les attributs aria et labels', 1, 114),
(312, 'Utiliser uniquement des champs texte', 0, 114),
(313, 'Ne pas utiliser de validation', 0, 114),
(314, 'Décrire l\'image pour l\'accessibilité', 1, 115),
(315, 'Améliorer le style de l\'image', 0, 115),
(316, 'Aucune utilité particulière', 0, 115),
(317, 'Pour mieux structurer le contenu (<header>, <nav>, <main>)', 1, 116),
(318, 'Pour le style uniquement', 0, 116),
(319, 'Pour améliorer les performances', 0, 116),
(320, 'GET envoie dans l\'URL, POST dans le corps de la requête', 1, 117),
(321, 'Ils font la même chose', 0, 117),
(322, 'POST est plus rapide que GET', 0, 117),
(323, 'Échapper les caractères spéciaux et utiliser htmlspecialchars()', 1, 118),
(324, 'Ne rien faire de spécial', 0, 118),
(325, 'Désactiver JavaScript', 0, 118),
(326, 'Pour chiffrer les données transmises', 1, 119),
(327, 'Pour avoir un joli cadenas', 0, 119),
(328, 'Ce n\'est pas important', 0, 119),
(329, 'Valider et échapper les entrées utilisateur', 1, 120),
(330, 'Utiliser uniquement des couleurs', 0, 120),
(331, 'Ignorer le problème', 0, 120),
(332, 'Contrôler les sources de contenu autorisées', 1, 121),
(333, 'Rendre le site plus joli', 0, 121),
(334, 'Augmenter les performances', 0, 121),
(335, 'Utiliser des tokens CSRF', 1, 122),
(336, 'Ne rien faire', 0, 122),
(337, 'Désactiver les formulaires', 0, 122),
(338, 'Utiliser HttpOnly cookies et éviter localStorage pour les données sensibles', 1, 123),
(339, 'Stocker tout dans localStorage', 0, 123),
(340, 'Utiliser des variables globales', 0, 123);

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
(1, 'https://www.w3.org/html/logo/downloads/HTML5_Badge_512.png', 'yes', 'HTML Basics', 'Questions sur les bases du HTML'),
(2, 'https://upload.wikimedia.org/wikipedia/commons/thumb/d/d5/CSS3_logo_and_wordmark.svg/1452px-CSS3_logo_and_wordmark.svg.png', 'yes', 'CSS Basics', 'Questions sur les bases du CSS'),
(3, 'https://upload.wikimedia.org/wikipedia/commons/6/6a/JavaScript-logo.png', 'yes', 'JavaScript Basics', 'Questions sur les bases du JavaScript');

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

-- --------------------------------------------------------

--
-- Structure de la table `quiz_quizzes`
--

DROP TABLE IF EXISTS `quiz_quizzes`;
CREATE TABLE IF NOT EXISTS `quiz_quizzes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `created_by` int NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`)
) ENGINE=InnoDB AUTO_INCREMENT=93 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `quiz_quizzes`
--

INSERT INTO `quiz_quizzes` (`id`, `title`, `description`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'Quiz temporaire', 'Quiz généré automatiquement pour le test.', 1, '2025-05-06 14:33:24', '2025-05-06 14:33:24'),
(86, 'Quiz HTML Basics', 'Quiz sur les bases du HTML', 8, '2025-05-04 00:00:00', '2025-05-06 14:38:16'),
(87, 'Quiz CSS Basics', 'Quiz sur les bases du CSS', 8, '2025-05-04 00:00:00', '2025-05-06 14:38:16'),
(88, 'Quiz JavaScript', 'Quiz sur les bases de JavaScript', 8, '2025-05-04 00:00:00', '2025-05-06 14:38:16'),
(89, 'Quiz temporaire', 'Quiz généré automatiquement pour le test.', 1, '2025-05-06 14:39:32', '2025-05-06 14:39:32'),
(90, 'Quiz temporaire', 'Quiz généré automatiquement pour le test.', 1, '2025-05-06 14:39:36', '2025-05-06 14:39:36'),
(91, 'Quiz temporaire', 'Quiz généré automatiquement pour le test.', 1, '2025-05-06 14:42:07', '2025-05-06 14:42:07'),
(92, 'Quiz temporaire', 'Quiz généré automatiquement pour le test.', 1, '2025-05-06 14:55:04', '2025-05-06 14:55:04');

-- --------------------------------------------------------

--
-- Structure de la table `quiz_quiz_questions`
--

DROP TABLE IF EXISTS `quiz_quiz_questions`;
CREATE TABLE IF NOT EXISTS `quiz_quiz_questions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `quiz_id` int NOT NULL,
  `question_id` int NOT NULL,
  `question_order` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_quiz_question` (`quiz_id`,`question_id`),
  KEY `fk_question_id` (`question_id`)
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `quiz_quiz_questions`
--

INSERT INTO `quiz_quiz_questions` (`id`, `quiz_id`, `question_id`, `question_order`) VALUES
(3, 86, 82, 1),
(4, 86, 84, 2),
(5, 86, 85, 3),
(6, 86, 86, 4),
(7, 86, 87, 5),
(11, 87, 88, 1),
(12, 87, 89, 2),
(13, 87, 90, 3),
(14, 87, 91, 4),
(15, 87, 92, 5),
(18, 88, 93, 1),
(19, 88, 94, 2),
(20, 88, 95, 3),
(21, 88, 96, 4),
(22, 88, 97, 5),
(25, 88, 98, 6),
(26, 88, 99, 7),
(27, 88, 100, 8),
(28, 88, 101, 9),
(29, 88, 102, 10),
(32, 87, 103, 6),
(33, 87, 104, 7),
(34, 87, 105, 8),
(35, 87, 106, 9),
(36, 87, 107, 10),
(39, 87, 108, 6),
(40, 87, 109, 7),
(41, 87, 110, 8),
(42, 87, 111, 9),
(43, 87, 112, 10),
(46, 86, 113, 6),
(47, 86, 114, 7),
(48, 86, 115, 8),
(49, 86, 116, 9),
(50, 86, 117, 10),
(53, 86, 118, 11),
(54, 86, 119, 12),
(56, 87, 120, 11),
(57, 87, 121, 12),
(59, 88, 122, 11),
(60, 88, 123, 12);

-- --------------------------------------------------------

--
-- Structure de la table `quiz_users`
--

DROP TABLE IF EXISTS `quiz_users`;
CREATE TABLE IF NOT EXISTS `quiz_users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('player','super_admin','editor') NOT NULL DEFAULT 'player',
  `Id_Avatar` int DEFAULT NULL,
  `date_creation` datetime DEFAULT CURRENT_TIMESTAMP,
  `last_login` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `fk_avatar` (`Id_Avatar`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `quiz_users`
--

INSERT INTO `quiz_users` (`id`, `name`, `first_name`, `email`, `password`, `role`, `Id_Avatar`, `date_creation`, `last_login`) VALUES
(1, 'Doe', 'John', 'john.doe@example.com', '482c811da5d5b4bc6d497ffa98491e38', 'player', 1, '2025-04-13 15:01:57', NULL),
(2, 'Smith', 'Jane', 'jane.smith@example.com', '482c811da5d5b4bc6d497ffa98491e38', 'player', 2, '2025-04-13 15:01:57', NULL),
(3, 'Brown', 'Charlie', 'charlie.brown@example.com', '482c811da5d5b4bc6d497ffa98491e38', 'player', 3, '2025-04-13 15:01:57', NULL),
(4, 'k', 'k', 'k@gmail.com', '$2y$10$kzPpzrdmS7WiHtC8cf4s.OQpvcUQAXbbMLWjhPL49osaJpKZvhJk.', 'player', 1, '2025-05-02 01:03:36', NULL),
(5, 'm', 's', 's@gmail.com', '$2y$10$Rkm6n8UP5qhNoZszbnjV6eT5ALQcuXZvMBON87vKC.509q24baENq', 'player', 1, '2025-05-02 10:00:11', NULL),
(6, 'm', 'm', 'm@gmail.com', '$2y$10$40.umbVcWfQ/s4wQ0UjrEO2tcgUzPXD7bPRTALUXf64M2CNlgGOI2', 'player', 1, '2025-05-02 10:19:02', NULL),
(7, 'KK', 'i', 'i@gmail.com', '$2y$10$ZHpnR8a34tteaT3RRijg6u0qzABRLqiPyxwiRiVWWOGBg2.WGlUq.', 'player', 2, '2025-05-02 10:40:22', NULL),
(8, 'Marchal', 'Sébastien', 'rasengraphe@gmail.com', '$2y$10$73OGo0NL6EYT.96eB6pwTuiKUszawOWREYCUHnwYzr4ryiQxpo146', 'super_admin', NULL, '2025-04-13 19:21:08', '2025-05-06 17:30:00'),
(9, 'mamaa', 'mamama', 'mama@gmail.com', '$2y$10$PB1mmgm/vUZ5VvwvYQbL3.R4IR574KDuJ4wUDJqdPBwlkJE.qIFTS', 'player', 1, '2025-05-06 12:09:49', '2025-05-06 17:15:00'),
(10, 'marc', 'marc', 'marc@gmail.com', '$2y$10$e4BgL5W3iPHzzNHlBnx5M.pG7DHH81yOyzq3QG5pm852xaNiP0FvW', 'player', 1, '2025-05-06 13:25:24', '2025-05-06 17:00:00');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `quiz_quizzes`
--
ALTER TABLE `quiz_quizzes`
  ADD CONSTRAINT `quiz_quizzes_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `quiz_users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `quiz_quiz_questions`
--
ALTER TABLE `quiz_quiz_questions`
  ADD CONSTRAINT `fk_question_id` FOREIGN KEY (`question_id`) REFERENCES `quiz_question` (`Id_question`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_quiz_id` FOREIGN KEY (`quiz_id`) REFERENCES `quiz_quizzes` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
