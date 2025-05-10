<?php include 'views/layout/header.php'; ?>

<h2>Liste des Quiz</h2>

<?php if (empty($quizzes)) : ?>
    <p>Aucun quiz disponible pour le moment.</p>
<?php else : ?>
    <ul>
        <?php foreach ($quizzes as $quiz) : ?>
            <li>
                <a href="index.php?action=play_quiz&id=<?php echo htmlspecialchars($quiz['Id_quiz']); ?>">
                    <?php echo htmlspecialchars($quiz['title']); ?>
                </a>
                <p><?php echo htmlspecialchars($quiz['description']); ?></p>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<?php include 'views/layout/footer.php'; ?>