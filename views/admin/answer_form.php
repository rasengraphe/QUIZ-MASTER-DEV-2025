<?php include 'views/layout/header.php'; ?>

<h2><?php echo isset($answer) ? 'Modifier' : 'Ajouter'; ?> une Réponse</h2>

<?php if (isset($errors)) : ?>
    <div class="error-container">
        <?php foreach ($errors as $error) : ?>
            <p class="error-message"><?php echo $error; ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<form method="POST" action="index.php?action=saveAnswer">
    <input type="hidden" name="Id_question_answer" value="<?php echo isset($answer) ? htmlspecialchars($answer['Id_question_answer']) : ''; ?>">
    <input type="hidden" name="Id_question" value="<?php echo htmlspecialchars($Id_question); ?>">

    <label for="text">Réponse :</label><br>
    <input type="text" name="text" id="text" value="<?php echo isset($answer) ? htmlspecialchars($answer['text']) : ''; ?>" required><br><br>
    <?php if (isset($errors['text'])) : ?>
        <p class="error-message"><?php echo $errors['text']; ?></p>
    <?php endif; ?>

    <label for="correct">Correcte :</label>
    <input type="checkbox" name="correct" id="correct" value="1" <?php if (isset($answer) && $answer['correct'] == 1) echo 'checked'; ?>>
    <br><br>

    <input type="submit" value="Enregistrer">
</form>

<?php include 'views/layout/footer.php'; ?>