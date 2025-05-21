<?php
// Script de test pour le téléchargement de fichiers

// Activer l'affichage des erreurs pour le débogage
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Créer le répertoire de téléchargement s'il n'existe pas
$uploadDir = 'uploads/test/';
$absolutePath = __DIR__ . '/' . $uploadDir;

if (!is_dir($absolutePath)) {
    mkdir($absolutePath, 0777, true);
    echo "<p>Répertoire créé: $absolutePath</p>";
}

// Information sur le serveur et la configuration PHP
echo "<h1>Test de téléchargement de fichiers</h1>";
echo "<h2>1. Informations serveur</h2>";
echo "<pre>";
echo "PHP version: " . phpversion() . "\n";
echo "OS: " . PHP_OS . "\n";
echo "Serveur: " . $_SERVER['SERVER_SOFTWARE'] . "\n";
echo "upload_max_filesize = " . ini_get('upload_max_filesize') . "\n";
echo "post_max_size = " . ini_get('post_max_size') . "\n";
echo "max_file_uploads = " . ini_get('max_file_uploads') . "\n";
echo "</pre>";

// Test de permissions
echo "<h2>2. Vérification des permissions</h2>";
echo "<p>Répertoire de test: <code>$uploadDir</code></p>";

if (is_writable($uploadDir)) {
    echo "<p style='color: green;'>✓ Le répertoire est accessible en écriture</p>";
} else {
    echo "<p style='color: red;'>✗ Le répertoire n'est PAS accessible en écriture</p>";
}

// Traitement du téléchargement
echo "<h2>3. Formulaire de test</h2>";
echo "<form action='' method='post' enctype='multipart/form-data'>";
echo "  <div>";
echo "    <label for='test_image'>Sélectionner une image:</label>";
echo "    <input type='file' name='test_image' id='test_image' accept='image/*'>";
echo "  </div>";
echo "  <div style='margin-top: 10px;'>";
echo "    <button type='submit' style='padding: 5px 15px;'>Télécharger</button>";
echo "  </div>";
echo "</form>";

// Traiter le téléchargement si un fichier a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['test_image'])) {
    echo "<h2>4. Résultats du téléchargement</h2>";
    
    echo "<h3>Données du fichier reçues:</h3>";
    echo "<pre>";
    print_r($_FILES['test_image']);
    echo "</pre>";
    
    // Vérifier s'il y a des erreurs
    if ($_FILES['test_image']['error'] !== UPLOAD_ERR_OK) {
        echo "<p style='color: red;'>Erreur lors du téléchargement (code " . $_FILES['test_image']['error'] . "):</p>";
        
        switch ($_FILES['test_image']['error']) {
            case UPLOAD_ERR_INI_SIZE:
                echo "<p>Le fichier dépasse la taille autorisée par PHP.ini</p>";
                break;
            case UPLOAD_ERR_FORM_SIZE:
                echo "<p>Le fichier dépasse la taille autorisée dans le formulaire</p>";
                break;
            case UPLOAD_ERR_PARTIAL:
                echo "<p>Le fichier n'a été que partiellement téléchargé</p>";
                break;
            case UPLOAD_ERR_NO_FILE:
                echo "<p>Aucun fichier n'a été téléchargé</p>";
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                echo "<p>Absence d'un dossier temporaire</p>";
                break;
            case UPLOAD_ERR_CANT_WRITE:
                echo "<p>Échec de l'écriture du fichier sur le disque</p>";
                break;
            default:
                echo "<p>Erreur inconnue</p>";
        }
    } else {
        // Essayer de déplacer le fichier
        $fileName = time() . '_' . basename($_FILES['test_image']['name']);
        $uploadFilePath = $uploadDir . $fileName;
        
        echo "<h3>Tentative de déplacement du fichier:</h3>";
        echo "<p>De: <code>" . $_FILES['test_image']['tmp_name'] . "</code></p>";
        echo "<p>Vers: <code>$uploadFilePath</code></p>";
        
        if (move_uploaded_file($_FILES['test_image']['tmp_name'], $uploadFilePath)) {
            echo "<p style='color: green;'>✓ Succès! Le fichier a été téléchargé.</p>";
            echo "<p>URL du fichier: <a href='$uploadFilePath' target='_blank'>$uploadFilePath</a></p>";
            echo "<p>Image téléchargée:</p>";
            echo "<img src='$uploadFilePath' style='max-width: 300px; border: 1px solid #ccc;'>";
        } else {
            echo "<p style='color: red;'>✗ Échec du déplacement du fichier.</p>";
            
            // Afficher plus d'informations sur l'erreur
            echo "<p>Dernière erreur: " . error_get_last()['message'] . "</p>";
            
            // Vérifier si le fichier temporaire existe
            if (file_exists($_FILES['test_image']['tmp_name'])) {
                echo "<p>Le fichier temporaire existe.</p>";
            } else {
                echo "<p>Le fichier temporaire n'existe pas.</p>";
            }
            
            // Vérifier les permissions du répertoire cible
            echo "<p>Permissions du répertoire: " . substr(sprintf('%o', fileperms($uploadDir)), -4) . "</p>";
        }
    }
}
?>
