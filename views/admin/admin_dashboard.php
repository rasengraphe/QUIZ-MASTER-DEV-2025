<?php include 'views/layout/header.php'; ?>

<main class="admin-main">
<div class="admin-card">
    <h1>Tableau de bord Admin</h1>
    <p>Bienvenue, <?php echo $_SESSION['user_name'] ?? 'Admin'; ?>. Gérez votre quiz depuis ce tableau de bord.</p>
    
    <div class="admin-actions">

<a href="index.php?action=addQuestion" class="admin-button">
<span class="icon">➕</span>
Ajouter une question
</a>

<a href="index.php?action=edit_question" class="admin-button">
        <span class="icon">✏️</span>
Modifier une question
</a>

<a href="index.php?action=questions" class="admin-button">
        <span class="icon">🗑️</span>
Supprimer une question
</a>

<a href="index.php?action=play_quiz" class="admin-button">
        <span class="icon">🎮</span>
Jouer au quiz
</a>
<a href="index.php?action=logout" class="admin-button">
        <span class="icon">🚪</span>
Se déconnecter
</a>


</main>

<?php include 'views/layout/footer.php'; ?>