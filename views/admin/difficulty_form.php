<?php include 'views/layout/header.php'; ?>

<h2><?php echo isset($difficulty) ? 'Modifier' : 'Ajouter'; ?> une Difficulté</h2>

<?php if (isset($errors)) : ?>
    <div class="error-container">
        <?php foreach ($errors as $error) : ?>
            <p class="error-message"><?php echo $error; ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<form method="POST" action="index.php?action=saveDifficulty">
    <input type="hidden" name="Id_question_difficulte" value="<?php echo htmlspecialchars($id); ?>">

    <label for="name">Nom :</label><br>
    <input type="text" name="name" id="name" value="<?php echo isset($difficulty) ? htmlspecialchars($difficulty) : ''; ?>" required><br><br>
    <?php if (isset($errors['name'])) : ?>
        <p class="error-message"><?php echo $errors['name']; ?></p>
    <?php endif; ?>

    <input type="submit" value="Enregistrer">
</form>

<?php include 'views/layout/footer.php'; ?>