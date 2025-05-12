# Guide de préparation des images pour la documentation

Ce guide vous aide à créer et préparer toutes les images requises pour la documentation du projet Quiz Master Dev 2025. Les images doivent être placées dans le dossier `c:\wamp64\www\QUIZ-MASTER-DEV-2025\documentation\images\`.

## 1. user_flow.jpg - Parcours utilisateur

**Description** : Schéma montrant le parcours d'un utilisateur du quiz.

**Comment créer** :

1. Utilisez [draw.io](https://app.diagrams.net/) ou [Figma](https://www.figma.com/)
2. Créez un schéma simple avec des rectangles connectés par des flèches
3. Incluez ces étapes :
   - Page d'accueil
   - Inscription/Connexion
   - Tableau de bord utilisateur
   - Sélection du quiz
   - Questions du quiz
   - Affichage des résultats
   - Partage des résultats

**Dimension recommandée** : 1200×600 pixels

## 2. use_cases.jpg - Diagramme de cas d'utilisation

**Description** : Diagramme UML montrant tous les cas d'utilisation du système.

**Comment créer** :

1. Utilisez [draw.io](https://app.diagrams.net/) ou [StarUML](http://staruml.io/)
2. Créez un diagramme avec deux acteurs principaux : Joueur et Administrateur
3. Pour le Joueur, ajoutez les cas d'utilisation :
   - S'inscrire
   - Se connecter
   - Jouer à un quiz
   - Consulter ses résultats
   - Modifier son profil
   - Partager ses résultats
4. Pour l'Administrateur, ajoutez :
   - Gérer les questions
   - Gérer les quiz
   - Gérer les utilisateurs
   - Consulter les statistiques

**Dimension recommandée** : 1200×800 pixels

## 3. mvc_architecture.jpg - Architecture MVC

**Description** : Schéma de l'architecture MVC de votre application.

**Comment créer** :

1. Utilisez [draw.io](https://app.diagrams.net/)
2. Créez trois boîtes principales : Modèle, Vue, Contrôleur
3. Ajoutez la base de données connectée au Modèle
4. Ajoutez le navigateur utilisateur connecté à la Vue
5. Montrez le flux de données entre les composants avec des flèches
6. Ajoutez une brève description de chaque composant

**Dimension recommandée** : 1000×700 pixels

## 4. database_schema.jpg - Schéma de base de données

**Description** : Modèle conceptuel ou physique de votre base de données.

**Comment créer** :

1. Utilisez [MySQL Workbench](https://www.mysql.com/products/workbench/) ou [dbdiagram.io](https://dbdiagram.io/)
2. Créez un schéma incluant toutes vos tables :
   - quiz_users
   - quiz_avatar
   - quiz_question
   - quiz_question_answer
   - quiz_question_category
   - quiz_question_difficulte
   - quiz
   - quiz_questions
   - quiz_game_history
3. Montrez clairement les relations et les clés étrangères entre les tables

**Dimension recommandée** : 1500×1000 pixels

## 5. wireframes.jpg - Maquettes filaires

**Description** : Images de vos maquettes Figma.

**Comment créer** :

1. Exportez vos maquettes depuis Figma
2. Incluez au moins 3-4 écrans clés :
   - Page d'accueil
   - Page de quiz
   - Tableau de bord utilisateur
   - Interface administrateur
3. Combinez les captures dans une seule image avec un logiciel comme Photoshop ou [Canva](https://www.canva.com/)

**Dimension recommandée** : 1600×900 pixels

## 6. homepage_screenshot.jpg - Capture d'écran de la page d'accueil

**Description** : Capture d'écran de la page d'accueil de votre application.

**Comment créer** :

1. Ouvrez votre application dans un navigateur
2. Utilisez l'outil de capture d'écran de votre système d'exploitation ou une extension comme [FireShot](https://getfireshot.com/)
3. Faites une capture de la page d'accueil complète
4. Assurez-vous que l'image est nette et montre l'interface à son avantage

**Dimension recommandée** : 1200×800 pixels

## 7. gantt_chart.jpg - Diagramme de Gantt

**Description** : Diagramme de Gantt montrant votre planning de projet.

**Comment créer** :

1. Utilisez Microsoft Excel, [TeamGantt](https://www.teamgantt.com/) ou [GanttProject](https://www.ganttproject.biz/)
2. Créez un diagramme incluant toutes les phases du projet :
   - Analyse des besoins (15-21 février)
   - Définition de l'environnement technique (22-28 février)
   - Conception des maquettes (29 février-6 mars)
   - Conception MCD/MLD (7-20 mars)
   - Développement (21 mars-5 mai)
   - Tests et débogage (6-12 mai)
3. Utilisez des couleurs différentes pour chaque phase
4. Incluez les jalons importants

**Dimension recommandée** : 1400×600 pixels

## Conseils généraux

- Utilisez une résolution suffisante pour que tous les détails soient lisibles
- Exportez toutes les images en JPG pour réduire la taille des fichiers
- Utilisez un nommage cohérent comme indiqué ci-dessus
- Placez toutes les images dans le dossier `c:\wamp64\www\QUIZ-MASTER-DEV-2025\documentation\images\`
- Créez ce dossier s'il n'existe pas encore :
  ```
  mkdir -p c:\wamp64\www\QUIZ-MASTER-DEV-2025\documentation\images
  ```
