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
                    } elseif ($_GET['error'] == 'upload_failed') {
                        echo 'Erreur lors du téléchargement de l\'image. Veuillez réessayer.';
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
                <!-- IMPORTANT: enctype doit être "multipart/form-data" pour les téléchargements de fichiers -->
                <form action="index.php?action=update_question" method="post" enctype="multipart/form-data" id="questionEditForm">
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
                        <label>Image de la question :</label>
                        
                        <div class="image-source-selector">
                            <div class="source-option">
                                <input type="radio" name="image_source" id="img_source_url" value="url" <?= (!empty($selectedQuestion['picture']) && filter_var($selectedQuestion['picture'], FILTER_VALIDATE_URL)) ? 'checked' : ''; ?>>
                                <label for="img_source_url">Par URL</label>
                            </div>
                            <div class="source-option">
                                <input type="radio" name="image_source" id="img_source_upload" value="upload" <?= (!empty($selectedQuestion['picture']) && !filter_var($selectedQuestion['picture'], FILTER_VALIDATE_URL)) ? 'checked' : ''; ?>>
                                <label for="img_source_upload">Par téléchargement</label>
                            </div>
                            <div class="source-option">
                                <input type="radio" name="image_source" id="img_source_none" value="none" <?= empty($selectedQuestion['picture']) ? 'checked' : ''; ?>>
                                <label for="img_source_none">Pas d'image</label>
                            </div>
                        </div>
                        
                        <!-- Option URL -->
                        <div id="url_option" class="image-option <?= (!empty($selectedQuestion['picture']) && filter_var($selectedQuestion['picture'], FILTER_VALIDATE_URL)) ? '' : 'hidden'; ?>">
                            <label for="picture_url">URL de l'image</label>
                            <input type="text" id="picture_url" name="picture_url" class="form-control" placeholder="Entrez l'URL d'une image (JPG, PNG, GIF)" value="<?= (isset($selectedQuestion['picture']) && filter_var($selectedQuestion['picture'], FILTER_VALIDATE_URL)) ? $selectedQuestion['picture'] : ''; ?>">
                        </div>
                        
                        <!-- Option téléchargement - IMPORTANT: Le nom du champ doit être correct -->
                        <div id="upload_option" class="image-option <?= (!empty($selectedQuestion['picture']) && !filter_var($selectedQuestion['picture'], FILTER_VALIDATE_URL)) ? '' : 'hidden'; ?>">
                            <label for="question_image_file">Choisir un fichier</label>
                            <input type="file" id="question_image_file" name="question_image_file" class="form-control-file" accept="image/jpeg,image/png,image/gif">
                            <small class="form-text text-muted">Taille maximale: 2MB. Formats acceptés: JPG, PNG, GIF</small>
                            <div class="form-text">
                                <strong>Note:</strong> Les fichiers seront enregistrés dans le dossier uploads/questions/
                            </div>
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
// Débogage - à commenter en production
echo "<div style='display: none;'>";
echo "<pre>";
// print_r($_FILES);  // Afficher les informations sur les fichiers téléchargés
// print_r($questions);
// print_r($selectedQuestion);
// print_r($answers);
echo "</pre>";
echo "</div>";
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
    const noneRadio = document.getElementById('img_source_none');
    const urlOption = document.getElementById('url_option');
    const uploadOption = document.getElementById('upload_option');
    
    function toggleImageOptions() {
        if (urlRadio && uploadRadio && noneRadio) {
            if (urlRadio.checked) {
                urlOption.classList.remove('hidden');
                uploadOption.classList.add('hidden');
            } else if (uploadRadio.checked) {
                urlOption.classList.add('hidden');
                uploadOption.classList.remove('hidden');
            } else if (noneRadio.checked) {
                urlOption.classList.add('hidden');
                uploadOption.classList.add('hidden');
            }
        }
    }
    
    if (urlRadio && uploadRadio && noneRadio) {
        urlRadio.addEventListener('change', toggleImageOptions);
        uploadRadio.addEventListener('change', toggleImageOptions);
        noneRadio.addEventListener('change', toggleImageOptions);
        toggleImageOptions();
    }
    
    // Validation du formulaire avant envoi
    const questionForm = document.getElementById('questionEditForm');
    if (questionForm) {
        questionForm.addEventListener('submit', function(e) {
            console.log('Formulaire soumis');
            
            // Vérifier quelle source d'image est sélectionnée
            const imageSource = document.querySelector('input[name="image_source"]:checked')?.value;
            console.log('Source d\'image sélectionnée:', imageSource);
            
            if (imageSource === 'url' && document.getElementById('picture_url').value.trim() === '') {
                alert('Veuillez entrer une URL d\'image valide ou choisir une autre option.');
                e.preventDefault();
                return false;
            }
            
            if (imageSource === 'upload') {
                const fileInput = document.getElementById('question_image_file');
                const hasCurrentImage = document.querySelector('input[name="current_image"]');
                const removeImage = document.querySelector('input[name="remove_image"]')?.checked;
                
                // Vérifier si un fichier est sélectionné ou si une image existante est conservée
                if (fileInput.files.length === 0 && !hasCurrentImage && !removeImage) {
                    alert('Veuillez sélectionner un fichier à télécharger ou choisir une autre option.');
                    e.preventDefault();
                    return false;
                }
                
                // Afficher les détails du fichier pour débogage
                if (fileInput.files.length > 0) {
                    const file = fileInput.files[0];
                    console.log('Fichier sélectionné:', file.name);
                    console.log('Type:', file.type);
                    console.log('Taille:', file.size, 'bytes');
                }
            }
            
            return true;
        });
    }
});
</script>

<?php include 'views/layout/footer.php'; ?>