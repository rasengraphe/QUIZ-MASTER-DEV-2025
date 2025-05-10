<?php include 'views/layout/header.php'; ?>

<h2><?php echo isset($category) ? 'Modifier' : 'Ajouter'; ?> une Catégorie</h2>

<?php if (isset($errors)) : ?>
    <div class="error-container">
        <?php foreach ($errors as $error) : ?>
            <p class="error-message"><?php echo $error; ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<form method="POST" action="index.php?action=saveCategory">
    <input type="hidden" name="Id_question_category" value="<?php echo htmlspecialchars($id); ?>">

    <label for="title">Titre :</label><br>
    <input type="text" name="title" id="title" value="<?php echo isset($category) ? htmlspecialchars($category['title']) : ''; ?>" required><br><br>
    <?php if (isset($errors['title'])) : ?>
        <p class="error-message"><?php echo $errors['title']; ?></p>
    <?php endif; ?>

    <label for="text">Description :</label><br>
    <textarea name="text" id="text" rows="4" cols="50"><?php echo isset($category) ? htmlspecialchars($category['text']) : ''; ?></textarea><br><br>
    <?php if (isset($errors['text'])) : ?>
        <p class="error-message"><?php echo $errors['text']; ?></p>
    <?php endif; ?>

    <label for="picture">Image :</label><br>
    <input type="text" name="picture" id="picture" value="<?php echo isset($category) ? htmlspecialchars($category['picture']) : ''; ?>"><br><br>
    <?php if (isset($errors['picture'])) : ?>
        <p class="error-message"><?php echo $errors['picture']; ?></p>
    <?php endif; ?>

    <label for="active">Active :</label><br>
    <select name="active" id="active">
        <option value="yes" <?php if (isset($category) && $category['active'] == 'yes') echo 'selected'; ?>>Oui</option>
        <option value="no" <?php if (isset($category) && $category['active'] == 'no') echo 'selected'; ?>>Non</option>
    </select><br><br>
    <?php if (isset($errors['active'])) : ?>
        <p class="error-message"><?php echo $errors['active']; ?></p>
    <?php endif; ?>

    <input type="submit" value="Enregistrer">
</form>

<?php include 'views/layout/footer.php'; ?>