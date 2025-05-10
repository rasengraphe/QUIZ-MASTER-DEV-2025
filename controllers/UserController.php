<?php
// quiz_project/controllers/UserController.php


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
class UserController extends Controller {
    private $userModel;

    public function __construct($db) {
        parent::__construct($db);
        // TODO: Ajouter une vérification d'authentification (le joueur doit être connecté)
        /*if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit();
        }*/
        $this->userModel = new UserModel($db);
    }

    public function profile() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login"); // Rediriger si non connecté
            exit();
        }
        $user = $this->userModel->getUserById($_SESSION['user_id']);
        $this->view('user/profile', ['user' => $user]);
    }

    public function editProfile() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit();
        }
        $user = $this->userModel->getUserById($_SESSION['user_id']);
        $this->view('user/edit_profile', ['user' => $user]);
    }

    public function updateProfile() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit();
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['user_id']; // Récupérer l'ID du joueur connecté
            $data = [
                'name' => $_POST['name'],
                'first_name' => $_POST['first_name'],
                'email' => $_POST['email'],
                // Ne mettez à jour le mot de passe que s'il est fourni
                'password' => !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null,
            ];

            // Validation des données du profil
            $errors = $this->validateProfileForm($_POST);
            if (!empty($errors)) {
                $this->view('user/edit_profile', ['errors' => $errors, 'player' => $_POST]);
                return;
            }

            // Si aucun nouveau mot de passe n'est fourni, ne mettez pas à jour le mot de passe dans la base de données
            if (empty($_POST['password'])) {
                unset($data['password']); // Supprimer le mot de passe du tableau de données
            }

            $this->userModel->updateUser($userId, $data, 'player');

            header("Location: index.php?action=profile");
            exit();
        }
    }

    private function validateProfileForm($data) {
        $errors = [];

        if (empty($data['name'])) {
            $errors['name'] = "Le nom est requis.";
        }
        if (empty($data['first_name'])) {
            $errors['first_name'] = "Le prénom est requis.";
        }
        if (empty($data['email'])) {
            $errors['email'] = "L'email est requis.";
        }
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "L'email n'est pas valide.";
        }
        if (!empty($data['password']) && strlen($data['password']) < 8) {
            $errors['password'] = "Le mot de passe doit contenir au moins 8 caractères.";
        }

        return $errors;
    }

    public function register() {
        // Récupérer tous les avatars disponibles pour les afficher dans le formulaire
        $avatarModel = new AvatarModel($this->db);
        $avatars = $avatarModel->getAllAvatars();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = [];
            
            // Récupération des données du formulaire
            $name = trim($_POST['name'] ?? '');
            $firstName = trim($_POST['first_name'] ?? ''); // Modifié pour correspondre au nom du champ
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['password_confirm'] ?? ''; // Modifié pour correspondre au nom du champ
            $avatarId = isset($_POST['avatar_id']) ? intval($_POST['avatar_id']) : 1; // Avatar par défaut

            // Validation des champs
            if (empty($name)) {
                $errors['name'] = "Le nom est requis";
            }
            
            if (empty($firstName)) {
                $errors['first_name'] = "Le prénom est requis";
            }
            
            if (empty($email)) {
                $errors['email'] = "L'email est requis";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = "Format d'email invalide";
            } else {
                // Vérifier si l'email existe déjà dans la table unifiée
                $stmt = $this->db->prepare("SELECT COUNT(*) FROM quiz_users WHERE email = ?");
                $stmt->execute([$email]);
                if ($stmt->fetchColumn() > 0) {
                    $errors['email'] = "Cet email est déjà utilisé";
                }
            }
            
            if (empty($password)) {
                $errors['password'] = "Le mot de passe est requis";
            } elseif (strlen($password) < 6) {
                $errors['password'] = "Le mot de passe doit contenir au moins 6 caractères";
            }
            
            if ($password !== $confirmPassword) {
                $errors['password_confirm'] = "Les mots de passe ne correspondent pas";
            }
            
            // Si pas d'erreurs, créer l'utilisateur
            if (empty($errors)) {
                // Hachage du mot de passe
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                
                // Insérer l'utilisateur avec l'avatar dans la nouvelle table unifiée
                $stmt = $this->db->prepare("INSERT INTO quiz_users (name, first_name, email, password, Id_Avatar, role, date_creation) VALUES (?, ?, ?, ?, ?, 'player', NOW())");
                $result = $stmt->execute([$name, $firstName, $email, $hashedPassword, $avatarId]);
                
                if ($result) {
                    $userId = $this->db->lastInsertId();
                                        
                    // Connexion automatique de l'utilisateur après inscription
                    session_regenerate_id(true);
                    $_SESSION['user_id'] = $userId;
                    $_SESSION['user_name'] = $name;
                    $_SESSION['user_role'] = 'player';
                    $_SESSION['user_type'] = 'player';
                    $_SESSION['player_logged_in'] = true;
                    
                    // Message de bienvenue
                    $_SESSION['success_message'] = "Bienvenue $firstName ! Votre compte a été créé avec succès.";
                    
                    // Redirection vers le tableau de bord joueur
                    header('Location: index.php?action=player_dashboard');
                    exit;
                } else {
                    $errors['db'] = "Erreur lors de la création du compte";
                }
            }
            
            // Si on arrive ici, c'est qu'il y a des erreurs
            // On réaffiche le formulaire avec les erreurs
        }
        
        // Afficher la page d'inscription avec les avatars
        include 'views/user/register.php';
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupérer les données POST
            $email = $_POST['email'] ?? '';
            $name = $_POST['name'] ?? '';
            $password = $_POST['password'] ?? '';
            
            // Pour le débogage
            error_log("Tentative de connexion - Email: $email, Nom: $name");
            
            // Validation de base
            $errors = [];
            
            if (empty($email) || empty($name) || empty($password)) {
                $errors['login'] = "Tous les champs sont requis";
            }
            
            if (empty($errors)) {
                $userModel = new UserModel($this->db);
                
                // Utiliser la nouvelle table unifiée quiz_users pour l'authentification
                $user = $userModel->getUserByEmailAndName($email, $name);
                
                if ($user) {
                    error_log("Utilisateur trouvé: " . print_r($user, true));
                    
                    // Vérifier le mot de passe (gère à la fois MD5 et bcrypt)
                    $passwordValid = false;
                    
                    if (strpos($user['password'], '$2y$') === 0) {
                        // Hash bcrypt
                        $passwordValid = password_verify($password, $user['password']);
                    } else {
                        // Hash MD5 (ancien format)
                        $passwordValid = (md5($password) === $user['password']);
                    }
                    
                    if ($passwordValid) {
                        // Authentification réussie
                        session_regenerate_id(true);
                        
                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['user_name'] = $user['name'];
                        $_SESSION['user_role'] = $user['role'];
                        $_SESSION['user_type'] = $user['role'] === 'player' ? 'player' : 'admin';

                        if ($user['role'] === 'player') {
                            $_SESSION['player_logged_in'] = true;
                        } else {
                        $_SESSION['admin_logged_in'] = true;
}
                        
                        // Vérifier s'il y a une redirection en attente
                        if (isset($_SESSION['redirect_after_login'])) {
                            $redirect = $_SESSION['redirect_after_login'];
                            unset($_SESSION['redirect_after_login']);
                            header('Location: ' . $redirect);
                            exit;
                        }
                        
                        // Sinon, rediriger vers le tableau de bord approprié
                        if ($user['role'] === 'player') {
                            header('Location: index.php?action=player_dashboard');
                        } else {
                        header('Location: index.php?action=admin_dashboard');
                        }
                        exit;
                    }
                }
                
                // Si on arrive ici, c'est que l'authentification a échoué
                $errors['login'] = "Identifiants incorrects";
                error_log("Échec de l'authentification - Aucun utilisateur trouvé ou mot de passe incorrect");
            }
            
            // S'il y a des erreurs, réafficher le formulaire
            include 'views/user/login.php';
        } else {
            // Afficher le formulaire de connexion
            include 'views/user/login.php';
        }
    }

    /**
     * Déconnecte l'utilisateur
     */
    public function logout() {
        // Détruire la session
        session_start();
        session_unset();
        session_destroy();
        
        // Rediriger vers la page d'accueil
        header('Location: index.php?action=home');
        exit();
    }

    public function changePassword() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit();
        }
        $this->view('user/change_password');
    }

    public function updatePassword() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit();
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['user_id'];
            $oldPassword = $_POST['old_password'];
            $newPassword = $_POST['new_password'];
            $confirmPassword = $_POST['confirm_password'];

            // Validation (à compléter)
            $errors = $this->validatePasswordChangeForm($_POST);
            if (!empty($errors)) {
                $this->view('user/change_password', ['errors' => $errors]);
                return;
            }

            $user = $this->userModel->getUserById($userId);
            if (!$user || !password_verify($oldPassword, $user['password'])) {
                $errors['old_password'] = "Mot de passe actuel incorrect.";
                $this->view('user/change_password', ['errors' => $errors]);
                return;
            }

            $this->userModel->updateUserPassword($userId, $newPassword);
            header("Location: index.php?action=profile");
            exit();
        }
    }

    // --- Validation Methods ---
    private function validateRegistrationForm($data) {
        $errors = [];

        if (empty($data['name'])) {
            $errors['name'] = "Le nom est requis.";
        }
        if (empty($data['first_name'])) {
            $errors['first_name'] = "Le prénom est requis.";
        }
        if (empty($data['email'])) {
            $errors['email'] = "L'email est requis.";
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "L'email n'est pas valide.";
        }
        if (empty($data['password'])) {
            $errors['password'] = "Le mot de passe est requis.";
        } elseif (strlen($data['password']) < 8) {
            $errors['password'] = "Le mot de passe doit contenir au moins 8 caractères.";
        }
        if (empty($data['avatar_id'])) {
            $errors['avatar_id'] = "L'avatar est requis.";
        }

        return $errors;
    }

    private function validateLoginForm($data) {
        $errors = [];

        if (empty($data['email'])) {
            $errors['email'] = "L'email est requis.";
        }
        if (empty($data['password'])) {
            $errors['password'] = "Le mot de passe est requis.";
        }

        return $errors;
    }

    private function validateUpdateProfileForm($data) {
        $errors = [];

        if (empty($data['name'])) {
            $errors['name'] = "Le nom est requis.";
        }
        if (empty($data['first_name'])) {
            $errors['first_name'] = "Le prénom est requis.";
        }
        if (empty($data['email'])) {
            $errors['email'] = "L'email est requis.";
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "L'email n'est pas valide.";
        }
        if (empty($data['avatar_id'])) {
            $errors['avatar_id'] = "L'avatar est requis.";
        }

        return $errors;
    }

    private function validatePasswordChangeForm($data) {
        $errors = [];

        if (empty($data['old_password'])) {
            $errors['old_password'] = "Le mot de passe actuel est requis.";
        }
        if (empty($data['new_password'])) {
            $errors['new_password'] = "Le nouveau mot de passe est requis.";
        } elseif (strlen($data['new_password']) < 8) {
            $errors['new_password'] = "Le nouveau mot de passe doit contenir au moins 8 caractères.";
        }
        if (empty($data['confirm_password'])) {
            $errors['confirm_password'] = "La confirmation du nouveau mot de passe est requise.";
        } elseif ($data['new_password'] !== $data['confirm_password']) {
            $errors['confirm_password'] = "Les nouveaux mots de passe ne correspondent pas.";
        }

        return $errors;
    }

    // --- Security Methods ---
    private function regenerateSessionId() {
        if (function_exists('session_regenerate_id')) {
            session_regenerate_id(true);
        } else {
            // Fallback pour les anciennes versions de PHP
            session_id(uniqid());
        }
    }

    /**
     * Récupère les statistiques de jeu d'un joueur
     * @param int $playerId ID du joueur
     * @return array Statistiques du joueur
     */
    private function getPlayerStats($playerId) {
        try {
            error_log("Debug getPlayerStats: Début de la récupération des stats pour ID=$playerId");
            
            // Requête pour obtenir le nombre total de parties, le score total et la moyenne
            $stmt = $this->db->prepare("
                SELECT 
                    COUNT(*) as total_games,
                    COALESCE(SUM(score), 0) as total_score,
                    COALESCE(AVG(score), 0) as avg_score,
                    COALESCE(MAX(score), 0) as best_score
                FROM quiz_game_history 
                WHERE player_id = ?
            ");
            $stmt->execute([$playerId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            error_log("Debug getPlayerStats: Résultat brut - " . print_r($result, true));
            
            // Si le joueur n'a jamais joué, on renvoie des valeurs par défaut
            if (!$result || $result['total_games'] == 0) {
                $stats = [
                    'total_games' => 0,
                    'total_score' => 0,
                    'avg_score' => 0,
                    'best_score' => 0
                ];
            } else {
                // Arrondir la moyenne pour l'affichage
                $stats = [
                    'total_games' => (int)$result['total_games'],
                    'total_score' => (int)$result['total_score'],
                    'avg_score' => round($result['avg_score'], 1),
                    'best_score' => (int)$result['best_score']
                ];
            }
            
            error_log("Debug getPlayerStats: Stats finales - " . print_r($stats, true));
            return $stats;
            
        } catch (PDOException $e) {
            error_log("Debug getPlayerStats: Erreur PDO - " . $e->getMessage());
            return [
                'total_games' => 0,
                'total_score' => 0,
                'avg_score' => 0,
                'best_score' => 0
            ];
        }
    }

    /**
     * Récupère les informations d'un joueur par son ID
     * @param int $playerId ID du joueur
     * @return array Informations du joueur
     */
    private function getPlayerById($playerId) {
        try {
            error_log("Debug getPlayerById: Début de la récupération du joueur ID=$playerId");
            
            $stmt = $this->db->prepare("
                SELECT * FROM quiz_users 
                WHERE id = ? AND role = 'player'
            ");
            $stmt->execute([$playerId]);
            $player = $stmt->fetch(PDO::FETCH_ASSOC);
            
            error_log("Debug getPlayerById: Résultat de la requête - " . ($player ? "Joueur trouvé" : "Joueur non trouvé"));
            if ($player) {
                error_log("Debug getPlayerById: Données du joueur - " . print_r($player, true));
            }
            
            return $player;
        } catch (PDOException $e) {
            error_log("Debug getPlayerById: Erreur PDO - " . $e->getMessage());
            error_log("Debug getPlayerById: Requête échouée pour ID=$playerId");
            return null;
        }
    }

    /**
     * Récupère l'historique récent des parties d'un joueur
     * @param int $playerId ID du joueur
     * @return array Historique des parties
     */
    private function getPlayerRecentGames($playerId) {
        try {
            $stmt = $this->db->prepare("
                SELECT gh.*, q.text as question_text
                FROM quiz_game_history gh
                LEFT JOIN quiz_question q ON gh.quiz_id = q.Id_question
                WHERE gh.player_id = ?
                ORDER BY gh.played_on DESC
                LIMIT 5
            ");
            $stmt->execute([$playerId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération de l'historique des parties: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Affiche le tableau de bord du joueur
     */
    public function playerDashboard() {
        // Vérifier si l'utilisateur est connecté en tant que joueur
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'player') {
            header('Location: index.php?action=login');
            exit();
        }

        $playerId = $_SESSION['user_id'];
        
        // Récupérer les informations du joueur
        $playerInfo = $this->getPlayerById($playerId);
        
        // Récupérer les statistiques du joueur
        $stats = $this->getPlayerStats($playerId);
        
        // Récupérer l'historique récent des parties
        $recentGames = $this->getPlayerRecentGames($playerId);
        
        // Afficher le tableau de bord
        $this->view('user/player_dashboard', [
            'player' => $playerInfo,
            'stats' => $stats,
            'recentGames' => $recentGames
        ]);
    }

    public function history() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit();
        }
        $Id_player = $_SESSION['user_id'];
        $history = $this->userModel->getQuizHistoryForPlayer($Id_player);
        $this->view('user/history', ['history' => $history]);
    }

    /**
     * Affiche le tableau de bord de l'administrateur
     */
    public function adminDashboard() {
        // Vérifier si l'utilisateur est connecté et est un administrateur
        if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], ['admin','super_admin'])) {
            header('Location: index.php?action=login');
            exit();
        }
        
        $adminId = $_SESSION['user_id'];
        
        // Récupérer les informations de l'administrateur depuis la table unifiée
        $admin = $this->userModel->getUserById($adminId);
        
        // Récupérer les statistiques de la plateforme
        $stats = [
            'total_players' => $this->userModel->getTotalPlayerCount(),
            'total_quizzes' => $this->userModel->getTotalQuizzesCount(),
            'total_questions' => $this->userModel->getTotalQuestionsCount(),
            'total_games' => $this->userModel->getTotalGamesCount()
        ];
        
        // Récupérer les dernières activités
        $recent_activities = $this->userModel->getRecentActivities(10); // 10 dernières activités
        
        // Afficher la vue du tableau de bord admin
        include('views/admin/admin_dashboard.php');
    }

    // ... (Autres méthodes)
}
?>
