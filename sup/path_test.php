<?php
// Afficher les différents chemins
echo "Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "<br>";
echo "Script Filename: " . $_SERVER['SCRIPT_FILENAME'] . "<br>";
echo "Current Directory: " . getcwd() . "<br>";
echo "Directory of this file: " . __DIR__ . "<br>";

// Essayer de créer le dossier avec un chemin absolu
$absolutePath = __DIR__ . '/uploads/questions';
echo "Tentative de création du dossier avec chemin absolu: $absolutePath<br>";

if (!is_dir($absolutePath)) {
    if (mkdir($absolutePath, 0777, true)) {
        echo "Dossier créé avec succès.<br>";
    } else {
        echo "Échec de la création du dossier.<br>";
        echo "Erreur: " . error_get_last()['message'] . "<br>";
    }
} else {
    echo "Le dossier existe déjà.<br>";
}

// Test d'écriture dans ce dossier
$testFile = $absolutePath . '/test_image.txt';
if (file_put_contents($testFile, 'Ceci est un test d\'écriture pour simuler une image')) {
    echo "Fichier test créé avec succès: " . $testFile . "<br>";
} else {
    echo "Échec de la création du fichier test.<br>";
    echo "Erreur: " . error_get_last()['message'] . "<br>";
}

// Vérifier les permissions du dossier
echo "Permissions du dossier: " . substr(sprintf('%o', fileperms($absolutePath)), -4) . "<br>";

// Si on est sur Windows, vérifier les utilisateurs
if (function_exists('posix_getpwuid')) {
    $owner = posix_getpwuid(fileowner($absolutePath));
    echo "Propriétaire du dossier: " . $owner['name'] . "<br>";
}

// Tester la fonctionnalité d'upload
echo "<form action='' method='post' enctype='multipart/form-data'>";
echo "<input type='file' name='test_file'><br>";
echo "<input type='submit' name='submit' value='Tester l\'upload'>";
echo "</form>";

if (isset($_POST['submit'])) {
    if (isset($_FILES['test_file']) && $_FILES['test_file']['error'] === 0) {
        $uploadedFile = $absolutePath . '/' . basename($_FILES['test_file']['name']);
        if (move_uploaded_file($_FILES['test_file']['tmp_name'], $uploadedFile)) {
            echo "Fichier uploadé avec succès: " . $uploadedFile . "<br>";
        } else {
            echo "Échec de l'upload.<br>";
            echo "Erreur: " . error_get_last()['message'] . "<br>";
        }
    } else {
        echo "Erreur lors de l'upload: " . $_FILES['test_file']['error'] . "<br>";
    }
}
?>