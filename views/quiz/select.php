<?php include 'views/layout/header.php'; ?>

<div class="quiz-container">
    <h2>Quiz Master</h2>
    <p>Prêt à tester vos connaissances ?</p>

    <div class="quiz-form-container">
        <form id="quiz-preferences-form" action="index.php?action=handle_play_quiz" method="POST">
            <input type="hidden" name="form_submitted" value="1">
            <div class="form-group">
                <label for="theme">Thème</label>
                <select id="theme" name="theme" required>
                    <option value="">Sélectionnez un thème</option>
                    <?php foreach ($categories as $category) : ?>
                        <option value="<?php echo $category['Id_question_category']; ?>">
                            <?php echo htmlspecialchars($category['title']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label>Niveau de difficulté</label>
                <div class="difficulty-selector">
                    <?php foreach ($difficulties as $difficulty) : ?>
                        <div class="difficulty-option">
                            <input type="radio" 
                                   id="difficulty_<?php echo $difficulty['Id_question_difficulte']; ?>" 
                                   name="difficulty" 
                                   value="<?php echo $difficulty['Id_question_difficulte']; ?>"
                                   <?php echo $difficulty['Id_question_difficulte'] == 2 ? 'checked' : ''; ?>>
                            <label for="difficulty_<?php echo $difficulty['Id_question_difficulte']; ?>">
                                <?php echo htmlspecialchars($difficulty['name']); ?>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <div class="form-group">
                <label for="questionCount">Nombre de questions: <span id="question-count-value">10</span></label>
                <input type="range" id="questionCount" name="questionCount" min="5" max="20" value="10" step="1">
            </div>
            
            <button type="submit" id="start-quiz-submit" class="btn primary-btn">Lancer le quiz</button>
        </form>
    </div>
</div>

<script>
    // Script intégré pour gérer les interactions du formulaire
    document.addEventListener('DOMContentLoaded', function() {
        // Effet visuel sur les options de difficulté
        console.log('Debug: Initialisation du formulaire');
        
        var difficultyOptions = document.querySelectorAll('.difficulty-option');
        console.log('Debug: Options de difficulté trouvées:', difficultyOptions.length);
        
        difficultyOptions.forEach(function(option) {
            option.addEventListener('click', function() {
                console.log('Debug: Option de difficulté cliquée');
                var radio = this.querySelector('input[type="radio"]');
                console.log('Debug: Radio button trouvé:', radio ? radio.value : 'non trouvé');
                
                if (radio) {
                    radio.checked = true;
                    console.log('Debug: Radio button coché:', radio.value);
                }
                
                difficultyOptions.forEach(function(opt) {
                    opt.classList.remove('selected');
                });
                this.classList.add('selected');
            });
            
            // Initialiser avec la sélection par défaut
            var radio = option.querySelector('input[type="radio"]');
            if (radio && radio.checked) {
                option.classList.add('selected');
            }
        });
        
        // Mettre à jour la valeur affichée du nombre de questions
        var questionCountSlider = document.getElementById('questionCount');
        var questionCountValue = document.getElementById('question-count-value');
        if (questionCountSlider && questionCountValue) {
            questionCountSlider.addEventListener('input', function() {
                questionCountValue.textContent = this.value;
            });
        }

        // Vérifier la soumission du formulaire
        var quizForm = document.getElementById('quiz-preferences-form');
        if (quizForm) {
            quizForm.addEventListener('submit', function(e) {
                console.log('Debug: Soumission du formulaire');
                
                var formData = new FormData(this);
                for (var pair of formData.entries()) {
                    console.log('Debug: Donnée du formulaire -', pair[0] + ': ' + pair[1]);
                }
                
                // Vérifier si un thème est sélectionné
                var themeSelect = document.getElementById('theme');
                console.log('Debug: Thème sélectionné:', themeSelect.value);
                
                if (themeSelect.value === '') {
                    console.log('Debug: Erreur - Aucun thème sélectionné');
                    e.preventDefault();
                    alert('Veuillez sélectionner un thème pour continuer');
                    return false;
                }
                
                // Vérifier si une difficulté est sélectionnée
                var difficultyInputs = document.querySelectorAll('input[name="difficulty"]');
                var difficultySelected = false;
                var selectedDifficulty = '';
                
                difficultyInputs.forEach(function(input) {
                    if (input.checked) {
                        difficultySelected = true;
                        selectedDifficulty = input.value;
                    }
                });
                
                console.log('Debug: Difficulté sélectionnée:', selectedDifficulty);
                
                if (!difficultySelected) {
                    console.log('Debug: Erreur - Aucune difficulté sélectionnée');
                    e.preventDefault();
                    alert('Veuillez sélectionner un niveau de difficulté pour continuer');
                    return false;
                }

                // Afficher un message de chargement
                document.getElementById('start-quiz-submit').textContent = 'Chargement...';
                document.getElementById('start-quiz-submit').disabled = true;
                
                console.log('Debug: Formulaire validé avec succès');
                return true;
            });
        }
    });
</script>

<?php include 'views/layout/footer.php'; ?>