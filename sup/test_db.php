<?php
require_once 'config/database.php';

if (isset($db)) {
    echo "Connexion à la base de données réussie!";
} else {
    echo "Échec de la connexion à la base de données.";
}
?>