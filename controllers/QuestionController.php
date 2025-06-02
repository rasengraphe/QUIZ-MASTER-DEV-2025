<?php
require_once __DIR__ . '/../core/Controller.php';
/**
 * Contrôleur de gestion des questions
 * Gère toutes les opérations liées aux questions du quiz :
 * - Création
 * - Modification
 * - Suppression
 * - Gestion des images associées
 */
class QuestionController extends Controller {
    /** @var QuestionModel Instance du modèle de questions */
    private $questionModel;

    /**
     * Constructeur du contrôleur
     * Initialise le modèle de questions
     * @param PDO $db Instance de la connexion à la base de données
     */
    public function __construct($db) {
        parent::__construct($db);
        $this->questionModel = new QuestionModel($db);
    }

    /**
     * Nettoie et valide les entrées
     * @param string $data Donnée à nettoyer
     * @return string Donnée nettoyée
     */
    private function cleanInput($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
        return $data;
    }

    /**
     * Méthode de mise à jour d'une question
     * Gère la modification complète d'une question :
     * - Texte de la question
     * - Réponses associées
     * - Image (upload ou URL)
     * - Catégorie et difficulté
     */
    public function update_question() {
        // Vérification de sécurité : l'utilisateur doit être connecté
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?action=login');
            exit();
        }

        // Logging pour debug
        error_log("POST: " . print_r($_POST, true));
        error_log("FILES: " . print_r($_FILES, true));

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validation et nettoyage de l'ID
            $questionId = filter_var(
                isset($_POST['Id_question']) ? $_POST['Id_question'] : 0,
                FILTER_VALIDATE_INT
            );

            if ($questionId === false || $questionId <= 0) {
                header('Location: index.php?action=edit_question&error=invalid_id');
                exit();
            }

            // Nettoyage des entrées
            $text = $this->cleanInput($_POST['text'] ?? '');
            $category = filter_var(
                $_POST['Id_question_category'] ?? null,
                FILTER_VALIDATE_INT
            );
            $difficulty = filter_var(
                $_POST['Id_question_difficulte'] ?? null,
                FILTER_VALIDATE_INT
            );
            
            // Validation de l'URL d'image
            $picture_url = isset($_POST['picture_url']) ? 
                filter_var($_POST['picture_url'], FILTER_VALIDATE_URL) : null;

            // Nettoyage du chemin d'image actuel
            $current_image = isset($_POST['current_image']) ? 
                $this->cleanInput($_POST['current_image']) : null;

            // Gestion des images
            $picture = null;
            $imageSource = isset($_POST['image_source']) ? $_POST['image_source'] : 'none';

            error_log("Source d'image sélectionnée: " . $imageSource);

            // Si l'utilisateur souhaite supprimer l'image
            if (isset($_POST['remove_image']) && $_POST['remove_image'] == '1') {
                error_log("Suppression d'image demandée");
                $picture = null;

                // Si une image actuelle existe et n'est pas une URL, on la supprime du serveur
                if (isset($_POST['current_image']) && !empty($_POST['current_image']) && 
                    !filter_var($_POST['current_image'], FILTER_VALIDATE_URL)) {
                    $oldImagePath = $_POST['current_image'];
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                        error_log("Image supprimée: " . $oldImagePath);
                    }
                }
            } else {
                switch ($imageSource) {
                    case 'url':
                        // Utiliser l'URL fournie
                        if (!empty($_POST['picture_url'])) {
                            $picture = $_POST['picture_url'];
                            error_log("URL d'image utilisée: " . $picture);
                        }
                        break;

                    case 'upload':
                        error_log("Option upload sélectionnée");

                        // Vérifier si un fichier a été téléchargé
                        if (isset($_FILES['question_image_file']) && $_FILES['question_image_file']['error'] === UPLOAD_ERR_OK) {
                            error_log("Fichier téléchargé: " . $_FILES['question_image_file']['name']);

                            // Créer le répertoire uploads/questions s'il n'existe pas
                            $uploadDir = 'uploads/questions/';
                            if (!is_dir($uploadDir)) {
                                mkdir($uploadDir, 0777, true);
                                error_log("Création du répertoire: " . $uploadDir);
                            }

                            // Générer un nom de fichier unique
                            $fileName = time() . '_' . basename($_FILES['question_image_file']['name']);
                            $uploadPath = $uploadDir . $fileName;

                            error_log("Tentative de déplacement vers: " . $uploadPath);

                            // Déplacer le fichier téléchargé
                            if (move_uploaded_file($_FILES['question_image_file']['tmp_name'], $uploadPath)) {
                                error_log("Fichier déplacé avec succès");
                                $picture = $uploadPath;

                                // Si une ancienne image existe, la supprimer
                                if (isset($_POST['current_image']) && !empty($_POST['current_image']) && 
                                    !filter_var($_POST['current_image'], FILTER_VALIDATE_URL)) {
                                    $oldImagePath = $_POST['current_image'];
                                    if (file_exists($oldImagePath)) {
                                        unlink($oldImagePath);
                                        error_log("Ancienne image supprimée: " . $oldImagePath);
                                    }
                                }
                            } else {
                                error_log("Échec du déplacement: " . error_get_last()['message']);
                                header("Location: index.php?action=edit_question&id=$questionId&error=upload_failed");
                                exit();
                            }
                        } 
                        // Si aucun nouveau fichier mais image existante, conserver l'image actuelle
                        else if (isset($_POST['current_image']) && !empty($_POST['current_image'])) {
                            $picture = $_POST['current_image'];
                            error_log("Conservation de l'image existante: " . $picture);
                        }
                        break;

                    case 'none':
                    default:
                        // Pas d'image
                        $picture = null;
                        error_log("Aucune image sélectionnée");
                        break;
                }
            }

            // Mettre à jour la question dans la base de données
            try {
                // Commencer une transaction
                $this->db->beginTransaction();

                // Tableau de données pour la mise à jour de la question
                $questionData = [
                    'Id_question' => $questionId,
                    'text' => $text,
                    'Id_question_category' => $category,
                    'Id_question_difficulte' => $difficulty,
                    'picture' => $picture
                ];

                error_log("Mise à jour de la question avec: " . print_r($questionData, true));

                // Mettre à jour la question
                $success = $this->questionModel->updateQuestion($questionData);

                if (!$success) {
                    throw new Exception("La mise à jour de la question a échoué");
                }

                // Gestion des réponses
                $this->updateAnswers($questionId, $_POST);

                // Validation de la transaction
                $this->db->commit();

                // Redirection avec succès
                header("Location: index.php?action=edit_question&id=$questionId&success=question_updated");
                exit();

            } catch (Exception $e) {
                // Rollback en cas d'erreur
                $this->db->rollBack();
                error_log("Erreur: " . $e->getMessage());
                header("Location: index.php?action=edit_question&id=$questionId&error=update_failed");
                exit();
            }
        } else {
            // Redirection si accès direct sans POST
            header('Location: index.php?action=admin_dashboard');
            exit();
        }
    }

    /**
     * Ajoute une question à un quiz
     * @param int $quizId ID du quiz
     */
    public function addQuestionToQuiz($quizId) {
        // Vérification des droits admin
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            $_SESSION['error_message'] = "Vous n'avez pas les droits pour modifier ce quiz.";
            header("Location: index.php?action=quiz");
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validation et nettoyage des entrées
            $questionId = filter_var($_POST['question_id'] ?? 0, FILTER_VALIDATE_INT);
            $order = filter_var($_POST['order'] ?? 0, FILTER_VALIDATE_INT);
            
            if ($questionId > 0) {
                try {
                    $added = $this->questionModel->addQuestionToQuiz($quizId, $questionId, $order);
                    $_SESSION['success_message'] = "Question ajoutée au quiz avec succès.";
                } catch (Exception $e) {
                    $_SESSION['error_message'] = "Erreur lors de l'ajout de la question au quiz.";
                    error_log($e->getMessage());
                }
            } else {
                $_SESSION['error_message'] = "Question invalide.";
            }
            
            header("Location: index.php?action=edit_quiz&id=" . $quizId);
            exit();
        }
    }

    /**
     * Supprime une question d'un quiz
     * @param int $quizId ID du quiz
     * @param int $questionId ID de la question
     */
    public function removeQuestionFromQuiz($quizId, $questionId) {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            $_SESSION['error_message'] = "Vous n'avez pas les droits pour modifier ce quiz.";
            header("Location: index.php?action=quiz");
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Validation des IDs
                $quizId = filter_var($quizId, FILTER_VALIDATE_INT);
                $questionId = filter_var($questionId, FILTER_VALIDATE_INT);
                
                if (!$quizId || !$questionId) {
                    throw new Exception("IDs invalides");
                }

                $removed = $this->questionModel->removeQuestionFromQuiz($quizId, $questionId);
                $_SESSION['success_message'] = "Question retirée du quiz avec succès.";
            } catch (Exception $e) {
                $_SESSION['error_message'] = "Erreur lors du retrait de la question du quiz.";
                error_log($e->getMessage());
            }
            
            header("Location: index.php?action=edit_quiz&id=" . $quizId);
            exit();
        }
    }

    // ... autres méthodes du contrôleur ...
}
?>