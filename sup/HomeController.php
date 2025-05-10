<?php
// quiz_project/controllers/HomeController.php
class HomeController extends Controller {
    public function __construct($db) {
        parent::__construct($db);
    }

    public function index() {
        // Afficher la page d'accueil
        include 'views/home.php';
    }

    // Ajoutez cette méthode qui manque
    public function home() {
        // Affiche la page d'accueil
        include 'views/home.php';
    }

    // ... Autres méthodes pour la page d'accueil
}
?>