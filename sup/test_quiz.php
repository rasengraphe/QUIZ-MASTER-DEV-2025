<?php
require_once 'config/database.php';
require_once 'models/Model.php';
require_once 'models/QuestionModel.php';

session_start();

echo "<h1>Test de récupération des questions</h1>";

try {
    $questionModel = new QuestionModel($db);
    $questions = $questionModel->getRandomQuestions(10);
    
    echo "<p>Nombre de questions récupérées : " . count($questions) . "</p>";
    
    if (count($questions) > 0) {
        echo "<h2>Questions disponibles</h2>";
        echo "<ul>";
        foreach ($questions as $q) {
            echo "<li>Question ID " . $q['Id_question'] . ": " . htmlspecialchars($q['text']);
            
            // Vérifier si l'image existe
            if (!empty($q['picture'])) {
                echo " (avec image)";
                echo "<br><img src='" . $q['picture'] . "' style='max-width:200px; max-height:150px;'>";
            }
            
            echo "</li>";
        }
        echo "</ul>";
        
        // Tester la récupération des réponses pour la première question
        if (isset($questions[0]['Id_question'])) {
            $firstQuestionId = $questions[0]['Id_question'];
            echo "<h2>Réponses pour la première question (ID: $firstQuestionId)</h2>";
            
            $answers = $questionModel->getAnswersForQuestion($firstQuestionId);
            
            if (count($answers) > 0) {
                echo "<ul>";
                foreach ($answers as $a) {
                    echo "<li>" . htmlspecialchars($a['text']) . " (Correcte: " . ($a['correct'] ? "Oui" : "Non") . ")</li>";
                }
                echo "</ul>";
            } else {
                echo "<p>Aucune réponse trouvée pour cette question.</p>";
            }
        }
    } else {
        echo "<p style='color:red'>Aucune question disponible dans la base de données.</p>";
        
        // Vérifier si la table existe et contient des données
        $tables = $db->query("SHOW TABLES LIKE 'quiz_question'")->fetchAll();
        if (count($tables) == 0) {
            echo "<p>La table 'quiz_question' n'existe pas.</p>";
        } else {
            $count = $db->query("SELECT COUNT(*) FROM quiz_question")->fetchColumn();
            echo "<p>La table 'quiz_question' existe et contient $count enregistrements.</p>";
        }
    }
} catch (Exception $e) {
    echo "<p style='color:red'>Erreur : " . $e->getMessage() . "</p>";
    echo "<p>Trace : <pre>" . $e->getTraceAsString() . "</pre></p>";
}
?>