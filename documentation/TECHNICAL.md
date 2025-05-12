# Documentation Technique - Quiz Master Dev 2025

## Architecture du projet

Quiz Master Dev utilise une architecture MVC (Modèle-Vue-Contrôleur) personnalisée pour séparer les responsabilités et assurer une maintenance efficace :

- **Modèles** : Gestion des données et interaction avec la base de données
- **Vues** : Présentation des interfaces utilisateur
- **Contrôleurs** : Traitement des requêtes et coordination

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
│   ├── layout/              # Templates partagés
│   └── user/                # Pages utilisateur
├── documentation/           # Documentation du projet
└── index.php                # Point d'entrée
```

## Système de routage

Le système de routage est centralisé dans le fichier `index.php`. Il gère toutes les requêtes entrantes et les dirige vers les contrôleurs appropriés :

```php
$action = $_GET['action'] ?? 'home';

switch ($action) {
    case 'login':
        $controller = new UserController($db);
        $controller->login();
        break;
    // Autres routes...
    default:
        include 'views/home.php';
        break;
}
```

## Base de données

### Schéma

La base de données MySQL contient les tables suivantes :

#### Tables principales (V1)
- `quiz_users` : Informations des utilisateurs
- `quiz_question` : Questions des quiz
- `quiz_question_answer` : Réponses possibles aux questions
- `quiz_game_history` : Historique des parties jouées
- `quiz_avatar` : Avatars disponibles pour les utilisateurs

#### Tables pour extension future (V2)
- `quiz_player_class` : Classification des joueurs
- `quiz_question_category` : Catégories principales des questions
- `quiz_question_category_details` : Sous-catégories détaillées
- `quiz_question_difficulte` : Niveaux de difficulté des questions

### Connexion à la base de données

Le fichier `config/database.php` gère la connexion à la base de données :

```php
$db_host = "localhost";
$db_name = "quiz_master";
$db_user = "root";
$db_pass = "";

try {
    $db = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_pass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
```

## Authentification et sécurité

### Authentification

Le système d'authentification utilise les sessions PHP. Le mot de passe est haché avec la fonction `password_hash()` :

```php
// Enregistrement d'un utilisateur
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Vérification lors de la connexion
if (password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_role'] = $user['role'];
}
```

### Sécurité

Les mesures de sécurité implémentées :

- Utilisation de requêtes préparées pour éviter les injections SQL
- Échappement des sorties HTML pour prévenir les attaques XSS
- Validation des entrées utilisateur
- Gestion des permissions et rôles utilisateur
- Protection contre les attaques CSRF

## Front-end

### SCSS / CSS

Le préprocesseur SCSS est utilisé pour organiser les styles de manière modulaire :

- `_variables.scss` : Variables globales (couleurs, typographie)
- `_layout.scss` : Structure générale des pages
- `_form.scss` : Styles des formulaires
- `_question_form.scss` : Styles spécifiques aux formulaires de questions
- `_home.scss` : Styles de la page d'accueil

Pour compiler les fichiers SCSS en CSS, un script de compilation est utilisé.

### JavaScript

Le JavaScript est organisé en modules :

- `quiz.js` : Gestion des quiz et questions
- `form-validation.js` : Validation des formulaires
- `user-profile.js` : Fonctionnalités du profil utilisateur

### API Web Share

L'API Web Share est utilisée pour permettre le partage des résultats :

```javascript
if (navigator.share) {
  navigator.share({
    title: 'Mon résultat au quiz',
    text: `J'ai obtenu ${score} points au quiz "${quizTitle}" sur Quiz Master Dev!`,
    url: window.location.href,
  })
  .then(() => console.log('Partage réussi'))
  .catch((error) => console.log('Erreur de partage', error));
}
```

## Modèles principaux

### UserModel

Gère les utilisateurs (inscription, connexion, profil).

### QuizModel

Gère les quiz (création, modification, récupération).

### QuestionModel

Gère les questions et leurs réponses.

### PlayerModel

Gère les fonctionnalités spécifiques aux joueurs.

## Contrôleurs principaux

### UserController

Gère l'authentification et les profils utilisateurs.

### AdminController

Gère les fonctionnalités d'administration.

### QuizController

Gère le déroulement des quiz, les questions et les résultats.

## Gestion des fichiers

Les images téléchargées pour les questions sont stockées dans le dossier `uploads/questions/` :

```php
$uploadDir = 'uploads/questions/';
$uniqueName = uniqid('quest_') . '.' . $extension;
$uploadFilePath = $uploadDir . $uniqueName;

if (move_uploaded_file($_FILES['picture']['tmp_name'], $uploadFilePath)) {
    $question['picture'] = $uploadFilePath;
}
```

## Dépendances externes

- Bootstrap 4.5 : Framework CSS
- jQuery 3.5 : Bibliothèque JavaScript
- FontAwesome : Bibliothèque d'icônes

## Déploiement

Pour plus d'informations sur le déploiement, consultez le [Guide de déploiement](deploiement.md).
