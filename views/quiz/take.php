<?php include 'views/layout/header.php'; ?>

<h2><?php echo htmlspecialchars($quiz['title']); ?></h2>
<p><?php echo htmlspecialchars($quiz['description']); ?></p>

<form method="POST" action="index.php?action=submit&Id_quiz=<?php echo htmlspecialchars($quiz['Id_quiz']); ?>">
    <?php foreach ($questions as $question) : ?>
        <div class="question">
            <h3><?php echo htmlspecialchars($question['text']); ?></h3>
            <?php if ($question['picture']) : ?>
                <img src="public/uploads/<?php echo htmlspecialchars($question['picture']); ?>" alt="Image de la question">
            <?php endif; ?>
            <div class="answers">
                <?php foreach ($question['answers'] as $answer) : ?>
                    <label>
                        <input type="radio" name="answers[<?php echo htmlspecialchars($question['Id_question']); ?>]" value="<?php echo htmlspecialchars($answer['Id_question_answer']); ?>" required>
                        <?php echo htmlspecialchars($answer['text']); ?>
                    </label><br>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>
    <input type="submit" value="Soumettre le Quiz">
</form>

<?php include 'views/layout/footer.php'; ?>