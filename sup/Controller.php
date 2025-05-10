<?php
// filepath: c:\wamp64\www\QUIZ-MASTER-DEV-2025\controllers\Controller.php

class Controller {
    protected $db;
    
    public function __construct($db = null) {
        $this->db = $db;
    }
    
    protected function view($name, $data = []) {
        // Rendre les données disponibles pour la vue
        extract($data);
        
        // Inclure le fichier de vue
        require_once "views/$name.php";
    }
}
?>