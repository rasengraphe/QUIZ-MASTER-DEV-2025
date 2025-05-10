<?php include 'views/layout/header.php'; ?>

<h2>Liste des Catégories</h2>

<a href="index.php?action=categoryForm">Ajouter une catégorie</a>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Titre</th>
            <th>Description</th>
            <th>Image</th>
            <th>Active</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($categories as $category) : ?>
            <tr>
                <td><?php echo htmlspecialchars($category['Id_question_category']); ?></td>
                <td><?php echo htmlspecialchars($category['details']['title']); ?></td>
                <td><?php echo htmlspecialchars($category['details']['text']); ?></td>
                <td><?php echo htmlspecialchars($category['details']['picture']); ?></td>
                <td><?php echo htmlspecialchars($category['details']['active']); ?></td>
                <td>
                    <a href="index.php?action=categoryForm&id=<?php echo htmlspecialchars($category['Id_question_category']); ?>">Modifier</a> |
                    <a href="index.php?action=deleteCategory&id=<?php echo htmlspecialchars($category['Id_question_category']); ?>">Supprimer</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include 'views/layout/footer.php'; ?>