<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Application de Quiz</title>
    <link rel="stylesheet" href="public/css/style.css"> <!-- Lien vers le fichier CSS compilé -->
<script src="public/js/script.js" defer></script> <!-- Ajout du script principal -->
<script src="public/js/modal.js" defer></script> <!-- Nouveau script pour la modal -->
</head>
<body>
<header>
    <h1>Quiz Master Dev 2025</h1>
    <nav>
        <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
            <a href="index.php?action=admin_dashboard">Accueil Admin</a>
        <?php elseif (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'player'): ?>
            <a href="index.php?action=player_dashboard">Accueil Joueur</a>
        <?php else: ?>
            <a href="index.php?action=register">S'inscrire</a>
            <a href="index.php?action=login">Se connecter</a>
        <?php endif; ?>

        <!-- Bouton de déconnexion pour les utilisateurs connectés -->
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="index.php?action=logout" class="logout-btn">Déconnexion</a>
        <?php endif; ?>
    </nav>
</header>
<main>