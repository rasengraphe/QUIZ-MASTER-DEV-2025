// Fonction pour ouvrir la modal de sélection de quiz
function openQuizModal() {
    console.log("Tentative d'ouverture de la modal");
    var modal = document.getElementById('quiz-modal');
    if (modal) {
        modal.style.display = 'block';
        document.body.classList.add('modal-open');
        console.log("Modal ouverte");
    } else {
        console.error("La modal n'existe pas !");
    }
}

// Fonction pour fermer la modal
function closeQuizModal() {
    console.log("Tentative de fermeture de la modal");
    var modal = document.getElementById('quiz-modal');
    if (modal) {
        modal.style.display = 'none';
        document.body.classList.remove('modal-open');
        console.log("Modal fermée");
    }
}

// Attendre que le document soit complètement chargé
document.addEventListener('DOMContentLoaded', function() {
    console.log("DOM chargé, initialisation des événements de la modal");
    
    // Ouvrir la modal avec le bouton
    var startQuizBtn = document.getElementById('start-quiz-btn');
    if (startQuizBtn) {
        startQuizBtn.addEventListener('click', function(e) {
            e.preventDefault();
            console.log("Bouton 'Commencer un quiz' cliqué");
            openQuizModal();
        });
    } else {
        console.error("Le bouton 'start-quiz-btn' n'existe pas !");
    }
    
    // Fermer la modal avec le bouton de fermeture
    var closeModalBtn = document.querySelector('.close-modal');
    if (closeModalBtn) {
        closeModalBtn.addEventListener('click', function() {
            console.log("Bouton de fermeture cliqué");
            closeQuizModal();
        });
    }
    
    // Fermer la modal en cliquant en dehors
    window.addEventListener('click', function(event) {
        var modal = document.getElementById('quiz-modal');
        if (modal && event.target === modal) {
            console.log("Clic en dehors de la modal");
            closeQuizModal();
        }
    });
    
    // Effet visuel sur les options de difficulté
    var difficultyOptions = document.querySelectorAll('.difficulty-option');
    difficultyOptions.forEach(function(option) {
        option.addEventListener('click', function() {
            // Sélectionne le bouton radio associé à cette option
            var radio = this.querySelector('input[type="radio"]');
            if (radio) {
                radio.checked = true;
                console.log('Option de difficulté sélectionnée:', radio.value);
            }
            
            // Met à jour l'apparence
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
});