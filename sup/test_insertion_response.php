<?php
// Démarrer la session et activer les erreurs pour le debug
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

// Inclure la configuration (ici le fichier database.php contient la connexion $db)
require_once 'config/database.php';
require_once 'models/QuestionManager.php';

// Instanciation du QuestionManager
$questionManager = new QuestionManager($db);

// Données d'exemple pour insérer une réponse pour la question d'ID 84
$testData = [
    'text' => 'Test de réponse insérée',
    'correct' => 1,  // 1 pour réponse correcte, 0 sinon.
    'Id_question' => 84
];

// Insertion de la réponse et vérification
if ($questionManager->addQuestionAnswer($testData)) {
    echo "Insertion réussie.<br><br>";
    
    // Récupérer et afficher les réponses pour la question 84
    $answers = $questionManager->getAnswersByQuestionId(84);
    echo "Les réponses associées à la question d'ID 84:<br>";
    echo "<pre>" . print_r($answers, true) . "</pre>";
} else {
    echo "Erreur lors de l'insertion de la réponse.";
}
?>