<?php
require_once '../../config/database.php';

try {
    // Modifier le type de colonne pour stocker de plus grandes images
    $db->exec("ALTER TABLE quiz_question MODIFY picture MEDIUMTEXT");
    echo "<p style='color:green'>La colonne 'picture' a été modifiée pour accepter des images plus grandes.</p>";
} catch (PDOException $e) {
    echo "<p style='color:red'>Erreur: " . $e->getMessage() . "</p>";
}
?>