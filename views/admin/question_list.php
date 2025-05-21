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
        <a href="index.php?action=addQuestion" class="btn add-button">Ajouter une question</a>
    <?php endif; ?>
    <a href="index.php?action=admin_dashboard" class="btn secondary">Retour au tableau de bord</a>
</div>

<div style="display: flex; justify-content: center; width: 100%;">
    <select class="question-list" onchange="window.location.href=this.value">
        <option value="">▼ Sélectionnez une question ▼</option>
        <?php if (!empty($questions)) : ?>
            <?php foreach ($questions as $question) : ?>
                <option value="index.php?action=editQuestion&id=<?php echo htmlspecialchars($question['Id_question']); ?>">
                    Question <?php echo htmlspecialchars($question['Id_question']); ?> - 
                    <?php echo htmlspecialchars(substr($question['text'], 0, 100)) . '...'; ?>
                </option>
            <?php endforeach; ?>
        <?php endif; ?>
    </select>
</div>

<style>
.question-list {
    width: 70%;
    padding: 15px;
    margin: 30px auto;
    border: 2px solid #007bff;
    border-radius: 8px;
    font-size: 16px;
    text-align: center;
    text-align-last: center;
    background-color: white;
    cursor: pointer;
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 1rem center;
    background-size: 1em;
}

.question-list:hover {
    border-color: #0056b3;
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
}

.question-list option {
    text-align: center;
    padding: 10px;
}

.add-button {
    background-color: #28a745;
    color: white;
    border: 2px solid #28a745;
}

.add-button:hover {
    background-color: #218838;
    border-color: #1e7e34;
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
}
</style>

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

<div style="display: flex; justify-content: center; margin: 40px 0;">
    <a href="index.php?action=admin_dashboard" class="btn secondary">Retour au tableau de bord</a>
</div>

<?php include 'views/layout/footer.php'; ?>