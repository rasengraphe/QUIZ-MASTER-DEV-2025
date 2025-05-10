<?php include 'views/layout/header.php'; ?>

<h2>Liste des Difficultés</h2>

<a href="index.php?action=difficultyForm">Ajouter une difficulté</a>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($difficulties as $difficulty) : ?>
            <tr>
                <td><?php echo htmlspecialchars($difficulty['Id_question_difficulte']); ?></td>
                <td><?php echo htmlspecialchars($difficulty['name']); ?></td>
                <td>
                    <a href="index.php?action=difficultyForm&id=<?php echo htmlspecialchars($difficulty['Id_question_difficulte']); ?>">Modifier</a> |
                    <a href="index.php?action=deleteDifficulty&id=<?php echo htmlspecialchars($difficulty['Id_question_difficulte']); ?>">Supprimer</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include 'views/layout/footer.php'; ?>