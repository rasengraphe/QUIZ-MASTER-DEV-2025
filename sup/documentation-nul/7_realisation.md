# 7. Réalisation du Projet

## 7.1 Méthodologie de Développement et Contexte Professionnel

### Contexte de Réalisation
Ce projet s'est déroulé en parallèle de mon stage chez Solidnames, une entreprise spécialisée dans l'hébergement web. Cette expérience professionnelle m'a permis d'enrichir mon approche du développement en :
- Appliquant les bonnes pratiques observées en entreprise
- Bénéficiant des conseils de développeurs expérimentés
- Comprenant les enjeux réels d'une application web professionnelle

### Planning et Organisation
Durant ces 10 semaines (Mars - Mai 2025), j'ai organisé mon travail en sprints tout en jonglant entre mon stage et le développement du projet :

1. **Sprint 1 : Fondations** (1-15 Mars 2025)
   - Définition de l'architecture MVC, inspirée des pratiques de Solidnames
   - Mise en place de la base de données avec les optimisations apprises en stage
   - Implémentation du système d'authentification sécurisé
   
2. **Sprint 2 : Fonctionnalités Core** (16 Mars - 15 Avril 2025)
   - Développement du système de quiz
   - Création de l'interface responsive
   - Intégration du système de score
   
3. **Sprint 3 : Perfectionnement** (16 Avril - 15 Mai 2025)
   - Optimisations de performance
   - Tests approfondis
   - Documentation détaillée

### Méthodologie Hybride
J'ai adapté ma méthodologie pour concilier stage et projet :
- **Matinées** : Stage chez Solidnames
- **Après-midis/Soirées** : Stage chez Solidnames/Développement du projet
- **Week-ends** : Développement du projet/ Tests et documentation

### Outils de Suivi
Pour suivre mon avancement, j'ai utilisé :
- Trello pour la gestion des tâches
- GitHub pour le versioning
- Discord pour les retours utilisateurs

## 7.2 Défis Techniques Rencontrés

### Challenge 1 : Performance des Quiz
Face au ralentissement constaté lors du chargement des quiz complexes, j'ai :
```php
// Optimisation du chargement des questions
public function loadQuizQuestions($quizId) {
    // Mise en place du cache
    if ($cached = $this->cache->get("quiz_$quizId")) {
        return $cached;
    }
    
    // Requête optimisée avec jointures
    $questions = $this->db->prepare("
        SELECT q.*, a.text as answer 
        FROM quiz_questions q 
        LEFT JOIN quiz_answers a ON q.id = a.question_id 
        WHERE q.quiz_id = ?
    ");
    
    // Mise en cache pour 1 heure
    $this->cache->set("quiz_$quizId", $questions, 3600);
    return $questions;
}
```

### Retours Utilisateurs et Tests
Pour valider l'ergonomie et l'intuitivité de l'application, j'ai mis en place une phase de tests utilisateurs avec ma famille :

#### Panel de Testeurs
- Mes enfants (8 et 12 ans) : un public idéal pour tester l'accessibilité
- Leurs amis : représentant la cible jeune de l'application
- Mon entourage proche : apportant un regard d'utilisateurs adultes

#### Retours Collectés
Les tests en famille ont révélé plusieurs points d'amélioration :
- Interface simplifiée suite aux remarques de mes enfants
- Ajout d'animations pour rendre l'expérience plus ludique
- Adaptation du niveau de difficulté selon l'âge

#### Bénéfices des Tests en Famille
Cette approche m'a permis de :
- Obtenir des retours sincères et directs
- Tester l'application dans des conditions réelles
- Adapter l'interface pour la rendre accessible à tous les âges

## 7.3 Défis Techniques et Solutions

### Optimisation des Performances
Face aux ralentissements constatés lors des tests avec mes enfants, j'ai implémenté plusieurs solutions :

#### 1. Système de Cache Intelligent
```php
class QuizCacheManager {
    private $redis;
    
    public function __construct() {
        $this->redis = new Redis();
        $this->redis->connect('127.0.0.1', 6379);
    }
    
    public function getQuizData($quizId) {
        $cacheKey = "quiz_{$quizId}_data";
        
        // Vérifie si les données sont en cache
        if ($cachedData = $this->redis->get($cacheKey)) {
            return json_decode($cachedData, true);
        }
        
        // Si non, récupère et met en cache
        $quizData = $this->fetchQuizData($quizId);
        $this->redis->setex($cacheKey, 3600, json_encode($quizData));
        
        return $quizData;
    }
}
```

#### 2. Chargement Progressif des Questions
Suite aux retours de mes enfants sur la lenteur du chargement initial :

```javascript
class QuizLoader {
    constructor() {
        this.questionsBuffer = 3; // Charge 3 questions à l'avance
    }
    
    async loadNextQuestions(currentIndex) {
        const nextQuestions = await fetch(`/api/questions/next/${currentIndex}`);
        return this.prepareQuestions(await nextQuestions.json());
    }
}
```

### Adaptation au Niveau des Utilisateurs
Les tests en famille m'ont permis d'identifier le besoin d'adapter la difficulté :

```php
class DifficultyManager {
    public function adjustDifficulty($userAge, $previousScores) {
        // Adaptation selon l'âge et les performances
        $baseLevel = $this->getBaseLevel($userAge);
        $adjustment = $this->calculateAdjustment($previousScores);
        
        return max(1, min(10, $baseLevel + $adjustment));
    }
}
```

### Interface Adaptative
Pour répondre aux besoins des différents âges :

```scss
// Styles adaptés aux enfants
.quiz-button {
    font-size: calc(1rem + 0.5vw);
    padding: 1.2rem;
    border-radius: 15px;
    
    &.young-user {
        font-size: calc(1.2rem + 0.5vw);
        padding: 1.5rem;
        // Couleurs vives pour les jeunes utilisateurs
        background: linear-gradient(45deg, #FF6B6B, #4ECDC4);
    }
}
```