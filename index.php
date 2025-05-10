<?php
session_start(); // Démarre les sessions

// Configuration des erreurs (à activer pour déboguer)
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error.log');

// Inclure la configuration de la base de données
require_once 'config/database.php';

// Autoloading des classes
function chargerClasse($classe) {
    $chemins = ['controllers/', 'models/', 'core/'];
    foreach ($chemins as $chemin) {
        $fichier = $chemin . $classe . '.php';
        if (file_exists($fichier)) {
            require_once $fichier;
            return;
        }
    }
}
spl_autoload_register('chargerClasse');

// Récupération de l'action depuis l'URL
$action = $_GET['action'] ?? 'home';

// Router
switch ($action) {
    // Routes utilisateur
    case 'register':
        $controller = new UserController($db);
        $controller->register();
        break;
    case 'login':
        $controller = new UserController($db);
        $controller->login();
        break;
    case 'logout':
        $controller = new UserController($db);
        $controller->logout();
        break;
    case 'player_dashboard':
        $controller = new UserController($db);
        $controller->playerDashboard();
        break;
    case 'edit_profile':
        $controller = new PlayerController($db);
        $controller->editProfile();
        break;
    case 'updateProfile':
        $controller = new PlayerController($db);
        $controller->updateProfile();
        break;
    
    // Routes quiz
    case 'quiz':
        $controller = new QuizController($db);
        $controller->index();
        break;
    case 'select_quiz':
        $controller = new QuizController($db);
        $controller->select();
        break;
    case 'submit_quiz':
        $controller = new QuizController($db);
        $controller->submitQuiz();
        break;
    case 'show_results':
        $controller = new QuizController($db);
        $controller->showResults();
        break;
    case 'handle_play_quiz':
        $controller = new QuizController($db);
        $controller->handlePlayQuiz();
        break;
    case 'play_quiz':
        $controller = new QuizController($db);
        if (isset($_GET['id'])) {
            $controller->play($_GET['id']);
        } else {
            // Si aucun ID n'est fourni, rediriger vers la sélection de quiz
            $controller->select();
        }
        break;
    case 'next_question':
        $controller = new QuizController($db);
        $controller->nextQuestion();
        break;
    
    // Routes admin - Questions
    case 'admin':
    case 'admin_dashboard':
        $controller = new AdminController($db);
        $controller->dashboard();
        break;
    case 'questions':
    case 'questionList': // garde pour compatibilité
        $controller = new AdminController($db);
        $controller->indexQuestions();
        break;
    case 'addQuestion':
    case 'createQuestion': // nouveau nom standardisé
        $controller = new AdminController($db);
        $controller->createQuestion();
        break;
    case 'saveQuestion':
    case 'storeQuestion': // nouveau nom standardisé
        $controller = new AdminController($db);
        $controller->storeQuestion();
        break;
    case 'editQuestion':
    case 'edit_question': // harmonisé mais conservé pour compatibilité
        $controller = new AdminController($db);
        $controller->editQuestion();
        break;
    case 'updateQuestion':
        $controller = new AdminController($db);
        $controller->updateQuestion();
        break;
    case 'deleteQuestion':
        $controller = new AdminController($db);
        $controller->deleteQuestion();
        break;

    // Ajouter cette route pour le bouton "Supprimer une question" du dashboard admin
    case 'delete_question':
        // Rediriger vers la liste des questions
        header('Location: index.php?action=questions');
        exit();

    // Routes admin - Quiz
    case 'quizzes':
    case 'quizList': // garde pour compatibilité
        $controller = new AdminController($db);
        $controller->indexQuizzes();
        break;
    case 'createQuiz':
    case 'addQuiz': // garde pour compatibilité
        $controller = new AdminController($db);
        $controller->createQuiz();
        break;
    case 'editQuiz':
        $controller = new AdminController($db);
        $controller->editQuiz();
        break;
    case 'storeQuiz':
    case 'saveQuiz': // garde pour compatibilité
        $controller = new AdminController($db);
        $controller->storeQuiz();
        break;
    case 'deleteQuiz':
        $controller = new AdminController($db);
        $controller->deleteQuiz();
        break;
    
    // Routes admin - Catégories
    case 'categories':
    case 'categoryList': // garde pour compatibilité
        $controller = new AdminController($db);
        $controller->indexCategories();
        break;
    case 'createCategory':
    case 'addCategory': // garde pour compatibilité
        $controller = new AdminController($db);
        $controller->createCategory();
        break;
    case 'editCategory':
        $controller = new AdminController($db);
        $controller->editCategory();
        break;
    case 'storeCategory':
    case 'saveCategory': // garde pour compatibilité
        $controller = new AdminController($db);
        $controller->storeCategory();
        break;
    case 'deleteCategory':
        $controller = new AdminController($db);
        $controller->deleteCategory();
        break;
    
    // Routes admin - Difficultés
    case 'difficulties':
    case 'difficultyList': // garde pour compatibilité
        $controller = new AdminController($db);
        $controller->indexDifficulties();
        break;
    case 'createDifficulty':
    case 'addDifficulty': // garde pour compatibilité
        $controller = new AdminController($db);
        $controller->createDifficulty();
        break;
    case 'editDifficulty':
        $controller = new AdminController($db);
        $controller->editDifficulty();
        break;
    case 'storeDifficulty':
    case 'saveDifficulty': // garde pour compatibilité
        $controller = new AdminController($db);
        $controller->storeDifficulty();
        break;
    case 'deleteDifficulty':
        $controller = new AdminController($db);
        $controller->deleteDifficulty();
        break;
    
    // Routes admin - Utilisateurs
    case 'users':
    case 'userList': // garde pour compatibilité
        $controller = new AdminController($db);
        $controller->indexUsers();
        break;
    case 'createUser':
    case 'addUser': // garde pour compatibilité
        $controller = new AdminController($db);
        $controller->createUser();
        break;
    case 'editUser':
        $controller = new AdminController($db);
        $controller->editUser();
        break;
    case 'storeUser':
    case 'saveUser': // garde pour compatibilité
        $controller = new AdminController($db);
        $controller->storeUser();
        break;
    case 'deleteUser':
        $controller = new AdminController($db);
        $controller->deleteUser();
        break;
    
    case 'clearSession':
        $controller = new AdminController($db);
        $controller->clearSession();
        break;
    
    // Page d'accueil par défaut
    case 'home':
    default:
        include 'views/home.php';
        break;
}
?>
