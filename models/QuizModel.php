<?php
// quiz_project/models/QuizModel.php
class QuizModel extends Model {
protected $db;

    public function __construct($db) {
        parent::__construct($db);
$this->db = $db;
    }

/**
     * Récupère tous les quiz
     */
    public function getAllQuizzes() {
        try {
            $stmt = $this->db->query("
                SELECT q.*, 
                       (SELECT COUNT(*) FROM quiz_quiz_questions WHERE quiz_id = q.id) as question_count
                FROM quiz_quizzes q
                ORDER BY q.created_at DESC
            ");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des quiz: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Récupère un quiz par son ID
     */
    public function getQuizById($id) {
        try {
            error_log("Debug getQuizById: Tentative de récupération du quiz $id");
            $stmt = $this->db->prepare("
                SELECT *
                FROM quiz_quizzes 
                WHERE id = ?
            ");
            $stmt->execute([$id]);
            $quiz = $stmt->fetch(PDO::FETCH_ASSOC);
            error_log("Debug getQuizById: Résultat=" . print_r($quiz, true));
            return $quiz;
        } catch (PDOException $e) {
            error_log("Erreur dans getQuizById: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Crée un nouveau quiz
     */
    public function createQuiz($title, $description, $createdBy) {
        try {
            error_log("Debug createQuiz: Début avec title=$title, createdBy=$createdBy");
            
            $stmt = $this->db->prepare("
                INSERT INTO quiz_quizzes (title, description, created_by, created_at) 
                VALUES (:title, :description, :created_by, NOW())
            ");
            
            $stmt->bindParam(':title', $title, PDO::PARAM_STR);
            $stmt->bindParam(':description', $description, PDO::PARAM_STR);
            $stmt->bindParam(':created_by', $createdBy, PDO::PARAM_INT);
            
            error_log("Debug createQuiz: Exécution de la requête");
            $success = $stmt->execute();
            
            if (!$success) {
                error_log("Debug createQuiz: Erreur lors de l'exécution - " . print_r($stmt->errorInfo(), true));
                return false;
            }
            
            $quizId = $this->db->lastInsertId();
            error_log("Debug createQuiz: Quiz créé avec succès, ID=$quizId");
            return $quizId;
            
        } catch (PDOException $e) {
            error_log("Debug createQuiz: Exception PDO - " . $e->getMessage());
            error_log("Debug createQuiz: Trace - " . $e->getTraceAsString());
            return false;
        }
    }

    /**
     * Met à jour un quiz existant
     */
    public function updateQuiz($id, $title, $description) {
try {
        $stmt = $this->db->prepare("
UPDATE quiz_quizzes
SET title = :title, description = :description
WHERE id = :id
");
        $stmt->bindParam(':title', $title, PDO::PARAM_STR);
            $stmt->bindParam(':description', $description, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erreur lors de la mise à jour du quiz: " . $e->getMessage());
        return false;
    }
}

    /**
     * Supprime un quiz et ses associations
     */
    public function deleteQuiz($id) {
        try {
            // Commencer une transaction
            $this->db->beginTransaction();
            
            // Supprimer les résultats associés au quiz
            $resultStmt = $this->db->prepare("DELETE FROM quiz_results WHERE quiz_id = :id");
        $resultStmt->bindParam(':id', $id, PDO::PARAM_INT);
            $resultStmt->execute();
            
            // Supprimer les associations quiz-question
            $quizQuestionStmt = $this->db->prepare("DELETE FROM quiz_quiz_questions WHERE quiz_id = :id");
            $quizQuestionStmt->bindParam(':id', $id, PDO::PARAM_INT);
            $quizQuestionStmt->execute();
            
            // Supprimer le quiz
            $quizStmt = $this->db->prepare("DELETE FROM quiz_quizzes WHERE id = :id");
        $quizStmt->bindParam(':id', $id, PDO::PARAM_INT);
            $quizStmt->execute();
            
            // Valider la transaction
            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            // Annuler la transaction en cas d'erreur
            $this->db->rollBack();
            error_log("Erreur lors de la suppression du quiz: " . $e->getMessage());
        return false;
    }
}

    /**
     * Récupère les questions pour un quiz spécifique
     */
    public function getQuestionsForQuiz($quizId) {
        try {
            $stmt = $this->db->prepare("
                SELECT q.*, qq.question_order
                FROM quiz_question q
                JOIN quiz_quiz_questions qq ON q.Id_question = qq.question_id
                WHERE qq.quiz_id = :quiz_id
                ORDER BY qq.question_order
            ");
            $stmt->bindParam(':quiz_id', $quizId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des questions du quiz: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Ajoute une question à un quiz
     */
    public function addQuestionToQuiz($quizId, $questionId, $order) {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO quiz_quiz_questions (quiz_id, question_id, question_order)
                VALUES (:quiz_id, :question_id, :order)
            ");
            $stmt->bindParam(':quiz_id', $quizId, PDO::PARAM_INT);
            $stmt->bindParam(':question_id', $questionId, PDO::PARAM_INT);
            $stmt->bindParam(':order', $order, PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erreur lors de l'ajout de la question au quiz: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Supprime une question d'un quiz
     */
    public function removeQuestionFromQuiz($quizId, $questionId) {
        try {
        $stmt = $this->db->prepare("
            DELETE FROM quiz_quiz_questions
            WHERE quiz_id = :quiz_id AND question_id = :question_id
        ");
        $stmt->bindParam(':quiz_id', $quizId, PDO::PARAM_INT);
            $stmt->bindParam(':question_id', $questionId, PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erreur lors de la suppression de la question du quiz: " . $e->getMessage());
        return false;
        }
    }

    /**
     * Enregistre le résultat d'un quiz
     */
    public function saveQuizResult($quizId, $userId, $score, $maxScore) {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO quiz_game_history (player_id, quiz_id, score, completed)
                VALUES (:user_id, :quiz_id, :score, 1)
            ");
            $stmt->bindParam(':quiz_id', $quizId, PDO::PARAM_INT);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':score', $score, PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erreur lors de l'enregistrement du résultat: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Récupère l'historique des quiz d'un utilisateur
     */
    public function getUserQuizHistory($userId) {
        try {
            $stmt = $this->db->prepare("
                SELECT qr.*, q.title as quiz_title,
                       (qr.score / qr.max_score) * 100 as percentage_score
                FROM quiz_results qr
                JOIN quiz_quizzes q ON qr.quiz_id = q.id
                WHERE qr.user_id = :user_id
                ORDER BY qr.completed_at DESC
            ");
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération de l'historique: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Compte le nombre total de quiz
     */
    public function countQuizzes() {
        try {
            $stmt = $this->db->query("SELECT COUNT(*) FROM quiz_quizzes");
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Erreur lors du comptage des quiz: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Vérifie si un jeu est déjà actif pour un joueur et un quiz
     * @param int $playerId ID du joueur
     * @param int $quizId ID du quiz
     * @return array|false Le jeu actif ou false si aucun
     */
    public function getActiveGameForPlayerAndQuiz($playerId, $quizId) {
        try {
            $stmt = $this->db->prepare("
                SELECT * FROM quiz_game_history
                WHERE player_id = :player_id AND quiz_id = :quiz_id AND completed = 0
                LIMIT 1
            ");
            $stmt->bindParam(':player_id', $playerId, PDO::PARAM_INT);
            $stmt->bindParam(':quiz_id', $quizId, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la vérification du jeu actif: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Démarre un nouveau jeu pour un joueur
     * @param int $playerId ID du joueur
     * @param int $quizId ID du quiz
     * @return int|false L'ID du jeu créé ou false en cas d'erreur
     */
    public function startNewGame($playerId, $quizId) {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO quiz_game_history (player_id, quiz_id, played_on, score, max_score, completed)
                VALUES (:player_id, :quiz_id, NOW(), 0, 0, 0)
            ");
            $stmt->bindParam(':player_id', $playerId, PDO::PARAM_INT);
            $stmt->bindParam(':quiz_id', $quizId, PDO::PARAM_INT);
            
            if ($stmt->execute()) {
                return $this->db->lastInsertId();
            }
            return false;
        } catch (PDOException $e) {
            error_log("Erreur lors du démarrage d'un nouveau jeu: " . $e->getMessage());
            return false;
        }
    }
}
?>