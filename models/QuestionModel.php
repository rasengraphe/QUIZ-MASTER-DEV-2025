<?php
// models/QuestionModel.php
class QuestionModel extends Model {
    protected $db;

    public function __construct($db) {
        parent::__construct($db);
        $this->db = $db;
    }

    /**
     * Récupère toutes les questions
     */
    public function getAllQuestions() {
        try {
            $stmt = $this->db->query("SELECT * FROM quiz_question ORDER BY Id_question DESC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des questions: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Récupère une question par son ID
     */
    public function getQuestionById($id) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM quiz_question WHERE Id_question = ?");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération de la question: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Supprime une question par son ID
     */
    public function deleteById($id) {
        try {
            // D'abord supprimer les réponses associées
            $stmtAnswers = $this->db->prepare("DELETE FROM quiz_reponse WHERE Id_question = ?");
            $stmtAnswers->execute([$id]);
            
            // Ensuite supprimer la question
            $stmtQuestion = $this->db->prepare("DELETE FROM quiz_question WHERE Id_question = ?");
            return $stmtQuestion->execute([$id]);
        } catch (PDOException $e) {
            error_log("Erreur lors de la suppression de la question: " . $e->getMessage());
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
                JOIN quiz_questions qq ON q.Id_question = qq.question_id
                WHERE qq.quiz_id = :quiz_id
                ORDER BY qq.question_order
            ");
            $stmt->bindParam(':quiz_id', $quizId, PDO::PARAM_INT);
            $stmt->execute();
        
            // Récupérer les questions
            $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Pour chaque question, récupérer les réponses associées
            foreach ($questions as &$question) {
                $question['answers'] = $this->getAnswersForQuestion($question['Id_question']);
                // Assurer la compatibilité avec le reste du code
                $question['id'] = $question['Id_question'];
                $question['question_text'] = $question['text'];
            }
            
            return $questions;
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des questions du quiz: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Récupère les réponses pour une question
     */
    public function getAnswersForQuestion($questionId) {
        try {
            $stmt = $this->db->prepare("
                SELECT * FROM quiz_question_answer
                WHERE Id_question = :question_id
                ORDER BY Id_question_answer
            ");
            $stmt->bindParam(':question_id', $questionId, PDO::PARAM_INT);
            $stmt->execute();
            $answers = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Assurer la compatibilité avec le reste du code
            foreach ($answers as &$answer) {
                $answer['id'] = $answer['Id_question_answer'];
                $answer['answer_text'] = $answer['text'];
                $answer['is_correct'] = $answer['correct'];
            }
            
            return $answers;
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des réponses: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Récupère la réponse correcte pour une question
     */
    public function getCorrectAnswerForQuestion($questionId) {
        try {
            $stmt = $this->db->prepare("
                SELECT *
                FROM quiz_question_answer
                WHERE Id_question = :question_id AND correct = 1
                LIMIT 1
            ");
            $stmt->bindParam(':question_id', $questionId, PDO::PARAM_INT);
            $stmt->execute();
            $answer = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($answer) {
                // Assurer la compatibilité avec le reste du code
                $answer['id'] = $answer['Id_question_answer'];
                $answer['answer_text'] = $answer['text'];
                $answer['is_correct'] = $answer['correct'];
            }
            
            return $answer;
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération de la réponse correcte: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Crée une nouvelle question
     */
    public function createQuestion($questionText, $imagePath, $createdBy) {
        try {
            error_log("Debug Question: Début createQuestion()");
            error_log("Debug Question: Paramètres - questionText: $questionText, imagePath: " . ($imagePath ?? 'null') . ", createdBy: $createdBy");
            
            $stmt = $this->db->prepare("
                INSERT INTO quiz_question (text, picture, Id_admin_editor, Id_question_category, Id_question_difficulte, date_creation)
                VALUES (:question_text, :image_path, :created_by, 1, 1, NOW())
            ");
            $stmt->bindParam(':question_text', $questionText, PDO::PARAM_STR);
            $stmt->bindParam(':image_path', $imagePath, PDO::PARAM_STR);
            $stmt->bindParam(':created_by', $createdBy, PDO::PARAM_INT);
            
            $success = $stmt->execute();
            error_log("Debug Question: Exécution de la requête - " . ($success ? "Réussie" : "Échouée"));
            
            if ($success) {
                $questionId = $this->db->lastInsertId();
                error_log("Debug Question: Question créée avec ID: $questionId");
                return $questionId;
            }
            
            error_log("Debug Question: Échec de la création de la question");
            return false;
        } catch (PDOException $e) {
            error_log("Debug Question: Erreur PDO lors de la création de la question: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Ajoute une réponse à une question
     */
    public function addAnswer($questionId, $answerText, $isCorrect) {
        try {
            error_log("Debug Answer: Début addAnswer()");
            error_log("Debug Answer: Paramètres - questionId: $questionId, answerText: $answerText, isCorrect: " . ($isCorrect ? "true" : "false"));
            
            $stmt = $this->db->prepare("
                INSERT INTO quiz_question_answer (Id_question, text, correct)
                VALUES (:question_id, :answer_text, :is_correct)
            ");
            $stmt->bindParam(':question_id', $questionId, PDO::PARAM_INT);
            $stmt->bindParam(':answer_text', $answerText, PDO::PARAM_STR);
            $stmt->bindParam(':is_correct', $isCorrect, PDO::PARAM_INT);
            
            $success = $stmt->execute();
            error_log("Debug Answer: Exécution de la requête - " . ($success ? "Réussie" : "Échouée"));
            
            if ($success) {
                $answerId = $this->db->lastInsertId();
                error_log("Debug Answer: Réponse créée avec ID: $answerId");
            }
            
            return $success;
        } catch (PDOException $e) {
            error_log("Debug Answer: Erreur PDO lors de la création de la réponse: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Met à jour une question existante
     */
    public function updateQuestion($id, $questionText, $imagePath) {
        try {
            // Si l'image est vide, ne pas mettre à jour ce champ
            if (empty($imagePath)) {
                $stmt = $this->db->prepare("
                    UPDATE quiz_question
                    SET text = :question_text
                    WHERE Id_question = :id
                ");
                $stmt->bindParam(':question_text', $questionText, PDO::PARAM_STR);
            } else {
                $stmt = $this->db->prepare("
                    UPDATE quiz_question
                    SET text = :question_text, picture = :image_path
                    WHERE Id_question = :id
                ");
                $stmt->bindParam(':question_text', $questionText, PDO::PARAM_STR);
                $stmt->bindParam(':image_path', $imagePath, PDO::PARAM_STR);
            }
            
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erreur lors de la mise à jour de la question: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Met à jour une réponse
     */
    public function updateAnswer($answerId, $answerText, $isCorrect) {
        try {
            $stmt = $this->db->prepare("
                UPDATE quiz_question_answer
                SET text = :answer_text, correct = :is_correct
                WHERE Id_question_answer = :id
            ");
            $stmt->bindParam(':answer_text', $answerText, PDO::PARAM_STR);
            $stmt->bindParam(':is_correct', $isCorrect, PDO::PARAM_INT);
            $stmt->bindParam(':id', $answerId, PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erreur lors de la mise à jour de la réponse: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Supprime une question et ses réponses associées
     */
    public function deleteQuestion($id) {
        try {
            $this->db->beginTransaction();
            
            // Supprimer d'abord les réponses associées
            $answersStmt = $this->db->prepare("DELETE FROM quiz_question_answer WHERE Id_question = :id");
            $answersStmt->bindParam(':id', $id, PDO::PARAM_INT);
            $answersStmt->execute();
            
            // Supprimer les associations quiz-question
            $quizQuestionsStmt = $this->db->prepare("DELETE FROM quiz_questions WHERE question_id = :id");
            $quizQuestionsStmt->bindParam(':id', $id, PDO::PARAM_INT);
            $quizQuestionsStmt->execute();
            
            // Supprimer la question elle-même
            $questionStmt = $this->db->prepare("DELETE FROM quiz_question WHERE Id_question = :id");
            $questionStmt->bindParam(':id', $id, PDO::PARAM_INT);
            $questionStmt->execute();
            
            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Erreur lors de la suppression de la question: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Supprime une réponse
     */
    public function deleteAnswer($answerId) {
        try {
            $stmt = $this->db->prepare("DELETE FROM quiz_question_answer WHERE Id_question_answer = :id");
            $stmt->bindParam(':id', $answerId, PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erreur lors de la suppression de la réponse: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Compte le nombre total de questions
     */
    public function countQuestions() {
        try {
            $stmt = $this->db->query("SELECT COUNT(*) FROM quiz_question");
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Erreur lors du comptage des questions: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Récupère les questions pour une recherche
     */
    public function searchQuestions($searchTerm) {
        try {
            $searchTerm = "%$searchTerm%";
            $stmt = $this->db->prepare("
                SELECT q.*, u.name as created_by_name
                FROM quiz_question q
                LEFT JOIN quiz_users u ON q.Id_admin_editor = u.id
                WHERE q.text LIKE :search_term
                ORDER BY q.date_creation DESC
            ");
            $stmt->bindParam(':search_term', $searchTerm, PDO::PARAM_STR);
            $stmt->execute();
            $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Normaliser les noms de champs pour la compatibilité
            foreach ($questions as &$question) {
                $question['id'] = $question['Id_question'];
                $question['question_text'] = $question['text'];
                $question['created_at'] = $question['date_creation'];
                $question['created_by'] = $question['Id_admin_editor'];
            }
            
            return $questions;
        } catch (PDOException $e) {
            error_log("Erreur lors de la recherche des questions: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Vérifie si une réponse est correcte
     */
    public function checkAnswer($questionId, $answerId) {
        try {
            $stmt = $this->db->prepare("
                SELECT correct FROM quiz_question_answer
                WHERE Id_question = :question_id AND Id_question_answer = :answer_id
            ");
            $stmt->bindParam(':question_id', $questionId, PDO::PARAM_INT);
            $stmt->bindParam(':answer_id', $answerId, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $result ? (bool)$result['correct'] : false;
        } catch (PDOException $e) {
            error_log("Erreur lors de la vérification de la réponse: " . $e->getMessage());
            return false;
        }
    }
}
?>