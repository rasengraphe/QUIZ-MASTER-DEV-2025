# Documentation QUIZ-MASTER-DEV-2025

## Table des matières

1. [Introduction](#introduction)
2. [Architecture du projet](#architecture-du-projet)
3. [Installation](#installation)
4. [Structure de la base de données](#structure-de-la-base-de-données)
5. [Fonctionnalités](#fonctionnalités)
6. [Guide utilisateur](#guide-utilisateur)
7. [Guide administrateur](#guide-administrateur)
8. [Aspects techniques](#aspects-techniques)
9. [Sécurité](#sécurité)
10. [Évolutivité et perspectives](#évolutivité-et-perspectives)

## Introduction

QUIZ-MASTER-DEV-2025 est une plateforme interactive de quiz en ligne développée en PHP. Le projet permet aux utilisateurs de participer à des quiz sur différents sujets, notamment le développement web (HTML, CSS, JavaScript), et aux administrateurs de gérer le contenu de la plateforme.

### Objectifs du projet

- Créer une plateforme de quiz intuitive et conviviale
- Permettre la gestion complète du contenu par les administrateurs
- Offrir une expérience utilisateur engageante
- Assurer la sécurité des données et des utilisateurs
- Proposer une structure évolutive pour de futures améliorations

## Architecture du projet

Le projet suit une architecture MVC (Modèle-Vue-Contrôleur) pour assurer une séparation claire des responsabilités :

- **Modèle** : Gère l'accès aux données et les opérations sur la base de données
- **Vue** : S'occupe de l'affichage et de l'interface utilisateur
- **Contrôleur** : Orchestre les interactions entre le modèle et la vue, traite les requêtes

### Structure des dossiers

```
QUIZ-MASTER-DEV-2025/
├── config/                  # Configuration (connexion BDD)
├── controllers/             # Contrôleurs (logique applicative)
├── core/                    # Classes fondamentales
├── models/                  # Modèles (accès aux données)
├── public/                  # Ressources publiques
│   ├── css/                 # Fichiers CSS compilés
│   ├── img/                 # Images
│   │   └── avatars/         # Avatars utilisateurs
│   ├── js/                  # Scripts JavaScript
│   └── scss/                # Fichiers SCSS source
├── uploads/                 # Fichiers téléchargés
│   └── questions/           # Images des questions
├── views/                   # Vues (templates)
│   ├── admin/               # Pages d'administration
│   ├── layout/              # Templates partagés (header, footer)
│   └── user/                # Pages utilisateur
└── index.php                # Point d'entrée
```

## Installation

### Prérequis

- PHP 7.4+ avec extensions PDO
- MySQL 5.7+ ou MariaDB 10+
- Serveur web (Apache/Nginx)
- Gestionnaire de dépendances Composer (optionnel)

### Étapes d'installation

1. Cloner le dépôt dans votre répertoire web :

   ```bash
   git clone [URL_DU_REPO] /path/to/www/QUIZ-MASTER-DEV-2025
   ```

2. Créer une base de données MySQL nommée `quizdb`

3. Importer le fichier SQL pour créer les tables :

   ```bash
   mysql -u [username] -p quizdb < database/schema.sql
   ```

4. Configurer la connexion à la base de données en modifiant le fichier `config/database.php` :

   ```php
   $db_host = "localhost";
   $db_name = "quizdb";
   $db_user = "votre_utilisateur";
   $db_pass = "votre_mot_de_passe";
   ```

5. S'assurer que les dossiers `uploads` et ses sous-dossiers sont accessibles en écriture :

   ```bash
   chmod -R 755 uploads/
   ```

6. Accéder à l'application via votre navigateur : `http://localhost/QUIZ-MASTER-DEV-2025/`

## Structure de la base de données

Le schéma de base de données comprend plusieurs tables reliées entre elles :

![Schéma de la base de données](path/to/database_schema.png)

### Tables principales

- `quiz_users` : Stocke les informations des utilisateurs (joueurs et administrateurs)
- `quiz_question` : Contient toutes les questions du quiz
- `quiz_question_answer` : Contient les réponses possibles pour chaque question
- `quiz_question_category` : Catégories des questions (HTML, CSS, JavaScript, etc.)
- `quiz_question_difficulte` : Niveaux de difficulté des questions
- `quiz` : Informations sur les quiz configurés
- `quiz_questions` : Table de jonction entre quiz et questions
- `quiz_game_history` : Historique des parties jouées par les utilisateurs
- `quiz_avatar` : Avatars disponibles pour les utilisateurs

## Fonctionnalités

### Fonctionnalités utilisateurs

- Inscription et connexion avec profil personnalisé
- Choix d'un avatar personnalisé
- Participation à différents quiz thématiques
- Suivi des scores et statistiques personnelles
- Consultation du classement des joueurs

### Fonctionnalités administrateurs

- Tableau de bord d'administration complet
- Gestion des questions (ajout, modification, suppression)
- Gestion des réponses pour chaque question
- Organisation des questions par catégorie et niveau de difficulté
- Création et configuration de quiz
- Gestion des utilisateurs
- Visualisation des statistiques

## Guide utilisateur

### Inscription et connexion

1. Accédez à la page d'accueil et cliquez sur "S'inscrire"
2. Remplissez le formulaire avec vos informations et choisissez un avatar
3. Une fois inscrit, connectez-vous avec votre email et mot de passe
4. Vous êtes redirigé vers votre tableau de bord joueur

### Participer à un quiz

1. Depuis le tableau de bord, cliquez sur "Jouer à un quiz"
2. Sélectionnez un quiz dans la liste disponible
3. Lisez les instructions et cliquez sur "Commencer"
4. Répondez aux questions en sélectionnant une réponse
5. À la fin du quiz, consultez votre score et les réponses correctes

### Consulter votre profil

1. Cliquez sur votre nom d'utilisateur ou votre avatar en haut à droite
2. Accédez à "Mon profil" pour voir vos statistiques
3. Pour modifier vos informations, cliquez sur "Modifier mon profil"
4. Vous pouvez changer votre avatar, email ou mot de passe

## Guide administrateur

### Accéder à l'administration

1. Connectez-vous avec un compte administrateur
2. Vous êtes automatiquement dirigé vers le tableau de bord admin
3. Utilisez le menu de navigation pour accéder aux différentes sections

### Gérer les questions

1. Dans la section "Questions", vous pouvez voir la liste de toutes les questions
2. Pour ajouter une question, cliquez sur "Ajouter une question"
3. Complétez le formulaire avec :
   - Le texte de la question
   - La catégorie et le niveau de difficulté
   - Les réponses possibles (en marquant la réponse correcte)
   - Une image optionnelle (par URL ou téléchargement)
4. Pour modifier une question existante, cliquez sur son bouton "Modifier"
5. Pour supprimer une question, utilisez le bouton "Supprimer"

### Créer un quiz

1. Accédez à la section "Quiz" du tableau de bord
2. Cliquez sur "Créer un quiz"
3. Donnez un titre et une description au quiz
4. Après la création, cliquez sur "Gérer les questions" pour ce quiz
5. Ajoutez des questions existantes au quiz depuis la liste disponible

## Aspects techniques

### Technologies utilisées

- **Backend** : PHP 8.0 orienté objet
- **Architecture** : Modèle-Vue-Contrôleur (MVC)
- **Base de données** : MySQL avec PDO pour les requêtes préparées
- **Frontend** : HTML5, CSS3 (SCSS), JavaScript
- **Responsive Design** : Compatible mobile, tablette et bureau

### Points techniques notables

- Utilisation des sessions PHP pour la gestion de l'authentification
- Système de routage personnalisé dans l'index.php
- Hachage sécurisé des mots de passe avec `password_hash()`
- Autoloader pour le chargement des classes
- Préparation des requêtes SQL pour éviter les injections
- Architecture extensible avec classes de base abstraites (Model, Controller)
- Compilation SCSS en CSS pour une meilleure organisation du style

## Sécurité

Le projet intègre plusieurs mécanismes de sécurité :

- Protection contre les injections SQL avec PDO et requêtes préparées
- Hachage des mots de passe avec algorithme sécurisé
- Protection CSRF sur les formulaires
- Échappement des sorties HTML (XSS)
- Validation des entrées utilisateur
- Gestion des permissions par rôles (joueur, admin, super_admin)
- Protection des routes sensibles

## Évolutivité et perspectives

Le projet a été conçu pour être facilement extensible. Voici quelques pistes d'amélioration future :

- Ajout d'un système de quiz en temps limité
- Mode multijoueur en temps réel
- Implémentation de badges et récompenses
- Intégration de médias riches (vidéos, audio)
- Système avancé de statistiques et analytiques
- API REST pour applications mobiles
- Système de paiement pour contenus premium

## Contribution

Ce projet a été développé par [Votre Nom] dans le cadre de [contexte du projet/formation]. Pour toute question ou suggestion, merci de me contacter à [votre email].
