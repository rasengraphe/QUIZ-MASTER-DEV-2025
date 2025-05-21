<?php

class QuestionController extends Controller {
    private $questionModel;

    public function __construct($db) {
        parent::__construct($db);
        $this->questionModel = new QuestionModel($db);
    }

    // Méthode pour mettre à jour une question
    public function update_question() {
        // Vérifier si l'utilisateur est autorisé
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?action=login');
            exit();
        }

        // Journaliser toutes les données envoyées pour le débogage
        error_log("POST: " . print_r($_POST, true));
        error_log("FILES: " . print_r($_FILES, true));

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupérer l'ID de la question
            $questionId = isset($_POST['Id_question']) ? intval($_POST['Id_question']) : 0;

            if ($questionId <= 0) {
                header('Location: index.php?action=edit_question&error=invalid_id');
                exit();
            }

            // Récupérer les données du formulaire
            $text = isset($_POST['text']) ? trim($_POST['text']) : '';
            $category = isset($_POST['Id_question_category']) && !empty($_POST['Id_question_category']) 
                ? intval($_POST['Id_question_category']) : null;
            $difficulty = isset($_POST['Id_question_difficulte']) && !empty($_POST['Id_question_difficulte']) 
                ? intval($_POST['Id_question_difficulte']) : null;

            // Traitement de l'image
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

                // Traitement des réponses
                $answers = isset($_POST['answers']) ? $_POST['answers'] : [];
                $correctAnswerIndex = isset($_POST['correct_answer']) ? intval($_POST['correct_answer']) : -1;

                // Mettre à jour chaque réponse
                foreach ($answers as $index => $answer) {
                    $answerId = isset($answer['Id_question_answer']) ? intval($answer['Id_question_answer']) : 0;
                    $answerText = isset($answer['text']) ? trim($answer['text']) : '';
                    $isCorrect = ($index == $correctAnswerIndex) ? 1 : 0;

                    if ($answerId > 0) {
                        // Mettre à jour une réponse existante
                        $this->questionModel->updateAnswer([
                            'Id_question_answer' => $answerId,
                            'text' => $answerText,
                            'correct' => $isCorrect
                        ]);
                    } else {
                        // Créer une nouvelle réponse
                        $this->questionModel->addAnswer([
                            'text' => $answerText,
                            'correct' => $isCorrect,
                            'Id_question' => $questionId
                        ]);
                    }
                }

                $this->db->commit();

                // Rediriger vers la page de succès
                header("Location: index.php?action=edit_question&id=$questionId&success=question_updated");
                exit();
            } catch (Exception $e) {
                // En cas d'erreur, annuler la transaction
                $this->db->rollBack();
                error_log("Erreur: " . $e->getMessage());

                header("Location: index.php?action=edit_question&id=$questionId&error=update_failed");
                exit();
            }
        } else {
            // Si ce n'est pas une requête POST, rediriger vers le dashboard
            header('Location: index.php?action=admin_dashboard');
            exit();
        }
    }

    // Autres méthodes du contrôleur...
}
?>