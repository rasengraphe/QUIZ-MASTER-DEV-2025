# Documentation Technique QUIZ-MASTER-DEV-2025

Ce document détaille les aspects techniques du projet pour les développeurs qui souhaitent comprendre son fonctionnement interne ou y contribuer.

## Architecture MVC

Le projet suit le modèle d'architecture Modèle-Vue-Contrôleur (MVC) :

### Modèle

Les modèles sont situés dans le dossier `models/` et héritent de la classe de base `Model` qui fournit les méthodes fondamentales d'accès à la base de données:

```php
// core/Model.php
class Model {
    protected $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Méthodes auxiliaires pour les requêtes
    protected function executeQuery($sql, $params = []) {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
}
```

Modèles principaux :

- `QuestionModel` : Gestion des questions et réponses
- `QuizModel` : Gestion des quiz
- `UserModel` : Gestion des utilisateurs
- `PlayerModel` : Fonctionnalités spécifiques aux joueurs
- `AvatarModel` : Gestion des avatars

### Contrôleur

Les contrôleurs sont dans le dossier `controllers/` et héritent de la classe `Controller` qui fournit les méthodes communes :

```php
// core/Controller.php
class Controller {
    protected $db;

    public function __construct($db) {
        $this->db = $db;
    }

    protected function view($viewPath, $data = []) {
        extract($data);
        include("views/{$viewPath}.php");
    }
}
```

Contrôleurs principaux :

- `AdminController` : Gestion administrative
- `UserController` : Authentification et profil utilisateur
- `QuizController` : Déroulement des quiz
- `PlayerController` : Fonctionnalités des joueurs

### Vue

Les vues se trouvent dans le dossier `views/` et sont organisées selon leur contexte d'utilisation.

## Routage

Le système de routage est implémenté dans le fichier `index.php` à la racine du projet. Il s'agit d'un routage simple basé sur des paramètres GET :

```php
$action = $_GET['action'] ?? 'home';

switch ($action) {
    case 'login':
        $controller = new UserController($db);
        $controller->login();
        break;
    // autres routes...
}
```

## Système d'authentification

L'authentification utilise les sessions PHP et vérifie les rôles utilisateur à chaque action sensible :

```php
// Exemple de vérification dans AdminController
private function ensureAdminAccess() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: index.php?action=login");
        exit();
    }

    $isAdmin = in_array($_SESSION['user_role'], ['admin', 'super_admin']);

    if (!$isAdmin) {
        $_SESSION['error'] = "Accès refusé. Vous devez être administrateur.";
        header("Location: index.php?action=login");
        exit();
    }
}
```

## Gestion des formulaires

Les formulaires suivent un cycle classique :

1. Affichage initial du formulaire
2. Soumission par l'utilisateur (POST)
3. Validation des données côté serveur
4. En cas d'erreur : réaffichage du formulaire avec messages d'erreur
5. En cas de succès : traitement et redirection

## Structure de la base de données détaillée

### Table quiz_users

| Colonne       | Type         | Description                       |
| ------------- | ------------ | --------------------------------- |
| id            | INT          | Identifiant unique (clé primaire) |
| name          | VARCHAR(50)  | Nom de l'utilisateur              |
| first_name    | VARCHAR(50)  | Prénom de l'utilisateur           |
| email         | VARCHAR(100) | Email (utilisé pour la connexion) |
| password      | VARCHAR(255) | Mot de passe hashé                |
| role          | VARCHAR(20)  | Rôle (player, admin, super_admin) |
| Id_Avatar     | INT          | Référence vers l'avatar choisi    |
| date_creation | DATETIME     | Date de création du compte        |
| score         | INT          | Score total du joueur             |

### Table quiz_question

| Colonne                | Type         | Description                              |
| ---------------------- | ------------ | ---------------------------------------- |
| Id_question            | INT          | Identifiant unique (clé primaire)        |
| Id_question_category   | INT          | Catégorie de la question (clé étrangère) |
| Id_admin_editor        | INT          | Admin qui a créé/édité la question       |
| Id_question_difficulte | INT          | Niveau de difficulté (clé étrangère)     |
| text                   | TEXT         | Texte de la question                     |
| picture                | VARCHAR(255) | Chemin de l'image (optionnel)            |
| date_creation          | DATETIME     | Date de création                         |

### Table quiz_question_answer

| Colonne            | Type    | Description                       |
| ------------------ | ------- | --------------------------------- |
| Id_question_answer | INT     | Identifiant unique (clé primaire) |
| Id_question        | INT     | Question associée (clé étrangère) |
| text               | TEXT    | Texte de la réponse               |
| correct            | BOOLEAN | Indique si c'est la bonne réponse |

## Gestion des fichiers

Les images téléchargées pour les questions sont stockées dans le dossier `uploads/questions/` avec un nom unique généré :

```php
// Exemple de traitement d'upload d'image
$uploadDir = realpath(dirname(__FILE__) . '/../uploads/questions') . '/';
$uniqueName = uniqid('quest_') . '.' . $extension;
$uploadFilePath = $uploadDir . $uniqueName;

if (move_uploaded_file($_FILES['picture_file']['tmp_name'], $uploadFilePath)) {
    $picture = 'uploads/questions/' . $uniqueName;
}
```

## Styles et responsivité

Le projet utilise SCSS pour une meilleure organisation du code CSS :

- Les fichiers sources sont dans `public/scss/`
- Ils sont compilés en CSS dans `public/css/`
- Le design est responsive grâce à des media queries

Structure SCSS :

- `_variables.scss` : Variables globales (couleurs, typographie)
- `_layout.scss` : Structure de page et grille
- `_form.scss` : Styles des formulaires
- `_question_form.scss` : Styles spécifiques aux formulaires de questions
- Autres fichiers spécifiques...

## Sécurité

### Prévention contre les injections SQL

Toutes les requêtes utilisent des requêtes préparées :

```php
$stmt = $this->db->prepare("SELECT * FROM quiz_users WHERE email = ?");
$stmt->execute([$email]);
```

### Protection contre XSS

Toutes les données affichées sont échappées :

```php
echo htmlspecialchars($variable);
```

### Hachage des mots de passe

Les mots de passe sont sécurisés avec `password_hash()` :

```php
$hash = password_hash($password, PASSWORD_DEFAULT);
```

### Validation des formulaires

Double validation côté client et serveur pour tous les formulaires.

## Tests et débogage

Le projet inclut des outils de débogage :

- Logs d'erreur détaillés
- Mode développement avec affichage des erreurs
- Commentaires pour le code complexe

## Optimisations

Plusieurs optimisations sont appliquées :

- Mise en cache des requêtes répétitives
- Chargement conditionnel des ressources
- Minification des fichiers CSS et JavaScript

## Compatibilité navigateurs

Le projet est testé et compatible avec :

- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+
- Opera 76+
