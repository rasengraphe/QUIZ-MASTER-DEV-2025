<?php
session_start(); // Initialisation de la session
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'config/database.php';

// Test de la connexion à la base de données
try {
    $stmt = $db->query("SELECT 1");
    echo "Connexion à la base de données réussie<br>";
} catch (PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage() . "<br>";
}

// Création d'une variable de session test
$_SESSION['test'] = 'Test de session';

// Affichage des variables de session
echo "<h3>Variables de session :</h3>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

// Affichage des erreurs PHP
echo "<h3>Configuration des erreurs PHP :</h3>";
echo "display_errors = " . ini_get('display_errors') . "<br>";
echo "error_reporting = " . ini_get('error_reporting') . "<br>";
echo "log_errors = " . ini_get('log_errors') . "<br>";
echo "error_log = " . ini_get('error_log') . "<br>";

// Affichage des informations sur l'environnement PHP
echo "<h3>Informations sur l'environnement :</h3>";
echo "Version PHP : " . phpversion() . "<br>";
echo "Chemin du fichier php.ini : " . php_ini_loaded_file() . "<br>";
echo "Extensions chargées : <pre>" . implode(", ", get_loaded_extensions()) . "</pre>";
?>