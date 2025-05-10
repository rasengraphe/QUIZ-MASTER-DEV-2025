<?php include 'views/layout/header.php'; ?>

<main class="login-container">
    <div class="login-form-card">
        <h1>Connexion</h1>
        
        <?php if (isset($errors['login'])): ?>
            <div class="error-message"><?php echo $errors['login']; ?></div>
        <?php endif; ?>
        
        <form action="index.php?action=login" method="post">
            <div class="form-group">
                <label for="name">Nom</label>
                <input type="text" name="name" id="name" required value="<?php echo isset($name) ? htmlspecialchars($name) : ''; ?>">
                <?php if (isset($errors['name'])): ?>
                    <span class="error"><?php echo $errors['name']; ?></span>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" required value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>">
                <?php if (isset($errors['email'])): ?>
                    <span class="error"><?php echo $errors['email']; ?></span>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" name="password" id="password" required>
                <?php if (isset($errors['password'])): ?>
                    <span class="error"><?php echo $errors['password']; ?></span>
                <?php endif; ?>
            </div>
            
            <button type="submit" class="btn primary">Se connecter</button>
        </form>
        
        <p class="form-footer">
            <a href="index.php?action=home">Retour à l'accueil</a>
        </p>
    </div>
</main>

<?php include 'views/layout/footer.php'; ?>