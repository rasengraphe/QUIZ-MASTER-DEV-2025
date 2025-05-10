<?php include 'views/layout/header.php'; ?>

<div class="admin-card">
    <h1>Tableau de bord administrateur</h1>
    
    <div class="admin-actions">
        <a href="index.php?action=addQuestion" class="btn primary">Ajouter une question</a>
        <a href="index.php?action=manageQuestions" class="btn">Gérer les questions</a>
    </div>
    
    <!-- Le reste du contenu du tableau de bord... -->
</div>

<?php include 'views/layout/footer.php'; ?>