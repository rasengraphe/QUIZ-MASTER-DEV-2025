<?php include 'views/layout/header.php'; ?>

<h2>Liste des Utilisateurs</h2>

<a href="index.php?action=userForm&type=player">Ajouter un Joueur</a> |
<a href="index.php?action=userForm&type=admin">Ajouter un Admin/Éditeur</a>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Email</th>
            <th>Type</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user) : ?>
            <tr>
                <td><?php echo htmlspecialchars($user['Id_player'] ?? $user['Id_admin_editor']); ?></td>
                <td><?php echo htmlspecialchars($user['name']); ?></td>
                <td><?php echo htmlspecialchars($user['first_name']); ?></td>
                <td><?php echo htmlspecialchars($user['email']); ?></td>
                <td><?php echo isset($user['Id_player']) ? 'Joueur' : 'Admin/Éditeur'; ?></td>
                <td>
                    <a href="index.php?action=userForm&id=<?php echo htmlspecialchars($user['Id_player'] ?? $user['Id_admin_editor']); ?>&type=<?php echo isset($user['Id_player']) ? 'player' : 'admin'; ?>">Modifier</a> |
                    <a href="index.php?action=deleteUser&id=<?php echo htmlspecialchars($user['Id_player'] ?? $user['Id_admin_editor']); ?>&type=<?php echo isset($user['Id_player']) ? 'player' : 'admin'; ?>">Supprimer</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include 'views/layout/footer.php'; ?>