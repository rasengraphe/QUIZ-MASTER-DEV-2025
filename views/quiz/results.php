<?php include 'views/layout/header.php'; ?>

<div class="results-container">
    <div class="results-card">
        <h2>Résultats du Quiz</h2>
        
        <div class="score">
            <?php echo $score; ?> / <?php echo $totalQuestions; ?>
        </div>
        
        <?php if ($percentageScore >= 80): ?>
            <p class="result-message success">Excellent travail ! Vous avez très bien réussi ce quiz.</p>
        <?php elseif ($percentageScore >= 60): ?>
            <p class="result-message good">Bon travail ! Vous avez bien réussi ce quiz.</p>
        <?php elseif ($percentageScore >= 40): ?>
            <p class="result-message average">Pas mal ! Mais vous pourriez faire mieux.</p>
        <?php else: ?>
            <p class="result-message needs-improvement">Vous devriez réviser un peu plus ce sujet.</p>
        <?php endif; ?>
        
        <div class="result-details">
            <?php foreach ($questionResults as $index => $result): ?>
                <div class="question-result">
                    <h4>Question <?php echo $index + 1; ?></h4>
                    <p><?php echo htmlspecialchars($result['question']); ?></p>
                    
                    <?php if ($result['correct']): ?>
                        <p class="correct">✓ Votre réponse est correcte</p>
                    <?php else: ?>
                        <p class="incorrect">✗ Votre réponse est incorrecte</p>
                        <p>La bonne réponse était: <?php echo htmlspecialchars($result['correctAnswer']); ?></p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="actions">
            <a href="index.php?action=play_quiz&id=<?php echo $quizId; ?>" class="btn">Rejouer le quiz</a>
            
            <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin'): ?>
                <!-- Pour les administrateurs -->
                <a href="index.php?action=admin_dashboard" class="btn secondary">Tableau de bord Admin</a>
            <?php elseif (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'player'): ?>
                <!-- Pour les joueurs -->
                <a href="index.php?action=player_dashboard" class="btn secondary">Tableau de bord Joueur</a>
            <?php else: ?>
                <!-- Pour les visiteurs non connectés -->
                <a href="index.php" class="btn secondary">Retour à l'accueil</a>
            <?php endif; ?>
        </div>

        <!-- Ajoutez ce bouton à votre page de résultats -->
        <div class="share-container">
            <button id="shareButton" class="btn share-btn">
                <i class="fas fa-share-alt"></i> Partager mon score
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const shareButton = document.getElementById('shareButton');
    
    // Vérifier si l'API de partage est disponible
    if (navigator.share) {
        shareButton.addEventListener('click', async () => {
            try {
                // Récupérer les données du quiz et du score
                const quizTitle = "<?php echo htmlspecialchars($quiz['title'] ?? 'Quiz Master'); ?>";
                const score = "<?php echo $correctAnswers ?? 0; ?>";
                const totalQuestions = "<?php echo $totalQuestions ?? 0; ?>";
                const percentage = Math.round((score / totalQuestions) * 100);
                
                // Préparer les données à partager
                await navigator.share({
                    title: `Mon score sur ${quizTitle}`,
                    text: `Je viens d'obtenir ${score}/${totalQuestions} (${percentage}%) sur le quiz "${quizTitle}"! Essayez-le aussi !`,
                    url: window.location.href
                });
                console.log('Contenu partagé avec succès');
            } catch (err) {
                console.log(`Erreur lors du partage: ${err}`);
            }
        });
        
        // Afficher le bouton de partage
        shareButton.style.display = 'flex';
    } else {
        // Si l'API n'est pas supportée, on peut afficher des liens directs
        shareButton.innerHTML = `
            <a href="https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(window.location.href)}" 
               target="_blank" rel="noopener noreferrer">
                <i class="fab fa-facebook"></i> Partager sur Facebook
            </a>`;
        shareButton.classList.add('fallback');
    }
});
</script>

<?php include 'views/layout/footer.php'; ?>