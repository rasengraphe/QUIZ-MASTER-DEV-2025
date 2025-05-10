<?php
// Fichier : delete_question_direct.php - Une solution de contournement directe
session_start();

// Vérifier l'authentification
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php?action=login');
    exit;
}

// Inclure la configuration de la base de données
require_once 'config/database.php';

// L'ID de la question à supprimer
$questionId = isset($_GET['id']) ? intval($_GET['id']) : null;

if (!$questionId) {
    $_SESSION['error'] = "ID de question manquant";
    header('Location: index.php?action=delete_question');
    exit;
}

// Fonction pour récupérer les détails d'une question
function getQuestionById($db, $id) {
    try {
        $sql = "SELECT * FROM quiz_question WHERE Id_question = :id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return false;
    }
}

// Récupérer les détails de la question
$question = getQuestionById($db, $questionId);

if (!$question) {
    $_SESSION['error'] = "Question non trouvée (ID: $questionId)";
    header('Location: index.php?action=delete_question');
    exit;
}

// Si on a confirmé la suppression
if (isset($_GET['confirm']) && $_GET['confirm'] === "1") {
    try {
        // Commencer une transaction
        $db->beginTransaction();
        
        // Désactiver les contraintes
        $db->exec("SET FOREIGN_KEY_CHECKS = 0");
        
        // Supprimer les réponses associées
        $sqlAnswers = "DELETE FROM quiz_question_answer WHERE Id_question = :id";
        $stmtAnswers = $db->prepare($sqlAnswers);
        $stmtAnswers->bindParam(':id', $questionId, PDO::PARAM_INT);
        $stmtAnswers->execute();
        
        // Supprimer la question
        $sqlQuestion = "DELETE FROM quiz_question WHERE Id_question = :id";
        $stmtQuestion = $db->prepare($sqlQuestion);
        $stmtQuestion->bindParam(':id', $questionId, PDO::PARAM_INT);
        $stmtQuestion->execute();
        
        // Réactiver les contraintes
        $db->exec("SET FOREIGN_KEY_CHECKS = 1");
        
        // Confirmer la transaction
        $db->commit();
        
        // Message de succès
        $_SESSION['success'] = "La question \"" . htmlspecialchars(substr($question['text'], 0, 50)) . 
                               (strlen($question['text']) > 50 ? '...' : '') . 
                               "\" (ID: $questionId) a été supprimée avec succès";
        
        // Rediriger vers la page de liste
        header('Location: index.php?action=delete_question');
        exit;
        
    } catch (PDOException $e) {
        // Annuler la transaction
        try {
            $db->rollBack();
            $db->exec("SET FOREIGN_KEY_CHECKS = 1");
        } catch (Exception $ex) {
            // Ignorer
        }
        
        $_SESSION['error'] = "Erreur lors de la suppression: " . $e->getMessage();
        header('Location: index.php?action=delete_question');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmer la suppression</title>
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>
    <div class="delete-question-container">
        <h1>Confirmer la suppression</h1>
        
        <div class="question-to-delete">
            <h2>Êtes-vous sûr de vouloir supprimer cette question ?</h2>
            <div class="question-details">
                <p><strong>ID:</strong> <?php echo $question['Id_question']; ?></p>
                <p><strong>Question:</strong> <?php echo htmlspecialchars($question['text']); ?></p>
            </div>
            
            <div class="confirmation-buttons">
                <a href="delete_question_direct.php?id=<?php echo $questionId; ?>&confirm=1" class="btn delete">Confirmer la suppression</a>
                <a href="index.php?action=delete_question" class="btn cancel">Annuler</a>
            </div>
        </div>
    </div>
</body>
</html>