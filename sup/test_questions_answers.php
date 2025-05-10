<?php
require_once 'config/database.php';

$stmt = $db->query("SELECT * FROM quiz_question");
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($questions as $question) {
    $questionId = $question['Id_question'];
    $stmtAnswers = $db->prepare("SELECT * FROM quiz_question_answer WHERE Id_question = ?");
    $stmtAnswers->execute([$questionId]);
    $answers = $stmtAnswers->fetchAll(PDO::FETCH_ASSOC);

    echo "Question: " . $question['text'] . "<br>";
    if (empty($answers)) {
        echo "Aucune réponse trouvée pour cette question.<br>";
    } else {
        foreach ($answers as $answer) {
            echo "- Réponse: " . $answer['text'] . " (Correct: " . $answer['correct'] . ")<br>";
        }
    }
    echo "<br>";
}
?>