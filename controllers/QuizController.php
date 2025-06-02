<?php
// quiz_project/controllers/QuizController.php
class QuizController extends Controller {
    private $quizModel;
    private $questionModel;

    public function __construct($db) {
        parent::__construct($db);
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit();
        }
        $this->quizModel = new QuizModel($db);
        $this->questionModel = new QuestionModel($db);
    }

/**
     * Affiche la liste des quiz disponibles
     */
    public function index() {
        $quizzes = $this->quizModel->getAllQuizzes();
        $this->view('quiz/list', ['quizzes' => $quizzes]);
    }

    /**
     * Affiche le formulaire pour créer un nouveau quiz
     */
    public function create() {
        // Vérifier si l'utilisateur est un administrateur
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            $_SESSION['error_message'] = "Vous n'avez pas les droits pour créer un quiz.";
            header("Location: index.php?action=quiz");
            exit();
        }

        $this->view('quiz/quiz_form', []);
    }

    /**
     * Traite la soumission du formulaire de création de quiz
     */
    public function store() {
        // Vérifier si l'utilisateur est un administrateur
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            $_SESSION['error_message'] = "Vous n'avez pas les droits pour créer un quiz.";
            header("Location: index.php?action=quiz");
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = $_POST['title'] ?? '';
            $description = $_POST['description'] ?? '';
            $createdBy = $_SESSION['user_id'];
            
            if (empty($title)) {
                $_SESSION['error_message'] = "Le titre du quiz est requis.";
                header("Location: index.php?action=create_quiz");
                exit();
            }
            
            $quizId = $this->quizModel->createQuiz($title, $description, $createdBy);
            
            if ($quizId) {
                $_SESSION['success_message'] = "Quiz créé avec succès.";
            header("Location: index.php?action=edit_quiz&id=" . $quizId);
            exit();
        } else {
                $_SESSION['error_message'] = "Erreur lors de la création du quiz.";
                header("Location: index.php?action=create_quiz");
                exit();
            }
        }
    }

    /**
     * Affiche le formulaire pour éditer un quiz existant
     */
    public function edit($id) {
// Vérifier si l'utilisateur est un administrateur
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
$_SESSION['error_message'] = "Vous n'avez pas les droits pour éditer ce quiz.";
            header("Location: index.php?action=quiz");
            exit();
        }
        
        $quiz = $this->quizModel->getQuizById($id);
        
        if (!$quiz) {
            $_SESSION['error_message'] = "Quiz non trouvé.";
            header("Location: index.php?action=quiz");
            exit();
        }
        
        $questions = $this->quizModel->getQuestionsForQuiz($id);
        $allQuestions = $this->questionModel->getAllQuestions();
        
        $this->view('quiz/edit_quiz', [
            'quiz' => $quiz, 
            'questions' => $questions,
            'allQuestions' => $allQuestions
        ]);
    }

    /**
     * Traite la mise à jour d'un quiz existant
     */
    public function update($id) {
        // Vérifier si l'utilisateur est un administrateur
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            $_SESSION['error_message'] = "Vous n'avez pas les droits pour mettre à jour ce quiz.";
            header("Location: index.php?action=quiz");
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = $_POST['title'] ?? '';
            $description = $_POST['description'] ?? '';
            
            if (empty($title)) {
                $_SESSION['error_message'] = "Le titre du quiz est requis.";
                header("Location: index.php?action=edit_quiz&id=" . $id);
                exit();
            }
            
            $updated = $this->quizModel->updateQuiz($id, $title, $description);
            
            if ($updated) {
                $_SESSION['success_message'] = "Quiz mis à jour avec succès.";
            } else {
                $_SESSION['error_message'] = "Erreur lors de la mise à jour du quiz.";
            }
            
            header("Location: index.php?action=edit_quiz&id=" . $id);
            exit();
        }
    }

    /**
     * Supprime un quiz
     */
    public function delete($id) {
        // Vérifier si l'utilisateur est un administrateur
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            $_SESSION['error_message'] = "Vous n'avez pas les droits pour supprimer ce quiz.";
            header("Location: index.php?action=quiz");
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $deleted = $this->quizModel->deleteQuiz($id);
            
            if ($deleted) {
                $_SESSION['success_message'] = "Quiz supprimé avec succès.";
            } else {
                $_SESSION['error_message'] = "Erreur lors de la suppression du quiz.";
            }
            
            header("Location: index.php?action=quiz");
            exit();
        } else {
            $quiz = $this->quizModel->getQuizById($id);
            
            if (!$quiz) {
                $_SESSION['error_message'] = "Quiz non trouvé.";
                header("Location: index.php?action=quiz");
                exit();
            }
            
            $this->view('quiz/delete_quiz', ['quiz' => $quiz]);
        }
    }

    /**
     * Affiche les détails d'un quiz à prendre
     */
    public function select() {
        $quizzes = $this->quizModel->getAllQuizzes();

        // Données statiques pour v2
        $categories = [
            ['Id_question_category' => 1, 'title' => 'HTML'],
            ['Id_question_category' => 2, 'title' => 'CSS'],
            ['Id_question_category' => 3, 'title' => 'JavaScript']
        ];
        
        $difficulties = [
            ['Id_question_difficulte' => 1, 'name' => 'Facile'],
            ['Id_question_difficulte' => 2, 'name' => 'Moyen'],
            ['Id_question_difficulte' => 3, 'name' => 'Difficile']
        ];
        
        $this->view('quiz/select', [
            'quizzes' => $quizzes,
            'categories' => $categories,
            'difficulties' => $difficulties
        ]);
    }

    /**
     * Traite le formulaire de sélection de quiz et génère un quiz personnalisé
* Version simplifiée pour garantir le fonctionnement
     */
    public function handlePlayQuiz() {
        error_log('Debug handlePlayQuiz: Début de la méthode');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?action=select_quiz");
            exit();
        }
        
        try {
            // Validation des paramètres
            $theme = isset($_POST['theme']) ? intval($_POST['theme']) : null;
            $difficulty = isset($_POST['difficulty']) ? intval($_POST['difficulty']) : null;
            $questionCount = isset($_POST['questionCount']) ? intval($_POST['questionCount']) : 10;
            
            error_log("Debug handlePlayQuiz: Paramètres - theme=$theme, difficulty=$difficulty, questionCount=$questionCount");
            
            if (!$theme || !$difficulty) {
                throw new Exception("Thème ou difficulté manquant");
            }
            
            // Récupérer les questions existantes correspondant aux critères
            $stmt = $this->db->prepare("
                WITH RankedQuestions AS (
                    SELECT DISTINCT q.Id_question, q.text, q.picture, q.date_creation,
                           ROW_NUMBER() OVER (ORDER BY RAND()) as rn
                    FROM quiz_question q
                    LEFT JOIN quiz_quiz_questions qq ON q.Id_question = qq.question_id AND qq.quiz_id = :quizId
                    WHERE q.Id_question_category = :theme 
                    AND q.Id_question_difficulte = :difficulty
                    AND qq.quiz_id IS NULL
                )
                SELECT q.*, qa.Id_question_answer, qa.text as answer_text, qa.correct
                FROM RankedQuestions q
                LEFT JOIN quiz_question_answer qa ON q.Id_question = qa.Id_question
                WHERE q.rn <= :questionCount
                ORDER BY q.Id_question, qa.Id_question_answer
            ");
            
            $stmt->bindValue(':quizId', $quizId, PDO::PARAM_INT);
            $stmt->bindValue(':theme', $theme, PDO::PARAM_INT);
            $stmt->bindValue(':difficulty', $difficulty, PDO::PARAM_INT);
            $stmt->bindValue(':questionCount', $questionCount, PDO::PARAM_INT);
            $stmt->execute();
            
            $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if (empty($questions)) {
                throw new Exception("Aucune question trouvée pour ces critères");
            }
            
            // Créer le quiz temporaire
            $title = "Quiz personnalisé - " . date('Y-m-d H:i:s');
            $description = "Quiz généré automatiquement - Thème: $theme, Difficulté: $difficulty";
            $userId = $_SESSION['user_id'] ?? null;
            
            if (!$userId) {
                throw new Exception("Utilisateur non connecté");
            }
            
            // Créer le quiz
            $stmt = $this->db->prepare("
                INSERT INTO quiz_quizzes (title, description, created_by, created_at) 
                VALUES (?, ?, ?, NOW())
            ");
            
            $stmt->execute([$title, $description, $userId]);
            $quizId = $this->db->lastInsertId();
            
            if (!$quizId) {
                throw new Exception("Échec de la création du quiz");
            }
            
            // Ajouter les questions au quiz
            $orderNum = 1;
            $existingQuestions = [];
            
            // Récupérer les questions existantes pour ce quiz
            $stmtExisting = $this->db->prepare("
                SELECT question_id 
                FROM quiz_quiz_questions 
                WHERE quiz_id = ?
            ");
            $stmtExisting->execute([$quizId]);
            while ($row = $stmtExisting->fetch(PDO::FETCH_ASSOC)) {
                $existingQuestions[] = $row['question_id'];
            }

            foreach ($questions as $question) {
                // Vérifier si la question n'est pas déjà dans le quiz
                if (!in_array($question['Id_question'], $existingQuestions)) {
                    $stmt = $this->db->prepare("
                        INSERT INTO quiz_quiz_questions (quiz_id, question_id, question_order)
                        VALUES (?, ?, ?)
                    ");
                    try {
                        $stmt->execute([$quizId, $question['Id_question'], $orderNum++]);
                    } catch (PDOException $e) {
                        // Log l'erreur mais continuer avec les autres questions
                        error_log("Erreur lors de l'ajout de la question {$question['Id_question']} au quiz $quizId: " . $e->getMessage());
                        continue;
                    }
                }
            }
            
            // Rediriger vers le quiz
            $_SESSION['success_message'] = "Quiz créé avec succès!";
            header("Location: index.php?action=play_quiz&id=" . $quizId);
            exit();
            
        } catch (Exception $e) {
            error_log("Debug handlePlayQuiz: Erreur - " . $e->getMessage());
            $_SESSION['error_message'] = "Une erreur est survenue : " . $e->getMessage();
            header("Location: index.php?action=select_quiz");
            exit();
        }
    }
    
    /**
     * Crée des questions de pour un quiz
     */
    private function createTestQuestions($quizId, $themeId, $difficultyId) {
        // Questions de base pour démarrer rapidement
        $questions = [
            [
                'question_text' => 'Quelle est la balise HTML pour créer un lien?',
                'answers' => [
                    ['text' => '<a>', 'is_correct' => true],
                    ['text' => '<link>', 'is_correct' => false],
                    ['text' => '<href>', 'is_correct' => false]
                ]
            ]
        ];
        
        // Ajouter les questions à la base de données        
        foreach ($questions as $index => $q) {
            // Créer la question avec tous les paramètres requis
            $questionId = $this->questionModel->createQuestion(
                $q['question_text'],
                null, // pas d'image pour les questions de test
                $_SESSION['user_id'] ?? 1 // utiliser l'ID de l'utilisateur connecté ou 1 par défaut
            );
            
            if ($questionId) {
                // Ajouter les réponses
                foreach ($q['answers'] as $a) {
                    $this->questionModel->addAnswer($questionId, $a['text'], $a['is_correct']);
                }
                
                // Lier au quiz
                $this->quizModel->addQuestionToQuiz($quizId, $questionId, $index + 1);
            }
        }
    }

    /**
     * Affiche les questions d'un quiz pour y jouer
     */
    public function play($id) {
        try {
            error_log("Debug play: Début avec ID=$id");
            
            // Récupérer le quiz
            $quiz = $this->quizModel->getQuizById($id);
            error_log("Debug play: Quiz trouvé - " . print_r($quiz, true));
            
            if (!$quiz) {
                throw new Exception("Quiz non trouvé");
            }

            // Récupérer les questions du quiz
            $stmt = $this->db->prepare("
                SELECT q.*, qq.question_order
                FROM quiz_question q
                JOIN quiz_quiz_questions qq ON q.Id_question = qq.question_id
                WHERE qq.quiz_id = ?
                ORDER BY qq.question_order ASC
            ");
            $stmt->execute([$id]);
            $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
            error_log("Debug play: Nombre de questions trouvées - " . count($questions));

            if (empty($questions)) {
                throw new Exception("Aucune question trouvée pour ce quiz");
            }

            // Récupérer les réponses pour chaque question
            foreach ($questions as &$question) {
                $stmt = $this->db->prepare("
                    SELECT Id_question_answer, text, correct 
                    FROM quiz_question_answer 
                    WHERE Id_question = ?
                ");
                $stmt->execute([$question['Id_question']]);
                $question['answers'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
                error_log("Debug play: Réponses pour question {$question['Id_question']} - " . count($question['answers']));
            }

            // Initialiser ou récupérer l'index de la question courante
            $currentQuestionIndex = isset($_SESSION['current_question_index']) ? $_SESSION['current_question_index'] : 0;
            $_SESSION['quiz_id'] = $id;

            error_log("Debug Play: Quiz ID reçu = " . $id);
            error_log("Debug Play: Nombre de questions - " . count($questions));
            error_log("Debug Play: Index question courante - " . $currentQuestionIndex);
            
            if (isset($questions[$currentQuestionIndex])) {
                error_log("Debug Play: Question courante - " . print_r($questions[$currentQuestionIndex], true));
            }

            // Afficher la vue
            $this->view('quiz/play', [
                'quiz' => $quiz,
                'questions' => $questions,
                'currentQuestionIndex' => $currentQuestionIndex,
                'quizId' => $id,
                'totalQuestions' => count($questions)
            ]);

        } catch (Exception $e) {
            error_log("Debug play: Erreur - " . $e->getMessage());
            $_SESSION['error_message'] = "Une erreur est survenue : " . $e->getMessage();
            header("Location: index.php?action=select_quiz");
            exit();
        }
    }

    /**
     * Traite la soumission d'une réponse et passe à la question suivante
     */
    public function nextQuestion() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?action=select_quiz");
            exit();
        }
        
        $quizId = $_SESSION['quiz_id'] ?? 0;
        $currentQuestionIndex = isset($_POST['current_question_index']) ? intval($_POST['current_question_index']) : 0;
        $questions = $_SESSION['quiz_questions'] ?? [];
        
        // Enregistrer la réponse actuelle
        if (isset($_POST['answer'])) {
            $questionId = $questions[$currentQuestionIndex]['id'];
            $_SESSION['quiz_answers'][$questionId] = $_POST['answer'];
        }
        
        $nextIndex = $currentQuestionIndex + 1;
        
        // Vérifier si c'est la dernière question
        if ($nextIndex >= count($questions)) {
            // Rediriger vers la page de résultats
            header("Location: index.php?action=submit_quiz");
            exit();
        }
        
        // Afficher la question suivante
        $this->view('quiz/play', [
            'quiz' => $this->quizModel->getQuizById($quizId),
            'questions' => $questions,
            'currentQuestionIndex' => $nextIndex,
            'quizId' => $quizId,
            'totalQuestions' => count($questions)
        ]);
    }

    /**
     * Traite la soumission finale du quiz et affiche les résultats
     */
    public function submitQuiz() {
        try {
            error_log("Debug submitQuiz: Début de la méthode");
            error_log("Debug submitQuiz: Session=" . print_r($_SESSION, true));
            error_log("Debug submitQuiz: POST=" . print_r($_POST, true));

            if (!isset($_SESSION['user_id'])) {
                error_log("Debug submitQuiz: Utilisateur non connecté");
                throw new Exception("Utilisateur non connecté");
            }

            $quizId = $_POST['quiz_id'] ?? null;
            $currentAnswer = $_POST['answer'] ?? null;
            $currentQuestionIndex = isset($_POST['current_question_index']) ? intval($_POST['current_question_index']) : 0;

            error_log("Debug submitQuiz: quizId=$quizId, currentAnswer=$currentAnswer, currentQuestionIndex=$currentQuestionIndex");

            if (!$quizId) {
                error_log("Debug submitQuiz: ID du quiz manquant");
                throw new Exception("ID du quiz manquant");
            }

            // Récupérer le quiz
            $quiz = $this->quizModel->getQuizById($quizId);
            error_log("Debug submitQuiz: Quiz récupéré=" . print_r($quiz, true));
            
            if (!$quiz) {
                error_log("Debug submitQuiz: Quiz non trouvé");
                throw new Exception("Quiz non trouvé");
            }

            // Sauvegarder la réponse actuelle
            if ($currentAnswer) {
                if (!isset($_SESSION['quiz_answers'])) {
                    $_SESSION['quiz_answers'] = [];
                }
                $_SESSION['quiz_answers'][$currentQuestionIndex] = $currentAnswer;
                error_log("Debug submitQuiz: Réponse sauvegardée pour question $currentQuestionIndex: $currentAnswer");
            }

            // Récupérer les questions du quiz
            $stmt = $this->db->prepare("
                SELECT q.*, qq.question_order
                FROM quiz_question q
                JOIN quiz_quiz_questions qq ON q.Id_question = qq.question_id
                WHERE qq.quiz_id = ?
                ORDER BY qq.question_order ASC
            ");
            $stmt->execute([$quizId]);
            $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            error_log("Debug submitQuiz: Nombre de questions trouvées: " . count($questions));

            if (empty($questions)) {
                error_log("Debug submitQuiz: Aucune question trouvée pour ce quiz");
                throw new Exception("Aucune question trouvée pour ce quiz");
            }

            // Récupérer les réponses pour chaque question
            foreach ($questions as &$question) {
                $stmt = $this->db->prepare("
                    SELECT * FROM quiz_question_answer 
                    WHERE Id_question = ?
                    ORDER BY RAND()
                ");
                $stmt->execute([$question['Id_question']]);
                $question['answers'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
                error_log("Debug submitQuiz: Réponses pour question {$question['Id_question']}: " . count($question['answers']));
            }

            error_log("Debug submitQuiz: currentQuestionIndex=$currentQuestionIndex, total questions=" . count($questions));

            // Vérifier si c'est la dernière question
            if ($currentQuestionIndex >= count($questions) - 1) {
                error_log("Debug submitQuiz: Dernière question atteinte, calcul des résultats");
                header("Location: index.php?action=show_results");
                exit();
            } else {
                error_log("Debug submitQuiz: Passage à la question suivante");
                // Passer à la question suivante
                $currentQuestionIndex++;

                // Afficher la question suivante
                $this->view('quiz/play', [
                    'quiz' => $quiz,
                    'questions' => $questions,
                    'currentQuestionIndex' => $currentQuestionIndex,
                    'quizId' => $quizId,
                    'totalQuestions' => count($questions)
                ]);
            }
        } catch (Exception $e) {
            error_log("Erreur dans submitQuiz: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            $_SESSION['error_message'] = "Une erreur est survenue lors de la soumission du quiz: " . $e->getMessage();
            header("Location: index.php?action=select_quiz");
            exit();
        }
    }

    /**
     * Affiche l'historique des résultats de l'utilisateur
     */
    public function history() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit();
        }
        
        $history = $this->quizModel->getUserQuizHistory($_SESSION['user_id']);
        $this->view('user/history', ['history' => $history]);
    }

    /**
     * Affiche les résultats du quiz
     */
    public function showResults() {
        try {
            error_log("Debug showResults: Début de la méthode");
            error_log("Debug showResults: Session=" . print_r($_SESSION, true));

            if (!isset($_SESSION['quiz_answers']) || !isset($_SESSION['quiz_id'])) {
                throw new Exception("Données du quiz manquantes");
            }

            $quizId = $_SESSION['quiz_id'];
            $answers = $_SESSION['quiz_answers'];

            // Récupérer le quiz
            $quiz = $this->quizModel->getQuizById($quizId);
            if (!$quiz) {
                throw new Exception("Quiz non trouvé");
            }

            // Récupérer toutes les questions du quiz avec leurs réponses
            $stmt = $this->db->prepare("
                SELECT q.*, qq.question_order,
                       qa.Id_question_answer, qa.text as answer_text, qa.correct
                FROM quiz_question q
                JOIN quiz_quiz_questions qq ON q.Id_question = qq.question_id
                JOIN quiz_question_answer qa ON q.Id_question = qa.Id_question
                WHERE qq.quiz_id = ?
                ORDER BY qq.question_order
            ");
            $stmt->execute([$quizId]);

            $questions = [];
            $questionResults = [];
            $score = 0;
            $totalQuestions = 0;
            $previousQuestionId = null;
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                if ($previousQuestionId !== $row['Id_question']) {
                    $totalQuestions++;
                    $previousQuestionId = $row['Id_question'];
                    
                    $userAnswerId = $answers[$totalQuestions - 1] ?? null;
                    $isCorrect = ($userAnswerId == $row['Id_question_answer'] && $row['correct'] == 1);
                    
                    if ($isCorrect) {
                        $score++;
                    }
                    
                    $questionResults[] = [
                        'question' => $row['text'],
                        'correct' => $isCorrect,
                        'correctAnswer' => $this->quizModel->getCorrectAnswerText($row['Id_question']),
                        'userAnswer' => $userAnswerId
                    ];
                }
            }

            // Ajouter les réponses correctes aux questions
            foreach ($questions as &$question) {
                $correct_answer = $this->quizModel->getCorrectAnswer($question['Id_question']);
                $question['correct_answer'] = $correct_answer['reponse']; // Assurez-vous que 'reponse' est le bon nom de colonne
            }

            // Calculer le pourcentage
            $percentageScore = ($totalQuestions > 0) ? ($score / $totalQuestions * 100) : 0;

            // Sauvegarder le résultat
            $stmt = $this->db->prepare("
                INSERT INTO quiz_game_history 
                (player_id, quiz_id, score, total_questions, completed, played_on)
                VALUES (?, ?, ?, ?, 1, NOW())
            ");
            $stmt->execute([$_SESSION['user_id'], $quizId, $score, $totalQuestions]);

            // Nettoyer la session
            unset($_SESSION['quiz_answers']);
            unset($_SESSION['quiz_id']);
            unset($_SESSION['current_question_index']);

            error_log("Debug showResults: Score=$score, Total=$totalQuestions, Percentage=$percentageScore");
            error_log("Debug showResults: Questions=" . print_r($questionResults, true));

            // Afficher les résultats
            $this->view('quiz/results', [
                'quiz' => $quiz,
                'score' => $score,
                'totalQuestions' => $totalQuestions,
                'percentageScore' => $percentageScore,
                'questionResults' => $questionResults,
                'quizId' => $quizId,
                'questions' => $questions
            ]);

        } catch (Exception $e) {
            error_log("Erreur dans showResults: " . $e->getMessage());
            error_log("Trace: " . $e->getTraceAsString());
            $_SESSION['error_message'] = "Une erreur est survenue lors de l'affichage des résultats";
            header("Location: index.php?action=select_quiz");
            exit();
        }
    }
}
?>