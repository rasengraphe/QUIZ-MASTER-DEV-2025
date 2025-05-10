<?php
// quiz_project/models/UserModel.php
class UserModel extends Model {
protected $db;

    public function __construct($db) {
        parent::__construct($db);
    $this->db = $db;
    }

    /**
     * Authentifie un utilisateur
     */
    public function authenticate($email, $password) {
        try {
                        $stmt = $this->db->prepare("SELECT * FROM quiz_users WHERE email = :email");
            $stmt->bindParam(':email', $email);
                        $stmt->execute();
                        $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user && password_verify($password, $user['password'])) {
                return $user;
                }
                        return false;
        } catch (PDOException $e) {
                        error_log("Erreur lors de l'authentification: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Récupère un administrateur par email et nom
     */
    public function getAdminByEmailAndName($email, $name) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM quiz_admin_editor WHERE email = ? AND name = ?");
            $stmt->execute([$email, $name]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération de l'admin: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Récupère un joueur par email et nom
     */
    public function getPlayerByEmailAndName($email, $name) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM quiz_player WHERE email = ? AND name = ?");
            $stmt->execute([$email, $name]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération du joueur: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Récupère un utilisateur par email et nom dans la table unifiée
     */
    public function getUserByEmailAndName($email, $name) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM quiz_users WHERE email = ? AND name = ?");
            $stmt->execute([$email, $name]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération de l'utilisateur: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Récupère un utilisateur par son ID
     */
    public function getUserById($id) {
        try {
        $stmt = $this->db->prepare("SELECT * FROM quiz_users WHERE id = :id");
        $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération de l'utilisateur: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Récupère un administrateur par son ID
     */
    public function getAdminById($id) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM quiz_admin_editor WHERE Id_admin_editor = ?");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération de l'administrateur: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Récupère un utilisateur par son email
     */
    public function getUserByEmail($email) {
        try {
                        $stmt = $this->db->prepare("SELECT * FROM quiz_users WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
                        return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération de l'utilisateur par email: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Crée un nouvel utilisateur
     */
    public function createUser($username, $email, $password, $role = 'player', $avatarPath = null) {
        try {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $this->db->prepare(
                "INSERT INTO quiz_users (username, email, password, role, avatar_path) 
                    VALUES (:username, :email, :password, :role, :avatar_path)"
            );
                    
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':role', $role);
            $stmt->bindParam(':avatar_path', $avatarPath);
            
            $stmt->execute();
                return $this->db->lastInsertId();
                    } catch (PDOException $e) {
                        error_log("Erreur lors de la création de l'utilisateur: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Met à jour les informations d'un utilisateur
     */
    public function updateUser($id, $data) {
        try {
            $updates = [];
            $params = [':id' => $id];
            
            // Construire dynamiquement les champs à mettre à jour
            foreach ($data as $key => $value) {
                if ($key !== 'id') {
                    $updates[] = "$key = :$key";
        $params[":$key"] = $value;
        }
        }
        
        if (empty($updates)) {
            return false;
    }

    $sql = "UPDATE quiz_users SET " . implode(', ', $updates) . " WHERE id = :id";
            $stmt = $this->db->prepare($sql);
                        
                        return $stmt->execute($params);
        } catch (PDOException $e) {
            error_log("Erreur lors de la mise à jour de l'utilisateur: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Met à jour le mot de passe d'un utilisateur
     */
    public function updatePassword($id, $newPassword) {
        try {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            
            $stmt = $this->db->prepare("UPDATE quiz_users SET password = :password WHERE id = :id");
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':id', $id);
            
                        return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erreur lors de la mise à jour du mot de passe: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Supprime un utilisateur
     */
    public function deleteUser($id) {
        try {
                        $stmt = $this->db->prepare("DELETE FROM quiz_users WHERE id = :id");
            $stmt->bindParam(':id', $id);
return $stmt->execute();
                            } catch (PDOException $e) {
            error_log("Erreur lors de la suppression de l'utilisateur: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Récupère tous les utilisateurs
     */
    public function getAllUsers() {
        try {
            $stmt = $this->db->query("SELECT * FROM quiz_users ORDER BY created_at DESC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des utilisateurs: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Compte le nombre total d'utilisateurs
     */
    public function countUsers() {
        try {
            $stmt = $this->db->query("SELECT COUNT(*) FROM quiz_users");
                        return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Erreur lors du comptage des utilisateurs: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Compte le nombre total de joueurs
     */
    public function getTotalPlayerCount() {
        try {
            $stmt = $this->db->query("SELECT COUNT(*) FROM quiz_users WHERE role = 'player'");
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Erreur lors du comptage des joueurs: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Compte le nombre total de quizzes
     */
    public function getTotalQuizzesCount() {
        try {
            $stmt = $this->db->query("SELECT COUNT(*) FROM quizzes");
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Erreur lors du comptage des quiz: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Compte le nombre total de questions
     */
    public function getTotalQuestionsCount() {
        try {
            $stmt = $this->db->query("SELECT COUNT(*) FROM questions");
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Erreur lors du comptage des questions: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Compte le nombre total de parties jouées
     */
    public function getTotalGamesCount() {
        try {
            $stmt = $this->db->query("SELECT COUNT(*) FROM quiz_game_history");
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Erreur lors du comptage des parties: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Récupère les activités récentes
     */
    public function getRecentActivities($limit = 10) {
        try {
            $stmt = $this->db->prepare("
                SELECT 'game_played' as activity_type, gh.player_id as user_id, u.name, gh.score, gh.played_on as activity_date, q.title as quiz_name
                FROM quiz_game_history gh
                JOIN quiz_users u ON gh.player_id = u.id
                LEFT JOIN quizzes q ON gh.quiz_id = q.id
                ORDER BY gh.played_on DESC
                LIMIT :limit
            ");
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des activités récentes: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Récupère l'historique des quiz pour un joueur
     */
    public function getQuizHistoryForPlayer($playerId) {
        try {
            $stmt = $this->db->prepare("
                SELECT gh.*, q.title as quiz_title,
                       (gh.score / gh.max_score) * 100 as percentage_score
                FROM quiz_game_history gh
                LEFT JOIN quizzes q ON gh.quiz_id = q.id
                WHERE gh.player_id = :player_id
                ORDER BY gh.played_on DESC
            ");
            $stmt->bindParam(':player_id', $playerId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération de l'historique des quiz: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Met à jour le mot de passe d'un utilisateur
     */
    public function updateUserPassword($userId, $newPassword) {
        try {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $stmt = $this->db->prepare("UPDATE quiz_users SET password = ? WHERE id = ?");
            return $stmt->execute([$hashedPassword, $userId]);
        } catch (PDOException $e) {
            error_log("Erreur lors de la mise à jour du mot de passe: " . $e->getMessage());
            return false;
        }
    }
}
?>