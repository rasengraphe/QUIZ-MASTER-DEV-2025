<?php
// core/Controller.php
require_once __DIR__ . '/../helpers/SecurityHelper.php';

class Controller {
    protected $db;

    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Vérifie si l'utilisateur est connecté
     */
    protected function checkAuth() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?action=login');
            exit();
        }
    }

    /**
     * Nettoie les entrées utilisateur
     */
    protected function sanitizeInput($data) {
        return SecurityHelper::cleanInput($data);
    }

    protected function view($view, $data = []) {
        extract($data);
        include __DIR__ . '/../views/' . $view . '.php';
    }

    //  Fonction pour échapper les sorties (XSS protection)
    protected function escape($string) {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }

    //  Fonction pour rediriger
    protected function redirect($url) {
        header("Location: " . $url);
        exit();
    }

    // ... Autres méthodes communes aux contrôleurs
}
?>