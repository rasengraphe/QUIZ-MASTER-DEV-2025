<?php
require_once 'models/QuestionManager.php';

class QuestionController {
    private $questionManager;
    private $uploadDir;
    
    public function __construct() {
        $this->questionManager = new QuestionManager();
        // Définir le chemin du dossier d'upload de façon absolue
        $this->uploadDir = realpath(dirname(__FILE__) . '/../uploads/questions') . '/';
        
        // Créer le dossier s'il n'existe pas
        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0777, true);
        }
    }
    
    public function saveQuestion() {
        if (!isset($_SESSION['user_id'])) {
            echo "Session utilisateur non définie. Redirection vers la page de connexion.";
            header('Location: index.php?action=login');
            exit;
        }

        // Récupération des données de base
        $questionText = isset($_POST['question_text']) ? trim($_POST['question_text']) : '';
        $category = isset($_POST['category']) ? (int)$_POST['category'] : 0;
        $difficulte = isset($_POST['difficulte']) ? (int)$_POST['difficulte'] : 0;
        $answers = isset($_POST['answers']) ? $_POST['answers'] : [];
        $correct = isset($_POST['correct']) ? (int)$_POST['correct'] : -1;
        $adminId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 1;
        
        // Validation de base
        $errors = [];
        if (empty($questionText)) {
            $errors[] = "Le texte de la question est obligatoire";
        }
        if ($category <= 0) {
            $errors[] = "Veuillez sélectionner une catégorie";
        }
        if ($difficulte <= 0) {
            $errors[] = "Veuillez sélectionner un niveau de difficulté";
        }
        if (count($answers) < 3) {
            $errors[] = "Veuillez fournir 3 réponses";
        }
        if ($correct < 0 || $correct > 2) {
            $errors[] = "Veuillez indiquer la réponse correcte";
        }
        
        // Traitement de l'image
        $picturePath = null;
        $imageSource = isset($_POST['image_source']) ? $_POST['image_source'] : 'url';
        
        if ($imageSource === 'url') {
            // Option URL d'image
            $pictureUrl = isset($_POST['picture_url']) ? trim($_POST['picture_url']) : '';
            if (!empty($pictureUrl)) {
                $picturePath = $pictureUrl;
            }
        } else if ($imageSource === 'upload') {
            // Option upload de fichier
            if (isset($_FILES['picture_file']) && $_FILES['picture_file']['error'] === 0) {
                $fileInfo = pathinfo($_FILES['picture_file']['name']);
                $extension = strtolower($fileInfo['extension']);
                
                // Vérifier l'extension
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                if (in_array($extension, $allowedExtensions)) {
                    $uniqueName = uniqid('quest_') . '.' . $extension;
                    $uploadFilePath = $this->uploadDir . $uniqueName;
                    
                    // Déplacer le fichier uploadé
                    if (move_uploaded_file($_FILES['picture_file']['tmp_name'], $uploadFilePath)) {
                        // Pour la BDD, utiliser un chemin relatif pour faciliter l'affichage
                        $picturePath = 'uploads/questions/' . $uniqueName;
                        
                        // Log pour debug
                        error_log("Image uploadée avec succès: " . $uploadFilePath);
                        error_log("Chemin enregistré en BDD: " . $picturePath);
                    } else {
                        $errors[] = "Échec de l'upload du fichier image";
                        error_log("Échec de l'upload vers: " . $uploadFilePath);
                        error_log("Erreur: " . print_r(error_get_last(), true));
                    }
                } else {
                    $errors[] = "Format d'image non autorisé. Utilisez JPG, PNG ou GIF";
                }
            }
        }
        
        // S'il y a des erreurs, réafficher le formulaire
        if (!empty($errors)) {
            $categories = $this->questionManager->getAllCategories();
            $difficultes = $this->questionManager->getAllDifficultes();
            
            include 'views/admin/question_form.php';
            return;
        }
        
        // Enregistrement de la question
        $questionId = $this->questionManager->addQuestion($category, $adminId, $difficulte, $questionText, $picturePath);
        
        if ($questionId) {
            // Enregistrement des réponses
            foreach ($answers as $index => $answer) {
                $isCorrect = ($index == $correct) ? 1 : 0;
                $this->questionManager->addAnswer($questionId, $answer, $isCorrect);
            }
            
            $_SESSION['question_added'] = true;
            header('Location: index.php?action=addQuestion');
            exit;
        } else {
            $errors[] = "Une erreur est survenue lors de l'enregistrement de la question.";
            $categories = $this->questionManager->getAllCategories();
            $difficultes = $this->questionManager->getAllDifficultes();
            
            include 'views/admin/question_form.php';
        }
    }
    
    public function addQuestion() {
        // Débogage
        error_log("Appel de addQuestion()");
        
        $categories = $this->questionManager->getAllCategories();
        $difficultes = $this->questionManager->getAllDifficultes();
        
        // Débogage
        error_log("Nombre de catégories: " . count($categories));
        error_log("Nombre de difficultés: " . count($difficultes));
        
        include 'views/admin/question_form.php';
    }
    
    public function clearSession() {
        if (isset($_GET['key']) && isset($_SESSION[$_GET['key']])) {
            unset($_SESSION[$_GET['key']]);
        }
        exit;
    }
}