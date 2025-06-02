<?php
/**
 * Vue du formulaire de gestion des questions (ajout/modification)
 * Cette page permet aux administrateurs de créer ou modifier des questions du quiz
 * Elle gère :
 * - Le formulaire de saisie des questions et réponses
 * - L'upload ou l'ajout d'URL d'images
 * - L'affichage des messages de confirmation
 */

// Démarrage de la session si pas déjà fait
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Initialisation des variables
if (!isset($question)) {
    $question = array();
}
if (!isset($answers)) {
    $answers = array();
}
if (!isset($errors)) {
    $errors = array();
}

include 'views/layout/header.php';
?>

<?php
// Nettoyage de la session pour éviter l'affichage intempestif de la modal
if (isset($_SESSION['question_added'])) {
    unset($_SESSION['question_added']);
}
?>

<div class="question-form-container">
    <!-- Titre dynamique selon le contexte (ajout ou modification) -->
    <h1>
        <?php echo isset($question['Id_question']) ? 'Modifier la Question' : 'Ajouter une Question'; ?>
    </h1>
    
    <!-- Affichage des messages d'erreur s'il y en a -->
    <?php if (isset($errors) && !empty($errors)) : ?>
        <div class="error-container">
            <?php foreach ($errors as $error) : ?>
                <p class="error-message"><?php echo $error; ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Modal de confirmation après sauvegarde réussie -->
    <div id="confirmation-modal" class="modal <?php echo isset($_SESSION['question_added']) ? 'show' : ''; ?>">
        <div class="modal-content">
            <span class="close">&times;</span>
            <div class="modal-icon">✓</div>
            <h2>Question <?php echo isset($question['Id_question']) ? 'modifiée' : 'ajoutée'; ?> avec succès!</h2>
            <p>Votre question a bien été enregistrée dans la base de données.</p>
            <div class="modal-actions">
                <button id="add-another" class="btn primary">Ajouter une autre question</button>
                <a href="index.php?action=questions" class="btn secondary">Retour à la liste</a>
            </div>
        </div>
    </div>

    <!-- Formulaire principal -->
    <form method="POST" action="index.php?action=storeQuestion" enctype="multipart/form-data" class="question-form">
        <!-- Champ caché pour l'ID en cas de modification -->
        <input type="hidden" name="Id_question" value="<?php echo isset($question['Id_question']) ? htmlspecialchars($question['Id_question']) : ''; ?>">
        
        <!-- Section texte de la question -->
        <div class="form-group">
            <label for="question_text">Question :</label>
            <textarea id="question_text" name="question_text" placeholder="Entrez la question" required><?php echo isset($question['text']) ? htmlspecialchars($question['text']) : ''; ?></textarea>
        </div>
        
        <?php
        // Configuration des réponses
        // Garantit qu'il y a toujours 3 réponses possibles
        $answersArray = isset($answers) && is_array($answers) ? $answers : [['text' => ''], ['text' => ''], ['text' => '']];
        
        // Complète jusqu'à 3 réponses si nécessaire
        while (count($answersArray) < 3) {
            $answersArray[] = ['text' => ''];
        }
        
        // Détermine quelle réponse est marquée comme correcte
        $correctAnswerIndex = -1;
        foreach ($answersArray as $index => $answer) {
            if (isset($answer['correct']) && $answer['correct'] == 1) {
                $correctAnswerIndex = $index;
                break;
            }
        }
        ?>
        
        <!-- Boucle d'affichage des champs de réponse -->
        <?php foreach ($answersArray as $index => $answer) : ?>
        <div class="form-group">
            <label for="answer<?php echo $index + 1; ?>">Réponse <?php echo $index + 1; ?> :</label>
            <input type="text" id="answer<?php echo $index + 1; ?>" name="answers[]" 
placeholder="Entrez la réponse <?php echo $index + 1; ?>" required 
value="<?php echo isset($answer['text']) ? htmlspecialchars($answer['text']) : ''; ?>">
        </div>
<?php endforeach; ?>
        
        <!-- Sélection de la réponse correcte -->
        <div class="form-group">
            <label for="correct">Réponse correcte (1, 2 ou 3) :</label>
            <select id="correct" name="correct" required>
                <option value="">Entrez le numéro de la réponse correcte</option>
                <option value="0" <?php echo $correctAnswerIndex === 0 ? 'selected' : ''; ?>>1</option>
                <option value="1" <?php echo $correctAnswerIndex === 1 ? 'selected' : ''; ?>>2</option>
                <option value="2" <?php echo $correctAnswerIndex === 2 ? 'selected' : ''; ?>>3</option>
            </select>
        </div>
        
        <!-- Section gestion des images -->
        <div class="form-group">
            <label>Image de la question :</label>
            
            <!-- Interface à onglets pour le choix du type d'ajout d'image -->
            <div class="image-option-tabs">
                <div class="tab-buttons">
                    <button type="button" class="tab-button active" data-tab="url">Par URL</button>
                    <button type="button" class="tab-button" data-tab="upload">Par téléchargement</button>
                </div>
                
                <div class="tab-content">
                    <div class="tab-panel active" id="url-panel">
                        <input type="text" id="picture" name="picture_url" placeholder="URL de l'image (optionnel)" value="<?php echo isset($question['picture']) ? htmlspecialchars($question['picture']) : ''; ?>">
                        <small class="form-help">OU entrez l'URL d'une image (JPG, PNG, GIF)</small>
                    </div>
                    
                    <div class="tab-panel" id="upload-panel">
                        <div class="file-upload-container">
                            <label for="picture_file" class="file-upload-label">Choisir un fichier</label>
                            <input type="file" id="picture_file" name="picture_file" accept="image/*" class="file-input">
                            <span class="file-selected">Aucun fichier choisi</span>
                        </div>
                        <small class="form-help">Taille maximale: 2MB. Formats acceptés: JPG, PNG, GIF</small>
                    </div>
                </div>
                
                <!-- Affichage de prévisualisation de l'image -->
                <div class="image-preview" id="image-preview">
                    <?php if (isset($question['picture']) && !empty($question['picture'])): ?>
                        <img src="<?php echo htmlspecialchars($question['picture']); ?>" alt="Prévisualisation">
                    <?php endif; ?>
                </div>
                
                <!-- Champ caché pour indiquer quelle option est utilisée -->
                <input type="hidden" name="image_source" id="image_source" value="url">
            </div>
        </div>
        
        <!-- Boutons d'action du formulaire -->
        <div class="form-actions">
            <button type="submit" class="btn primary"><?php echo isset($question['Id_question']) ? 'Modifier' : 'Enregistrer'; ?></button>
            <button type="button" id="reset-form" class="btn secondary">Annuler</button>
            <a href="index.php?action=questions" class="btn tertiary">Retour à la liste</a>
        </div>
    </form>
</div>

<!-- Scripts JavaScript -->
<script>
/**
 * Gestionnaire d'événements principal
 * Gère :
 * - Les interactions avec la modal de confirmation
 * - La réinitialisation du formulaire
 * - La prévisualisation des images
 * - Le système d'onglets pour l'upload d'images
 */
document.addEventListener('DOMContentLoaded', function() {
    // Gestion de la modal
    var modal = document.getElementById('confirmation-modal');
    var span = document.getElementsByClassName('close')[0];
    var addAnother = document.getElementById('add-another');
    
    // Fermer la modal quand on clique sur X
    span.onclick = function() {
        modal.classList.remove('show');
        // Supprimer la variable de session après fermeture
        fetch('index.php?action=clearSession&key=question_added');
    }
    
    // Fermer la modal quand on clique en dehors de celle-ci
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.classList.remove('show');
            // Supprimer la variable de session après fermeture
            fetch('index.php?action=clearSession&key=question_added');
        }
    }
    
    // Rester sur la page pour ajouter une autre question
    addAnother.onclick = function() {
        modal.classList.remove('show');
        // Supprimer la variable de session après fermeture
        fetch('index.php?action=clearSession&key=question_added');
        // Réinitialiser le formulaire
        document.querySelector('.question-form').reset();
document.getElementById('image-preview').innerHTML = '';
    }

    // Gestion de la réinitialisation du formulaire
    document.getElementById('reset-form').addEventListener('click', function() {
        // Si c'est un formulaire de modification, rediriger vers la liste plutôt que de réinitialiser
        if (document.querySelector('input[name="Id_question"]').value) {
            window.location.href = 'index.php?action=questions';
            return;
        }
        
        // Sinon, réinitialiser tous les champs du formulaire
        document.querySelector('.question-form').reset();
        
        // Réinitialiser la prévisualisation d'image
        document.getElementById('image-preview').innerHTML = '';
        
        // Revenir à l'onglet URL par défaut
        switchTab('url');
        
        // Faire défiler la page vers le haut
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
        });
    
    // Activer les onglets pour l'option d'image
    const tabButtons = document.querySelectorAll('.tab-button');
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const tabId = this.getAttribute('data-tab');
            switchTab(tabId);
        });
    });
    
    // Fonction pour changer d'onglet
    function switchTab(tabId) {
        // Désactiver tous les onglets
        const tabButtons = document.querySelectorAll('.tab-button');
        const tabPanels = document.querySelectorAll('.tab-panel');
        
        tabButtons.forEach(button => button.classList.remove('active'));
        tabPanels.forEach(panel => panel.classList.remove('active'));
        
        // Activer l'onglet sélectionné
        document.querySelector(`.tab-button[data-tab="${tabId}"]`).classList.add('active');
        document.getElementById(`${tabId}-panel`).classList.add('active');
        
        // Mettre à jour le champ caché
        document.getElementById('image_source').value = tabId;
    }

    // Prévisualisation du fichier sélectionné
    const fileInput = document.getElementById('picture_file');
    const fileSelected = document.querySelector('.file-selected');
    
    fileInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            fileSelected.textContent = this.files[0].name;
            
            // Prévisualisation de l'image
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('image-preview');
                preview.innerHTML = `<img src="${e.target.result}" alt="Prévisualisation">`;
            }
            reader.readAsDataURL(this.files[0]);
        } else {
            fileSelected.textContent = 'Aucun fichier choisi';
        }
    });
});
</script>

<?php include 'views/layout/footer.php'; ?>
