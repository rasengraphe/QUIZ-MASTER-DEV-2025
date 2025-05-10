<?php include 'views/layout/header.php'; ?>

<div class="player-dashboard-container">
  <div class="dashboard-header">
    <h1>Bienvenue, <span><?php echo htmlspecialchars($player['name'] ?? 'Joueur'); ?></span></h1>
  </div>
  
  <div class="player-dashboard">
    <!-- Profil du joueur -->
    <div class="dashboard-card profile-card">
      <div class="profile-header">
        <div class="avatar-container">
          <?php 
          $avatarId = $player['Id_Avatar'] ?? 1;
          $avatarPath = "assets/images/avatars/avatar{$avatarId}.png";
          
          // Vérifier si l'image existe
          if (!file_exists($avatarPath)) {
              $avatarPath = "assets/images/avatars/default.png";
          }
          ?>
          <img src="<?php echo $avatarPath; ?>" class="avatar-image" alt="Avatar du joueur">
        </div>
        
        <div class="profile-details">
          <h2><?php echo htmlspecialchars($player['name'] ?? 'Inconnu'); ?> <?php echo htmlspecialchars($player['first_name'] ?? ''); ?></h2>
          <p class="member-since">Membre depuis <?php echo date('d/m/Y', strtotime($player['date_creation'] ?? 'now')); ?></p>
          <div class="score-badge">Score total: <?php echo htmlspecialchars($player['score'] ?? '0'); ?></div>
        </div>
      </div>
    </div>
    
    <!-- Statistiques du joueur -->
    <div class="dashboard-stats">
      <div class="stat-card">
        <div class="stat-icon">
          <i class="fas fa-gamepad"></i>
        </div>
        <div class="stat-content">
          <h3>Parties jouées</h3>
          <p class="stat-value"><?php echo $stats['gamesCount'] ?? 0; ?></p>
        </div>
      </div>
      
      <div class="stat-card">
        <div class="stat-icon">
          <i class="fas fa-check-circle"></i>
        </div>
        <div class="stat-content">
          <h3>Quiz complétés</h3>
          <p class="stat-value"><?php echo $stats['quizzesCompleted'] ?? 0; ?></p>
        </div>
      </div>
      
      <div class="stat-card">
        <div class="stat-icon">
          <i class="fas fa-trophy"></i>
        </div>
        <div class="stat-content">
          <h3>Meilleur score</h3>
          <p class="stat-value"><?php echo $stats['bestScore'] ?? 0; ?></p>
        </div>
      </div>
    </div>
    
    <!-- Actions -->
    <div class="dashboard-card">
      <h2 class="section-title"><i class="fas fa-bolt"></i> Actions rapides</h2>
      <div class="action-buttons">
        <a href="index.php?action=play_quiz" class="action-btn primary">
          <i class="fas fa-play"></i> Jouer
        </a>
        <a href="index.php?action=leaderboard" class="action-btn secondary">
          <i class="fas fa-trophy"></i> Classement
        </a>
        <a href="index.php?action=edit_profile" class="action-btn tertiary">
          <i class="fas fa-user-edit"></i> Modifier profil
        </a>
      </div>
    </div>
    
    <!-- Parties récentes -->
    <div class="dashboard-card">
      <h2 class="section-title"><i class="fas fa-history"></i> Parties récentes</h2>
      <div class="recent-games">
        <?php if (!empty($recentGames)): ?>
          <?php foreach ($recentGames as $game): ?>
            <div class="game-item">
              <div class="game-info">
                <h4 class="game-title"><?php echo htmlspecialchars($game['quiz_name'] ?? 'Quiz #'.$game['quiz_id']); ?></h4>
                <div class="game-meta">
                  <span><i class="fas fa-calendar"></i> <?php echo date('d/m/Y H:i', strtotime($game['played_on'] ?? 'now')); ?></span>
                  <span class="game-score"><i class="fas fa-star"></i> Score: <?php echo htmlspecialchars($game['score'] ?? 0); ?></span>
                  <span>
                    <?php if (isset($game['completed']) && $game['completed'] == 1): ?>
                      <i class="fas fa-check-circle"></i> Terminé
                    <?php else: ?>
                      <i class="fas fa-clock"></i> En cours
                    <?php endif; ?>
                  </span>
                </div>
              </div>
              <a href="index.php?action=game_details&id=<?php echo $game['id'] ?? 0; ?>" class="view-details-btn">
                <i class="fas fa-eye"></i> Détails
              </a>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p class="no-data">Vous n'avez pas encore joué de quiz.</p>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<?php include 'views/layout/footer.php'; ?>