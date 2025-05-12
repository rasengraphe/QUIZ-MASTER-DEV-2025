# Dossier de Projet : Quiz Master Dev

## Page de Garde

### Titre du Projet : Quiz Master Dev 2025

**Nom :** VOTRE NOM

**Formation :** Développeur Web et Web Mobile

**Établissement :** VOTRE ÉTABLISSEMENT

**Année Académique :** 2024-2025

![Logo Quiz Master Dev](../public/img/logo.png)

## Table des Matières

1. [Introduction](#1-introduction)
2. [Présentation du Projet](#2-présentation-du-projet)
3. [Objectifs du Projet](#3-objectifs-du-projet)
4. [Spécifications Fonctionnelles](#4-spécifications-fonctionnelles)
5. [Spécifications Techniques](#5-spécifications-techniques)
6. [Architecture du Projet](#6-architecture-du-projet)
7. [Réalisation](#7-réalisation)
   - [7.1. Étapes de la Réalisation](#71-étapes-de-la-réalisation)
   - [7.2. Maquettes](#72-maquettes)
   - [7.3. Intégration](#73-intégration)
   - [7.4. Base de Données](#74-base-de-données)
8. [Tests et Validation](#8-tests-et-validation)
9. [Conclusion](#9-conclusion)
10. [Annexes](#10-annexes)

## 1. Introduction

### Brève description de la plateforme Quiz Master Dev

QUIZ-MASTER-DEV-2025 est une plateforme interactive de quiz en ligne développée en PHP. Le projet permet aux utilisateurs de participer à des quiz sur différents sujets, notamment le développement web (HTML, CSS, JavaScript), et aux administrateurs de gérer le contenu de la plateforme. L'application offre une expérience d'apprentissage ludique et interactive, tout en fournissant aux administrateurs les outils nécessaires pour gérer et analyser l'activité des utilisateurs.

### Contexte du projet

Ce projet a été réalisé dans le cadre de ma formation de Développeur Web et Web Mobile. Il répond à un besoin de créer une application web complète, mettant en pratique les compétences acquises en développement front-end et back-end. L'objectif était de concevoir une plateforme qui soit à la fois utile pour les utilisateurs et un exemple concret de développement web moderne.

### Public cible

Quiz Master Dev s'adresse à deux types de public :

- **Les joueurs :** Étudiants, professionnels, ou toute personne souhaitant tester et améliorer ses connaissances dans divers domaines, en particulier le développement web.

- **Les administrateurs :** Formateurs, enseignants, ou responsables de formation qui ont besoin d'un outil pour créer et gérer des quiz pour leurs apprenants.

![Capture d'écran de la page d'accueil](../documentation/images/homepage_screenshot.jpg)

## 2. Présentation du Projet

### Description détaillée de l'application

QUIZ-MASTER-DEV-2025 est une plateforme web qui permet de créer et de participer à des quiz en ligne. Elle est composée de deux parties principales :

- Une interface utilisateur pour les joueurs, leur permettant de s'inscrire, de se connecter, de choisir un quiz, de répondre aux questions, de consulter leurs résultats et de gérer leur profil.

- Une interface d'administration pour les administrateurs, leur permettant de gérer les utilisateurs, de créer, modifier et supprimer des quiz et des questions, de gérer les catégories de quiz, et de consulter des statistiques sur l'activité de la plateforme.

### Fonctionnalités principales

Le projet offre les fonctionnalités suivantes :

**Pour les joueurs :**

- Inscription et connexion sécurisées
- Navigation facile et intuitive dans le catalogue de quiz
- Participation à des quiz avec différents types de questions
- Affichage des résultats détaillés après chaque quiz
- Suivi de la progression et des scores personnels dans un tableau de bord dédié
- Personnalisation du profil utilisateur avec choix d'avatar
- **Partage des résultats sur les réseaux sociaux** grâce à l'API Web Share

**Pour les administrateurs :**

- Gestion complète des comptes utilisateurs (création, modification, suppression, rôles)
- Création, modification et suppression de quiz, questions et réponses
- Organisation des quiz par catégories et niveaux de difficulté
- Consultation de statistiques et de rapports sur l'activité des joueurs et des quiz
- Gestion de la sécurité de la plateforme

![Schéma du flux utilisateur](../documentation/images/user_flow.jpg)

## 3. Objectifs du Projet

### Objectifs fonctionnels

- Créer une plateforme de quiz intuitive et conviviale, accessible à tous types d'utilisateurs.
- Permettre aux joueurs de participer à des quiz de manière interactive et motivante.
- Fournir aux administrateurs un outil complet pour gérer le contenu de la plateforme de manière efficace.
- Assurer une expérience utilisateur fluide et agréable sur tous les appareils (ordinateur, tablette, mobile).

### Objectifs techniques

- Assurer la sécurité des données et des utilisateurs en mettant en place des mécanismes de protection contre les attaques courantes (injections SQL, XSS, CSRF).
- Proposer une structure évolutive pour de futures améliorations et l'ajout de nouvelles fonctionnalités.
- Utiliser des technologies web modernes et performantes (PHP, MySQL, HTML, CSS, JavaScript).
- Respecter les standards du web et les bonnes pratiques de développement.

### Objectifs d'apprentissage

- Approfondir mes connaissances en développement web full-stack (front-end et back-end).
- Maîtriser l'architecture MVC et son application dans un projet concret.
- Développer des compétences en conception de base de données et en requêtes SQL.
- Mettre en pratique les principes de sécurité web et de protection des données.
- Améliorer mes compétences en gestion de projet, en particulier la planification, l'organisation et la documentation.

## 4. Spécifications Fonctionnelles

### Détail des fonctionnalités de l'application

#### Pour les Joueurs :

- **Inscription et connexion :** Les joueurs peuvent créer un compte et se connecter à la plateforme de manière sécurisée.
- **Participation à des quiz :**
  - Sélection d'un quiz parmi une liste organisée par catégories et niveaux de difficulté
  - Affichage des questions une par une avec leurs options de réponse
  - Calcul automatique du score en fonction des réponses correctes
  - Chronomètre optionnel pour limiter le temps de réponse
- **Système de scores et résultats :**
  - Affichage détaillé des résultats à la fin de chaque quiz (score, réponses correctes/incorrectes)
  - Historique des quiz joués et des scores obtenus
  - Visualisation des statistiques personnelles (moyenne, meilleur score, progression)
- **Partage social :**
  - Partage des résultats de quiz via l'API Web Share sur diverses plateformes (réseaux sociaux, applications de messagerie)
  - Options de personnalisation du message de partage incluant le score obtenu
- **Modification du profil :** Les joueurs peuvent modifier leurs informations personnelles, comme leur nom, leur avatar, et leur mot de passe.

#### Pour les Administrateurs :

- **Gestion des utilisateurs :** Les administrateurs peuvent voir la liste des utilisateurs, modifier leurs informations, changer leur rôle (joueur, administrateur), et désactiver des comptes.
  - _Note: La fonctionnalité CRUD complète pour les joueurs (ajout, modification, suppression par l'administrateur) est prévue pour la version 2.0 du projet_
- **Gestion des questions :** Les administrateurs peuvent créer de nouveaux quiz, ajouter, modifier et supprimer des questions (avec différents types de réponses possibles), et organiser les questions en catégories.
- **Gestion des catégories de quiz :** Les administrateurs peuvent créer, modifier et supprimer des catégories et des niveaux de difficulté pour organiser le contenu.
- **Statistiques et rapports :** Les administrateurs peuvent consulter des statistiques sur l'activité de la plateforme, comme le nombre de joueurs inscrits, les quiz les plus populaires, et les résultats moyens des joueurs.

### Cas d'utilisation

![Diagramme de cas d'utilisation](../documentation/images/use_cases.jpg)

## 5. Spécifications Techniques

### Environnement de développement

Le projet a été développé sur un environnement de développement composé des éléments suivants :

- **Système d'exploitation :** Windows 10
- **Serveur web :** Apache 2.4 (WampServer)
- **SGBD :** MySQL 8.0
- **Éditeur de code :** Visual Studio Code
- **Outils de gestion de projet :** Git, GitHub

### Langages de programmation utilisés

Les langages de programmation utilisés pour développer Quiz Master Dev sont :

- **PHP 8.0 :** Langage de script côté serveur pour la logique applicative et l'interaction avec la base de données.
- **HTML5 :** Langage de balisage pour la structure et le contenu des pages web.
- **CSS3 :** Langage de style pour la présentation et l'apparence des pages web.
- **JavaScript ES6 :** Langage de script côté client pour l'interactivité et les fonctionnalités dynamiques de l'interface utilisateur.
- **SCSS :** Préprocesseur CSS pour une meilleure organisation et maintenance du code CSS.

### Frameworks et librairies

Le projet utilise les frameworks et librairies suivantes :

- **Architecture MVC personnalisée :** Framework PHP maison pour structurer l'application selon l'architecture MVC.
- **Bootstrap 4.5 :** Framework CSS pour la création d'une interface utilisateur responsive et esthétique.
- **jQuery 3.5 :** Bibliothèque JavaScript pour faciliter les manipulations DOM et les requêtes AJAX.
- **API Web Share :** API native des navigateurs permettant le partage de contenu sur les réseaux sociaux et autres applications.

### Base de données

La base de données utilisée est MySQL. Elle permet de stocker de manière structurée les données de l'application, telles que les informations des utilisateurs, les quiz, les questions, les réponses, et les résultats.

### Architecture logicielle

Le projet suit une architecture logicielle MVC (Modèle-Vue-Contrôleur). Cette architecture permet de séparer les différentes parties de l'application (données, présentation, logique) pour faciliter le développement, la maintenance et l'évolutivité.

| Technologie    | Rôle                                                                  |
| -------------- | --------------------------------------------------------------------- |
| PHP 8.0        | Logique applicative côté serveur, interaction avec la base de données |
| HTML5          | Structure et contenu des pages web                                    |
| CSS3/SCSS      | Style et présentation des pages web                                   |
| JavaScript ES6 | Interactivité et fonctionnalités dynamiques côté client               |
| MySQL 8.0      | Stockage des données de l'application                                 |
| Bootstrap 4.5  | Framework CSS pour une interface responsive                           |
| jQuery 3.5     | Bibliothèque JS pour manipulations DOM et requêtes AJAX               |

## 6. Architecture du Projet

### Description de l'architecture MVC

Le projet suit une architecture MVC (Modèle-Vue-Contrôleur) pour assurer une séparation claire des responsabilités :

- **Modèle :** Gère l'accès aux données et les opérations sur la base de données. Les modèles sont responsables de la logique métier et de la manipulation des données.

- **Vue :** S'occupe de l'affichage et de l'interface utilisateur. Les vues sont des templates qui présentent les données à l'utilisateur.

- **Contrôleur :** Orchestre les interactions entre le modèle et la vue, traite les requêtes de l'utilisateur, récupère les données du modèle et les transmet à la vue pour l'affichage. Les contrôleurs agissent comme intermédiaires entre les modèles et les vues.

#### Exemple concret : Gestion d'une question par l'administrateur

Voici un exemple illustrant le fonctionnement de l'architecture MVC lorsqu'un administrateur modifie une question :

1. **Interaction utilisateur :**
   - L'administrateur se connecte au tableau de bord et clique sur "Modifier" à côté d'une question.

2. **Contrôleur (`QuestionController.php`) :**
   ```php
   public function editQuestion($id) {
       // Vérification des permissions administrateur
       if (!$this->isAdmin()) {
           $this->redirect('login');
       }
       
       // Récupération des données de la question via le modèle
       $questionModel = new QuestionModel();
       $question = $questionModel->getQuestionById($id);
       
       if (!$question) {
           $this->setError("La question demandée n'existe pas.");
           $this->redirect('admin/questions');
       }
       
       // Récupération des données annexes (catégories, niveaux de difficulté)
       $categoryModel = new CategoryModel();
       $categories = $categoryModel->getAllCategories();
       
       // Transmission des données à la vue
       $this->render('admin/questions/edit', [
           'question' => $question,
           'categories' => $categories
       ]);
   }
   ```

3. **Modèle (`QuestionModel.php`) :**
   ```php
   public function getQuestionById($id) {
       // Requête SQL préparée pour éviter les injections
       $query = $this->db->prepare('
           SELECT q.*, c.name as category_name, d.level as difficulty_level
           FROM quiz_question q
           LEFT JOIN quiz_question_category c ON q.Id_category = c.Id_category
           LEFT JOIN quiz_question_difficulte d ON q.Id_difficulte = d.Id_difficulte
           WHERE q.Id_question = ?
       ');
       
       $query->execute([$id]);
       return $query->fetch();
   }
   ```

4. **Vue (`views/admin/questions/edit.php`) :**
   ```php
   <div class="admin-card">
       <h2>Modifier la question</h2>
       
       <form method="post" action="index.php?action=updateQuestion">
           <input type="hidden" name="id" value="<?= htmlspecialchars($question['Id_question']) ?>">
           
           <div class="form-group">
               <label for="question-text">Texte de la question</label>
               <textarea id="question-text" name="text" class="form-control" required><?= htmlspecialchars($question['text']) ?></textarea>
           </div>
           
           <div class="form-group">
               <label for="category">Catégorie</label>
               <select id="category" name="category" class="form-control">
                   <?php foreach($categories as $category): ?>
                   <option value="<?= $category['Id_category'] ?>" <?= $question['Id_category'] == $category['Id_category'] ? 'selected' : '' ?>>
                       <?= htmlspecialchars($category['name']) ?>
                   </option>
                   <?php endforeach; ?>
               </select>
           </div>
           
           <!-- Autres champs du formulaire... -->
           
           <div class="form-actions">
               <a href="index.php?action=questions" class="btn btn-secondary">Annuler</a>
               <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
           </div>
       </form>
   </div>
   ```

5. **Flux complet :**
   - L'URL `index.php?action=editQuestion&id=123` est demandée par l'administrateur
   - Le routeur analyse l'URL et appelle la méthode `editQuestion(123)` du `QuestionController`
   - Le contrôleur vérifie les permissions, puis demande au `QuestionModel` de récupérer les données
   - Le modèle interroge la base de données et renvoie les informations de la question
   - Le contrôleur transmet ces données à la vue
   - La vue génère le HTML du formulaire d'édition avec les valeurs actuelles de la question
   - L'administrateur voit le formulaire pré-rempli et peut modifier la question

Cette séparation en MVC permet une maintenance plus facile (chaque partie peut être modifiée indépendamment) et favorise la réutilisation du code.

### Structure des dossiers et fichiers

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
├── documentation/           # Documentation du projet
└── index.php                # Point d'entrée
```

### Schéma de l'architecture

![Schéma de l'architecture MVC](../documentation/images/mvc_architecture.jpg)

## 7. Réalisation

### 7.1. Étapes de la Réalisation

#### Planning prévisionnel du projet

Le projet a été réalisé selon le planning suivant :

| Semaine      | Période           | Étapes                                           |
| ------------ | ----------------- | ------------------------------------------------ |
| Semaine 1    | 15-21 février     | Expression des besoins, définition des objectifs |
| Semaine 2    | 22-28 février     | Définition de l'environnement technique          |
| Semaine 3    | 29 février-6 mars | Conception des maquettes et wireframes           |
| Semaine 4-5  | 7-20 mars         | Conception MCD/MLD, diagrammes UML               |
| Semaine 6-11 | 21 mars-5 mai     | Développement back-end et front-end              |
| Semaine 12   | 6-12 mai          | Phase de tests et débogage                       |

#### Chronologie des étapes du projet

La réalisation du projet Quiz Master Dev s'est déroulée selon les étapes suivantes :

1. **Analyse des besoins et spécifications :**

   - Définition des exigences fonctionnelles et techniques
   - Création d'un cahier des charges

2. **Conception :**

   - Création de wireframes pour définir la structure des pages
   - Conception des maquettes graphiques
   - Modélisation de la base de données
   - Choix des technologies

3. **Développement :**

   - Mise en place de l'environnement de développement
   - Création de la structure du projet
   - Développement du back-end (modèles, contrôleurs)
   - Développement du front-end (vues, intégration HTML/CSS/JS)

4. **Tests :**

   - Tests unitaires des composants
   - Tests d'intégration
   - Tests fonctionnels
   - Tests de sécurité

5. **Déploiement :**

   - Préparation pour le déploiement
   - Configuration du serveur
   - Déploiement de l'application

6. **Documentation :**
   - Rédaction de la documentation technique
   - Rédaction du guide utilisateur
   - Rédaction du dossier de projet

#### Choix de conception et difficultés rencontrées

Lors de la conception de Quiz Master Dev, plusieurs choix ont été faits :

- Utilisation de l'architecture MVC personnalisée pour une meilleure structure
- Adoption d'une approche orientée objet pour le développement PHP
- Utilisation de SCSS pour organiser le CSS de manière modulaire
- Implémentation d'un système de routage simple et efficace

Les principales difficultés rencontrées ont été :

- La mise en place du système d'authentification avec différents rôles
- La gestion des images téléchargées pour les questions
- L'implémentation du système de suppression des questions avec gestion des contraintes de clés étrangères
- La création d'une interface responsive adaptée à tous les appareils

#### Solutions apportées

Pour surmonter ces difficultés, les solutions suivantes ont été mises en œuvre :

- Développement d'un système d'authentification robuste avec sessions PHP et hachage des mots de passe
- Création d'un gestionnaire de fichiers pour les images de questions avec validation des types et tailles
- Implémentation d'une gestion transactionnelle pour les suppressions en cascade
- Utilisation de media queries et de CSS flexbox pour assurer la responsivité

### 7.2. Maquettes

#### Présentation des wireframes et des maquettes graphiques

Les wireframes et les maquettes graphiques ont été créés pour définir l'interface utilisateur de Quiz Master Dev.

Les wireframes ont permis de définir la structure des pages, la disposition des éléments, et le flux de navigation. Ils ont été réalisés à l'aide de FIGMA.

Les maquettes graphiques ont ensuite été créées pour donner une apparence visuelle à l'application. Elles définissent les couleurs, les typographies, les images, et les autres éléments graphiques. Elles ont été réalisées avec Figma.

#### Outils utilisés pour la conception

Les outils utilisés pour la conception de l'interface utilisateur sont :

- Figma pour les wireframes
- Figma pour les maquettes graphiques

#### Justification des choix de design

Les choix de design ont été guidés par les principes suivants :

- **Simplicité et clarté :** L'interface est conçue pour être intuitive et facile à utiliser.
- **Cohérence :** Une identité visuelle cohérente est maintenue à travers toute l'application.
- **Accessibilité :** Le design respecte les standards d'accessibilité pour être utilisable par tous.
- **Modernité :** Des éléments de design contemporains sont utilisés pour une expérience agréable.
- **Responsivité :** L'interface s'adapte à tous les types d'écrans.

Les couleurs principales (#0071e3 et #1d1d1f) ont été choisies pour leur contraste et leur association au domaine technologique. La typographie sans-serif a été sélectionnée pour sa lisibilité sur écran.

![Maquettes graphiques](../documentation/images/wireframes.jpg)

### 7.3. Intégration

#### Technologies utilisées (HTML5, CSS3, SASS, JavaScript)

L'intégration de l'interface utilisateur a été réalisée en utilisant les technologies suivantes :

- **HTML5 :** Pour la structure et le contenu des pages web.
- **CSS3/SCSS :** Pour la mise en forme et le style, avec SCSS pour une meilleure organisation.
- **JavaScript ES6 :** Pour l'interactivité et les fonctionnalités dynamiques, notamment pour le système de quiz et la gestion des réponses.
- **jQuery :** Pour simplifier les manipulations DOM et les requêtes AJAX.
- **API Web Share :** Implémentation de l'API native pour permettre le partage des résultats sur différentes plateformes.

#### Organisation du code

Le code CSS est organisé en modules SCSS :

- `_variables.scss` : Variables globales (couleurs, typographie)
- `style.scss` : Fichier principal important tous les modules
- `_layout.scss` : Structure générale et mise en page commune
- `_home.scss` : Styles spécifiques à la page d'accueil
- `_admin_dashboard.scss` : Styles spécifiques au tableau de bord administrateur
- `_player_dashboard.scss` : Styles spécifiques au tableau de bord joueur
- `_quiz.scss` : Styles spécifiques aux pages de quiz

Cette organisation modulaire permet de maintenir un code bien structuré où chaque fonctionnalité principale dispose de son propre fichier de style, tout en partageant des éléments communs via le fichier principal. Cette approche facilite la maintenance et l'évolution future du projet.

#### Système de gestion des avatars

Un système complet de gestion des avatars a été implémenté pour permettre aux utilisateurs de personnaliser leur profil :

1. **Structure de données** :

   - Table `quiz_avatar` dans la base de données qui stocke les identifiants uniques (Id_Avatar) pour chaque avatar disponible
   - Correspondance directe entre les entrées de la base de données et les fichiers physiques d'images

2. **Organisation des fichiers** :

   - Les images des avatars sont stockées dans le dossier `public/img/avatars/`
   - Chaque avatar est nommé selon la convention `avatar{Id_Avatar}.png` (ex: avatar1.png, avatar2.png, etc.)
   - Cette convention permet une liaison automatique entre l'ID stocké en base de données et le fichier correspondant

3. **Fonctionnalités utilisateur** :
   - **Lors de l'inscription** : L'utilisateur peut sélectionner son avatar parmi les options disponibles affichées sous forme de galerie
   - **Modification du profil** : L'utilisateur peut changer son avatar à tout moment depuis son espace personnel
4. **Mise en œuvre technique** :

   - Les avatars disponibles sont récupérés dynamiquement de la base de données et affichés à l'utilisateur
   - Lors de la sélection, seul l'ID de l'avatar est stocké dans la table `quiz_users`
   - À l'affichage, le chemin de l'image est généré dynamiquement en combinant le chemin du dossier et l'ID stocké

5. **Avantages de cette approche** :
   - Facilité de maintenance : ajouter un nouvel avatar ne nécessite que l'ajout d'une entrée en base de données et du fichier image correspondant
   - Économie d'espace de stockage : seul l'identifiant est stocké dans la table utilisateurs
   - Flexibilité : possibilité d'ajouter des métadonnées aux avatars (nom, catégorie, etc.) dans la table `quiz_avatar` si nécessaire

Cette implémentation offre une expérience utilisateur engageante tout en maintenant une architecture de données optimisée et facilement extensible.

#### Responsivité

L'application est entièrement responsive grâce à :

- L'utilisation de media queries pour adapter l'affichage aux différentes tailles d'écran
- L'implémentation de flexbox et grid pour des layouts flexibles
- Des images et polices responsives qui s'adaptent à l'écran

```scss
// Exemple de code SCSS pour la responsivité
@media (max-width: 768px) {
  .form-actions {
    flex-direction: column;
    gap: 15px;

    .btn {
      width: 100%;
    }
  }
}
```

```javascript
// Exemple de code JavaScript pour la gestion des quiz
document.addEventListener("DOMContentLoaded", function () {
  const quizForm = document.getElementById("quiz-form");

  if (quizForm) {
    quizForm.addEventListener("submit", function (e) {
      e.preventDefault();

      // Collecter les réponses
      const answers = {};
      const inputs = quizForm.querySelectorAll('input[type="radio"]:checked');

      inputs.forEach((input) => {
        answers[input.name] = input.value;
      });

      // Envoyer les réponses au serveur
      submitAnswers(answers);
    });
  }
});
```

### 7.4. Base de Données

#### Modélisation des données

La modélisation de la base de données a été réalisée à l'aide d'un diagramme entité-association (EA). Les principales entités sont :

- **quiz_users :** Stocke les informations des utilisateurs (joueurs et administrateurs)
- **quiz_question :** Contient toutes les questions du quiz
- **quiz_question_answer :** Contient les réponses possibles pour chaque question
- **quiz_question_category :** Catégories des questions
- **quiz_question_difficulte :** Niveaux de difficulté des questions
- **quiz :** Informations sur les quiz configurés
- **quiz_questions :** Table de jonction entre quiz et questions
- **quiz_game_history :** Historique des parties jouées par les utilisateurs
- **quiz_avatar :** Avatars disponibles pour les utilisateurs

#### Système de gestion de base de données

MySQL 8.0 a été choisi comme système de gestion de base de données pour sa fiabilité, ses performances et sa compatibilité avec PHP.

#### Justification des choix de conception

La conception de la base de données a été guidée par les principes suivants :

- **Intégrité des données :** Utilisation de clés primaires et étrangères pour garantir la cohérence
- **Normalisation :** Tables conçues selon les formes normales pour éviter la redondance
- **Performance :** Indexation appropriée pour optimiser les requêtes fréquentes
- **Évolutivité :** Structure permettant l'ajout de nouvelles fonctionnalités

![Schéma de la base de données](../documentation/images/database_schema.jpg)

## 8. Tests et Validation

### Méthodologies de test utilisées

Pour assurer la qualité et la fiabilité de l'application, j'ai utilisé une approche de tests manuels systématiques :

- **Tests fonctionnels :** Vérification systématique de chaque fonctionnalité selon des scénarios d'utilisation prédéfinis.
- **Tests d'intégration :** Vérification des interactions entre les différents composants de l'application.
- **Tests de compatibilité navigateur :** Vérification du comportement de l'application sur différents navigateurs (Chrome, Firefox, Edge).
- **Tests de responsivité :** Vérification de l'adaptation de l'interface sur différents appareils et tailles d'écran.
- **Tests de sécurité :** Vérification de la robustesse face aux tentatives d'injection SQL et attaques XSS.

Contrairement à ce qui était initialement prévu, les tests unitaires automatisés avec PHPUnit n'ont pas été implémentés dans cette version du projet, privilégiant une approche manuelle plus adaptée aux contraintes de temps.

### Cas de tests et résultats

| Cas de test             | Description                                | Résultat attendu                                             | Résultat obtenu |
| ----------------------- | ------------------------------------------ | ------------------------------------------------------------ | --------------- |
| Inscription utilisateur | Création d'un nouveau compte joueur        | Compte créé avec succès, redirection vers le tableau de bord | Succès          |
| Connexion utilisateur   | Authentification avec identifiants valides | Connexion réussie, accès au tableau de bord                  | Succès          |
| Création de quiz        | Admin crée un nouveau quiz                 | Quiz ajouté à la base de données                             | Succès          |
| Ajout de question       | Admin ajoute une question avec image       | Question enregistrée avec son image                          | Succès          |
| Suppression de question | Admin supprime une question                | Question et réponses associées supprimées                    | Succès          |
| Participation à un quiz | Joueur répond aux questions d'un quiz      | Résultats affichés en fin de quiz                            | Succès          |

### Optimisations et corrections

Suite aux tests, plusieurs optimisations et corrections ont été apportées :

- Amélioration des performances de chargement des pages avec beaucoup de questions
- Correction des problèmes d'affichage sur certains appareils mobiles
- Renforcement de la sécurité contre les injections SQL et XSS
- Optimisation des requêtes à la base de données pour les quiz avec nombreuses questions

## 9. Conclusion

### Synthèse du projet

Le projet Quiz Master Dev 2025 a permis de créer une plateforme de quiz interactive et complète, répondant aux besoins des joueurs et des administrateurs. L'architecture MVC mise en place offre une base solide pour maintenir et faire évoluer l'application.

Les objectifs initiaux du projet ont été atteints :

- Une interface intuitive et conviviale a été développée
- Les fonctionnalités essentielles pour les joueurs et administrateurs sont opérationnelles
- La sécurité a été prise en compte à tous les niveaux
- L'application est responsive et s'adapte à tous les appareils

### Compétences acquises et développées

Ce projet m'a permis de développer et d'approfondir plusieurs compétences :

- Conception et développement d'une application complète avec architecture MVC
- Implémentation des fonctionnalités back-end avec PHP orienté objet
- Création d'interfaces modernes et responsives avec HTML5, CSS3/SCSS et JavaScript
- Conception et gestion de base de données relationnelles avec MySQL
- Mise en place de mesures de sécurité web (validation des entrées, échappement des sorties)

### Perspectives d'évolution du projet

Plusieurs évolutions sont envisageables pour améliorer l'application :

- Ajout d'un système de quiz en temps limité avec options de paramétrage avancées
- Implémentation d'un mode multijoueur pour jouer en temps réel contre d'autres participants
- Intégration de médias riches (vidéos, audio) dans les questions
- Extension des fonctionnalités de partage social pour inclure d'autres plateformes
- **Implémentation du CRUD complet des utilisateurs** pour permettre aux administrateurs de gérer entièrement les comptes joueurs
- **Activation du système de classification des joueurs** (tables quiz_player_class) permettant de les catégoriser en joueurs débutants, intermédiaires, experts ou professionnels selon leurs performances
- **Enrichissement des systèmes de catégorisation et de difficulté** déjà présents dans la base de données (tables quiz_question_category, quiz_question_category_details et quiz_question_difficulte) pour une meilleure organisation des contenus
- Mise en place de tests unitaires automatisés avec PHPUnit pour renforcer la fiabilité du code
- Développement d'une API REST pour applications mobiles
- Système de badges et récompenses pour motiver les joueurs
- Classements par catégorie et globaux pour stimuler l'engagement

## 10. Annexes

### Guide d'installation

Voir le fichier [README.md](../README.md) pour les instructions détaillées d'installation et de configuration.

### Guide utilisateur

Voir le fichier [USER_GUIDE.md](../documentation/USER_GUIDE.md) pour le guide complet d'utilisation de la plateforme.

### Documentation technique

La documentation technique complète est disponible dans le fichier [TECHNICAL.md](../documentation/TECHNICAL.md).

### Structure complète de la base de données

La base de données comprend non seulement les tables actuellement utilisées mais aussi des tables prévues pour les futures extensions (V2) :

**Tables principales (V1) :**

- `quiz_users` : Informations des utilisateurs
- `quiz_question` : Questions des quiz
- `quiz_question_answer` : Réponses possibles aux questions
- `quiz_game_history` : Historique des parties jouées
- `quiz_avatar` : Avatars disponibles pour les utilisateurs

**Tables pour extension future (V2) :**

- `quiz_player_class` : Classification des joueurs (débutant, intermédiaire, expert, pro)
- `quiz_question_category` : Catégories principales des questions (HTML, CSS, JavaScript, etc.)
- `quiz_question_category_details` : Sous-catégories détaillées des questions
- `quiz_question_difficulte` : Niveaux de difficulté des questions (facile, moyen, difficile)

Cette structure de base de données a été conçue pour être évolutive et permettre l'ajout de fonctionnalités sans nécessiter de modifications majeures du schéma existant.

### Références et sources

- Documentation PHP : [php.net](https://www.php.net/docs.php)
- Documentation MySQL : [dev.mysql.com](https://dev.mysql.com/doc/)
- MDN Web Docs : [developer.mozilla.org](https://developer.mozilla.org/)
