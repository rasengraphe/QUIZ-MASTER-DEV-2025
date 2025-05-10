<?php include 'views/layout/header.php'; ?>

<div class="quiz-container">
    <?php if ($quiz): ?>
        <h2><?php echo htmlspecialchars($quiz['title'] ?? ''); ?></h2>
    <?php else: ?>
        <h2>Quiz en cours</h2>
    <?php endif; ?>
    
    <div class="quiz-progress">
        <div class="progress-header">
            <p class="progress-text">Question <?php echo ($currentQuestionIndex + 1); ?> / <?php echo $totalQuestions; ?></p>
        </div>
        <div class="progress-bar">
            <div class="progress-fill" style="width: <?php echo (($currentQuestionIndex + 1) / $totalQuestions * 100); ?>%"></div>
        </div>
    </div>

    <?php if (empty($questions)): ?>
        <div class="error-message">
            <p>Aucune question disponible pour ce quiz.</p>
            <a href="index.php?action=select_quiz" class="btn">Retour à la sélection</a>
        </div>
    <?php else: ?>
        <?php $question = $questions[$currentQuestionIndex]; ?>
        
        <div class="question-box active">
            <div class="question-content">
                <p class="question-text"><?php echo htmlspecialchars($question['text'] ?? ''); ?></p>
                
                <?php if (!empty($question['picture'])): ?>
                    <div class="question-image-container">
                        <img src="<?php echo htmlspecialchars($question['picture']); ?>" alt="Image de la question">
                    </div>
                <?php endif; ?>
            </div>
            
            <form id="quiz-form" method="POST" action="index.php?action=submit_quiz">
                <input type="hidden" name="quiz_id" value="<?php echo htmlspecialchars($quizId); ?>">
                <input type="hidden" name="current_question_index" value="<?php echo $currentQuestionIndex; ?>">
                
                <div class="answers">
                    <?php if (!empty($question['answers'])): ?>
                        <?php foreach($question['answers'] as $answer): ?>
                            <div class="answer">
                                <label class="answer-label">
                                    <input type="radio" name="answer" value="<?php echo htmlspecialchars($answer['Id_question_answer'] ?? ''); ?>" required>
                                    <span class="answer-text"><?php echo htmlspecialchars($answer['text'] ?? ''); ?></span>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="no-answers">Aucune réponse disponible pour cette question.</p>
                    <?php endif; ?>
                </div>
                
                <div class="question-actions">
                    <button type="submit" class="btn primary-btn">
                        <?php echo ($currentQuestionIndex + 1 < $totalQuestions) ? 'Question suivante' : 'Terminer le quiz'; ?>
                    </button>
                </div>
            </form>
        </div>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const answers = document.querySelectorAll('.answer');
    
    answers.forEach(answer => {
        answer.addEventListener('click', function() {
            answers.forEach(a => a.classList.remove('selected'));
            this.classList.add('selected');
            const radio = this.querySelector('input[type="radio"]');
            if (radio) {
                radio.checked = true;
            }
        });
    });
});
</script>

<?php include 'views/layout/footer.php'; ?>