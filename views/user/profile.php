<?php include 'views/layout/header.php'; ?>

<h2>Mon Profil</h2>

<p>Nom : <?php echo htmlspecialchars($player['name']); ?></p>
<p>Prénom : <?php echo htmlspecialchars($player['first_name']); ?></p>
<p>Email : <?php echo htmlspecialchars($player['email']); ?></p>

<a href="index.php?action=editProfile">Modifier mon profil</a>

<?php include 'views/layout/footer.php'; ?>