<?php require_once 'views/layout/header.php'; ?>

<?php if (!$player): ?>
    <div class="error-message">
        <p>Une erreur s'est produite lors du chargement de votre profil. Veuillez vous reconnecter.</p>
        <a href="index.php?action=login" class="btn primary">Se reconnecter</a>
    </div>
<?php else: ?>
<!-- Débogage temporaire -->
<div style="background-color: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 20px; border-radius: 5px; display: none;">
    <h4>Informations de débogage</h4>
    <pre><?php
        echo "ID Joueur: " . ($_SESSION['user_id'] ?? 'non défini') . "\n";
        echo "Type utilisateur: " . ($_SESSION['user_type'] ?? 'non défini') . "\n";
        echo "Parties jouées: " . ($stats['gamesCount'] ?? 'non défini') . "\n";
        echo "Quiz complétés: " . ($stats['quizzesCompleted'] ?? 'non défini') . "\n";
        echo "Meilleur score: " . ($stats['bestScore'] ?? 'non défini') . "\n";
    ?></pre>
</div>

<script>
// Pour afficher/masquer les informations de débogage
document.addEventListener('DOMContentLoaded', function() {
    const debugInfo = document.querySelector('div[style*="display: none"]');
    if (debugInfo) {
        debugInfo.style.display = 'none';
        
        // Double-cliquer n'importe où pour afficher/masquer
        document.addEventListener('dblclick', function() {
            debugInfo.style.display = debugInfo.style.display === 'none' ? 'block' : 'none';
        });
    }
});
</script>

<!-- Nouvelle div englobante pour centrer le contenu -->
<div class="dashboard-wrapper">
    <div class="player-dashboard-container">
        <!-- Header du dashboard -->
        <div class="dashboard-header">
            <h1>Tableau de bord <span>Joueur</span></h1>
        </div>

        <!-- Contenu du dashboard -->
        <div class="player-dashboard">
            <!-- Section profil utilisateur -->
            <div class="dashboard-card profile-card">
                <div class="profile-header">
                    <div class="avatar-container">
                        <?php if(isset($player['Id_Avatar']) && $player['Id_Avatar'] > 0): ?>
                            <img class="avatar-image" src="public/img/avatars/avatar<?= $player['Id_Avatar'] ?>.png" alt="Avatar">
                        <?php else: ?>
                            <img class="avatar-image" src="public/img/avatars/default-avatar.png" alt="Avatar par défaut">
                        <?php endif; ?>
                    </div>
                    <div class="profile-details">
                        <h2><?= htmlspecialchars($player['first_name'] ?? '') ?> <?= htmlspecialchars($player['name'] ?? '') ?></h2>
                        <p class="member-since">Membre depuis <?= isset($player['date_creation']) ? date('d/m/Y', strtotime($player['date_creation'])) : 'N/A' ?></p>
                        <div class="score-badge"><?= $player['score'] ?? 0 ?> points</div>
                    </div>
                </div>
            </div>

            <!-- Section statistiques (ligne statique de gauche à droite) -->
            <div class="stats-container">
                <h2 class="section-title">Vos statistiques</h2>
                
                <div class="stats-row">
                    <div class="stat-box">
                        <div class="stat-icon"><i class="fas fa-gamepad"></i></div>
                        <div class="stat-value"><?= $stats['total_games'] ?? 0 ?></div>
                        <div class="stat-label">Parties jouées</div>
                    </div>
                    
                    <div class="stat-box">
                        <div class="stat-icon"><i class="fas fa-trophy"></i></div>
                        <div class="stat-value"><?= $stats['best_score'] ?? 0 ?></div>
                        <div class="stat-label">Meilleur score</div>
                    </div>
                    
                    <div class="stat-box">
                        <div class="stat-icon"><i class="fas fa-chart-line"></i></div>
                        <div class="stat-value"><?= $stats['avg_score'] ?? 0 ?></div>
                        <div class="stat-label">Score moyen</div>
                    </div>
                    
                    <div class="stat-box">
                        <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                        <div class="stat-value"><?= $stats['total_score'] ?? 0 ?></div>
                        <div class="stat-label">Score total</div>
                    </div>
                </div>
            </div>

            <!-- Actions principales (en dessous des statistiques) -->
            <div class="dashboard-card">
                <h2 class="section-title"><i class="fas fa-bolt"></i> Actions rapides</h2>
                <div class="action-buttons">
                    <a href="index.php?action=play_quiz" class="action-btn primary">
                        <i class="fas fa-play-circle"></i> Jouer à un quiz
                    </a>
                    <a href="index.php?action=history" class="action-btn secondary">
                        <i class="fas fa-history"></i> Historique des parties
                    </a>
                    <a href="index.php?action=edit_profile" class="action-btn tertiary">
                        <i class="fas fa-user-edit"></i> Modifier mon profil
                    </a>
                </div>
            </div>
            
            <!-- Quiz récemment joués -->
            <?php if (!empty($recent_games)): ?>
            <div class="dashboard-card">
                <h2 class="section-title"><i class="fas fa-history"></i> Parties récentes</h2>
                <div class="recent-games">
                    <?php foreach ($recent_games as $game): ?>
                    <div class="game-item">
                        <div class="game-info">
                            <h3 class="game-title"><?= htmlspecialchars($game['quiz_name'] ?? 'Quiz #'.$game['id']) ?></h3>
                            <div class="game-meta">
                                <span class="game-date"><i class="far fa-calendar-alt"></i> <?= isset($game['played_on']) ? date('d/m/Y', strtotime($game['played_on'])) : 'Date inconnue' ?></span>
                                <span class="game-time"><i class="far fa-clock"></i> <?= isset($game['played_on']) ? date('H:i', strtotime($game['played_on'])) : '' ?></span>
                                <span class="game-score"><i class="fas fa-star"></i> <?= $game['score'] ?? 'N/A' ?> points</span>
                            </div>
                        </div>
                        <a href="index.php?action=game_details&id=<?= $game['id'] ?>" class="view-details-btn">
                            <i class="fas fa-eye"></i> Détails
                        </a>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div class="view-all">
                    <a href="index.php?action=history">Voir tout l'historique <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
            <?php endif; ?>

            <!-- Quiz recommandés -->
            <?php if (!empty($recommended_quizzes)): ?>
            <div class="dashboard-card">
                <h2 class="section-title"><i class="fas fa-lightbulb"></i> Quiz recommandés</h2>
                <div class="quiz-grid">
                    <?php foreach ($recommended_quizzes as $quiz): ?>
                    <div class="quiz-card">
                        <div class="quiz-header">
                            <h3><?= htmlspecialchars($quiz['title'] ?? 'Quiz sans titre') ?></h3>
                            <span class="difficulty-badge <?= strtolower($quiz['difficulty'] ?? 'unknown') ?>"><?= htmlspecialchars($quiz['difficulty'] ?? 'Difficulté inconnue') ?></span>
                        </div>
                        <p class="quiz-description"><?= htmlspecialchars($quiz['description'] ?? 'Pas de description disponible') ?></p>
                        <a href="index.php?action=play_quiz&quiz_id=<?= $quiz['id'] ?>" class="play-btn">
                            <i class="fas fa-play"></i> Jouer maintenant
                        </a>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div class="view-all">
                    <a href="index.php?action=quiz_list">Voir tous les quiz disponibles <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Ajouter ce CSS dans le head ou dans un fichier CSS séparé -->
<style>
/* Styles existants... */

/* Conteneur principal pour centrer le tout */
.dashboard-wrapper {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.player-dashboard-container {
    width: 100%;
    margin: 0 auto;
}

/* Style pour les statistiques en ligne */
.stats-container {
    background-color: #f8f9fa;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 25px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    text-align: center;
}

.section-title {
    margin-bottom: 20px;
    color: #333;
    font-size: 1.5rem;
    font-weight: 600;
}

.stats-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: nowrap;
    gap: 15px;
    margin: 0 auto;
    max-width: 900px;
}

.stat-box {
    flex: 1;
    padding: 15px;
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    text-align: center;
}

.stat-icon {
    font-size: 24px;
    margin-bottom: 8px;
}

.stat-value {
    font-size: 28px;
    font-weight: bold;
    color: #2c3e50;
    margin-bottom: 4px;
}

.stat-label {
    font-size: 14px;
    color: #7f8c8d;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Couleurs pour les icônes de statistiques */
.stat-box:nth-child(1) .stat-icon { color: #3498db; }
.stat-box:nth-child(2) .stat-icon { color: #f39c12; }
.stat-box:nth-child(3) .stat-icon { color: #2ecc71; }
.stat-box:nth-child(4) .stat-icon { color: #9b59b6; }

/* Style pour les boutons d'action */
.dashboard-card {
    background-color: #f8f9fa;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 25px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    text-align: center;
}

.action-buttons {
    display: flex;
    justify-content: center;
    gap: 20px;
    flex-wrap: wrap;
    margin: 0 auto;
    max-width: 900px;
}

.action-btn {
    padding: 12px 25px;
    border-radius: 6px;
    color: white;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
    min-width: 180px;
}

.action-btn i {
    margin-right: 8px;
}

.action-btn.primary { background-color: #3498db; }
.action-btn.secondary { background-color: #2ecc71; }
.action-btn.tertiary { background-color: #f39c12; }

.action-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

/* Responsive */
@media (max-width: 768px) {
    .stats-row {
        flex-wrap: wrap;
    }
    
    .stat-box {
        min-width: calc(50% - 15px);
        margin-bottom: 15px;
    }
    
    .action-btn {
        min-width: 100%;
        margin-bottom: 10px;
    }
}
</style>

<!-- Fontawesome pour les icônes -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<?php require_once 'views/layout/footer.php'; ?>