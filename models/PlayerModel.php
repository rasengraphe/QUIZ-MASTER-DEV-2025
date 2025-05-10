<?php
require_once 'core/Model.php';

class PlayerModel extends Model {
    
    /**
     * Récupère un joueur par son ID
     * 
     * @param int $playerId ID du joueur
     * @return array|false Données du joueur ou false si non trouvé
     */
    public function getPlayerById($playerId) {
        $sql = "SELECT * FROM quiz_users WHERE id = ? AND role = 'player'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$playerId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Récupère un joueur par son email
     * 
     * @param string $email Email du joueur
     * @return array|false Données du joueur ou false si non trouvé
     */
    public function getPlayerByEmail($email) {
        $sql = "SELECT * FROM quiz_users WHERE email = ? AND role = 'player'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Crée un nouveau joueur
     * 
     * @param array $data Données du joueur (name, first_name, email, password_hash)
     * @return int|false ID du joueur créé ou false en cas d'échec
     */
    public function createPlayer($data) {
        $sql = "INSERT INTO quiz_users (name, first_name, email, password, Id_Avatar, role, date_creation) 
                VALUES (?, ?, ?, ?, ?, 'player', NOW())";
        
        $stmt = $this->db->prepare($sql);
        $success = $stmt->execute([
            $data['name'],
            $data['first_name'],
            $data['email'],
            $data['password_hash'],
            $data['avatar_id'] ?? 1
        ]);
        
        return $success ? $this->db->lastInsertId() : false;
    }
    
    /**
     * Met à jour les informations d'un joueur
     * 
     * @param int $playerId ID du joueur
     * @param array $data Données à mettre à jour
     * @return bool Succès de la mise à jour
     */
    public function updatePlayer($playerId, $data) {
        // Préparer les colonnes et valeurs pour la requête SQL
        $setClauses = [];
        $params = [];
        
        foreach ($data as $column => $value) {
            $setClauses[] = "{$column} = ?";
            $params[] = $value;
        }
        
        // Ajouter l'ID du joueur aux paramètres
        $params[] = $playerId;
        
        $sql = "UPDATE quiz_users SET " . implode(', ', $setClauses) . " WHERE id = ? AND role = 'player'";
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute($params);
    }
    
    /**
     * Met à jour le score d'un joueur
     * 
     * @param int $playerId ID du joueur
     * @param int $points Points à ajouter au score actuel
     * @return bool Succès de la mise à jour
     */
    public function updatePlayerScore($playerId, $points) {
        $sql = "UPDATE quiz_users SET score = score + ? WHERE id = ? AND role = 'player'";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$points, $playerId]);
    }
    
    /**
     * Récupère les statistiques d'un joueur
     * 
     * @param int $playerId ID du joueur
     * @return array Statistiques du joueur
     */
    public function getPlayerStats($playerId) {
        // Nombre total de parties jouées
        $sqlGames = "SELECT COUNT(*) as gamesCount FROM quiz_game_history WHERE player_id = ?";
        $stmtGames = $this->db->prepare($sqlGames);
        $stmtGames->execute([$playerId]);
        $gamesCount = $stmtGames->fetch(PDO::FETCH_ASSOC)['gamesCount'];
        
        // Meilleur score
        $sqlBestScore = "SELECT MAX(score) as bestScore FROM quiz_game_history WHERE player_id = ?";
        $stmtBestScore = $this->db->prepare($sqlBestScore);
        $stmtBestScore->execute([$playerId]);
        $bestScore = $stmtBestScore->fetch(PDO::FETCH_ASSOC)['bestScore'];
        
        // Quiz complétés (nombre de quiz différents joués)
        $sqlQuizzes = "SELECT COUNT(DISTINCT quiz_id) as quizzesCompleted FROM quiz_game_history WHERE player_id = ?";
        $stmtQuizzes = $this->db->prepare($sqlQuizzes);
        $stmtQuizzes->execute([$playerId]);
        $quizzesCompleted = $stmtQuizzes->fetch(PDO::FETCH_ASSOC)['quizzesCompleted'];
        
        return [
            'gamesCount' => $gamesCount,
            'bestScore' => $bestScore,
            'quizzesCompleted' => $quizzesCompleted
        ];
    }
    
    /**
     * Récupère les parties récentes d'un joueur
     * 
     * @param int $playerId ID du joueur
     * @param int $limit Nombre maximum de parties à récupérer
     * @return array Liste des parties récentes
     */
    public function getRecentGames($playerId, $limit = 5) {
        $sql = "SELECT gh.*, q.title as quiz_name 
                FROM quiz_game_history gh
                LEFT JOIN quiz q ON gh.quiz_id = q.Id_Quiz 
                WHERE gh.player_id = ? 
                ORDER BY gh.played_on DESC 
                LIMIT ?";
                
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$playerId, $limit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Récupère les quiz recommandés pour un joueur
     * 
     * @param int $playerId ID du joueur
     * @param int $limit Nombre maximum de quiz à récupérer
     * @return array Liste des quiz recommandés
     */
    public function getRecommendedQuizzes($playerId, $limit = 4) {
        // Exemple de logique simple: recommander des quiz que le joueur n'a pas encore joués
        $sql = "SELECT q.Id_Quiz as id, q.title, q.description, q.difficulty_level as difficulty
                FROM quiz q 
                WHERE q.Id_Quiz NOT IN (
                    SELECT DISTINCT quiz_id FROM quiz_game_history WHERE player_id = ?
                )
                AND q.is_active = 1
                ORDER BY RAND()
                LIMIT ?";
                
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$playerId, $limit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Enregistre une nouvelle partie de quiz
     * 
     * @param array $gameData Données de la partie
     * @return int|false ID de la partie créée ou false en cas d'échec
     */
    public function saveGameHistory($gameData) {
        $sql = "INSERT INTO quiz_game_history (player_id, quiz_id, score, played_on, completion_time)
                VALUES (?, ?, ?, NOW(), ?)";
                
        $stmt = $this->db->prepare($sql);
        $success = $stmt->execute([
            $gameData['player_id'],
            $gameData['quiz_id'],
            $gameData['score'],
            $gameData['completion_time']
        ]);
        
        return $success ? $this->db->lastInsertId() : false;
    }
    
    /**
     * Récupère le classement des joueurs
     * 
     * @param int $limit Nombre maximum de joueurs à récupérer
     * @return array Liste des joueurs avec leur classement
     */
    public function getLeaderboard($limit = 10) {
        $sql = "SELECT id, name, first_name, score, Id_Avatar
                FROM quiz_users
                WHERE role = 'player'
                ORDER BY score DESC
                LIMIT ?";
                
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$limit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>