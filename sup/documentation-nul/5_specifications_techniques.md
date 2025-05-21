# 5. Spécifications Techniques

## 5.1 Environnement de Développement
Pour ce projet, j'ai mis en place un environnement de développement robuste et moderne :

### Stack Technique Principal
- **Serveur Local** : WampServer 3.3.0
  - Choisi pour sa stabilité et sa simplicité de configuration
  - Configuration personnalisée pour les besoins du projet
  - Facilité de déploiement vers la production

### Outils de Développement
- **IDE** : Visual Studio Code
  - Extensions PHP installées pour le debugging
  - Configuration personnalisée pour le PSR-12
  - Intégration Git native

- **Contrôle de Version**
  - Git avec stratégie de branches (main, develop, features)
  - GitHub pour le stockage distant
  - Actions GitHub pour l'intégration continue

## 5.2 Technologies Utilisées

### Backend
J'ai choisi PHP 8.1 pour plusieurs raisons :
- **Nouvelles Fonctionnalités**
  - Types de retour union
  - Promotion des propriétés du constructeur
  - Match expression pour une meilleure lisibilité

- **Base de Données**
  ```sql
  -- Exemple de structure optimisée
  CREATE TABLE quiz_questions (
      id INT PRIMARY KEY AUTO_INCREMENT,
      title VARCHAR(255) NOT NULL,
      difficulty ENUM('easy', 'medium', 'hard')
  ) ENGINE=InnoDB;
  ```

### Frontend
Mon approche frontend s'est concentrée sur la performance :

- **HTML5 Sémantique**
  ```html
  <article class="quiz-card">
      <header class="quiz-header">
          <h2><!-- Titre dynamique --></h2>
      </header>
  </article>
  ```

- **CSS Modern**
  ```css
  /* Variables personnalisées */
  :root {
      --primary-color: #3498db;
      --secondary-color: #2ecc71;
  }
  ```

- **JavaScript Modulaire**
  ```javascript
  // Architecture basée sur les modules
  import { QuizManager } from './modules/QuizManager.js';
  ```

## 5.3 Architecture Logicielle
J'ai implémenté une architecture MVC personnalisée :

```plaintext
📁 QUIZ-MASTER-DEV-2025/
├── 📁 app/
│   ├── 📁 Controllers/
│   ├── 📁 Models/
│   └── 📁 Views/
├── 📁 config/
├── 📁 public/
└── 📁 tests/
```

## 5.4 Sécurité
Mesures de sécurité implémentées :

- **Protection Contre les Injections**
  ```php
  public function secureQuery($param) {
      return $this->db->prepare("SELECT * FROM users WHERE id = ?");
  }
  ```

- **Authentification**
  - Sessions sécurisées avec régénération d'ID
  - Tokens CSRF sur tous les formulaires
  - Stockage sécurisé des mots de passe (Bcrypt)