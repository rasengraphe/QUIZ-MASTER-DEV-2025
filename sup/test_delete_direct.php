<?php
// Fichier test_delete_direct.php - Test direct de suppression
require_once 'config/database.php';

// ID à supprimer
$questionId = isset($_GET['id']) ? intval($_GET['id']) : null;

if (!$questionId) {
    die("Veuillez spécifier un ID: test_delete_direct.php?id=XX");
}

echo "<h2>Test de suppression directe pour la question ID: $questionId</h2>";

try {
    // Commencer une transaction
    $db->beginTransaction();
    echo "<p>Transaction commencée</p>";
    
    // Désactiver les contraintes
    $db->exec("SET FOREIGN_KEY_CHECKS = 0");
    echo "<p>Contraintes désactivées</p>";
    
    // Supprimer les réponses associées
    $sqlAnswers = "DELETE FROM quiz_question_answer WHERE Id_question = :id";
    $stmtAnswers = $db->prepare($sqlAnswers);
    $stmtAnswers->bindParam(':id', $questionId, PDO::PARAM_INT);
    $stmtAnswers->execute();
    $countAnswers = $stmtAnswers->rowCount();
    echo "<p>Réponses supprimées: $countAnswers</p>";
    
    // Supprimer la question
    $sqlQuestion = "DELETE FROM quiz_question WHERE Id_question = :id";
    $stmtQuestion = $db->prepare($sqlQuestion);
    $stmtQuestion->bindParam(':id', $questionId, PDO::PARAM_INT);
    $stmtQuestion->execute();
    $countQuestion = $stmtQuestion->rowCount();
    echo "<p>Questions supprimées: $countQuestion</p>";
    
    // Réactiver les contraintes
    $db->exec("SET FOREIGN_KEY_CHECKS = 1");
    echo "<p>Contraintes réactivées</p>";
    
    // Confirmer la transaction
    $db->commit();
    echo "<p>Transaction confirmée</p>";
    
    echo "<h3>" . ($countQuestion > 0 ? "✅ Suppression réussie!" : "❌ Échec de la suppression") . "</h3>";
    
} catch (PDOException $e) {
    // En cas d'erreur
    try {
        $db->rollBack();
        $db->exec("SET FOREIGN_KEY_CHECKS = 1");
    } catch (Exception $ex) {
        // Ignorer
    }
    
    echo "<h3>❌ Erreur PDO: " . $e->getMessage() . "</h3>";
}
?>
<p><a href="index.php?action=delete_question">Retour à la page de suppression</a></p>