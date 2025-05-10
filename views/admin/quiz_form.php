<?php include 'views/layout/header.php'; ?>

<h2><?php echo isset($quiz) ? 'Modifier' : 'Ajouter'; ?> un Quiz</h2>

<?php if (isset($errors)) : ?>
    <div class="error-container">
        <?php foreach ($errors as $error) : ?>
            <p class="error-message"><?php echo $error; ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<form method="POST" action="index.php?action=saveQuiz">
    <input type="hidden" name="Id_quiz" value="<?php echo isset($quiz) ? htmlspecialchars($quiz['Id_quiz']) : ''; ?>">

    <label for="title">Titre :</label><br>
    <input type="text" name="title" id="title" value="<?php echo isset($quiz) ? htmlspecialchars($quiz['title']) : ''; ?>" required><br><br>
    <?php if (isset($errors['title'])) : ?>
        <p class="error-message"><?php echo $errors['title']; ?></p>
    <?php endif; ?>

    <label for="description">Description :</label><br>
    <textarea name="description" id="description" rows="4" cols="50"><?php echo isset($quiz) ? htmlspecialchars($quiz['description']) : ''; ?></textarea><br><br>
    <?php if (isset($errors['description'])) : ?>
        <p class="error-message"><?php echo $errors['description']; ?></p>
    <?php endif; ?>

    <input type="submit" value="Enregistrer">
</form>

<?php include 'views/layout/footer.php'; ?>