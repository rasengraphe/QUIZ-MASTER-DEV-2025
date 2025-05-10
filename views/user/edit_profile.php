<?php include 'views/layout/header.php'; ?>

<div class="container">
    <h2>Modifier mon profil</h2>
    
    <?php if (isset($errors) && !empty($errors)): ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    
    <form method="POST" action="index.php?action=updateProfile">
        <div class="form-group">
            <label for="name">Nom :</label>
            <input type="text" id="name" name="name" class="form-control" 
                   value="<?php echo htmlspecialchars($player['name'] ?? ''); ?>" required>
        </div>
        
        <div class="form-group">
            <label for="first_name">Prénom :</label>
            <input type="text" id="first_name" name="first_name" class="form-control" 
                   value="<?php echo htmlspecialchars($player['first_name'] ?? ''); ?>" required>
        </div>
        
        <div class="form-group">
            <label for="email">Email :</label>
            <input type="email" id="email" name="email" class="form-control" 
                   value="<?php echo htmlspecialchars($player['email'] ?? ''); ?>" required>
        </div>
        
        <div class="form-group">
            <label for="password">Nouveau mot de passe (laisser vide pour ne pas modifier) :</label>
            <input type="password" id="password" name="password" class="form-control">
        </div>
        
        <div class="form-group">
            <label>Sélectionnez votre avatar :</label>
            <div class="avatar-selection">
                <?php if (isset($avatars) && !empty($avatars)): ?>
                    <?php foreach ($avatars as $avatar): ?>
                        <label class="avatar-option">
                            <input type="radio" name="avatar_id" value="<?php echo $avatar['Id_Avatar']; ?>" 
                                <?php echo (isset($player['Id_Avatar']) && $player['Id_Avatar'] == $avatar['Id_Avatar']) ? 'checked' : ''; ?>>
                            <div class="avatar-container">
                                <img src="<?php echo htmlspecialchars($avatar['path']); ?>" 
                                     alt="Avatar <?php echo $avatar['Id_Avatar']; ?>">
                            </div>
                        </label>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Aucun avatar disponible.</p>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Mettre à jour</button>
            <a href="index.php?action=profile" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</div>

<style>
    .avatar-selection {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        margin: 10px 0;
    }
    
    .avatar-option {
        cursor: pointer;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    
    .avatar-container {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        overflow: hidden;
        margin: 5px;
        border: 3px solid transparent;
        transition: border-color 0.2s ease;
    }
    
    .avatar-option input[type="radio"] {
        display: none;
    }
    
    .avatar-option input[type="radio"]:checked + .avatar-container {
        border-color: #007bff;
    }
    
    .avatar-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
</style>

<?php include 'views/layout/footer.php'; ?>