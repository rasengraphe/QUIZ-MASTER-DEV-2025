<?php include 'views/layout/header.php'; ?>

<div class="page-container">
    <!-- Nouveau bouton retour en haut de la page -->
    <a href="index.php?action=admin_dashboard" class="btn back-top">← Retour au tableau de bord</a>
    
    <div class="edit-question-container">
        <h1>Modifier une question</h1>
        
        <?php if (isset($_GET['success']) && $_GET['success'] == 'question_updated'): ?>
            <div class="alert success">
                La question a été mise à jour avec succès.
            </div>
        <?php endif; ?>
        
        <?php if (isset($_GET['error'])): ?>
            <div class="alert error">
                <?php 
                    if ($_GET['error'] == 'question_not_found') {
                        echo 'La question demandée n\'a pas été trouvée.';
                    } else {
                        echo 'Une erreur est survenue lors de la modification de la question.';
                    }
                ?>
            </div>
        <?php endif; ?>
        
        <!-- Sélection de la question à modifier -->
        <div class="question-selector">
            <h2>Sélectionner une question à modifier</h2>
            <form action="index.php" method="get">
                <input type="hidden" name="action" value="edit_question">
                <div class="form-group">
                    <label for="question_id">Choisir une question :</label>
                    <select name="id" id="question_id" class="form-control" required onchange="this.form.submit()">
                        <option value="">-- Sélectionnez une question --</option>
                        <?php if (isset($questions) && is_array($questions)): ?>
                            <?php foreach ($questions as $q): ?>
                                <option value="<?php echo $q['Id_question']; ?>" <?php echo (isset($_GET['id']) && $_GET['id'] == $q['Id_question']) ? 'selected' : ''; ?>>
                                    ID: <?php echo $q['Id_question']; ?> - <?php echo htmlspecialchars($q['text']); ?> 
                                    <?php if (isset($q['category_name'])): ?>
                                        (Catégorie: <?php echo htmlspecialchars($q['category_name']); ?>, 
                                        Difficulté: <?php echo isset($q['difficulty_name']) ? htmlspecialchars($q['difficulty_name']) : 'Non définie'; ?>)
                                    <?php endif; ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </form>
        </div>

        <!-- Formulaire d'édition qui s'affiche seulement si une question est sélectionnée -->
        <?php if (isset($selectedQuestion) && $selectedQuestion): ?>
            <?php 
            // Préparation du chemin d'image correct
            $imageSrc = '';
            if (!empty($selectedQuestion['picture'])) {
                if (filter_var($selectedQuestion['picture'], FILTER_VALIDATE_URL)) {
                    $imageSrc = $selectedQuestion['picture'];
                } else {
                    $imageSrc = $selectedQuestion['picture'];
                }
            }
            ?>
            <div class="edit-form">
                <h2>Modifier la Question</h2>
                <form action="index.php?action=update_question" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="Id_question" value="<?php echo isset($selectedQuestion['Id_question']) ? $selectedQuestion['Id_question'] : ''; ?>">
                    
                    <div class="form-group">
                        <label for="question_text">Texte de la question :</label>
                        <input type="text" name="text" id="question_text" class="form-control" value="<?php echo isset($selectedQuestion['text']) ? htmlspecialchars($selectedQuestion['text']) : ''; ?>">
                    </div>
                    
                    <!-- Sélection de la catégorie -->
                    <div class="form-group">
                        <label for="question_category">Catégorie :</label>
                        <select name="Id_question_category" id="question_category" class="form-control">
                            <option value="">-- Optionnel --</option>
                            <?php if (isset($categories) && is_array($categories)): ?>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?php echo $cat['Id_question_category']; ?>"
                                        <?php echo (isset($selectedQuestion['Id_question_category']) && $selectedQuestion['Id_question_category'] == $cat['Id_question_category']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($cat['title']); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <!-- Sélection de la difficulté -->
                    <div class="form-group">
                        <label for="question_difficulte">Difficulté :</label>
                        <select name="Id_question_difficulte" id="question_difficulte" class="form-control">
                            <option value="">-- Optionnel --</option>
                            <?php if (isset($difficulties) && is_array($difficulties)): ?>
                                <?php foreach ($difficulties as $dif): ?>
                                    <option value="<?php echo $dif['Id_question_difficulte']; ?>"
                                        <?php echo (isset($selectedQuestion['Id_question_difficulte']) && $selectedQuestion['Id_question_difficulte'] == $dif['Id_question_difficulte']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($dif['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    
                    <!-- Gestion des réponses -->
                    <?php for ($i = 0; $i < 3; $i++): ?>
                        <div class="form-group">
                            <label for="answer_<?php echo $i+1; ?>">Réponse <?php echo $i+1; ?> :</label>
                            <input type="text" id="answer_<?php echo $i+1; ?>" name="answers[<?php echo $i; ?>][text]" class="form-control"
                                   value="<?php echo isset($answers[$i]) ? htmlspecialchars($answers[$i]['text']) : ''; ?>" required>
                            <input type="hidden" name="answers[<?php echo $i; ?>][Id_question_answer]" 
                                   value="<?php echo isset($answers[$i]) ? $answers[$i]['Id_question_answer'] : ''; ?>">
                        </div>
                    <?php endfor; ?>
                    
                    <!-- Sélection de la bonne réponse -->
                    <div class="form-group">
                        <label for="correct_answer">Réponse correcte (1, 2 ou 3) :</label>
                        <select id="correct_answer" name="correct_answer" class="form-control" required>
                            <?php for ($i = 0; $i < 3; $i++): ?>
                                <option value="<?php echo $i; ?>" <?php echo (isset($answers[$i]) && $answers[$i]['correct'] == 1) ? 'selected' : ''; ?>>
                                    <?php echo $i+1; ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    
                    <!-- Gestion de l'image -->
                    <div class="form-group">
                        <label for="question_image">Image de la question :</label>
                        
                        <div class="image-source-selector">
                            <div class="source-option">
                                <input type="radio" name="image_source" id="img_source_url" value="url" <?= (!empty($selectedQuestion['picture']) && filter_var($selectedQuestion['picture'], FILTER_VALIDATE_URL)) ? 'checked' : ''; ?>>
                                <label for="img_source_url">Par URL</label>
                            </div>
                            <div class="source-option">
                                <input type="radio" name="image_source" id="img_source_upload" value="upload" <?= (!empty($selectedQuestion['picture']) && !filter_var($selectedQuestion['picture'], FILTER_VALIDATE_URL)) ? 'checked' : ''; ?>>
                                <label for="img_source_upload">Par téléchargement</label>
                            </div>
                        </div>
                        
                        <!-- Option URL -->
                        <div id="url_option" class="image-option <?= (!empty($selectedQuestion['picture']) && filter_var($selectedQuestion['picture'], FILTER_VALIDATE_URL)) ? '' : 'hidden'; ?>">
                            <label for="picture_url">URL de l'image (optionnel)</label>
                            <input type="text" id="picture_url" name="picture_url" class="form-control" placeholder="OU entrez l'URL d'une image (JPG, PNG, GIF)" value="<?= (isset($selectedQuestion['picture']) && filter_var($selectedQuestion['picture'], FILTER_VALIDATE_URL)) ? $selectedQuestion['picture'] : ''; ?>">
                        </div>
                        
                        <!-- Option téléchargement -->
                        <div id="upload_option" class="image-option <?= (!empty($selectedQuestion['picture']) && !filter_var($selectedQuestion['picture'], FILTER_VALIDATE_URL)) ? '' : 'hidden'; ?>">
                            <label for="question_image">Choisir un fichier</label>
                            <input type="file" id="question_image" name="question_image" class="form-control-file">
                            <small class="form-text text-muted">Taille maximale: 2MB. Formats acceptés: JPG, PNG, GIF</small>
                        </div>
                        
                        <!-- Affichage de l'image actuelle -->
                        <?php if (isset($selectedQuestion['picture']) && !empty($selectedQuestion['picture'])): ?>
                            <div class="current-image">
                                <p>Image actuelle :</p>
                                <img src="<?php echo $imageSrc; ?>" alt="Image actuelle" class="question-image">
                                <div class="image-actions">
                                    <label>
                                        <input type="checkbox" name="remove_image" value="1"> Supprimer l'image
                                    </label>
                                    <input type="hidden" name="current_image" value="<?= $selectedQuestion['picture']; ?>">
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn save">Valider</button>
                        <a href="index.php?action=edit_question" class="btn cancel">Annuler</a>
                    </div>
                </form>
            </div>
        <?php endif; ?>
        
        <a href="index.php?action=admin_dashboard" class="btn back">Retour au tableau de bord</a>
    </div>
</div>

<?php
echo "<pre>";
print_r($questions);
print_r($selectedQuestion);
print_r($answers);
echo "</pre>";
?>

<script>
document.addEventListener('DOMContentLoaded', function() {
// Gestion fermeture des notifications
    document.querySelectorAll('.close-notification').forEach(function(closeBtn) {
        closeBtn.addEventListener('click', function() {
            this.parentElement.style.display = 'none';
        });
    });
    
    // Auto-hide notifications après 5 secondes
    setTimeout(function() {
        document.querySelectorAll('.notification-banner').forEach(function(banner) {
            banner.style.display = 'none';
        });
    }, 5000);
    
    const urlRadio = document.getElementById('img_source_url');
    const uploadRadio = document.getElementById('img_source_upload');
    const urlOption = document.getElementById('url_option');
    const uploadOption = document.getElementById('upload_option');
    
    function toggleImageOptions() {
        if (urlRadio && uploadRadio) {
            if (urlRadio.checked) {
                urlOption.classList.remove('hidden');
                uploadOption.classList.add('hidden');
            } else if (uploadRadio.checked) {
                urlOption.classList.add('hidden');
                uploadOption.classList.remove('hidden');
            }
        }
    }
    
    if (urlRadio && uploadRadio) {
        urlRadio.addEventListener('change', toggleImageOptions);
        uploadRadio.addEventListener('change', toggleImageOptions);
        toggleImageOptions();
    }
});
</script>

<?php include 'views/layout/footer.php'; ?>