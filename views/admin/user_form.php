<?php include 'views/layout/header.php'; ?>

<h2><?php echo isset($user) ? 'Modifier' : 'Ajouter'; ?> un Utilisateur (<?php echo htmlspecialchars(ucfirst($type)); ?>)</h2>

<?php if (isset($errors)) : ?>
    <div class="error-container">
        <?php foreach ($errors as $error) : ?>
            <p class="error-message"><?php echo $error; ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<form method="POST" action="index.php?action=saveUser">
    <input type="hidden" name="type" value="<?php echo htmlspecialchars($type); ?>">
    <input type="hidden" name="Id" value="<?php echo htmlspecialchars($user['Id_player'] ?? $user['Id_admin_editor'] ?? ''); ?>">

    <label for="name">Nom :</label><br>
    <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($user['name'] ?? ''); ?>" required><br><br>
    <?php if (isset($errors['name'])) : ?>
        <p class="error-message"><?php echo $errors['name']; ?></p>
    <?php endif; ?>

    <label for="first_name">Prénom :</label><br>
    <input type="text" name="first_name" id="first_name" value="<?php echo htmlspecialchars($user['first_name'] ?? ''); ?>" required><br><br>
    <?php if (isset($errors['first_name'])) : ?>
        <p class="error-message"><?php echo $errors['first_name']; ?></p>
    <?php endif; ?>

    <label for="email">Email :</label><br>
    <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required><br><br>
    <?php if (isset($errors['email'])) : ?>
        <p class="error-message"><?php echo $errors['email']; ?></p>
    <?php endif; ?>

    <label for="password">Mot de passe :</label><br>
    <input type="password" name="password" id="password" <?php echo isset($user) ? '' : 'required'; ?>><br><br>
    <?php if (isset($errors['password'])) : ?>
        <p class="error-message"><?php echo $errors['password']; ?></p>
    <?php endif; ?>

    <input type="submit" value="Enregistrer">
</form>

<?php include 'views/layout/footer.php'; ?>