<?php
// quiz_project/controllers/AdminController.php
class AdminController extends Controller {
    private $questionManager;
    private $quizModel;
    private $userModel; // Ajout pour la validation des utilisateurs
    protected $db; // Changer 'private' en 'protected' pour correspondre à la classe parent

    public function __construct($db) {
        parent::__construct($db);
        
        // Vérification d'authentification uniforme
        $this->ensureAdminAccess();
        
        // Initialisation des modèles
        $this->questionManager = new QuestionManager($db);
        $this->quizModel = new QuizModel($db);
        $this->userModel = new UserModel($db);
    }

    /**
     * Méthode unifiée pour vérifier l'accès administrateur
     * Combine les anciennes vérifications en une seule logique cohérente
     */
    private function ensureAdminAccess() {
        // Vérification de base - l'utilisateur doit être connecté
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit();
        }
        
        // Vérification du rôle - doit être admin ou super_admin
        $isAdmin = false;
        
        if (isset($_SESSION['user_role'])) {
            $isAdmin = in_array($_SESSION['user_role'], ['admin', 'super_admin']);
        }
        
        if (isset($_SESSION['user_type'])) {
            $isAdmin = $isAdmin || $_SESSION['user_type'] === 'admin';
        }
        
        if (isset($_SESSION['admin_logged_in'])) {
            $isAdmin = $isAdmin || $_SESSION['admin_logged_in'] === true;
        }
        
        if (!$isAdmin) {
            // L'utilisateur n'est pas un administrateur
            $_SESSION['error'] = "Accès refusé. Vous devez être administrateur pour accéder à cette page.";
            header("Location: index.php?action=login");
            exit();
        }
    }

    // --- Question Methods ---

    public function index() {
        $this->view('admin/dashboard');
    }

    public function dashboard() {
        $this->ensureAdminAccess();
        // Debug info
        error_log("Admin Dashboard - Session: " . print_r($_SESSION, true));
        
        // Inclure la vue du tableau de bord admin
        include 'views/admin/admin_dashboard.php';
    }

    /**
     * Liste des questions (anciennement questionList)
     */
    public function indexQuestions() {
        // Débogage
        error_log("indexQuestions appelée");
        error_log("GET params: " . print_r($_GET, true));
        
        // Vérifier les messages de session
        if (isset($_SESSION['success'])) {
            error_log("Message de succès présent: " . $_SESSION['success']);
        }
        if (isset($_SESSION['error'])) {
            error_log("Message d'erreur présent: " . $_SESSION['error']);
        }
        
        $questions = $this->questionManager->getAllQuestions();
        // Simplification : on ne récupère plus les catégories et difficultés
        $this->view('admin/question_list', ['questions' => $questions]);
    }

    /**
     * Formulaire pour ajouter/modifier une question (anciennement questionForm)
     */
    public function formQuestion($id = null) {
        // Simplification : suppression des catégories et difficultés
        $question = $id ? $this->questionManager->getQuestionById($id) : null;
        $answers = $id ? $this->questionManager->getAnswersForQuestion($id) : [];
        
        $this->view('admin/question_form', ['question' => $question, 'answers' => $answers]);
    }

    /**
     * Modifier une question existante (anciennement editQuestion)
     */
    public function editQuestion() {
        // Si un ID est spécifié dans l'URL, afficher le formulaire d'édition        
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $questionId = intval($_GET['id']);
            // Utiliser la méthode formQuestion pour afficher le formulaire avec les données
            $this->formQuestion($questionId);
        } else {
            // Afficher la liste des questions pour sélection
            $questions = $this->questionManager->getAllQuestions();
            
            // Supprimer toute variable de session qui pourrait afficher une modal
            if (isset($_SESSION['question_added'])) {
                unset($_SESSION['question_added']);
            }
            
            $this->view('admin/question_list', [
                'questions' => $questions, 
                'mode' => 'edit'
            ]);
        }
    }

    /**
     * Ajouter une nouvelle question (anciennement addQuestion)
     */
    public function createQuestion() {
        // Utiliser formQuestion sans ID pour le formulaire d'ajout
        $this->formQuestion();
    }

    /**
     * Sauvegarder une question nouvelle ou modifiée (anciennement saveQuestion)
     */
    public function storeQuestion() {
        // Vérifiez si le formulaire a été soumis
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupérer les données du formulaire
            $questionText = isset($_POST['question_text']) ? trim($_POST['question_text']) : '';
            
            // Traitement de l'image
            $picture = null;
            $imageSource = isset($_POST['image_source']) ? $_POST['image_source'] : 'url';
            
            if ($imageSource === 'url') {
                // Option URL d'image
                $pictureUrl = isset($_POST['picture_url']) ? trim($_POST['picture_url']) : '';
                if (!empty($pictureUrl)) {
                    $picture = $pictureUrl;
                }
            } else if ($imageSource === 'upload' && isset($_FILES['picture_file']) && $_FILES['picture_file']['error'] === 0) {
                $uploadDir = realpath(dirname(__FILE__) . '/../uploads/questions') . '/';
                
                // Créer le dossier s'il n'existe pas
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                $fileInfo = pathinfo($_FILES['picture_file']['name']);
                $extension = strtolower($fileInfo['extension']);
                
                // Vérifier l'extension
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                if (in_array($extension, $allowedExtensions)) {
                    $uniqueName = uniqid('quest_') . '.' . $extension;
                    $uploadFilePath = $uploadDir . $uniqueName;
                    
                    // Déplacer le fichier uploadé
                    if (move_uploaded_file($_FILES['picture_file']['tmp_name'], $uploadFilePath)) {
                        // Pour la BDD, utiliser un chemin relatif pour faciliter l'affichage
                        $picture = 'uploads/questions/' . $uniqueName;
                        
                        // Log pour debug
                        error_log("Image uploadée avec succès: " . $uploadFilePath);
                        error_log("Chemin enregistré en BDD: " . $picture);
                    } else {
                        $errors[] = "Échec de l'upload du fichier image";
                        error_log("Échec de l'upload vers: " . $uploadFilePath);
                        error_log("Erreur: " . print_r(error_get_last(), true));
                    }
                } else {
                    $errors[] = "Format d'image non autorisé. Utilisez JPG, PNG ou GIF";
                }
            }
            
            // Récupérer les réponses
            $answers = isset($_POST['answers']) ? $_POST['answers'] : [];
            $correct = isset($_POST['correct']) ? intval($_POST['correct']) : -1;
            
            // Validation basique
            $errors = [];
            if (empty($questionText)) {
                $errors[] = "Le texte de la question est obligatoire.";
            }
            if (count($answers) < 2) {
                $errors[] = "Au moins deux réponses sont requises.";
            }
            if ($correct < 0 || $correct >= count($answers)) {
                $errors[] = "Veuillez sélectionner une réponse correcte valide.";
            }
            
            // Si des erreurs sont présentes, réafficher le formulaire avec les erreurs
            if (!empty($errors)) {
                $question = [
                    'Id_question' => isset($_POST['Id_question']) ? $_POST['Id_question'] : '',
                    'text' => $questionText,
                    'picture' => $picture,
                ];
                
                // Afficher le formulaire avec les erreurs
                $this->view('admin/question_form', ['errors' => $errors, 'question' => $question, 'answers' => $answers]);
                return;
            }
            
            // Récupérer l'ID de l'admin connecté
            $adminId = isset($_SESSION['admin_id']) ? $_SESSION['admin_id'] : (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 1);
            
            try {
                $this->db->beginTransaction();
                
                // Déterminer s'il s'agit d'un ajout ou d'une mise à jour
                $questionId = isset($_POST['Id_question']) ? intval($_POST['Id_question']) : 0;
                
                if ($questionId > 0) {
                    // Mise à jour d'une question existante
                    $this->questionManager->updateQuestion([
                        'Id_question' => $questionId,
                        'text' => $questionText,
                        'picture' => $picture
                    ]);
                } else {
                    // Ajout d'une nouvelle question
                    $questionId = $this->questionManager->addQuestion([
                        'text' => $questionText,
                        'Id_admin_editor' => $adminId,
                        'picture' => $picture
                    ]);
                }
                
                if ($questionId) {
                    // Supprimer d'abord les réponses existantes si c'est une mise à jour
                    if (isset($_POST['Id_question'])) {
                        $this->deleteAnswersForQuestion($questionId);
                    }
                    
                    // Ajouter les réponses
                    foreach ($answers as $index => $answerText) {
                        $isCorrect = ($index == $correct) ? 1 : 0;
                        $this->questionManager->addQuestionAnswer([
                            'text' => $answerText,
                            'correct' => $isCorrect,
                            'Id_question' => $questionId
                        ]);
                    }

                    $this->db->commit();
                    
                    // Définir la variable de session uniquement pour les nouvelles questions, pas pour les modifications
                    if (!isset($_POST['Id_question'])) {
                        $_SESSION['question_added'] = true;
                    } else {
                        // Pour les modifications, utiliser un message différent qui n'affiche pas de modal
                        $_SESSION['success'] = "Question modifiée avec succès.";
                    }
                    
                    // Rediriger vers la liste des questions
                    header('Location: index.php?action=questions');
                    exit;
                } else {
                    throw new Exception("Impossible d'ajouter/modifier la question.");
                }
            } catch (Exception $e) {
                $this->db->rollBack();
                
                $errors[] = "Une erreur est survenue : " . $e->getMessage();
                                
                $question = [
                    'Id_question' => isset($_POST['Id_question']) ? $_POST['Id_question'] : '',
                    'text' => $questionText,
                    'picture' => $picture,
                ];
                
                // Afficher le formulaire avec les erreurs
                $this->view('admin/question_form', ['errors' => $errors, 'question' => $question, 'answers' => $answers]);
            }
        } else {
            // Si accès direct à cette URL sans POST, rediriger vers le formulaire
            header('Location: index.php?action=createQuestion');
            exit();
        }
    }

    /**
     * Mise à jour d'une question (anciennement updateQuestion)
     */
    public function updateQuestion() {
        // Nous redirigeons vers storeQuestion qui gère maintenant aussi les mises à jour
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->storeQuestion();
        } else {
            header("Location: index.php?action=questions");
            exit();
        }
    }

    /**
     * Supprimer une question (anciennement deleteQuestion)
     */
    public function deleteQuestion() {
        // Vérification des permissions
        if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], ['admin', 'super_admin'])) {
            header('Location: index.php?action=login');
            exit();
        }
        
        $questionId = $_GET['id'] ?? null;
        if (!$questionId) {
            $_SESSION['error'] = "ID de question manquant.";
            header('Location: index.php?action=questions');
            exit();
        }
        
        $questionModel = new QuestionModel($this->db);
        
        // Traitement de la confirmation de suppression
        if (isset($_POST['confirm_delete']) && $_POST['confirm_delete'] === 'yes') {
            // Suppression directe et simple
            try {
                // Requête SQL directe pour contourner d'éventuelles contraintes
                $sql = "DELETE FROM quiz_question WHERE Id_question = ?";
                $stmt = $this->db->prepare($sql);
                $result = $stmt->execute([$questionId]);
                
                if ($result && $stmt->rowCount() > 0) {
                    $_SESSION['success'] = "Question supprimée avec succès.";
                } else {
                    $_SESSION['error'] = "Aucune question n'a été supprimée. Vérifiez l'ID.";
                }
            } catch (Exception $e) {
                $_SESSION['error'] = "Erreur lors de la suppression: " . $e->getMessage();
                error_log("Erreur SQL: " . $e->getMessage());
            }
            
            header('Location: index.php?action=questions');
            exit();
        }
        
        // Récupération des données de la question
        $question = $questionModel->getQuestionById($questionId);
        if (!$question) {
            $_SESSION['error'] = "Question introuvable.";
            header('Location: index.php?action=questions');
            exit();
        }
        
        // Affichage direct de la page de confirmation
        echo '<!DOCTYPE html>
<html>
<head>
    <title>Supprimer une question</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card">
                    <div class="card-header bg-danger text-white">
                        <h2>Confirmer la suppression</h2>
                    </div>
                    <div class="card-body">
                        <h5>Êtes-vous sûr de vouloir supprimer cette question ?</h5>
                        
                        <div class="alert alert-warning mt-3">
                            <strong>ID :</strong> '.htmlspecialchars($question['Id_question']).'<br>
                            <strong>Question :</strong> '.htmlspecialchars($question['text']).'
                            
                            '.(!empty($question['picture']) ? '
                            <div class="mt-2">
                                <strong>Image de la question</strong><br>
                                <img src="'.htmlspecialchars($question['picture']).'" class="img-fluid img-thumbnail" style="max-height: 200px;">
                            </div>' : '').'
                        </div>
                        
                        <div class="alert alert-danger">
                            <strong>Attention :</strong> Cette action est irréversible et supprimera également toutes les réponses associées à cette question.
                        </div>
                        
                        <form method="post" action="index.php?action=deleteQuestion&id='.htmlspecialchars($question['Id_question']).'">
                            <input type="hidden" name="confirm_delete" value="yes">
                            
                            <div class="d-flex justify-content-between mt-4">
                                <a href="index.php?action=questions" class="btn btn-secondary">Annuler</a>
                                <button type="submit" class="btn btn-danger">Confirmer la suppression</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>';
    }

    // --- Answer Methods ---

    public function deleteAnswersForQuestion($Id_question) {
        $stmt = $this->db->prepare("DELETE FROM quiz_question_answer WHERE Id_question = ?");
        $stmt->execute([$Id_question]);
    }

    // --- Quiz Methods ---

    /**
     * Liste des quiz (anciennement quizList)
     */
    public function indexQuizzes() {
        $quizzes = $this->quizModel->getAllQuizzes();
        $this->view('admin/quiz_list', ['quizzes' => $quizzes]);
    }

    /**
     * Formulaire pour ajouter/modifier un quiz (anciennement quizForm)
     */
    public function formQuiz($id = null) {
        $quiz = $id ? $this->quizModel->getQuizById($id) : null;
        $this->view('admin/quiz_form', ['quiz' => $quiz]);
    }

    /**
     * Ajouter un nouveau quiz
     */
    public function createQuiz() {
        // Utiliser formQuiz sans ID pour le formulaire d'ajout
        $this->formQuiz();
    }

    /**
     * Modifier un quiz existant
     */
    public function editQuiz() {
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $quizId = intval($_GET['id']);
            $this->formQuiz($quizId);
        } else {
            // Afficher la liste des quiz pour sélection
            header('Location: index.php?action=indexQuizzes');
            exit();
        }
    }

    /**
     * Sauvegarder un quiz nouveau ou modifié (anciennement saveQuiz)
     */
    public function storeQuiz() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = $_POST['title'];
            $description = $_POST['description'];
            $Id_admin_editor = $_SESSION['user_id']; // Récupérer l'ID de l'admin connecté

            // Validation des données du quiz
            $errors = $this->validateQuizForm($_POST);
            if (!empty($errors)) {
                $this->view('admin/quiz_form', ['errors' => $errors, 'quiz' => $_POST]);
                return;
            }

            if (empty($_POST['Id_quiz'])) {
                $this->quizModel->createQuiz($title, $description, $Id_admin_editor);
            } else {
                $Id_quiz = $_POST['Id_quiz'];
                $this->quizModel->updateQuiz($Id_quiz, $title, $description);
            }

            header("Location: index.php?action=indexQuizzes");
            exit();
        }
    }

    /**
     * Supprimer un quiz (anciennement deleteQuiz)
     */
    public function deleteQuiz($id) {
        $this->quizModel->deleteQuiz($id);
        header("Location: index.php?action=indexQuizzes");
        exit();
    }

    // --- Quiz Question Management ---

    public function manageQuizQuestions($Id_quiz) {
        $quiz = $this->quizModel->getQuizById($Id_quiz);
        $questionsInQuiz = $this->quizModel->getQuestionsForQuiz($Id_quiz);
        $questionsNotInQuiz = $this->quizModel->getQuestionsNotInQuiz($Id_quiz);
        $this->view('admin/quiz_question_management', [
            'quiz' => $quiz,
            'questionsInQuiz' => $questionsInQuiz,
            'questionsNotInQuiz' => $questionsNotInQuiz,
        ]);
    }

    public function addQuestionToQuiz($Id_quiz, $Id_question) {
        $this->quizModel->addQuestionToQuiz($Id_quiz, $Id_question);
        header("Location: index.php?action=manageQuizQuestions&id=$Id_quiz");
        exit();
    }

    public function removeQuestionFromQuiz($Id_quiz, $Id_question) {
        $this->quizModel->removeQuestionFromQuiz($Id_quiz, $Id_question);
        header("Location: index.php?action=manageQuizQuestions&id=$Id_quiz");
        exit();
    }

    // --- User Methods ---

    /**
     * Liste des utilisateurs (anciennement userList)
     */
    public function indexUsers() {
        $users = $this->userModel->getAllUsers();
        $this->view('admin/user_list', ['users' => $users]);
    }

    /**
     * Formulaire pour ajouter/modifier un utilisateur (anciennement userForm)
     */
    public function formUser($id = null, $type = 'player') {
        $user = $id ? $this->userModel->getUserById($id, $type) : null;
        $this->view('admin/user_form', ['user' => $user, 'type' => $type]);
    }

    /**
     * Ajouter un nouvel utilisateur
     */
    public function createUser() {
        // Utiliser formUser sans ID pour le formulaire d'ajout
        $this->formUser();
    }

    /**
     * Modifier un utilisateur existant
     */
    public function editUser() {
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $userId = intval($_GET['id']);
            $type = isset($_GET['type']) ? $_GET['type'] : 'player';
            $this->formUser($userId, $type);
        } else {
            // Afficher la liste des utilisateurs pour sélection
            header('Location: index.php?action=indexUsers');
            exit();
        }
    }

    /**
     * Sauvegarder un utilisateur nouveau ou modifié (anciennement saveUser)
     */
    public function storeUser() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $type = $_POST['type'];
            $data = [
                'name' => $_POST['name'],
                'first_name' => $_POST['first_name'],
                'email' => $_POST['email'],
                'password' => password_hash($_POST['password'], PASSWORD_DEFAULT), // Hash le mot de passe
            ];

            // Validation des données de l'utilisateur
            $errors = $this->validateUserForm($_POST);
            if (!empty($errors)) {
                $this->view('admin/user_form', ['errors' => $errors, 'user' => $_POST, 'type' => $type]);
                return;
            }

            if (empty($_POST['Id'])) {
                $this->userModel->createUser($data, $type);
            } else {
                $this->userModel->updateUser($_POST['Id'], $data, $type);
            }

            header("Location: index.php?action=indexUsers");
            exit();
        }
    }

    /**
     * Supprimer un utilisateur (anciennement deleteUser)
     */
    public function deleteUser($id, $type = 'player') {
        $this->userModel->deleteUser($id, $type);
        header("Location: index.php?action=indexUsers");
        exit();
    }

    private function validateUserForm($data) {
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
        if (empty($data['password'])) {
            $errors['password'] = "Le mot de passe est requis.";
        }
        if (strlen($data['password']) < 8) {
            $errors['password'] = "Le mot de passe doit contenir au moins 8 caractères.";
        }

        return $errors;
    }

    // --- Category Methods ---

    /**
     * Liste des catégories (anciennement categoryList)
     */
    public function indexCategories() {
        $categories = $this->questionManager->getAllCategories();
        foreach ($categories as &$category) {
            $category['details'] = $this->questionManager->getCategoryDetails($category['Id_question_category']);
        }
        $this->view('admin/category_list', ['categories' => $categories]);
    }

    /**
     * Formulaire pour ajouter/modifier une catégorie (anciennement categoryForm)
     */
    public function formCategory($id = null) {
        $category = $id ? $this->questionManager->getCategoryDetails($id) : null;
        $this->view('admin/category_form', ['category' => $category, 'id' => $id]);
    }

    /**
     * Ajouter une nouvelle catégorie
     */
    public function createCategory() {
        // Utiliser formCategory sans ID pour le formulaire d'ajout
        $this->formCategory();
    }

    /**
     * Modifier une catégorie existante
     */
    public function editCategory() {
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $categoryId = intval($_GET['id']);
            $this->formCategory($categoryId);
        } else {
            // Afficher la liste des catégories pour sélection
            header('Location: index.php?action=indexCategories');
            exit();
        }
    }

    /**
     * Sauvegarder une catégorie nouvelle ou modifiée (anciennement saveCategory)
     */
    public function storeCategory() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $details = [
                'picture' => $_POST['picture'],
                'active' => $_POST['active'],
                'title' => $_POST['title'],
                'text' => $_POST['text'],
            ];

            // Validation des données de la catégorie
            $errors = $this->validateCategoryForm($_POST);
            if (!empty($errors)) {
                $this->view('admin/category_form', ['errors' => $errors, 'category' => $_POST]);
                return;
            }

            if (empty($_POST['Id_question_category'])) {
                $id = $this->questionManager->createCategory();
                $details['Id_question_category'] = $id;
                $this->questionManager->updateCategory($id, $details);
            } else {
                $this->questionManager->updateCategory($_POST['Id_question_category'], $details);
            }

            header("Location: index.php?action=indexCategories");
            exit();
        }
    }

    private function validateCategoryForm($data) {
        $errors = [];

        if (empty($data['title'])) {
            $errors['title'] = "Le titre de la catégorie est requis.";
        }
        if (strlen($data['title']) > 255) {
            $errors['title'] = "Le titre de la catégorie ne doit pas dépasser 255 caractères.";
        }
        if (strlen($data['text']) > 1000) {
            $errors['text'] = "La description de la catégorie ne doit pas dépasser 1000 caractères.";
        }
        if (empty($data['active'])) {
            $errors['active'] = "Le statut active est requis.";
        }

        return $errors;
    }

    /**
     * Supprimer une catégorie (anciennement deleteCategory)
     */
    public function deleteCategory($id) {
        $this->questionManager->deleteCategory($id);
        header("Location: index.php?action=categoryList");
        exit();
    }

    // --- Difficulty Methods ---

    public function difficultyList() {
        $difficulties = $this->questionManager->getAllDifficulties();
        $this->view('admin/difficulty_list', ['difficulties' => $difficulties]);
    }

    public function difficultyForm($id = null) {
        $difficulty = $id ? $this->questionManager->getDifficultyName($id) : null;
        $this->view('admin/difficulty_form', ['difficulty' => $difficulty, 'id' => $id]);
    }

    public function saveDifficulty() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];

            // Validation des données de la difficulté
            $errors = $this->validateDifficultyForm($_POST);
            if (!empty($errors)) {
                $this->view('admin/difficulty_form', ['errors' => $errors, 'difficulty' => $_POST]);
                return;
            }

            if (empty($_POST['Id_question_difficulte'])) {
                $this->questionManager->createDifficulty($name);
            } else {
                $this->questionManager->updateDifficulty($_POST['Id_question_difficulte'], $name);
            }

            header("Location: index.php?action=difficultyList");
            exit();
        }
    }

    private function validateDifficultyForm($data) {
        $errors = [];

        if (empty($data['name'])) {
            $errors['name'] = "Le nom de la difficulté est requis.";
        }
        if (strlen($data['name']) > 50) {
            $errors['name'] = "Le nom de la difficulté ne doit pas dépasser 50 caractères.";
        }

        return $errors;
    }

    public function deleteDifficulty($id) {
        $this->questionManager->deleteDifficulty($id);
        header("Location: index.php?action=difficultyList");
        exit();
    }
}
?>
