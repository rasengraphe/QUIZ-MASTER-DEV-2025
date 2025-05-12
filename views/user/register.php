<?php include 'views/layout/header.php'; ?>

<div class="auth-container">
    <div class="register-form-container">
        <div class="form-header text-center">
            <h1>Inscription</h1>
            <p class="form-description">Créez votre compte pour participer aux quiz</p>
        </div>

        <?php if (isset($errors) && !empty($errors)) : ?>
            <div class="error-container">
                <?php foreach ($errors as $error) : ?>
                    <p class="error-message"><?php echo $error; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form action="index.php?action=register" method="post" class="register-form">
            <div class="form-group">
                <label for="name">Nom</label>
                <input type="text" id="name" name="name" value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>" required>
            </div>

            <div class="form-group">
                <label for="first_name">Prénom</label>
                <input type="text" id="first_name" name="first_name" value="<?php echo isset($_POST['first_name']) ? htmlspecialchars($_POST['first_name']) : ''; ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
            </div>

            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="form-group">
                <label for="password_confirm">Confirmer le mot de passe</label>
                <input type="password" id="password_confirm" name="password_confirm" required>
            </div>

            <!-- Version améliorée et responsive pour les avatars -->
            <div class="form-group">
                <label>Choisissez un avatar :</label>
                <div class="avatar-selection">
                    <?php for($i = 1; $i <= 5; $i++): ?>
                        <div class="avatar-option">
                            <input type="radio" id="avatar<?php echo $i; ?>" name="avatar" value="<?php echo $i; ?>" <?php echo ($i === 1) ? 'checked' : ''; ?>>
                            <label for="avatar<?php echo $i; ?>">
                                <img src="public/img/avatars/avatar<?php echo $i; ?>.png" alt="Avatar <?php echo $i; ?>" class="avatar-preview">
                            </label>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">S'inscrire</button>
            </div>
        </form>

        <div class="auth-footer">
            <p>Déjà inscrit ? <a href="index.php?action=login">Se connecter</a></p>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const avatarInputs = document.querySelectorAll('.avatar-option input[type="radio"]');
    
    // Vérifier quelle option est sélectionnée au chargement
    avatarInputs.forEach(input => {
        if (input.checked) {
            const img = input.nextElementSibling.querySelector('.avatar-preview');
            if (img) {
                img.style.borderColor = '#0071e3';
            }
        }
    });
    
    // Ajouter les écouteurs d'événements pour le changement de sélection
    avatarInputs.forEach(input => {
        input.addEventListener('change', function() {
            // Réinitialiser tous les avatars
            document.querySelectorAll('.avatar-preview').forEach(img => {
                img.style.borderColor = 'transparent';
            });
            
            // Mettre en surbrillance l'avatar sélectionné
            if (this.checked) {
                const img = this.nextElementSibling.querySelector('.avatar-preview');
                if (img) {
                    img.style.borderColor = '#0071e3';
                }
            }
        });
    });
});
</script>

<?php include 'views/layout/footer.php'; ?>