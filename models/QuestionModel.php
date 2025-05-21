<?php
/**
 * Classe QuestionModel - Gère toutes les interactions avec la base de données pour les questions
 * Hérite de la classe Model pour la connexion à la base de données
 */
class QuestionModel extends Model {
    protected $db; // Instance de PDO pour la connexion à la base de données

    /**
     * Constructeur - Initialise la connexion à la base de données
     * @param PDO $db - Instance de connexion à la base de données
     */
    public function __construct($db) {
        parent::__construct($db);
        $this->db = $db;
    }

    /**
     * Récupère toutes les questions de la base de données
     * @return array - Liste de toutes les questions triées par ID décroissant
     * Structure de retour: [
     *     ['Id_question' => 1, 'text' => 'Question?', ...],
     *     ['Id_question' => 2, 'text' => 'Autre question?', ...]
     * ]
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
     * Récupère une question spécifique par son ID
     * @param int $id - ID de la question à récupérer
     * @return array|false - Données de la question ou false si non trouvée
     * Exemple de retour: ['Id_question' => 1, 'text' => 'Question?', ...]
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
     * Récupère les questions pour un quiz spécifique avec leurs réponses
     * @param int $quizId - ID du quiz
     * @return array - Questions avec leurs réponses associées
     * Structure de retour: [
     *     [
     *         'Id_question' => 1,
     *         'text' => 'Question?',
     *         'answers' => [
     *             ['id' => 1, 'text' => 'Réponse 1', 'is_correct' => 1],
     *             ['id' => 2, 'text' => 'Réponse 2', 'is_correct' => 0]
     *         ]
     *     ]
     * ]
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
     * Crée une nouvelle question dans la base de données
     * @param string $questionText - Texte de la question
     * @param string|null $imagePath - Chemin de l'image associée (optionnel)
     * @param int $createdBy - ID de l'administrateur créant la question
     * @return int|false - ID de la nouvelle question ou false si échec
     * 
     * Notes sur la requête:
     * - Id_question_category = 1 : Catégorie par défaut
     * - Id_question_difficulte = 1 : Difficulté par défaut
     * - NOW() : Date/heure actuelle de création
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
    public function addAnswer($data) {
        try {
            $sql = "INSERT INTO quiz_question_answer (text, correct, Id_question) 
                   VALUES (:text, :correct, :Id_question)";
                   
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute([
                'text' => $data['text'],
                'correct' => $data['correct'],
                'Id_question' => $data['Id_question']
            ]);
            
            return $result ? $this->db->lastInsertId() : false;
        } catch (PDOException $e) {
            error_log("Erreur lors de l'ajout de la réponse: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Met à jour une question existante
     * @param array $data - Données de la question à mettre à jour
     * Structure attendue de $data:
     * [
     *     'text' => 'Nouveau texte',
     *     'Id_question' => 1,
     *     'Id_question_category' => 2, // optionnel
     *     'Id_question_difficulte' => 3, // optionnel
     *     'picture' => 'chemin/image.jpg' // optionnel
     * ]
     * @return bool - true si succès, false si échec
     */
    public function updateQuestion($data) {
        try {
            // Construire la requête SQL
            $sql = "UPDATE quiz_question SET text = :text";
            
            $params = [
                'text' => $data['text'],
                'Id_question' => $data['Id_question']
            ];
            
            // Ajouter la catégorie si elle est définie
            if (isset($data['Id_question_category']) && !is_null($data['Id_question_category'])) {
                $sql .= ", Id_question_category = :category";
                $params['category'] = $data['Id_question_category'];
            } else {
                $sql .= ", Id_question_category = NULL";
            }
            
            // Ajouter la difficulté si elle est définie
            if (isset($data['Id_question_difficulte']) && !is_null($data['Id_question_difficulte'])) {
                $sql .= ", Id_question_difficulte = :difficulty";
                $params['difficulty'] = $data['Id_question_difficulte'];
            } else {
                $sql .= ", Id_question_difficulte = NULL";
            }
            
            // Traitement spécial pour l'image
            if (array_key_exists('picture', $data)) {
                if (is_null($data['picture'])) {
                    $sql .= ", picture = NULL";
                } else {
                    $sql .= ", picture = :picture";
                    $params['picture'] = $data['picture'];
                }
            }
            
            $sql .= " WHERE Id_question = :Id_question";
            
            error_log("SQL: " . $sql);
            error_log("Params: " . print_r($params, true));
            
            // Exécuter la requête
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute($params);
            
            if (!$result) {
                error_log("Erreur SQL: " . print_r($stmt->errorInfo(), true));
            }
            
            return $result;
        } catch (PDOException $e) {
            error_log("PDO Exception: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Met à jour une réponse
     */
    public function updateAnswer($data) {
        try {
            $sql = "UPDATE quiz_question_answer 
                   SET text = :text, correct = :correct 
                   WHERE Id_question_answer = :Id_question_answer";
                   
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'text' => $data['text'],
                'correct' => $data['correct'],
                'Id_question_answer' => $data['Id_question_answer']
            ]);
        } catch (PDOException $e) {
            error_log("Erreur lors de la mise à jour de la réponse: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Supprime une question et toutes ses dépendances
     * Utilise une transaction pour garantir l'intégrité des données
     * Ordre de suppression:
     * 1. Supprime les réponses associées
     * 2. Supprime les liens quiz-questions
     * 3. Supprime la question elle-même
     * 
     * @param int $id - ID de la question à supprimer
     * @return bool - true si succès, false si échec
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
     * Recherche des questions par texte
     * @param string $searchTerm - Terme à rechercher
     * @return array - Questions correspondant à la recherche
     * 
     * Notes:
     * - Utilise LIKE avec des wildcards (%)
     * - Joint avec la table users pour récupérer le nom du créateur
     * - Normalise les noms de champs pour la compatibilité
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