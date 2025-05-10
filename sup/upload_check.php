<?php
// Activer l'affichage des erreurs
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Chemins à vérifier
$basePath = realpath(__DIR__);
$uploadsPath = $basePath . '/uploads';
$questionsPath = $basePath . '/uploads/questions';

echo "<h1>Vérification des dossiers d'upload</h1>";

// Vérifier le dossier de base
echo "<h2>Dossier de base du projet</h2>";
echo "Chemin: " . $basePath . "<br>";
echo "Existe: " . (is_dir($basePath) ? "Oui" : "Non") . "<br>";
echo "Permissions: " . substr(sprintf('%o', fileperms($basePath)), -4) . "<br>";

// Vérifier le dossier uploads
echo "<h2>Dossier uploads</h2>";
echo "Chemin: " . $uploadsPath . "<br>";
if (!is_dir($uploadsPath)) {
    echo "N'existe pas - tentative de création...<br>";
    if (mkdir($uploadsPath, 0777)) {
        echo "Dossier créé avec succès<br>";
    } else {
        echo "Échec de la création du dossier<br>";
        echo "Erreur: " . print_r(error_get_last(), true) . "<br>";
    }
} else {
    echo "Existe: Oui<br>";
    echo "Est accessible en écriture: " . (is_writable($uploadsPath) ? "Oui" : "Non") . "<br>";
    echo "Permissions: " . substr(sprintf('%o', fileperms($uploadsPath)), -4) . "<br>";
}

// Vérifier le dossier questions
echo "<h2>Dossier uploads/questions</h2>";
echo "Chemin: " . $questionsPath . "<br>";
if (!is_dir($questionsPath)) {
    echo "N'existe pas - tentative de création...<br>";
    if (mkdir($questionsPath, 0777, true)) {
        echo "Dossier créé avec succès<br>";
    } else {
        echo "Échec de la création du dossier<br>";
        echo "Erreur: " . print_r(error_get_last(), true) . "<br>";
    }
} else {
    echo "Existe: Oui<br>";
    echo "Est accessible en écriture: " . (is_writable($questionsPath) ? "Oui" : "Non") . "<br>";
    echo "Permissions: " . substr(sprintf('%o', fileperms($questionsPath)), -4) . "<br>";
}

// Test d'upload
echo "<h2>Test d'upload</h2>";
echo "<form method='post' enctype='multipart/form-data'>";
echo "<input type='file' name='test_file'><br><br>";
echo "<input type='submit' name='upload' value='Tester l&#39;upload'>";
echo "</form>";

if (isset($_POST['upload'])) {
    if (isset($_FILES['test_file']) && $_FILES['test_file']['error'] === 0) {
        $testFile = $questionsPath . '/' . basename($_FILES['test_file']['name']);
        
        echo "Tentative d'upload vers: " . $testFile . "<br>";
        
        if (move_uploaded_file($_FILES['test_file']['tmp_name'], $testFile)) {
            echo "<p style='color:green'>Succès: Le fichier a été uploadé correctement</p>";
            echo "<img src='uploads/questions/" . basename($_FILES['test_file']['name']) . "' style='max-width:300px'><br>";
        } else {
            echo "<p style='color:red'>Échec: Le fichier n'a pas pu être uploadé</p>";
            echo "Erreur: " . print_r(error_get_last(), true);
        }
    } else {
        echo "<p style='color:red'>Erreur: " . $_FILES['test_file']['error'] . "</p>";
    }
}
?>