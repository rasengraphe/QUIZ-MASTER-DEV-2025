<?php include 'views/layout/header.php'; ?>

<h2>Liste des Quiz</h2>

<a href="index.php?action=quizForm">Ajouter un quiz</a>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Titre</th>
            <th>Description</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($quizzes as $quiz) : ?>
            <tr>
                <td><?php echo htmlspecialchars($quiz['Id_quiz']); ?></td>
                <td><?php echo htmlspecialchars($quiz['title']); ?></td>
                <td><?php echo htmlspecialchars($quiz['description']); ?></td>
                <td>
                    <a href="index.php?action=quizForm&id=<?php echo htmlspecialchars($quiz['Id_quiz']); ?>">Modifier</a> |
                    <a href="index.php?action=deleteQuiz&id=<?php echo htmlspecialchars($quiz['Id_quiz']); ?>">Supprimer</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include 'views/layout/footer.php'; ?>