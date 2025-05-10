<?php include 'views/layout/header.php'; ?>

<?php
// Afficher tous les messages d'erreur/succès stockés en session
if (isset($_SESSION['error'])) {
    echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
    unset($_SESSION['error']);
}

if (isset($_SESSION['success'])) {
    echo '<div class="alert alert-success">' . $_SESSION['success'] . '</div>';
    unset($_SESSION['success']);
}

// Débug - Afficher la requête qui devrait avoir été exécutée
echo "<!-- debug: GET params: " . htmlspecialchars(print_r($_GET, true)) . " -->";
echo "<!-- debug: POST params: " . htmlspecialchars(print_r($_POST, true)) . " -->";
?>

<h2>Liste des Questions</h2>

<div class="button-container" style="margin-bottom: 20px;">
    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'super_admin') : ?>
        <a href="index.php?action=addQuestion" class="btn primary">Ajouter une question</a>
    <?php endif; ?>
    <a href="index.php?action=admin_dashboard" class="btn secondary">Retour au tableau de bord</a>
</div>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Catégorie</th>
            <th>Difficulté</th>
            <th>Texte</th>
            <th>Image</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($questions)) : ?>
            <?php foreach ($questions as $question) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($question['Id_question']); ?></td>
                    <td><?php echo htmlspecialchars($question['category']['title'] ?? 'Non défini'); ?></td>
                    <td><?php echo htmlspecialchars($question['difficulty'] ?? 'Non défini'); ?></td>
                    <td><?php echo htmlspecialchars($question['text']); ?></td>
                    <td>
                        <?php if (!empty($question['picture'])) : ?>
                            <?php 
                            // Correction du chemin d'affichage de l'image
                            $picturePath = $question['picture'];
                            // Si le chemin commence déjà par 'public/' on ne l'ajoute pas
                            if (strpos($picturePath, 'public/') !== 0 && strpos($picturePath, 'http') !== 0) {
                                $picturePath = $picturePath;
                            }
                            ?>
                            <img src="<?php echo htmlspecialchars($picturePath); ?>" alt="Image de la question" style="max-width: 100px; max-height: 100px;">
                        <?php else : ?>
                            Aucune image
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'super_admin') : ?>
                            <a href="index.php?action=editQuestion&id=<?php echo htmlspecialchars($question['Id_question']); ?>" class="btn edit">Modifier</a>
                            <a href="index.php?action=deleteQuestion&id=<?php echo htmlspecialchars($question['Id_question']); ?>" class="btn delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette question ?');">Supprimer</a>
                        <?php else : ?>
                            Non autorisé
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else : ?>
            <tr>
                <td colspan="6">Aucune question trouvée.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php include 'views/layout/footer.php'; ?>