<?php
require_once '../../config/database.php';

try {
    $query = "SELECT Id_question, text, LEFT(picture, 100) AS picture_preview, LENGTH(picture) AS picture_length 
              FROM quiz_question WHERE picture IS NOT NULL LIMIT 5";
    $stmt = $db->query($query);
    $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h1>Débogage des images</h1>";
    
    foreach ($questions as $question) {
        echo "<div style='margin-bottom:20px; padding:10px; border:1px solid #ccc;'>";
        echo "<h3>Question ID: " . $question['Id_question'] . "</h3>";
        echo "<p>Texte: " . htmlspecialchars($question['text']) . "</p>";
        echo "<p>Aperçu de l'image: " . htmlspecialchars($question['picture_preview']) . "...</p>";
        echo "<p>Longueur de l'image: " . $question['picture_length'] . " caractères</p>";
        
        if (strpos($question['picture_preview'], 'data:image') === 0) {
            echo "<p>Type: Image base64</p>";
        } else if (filter_var($question['picture_preview'], FILTER_VALIDATE_URL)) {
            echo "<p>Type: URL externe</p>";
        } else {
            echo "<p>Type: Inconnu ou chemin local</p>";
        }
        
        // Test d'affichage de l'image
        echo "<p>Test d'affichage:</p>";
        
        // Récupérer l'image complète
        $query = "SELECT picture FROM quiz_question WHERE Id_question = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$question['Id_question']]);
        $fullImage = $stmt->fetchColumn();
        
        echo "<img src='" . htmlspecialchars($fullImage) . "' style='max-width:300px; max-height:200px;'>";
        echo "</div>";
    }
    
} catch (PDOException $e) {
    echo "<p style='color:red'>Erreur: " . $e->getMessage() . "</p>";
}
?>