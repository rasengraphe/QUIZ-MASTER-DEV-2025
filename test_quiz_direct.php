<?php
// Script de test direct pour diagnostiquer le problème avec les quiz
session_start();

// Inclusion de la configuration de la base de données
require_once 'config/database.php';
// Les variables de connexion ($db_host, $db_name, $db_user, $db_pass) sont maintenant disponibles

// Fonctions utilitaires pour les tests
function printTable($data, $title = null) {
    if ($title) echo "<h3>$title</h3>";
    echo '<table border="1" cellpadding="5" cellspacing="0">';

// En-têtes
    if (!empty($data)) {
        echo '<tr>';
        foreach (array_keys($data[0]) as $header) {
            echo "<th>$header</th>";
        }
        echo '</tr>';
    }
    
    // Données
    foreach ($data as $row) {
        echo '<tr>';
    foreach ($row as $cell) {
            echo '<td>' . (is_null($cell) ? 'NULL' : htmlspecialchars($cell)) . '</td>';
        }
        echo '</tr>';
    }
    
    echo '</table>';
}

function executeQuery($db, $sql, $params = []) {
    try {
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo '<div style="color: red; padding: 10px; margin: 10px 0; border: 1px solid red;">';
        echo '<strong>Erreur SQL:</strong> ' . htmlspecialchars($e->getMessage()) . '<br>';
        echo '<strong>Requête:</strong> ' . htmlspecialchars($sql);
        echo '</div>';
        return [];
    }
}

// Création d'un quiz directement (sans utiliser le modèle)
function createQuizDirectly($db, $title, $description, $userId) {
    try {
        // Vérifier d'abord si la table quizzes existe
        $tableExists = $db->query("SHOW TABLES LIKE 'quizzes'")->rowCount() > 0;
        
        if (!$tableExists) {
            // La table n'existe pas, nous devons la créer
            $db->exec("
                CREATE TABLE IF NOT EXISTS quizzes (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    title VARCHAR(255) NOT NULL,
                    description TEXT,
                    created_by INT NOT NULL,
                    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    FOREIGN KEY (created_by) REFERENCES quiz_users(id) ON DELETE CASCADE
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
            ");
            
            echo '<div style="color: green; padding: 10px; margin: 10px 0; border: 1px solid green;">';
            echo 'Table quizzes créée avec succès!';
            echo '</div>';
        }
        
        // Ensuite, essayons d'insérer le quiz
        $stmt = $db->prepare("
            INSERT INTO quizzes (title, description, created_by)
            VALUES (:title, :description, :created_by)
        ");
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':created_by', $userId);
        $stmt->execute();
        
        $quizId = $db->lastInsertId();
        echo '<div style="color: green; padding: 10px; margin: 10px 0; border: 1px solid green;">';
        echo "Quiz créé avec succès! ID: $quizId";
        echo '</div>';
        
        return $quizId;
} catch (PDOException $e) {
    echo '<div style="color: red; padding: 10px; margin: 10px 0; border: 1px solid red;">';
        echo '<strong>Erreur lors de la création du quiz:</strong> ' . htmlspecialchars($e->getMessage());
        echo '</div>';
        return false;
    }
}

// Fonction pour ajouter une question à un quiz
function addQuestionToQuiz($db, $quizId, $questionId, $order) {
    try {
        // Vérifier si la table quiz_questions existe
        $tableExists = $db->query("SHOW TABLES LIKE 'quiz_questions'")->rowCount() > 0;
        
        if (!$tableExists) {
            // La table n'existe pas, nous devons la créer
            $db->exec("
                CREATE TABLE IF NOT EXISTS quiz_questions (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    quiz_id INT NOT NULL,
                    question_id INT NOT NULL,
                    question_order INT NOT NULL DEFAULT 0,
                    UNIQUE KEY unique_quiz_question (quiz_id, question_id),
                    FOREIGN KEY (quiz_id) REFERENCES quizzes(id) ON DELETE CASCADE,
                    FOREIGN KEY (question_id) REFERENCES quiz_question(Id_question) ON DELETE CASCADE
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
            ");
            
            echo '<div style="color: green; padding: 10px; margin: 10px 0; border: 1px solid green;">';
            echo 'Table quiz_questions créée avec succès!';
            echo '</div>';
        }
        
        // Insérer la question dans le quiz
        $stmt = $db->prepare("
            INSERT INTO quiz_questions (quiz_id, question_id, question_order)
            VALUES (:quiz_id, :question_id, :order)
        ");
        $stmt->bindParam(':quiz_id', $quizId);
        $stmt->bindParam(':question_id', $questionId);
        $stmt->bindParam(':order', $order);
        $stmt->execute();
        
        echo '<div style="color: green; padding: 10px; margin: 10px 0; border: 1px solid green;">';
        echo "Question $questionId ajoutée au quiz $quizId avec succès!";
        echo '</div>';
        
        return true;
    } catch (PDOException $e) {
        echo '<div style="color: red; padding: 10px; margin: 10px 0; border: 1px solid red;">';
        echo '<strong>Erreur lors de l\'ajout de la question au quiz:</strong> ' . htmlspecialchars($e->getMessage());
        echo '</div>';
        return false;
    }
}

// Exécution du script HTML
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Direct des Quiz</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1, h2, h3 { color: #333; }
        .section { margin-bottom: 30px; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }
        table { border-collapse: collapse; width: 100%; }
        th { background-color: #f2f2f2; }
        button { padding: 8px 12px; background-color: #4CAF50; color: white; border: none; cursor: pointer; }
        button:hover { background-color: #45a049; }
        .error { color: red; }
        .success { color: green; }
    </style>
</head>
<body>
    <h1>Test Direct des Quiz</h1>
    
    <?php
    try {
        // Se connecter à la base de données (on utilise la variable $db déjà définie dans config/database.php)
        // $db est déjà créé dans le fichier config/database.php
        
        echo '<div class="section">';
        echo '<h2>Vérification de la structure de la base de données</h2>';
        
        // Lister les tables existantes
        $tables = executeQuery($db, "SHOW TABLES");
        $tableList = [];
        foreach ($tables as $table) {
            $tableList[] = array_values($table)[0];
        }
        
        echo '<p>Tables existantes: ' . implode(', ', $tableList) . '</p>';
        
        // Vérifier si les tables nécessaires existent
        $missingTables = [];
        $requiredTables = ['quizzes', 'quiz_questions', 'quiz_question', 'quiz_question_answer', 'quiz_users'];
        
        foreach ($requiredTables as $table) {
            if (!in_array($table, $tableList)) {
                $missingTables[] = $table;
            }
        }
        
        if (count($missingTables) > 0) {
            echo '<p class="error">Tables manquantes: ' . implode(', ', $missingTables) . '</p>';
        } else {
            echo '<p class="success">Toutes les tables requises existent!</p>';
        }
        echo '</div>';
        
        // Si les tables quizzes ou quiz_questions n'existent pas, proposer de les créer
        if (in_array('quizzes', $missingTables) || in_array('quiz_questions', $missingTables)) {
            echo '<div class="section">';
            echo '<h2>Création des tables manquantes</h2>';
            
            if (isset($_POST['create_tables'])) {
                // Création de la table quizzes si elle n'existe pas
                if (in_array('quizzes', $missingTables)) {
                    try {
                        $db->exec("
                            CREATE TABLE IF NOT EXISTS quizzes (
                                id INT AUTO_INCREMENT PRIMARY KEY,
                                title VARCHAR(255) NOT NULL,
                                description TEXT,
                                created_by INT NOT NULL,
                                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                                FOREIGN KEY (created_by) REFERENCES quiz_users(id) ON DELETE CASCADE
                            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
                        ");
                        echo '<p class="success">Table quizzes créée avec succès!</p>';
                    } catch (PDOException $e) {
        echo '<p class="error">Erreur lors de la création de la table quizzes: ' . htmlspecialchars($e->getMessage()) . '</p>';
    }
}

// Création de la table quiz_questions si elle n'existe pas
                if (in_array('quiz_questions', $missingTables)) {
                    try {
                        $db->exec("
                            CREATE TABLE IF NOT EXISTS quiz_questions (
                                id INT AUTO_INCREMENT PRIMARY KEY,
                                quiz_id INT NOT NULL,
                                question_id INT NOT NULL,
                                question_order INT NOT NULL DEFAULT 0,
                                UNIQUE KEY unique_quiz_question (quiz_id, question_id),
                                FOREIGN KEY (quiz_id) REFERENCES quizzes(id) ON DELETE CASCADE,
                                FOREIGN KEY (question_id) REFERENCES quiz_question(Id_question) ON DELETE CASCADE
                            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
                        ");
                        echo '<p class="success">Table quiz_questions créée avec succès!</p>';
                    } catch (PDOException $e) {
                        echo '<p class="error">Erreur lors de la création de la table quiz_questions: ' . htmlspecialchars($e->getMessage()) . '</p>';
                    }
                }
            } else {
                echo '<form method="post">';
                echo '<p>Cliquez sur le bouton ci-dessous pour créer les tables manquantes:</p>';
                echo '<button type="submit" name="create_tables">Créer les tables</button>';
                echo '</form>';
            }
            
            echo '</div>';
        }
        
        // Tester la création d'un quiz
        echo '<div class="section">';
        echo '<h2>Test de création d\'un quiz</h2>';
        
        if (isset($_POST['create_quiz'])) {
            $title = $_POST['title'];
            $description = $_POST['description'];
            $userId = $_POST['user_id'];
            
            $quizId = createQuizDirectly($db, $title, $description, $userId);
            
            if ($quizId) {
                // Récupérer quelques questions pour les ajouter au quiz
                $questions = executeQuery($db, "SELECT Id_question, text FROM quiz_question LIMIT 3");
                
                if (count($questions) > 0) {
                    foreach ($questions as $index => $question) {
                        addQuestionToQuiz($db, $quizId, $question['Id_question'], $index + 1);
                    }
                } else {
                    echo '<p class="error">Aucune question disponible à ajouter au quiz.</p>';
                }
            }
        } else {
            // Récupérer les utilisateurs disponibles
            $users = executeQuery($db, "SELECT id, name, first_name, email FROM quiz_users");
            
            if (count($users) > 0) {
    echo '<form method="post">';
                echo '<div>';
                echo '<label for="title">Titre du quiz:</label><br>';
                echo '<input type="text" id="title" name="title" required value="Quiz de test">';
                echo '</div><br>';
                
                echo '<div>';
                echo '<label for="description">Description:</label><br>';
                echo '<textarea id="description" name="description" rows="3">Ce quiz est créé pour tester la fonctionnalité de création de quiz.</textarea>';
                echo '</div><br>';
                
                echo '<div>';
                echo '<label for="user_id">Créé par:</label><br>';
                echo '<select id="user_id" name="user_id" required>';
                foreach ($users as $user) {
                    echo '<option value="' . $user['id'] . '">' . $user['first_name'] . ' ' . $user['name'] . ' (' . $user['email'] . ')</option>';
                }
                echo '</select>';
                echo '</div><br>';
                
                echo '<button type="submit" name="create_quiz">Créer le quiz</button>';
                echo '</form>';
} else {
    echo '<p class="error">Aucun utilisateur disponible pour créer un quiz.</p>';
            }
        }
        
        echo '</div>';
        
        // Afficher les quiz existants
        if (!in_array('quizzes', $missingTables)) {
        echo '<div class="section">';
            echo '<h2>Quiz existants</h2>';
            
            $quizzes = executeQuery($db, "
                SELECT q.*, u.name, u.first_name
                FROM quizzes q
                LEFT JOIN quiz_users u ON q.created_by = u.id
                ORDER BY q.created_at DESC
            ");
            
            if (count($quizzes) > 0) {
                printTable($quizzes, "Liste des quiz");
                
                // Afficher les questions pour chaque quiz
                echo '<h3>Questions par quiz</h3>';
                foreach ($quizzes as $quiz) {
                    $quizQuestions = executeQuery($db, "
                        SELECT qq.question_order, q.text as question_text, q.Id_question, 
                               qc.title as category, qd.name as difficulty
                        FROM quiz_questions qq
                        JOIN quiz_question q ON qq.question_id = q.Id_question
                        LEFT JOIN quiz_question_category_details qc ON q.Id_question_category = qc.Id_question_category
                        LEFT JOIN quiz_question_difficulte qd ON q.Id_question_difficulte = qd.Id_question_difficulte
                        WHERE qq.quiz_id = ?
                        ORDER BY qq.question_order
                    ", [$quiz['id']]);
                    
                    if (count($quizQuestions) > 0) {
                        printTable($quizQuestions, "Questions du quiz: " . htmlspecialchars($quiz['title']));
                    } else {
    echo "<p>Aucune question pour le quiz: " . htmlspecialchars($quiz['title']) . "</p>";
                    }
                }
            } else {
                echo '<p>Aucun quiz existant.</p>';
            }
            
            echo '</div>';
        }
        
    } catch (PDOException $e) {
        echo '<div class="error">';
    echo '<h2>Erreur!</h2>';
        echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
    echo '</div>';
}
?>
</body>
</html>