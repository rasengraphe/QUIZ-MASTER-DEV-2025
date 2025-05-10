<?php
// quiz_project/core/Controller.php
class Controller {
    protected $db;

    public function __construct($db) {
        $this->db = $db;
    }

    protected function view($view, $data = []) {
        extract($data);
        include 'views/' . $view . '.php';
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