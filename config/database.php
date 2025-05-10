<?php
// Configuration de la base de données (à déplacer dans config/database.php pour plus de sécurité)
$host = 'localhost';
$dbname = 'quizdb';
$username = 'root';
$password = '';

try {
    error_log("Debug DB: Tentative de connexion à la base de données");
    error_log("Debug DB: Host=$host, Database=$dbname, User=$username");
    
    $db = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
    error_log("Debug DB: Connexion à la base de données réussie");
} catch(PDOException $e) {
    error_log("Debug DB: ERREUR de connexion à la base de données - " . $e->getMessage());
    die("Erreur de connexion à la base de données");
}
?>