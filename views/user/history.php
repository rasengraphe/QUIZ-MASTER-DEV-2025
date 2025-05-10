<?php include 'views/layout/header.php'; ?>

<h2>Mon Historique de Quiz</h2>

<?php if (empty($history)) : ?>
    <p>Vous n'avez pas encore passé de quiz.</p>
<?php else : ?>
    <table>
        <thead>
            <tr>
                <th>Titre du Quiz</th>
                <th>Description</th>
                <th>Score</th>
                <th>Date de Passage</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($history as $item) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['title']); ?></td>
                    <td><?php echo htmlspecialchars($item['description']); ?></td>
                    <td><?php echo htmlspecialchars($item['score']); ?></td>
                    <td><?php echo htmlspecialchars($item['date_taken']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<a href="index.php?action=profile">Retour à mon profil</a>

<?php include 'views/layout/footer.php'; ?>