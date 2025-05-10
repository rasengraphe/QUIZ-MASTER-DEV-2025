<?php
class PlayerController {
    private $db;
    private $playerModel;
    
    public function __construct($db) {
        $this->db = $db;
        $this->playerModel = new PlayerModel($db);
    }
    
    // Méthode pour le rendu des vues
    protected function view($viewPath, $data = []) {
        extract($data);
        include("views/{$viewPath}.php");
    }
    
    // Méthode pour afficher le formulaire d'édition de profil
    public function editProfile() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit();
        }
        
        $playerId = $_SESSION['user_id'];
        $player = $this->playerModel->getPlayerById($playerId);
        
        // Récupérer tous les avatars pour les afficher dans le formulaire
        $avatarModel = new AvatarModel($this->db);
        $avatars = $avatarModel->getAllAvatars();
        
        $this->view('user/edit_profile', [
            'player' => $player, 
            'avatars' => $avatars
        ]);
    }
    
    // Méthode pour mettre à jour le profil
    public function updateProfile() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $playerId = $_SESSION['user_id'];
            $data = [
                'name' => $_POST['name'],
                'first_name' => $_POST['first_name'],
                'email' => $_POST['email'],
                'Id_Avatar' => $_POST['avatar_id'] // Ajout de l'ID d'avatar
            ];
            
            // Si un mot de passe est fourni, le mettre à jour
            if (!empty($_POST['password'])) {
                $data['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
            }
            
            // Validation des données
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
            
            if (!empty($errors)) {
                // Récupérer tous les avatars pour les afficher dans le formulaire
                $avatarModel = new AvatarModel($this->db);
                $avatars = $avatarModel->getAllAvatars();
                
                $this->view('user/edit_profile', [
                    'player' => $data,
                    'errors' => $errors,
                    'avatars' => $avatars
                ]);
                return;
            }
            
            if ($this->playerModel->updatePlayer($playerId, $data)) {
                $_SESSION['success'] = "Profil mis à jour avec succès.";
                header("Location: index.php?action=player_dashboard");
                exit();
            } else {
                $_SESSION['error'] = "Erreur lors de la mise à jour du profil.";
                header("Location: index.php?action=edit_profile");
                exit();
            }
        }
    }

    /**
     * Affiche le tableau de bord du joueur
     */
    public function dashboard() {
        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'player') {
            header('Location: index.php?action=login');
            exit;
        }
        
        $playerId = $_SESSION['user_id'];
        
        // Récupérer les statistiques du joueur
        $playerStats = $this->getPlayerStats($playerId);
        
        // Récupérer l'historique des parties récentes
        $recentGames = $this->getRecentGames($playerId, 5);
        
        // Charger la vue du tableau de bord
        $this->view('user/player_dashboard', [
            'stats' => $playerStats,
            'recentGames' => $recentGames
        ]);
    }

    /**
     * Récupère les statistiques d'un joueur
     */
    private function getPlayerStats($playerId) {
        try {
            // Calculer le nombre total de parties jouées (depuis la table quiz_game_history)
            $stmt = $this->db->prepare("
                SELECT COUNT(*) AS total_games,
                       SUM(score) AS total_score,
                       AVG(score) AS avg_score
                FROM quiz_game_history
                WHERE player_id = ? AND completed = 1
            ");
            $stmt->execute([$playerId]);
            $stats = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // En cas de joueur sans historique
            if (!$stats || $stats['total_games'] == 0) {
                return [
                    'total_games' => 0,
                    'total_score' => 0,
                    'avg_score' => 0,
                    'best_score' => 0
                ];
            }
            
            // Récupérer le meilleur score
            $stmt = $this->db->prepare("
                SELECT MAX(score) AS best_score
                FROM quiz_game_history
                WHERE player_id = ? AND completed = 1
            ");
            $stmt->execute([$playerId]);
            $bestScore = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Fusionner les résultats
            $stats['best_score'] = $bestScore['best_score'] ?? 0;
            
            return $stats;
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des statistiques : " . $e->getMessage());
            return [
                'total_games' => 0,
                'total_score' => 0,
                'avg_score' => 0,
                'best_score' => 0
            ];
        }
    }

    /**
     * Récupère l'historique récent des parties d'un joueur
     */
    private function getRecentGames($playerId, $limit = 5) {
        try {
            $stmt = $this->db->prepare("
                SELECT gh.*, q.title as quiz_name
                FROM quiz_game_history gh
                LEFT JOIN quizzes q ON gh.quiz_id = q.Id_Quiz
                WHERE gh.player_id = ?
                ORDER BY gh.played_on DESC
                LIMIT ?
            ");
            $stmt->execute([$playerId, $limit]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des parties récentes : " . $e->getMessage());
            return [];
        }
    }
}
?>